<article class="c-news-item c-news-item--default">
    <a href="{{ $item->getViewUrl() }}" class="link">

        <div class="js-img c-img c-img--sm">
            <img src="{{ $item->getImage('200x200', 'square') }}" srcset="{{ $item->getImage('400x400', 'square') }} 2x" alt="{{ $item->title }}" width="200" height="200">
        </div>

        <div class="inner">
            <h3 class="c-news-title">{{ $item->title }}</h3>

            @if ($item->image_storage_id)
                @foreach ($item->statuses as $status)
                    <i class="icon {{ $status->slug }}" aria-hidden="true"></i>
                @endforeach
            @endif

            <time class="date" pubdate datetime="{{ $item->getSimplePublishDate() }}">
                {{ $item->getSimplePublishDate() }}
            </time>
        </div>
    </a>
</article>
