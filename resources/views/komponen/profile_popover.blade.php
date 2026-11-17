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
@endphp

<div id="profile_popover_container" class="relative inline-block text-left" data-component="profil_popover">
    <!-- Trigger Button -->
    <button type="button" 
            id="profilePopoverTrigger" 
            class="flex items-center gap-2.5 p-1 sm:pl-2.5 sm:pr-2 py-1 rounded-full bg-white/10 hover:bg-white/20 border border-white/20 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-teal-300 group cursor-pointer"
            aria-expanded="false" 
            aria-haspopup="true"
            title="Profil Pengguna">
        <div class="relative w-8 h-8 rounded-full overflow-hidden ring-2 ring-white/40 shadow-xs bg-[#0F766E] shrink-0">
            <img id="triggerAvatarImg" 
                 src="{{ asset('assets/' . $currentAvatar) }}" 
                 alt="Foto Profil" 
                 class="w-full h-full object-cover">
            <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-400 border-2 border-[#2E978E] rounded-full"></span>
        </div>

        <div class="hidden md:flex flex-col text-left pr-1 min-w-0">
            <span class="text-xs font-bold text-white leading-tight truncate max-w-[120px]">
                {{ $currentUser->name ?? 'Pengguna' }}
            </span>
            <span class="text-[10px] text-teal-200 capitalize leading-tight truncate max-w-[120px]">
                {{ $currentUser->position ?? ($isPelapor ? 'Pelapor' : $roleName) }}
            </span>
        </div>

        <svg class="w-3.5 h-3.5 text-white/80 group-hover:text-white transition-transform duration-200" id="triggerChevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Popover Card Dropdown -->
    <div id="profilePopoverDropdown" 
         class="hidden absolute right-0 top-full mt-2.5 w-[330px] sm:w-[410px] bg-white rounded-2xl shadow-2xl border border-slate-200/80 z-50 overflow-hidden text-slate-800 transition-all duration-200 origin-top-right transform scale-95 opacity-0">
        
        <!-- Popover Header -->
        <div class="bg-gradient-to-r from-[#2E978E] to-[#0A8080] p-4 text-white relative">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-white/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold uppercase tracking-wider text-teal-100">Profil Akun</h4>
                        <p class="text-sm font-extrabold text-white leading-tight">{{ $currentUser->name }}</p>
                    </div>
                </div>

                <button type="button" 
                        id="closeProfilePopoverBtn" 
                        class="p-1 rounded-lg text-white/80 hover:text-white hover:bg-white/20 transition cursor-pointer"
                        title="Tutup">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Form Edit Profil & Ganti Password -->
        <form id="profilePopoverForm" action="{{ route('profile.update') }}" method="POST" class="p-4 sm:p-5 max-h-[75vh] overflow-y-auto space-y-4">
            @csrf
            @method('PUT')

            <!-- Avatar Selector -->
            <div>
                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-2">
                    Pilihan Avatar Profil
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <!-- Option Men -->
                    <label class="relative flex items-center gap-3 p-2.5 rounded-xl border-2 cursor-pointer transition avatar-option-card {{ $currentAvatar === 'profil_men.webp' ? 'border-[#2E978E] bg-teal-50/50' : 'border-slate-200 hover:border-slate-300 bg-white' }}">
                        <input type="radio" 
                               name="avatar" 
                               value="profil_men.webp" 
                               class="sr-only avatar-radio" 
                               {{ $currentAvatar === 'profil_men.webp' ? 'checked' : '' }}>
                        <img src="{{ asset('assets/profil_men.webp') }}" 
                             alt="Avatar Pria" 
                             class="w-10 h-10 rounded-full object-cover ring-2 ring-slate-100 shrink-0">
                        <div class="min-w-0">
                            <span class="block text-xs font-bold text-slate-800">Pria</span>
                            <span class="block text-[10px] text-slate-500">profil_men.webp</span>
                        </div>
                        <div class="checkmark-badge ml-auto {{ $currentAvatar === 'profil_men.webp' ? '' : 'hidden' }}">
                            <div class="w-5 h-5 rounded-full bg-[#2E978E] text-white flex items-center justify-center">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                    </label>

                    <!-- Option Women -->
                    <label class="relative flex items-center gap-3 p-2.5 rounded-xl border-2 cursor-pointer transition avatar-option-card {{ $currentAvatar === 'profil_wmn.webp' ? 'border-[#2E978E] bg-teal-50/50' : 'border-slate-200 hover:border-slate-300 bg-white' }}">
                        <input type="radio" 
                               name="avatar" 
                               value="profil_wmn.webp" 
                               class="sr-only avatar-radio" 
                               {{ $currentAvatar === 'profil_wmn.webp' ? 'checked' : '' }}>
                        <img src="{{ asset('assets/profil_wmn.webp') }}" 
                             alt="Avatar Wanita" 
                             class="w-10 h-10 rounded-full object-cover ring-2 ring-slate-100 shrink-0">
                        <div class="min-w-0">
                            <span class="block text-xs font-bold text-slate-800">Wanita</span>
                            <span class="block text-[10px] text-slate-500">profil_wmn.webp</span>
                        </div>
                        <div class="checkmark-badge ml-auto {{ $currentAvatar === 'profil_wmn.webp' ? '' : 'hidden' }}">
                            <div class="w-5 h-5 rounded-full bg-[#2E978E] text-white flex items-center justify-center">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Detail Information Section -->
            <div class="space-y-3">
                <!-- Nama (Editable) -->
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

                <!-- Jabatan (Editable as requested) -->
                <div>
                    <label for="popover_input_position" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Jabatan / Posisi
                    </label>
                    <input type="text" 
                           id="popover_input_position" 
                           name="position" 
                           value="{{ old('position', $currentUser->position) }}" 
                           placeholder="Contoh: Mahasiswa, Dosen, Staf" 
                           class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-[#2E978E] focus:border-transparent bg-slate-50/50 hover:bg-white transition dirty-track-input">
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

            <!-- Ganti Password Section -->
            <div class="pt-2 border-t border-slate-100">
                <button type="button" 
                        id="togglePasswordFieldsBtn" 
                        class="w-full flex items-center justify-between text-left py-1 text-xs font-bold text-slate-700 hover:text-[#2E978E] transition">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Ubah Password Akun
                    </span>
                    <svg id="passwordAccordionIcon" class="w-3.5 h-3.5 text-slate-400 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div id="passwordFieldsContainer" class="hidden mt-3 space-y-2.5 bg-slate-50 p-3 rounded-xl border border-slate-150">
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
                    <p class="text-[10px] text-slate-500 italic">* Kosongkan ketiga kolom password jika tidak ingin mengganti password.</p>
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
        const triggerChevron = document.getElementById('triggerChevron');
        const triggerAvatarImg = document.getElementById('triggerAvatarImg');
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
            position: "{{ addslashes($currentUser->position ?? '') }}"
        };

        let currentSelectedAvatar = initialValues.avatar;

        // Toggle popover visibility
        function openPopover() {
            dropdown.classList.remove('hidden');
            requestAnimationFrame(() => {
                dropdown.classList.remove('opacity-0', 'scale-95');
                dropdown.classList.add('opacity-100', 'scale-100');
            });
            if (triggerChevron) triggerChevron.classList.add('rotate-180');
            trigger.setAttribute('aria-expanded', 'true');
        }

        function closePopover() {
            dropdown.classList.remove('opacity-100', 'scale-100');
            dropdown.classList.add('opacity-0', 'scale-95');
            setTimeout(() => {
                dropdown.classList.add('hidden');
            }, 180);
            if (triggerChevron) triggerChevron.classList.remove('rotate-180');
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
                avatarCards.forEach((card) => {
                    const r = card.querySelector('.avatar-radio');
                    const badge = card.querySelector('.checkmark-badge');
                    if (r && r.checked) {
                        card.classList.add('border-[#2E978E]', 'bg-teal-50/50');
                        card.classList.remove('border-slate-200');
                        if (badge) badge.classList.remove('hidden');
                    } else {
                        card.classList.remove('border-[#2E978E]', 'bg-teal-50/50');
                        card.classList.add('border-slate-200');
                        if (badge) badge.classList.add('hidden');
                    }
                });

                // Update trigger image preview
                if (triggerAvatarImg) {
                    triggerAvatarImg.src = "{{ asset('assets') }}/" + currentSelectedAvatar;
                }

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

        // Listen for input changes across all form fields
        document.querySelectorAll('.dirty-track-input').forEach(input => {
            input.addEventListener('input', checkDirtyState);
            input.addEventListener('change', checkDirtyState);
        });

        // Initialize state on load
        checkDirtyState();
    })();
</script>
