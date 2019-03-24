<div class="form-group">
    <label>Company Name</label>
    <input type="text" class="form-control" name="name" value="{{ $company->name }}">
</div>
<div class="form-group">
    <label>Email</label>
    <input type="text" class="form-control" name="email" value="{{ $company->email }}">
</div>
<div class="form-group">
    {{ Form::checkbox('is_visible', 1, $company->is_visible, ['id' => 'is_visible']) }}
    <label for="is_visible">Is Visible</label> (Will only be visible if the company has also been credentialed)
</div>
<div class="form-group">
    {{ Form::checkbox('iicrc_certified', 1, $company->iicrc_certified, ['id' => 'iicrc_certified']) }}
    <label for="iicrc_certified">IICRC Certified</label>
</div>
<div class="form-group">
    {{ Form::checkbox('credentialed', 1, $company->credentialed, ['id' => 'credentialed']) }}
    <label for="credentialed">Credentialed</label>
</div>

<div class="form-group">
    <button class="btn btn-success">
        Save
    </button>
</div>
