@php($tempImg = "https://cache.willhaben.at/mmo/3/341/339/043_-521402288.jpg")
<x-tailwind-element-layout :title="$realEstate->objekttitel">
    <div class="min-h-screen flex flex-col items-center py-6 sm:pt-0 bg-gray-100">
        <div class="block p-6 rounded-lg shadow-lg bg-white max-w-3xl w-full">
            <h1 class="text-xl text-center mb-2">{{ $realEstate->objekttitel }}</h1>

            @if($images)<x-elements.carousel :items="$images"></x-elements.carousel>@endif

            <div class="mt-4 text-gray-700">
                <x-elements.tabs :items="['Infos', 'Ausstattung', 'Kontakt']">
                    <x-elements.tab title="Infos" :active="true">
                        <h3 class="text-lg mb-1 mt-4">Flächen</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <x-elements.facts-col title="Wohnfläche" :value="$realEstate->flaechen->wohnflaeche" unit="m²" />
                            <x-elements.facts-col title="Grundstücksfläche" :value="$realEstate->flaechen->grundstuecksflaeche" unit="m²" />
                            <x-elements.facts-col title="Kellerfläche" :value="$realEstate->flaechen->kellerflaeche" unit="m²" />
                            <x-elements.facts-col title="Nutzfläche" :value="$realEstate->flaechen->nutzflaeche" unit="m²" />
                            <x-elements.facts-col title="Gartenfläche" :value="$realEstate->flaechen->gartenflaeche" unit="m²" />
                        </div>

                        @if($realEstate->freitexte->objektbeschreibung)
                            <h2 class="text-lg mt-4 ">Objektbeschreibung</h2>
                            <p>{{ $realEstate->freitexte->objektbeschreibung }}</p>
                        @endif

                        @if($realEstate->freitexte->lage)
                            <h2 class="text-lg mt-4">Lage</h2>
                            <p>{{ $realEstate->freitexte->lage }}</p>
                        @endif

                        @if($realEstate->freitexte->sonstige_angaben)
                            <h2 class="text-lg mt-4">Sonstige Angaben</h2>
                            <p>{{ $realEstate->freitexte->sonstige_angaben }}</p>
                        @endif
                    </x-elements.tab>

                    <x-elements.tab title="Ausstattung">
                        @if($realEstate->freitexte->ausstatt_beschr)
                            <h2 class="text-lg mt-4 text-gray-900">Ausstattung</h2>
                            <p>{{ $realEstate->freitexte->ausstatt_beschr }}</p>
                        @endif

                        <h2 class="text-lg mt-4 text-gray-900">Heizung</h2>
                        <p>{{ $realEstate->ausstattung->getBefeuerungString() }}</p>

                        <div class="grid grid-cols-2 gap-4">
                            @php($specials = [])
                            @if($realEstate->ausstattung->wintergarten)@php($specials[]= "Wintergarten")@endif
                            @if($realEstate->ausstattung->barrierefrei)@php($specials[]= "Barrierefrei")@endif
                            @if($realEstate->ausstattung->sauna)@php($specials[]= "Sauna")@endif

                            @if($specials)
                                <h2 class="text-lg mt-4 text-gray-900">Besonderheiten</h2>
                                <p>{{ implode(", ", $specials) }}</p>
                            @endif
                        </div>
                    </x-elements.tab>

                    <x-elements.tab title="Kontakt">
                        <livewire:immo-contact-form :realEstate="$realEstate"></livewire:immo-contact-form>
                    </x-elements.tab>
                </x-elements.tabs>
            </div>
        </div>
    </div>
</x-tailwind-element-layout>
