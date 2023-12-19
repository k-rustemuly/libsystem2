@props([
    'thumbnail' => '',
])
@if($thumbnail)
    <div style="display: flex;justify-content: center;align-content: center;">
        <img src="{{ $thumbnail }}" />
    </div>
@endif
