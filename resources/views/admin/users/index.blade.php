@extends ('admin.layout')

@section ('sub-content')
    <div class="col-xs-12">
        <a href="/admin/users/create" class="btn btn-default">
            Create
        </a>

        <div class="clearfix"></div>
        <hr>

        <table class="table">
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Company</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>
                            <a href="/admin/users/{{ $user->id}}/edit">
                                {{ $user->name }}
                            </a>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{!! $user->company->name or '<span class="text-danger">No company</span>' !!}</td>
                        <td>{{ $user->role->name or '' }}</td>
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
