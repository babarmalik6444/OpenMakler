<x-forms::field-wrapper
    :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :helper-text="$getHelperText()"
    :hint="$getHint()"
    :hint-icon="$getHintIcon()"
    :required="$isRequired()"
    :state-path="$getStatePath()"
>
    <div x-data="{ state: $wire.entangle('{{ $getStatePath() }}') }">
        <template x-for="(row, index) in state.active">
            <div class="flex items-center justify-between space-x-2 rtl:space-x-reverse mb-3">
                <label class="inline-flex items-center space-x-3 rtl:space-x-reverse w-full" for="data.test">
                    <span x-on:click.prevent="state.active.splice(index, 1)">
                        <input type="checkbox"
                               class="text-primary-600 transition duration-75 rounded shadow-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500 filament-forms-checkbox-component border-gray-300">
                    </span>
                    <input x-model.lazy="state.active[index]"
                           type="text"
                           class="block w-full transition duration-75 rounded-lg shadow-sm focus:border-primary-600 focus:ring-1 focus:ring-inset focus:ring-primary-600 disabled:opacity-70 border-gray-300">
                </label>
            </div>
        </template>

        <div class="flex flex-wrap justify-center gap-4 mt-5">
            <button type="button"
                    x-on:click.lazy="state.active.push([])"
                    class="px-2 py-1 border text-sm rounded-md tracking-widest focus:ring focus:ring-gray-300 disabled:opacity-25 transition focus:border-gray-900 focus:outline-none text-md text-white bg-primary-500 hover:bg-primary-600 active:bg-gray-900">
                Hinzufügen
            </button>
        </div>
    </div>
</x-forms::field-wrapper>
