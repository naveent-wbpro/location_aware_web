<div class="form-group">
    <label for="">Name</label>
    <input class="form-control" type="text" name="form_name" value="{{ $form->name }}">
</div>

<hr>

<div class="form-group">
    <button class="btn btn-default btn-xs" id="add-field" type="button">
        Add Field
    </button>
</div>

@foreach ($form->fields as $field)
    <div class="form-fields">
        <div class="form-field well well-sm">
            <i class="fa fa-times-circle text-danger pull-right delete-field"></i>
            <div class="form-group">
                <label for="">Form Field Name</label>
                <input class="form-control" type="text" name="field_name[{{ $field->id }}]" value="{{ $field->name }}">
            </div>
            <div class="form-group">
                <label for="">Form Field Type</label>
                <select id="" class="form-control" name="field_type[{{ $field->id }}]">
                    <option value="text">Textbox</option>
                    <option value="checkbox">Checkbox</option>
                </select>
            </div>
        </div>
    </div>
@endforeach
<div class="form-fields">
    <div class="form-field well well-sm">
        <div class="form-group">
            <label for="">Form Field Name</label>
            <input class="form-control" type="text" name="field_name[]">
        </div>
        <div class="form-group">
            <label for="">Form Field Type</label>
            <select id="" class="form-control" name="field_type[]">
                <option value="text">Textbox</option>
                <option value="checkbox">Checkbox</option>
            </select>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-xs-6">
        <a href="/companies/{{ $company->id }}/forms" class="btn btn-default">
            Back
        </a>
    </div>
    <div class="col-xs-6 text-right">
        <button class="btn btn-success">
            Save
        </button>
    </div>
</div>

<script>
    $("#add-field").click(function() {
        $(".form-fields").children().first().clone().appendTo('.form-fields');              
        $(".form-fields").children().last().find('input').val('');
    });

    $(".delete-field").click(function() {
        $(this).parent().parent().remove();                
    })
</script>
