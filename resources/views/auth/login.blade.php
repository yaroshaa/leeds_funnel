<x-guest-layout>
    <div class="container">
        <div class="col-md-8 col-lg-5 mx-auto">
            <div class="mb-5 mx-auto col-8 text-center">
                <a href="/" class="d-block">
                    <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" style="max-width: 100%">
                </a>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')"/>
            <x-auth-validation-errors class="mb-4" :errors="$errors"/>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-floating mb-3">
                    <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com"
                           value="{{ old('email') }}" autofocus required autocomplete="email">
                    <label for="email">{{ __('Email address') }}</label>
                </div>

                <div class="form-floating">
                    <input type="password" name="password"
                           class="form-control" id="password" placeholder="{{ __('Password') }}"
                           required autocomplete="current-password">
                    <label for="password">{{ __('Password') }}</label>
                </div>

                <div class="d-flex align-items-center justify-content-end mt-5">
                    <div class="form-check me-auto">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                        <label class="form-check-label" for="remember_me">{{ __('Remember me') }}</label>
                    </div>

                    @if (Route::has('password.request'))
                        <a class="underline small" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <div class="ms-3">
                        <button class="btn btn-primary">{{ __('Login') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
