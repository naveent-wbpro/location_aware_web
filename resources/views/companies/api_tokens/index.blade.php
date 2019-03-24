@extends ('companies.company_layout')

@section ('sub-content')
    <a href="/companies/{{ $company->id }}/api_tokens/create" class="">
        Create Token
    </a>

    <table class="table">
        <tr>
            <th>
                Access Token
            </th>
            <th>
                Last Accessed
            </th>
        </tr>
        @forelse ($company->apiTokens as $api_token)
            <tr>
                <td>
                    {{ $api_token->access_token }}
                </td>
                <td>
                    {{ $api_token->last_accessed }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan=2>
                    No api tokens 
                </td>
            </tr>
        @endforelse
    </table>

@endsection
