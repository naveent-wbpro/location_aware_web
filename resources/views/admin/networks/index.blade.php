@extends ('admin.layout')

@section ('sub-content')
    <table class="table">
        <thead>
            <tr>
                <th>
                    ID
                </th>
                <th>Network Name</th>
                <th>
                    Owner
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($networks as $network)
                <tr>
                    <td>
                        {{ $network->id }}
                    </td>
                    <td>
                        <a href="/admin/networks/{{ $network->id }}">
                            {{ $network->name }}
                        </a>
                    </td>
                    <td>
                        @if (!empty($network->owner))
                            <a href="/admin/companies/{{ $network->owner->id }}/edit">
                                {{ $network->owner->name }}
                            </a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            $(".table").DataTable();        
        })
    </script>
@endsection
