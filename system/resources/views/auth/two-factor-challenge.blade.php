<x-guest-layout>
    @push('scripts')
        <script type="text/javascript">
            if(typeof toggle == 'function') {
                toggle();
            }
            function toggle() {
                var checkbox = document.getElementById('toggle');
                if(checkbox) {
                    var inputCodeDiv = document.getElementById('code').closest('form');
                    var inputRecoveryCodeDiv = document.getElementById('recovery_code').closest('form');
                    if(checkbox.checked == true) {
                        // hide the generated code input group
                        inputCodeDiv.classList.remove('block');
                        inputCodeDiv.classList.add('hidden');
                        // show the recovery code input group
                        inputRecoveryCodeDiv.classList.remove('hidden');
                        inputRecoveryCodeDiv.classList.add('block');
                    } else {
                        // hide the recovery code input group
                        inputRecoveryCodeDiv.classList.remove('block');
                        inputRecoveryCodeDiv.classList.add('hidden');
                        // show the generated code input group
                        inputCodeDiv.classList.remove('hidden');
                        inputCodeDiv.classList.add('block');
                    }
                }
            }
        </script>
    @endpush
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <h3 class="mb-4">{{ __('Two Factor Authentification') }}</h3>

        {{-- If 2FA was enabled --}}
        @if (session('status') == 'two-factor-authentication-enabled')
            {{-- Dsiplay informative message --}}
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ __('Two factor authentication has been enabled') }}.
            </div>

            {{-- Dsiplay QrCode --}}
            {!! auth()->user()->twoFactorQrCodeSvg() !!}

            {{-- Dsiplay Recovery Codes --}}
            <div class="my-4 font-bold text-sm text-green-600">
                {{ __('Please store this codes in a secure location') }}.<br> {{ __('They will be used to access your 2FA if it is needed') }}.
            </div>

            <code class="mb-4 bg-gray-300 p-3 inline-block">
                @foreach(auth()->user()->recoveryCodes() as $code)
                    {{ trim($code) }}<br>
                @endforeach
            </code>
        @else
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
        @endif

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        @if(auth()->check() && !auth()->user()->two_factor_secret)
            <form method="POST" action="{{ route('two-factor.enable')}}">
                @csrf
                <x-button class="mt-3">
                    {{ __('Enable Google 2FA') }}
                </x-button>
            </form>
        @else

            @if (session('status') != 'two-factor-authentication-enabled')
                <div class="mb-4 font-medium text-sm">
                    {{ __('Enter the generated code or one of the recovery codes') }}.<br>
                </div>

                <div class="mb-4">
                    <x-checkbox :id="'toggle'" class="mr-1" type="checkbox" name="toggle" onclick="toggle()">{{ __('Use recovery code') }}</x-checkbox>
                </div>
                
                <form method="POST" action="{{ url('two-factor-challenge') }}">
                    @csrf
                    <!-- Code -->
                    <div class="mt-5">
                        <x-input id="code" class="block mt-1 w-full" type="text" name="code" placeholder="{{ __('Code') }}" :value="old('code')" />
                    </div>

                    <div class="sm:flex justify-end">
                        <div class="flex items-center mt-4">
                            @if (Route::has('login'))
                                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                                    {{ __('Go back') }}
                                </a>
                            @endif
                            <x-button class="ml-3">
                                {{ __('Enter') }}
                            </x-button>
                        </div>
                    </div>
                </form>

                <form method="POST" action="{{ url('two-factor-challenge') }}" class="hidden">
                    @csrf

                    <div class="mb-4 font-medium text-sm text-red-600">
                        <b>{{ __('After using one recovery code another one will be generated') }}.<br></b>
                    </div>
                    <!-- Recovery code -->
                    <div class="mt-5">
                        <x-input id="recovery_code" class="block mt-1 w-full" type="text" name="recovery_code" placeholder="{{ __('Recovery code') }}" :value="old('recovery_code')" />
                    </div>

                    <div class="sm:flex justify-end">
                        <div class="flex items-center mt-4">
                            @if (Route::has('login'))
                                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                                    {{ __('Go back') }}
                                </a>
                            @endif
                            <x-button class="ml-3">
                                {{ __('Enter') }}
                            </x-button>
                        </div>
                    </div>
                </form>
            @else
                <div class="block">
                    <x-link-button href="{{ route('user.details') }}">
                        {{ __('My details') }}
                    </x-link-button>
                </div>
            @endif
        @endif
    </x-auth-card>
</x-guest-layout>
