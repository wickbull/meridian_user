<article class="c-news-item o-list__item">
    <a href="{{ $item->getViewUrl() }}" class="link">
        <h3 class="c-news-title">{{ $item->title }}</h3>

        <p class="description">
            {{ $item->getOgDescription() }}
        </p>
    </a>
</article>
