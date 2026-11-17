@php
    $currentUser = auth()->user();
    $currentAvatar = $currentUser?->avatar ?: 'profil_men.webp';
    $isPelapor = $currentUser && $currentUser->isPelapor();
    $roleName = match($currentUser?->role) {
        'kabag' => 'Kepala Bagian (Kabag)',
        'kasubag' => 'Kasubag',
        'petugas' => 'Petugas',
        default => 'Pelapor',
    };
    $subDeptName = $currentUser?->bidang ?? 'Semua Bidang';
    $userPos = strtolower($currentUser?->position ?? '');
@endphp

<div id="profile_popover_container" class="relative inline-block text-left" data-component="profil_popover">
    <!-- Trigger Button: Clean circle avatar without outer pill border, name/jabatan, arrow, or green dot -->
    <button type="button" 
            id="profilePopoverTrigger" 
            class="w-8 h-8 rounded-full overflow-hidden border border-teal-300 flex items-center justify-center shrink-0 shadow-xs hover:ring-2 hover:ring-teal-200 focus:outline-none transition cursor-pointer"
            aria-expanded="false" 
            aria-haspopup="true"
            title="Profil Pengguna">
        <img id="triggerAvatarImg" 
             src="{{ asset('assets/' . $currentAvatar) }}" 
             alt="Foto Profil" 
             class="w-full h-full object-cover">
    </button>

    <!-- Popover Card Dropdown -->
    <div id="profilePopoverDropdown" 
         class="hidden absolute right-0 top-full mt-2.5 w-[330px] sm:w-[390px] bg-white rounded-2xl shadow-2xl border border-slate-200/80 z-50 overflow-hidden text-slate-800 transition-all duration-200 origin-top-right transform scale-95 opacity-0">
        
        <!-- Popover Top Card (Centered Avatar, Name, Email as in design reference) -->
        <div class="bg-[#F8FAFC] border-b border-slate-200/70 p-5 text-center relative">
            <!-- Close Button -->
            <button type="button" 
                    id="closeProfilePopoverBtn" 
                    class="absolute top-3 right-3 p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-200/60 transition cursor-pointer"
                    title="Tutup">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Centered Avatar (Click to toggle avatar choices) -->
            <div class="relative inline-block">
                <button type="button" 
                        id="centerAvatarToggleBtn" 
                        class="relative w-16 h-16 sm:w-20 sm:h-20 rounded-full overflow-hidden mx-auto shadow-md border-2 border-white ring-2 ring-teal-500/30 cursor-pointer transition transform hover:scale-105 group focus:outline-none"
                        title="Klik untuk memilih foto profil">
                    <img id="centerAvatarImg" 
                         src="{{ asset('assets/' . $currentAvatar) }}" 
                         alt="{{ $currentUser->name ?? 'User' }}" 
                         class="w-full h-full object-cover">
                    <!-- Hover overlay hint -->
                    <div class="absolute inset-0 bg-black/35 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white">
                        <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="text-[9px] font-bold">Ubah</span>
                    </div>
                </button>
            </div>

            <!-- Name (Centered) -->
            <h3 id="popoverHeaderName" class="text-base sm:text-lg font-bold text-slate-800 leading-tight mt-2.5 truncate px-4">
                {{ $currentUser->name ?? 'Pengguna' }}
            </h3>

            <!-- Email (Centered) -->
            <p class="text-xs text-slate-500 mt-0.5 truncate px-4 font-normal">
                {{ $currentUser->email }}
            </p>

            <!-- Clickable Avatar Choice Drawer (Hidden by default, reveals on avatar click) -->
            <div id="avatarPickerDrawer" class="hidden mt-3.5 pt-3 border-t border-slate-200/80 animate-fadeIn">
                <span class="block text-[10.5px] font-bold text-slate-600 uppercase tracking-wider mb-2">
                    Pilih Foto Profil:
                </span>
                <div class="grid grid-cols-2 gap-2.5 max-w-[270px] mx-auto text-left">
                    <!-- Option Men -->
                    <label class="relative flex items-center gap-2 p-2 rounded-xl border-2 cursor-pointer transition avatar-option-card {{ $currentAvatar === 'profil_men.webp' ? 'border-[#2E978E] bg-teal-50/70 shadow-xs' : 'border-slate-200 hover:border-slate-300 bg-white' }}">
                        <input type="radio" 
                               name="avatar_radio" 
                               value="profil_men.webp" 
                               class="sr-only avatar-radio" 
                               {{ $currentAvatar === 'profil_men.webp' ? 'checked' : '' }}>
                        <img src="{{ asset('assets/profil_men.webp') }}" 
                             alt="Avatar Pria" 
                             class="w-8 h-8 rounded-full object-cover ring-1 ring-slate-200 shrink-0">
                        <div class="min-w-0">
                            <span class="block text-xs font-bold text-slate-800 leading-tight">Pria</span>
                            <span class="block text-[9.5px] text-slate-400">profil_men</span>
                        </div>
                        <div class="checkmark-badge ml-auto {{ $currentAvatar === 'profil_men.webp' ? '' : 'hidden' }}">
                            <div class="w-4 h-4 rounded-full bg-[#2E978E] text-white flex items-center justify-center">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                    </label>

                    <!-- Option Women -->
                    <label class="relative flex items-center gap-2 p-2 rounded-xl border-2 cursor-pointer transition avatar-option-card {{ $currentAvatar === 'profil_wmn.webp' ? 'border-[#2E978E] bg-teal-50/70 shadow-xs' : 'border-slate-200 hover:border-slate-300 bg-white' }}">
                        <input type="radio" 
                               name="avatar_radio" 
                               value="profil_wmn.webp" 
                               class="sr-only avatar-radio" 
                               {{ $currentAvatar === 'profil_wmn.webp' ? 'checked' : '' }}>
                        <img src="{{ asset('assets/profil_wmn.webp') }}" 
                             alt="Avatar Wanita" 
                             class="w-8 h-8 rounded-full object-cover ring-1 ring-slate-200 shrink-0">
                        <div class="min-w-0">
                            <span class="block text-xs font-bold text-slate-800 leading-tight">Wanita</span>
                            <span class="block text-[9.5px] text-slate-400">profil_wmn</span>
                        </div>
                        <div class="checkmark-badge ml-auto {{ $currentAvatar === 'profil_wmn.webp' ? '' : 'hidden' }}">
                            <div class="w-4 h-4 rounded-full bg-[#2E978E] text-white flex items-center justify-center">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Form Edit Profil & Ganti Password -->
        <form id="profilePopoverForm" action="{{ route('profile.update') }}" method="POST" class="p-4 sm:p-5 max-h-[65vh] overflow-y-auto space-y-3.5">
            @csrf
            @method('PUT')

            <!-- Hidden input for selected avatar -->
            <input type="hidden" name="avatar" id="hiddenAvatarInput" value="{{ $currentAvatar }}">

            <!-- Detail Information Fields -->
            <div class="space-y-3">
                <!-- Nama Lengkap (Editable) -->
                <div>
                    <label for="popover_input_name" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Nama Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           id="popover_input_name" 
                           name="name" 
                           value="{{ old('name', $currentUser->name) }}" 
                           required 
                           class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#2E978E] focus:border-transparent bg-slate-50/50 hover:bg-white transition dirty-track-input">
                </div>

                <!-- NIM / NIP (Read-Only) -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                        {{ $isPelapor ? 'NIM' : 'NIP / NIK' }} <span class="text-[10px] text-slate-400 font-normal lowercase">(read-only)</span>
                    </label>
                    <div class="w-full px-3 py-2 text-xs font-semibold rounded-xl border border-slate-200 bg-slate-100 text-slate-700 flex items-center justify-between">
                        <span>{{ $currentUser->identity_number ?: '-' }}</span>
                        <span class="text-[10px] px-2 py-0.5 rounded-md bg-slate-200 text-slate-600 font-medium">Tetap</span>
                    </div>
                </div>

                <!-- Email (Read-Only) -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Email <span class="text-[10px] text-slate-400 font-normal lowercase">(read-only)</span>
                    </label>
                    <div class="w-full px-3 py-2 text-xs font-semibold rounded-xl border border-slate-200 bg-slate-100 text-slate-700 flex items-center justify-between">
                        <span class="truncate">{{ $currentUser->email }}</span>
                        <span class="text-[10px] px-2 py-0.5 rounded-md bg-teal-100 text-[#0F766E] font-medium">Terdaftar</span>
                    </div>
                </div>

                <!-- No Telepon (Editable) -->
                <div>
                    <label for="popover_input_phone" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                        No. Telepon / WhatsApp
                    </label>
                    <input type="text" 
                           id="popover_input_phone" 
                           name="phone_number" 
                           value="{{ old('phone_number', $currentUser->phone_number) }}" 
                           placeholder="Contoh: 081234567890" 
                           class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#2E978E] focus:border-transparent bg-slate-50/50 hover:bg-white transition dirty-track-input">
                </div>

                <!-- Jabatan (Dropdown 3 Pilihan: Mahasiswa, Tendik, Dosen) -->
                <div>
                    <label for="popover_input_position" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Jabatan <span class="text-rose-500">*</span>
                    </label>
                    <select id="popover_input_position" 
                            name="position" 
                            class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#2E978E] focus:border-transparent bg-slate-50/50 hover:bg-white transition dirty-track-input cursor-pointer font-medium text-slate-700">
                        <option value="mahasiswa" {{ $userPos === 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                        <option value="tendik" {{ ($userPos === 'tendik' || str_contains($userPos, 'tenaga kependidikan')) ? 'selected' : '' }}>Tenaga Kependidikan (Tendik)</option>
                        <option value="dosen" {{ $userPos === 'dosen' ? 'selected' : '' }}>Dosen</option>
                        @if(!in_array($userPos, ['mahasiswa', 'tendik', 'dosen']) && !empty($currentUser->position))
                            <option value="{{ $currentUser->position }}" selected>{{ $currentUser->position }}</option>
                        @endif
                    </select>
                </div>

                <!-- Additional Badges for Staff (Kabag, Kasubag, Petugas) -->
                @if(!$isPelapor)
                    <div class="pt-2 border-t border-slate-100">
                        <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-2">
                            Status & Penugasan
                        </label>
                        <div class="grid grid-cols-3 gap-2">
                            <!-- Role Badge -->
                            <div class="p-2 rounded-xl bg-teal-50 border border-teal-200/80 text-center">
                                <span class="block text-[9px] font-bold text-teal-800 uppercase tracking-wider">Role</span>
                                <span class="block text-[11px] font-extrabold text-[#0F766E] capitalize truncate" title="{{ $roleName }}">
                                    {{ $currentUser->role }}
                                </span>
                            </div>

                            <!-- Bidang Badge -->
                            <div class="p-2 rounded-xl bg-blue-50 border border-blue-200/80 text-center">
                                <span class="block text-[9px] font-bold text-blue-800 uppercase tracking-wider">Bidang</span>
                                <span class="block text-[11px] font-extrabold text-blue-700 truncate" title="{{ $subDeptName }}">
                                    {{ $subDeptName }}
                                </span>
                            </div>

                            <!-- Status Badge -->
                            <div class="p-2 rounded-xl bg-emerald-50 border border-emerald-200/80 text-center">
                                <span class="block text-[9px] font-bold text-emerald-800 uppercase tracking-wider">Status</span>
                                <span class="inline-flex items-center gap-1 text-[11px] font-extrabold text-emerald-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Aktif
                                </span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Ganti Password Section (Accordion) -->
            <div class="pt-2 border-t border-slate-100">
                <button type="button" 
                        id="togglePasswordFieldsBtn" 
                        class="w-full flex items-center justify-between text-left py-1 text-xs font-bold text-slate-700 hover:text-[#2E978E] transition cursor-pointer">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Ubah Password Akun
                    </span>
                    <svg id="passwordAccordionIcon" class="w-3.5 h-3.5 text-slate-400 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div id="passwordFieldsContainer" class="hidden mt-2.5 space-y-2.5 bg-slate-50 p-3 rounded-xl border border-slate-150">
                    <div>
                        <label for="popover_input_current_password" class="block text-[10.5px] font-bold text-slate-600 mb-1">
                            Password Lama
                        </label>
                        <input type="password" 
                               id="popover_input_current_password" 
                               name="current_password" 
                               placeholder="Masukkan password saat ini" 
                               class="w-full px-3 py-1.5 text-xs rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#2E978E] bg-white dirty-track-input">
                    </div>

                    <div>
                        <label for="popover_input_new_password" class="block text-[10.5px] font-bold text-slate-600 mb-1">
                            Password Baru
                        </label>
                        <input type="password" 
                               id="popover_input_new_password" 
                               name="new_password" 
                               placeholder="Minimal 8 karakter" 
                               class="w-full px-3 py-1.5 text-xs rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#2E978E] bg-white dirty-track-input">
                    </div>

                    <div>
                        <label for="popover_input_new_password_confirmation" class="block text-[10.5px] font-bold text-slate-600 mb-1">
                            Konfirmasi Password Baru
                        </label>
                        <input type="password" 
                               id="popover_input_new_password_confirmation" 
                               name="new_password_confirmation" 
                               placeholder="Ulangi password baru" 
                               class="w-full px-3 py-1.5 text-xs rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#2E978E] bg-white dirty-track-input">
                    </div>
                    <p class="text-[10px] text-slate-500 italic">* Kosongkan kolom password jika tidak ingin mengubah password.</p>
                </div>
            </div>

            <!-- Footer: Submit Button -->
            <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-3">
                <span id="dirtyStatusIndicator" class="text-[11px] text-slate-400 italic">
                    Belum ada perubahan
                </span>

                <button type="submit" 
                        id="saveProfileBtn" 
                        disabled 
                        class="px-5 py-2.5 rounded-xl bg-slate-300 text-slate-500 text-xs font-bold shadow-xs transition-all duration-200 flex items-center gap-2 cursor-not-allowed opacity-60">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    (function () {
        const trigger = document.getElementById('profilePopoverTrigger');
        const dropdown = document.getElementById('profilePopoverDropdown');
        const closeBtn = document.getElementById('closeProfilePopoverBtn');
        const triggerAvatarImg = document.getElementById('triggerAvatarImg');
        const centerAvatarImg = document.getElementById('centerAvatarImg');
        const centerAvatarToggleBtn = document.getElementById('centerAvatarToggleBtn');
        const avatarPickerDrawer = document.getElementById('avatarPickerDrawer');
        const hiddenAvatarInput = document.getElementById('hiddenAvatarInput');
        const popoverHeaderName = document.getElementById('popoverHeaderName');
        const saveBtn = document.getElementById('saveProfileBtn');
        const dirtyIndicator = document.getElementById('dirtyStatusIndicator');
        const form = document.getElementById('profilePopoverForm');
        const togglePasswordBtn = document.getElementById('togglePasswordFieldsBtn');
        const passwordContainer = document.getElementById('passwordFieldsContainer');
        const passwordChevron = document.getElementById('passwordAccordionIcon');

        if (!trigger || !dropdown || !form) return;

        // Baseline initial values for dirty check
        const initialValues = {
            avatar: "{{ $currentAvatar }}",
            name: "{{ addslashes($currentUser->name ?? '') }}",
            phone_number: "{{ addslashes($currentUser->phone_number ?? '') }}",
            position: (document.getElementById('popover_input_position')?.value || '').trim()
        };

        let currentSelectedAvatar = initialValues.avatar;

        // Toggle popover visibility
        function openPopover() {
            dropdown.classList.remove('hidden');
            requestAnimationFrame(() => {
                dropdown.classList.remove('opacity-0', 'scale-95');
                dropdown.classList.add('opacity-100', 'scale-100');
            });
            trigger.setAttribute('aria-expanded', 'true');
        }

        function closePopover() {
            dropdown.classList.remove('opacity-100', 'scale-100');
            dropdown.classList.add('opacity-0', 'scale-95');
            setTimeout(() => {
                dropdown.classList.add('hidden');
            }, 180);
            trigger.setAttribute('aria-expanded', 'false');
        }

        function togglePopover() {
            if (dropdown.classList.contains('hidden')) {
                openPopover();
            } else {
                closePopover();
            }
        }

        trigger.addEventListener('click', function (e) {
            e.stopPropagation();
            togglePopover();
        });

        if (closeBtn) {
            closeBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                closePopover();
            });
        }

        // Toggle avatar selection options on center avatar click
        if (centerAvatarToggleBtn && avatarPickerDrawer) {
            centerAvatarToggleBtn.addEventListener('click', function (e) {
                e.preventDefault();
                avatarPickerDrawer.classList.toggle('hidden');
            });
        }

        // Close on clicking outside
        document.addEventListener('click', function (e) {
            if (!dropdown.contains(e.target) && !trigger.contains(e.target)) {
                if (!dropdown.classList.contains('hidden')) {
                    closePopover();
                }
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !dropdown.classList.contains('hidden')) {
                closePopover();
            }
        });

        // Toggle password accordion
        if (togglePasswordBtn && passwordContainer) {
            togglePasswordBtn.addEventListener('click', function () {
                passwordContainer.classList.toggle('hidden');
                if (passwordChevron) {
                    passwordChevron.classList.toggle('rotate-180');
                }
            });
        }

        // Avatar switcher handler
        const avatarRadios = document.querySelectorAll('.avatar-radio');
        const avatarCards = document.querySelectorAll('.avatar-option-card');

        avatarRadios.forEach((radio) => {
            radio.addEventListener('change', function () {
                currentSelectedAvatar = this.value;
                if (hiddenAvatarInput) {
                    hiddenAvatarInput.value = currentSelectedAvatar;
                }

                avatarCards.forEach((card) => {
                    const r = card.querySelector('.avatar-radio');
                    const badge = card.querySelector('.checkmark-badge');
                    if (r && r.checked) {
                        card.classList.add('border-[#2E978E]', 'bg-teal-50/70', 'shadow-xs');
                        card.classList.remove('border-slate-200');
                        if (badge) badge.classList.remove('hidden');
                    } else {
                        card.classList.remove('border-[#2E978E]', 'bg-teal-50/70', 'shadow-xs');
                        card.classList.add('border-slate-200');
                        if (badge) badge.classList.add('hidden');
                    }
                });

                // Update center image and trigger icon preview
                const newAvatarSrc = "{{ asset('assets') }}/" + currentSelectedAvatar;
                if (triggerAvatarImg) triggerAvatarImg.src = newAvatarSrc;
                if (centerAvatarImg) centerAvatarImg.src = newAvatarSrc;

                checkDirtyState();
            });
        });

        // Dirty checking logic
        function checkDirtyState() {
            const currentName = (document.getElementById('popover_input_name')?.value || '').trim();
            const currentPhone = (document.getElementById('popover_input_phone')?.value || '').trim();
            const currentPos = (document.getElementById('popover_input_position')?.value || '').trim();

            const curPass = (document.getElementById('popover_input_current_password')?.value || '').trim();
            const newPass = (document.getElementById('popover_input_new_password')?.value || '').trim();
            const confirmPass = (document.getElementById('popover_input_new_password_confirmation')?.value || '').trim();

            const isAvatarChanged = currentSelectedAvatar !== initialValues.avatar;
            const isNameChanged = currentName !== initialValues.name;
            const isPhoneChanged = currentPhone !== initialValues.phone_number;
            const isPosChanged = currentPos !== initialValues.position;
            const isPasswordChanged = curPass.length > 0 || newPass.length > 0 || confirmPass.length > 0;

            const isDirty = isAvatarChanged || isNameChanged || isPhoneChanged || isPosChanged || isPasswordChanged;

            // Live update header name in popover
            if (popoverHeaderName && currentName.length > 0) {
                popoverHeaderName.textContent = currentName;
            }

            if (isDirty) {
                saveBtn.disabled = false;
                saveBtn.classList.remove('bg-slate-300', 'text-slate-500', 'cursor-not-allowed', 'opacity-60');
                saveBtn.classList.add('bg-[#0F766E]', 'hover:bg-[#0D655E]', 'text-white', 'cursor-pointer', 'opacity-100', 'shadow-md');
                if (dirtyIndicator) {
                    dirtyIndicator.textContent = 'Ada perubahan belum disimpan';
                    dirtyIndicator.className = 'text-[11px] text-amber-600 font-semibold';
                }
            } else {
                saveBtn.disabled = true;
                saveBtn.classList.remove('bg-[#0F766E]', 'hover:bg-[#0D655E]', 'text-white', 'cursor-pointer', 'opacity-100', 'shadow-md');
                saveBtn.classList.add('bg-slate-300', 'text-slate-500', 'cursor-not-allowed', 'opacity-60');
                if (dirtyIndicator) {
                    dirtyIndicator.textContent = 'Belum ada perubahan';
                    dirtyIndicator.className = 'text-[11px] text-slate-400 italic';
                }
            }
        }

        // Listen for input and change events
        document.querySelectorAll('.dirty-track-input').forEach(input => {
            input.addEventListener('input', checkDirtyState);
            input.addEventListener('change', checkDirtyState);
        });

        // Initialize state on load
        checkDirtyState();
    })();
</script>
