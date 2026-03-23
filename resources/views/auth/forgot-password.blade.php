<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 " :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="form-label">Email</label>
            <x-text-input id="email" placeholder="Enter email" class="form-control" type="email" name="email"
                :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="text-center mt-3">
            <button type="submit" class="btn btn-primary btn-block"> {{ __('Email Password Reset Link') }}</button>
        </div>

        
    </form>
</x-guest-layout>


