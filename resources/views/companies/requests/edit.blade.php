@extends ('companies.company_layout')

@section ('sub-content')
    <p>
        All times are displayed in the {{ \Auth::user()->timezone  }} timezone.
    </p>
    <p class="lead">
        {{ $request->customer_name }}
    </p>

    <div class="form-group">
        <b>Elapsed Time Since Request</b>
        <br>
        {{ $request->created_at->diffForHumans() }} - {{ $request->created_at->timezone(\Auth::user()->timezone)->format('F jS, Y \a\t h:ia T') }}
    </div>

    @if ($request->scheduled_date !== null)
        <div class="form-group">
            <b>This customer has requested service at a future date</b>
            <br>
            {{ $request->scheduled_date->format('F jS, Y') }} from {{ $request->timeWindow->description }}
        </div>
    @endif
    <div class="row text-center">
        <div class="col-xs-12 col-sm-4 col-md-3">
            <div class="panel panel-{{ is_null($request->contacted_on) ? 'danger' : 'success' }}">
                <div class="panel-heading">
                    1. Contact Customer
                </div>
                <div class="panel-body">
                    @if (is_null($request->contacted_on))
                        {{ Form::open(['route' => ['requests.update', $company->id, $request->id], 'method' => 'put']) }}
                            <input type="hidden" name="request_status" value="contacted_on">
                            <button class="btn btn-lg btn-danger">        
                                <i class="fa fa-2x fa-phone"></i>
                            </button>
                        {{ Form::close() }}
                    @else
                        <button class="btn btn-lg btn-success">
                            <i class="fa fa-2x fa-check"></i>
                        </button>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-3">
            <div class="panel panel-{{ is_null($request->assigned_on) ? 'danger' : 'success' }}">
                <div class="panel-heading">
                    2. Assign To Employee
                </div>
                <div class="panel-body">
                    @if (is_null($request->contacted_on))
                        <button class="btn btn-lg btn-danger">                
                            <i class="fa fa-2x fa-user"></i>
                        </button>
                    @elseif(!is_null($request->contacted_on) && is_null($request->assigned_on))
                        <a href="/companies/{{ $company->id }}/requests/{{ $request->id }}/assignments" button class="btn btn-lg btn-danger">
                            <i class="fa fa-2x fa-user"></i>
                        </a>
                    @else
                        <a href="/companies/{{ $company->id }}/requests/{{ $request->id }}/assignments" button class="btn btn-lg btn-success">
                            <i class="fa fa-2x fa-check"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-3">
            <div class="panel panel-{{ is_null($request->arrived_on) ? 'danger' : 'success' }}">
                <div class="panel-heading">
                    3. Arrived At Customer
                </div>
                <div class="panel-body">
                    @if (is_null($request->assigned_on))
                        <button class="btn btn-lg btn-danger">                
                            <i class="fa fa-2x fa-truck"></i>
                        </button>
                    @elseif(!is_null($request->assigned_on) && is_null($request->arrived_on))
                        {{ Form::open(['route' => ['requests.update', $company->id, $request->id], 'method' => 'put']) }}
                            <input type="hidden" name="request_status" value="arrived_on">
                            <button class="btn btn-lg btn-danger">
                                <i class="fa fa-2x fa-truck"></i>
                            </button>
                        {{ Form::close() }}
                    @else
                        <button class="btn btn-lg btn-success">
                            <i class="fa fa-2x fa-check"></i>
                        </button>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-3">
            <div class="panel panel-{{ is_null($request->closed_on) ? 'danger' : 'success' }}">
                <div class="panel-heading">
                    4. Finish and Close
                </div>
                <div class="panel-body">
                    @if (is_null($request->arrived_on))
                        <button class="btn btn-lg btn-danger">                
                            <i class="fa fa-2x fa-thumbs-o-up"></i>
                        </button>
                    @elseif(!is_null($request->arrived_on) && is_null($request->closed_on))
                        {{ Form::open(['route' => ['requests.update', $company->id, $request->id], 'method' => 'put']) }}
                            <input type="hidden" name="request_status" value="closed_on">
                            <button class="btn btn-lg btn-danger">        
                                <i class="fa fa-2x fa-thumbs-o-up"></i>
                            </button>
                        {{ Form::close() }}
                    @else
                        <button class="btn btn-lg btn-success">
                            <i class="fa fa-2x fa-check"></i>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Request Information</h3>
                </div>
                <div class="panel-body" style="">
                    <div class="form-group">
                        <label>Phone Number</label>
                        <br>    
                        {{ $request->customer_phone_number_type or '' }} {{ $request->customer_phone_number }}
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <br>
                        {{ $request->customer_email }}
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <br>
                        {{ $request->customer_address }}
                    </div>
                    @foreach ($request->customFields as $field)
                        <div class="form-group">
                            <label>{{ $field->field->name }}</label>
                            <br>
                            {{ $field->response }}
                        </div>
                    @endforeach
                </div>
            </div>
            @if ($request->survey_result)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Survey Response</h3>
                    </div>
                    <div class="panel-body" style="">
                        <p>
                            {{ $request->customer_name }} responded to your survey and gave you a grade of:
                        </p>

                        @if ($request->survey_result === 1)
                            <div class="alert alert-success">
                                <i class="fa fa-star fa-fw fa-3x pull-left"></i>
                                <p class="lead">Your work was fabulous</p>
                            </div>
                        @elseif ($request->survey_result === 2)
                            <div class="alert alert-success">
                                <i class="fa fa-smile-o fa-fw fa-3x pull-left"></i>
                                <p class="lead">Your work was good</p>
                            </div>
                        @elseif ($request->survey_result === 3)
                            <div class="alert alert-warning">
                                <i class="fa fa-meh-o fa-fw fa-3x pull-left"></i>
                                <p class="lead">Your work was just OK</p>
                            </div>
                        @elseif ($request->survey_result === 4)
                            <div class="alert alert-danger">
                                <i class="fa fa-frown-o fa-fw fa-3x pull-left"></i>
                                <p class="lead">I was really disappointed with your work</p>
                            </div>
                        @endif

                        @if ($request->survey_comment !== null)
                            {{ $request->customer_name }} left the following comment:
                            <div class="well well-sm">
                                {{ $request->survey_comment }}
                            </div>
                        @endif


                        <p>
                            In the survey, {{ $request->customer_name }} was given the following choices:
                        </p>

                        <ul>
                            <li>
                                <i class="fa fa-star fa-fw"></i> Your work was fabulous
                            </li>
                            <li>
                                <i class="fa fa-smile-o fa-fw"></i> Your work was good
                            </li>
                            <li>
                                <i class="fa fa-meh-o fa-fw"></i> Your work was just OK
                            </li>
                            <li>
                                <i class="fa fa-frown-o fa-fw"></i> I was really disappointed with your work
                            </li>
                        </ul>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-xs-12 col-sm-6">
            <div class="panel panel-default" style="background-color: transparent">
                <div class="panel-heading">
                    <h3 class="panel-title">Assignment Log</h3>
                </div>
                <div class="panel-body" style="">
                    <ul class="list-ic vertical">
                        <li>
                            <span>&nbsp;</span>
                            <a>{{ $request->created_at->timezone(\Auth::user()->timezone)->format('F jS, Y \a\t h:ia T') }}</a>
                            <p>
                                <i class="fa fa-star fa-fw"></i>
                                @if ($request->company_generated_user_id !== null)
                                    {{ $request->companyGeneratedUser->name }} (admin) created this request 
                                @else
                                    {{ $request->customer_name }} created request
                                @endif
                            </p>
                        </li>
                        @if ($request->contacted_on !== null)
                            <li>
                                <span>&nbsp;</span>
                                <a>{{ $request->contacted_on->timezone(\Auth::user()->timezone)->format('F jS, Y \a\t h:ia T') }}</a>
                                <p>
                                    <i class="fa fa-star fa-fw"></i>
                                    {{ $request->customer_name }} contacted
                                </p>
                            </li>
                        @endif
                    @if (!is_null($request->assigned_on) || count($request->employees) > 0)
                        <li>
                            <span>&nbsp;</span>
                            <a>{{ $request->assigned_on->timezone(\Auth::user()->timezone)->format('F jS, Y \a\t h:ia T') }}</a>
                            <p>
                                <i class="fa fa-edit fa-fw"></i>
                                @foreach ($request->employees as $user)
                                    {{ $user->name }}
                                @endforeach
                                {{ count($request->employees) > 1 ? 'were' : 'was' }}
                                assigned
                            </p>
                            <p>
                                <i class="fa fa-envelope-o fa-fw"></i>
                                {{ $request->customer_name }} is notified via email
                            </p>
                        </li>
                    @endif

                    @foreach($request->employees_acknowledged as $employee)
                        <li>
                            <span>&nbsp;</span>
                            <a>{{ \Carbon\Carbon::parse($employee->pivot->acknowledged_at)->timezone(\Auth::user()->timezone)->format('F jS Y \a\t h:ia T') }}</a>
                            <p>
                                <i class="fa fa-check-circle-o fa-fw"></i>
                                {{ $employee->name }} acknowledged the assignment
                            </p>
                        </li>
                    @endforeach

                    @foreach($request->employees_on_the_way as $key =>$employee)
                        <li>
                            <span>&nbsp;</span>
                            <a>{{ \Carbon\Carbon::parse($employee->pivot->on_the_way_at)->timezone(\Auth::user()->timezone)->format ('F jS Y \a\t h:ia T') }}</a>
                            <p>
                                <i class="fa fa-car fa-fw"></i>
                                {{ $employee->name }} was on the way to the customer
                            </p>
                            @if ($key === 0)
                                <p>
                                    <i class="fa fa-envelope-o fa-fw"></i>
                                    {{ $request->customer_name }} was notified via email
                                </p>
                            @endif
                        </li>
                    @endforeach

                    @if (!is_null($request->arrivalUser))
                        <li>
                            <span>&nbsp;</span>
                            <a>{{ $request->arrived_on->timezone(\Auth::user()->timezone)->format('F jS, Y \a\t h:ia T') }}</a>
                            <p>
                                <i class="fa fa-map-pin fa-fw"></i>
                                {{ $request->arrivalUser->name }} arrived at the customer
                            </p>
                        </li>
                    @elseif (!is_null($request->arrived_on))
                        <li>
                            <span>&nbsp;</span>
                            <a>{{ $request->arrived_on->timezone(\Auth::user()->timezone)->format('F jS, Y \a\t h:ia T') }}</a>
                            <p>
                                <i class="fa fa-map-pin fa-fw"></i>
                                Employee arrived at customer
                            </p>
                        </li>
                    @endif

                    @if (!is_null($request->departed_on))
                        <li>
                            <span>&nbsp;</span>
                            <a>{{ $request->departed_on->timezone(\Auth::user()->timezone)->format('F jS, Y \a\t h:ia T') }}</a>
                            <p>
                                <i class="fa fa-car fa-fw"></i>
                                {{ $request->arrivalUser->name }} departed
                            </p>
                        </li>
                    @endif

                    @if (!is_null($request->closed_on))
                        <li>
                            <span>&nbsp;</span>
                            <a>{{ $request->closed_on->timezone(\Auth::user()->timezone)->format('F jS, Y \a\t h:ia T') }}</a>
                            <p>
                                <i class="fa fa-check-circle fa-fw text-success"></i>
                                Request finished & closed
                            </p>
                            <p>
                                <i class="fa fa-envelope-o fa-fw"></i>
                                Survey sent to {{ $request->customer_name }}
                            </p>
                        </li>
                    @endif
                    @if (!is_null($request->surveyed_at))
                        <li>
                            <span>&nbsp;</span>
                            <a>{{ $request->surveyed_at->timezone(\Auth::user()->timezone)->format('F jS, Y \a\t h:ia T') }}</a>
                            <p>
                                <i class="fa fa-star"></i>
                                {{ $request->customer_name }} responded to survey
                            </p>
                        </li>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
