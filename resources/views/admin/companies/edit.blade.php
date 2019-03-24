@extends ('admin.layout')

@section ('sub-content')
    <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        <div class="form-group">
            {{ Form::open(['route' => ['admin.companies.update', $company->id], 'method' => 'put']) }}
                @include ('admin.companies._form')
            {{ Form::close() }}
        </div>
        
        <hr>

        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Role</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($company->employees as $employee)
                    <tr>
                        <td>
                            <a href="/admin/users/{{ $employee->id }}/edit">
                                {{ $employee->name }}
                            </a>
                        </td>
                        <td>
                            {{ $employee->role->name }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <hr>
        
        
        <div class="form-group">
            <label for="">Networks</label>
            <ul>
                @forelse ($company->allNetworks as $network)
                    <li>
                        <a href="/admin/networks/{{ $network->id }}">
                            {{ $network->name }}
                        </a>
                        @if ($network->company_id == $company->id)
                            <small>(Owner)</small>
                        @else
                            <small>(Belongs To)</small>
                        @endif
                    </li>
                @empty
                    <li>
                        No networks 
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection
