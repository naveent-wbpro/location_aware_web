@extends ('admin.layout')

@section ('sub-content')

<h3>
    {{ $user->name }}
</h3>

{{ Form::open(['route' => ['admin.users.update', $user->id], 'method' => 'put']) }}
    @include ('admin.users._form')
{{ Form::close() }}

<hr>

<div class="form-group">
    <div class="form-group">
        Resend Email with new password
    </div>
    <a href="/admin/users/{{ $user->id }}/reset_password" onClick="return confirm('Are you sure you want to reset this persons password?')" class="btn btn-default">
        Reset Password
    </a>
</div>

{{ Form::open(['route' => ['admin.users.destroy', $user->id], 'method' => 'delete', 'onsubmit' => 'return confirm("Do you really want to delete this user?");']) }}
    <hr>
    <div class="form-group">
        Would you like to delete this user?
    </div>
    <div class="form-group">
        <button class="btn btn-danger">
            Delete
        </button>
    </div>
{{ Form::close() }}

@endsection
