<div class="form-group">
    <label for="name">Name</label>
    {{ Form::text('name', $user->name, ['class' => 'form-control']) }}
</div>

<div class="form-group">
    <label for="email">Email</label>
    {{ Form::text('email', $user->email, ['class' => 'form-control']) }}
</div>

<div class="form-group">
    <label for="role">Role</label>
    {{ Form::select('role_id', $roles, $user->role_id, ['class' => 'form-control', 'id' => 'role_id']) }}    
</div>

<div class="form-group {{ ($user->role_id != null && $user->role_id != 1) ? '' : 'hidden' }}" id="company-name-container">
    <label>Company Name</label>
    {{ Form::select('company_id', $companies, ($user->company == null ? '' : $user->company->id), ['class' => 'form-control']) }}
</div>

<div class="form-group">
    <button class="btn btn-default">
        Save
    </button>
</div>

<script>
    $("#role_id").on('change', function() {
        if($(this).val() == 1) {
            $("#company-name-container").addClass('hidden');
        } else {
            $("#company-name-container").removeClass('hidden');
        }
    })
</script>
