@extends ('layouts.app')

@section ('content')
    <h2>
        Company Comparisons
    </h2>

    <table class="table">
        <thead>
            <tr>
                <th></th>
                @foreach ($companies as $company)
                    <th>
                        <a href="/companies/{{ $company->id }}">
                            {{ $company->name }}
                        </a>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Years of Experience</td>
                @foreach ($companies as $company)
                    <td>{{ $company->years_in_business or 'N/A' }}</td>
                @endforeach
            </tr>
            <tr>
                <td>Reviews Rating</td>
                @foreach ($companies as $company)
                    <td>
                        {{ clean_number_format(reverse_rating($company->getAverageRating())) }} / 4
                    </td>
                @endforeach
            </tr>
            <tr>
                <td>Number of Jobs Completed</td>
                @foreach ($companies as $company)
                    <td>{{ $company->closedRequests->count() }}</td>
                @endforeach
            </tr>
            <tr>
                <td>IICRC Certified</td>
                @foreach ($companies as $company)
                    <td>{{ $company->iicrc_certified ? 'Yes' : 'No' }}</td>
                @endforeach
            </tr>
            <tr>
                <td>Contractor Warranty</td>
                @foreach ($companies as $company)
                    <td>{{ $company->contractor_warranty ? 'Yes' : 'No' }}</td>
                @endforeach
            </tr>
            <tr>
                <td>Credentialed</td>
                @foreach ($companies as $company)
                    <td>{{ $company->credentialed ? 'Yes' : 'No' }}</td>
                @endforeach
            </tr>
        </tbody>
    </table>


@endsection
