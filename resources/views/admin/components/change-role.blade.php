@props([
    'route',
    'toRoute',
    'avatar',
    'name' => '',
    'username' => '',
    'before',
    'after',
])
    {{ $before ?? '' }}
        <div class="menu-profile">
            <a href="{{ $route }}"
                class="menu-profile-main"
            >
                <div class="menu-profile-photo">
                    <img class="h-full w-full object-cover"
                        src="{{ $avatar }}"
                        alt="{{ $nameOfUser }}"
                    />
                </div>
                <div class="menu-profile-info">
                    <h5 class="name">{{ $nameOfUser }}</h5>
                    <div class="email">{{ $username }}</div>
                </div>
            </a>

            <a href="{{ $toRoute }}"
                class="menu-profile-exit"
                title="Logout"
            >
                <x-moonshine::icon
                    icon="heroicons.arrow-path-rounded-square"
                    color="gray"
                    size="6"
                />
            </a>
    </div>
    {{ $after ?? '' }}
