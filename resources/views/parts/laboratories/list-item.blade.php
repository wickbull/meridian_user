<article class="c-news-item o-list__item">
    <a href="{{ $laboratory->getViewUrl() }}" class="link">
        <h3 class="c-news-title">{{ $laboratory->title }}</h3>

        <p class="description">
            {{ $laboratory->getOgDescription() }}
        </p>
    </a>
</article>
