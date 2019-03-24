@extends ('companies/company_layout')

@section ('sub-content')
    <small class="pull-right">
        To help secure your application, you must add a valid website for your code snippet to work.
    </small>

    <a href="/companies/{{ $company->id }}/code_snippet/create" class="btn btn-default btn-sm">
        Create New Snippet
    </a>
    
    <hr>

        <span id="success-message" class="text-success"></span>
        <table class="table">
            <thead>
                <tr>
                    <th>Website Name</th>
                    <th>Url</th>
                    <th>Code Snippet for</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($company->codeSnippetWebsites as $code_snippet)
                    <tr>
                        <td>
                            <a href="/companies/{{ $company->id }}/code_snippet/{{ $code_snippet->id }}/edit">
                                {{ $code_snippet->name }}
                            </a>
                        </td>
                        <td>
                            @if ($code_snippet->public_url)
                                {{ url('maps/'.$code_snippet->public_url) }}
                            @else
                                {{ $code_snippet->url }}
                            @endif
                        </td>
                        <td>
                            {{ Form::select('snippet_id['.$code_snippet->id.']', $networks_array, $code_snippet->network_id, ['id' => $code_snippet->id, 'class' => 'network-dropdown', 'placeholder' => $company->name] ) }}
                        </td>
                        <td>
                            <button class="btn btn-xs btn-default" data-toggle="modal" data-target="#code-snippet-modal-{{ $code_snippet->id }}">
                                <i class="fa fa-code"></i>
                                Get Code Snippet
                            </button>
                            @include ('companies/code_snippet/_code_snippet_modal')
                        </td>
                        <td class="text-right">
                            {{ Form::open(['route' => ['code_snippet.destroy', $code_snippet->company_id, $code_snippet->id], 'method' => 'delete', 'class' => 'confirm-delete']) }}
                                <button class="btn btn-xs btn-danger"><i class="fa fa-times"></i></button>
                            {{ Form::close() }}
                        </td>
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table>

    <script>
        var url = '<?= route('code_snippet.update', ['company_id' => $company->id, 'id' => '']); ?>';
        url += '/'+$(this).attr('id');
        $(".network-dropdown").change(function(){
            $.ajax({
                //url: '<?= route('code_snippet.update', ['company_id' => $company->id, 'id' => '']); ?>'+'/'+$(this).attr('id'),
                url: url,
                method: 'patch',
                data: {'network_id' : $(this).val()},
                success:function() {
                    $("#success-message").html('Networks Updated');
                    setTimeout(function() {
                        $("#success-message").html('');    
                    }, 3000)
                },
                error:function() {
                    alert('Error');
                }
            })            
        })
    </script>
@endsection