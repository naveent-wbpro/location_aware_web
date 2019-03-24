@extends ('companies/company_layout')

@section ('sub-content')
    <a href="/companies/{{ $company->id }}/forms/create" class="btn btn-default btn-sm">
        Create New Form
    </a>
    <hr>

    <span id="success-message"></span>
    <table class="table">
        @foreach ($forms as $form)
            <tr>
                <td>
                    <a href="/companies/{{ $company->id }}/forms/{{ $form->id }}/edit">
                        {{ $form->name }}
                    </a>
                </td>
                <td>
                    {{ $form->fields->count() }} Fields
                </td>
                <td>
                    {{ Form::select('code_snippet', ['' => 'Pick a map', 0 => 'Internal Requests'] + $code_snippets, $form->code_snippet_website_id, ['class' => 'form-dropdown', 'id' => $form->id]) }}
                </td>
                <td>
                    {{ Form::open(['route' => ['companies.{company_id}.forms.destroy', $company->id, $form->id], 'method' => 'delete', 'onSubmit' => 'return confirm("Are you sure you want to delete this form?")']) }}
                        <button class="btn btn-danger btn-xs">
                            <i class="fa fa-times"></i>
                        </button>
                    {{ Form::close() }}
                </td>
            </tr>
        @endforeach
    </table>

    <script>
        $(".form-dropdown").change(function(){
            $.ajax({
                url: '<?= route('companies.{company_id}.forms.update_code_snippets',['company_id' => $company->id]); ?>?form_id='+$(this).attr('id'),
                method: 'post',
                data: {'code_snippet_website_id' : $(this).val()},
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
