@extends ('layouts.app')

@section ('content')
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script>
        Stripe.setPublishableKey('<?= config('services.stripe.key') ?>');
    </script>

   <div class="col-xs-12 col-sm-offset-2">
       <h3>Update Payment Method</h3>
   </div>

    <div class="col-xs-12 col-sm-4 col-sm-offset-2 bg bg-success well-sm" style="height: 400px">
        <p class="lead">
            Payment Information
        </p>
        <p>By submitting your payment information, you will be subscribing to the LocationAware services for business</p>
        <p>You can edit how many employees you need in the billing screen</p>
        <p>This form is submitted securely using Stripe <i class="fa fa-stripe"></i></p>
    </div>
    <div class="col-xs-12 col-sm-4 well well-sm" style="height: 400px">
        {{ Form::open(['url' => '/billing', 'method' => 'put', 'id' => 'payment-form']) }}
            <span class="payment-errors hidden text-danger"></span>

            <div class="clearfix"></div>

            <div class="form-group">
                <label>
                    <span>Card Number</span>
                    <input type="text" class="form-control" data-stripe="number">
                </label>
            </div>

            <div class="form-group form-inline">
                <label>
                    <span>Expiration (MM/YY)</span>
                </label>
                <div class="clearfix"></div>
                <input type="text" class="form-control" size="10" data-stripe="exp_month">
                <span> / </span>
                <input type="text" class="form-control" size="10" data-stripe="exp_year">
            </div>

            <div class="form-group">
                <label>
                    <span>CVC</span>
                    <input type="text" class="form-control" size="4" data-stripe="cvc">
                </label>
            </div>

            <div class="form-group">
                <label>
                    <span>Billing Zip</span>
                    <input type="text" class="form-control" size="6" data-stripe="address_zip">
                </label>
            </div>

            <div class="col-xs-6">
                <a href="/billing" class="btn btn-default">
                    Cancel
                </a>
            </div>
            <div class="col-xs-6">
                <button class="btn btn-primary">
                    Save Card
                </button>
            </div>
        {{ Form::close() }}
    </div>

    <script>
        var $form = $('#payment-form');
        $form.submit(function(event) {
            // Disable the submit button to prevent repeated clicks:
            $form.find('.submit').prop('disabled', true);

            // Request a token from Stripe:
            Stripe.card.createToken($form, stripeResponseHandler);

            // Prevent the form from being submitted:
            return false;
        });

        function stripeResponseHandler(status, response) {
            // Grab the form:
            var $form = $('#payment-form');

            if (response.error) { // Problem!

                // Show the errors on the form
                $form.find('.payment-errors').removeClass('hidden').text(response.error.message);
                $form.find('button').prop('disabled', false); // Re-enable submission

                window.setTimeout(function() {
                    $(".payment-errors").addClass('hidden').html('');
                }, 5000)

            } else { // Token was created!

                // Get the token ID:
                var token = response.id;

                // Insert the token into the form so it gets submitted to the server:
                $form.append($('<input type="hidden" name="stripeToken" />').val(token));

                // Submit the form:
                $form.get(0).submit();

            }
        }
    </script>
@endsection
