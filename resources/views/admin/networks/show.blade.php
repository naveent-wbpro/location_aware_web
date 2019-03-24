@extends ('admin.layout')

@section('sub-content')
    <h3>
        Network : {{ $network->name }}
    </h3>

    <ul>
        @forelse ($network->companies as $company)
            <li>
                <a href="/admin/companies/{{ $company->id }}/edit">
                    {{ $company->name }}
                </a>
                @if ($network->owner->id === $company->id)
                    (Is owner of network)
                @endif
            </li>
        @empty
            <li>
                No companies
            </li>
        @endforelse
    </ul>
@endsection
