<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    {{-- Global Error Message Here --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif



    <form method="POST" action="{{ route('login') }}" id="loginForm">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input id="email" class="form-control" placeholder="Enter Email" type="email" name="email"
                :value="old('email')" required autofocus autocomplete="username" />
            {{-- <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" /> --}}
        </div>

        <!-- Password -->
        {{-- <div class="mb-3">
            <label class="form-label">Password</label>
            <input id="password" class="form-control" placeholder="Enter Password" type="password" name="password"
                required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
        </div> --}}
        <div class="mb-3">
            <label class="form-label">Password</label>

            <div class="input-group">
                <input id="password" class="form-control" placeholder="Enter Password" type="password" name="password"
                    required autocomplete="current-password" />

                <button class="btn" type="button" id="togglePassword">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>



        <!-- Remember Me -->
        <div class="form-row d-flex justify-content-between flex-wrap mb-3">
            <div class="form-group">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __(key: 'Remember me') }}</span>
                </label>
            </div>
            <div class="form-group">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>
        </div>

        <!-- Cloudflare Turnstile CAPTCHA -->
        <div class="mb-4">
            <div class="cf-turnstile" data-sitekey="{{ env('TURNSTILE_SITEKEY') }}" data-callback="onCaptchaSuccess">
            </div>

        </div>

        <!-- Login Button -->
        <div class="text-center">
            <button type="submit" class="btn btn-primary btn-block" id="loginButton" disabled>
                {{ __('Log in') }} <i class="fa fa-paper-plane ms-2"></i>
            </button>
        </div>
    </form>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <script>
        // Enable the login button after CAPTCHA success
        function onCaptchaSuccess(token) {
            document.getElementById('loginButton').disabled = false;
        }

        const togglePassword = document.getElementById("togglePassword");
        const password = document.getElementById("password");
        const icon = togglePassword.querySelector("i");

        togglePassword.addEventListener("click", function () {
            const isPassword = password.type === "password";
            password.type = isPassword ? "text" : "password";

            icon.classList.toggle("bi-eye", !isPassword);
            icon.classList.toggle("bi-eye-slash", isPassword);
        });


    </script>
</x-guest-layout>