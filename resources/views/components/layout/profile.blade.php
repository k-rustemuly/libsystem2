@props([
    'avatar',
    'name' => '',
    'username' => '',
    'before',
    'after',
])
<div {{ $attributes->merge(['class' => 'profile']) }}>
    {{ $before ?? '' }}
    <div class="menu-profile-photo">
        <img class="h-16 h-full w-full object-cover" src="{{ $avatar }}" alt="{{ $nameOfUser }}"/>
    </div>
    <div class="menu-profile-info">
        <h5 class="name">{{ $nameOfUser }}</h5>
        <div class="email">{{ $username }}</div>
    </div>
    {{ $after ?? '' }}
</div>
