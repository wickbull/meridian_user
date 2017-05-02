<ul class="c-carousel js-carousel">

    @foreach($photos as $photo)
        <li class="c-slide">
            <img src="{{ $photo->getPhoto('750x500') }}" srcset="{{ $photo->getPhoto('1500x1000') }} 2x" alt="{{ $photo->title }}">
        </li>
    @endforeach

</ul>
