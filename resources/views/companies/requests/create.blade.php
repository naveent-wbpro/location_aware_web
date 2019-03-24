@extends ('layouts.request_popup')

@section ('content')
    <div class="row">
    <div class="col-xs-12 col-sm-8 col-sm-offset-2 text-center">
        <div class="media">
            <div class="media-left">
                            </div>
            <div class="media-body">
                @if ($instant_response == false)
                    <h4 class="media-heading">Requesting: {{ $company->name }}</h4>
                    {{ $company->description }}
                @else
                    <h4 class="media-header">Instant Response</h4>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-8 col-sm-offset-2">
        @if (isset($employee))
            <h4>You requested service from {{ $employee->name }}</h4>
            @if (count($employee->profilePhoto) == 1)
                <img class="pull-left" src="{{ $employee->profilePhoto->url }}" alt="Employee Image" style="max-height: 100px; max-width: 100px; margin-right: 5px;">
            @endif
            <p>
                @if (!empty($employee->trade))
                    Trade : {{ $employee->trade->name  or ''}}
                @endif
            </p>
        @endif
    </div>

    <div class="clearfix"></div>
    <hr>

    {{ Form::open(['route' => ['companies.{company_id}.requests.store', $company->id], 'id' => 'request-form']) }}
        <div class="col-xs-12 col-sm-8 col-sm-offset-2 ">
            <h4>Job Information</h4>
            <div class="form-group">
                <label for="">Customer Name</label>
                <input type="text" class="form-control" name="customer_name" value="{{ old('customer_name', $name) }}" placeholder="John Smith">
            </div>
            <div class="form-group">
                <label for="">Customer Phone Number For Notifications</label>
                <div class="row">
                    <div class="col-xs-7">
                        <input type="text" class="form-control" name="customer_phone_number" value="{{ old('customer_phone_number', $phone_number) }}" placeholder="(770) 555-1234">
                    </div>
                    <div class="col-xs-5">
                        {{ Form::select('customer_phone_number_type', [1 => 'Cell', 2 => 'Home'], old('customer_phone_number_type'), ['class' => 'form-control']) }}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="">Customer Email For Notifications</label>
                <input type="text" class="form-control" name="customer_email" value="{{ old('customer_email', $email) }}" placeholder="johnsmith@email.com">
            </div>
            <div class="form-group">
                <label for="">Address</label>
                <input type="name" class="form-control" name="customer_address" value="{{ old('customer_address', $street_address) }}" placeholder="Street Address">
            </div>
            <div class="form-group">
                <input type="name" class="form-control" name="customer_zipcode" value="{{ old('customer_zipcode', $zipcode) }}" placeholder="Zip Code">
            </div>
            <div class="form-group">
                <input type="checkbox" value="1" name="schedule_for_later" id="schedule-button">
                <label for="schedule-button">Would you like to schedule this for a later date?</label>
            </div>
            <div class="form-group row hidden" id="future-date-container">
                @if (!empty($company_generated))
                    <div class="form-group col-xs-12">
                        <a href="#" onClick="window.open('/companies/{{ $company->id }}/calendar?popup=true', 'Calendar', 'width=800, height=800'); return false">
                            <i class="fa fa-calendar"></i>
                            View Calendar
                        </a>
                    </div>
                @endif
                <div class="form-group col-xs-12 col-sm-6">
                    <label for="date">Future Date</label>
                    <input type="date" class="form-control" name="scheduled_date">
                </div>
                <div class="form-group col-xs-12 col-sm-6">
                    <label for="time-window">Time Window</label>
                    {{ Form::select('scheduled_time_window_id', $time_windows, '', ['class' => 'form-control'])}}
                </div>
            </div>

            @if ($forms)
                @foreach ($forms as $form)
                    @foreach ($form->fields as $field)
                        <div class="form-group">
                            @if ($field->input_type === 'text')
                                <label for="">{{ $field->name }}</label>
                                <br>
                                <input type="text" name="custom_fields[{{ $field->id }}]" class="form-control">
                            @else
                                <input type="checkbox" name="custom_fields[{{ $field->id }}]" value="1" id="{{ $field->id }}">
                                <label for="{{ $field->id }}">{{ $field->name }}</label>
                            @endif
                        </div>
                    @endforeach
                @endforeach
            @endif
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-6">
                        @if (!empty($company_generated))
                            <a class="btn btn-default" href="/companies/{{ $company->id }}/requests">
                                Cancel
                            </a>
                        @endif
                    </div>
                    <div class="col-xs-6 text-right">
                        <button
                            class="g-recaptcha btn btn-success"
                            data-sitekey="6LdqBEEUAAAAAOAqv2Nzs7SBq54nHegn6kD25If3"
                            data-callback="formCallback">
                            Submit Request
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="company_id" value="{{ $company->id }}">
        @if (isset($employee))
            <input type="hidden" name="employee_id" value="{{ $employee->id }}">
        @endif

        @if ($instant_response == 1)
            <input type="hidden" name="instant_response" value="1">
        @endif

        @if (!empty($network_id) && $network_id !== 'undefined')
            <input type="hidden" name="network_id" value="{{ $network_id }}">
        @endif
        @if (!empty($office) && $office !== 'undefined') 
            <input type="hidden" name="office_id" value="{{ $office->id }}">
        @endif
        @if (!empty($company_generated))
            <input type="hidden" name="company_generated" value="1">
        @endif
            
    {{ Form::close() }}
</div>

<script>
    $("#schedule-button").click(function() {
        $("#future-date-container").toggleClass('hidden');        
    })
    function formCallback(data) {
        document.getElementById("request-form").submit();
    }
</script>

@endsection
