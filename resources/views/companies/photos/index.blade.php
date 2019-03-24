@extends ('companies.show')

@section ('sub-content')
    @if ($can_edit)
        <a href="/companies/{{ $company->id }}/photos/create" class="btn btn-default">
            Edit Photos
        </a>
    @endif
    <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-start-slideshow="true">
        <div class="slides"></div>
        <h3 class="title"></h3>
        <a class="prev">‹</a>
        <a class="next">›</a>
        <a class="close">×</a>
        <a class="play-pause"></a>
        <ol class="indicator"></ol>
    </div>
    <div id="contractors">
        @forelse ($photos as $photo)
            <a href="{{ $photo->url }}" data-gallery>
                <div class="photo-container pull-left">
                    <img src="{{ $photo->url }}" style="height: 200px">
                </div>
            </a>
        @empty
            No photos available
        @endforelse
    </div>

@endsection
