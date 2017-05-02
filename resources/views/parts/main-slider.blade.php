<a href="{{ $item->getViewUrl() }}" class="link">
    <div class="o-type">
        @foreach ($item->statuses as $status)
            <span class="item"><i class="icon {{ $status->slug }}" aria-hidden="true"></i></span>
        @endforeach
    </div>

    @if ($item->image_storage_id)
        <div class="js-img c-img c-img--lg">
            <img src="{{ $item->getImage('750x450') }}" srcset="{{ $item->getImage('1500x900') }} 2x" alt="{{ $item->title }}" width="750" height="450">
        </div>
    @endif

    <div class="header">
        <h3 class="title">{{ $item->title }}</h3>

        <span class="category">
            @foreach ($item->categories as $category)
                {{ $category->title }}
            @endforeach
        </span>

        <time class="date" pubdate datetime="{{ $item->getSimplePublishDate() }}">
            {{ $item->getSimplePublishDate() }}
        </time>
    </div>
</a>
