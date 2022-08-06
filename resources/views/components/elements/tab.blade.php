@php($slug = \Str::slug($title))
<div class="tab-pane fade show {{ $active ?? false ? "active" : ""}}" id="tabs-{{ $slug }}" role="tabpanel">
    {{ $slot }}
</div>
