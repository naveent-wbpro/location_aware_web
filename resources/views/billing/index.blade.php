@extends ('layouts.app')

@section ('content')
    <div class="col-xs-12">
        <h3>
            Billing
            <small>
                <a href="/">
                    Go Back
                </a>
            </small>
        </h3>
    </div>
    <div class="col-xs-12">
        <p>
            You can securely add your payment method below. By adding a payment method you will be able to claim jobs as they appear.
        </p>
    </div>
    <div class="col-xs-12 col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Current Subscription
                </h3>
            </div>
            <div class="panel-body">
                <p class="lead">Subscriptions are currently disabled at this time.</p>
            </div>
        </div>    
    </div>
    <div class="col-xs-12 col-sm-6">
         <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Payment Method
                </h3>
            </div>
            <div class="panel-body">
                @if ($user->card_brand !== null)
                    <div class="form-group">
                         <label>
                            Credit Card Brand
                        </label>
                        <p>
                            {{ $user->card_brand }}
                        </p>
                    </div> 
                    <div class="form-group">
                         <label>
                            Credit Card Last 4 Digits
                        </label>
                        <p>
                            **** ****** {{ $user->card_last_four }}
                        </p>
                    </div> 
                @else
                    No payment information
                @endif
            </div>
            <div class="panel-footer">
                @if ($user->card_brand != null)
                    {{ Form::open(['url' => 'billing/destroy', 'onSubmit' => 'return confirm("Are you sure you want to delete your credit card?")', 'class' => 'pull-left']) }}
                        <a href="javascript:$('form').submit()" class="text-danger">
                            Remove Payment method
                        </a>
                    {{ Form::close() }}
                @endif
                <a href="/billing/edit" class="pull-right">
                    Update Payment Method
                </a>
                <div class="clearfix"></div>
            </div>
        </div>       
    </div>
@endsection
