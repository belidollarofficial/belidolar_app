<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.auth')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <h1 class="text-xl font-bold text-white mb-1">Masuk ke Akun Anda</h1>
    <p class="text-sm text-gray-400 mb-6">Masukkan email dan password untuk melanjutkan.</p>

    @if (session('status'))
        <div class="mb-4 text-sm text-emerald-300 bg-emerald-500/10 border border-emerald-500/30 rounded-xl px-4 py-3">
            {{ session('status') }}
        </div>
    @endif

    {{-- x-on:keydown.enter membuat tombol Enter langsung submit form login --}}
    <form wire:submit="login" x-on:keydown.enter.prevent="$wire.login()">
        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-semibold text-white mb-1">Email</label>
            <input wire:model="form.email" id="email" type="email" name="email" required autofocus autocomplete="username"
                   placeholder="admin@belidolar.id"
                   class="w-full border border-[#334155] bg-[#0f1724] text-white rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#4f46e5] focus:border-[#4f46e5] transition" />
            @error('form.email') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password" class="block text-sm font-semibold text-white mb-1">Password</label>
            <input wire:model="form.password" id="password" type="password" name="password" required autocomplete="current-password"
                   placeholder="••••••••"
                   class="w-full border border-[#334155] bg-[#0f1724] text-white rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-[#4f46e5] focus:border-[#4f46e5] transition" />
            @error('form.password') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Remember Me + Forgot -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember" class="inline-flex items-center gap-2 text-sm text-gray-400 cursor-pointer">
                <input wire:model="form.remember" id="remember" type="checkbox" name="remember"
                       class="rounded border-[#334155] bg-[#0f1724] text-[#4f46e5] focus:ring-[#4f46e5]" />
                Ingat saya
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" wire:navigate class="text-sm text-cyan-300 hover:underline">
                    Lupa password?
                </a>
            @endif
        </div>

        <button type="submit" wire:loading.attr="disabled" wire:target="login"
                class="w-full mt-6 bg-gradient-to-r from-[#4f46e5] to-[#06b6d4] text-white font-bold py-3 rounded-xl text-sm tracking-wide flex items-center justify-center gap-2 hover:brightness-110 transition">
            <span wire:loading.remove wire:target="login"><i class="fas fa-sign-in-alt"></i> Masuk</span>
            <span wire:loading wire:target="login"><i class="fas fa-spinner fa-spin"></i> Memproses...</span>
        </button>
    </form>
</div>