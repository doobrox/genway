<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div>
                <x-input id="name" class="block mt-1 w-full" 
                            type="text" 
                            name="name" 
                            placeholder="{{ __('Name') }}" 
                            :value="old('name')" 
                            required autofocus />
            </div>

            <!-- Surname -->
            <div class="mt-5">
                <x-input id="surname" class="block mt-1 w-full" 
                            type="text" 
                            name="surname" 
                            placeholder="{{ __('Surname') }}" 
                            :value="old('surname')" required autofocus />
            </div>

            <!-- ID Card Number -->
            <div class="mt-5">
                <x-input id="id_card_number" class="block mt-1 w-full" 
                            type="text" 
                            name="id_card_number" 
                            placeholder="{{ __('ID Card Number') }}" 
                            :value="old('id_card_number')" 
                            required autofocus />
            </div>

            <!-- Email Address -->
            <div class="mt-5">
                <x-input id="email" class="block mt-1 w-full" 
                            type="email" 
                            name="email" 
                            placeholder="{{ __('Email') }}" 
                            :value="old('email')" 
                            required />
            </div>

            <!-- Phone -->
            <div class="mt-5">
                <x-input id="phone" class="block mt-1 w-full" 
                            type="text" 
                            name="phone" 
                            placeholder="{{ __('Phone') }}" 
                            :value="old('phone')" 
                            required autofocus />
            </div>

            <!-- Address -->
            <div class="mt-5">
                <x-textarea id="address" class="block mt-1 w-full" 
                            name="address" 
                            placeholder="{{ __('Address') }}" 
                            required autofocus >{{ old('address') }}</x-textarea>
            </div>

            <!-- Password -->
            <div class="mt-5">
                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                placeholder="{{ __('Password') }}" 
                                required autocomplete="new-password" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-5">
                <x-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" 
                                placeholder="{{ __('Confirm Password') }}" 
                                required />
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="ml-4">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
