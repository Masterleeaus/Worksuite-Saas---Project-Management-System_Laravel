<div class="t-card {{ $t->is_featured ? 'featured' : '' }}">
    {{-- Before / After photos --}}
    @if($t->before_photo && $t->after_photo)
    <div class="t-card-before-after">
        <div>
            <img src="{{ $t->file($t->before_photo) }}" alt="{{ __('Before') }}">
            <div class="label before">{{ __('Before') }}</div>
        </div>
        <div>
            <img src="{{ $t->file($t->after_photo) }}" alt="{{ __('After') }}">
            <div class="label after">{{ __('After') }}</div>
        </div>
    </div>
    @elseif($t->client_image)
    <div class="t-card-photo">
        <img src="{{ $t->client_image }}" alt="{{ $t->display_name }}">
    </div>
    @endif

    <div class="t-card-body">
        @if($t->is_featured)
        <span class="t-featured-badge">★ {{ __('Featured') }}</span>
        @endif

        {{-- Star rating --}}
        <div class="t-stars">
            @for($i = 1; $i <= 5; $i++)
                @if($i <= ($t->star_rating ?? 5))
                    ★
                @else
                    <span>☆</span>
                @endif
            @endfor
        </div>

        {{-- Quote --}}
        <p class="t-quote">"{{ $t->content ?: $t->description }}"</p>

        {{-- Video --}}
        @if($t->video_url)
        <div class="t-video">
            @php
                $videoUrl = $t->video_url;
                // Convert YouTube watch URL to embed
                if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $videoUrl, $m)) {
                    $videoUrl = 'https://www.youtube.com/embed/' . $m[1];
                } elseif (preg_match('/youtu\.be\/([^?]+)/', $videoUrl, $m)) {
                    $videoUrl = 'https://www.youtube.com/embed/' . $m[1];
                } elseif (preg_match('/vimeo\.com\/(\d+)/', $videoUrl, $m)) {
                    $videoUrl = 'https://player.vimeo.com/video/' . $m[1];
                }
            @endphp
            <iframe src="{{ $videoUrl }}" allowfullscreen loading="lazy"></iframe>
        </div>
        @endif

        {{-- Meta --}}
        <div class="t-meta">
            <span class="name">{{ $t->customer_name ?: $t->client_name }}</span>
            @if($t->suburb)
                <span class="suburb">📍 {{ $t->suburb }}</span>
            @endif
            @if($t->service_type)
                <span class="service">{{ ucfirst($t->service_type) }}</span>
            @endif
            @if($t->source && $t->source !== 'manual')
                <span class="source">via {{ ucfirst($t->source) }}</span>
            @endif
        </div>
    </div>
</div>
