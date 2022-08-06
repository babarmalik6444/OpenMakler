<nav class="md:w-56 z-50 hidden md:block sidebar transition ease-in duration-75 bg-oxford-blue-500 text-blue-gray-300" :class="{'w-18': sidebar, 'md:w-56': !sidebar}">
    <ul class="flex flex-col min-h-screen">
        {{-- Sidebar Profile --}}
        @php($user = auth()->user())
        <li class="relative">
            <img src="{{ asset("assets/img/header-profile.png") }}" alt="header-profile" class="absolute w-full h-full max-w-none top-0 left-0">
            <div class="relative z-10 side-profile px-6 py-5 text-center" x-data="{ open: false }" @keydown.escape="open = false">
                <img alt="image" class="rounded-full w-12 mx-auto mb-2"
                     src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80">
                <span class="block text-xs font-semibold" :class="{'text-white': open, 'text-blue-gray-400': !open}">
                    {{ $user->name }}
                </span>
                <span class="text-xs text-oxford-blue-400">
                    {{ $user->company_id ? $user->company->getName() : $user->userRole->getName() }}
                </span>
            </div>
            <div class="logo-element hidden text-white text-lg font-semibold relative z-10 py-4 px-6">
                OM
            </div>
        </li>

        @foreach($sidebarItems as $item)
            @php($hasChildren = !empty($item["children"]))
            <li x-data="{opened: {{ $item["selected"] || $item["opened"] ? "true" : "false" }} }"
                class="trnstsn border-l-4 border-transparent"
                :class="{'bg-oxford-blue-700 border-primary-500 dd-active': opened}">
                <a @if(!empty($item["route"]))href="{{ route($item["route"], $item["routeParams"] ?? []) }}" @else @click.prevent="opened = !opened" @endif
                aria-expanded="true"
                   class="px-6 py-4 gap-3 text-sm items-center font-semibold flex justify-between hover:bg-oxford-blue-700 hover:text-white cursor-pointer"
                   :class="{'text-blue-gray-300': !opened, 'text-white': opened}">
                    <x-icon :name="$item['icon']" class="w-3"></x-icon>
                    <span class="flex-1 nav-label">{{ $item["label"] }}</span>

                    @if(!empty($item["flag"]["label"]))
                        <span class="nav-badge inline-flex items-center justify-center px-1 py-0.5 text-[10px] uppercase font-semibold leading-none text-white rounded-sm {{ $item["flag"]["class"] }}">
                            {{ $item["flag"]["label"] }}
                        </span>
                    @endif

                    @if($hasChildren)
                        <span class="trnstsn transforrm nav-arrow" :class="{'-rotate-90': opened}">
                            <x-icon name="fas-angle-left" class="w-2"></x-icon>
                        </span>
                    @endif
                </a>

                @if($hasChildren)
                    <ul class="dropdown-menu transition-all max-h-0 duration-700 overflow-hidden"
                        aria-expanded="true"
                        x-ref="container{{ $loop->index }}"
                        x-bind:style="opened ? 'max-height: ' + $refs.container{{ $loop->index }}.scrollHeight + 'px' : ''">
                        @foreach($item["children"] as $child)
                            <li>
                                <a href="{{ route($child["route"], $child["routeParams"] ?? []) }}"
                                   class="px-6 py-2 pl-14 inline-flex gap-3 text-xs items-center hover:text-white
                                        @if($child["selected"]) text-white font-bold @endif">
                                    {{ $child["label"] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
</nav>
