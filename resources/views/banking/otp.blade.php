<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('For your security, please enter the OTP sent to your email (Check storage/logs/laravel.log for testing).') }}
    </div>
    <form method="POST" action="{{ route('otp.verify') }}">
        @csrf
        <div>
            <x-input-label for="otp" :value="__('OTP Code')" />
            <x-text-input id="otp" class="block mt-1 w-full" type="text" name="otp" required autofocus />
            <x-input-error :messages="$errors->get('otp')" class="mt-2" />
        </div>
        <div class="flex justify-end mt-4">
            <x-primary-button>
                {{ __('Verify') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>