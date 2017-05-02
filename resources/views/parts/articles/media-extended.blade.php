<article class="c-news-item o-list__item c-news-item--media">
    <a href="{{ $item->getViewUrl() }}" class="link">

        <div class="js-img c-img c-img--lg">
            <img src="{{ $item->getImage('750x450', 'default') }}" srcset="{{ $item->getImage('1500x900', 'default') }} 2x" alt="{{ $item->title }}" width="750" height="450">
        </div>

        <div class="inner">
            <h3 class="c-news-title">{{ $item->title }}</h3>

            @if ($item->image_storage_id)
                <div class="o-type">
                    @foreach ($item->statuses as $status)
                        <span class="item">
                            <i class="icon {{ $status->slug }}" aria-hidden="true"></i>
                        </span>
                    @endforeach
                </div>
            @endif

            @if (!$item->categories->isEmpty())
                <span class="category">
                    @foreach ($item->categories as $category)
                        {{ $category->title }}
                    @endforeach
                </span>
            @endif

            <time class="date" pubdate datetime="{{ $item->getSimplePublishDate() }}">
                {{ $item->getSimplePublishDate() }}
            </time>
        </div>

    </a>
</article>
