    <div class="panel panel-primary company-calandar">
        <div class="panel-heading">
            <div class="col-md-7">Requests Calendar</div>
            <div class="col-md-3">
                @if (isset($employees))
                    {{ Form::open(array('url' => 'companies/'.$company->id.'/calendar','id'=>'target')) }}
                        <select name="selected_employee" class="form-control input-sm employee-dropdown">
                            <option value="">All Employees</option> 
                            @foreach ($employees as $employee)
                                <option {{ $validate == $employee->id ? 'selected' : '' }} value="{{ $employee->id }}"> {{ $employee->name }}</option>
                            @endforeach
                        </select>
                    {{ Form::close() }}
                @endif
            </div>

            <div class="col-md-2"> 
                <a class="btn btn-default btn-xs pull-right" href="calendar/download">Export Calendar</a>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="panel-body" >
            {!! $calendar->calendar() !!}
            {!! $calendar->script() !!} 
        </div>    
    </div>
<script>
$(".employee-dropdown").change(function(){

        $( "#target" ).submit();     
        })
</script>
