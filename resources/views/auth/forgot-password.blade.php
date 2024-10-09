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

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="form-floating mb-3">
                    <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com"
                           value="{{ old('email') }}" autofocus required autocomplete="email">
                    <label for="email">{{ __('Email address') }}</label>
                </div>

                <div class="text-center">
                    <button class="btn btn-primary">{{ __('Email Password Reset Link') }}</button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
