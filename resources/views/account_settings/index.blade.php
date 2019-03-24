@extends ('layouts.app')

@section ('content')
<div class="row">
<div class="col-xs-12 col-sm-10 col-sm-1 col-md-8 col-md-offset-2">
        <h3>
            Account Settings
            <small>
                <a href="/">
                    Go Back
                </a>
            </small>
        
        </h3>

    <hr>
    {{ Form::open(['action' => 'AccountSettingsController@update', 'method' => 'put']) }}
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                User Account Information
            </h3>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="{{ $user->name }}">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" class="form-control" value="{{ $user->email }}">
            </div>
            <div class="form-group">
                <label for="timezone">Timezone</label>
                {{ Form::select('timezone', ['' => 'Choose a timezone'] + $timezones, $user->timezone, ['class' => 'form-control']) }}
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">
                Password Change
            </h3>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="form-group">
                <label>Confirm New Password</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>    
        </div>
    </div>

    <div class="row">
        <div class="col-xs-6">
            <a href="/" class="btn btn-default">
                Cancel
            </a>
        </div>
        <div class="col-xs-6 text-right">
            <button class="btn btn-success">
                Update
            </button>
        </div>
    </div>
    {{ Form::close() }}
</div>
</div>
@endsection
