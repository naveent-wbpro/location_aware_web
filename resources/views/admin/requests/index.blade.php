@extends ('admin.layout')

@section ('sub-content')
    <div class="col-xs-12">
        <table class="table">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Company</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($requests as $request)
                    <tr>
                        <td>
                            <a href="/admin/requests/{{ $request->id }}">
                                {{ $request->customer_name }}
                            </a>
                        </td>
                        @if ($request->company)
                            <td>{{ $request->company->name }}</td>
                        @else
                            <td>No Company Attached</td>
                        @endif
                        <td>{{ $request->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            $(".table").DataTable();        
        })
    </script>
@endsection
