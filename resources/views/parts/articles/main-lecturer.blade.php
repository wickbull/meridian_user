<div class="o-list__item">
    <article class="c-card">
        <a href="{{ $item->getViewUrl() }}" class="link">

            @if ($item->image_storage_id)
                <div class="photo">
                    <img src="{{ $item->getImage('128x128') }}" srcset="{{ $item->getImage('256x256') }} 2x" alt="{{ $item->title }}" width="128" height="128">
                </div>
            @endif

            <div class="header">
                <h4 class="title">{{ $item->title }}</h4>
                <p class="info">{{ $item->position }}</p>
            </div>
        </a>
    </article>
</div>
