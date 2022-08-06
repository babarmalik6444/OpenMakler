<div id="carouselExampleCaptions" class="carousel slide relative" data-bs-ride="carousel">
    <div class="carousel-indicators absolute right-0 bottom-0 left-0 flex justify-center p-0 mb-4">
        @foreach($items AS $item)
            <button
                type="button"
                data-bs-target="#carouselExampleCaptions"
                data-bs-slide-to="{{ $loop->index }}"
                @if($loop->first)class="active" @endif
                aria-current="true"
                aria-label="Slide {{ $loop->index + 1 }}"
            ></button>
        @endforeach
    </div>
    <div class="carousel-inner relative w-full overflow-hidden">
        @foreach($items AS $item)
            <div class="carousel-item relative float-left w-full {{ $loop->first ? 'active' : '' }}">
                <div class="imgContainer">
                    <img src="{{ $item["src"] }}" class="block w-full" alt="{{ $item["alt"] ?? "" }}" />
                </div>
                @if($item["title"] ?? false)
                    <div class="carousel-caption hidden md:block absolute text-center">
                        <h5 class="text-xl">{{ $item["title"] }}</h5>
                        @if(!empty($item["teaser"]))<p>{{ $item["teaser"] }}</p>@endif
                    </div>
                @endif
            </div>
        @endforeach
    </div>
    <button
        class="carousel-control-prev absolute top-0 bottom-0 flex items-center justify-center p-0 text-center border-0 hover:outline-none hover:no-underline focus:outline-none focus:no-underline left-0"
        type="button"
        data-bs-target="#carouselExampleCaptions"
        data-bs-slide="prev"
    >
        <span class="carousel-control-prev-icon inline-block bg-no-repeat" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button
        class="carousel-control-next absolute top-0 bottom-0 flex items-center justify-center p-0 text-center border-0 hover:outline-none hover:no-underline focus:outline-none focus:no-underline right-0"
        type="button"
        data-bs-target="#carouselExampleCaptions"
        data-bs-slide="next"
    >
        <span class="carousel-control-next-icon inline-block bg-no-repeat" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<style>
    .imgContainer {
        width: 100%;
        padding-top: 66.66%; /* 3:2 Aspect Ratio (divide 2 by 3 = 0.6666)  */
    }
    .imgContainer img {
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
    }
</style>
