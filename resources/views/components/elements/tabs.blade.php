{{--@php($keys = array_keys($items))--}}
<div>
    <ul class="nav nav-tabs flex flex-col md:flex-row flex-wrap list-none border-b-0 pl-0 mb-4" id="tabs-tabFill" role="tablist">
        @foreach($items AS $key)
            @php($slug = \Str::slug($key))
            <li class="nav-item flex-auto text-center" role="presentation">
                <a href="#tabs-{{ $slug }}" class="
                  nav-link
                  w-full
                  block
                  text-lg
                  text-xs
                  leading-tight
                  uppercase
                  border-x-0 border-t-0 border-b-2
                  px-6
                  py-3
                  my-2
                  hover:border-transparent hover:bg-gray-100
                  focus:border-transparent
                  {{ $loop->first ? "active" : ""}}"
                   id="tabs-home-tabFill"
                   data-bs-toggle="pill"
                   data-bs-target="#tabs-{{ $slug }}"
                   role="tab"
                   aria-controls="tabs-{{ $slug }}"
                   aria-selected="true">{{ $key }}</a>
            </li>
        @endforeach
    </ul>
    <div class="tab-content" id="tabs-tabContentFill">
        {{ $slot }}
    </div>
</div>
