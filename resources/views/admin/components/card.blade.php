<x-moonshine::grid>
    @foreach($element->items() as $item)
        <x-moonshine::column
            :colSpan="$element->columnSpanValue()"
            :adaptiveColSpan="$element->adaptiveColumnSpanValue()"
        >
            <x-moonshine::card
                url="#"
                :overlay="$element->isOverlay()"
                :thumbnail="$element->getThumbnail($item)"
                :title="$element->getTitle($item)"
                :subtitle="$element->getSubTitle($item)"
                :values="[]"
            >
                <x-slot:header>
                    <x-moonshine::badge color="green">{{ $element->getBadge($item) }}</x-moonshine::badge>
                </x-slot:header>
                {{-- d --}}
                <x-slot:actions>
                    @foreach($element->getActions($item) as $action)
                        {{ $action->render() }}
                    @endforeach
                </x-slot:actions>
            </x-moonshine::card>
        </x-moonshine::column>
    @endforeach
</x-moonshine::grid>
