@if($value)
    @if($unit == "m²")@php($value = number_format($value, 2))@endif

    <div class="grid sm:grid-cols-2">
        <div>{{ $title }}:</div>
        <div>{{ $value }} {{ $unit ?? "" }}</div>
    </div>
@endif
