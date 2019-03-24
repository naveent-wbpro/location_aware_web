@extends ('admin.layout')

@section ('sub-content')
    <a href="/admin/ads/create">Create new ad</a>

    <div class="clearfix"></div>
    <hr>
    
    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Url</th>
                <th>Posted At</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ads as $ad)
                <tr>
                    <td>
                        {{ $ad->title }}
                    </td>
                    <td>
                        {{ $ad->url }}
                    </td>
                    <td>
                        {{ $ad->posted_at }}
                    </td>
                    <td>
                        <a href="/admin/ads/{{ $ad->id }}/edit">
                            Edit
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
