<div class="form-group">
    <input class="form-control" type="text" name="name" value="{{ $code_snippet->name or old('name') }}" placeholder="Website Name">		
</div>

<div class="form-group">
    <input class="form-control" type="text" name="url" value="{{ $code_snippet->url or old('url') }}" placeholder="Company URL : www.mywebsite.com">
</div>
<div class="form-group">
    {{ Form::checkbox('public_url', 1, $code_snippet->public_url, ['id' => 'public_url']) }}
    <label for="public_url">Enable a public url for this code snippet</label>
</div>

<div class="row">
    <div class="col-xs-6">
        <a href="/companies/{{ $company->id }}/code_snippet" class="btn btn-default">
            Back
        </a>
    </div>
    <div class="col-xs-6 text-right">
        <button class="btn btn-success">
            Save
        </button>
    </div>
</div>
