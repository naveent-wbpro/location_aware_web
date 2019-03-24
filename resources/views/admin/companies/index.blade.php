@extends ('admin.layout')

@section ('sub-content')
<div class="col-xs-12">
    <a href="/admin/companies/create" class="btn btn-default">
        Create
    </a>

    <div class="clearfix"></div>
    <hr>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Is Visible</th>
                <th>Number of Employees</th>
                <th>Created Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($companies as $company)
                <tr>
                    <td>
                        <a href="/admin/companies/{{ $company->id }}/edit">
                            {{ $company->name }}
                        </a>
                    </td>
                    <td>
                        {{ $company->is_visible == 1 ? 'Yes' : 'No' }}
                    </td>
                    <td>{{ $company->employees->count() }}</td>
                    <td>{{ $company->created_at }}</td>
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
