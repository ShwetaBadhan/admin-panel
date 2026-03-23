<x-guest-layout>
    <form method="POST" action="{{ route('admin-register') }}">
        @csrf

        <!-- Name -->
        <div>
            <label class="form-label">Name</label>
            <input id="name" class="form-control" type="text" name="name" :value="old('name')" required
                autofocus autocomplete="name" placeholder="Enter Name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <label class="form-label">Email</label>
            <input id="email" class="form-control" type="email" name="email" :value="old('email')" required
                autocomplete="username" placeholder="Enter Email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label class="form-label">Password</label>
            <input id="password" class="form-control" placeholder="Enter Password" type="password" name="password" required
                autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <label class="form-label">Confirm Password</label>
            <input id="password_confirmation" placeholder="Confirm Password" class="form-control" type="password"
                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger" />
        </div>

       

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary btn-block"> {{ __('Register') }} <i class="ti-arrow-top-right ms-1"></i></button>
            </div>


    </form>
    <div class="new-account mt-3">
        <p class="mb-0">Already have an account? <a
                class="text-success"
                href="{{ route('login') }}">
                {{ __('Login') }}
            </a></p>
    </div>
</x-guest-layout>