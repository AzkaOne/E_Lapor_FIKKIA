<?php

namespace App\Services;

use App\Models\SubDepartment;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use ZipArchive;
use SimpleXMLElement;

class UserImportService
{
    /**
     * Parse and import users from CSV or XLSX file.
     *
     * @param UploadedFile|string $file
     * @param int|null $forcedSubDeptId
     * @param string|null $forcedRole
     * @return array{saved_count: int, failed_count: int, failed_rows: array, total_rows: int}
     */
    public function import(UploadedFile|string $file, ?int $forcedSubDeptId = null, ?string $forcedRole = null): array
    {
        $filePath = $file instanceof UploadedFile ? $file->getRealPath() : $file;
        $extension = strtolower(pathinfo($file instanceof UploadedFile ? $file->getClientOriginalName() : $file, PATHINFO_EXTENSION));

        $rawRows = [];
        if ($extension === 'xlsx' || $extension === 'xls') {
            $rawRows = $this->parseXlsx($filePath);
            if (empty($rawRows)) {
                // Fallback attempt as CSV in case it's a CSV with .xls/.xlsx extension
                $rawRows = $this->parseCsv($filePath);
            }
        } else {
            $rawRows = $this->parseCsv($filePath);
        }

        if (empty($rawRows)) {
            return [
                'saved_count' => 0,
                'failed_count' => 0,
                'failed_rows' => [['row' => 0, 'name' => '-', 'reason' => 'Berkas kosong atau tidak dapat dibaca.']],
                'total_rows' => 0,
            ];
        }

        return $this->processRows($rawRows, $forcedSubDeptId, $forcedRole);
    }

    /**
     * Parse CSV file with auto-detected delimiter.
     */
    protected function parseCsv(string $path): array
    {
        $handle = fopen($path, 'r');
        if (!$handle) {
            return [];
        }

        // Read first line to detect delimiter
        $firstLine = fgets($handle);
        if ($firstLine === false) {
            fclose($handle);
            return [];
        }

        // Remove UTF-8 BOM if present
        $firstLine = preg_replace('/^\xEF\xBB\xBF/', '', $firstLine);

        $delimiters = [',', ';', "\t", '|'];
        $bestDelimiter = ',';
        $maxCount = 0;
        foreach ($delimiters as $delim) {
            $count = substr_count($firstLine, $delim);
            if ($count > $maxCount) {
                $maxCount = $count;
                $bestDelimiter = $delim;
            }
        }

        rewind($handle);
        $rows = [];
        while (($data = fgetcsv($handle, 0, $bestDelimiter)) !== false) {
            // Trim BOM from first cell if first row
            if (empty($rows) && isset($data[0])) {
                $data[0] = preg_replace('/^\xEF\xBB\xBF/', '', $data[0]);
            }
            $rows[] = $data;
        }
        fclose($handle);

        return $rows;
    }

    /**
     * Parse XLSX file natively using ZipArchive and SimpleXMLElement.
     */
    protected function parseXlsx(string $path): array
    {
        if (!class_exists('ZipArchive')) {
            return [];
        }

        $zip = new ZipArchive();
        if ($zip->open($path) !== true) {
            return [];
        }

        // 1. Read Shared Strings
        $sharedStrings = [];
        $stringsXml = $zip->getFromName('xl/sharedStrings.xml');
        if ($stringsXml) {
            $xml = @simplexml_load_string($stringsXml);
            if ($xml && isset($xml->si)) {
                foreach ($xml->si as $val) {
                    if (isset($val->t)) {
                        $sharedStrings[] = (string)$val->t;
                    } elseif (isset($val->r)) {
                        $text = '';
                        foreach ($val->r as $r) {
                            $text .= (string)$r->t;
                        }
                        $sharedStrings[] = $text;
                    } else {
                        $sharedStrings[] = '';
                    }
                }
            }
        }

        // 2. Read Worksheet (Sheet 1)
        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();

        if (!$sheetXml) {
            return [];
        }

        $sheet = @simplexml_load_string($sheetXml);
        if (!$sheet || !isset($sheet->sheetData->row)) {
            return [];
        }

        $rows = [];
        foreach ($sheet->sheetData->row as $r) {
            $rowValues = [];
            $lastColIndex = -1;

            foreach ($r->c as $cell) {
                $coord = (string)$cell['r'];
                preg_match('/([A-Z]+)(\d+)/', $coord, $matches);
                $colLetters = $matches[1] ?? 'A';
                $colIndex = $this->letterToColumnIndex($colLetters);

                // Fill gaps if any
                while ($lastColIndex + 1 < $colIndex) {
                    $rowValues[] = '';
                    $lastColIndex++;
                }

                $type = (string)$cell['t'];
                $val = isset($cell->v) ? (string)$cell->v : '';

                if ($type === 's') {
                    $strIndex = (int)$val;
                    $cellValue = $sharedStrings[$strIndex] ?? '';
                } elseif ($type === 'inlineStr' && isset($cell->is->t)) {
                    $cellValue = (string)$cell->is->t;
                } else {
                    $cellValue = $val;
                }

                $rowValues[] = trim($cellValue);
                $lastColIndex = $colIndex;
            }

            $rows[] = $rowValues;
        }

        return $rows;
    }

    /**
     * Convert Excel column letters (A, B, ..., Z, AA) to 0-based index.
     */
    protected function letterToColumnIndex(string $letters): int
    {
        $letters = strtoupper($letters);
        $result = 0;
        $len = strlen($letters);
        for ($i = 0; $i < $len; $i++) {
            $result = $result * 26 + (ord($letters[$i]) - 64);
        }
        return $result - 1;
    }

    /**
     * Process raw tabular rows into database records with validation.
     */
    protected function processRows(array $rows, ?int $forcedSubDeptId = null, ?string $forcedRole = null): array
    {
        if (count($rows) < 2) {
            return [
                'saved_count' => 0,
                'failed_count' => 0,
                'failed_rows' => [['row' => 1, 'name' => '-', 'reason' => 'Hanya ada baris header tanpa data pegawai.']],
                'total_rows' => 0,
            ];
        }

        // Header mapping
        $headerRow = array_map(function ($col) {
            $c = strtolower(trim((string)$col));
            $c = str_replace([' ', '_', '-'], '', $c);
            return $c;
        }, $rows[0]);

        $columnMap = [
            'name' => null,
            'email' => null,
            'identity_number' => null,
            'role' => null,
            'sub_department' => null,
            'phone_number' => null,
            'position' => null,
            'password' => null,
        ];

        foreach ($headerRow as $idx => $header) {
            if (in_array($header, ['nama', 'name', 'namalengkap'])) {
                $columnMap['name'] = $idx;
            } elseif (in_array($header, ['email', 'surel'])) {
                $columnMap['email'] = $idx;
            } elseif (in_array($header, ['nip', 'nik', 'nipnik', 'identitynumber', 'nomoridentitas'])) {
                $columnMap['identity_number'] = $idx;
            } elseif (in_array($header, ['role', 'peran', 'jabatanrole'])) {
                $columnMap['role'] = $idx;
            } elseif (in_array($header, ['bidang', 'subdepartment', 'unit', 'subbagian', 'bagian'])) {
                $columnMap['sub_department'] = $idx;
            } elseif (in_array($header, ['notelp', 'nohp', 'telepon', 'phonenumber', 'whatsapp', 'wa'])) {
                $columnMap['phone_number'] = $idx;
            } elseif (in_array($header, ['jabatan', 'posisi', 'position'])) {
                $columnMap['position'] = $idx;
            } elseif (in_array($header, ['password', 'katasandi'])) {
                $columnMap['password'] = $idx;
            }
        }

        // Cache sub_departments
        $subDepartments = SubDepartment::all();
        $subDeptLookup = [];
        foreach ($subDepartments as $sd) {
            $subDeptLookup[strtolower(trim($sd->name))] = $sd->id;
            $subDeptLookup[strtolower(trim($sd->code))] = $sd->id;
            // Common aliases
            if (str_contains(strtolower($sd->name), 'sarana')) {
                $subDeptLookup['sarpras'] = $sd->id;
            }
            if (str_contains(strtolower($sd->name), 'keuangan')) {
                $subDeptLookup['keuangan'] = $sd->id;
                $subDeptLookup['keuangan & sdm'] = $sd->id;
            }
            if (str_contains(strtolower($sd->name), 'akademik')) {
                $subDeptLookup['akademik'] = $sd->id;
            }
            if (str_contains(strtolower($sd->name), 'dekanat')) {
                $subDeptLookup['dekanat'] = $sd->id;
            }
        }

        $savedCount = 0;
        $failedCount = 0;
        $failedRows = [];
        $processedEmails = [];

        for ($i = 1; $i < count($rows); $i++) {
            $rowNumber = $i + 1; // 1-based index in Excel/CSV
            $row = $rows[$i];

            // Check if entire row is empty
            $nonEmptyCells = array_filter($row, fn($c) => trim((string)$c) !== '');
            if (empty($nonEmptyCells)) {
                continue;
            }

            $name = $columnMap['name'] !== null && isset($row[$columnMap['name']]) ? trim((string)$row[$columnMap['name']]) : '';
            $email = $columnMap['email'] !== null && isset($row[$columnMap['email']]) ? strtolower(trim((string)$row[$columnMap['email']])) : '';
            $nip = $columnMap['identity_number'] !== null && isset($row[$columnMap['identity_number']]) ? trim((string)$row[$columnMap['identity_number']]) : '';
            $role = $columnMap['role'] !== null && isset($row[$columnMap['role']]) ? strtolower(trim((string)$row[$columnMap['role']])) : '';
            $bidang = $columnMap['sub_department'] !== null && isset($row[$columnMap['sub_department']]) ? strtolower(trim((string)$row[$columnMap['sub_department']])) : '';
            $phone = $columnMap['phone_number'] !== null && isset($row[$columnMap['phone_number']]) ? trim((string)$row[$columnMap['phone_number']]) : null;
            $position = $columnMap['position'] !== null && isset($row[$columnMap['position']]) ? trim((string)$row[$columnMap['position']]) : null;
            $password = $columnMap['password'] !== null && isset($row[$columnMap['password']]) ? trim((string)$row[$columnMap['password']]) : '';

            $displayName = $name ?: "Baris {$rowNumber}";

            // 1. Validasi Nama
            if (empty($name)) {
                $failedCount++;
                $failedRows[] = [
                    'row' => $rowNumber,
                    'name' => $displayName,
                    'reason' => 'Nama lengkap pegawai tidak boleh kosong.',
                ];
                continue;
            }

            // 2. Validasi NIP / NIK
            if (empty($nip)) {
                $failedCount++;
                $failedRows[] = [
                    'row' => $rowNumber,
                    'name' => $displayName,
                    'reason' => 'NIP/NIK pegawai tidak boleh kosong.',
                ];
                continue;
            }

            // 3. Validasi Email
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $failedCount++;
                $failedRows[] = [
                    'row' => $rowNumber,
                    'name' => $displayName,
                    'reason' => 'Format email tidak valid atau kosong.',
                ];
                continue;
            }

            // Cek duplikasi email dalam batch atau di database
            if (in_array($email, $processedEmails) || User::where('email', $email)->exists()) {
                $failedCount++;
                $failedRows[] = [
                    'row' => $rowNumber,
                    'name' => $displayName,
                    'reason' => "Email {$email} sudah terdaftar dalam sistem.",
                ];
                continue;
            }

            // 4. Validasi Role
            if ($forcedRole !== null) {
                if (!empty($role) && $role !== strtolower($forcedRole)) {
                    $failedCount++;
                    $failedRows[] = [
                        'row' => $rowNumber,
                        'name' => $displayName,
                        'reason' => "Role tidak diizinkan. Hanya diperbolehkan role '{$forcedRole}'.",
                    ];
                    continue;
                }
                $role = $forcedRole;
            } else {
                if (empty($role) || !in_array($role, ['kasubag', 'petugas'])) {
                    $failedCount++;
                    $failedRows[] = [
                        'row' => $rowNumber,
                        'name' => $displayName,
                        'reason' => "Role '{$role}' tidak valid. Hanya diperbolehkan 'kasubag' atau 'petugas'.",
                    ];
                    continue;
                }
            }

            // 5. Validasi Bidang / Sub Department
            $subDeptId = null;
            if ($forcedSubDeptId !== null) {
                if (!empty($bidang)) {
                    $matchedId = $subDeptLookup[$bidang] ?? null;
                    if (!$matchedId) {
                        foreach ($subDeptLookup as $key => $id) {
                            if (str_contains($bidang, $key) || str_contains($key, $bidang)) {
                                $matchedId = $id;
                                break;
                            }
                        }
                    }
                    if ($matchedId && $matchedId !== $forcedSubDeptId) {
                        $failedCount++;
                        $failedRows[] = [
                            'row' => $rowNumber,
                            'name' => $displayName,
                            'reason' => "Bidang tidak sesuai dengan unit kerja Anda.",
                        ];
                        continue;
                    }
                }
                $subDeptId = $forcedSubDeptId;
            } else {
                if (!empty($bidang) && isset($subDeptLookup[$bidang])) {
                    $subDeptId = $subDeptLookup[$bidang];
                } else {
                    // Partial match
                    foreach ($subDeptLookup as $key => $id) {
                        if (str_contains($bidang, $key) || str_contains($key, $bidang)) {
                            $subDeptId = $id;
                            break;
                        }
                    }
                }

                if (!$subDeptId) {
                    $failedCount++;
                    $failedRows[] = [
                        'row' => $rowNumber,
                        'name' => $displayName,
                        'reason' => "Bidang '{$bidang}' tidak ditemukan dalam sistem.",
                    ];
                    continue;
                }
            }

            // 6. Penentuan Password (default NIP/NIK jika kosong)
            $plainPassword = !empty($password) ? $password : $nip;

            // 7. Simpan Pegawai
            User::create([
                'name' => $name,
                'email' => $email,
                'identity_number' => $nip,
                'role' => $role,
                'sub_department_id' => $subDeptId,
                'phone_number' => $phone,
                'position' => $position ?: ($role === 'kasubag' ? 'Kasubag' : 'Petugas'),
                'password' => Hash::make($plainPassword),
                'avatar' => 'profil_men.webp',
            ]);

            $processedEmails[] = $email;
            $savedCount++;
        }

        return [
            'saved_count' => $savedCount,
            'failed_count' => $failedCount,
            'failed_rows' => $failedRows,
            'total_rows' => $savedCount + $failedCount,
        ];
    }
}
