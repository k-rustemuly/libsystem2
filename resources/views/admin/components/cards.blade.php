@props([
    'items',
    'fields',
    'overlay' => false,
    'simple' => false,
    'simplePaginate' => false,
    'name' => 'default',
    'columnSpan' => 12,
    'adaptiveColumnSpan' => 12
])
<x-moonshine::grid>
    @foreach($items as $item)
        <x-moonshine::column
            :colSpan="$columnSpan"
            :adaptiveColSpan="$adaptiveColumnSpan"
        >
            <x-moonshine::card
                url="#"
                :overlay="$overlay"
                :thumbnail="$item->getThumbnail()?->toRawValue()"
                :title="$item->getTitle()?->toRawValue()"
                :subtitle="$item->getSubTitle()?->toRawValue()"
                :values="$item->getFields()->mapWithKeys(fn($field) => [$field->label() => $field->preview()])"
            >
                <x-slot:header>
                    {!! $item->getBadge()?->preview() !!}
                </x-slot:header>
                <x-slot:actions>
                    <x-moonshine::action-group
                        :actions="$item->getActions()"
                    />
                </x-slot:actions>
            </x-moonshine::card>
        </x-moonshine::column>
    @endforeach
</x-moonshine::grid>
