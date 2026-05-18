<x-app-layout>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-cinzel font-bold text-transparent bg-clip-text bg-gradient-to-r from-[var(--text-primary)] to-[var(--text-accent)] mb-2">
                <i class="fa-solid fa-user-shield mr-2 text-[var(--text-accent)]"></i> User Profile
            </h1>
            <p class="text-[var(--text-secondary)]">Manage your identity in the realm</p>
        </div>
        
        <div class="hidden sm:block text-right">
            <div class="text-2xl font-cinzel font-bold text-[var(--text-primary)]">{{ $user->name }}</div>
            <div class="text-[var(--text-accent)] uppercase tracking-wider text-sm font-bold"><i class="fa-brands fa-d-and-d mr-1"></i> House {{ $user->house }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        
        <!-- Left Column: Avatar & Quick Stats -->
        <div class="xl:col-span-1 space-y-6">
            <div class="got-panel p-8 rounded-xl text-center" data-aos="fade-up">
                <div class="relative inline-block mb-6" x-data="{
                    triggerUpload() { this.$refs.avatarInput.click(); },
                    submitForm() { this.$refs.avatarForm.submit(); }
                }">
                    <div class="w-32 h-32 rounded-full border-4 border-[var(--accent-color)] bg-black mx-auto overflow-hidden shadow-[0_0_20px_var(--accent-glow)]">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                        @else
                            <i class="fa-solid fa-user text-5xl text-gray-400 mt-8"></i>
                        @endif
                    </div>
                    
                    <!-- Hidden form for uploading avatar -->
                    <form method="POST" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data" x-ref="avatarForm" class="hidden">
                        @csrf
                        @method('patch')
                        <input type="file" name="avatar" x-ref="avatarInput" accept="image/*" @change="submitForm">
                    </form>
                    
                    <!-- Upload Button -->
                    <button @click="triggerUpload" class="absolute bottom-0 right-0 w-10 h-10 rounded-full bg-[var(--panel-bg)] border border-[var(--accent-color)] text-[var(--text-accent)] hover:bg-[var(--accent-glow)] transition-colors flex items-center justify-center cursor-pointer shadow-lg">
                        <i class="fa-solid fa-camera"></i>
                    </button>
                </div>
                
                <h2 class="text-2xl font-cinzel font-bold">{{ $user->name }}</h2>
                <p class="text-[var(--text-secondary)] mb-6">{{ $user->character_class }}</p>
                
                <div class="grid grid-cols-2 gap-4 text-center border-t border-[var(--panel-border)] pt-6">
                    <div>
                        <div class="text-2xl font-cinzel font-bold text-yellow-400">{{ number_format($user->xp) }}</div>
                        <div class="text-xs text-[var(--text-secondary)] uppercase tracking-wider">Total XP</div>
                    </div>
                    <div>
                        <div class="text-2xl font-cinzel font-bold text-yellow-500">{{ number_format($user->coins) }}</div>
                        <div class="text-xs text-[var(--text-secondary)] uppercase tracking-wider">Coins</div>
                    </div>
                </div>
            </div>
            
            <!-- Delete Account -->
            <div class="got-panel p-6 rounded-xl border-red-500/30" data-aos="fade-up" data-aos-delay="100">
                <h3 class="text-lg font-cinzel font-bold text-red-500 mb-2"><i class="fa-solid fa-triangle-exclamation mr-2"></i> Danger Zone</h3>
                <p class="text-sm text-[var(--text-secondary)] mb-4">Once your account is deleted, all of your resources and data will be permanently deleted.</p>
                
                <form method="POST" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')
                    
                    <div class="mb-4">
                        <input class="got-input rounded-lg" type="password" name="password" placeholder="Confirm Password to Delete">
                        <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-red-400" />
                    </div>
                    
                    <button class="got-btn-outline w-full rounded-lg border-red-500 text-red-500 hover:bg-red-900/30 text-sm py-2">
                        Delete Account
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Right Column: Edit Details -->
        <div class="xl:col-span-2 space-y-6">
            
            <div class="got-panel p-8 rounded-xl" data-aos="fade-left">
                <h2 class="text-2xl font-cinzel font-bold mb-6 border-b border-[var(--panel-border)] pb-2">Profile Information</h2>
                
                <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('patch')
                    
                    @if (session('status') === 'profile-updated')
                        <div class="p-4 rounded-lg bg-green-900/50 border border-green-500 text-green-200" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                            <i class="fa-solid fa-check-circle mr-2"></i> Profile updated successfully.
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-cinzel text-sm uppercase tracking-wider text-[var(--text-secondary)] mb-2">Name</label>
                            <input class="got-input rounded-lg" type="text" name="name" value="{{ old('name', $user->name) }}" required>
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>
                        
                        <div>
                            <label class="block font-cinzel text-sm uppercase tracking-wider text-[var(--text-secondary)] mb-2">Email</label>
                            <input class="got-input rounded-lg" type="email" name="email" value="{{ old('email', $user->email) }}" required>
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4 pt-4 border-t border-[var(--panel-border)]">
                        <button class="got-btn rounded-lg px-8 py-3">Save Changes</button>
                    </div>
                </form>
            </div>
            
            <!-- Achievements Showcase Placeholder -->
            <div class="got-panel p-8 rounded-xl" data-aos="fade-left" data-aos-delay="100">
                <h2 class="text-2xl font-cinzel font-bold mb-6 border-b border-[var(--panel-border)] pb-2">Recent Achievements</h2>
                
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center shadow-[0_0_15px_rgba(250,204,21,0.5)] mb-2">
                            <i class="fa-solid fa-dragon text-2xl text-black"></i>
                        </div>
                        <div class="text-xs font-bold uppercase">First Blood</div>
                    </div>
                    
                    <div class="text-center opacity-40 grayscale">
                        <div class="w-16 h-16 mx-auto rounded-full bg-[var(--panel-border)] border border-gray-600 flex items-center justify-center mb-2">
                            <i class="fa-solid fa-lock text-xl text-gray-400"></i>
                        </div>
                        <div class="text-xs font-bold uppercase">Centurion</div>
                    </div>
                    
                    <div class="text-center opacity-40 grayscale">
                        <div class="w-16 h-16 mx-auto rounded-full bg-[var(--panel-border)] border border-gray-600 flex items-center justify-center mb-2">
                            <i class="fa-solid fa-lock text-xl text-gray-400"></i>
                        </div>
                        <div class="text-xs font-bold uppercase">Brain Lord</div>
                    </div>
                    
                    <div class="text-center opacity-40 grayscale">
                        <div class="w-16 h-16 mx-auto rounded-full bg-[var(--panel-border)] border border-gray-600 flex items-center justify-center mb-2">
                            <i class="fa-solid fa-lock text-xl text-gray-400"></i>
                        </div>
                        <div class="text-xs font-bold uppercase">Strategist</div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
