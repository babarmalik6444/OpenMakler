<form wire:submit.prevent="submit">
    @if(!$this->submitted)
        {{ $this->form }}

        <div class="text-center mt-5">
            <x-filament-support::button type="primary">Absenden</x-filament-support::button>
        </div>
    @else
        <div class="bg-green-100 rounded-lg py-5 px-6 mb-4 text-base text-green-700 mb-3 text-center" role="alert">
            <strong>Vielen Dank für Ihre Anfrage.</strong><br><br>
            Wir werden Sie so schnell wie möglich kontaktieren!
        </div>
    @endif
</form>
