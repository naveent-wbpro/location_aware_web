{{ Form::open(['url' => 'plans', 'method' => 'put']) }}
    <div class="well well-sm">
        <div class="row">
            <div class="col-xs-4">
                Base plan with 5 users
            </div>
            <div class="col-xs-4 text-center">
                $45
            </div>
        </div>
    </div>
    <div class="well well-sm">
        <div class="row">
            <div class="col-xs-4">
                Number of Additional Users
            </div>
            <div class="col-xs-4 text-center">
                $<span id="additional_users_price">0</span>
            </div>
            <div class="col-xs-4 text-right">
                {{ Form::select('additional_users', range(0,100), $user->numberOfEmployeesAllowed , ['id' => 'additional_users']) }}
            </div>
        </div>
    </div>
    <div class="well well-sm">
        <div class="row">
            <div class="col-xs-4">
                Add Coupon Code
            </div>
            <div class="col-xs-4 text-right">
                {{ Form::text('coupon', '') }}
            </div>
        </div>
    </div>

    <hr>
    
    <div class="well well-sm">
        <div class="row">
            <div class="col-xs-4">
                <b>Total</b>
            </div>
            <div class="col-xs-4 text-center">
                $<span id="total">0</span> / month
            </div>
        </div>
    </div>

    <div class="col-xs-6">
        <a class="btn btn-default" href="/billing">
            Back
        </a>
    </div>
    <div class="col-xs-6 text-right">
        <button class="btn btn-success">
            Update
        </button>
    </div>
{{ Form::close() }}

<script>
    function calculateTotal() {
        number_of_additional_users = $("#additional_users").val();
        additional_users_price = number_of_additional_users * 5;
        total = 45 + (number_of_additional_users * 5);
        $("#additional_users_price").html(additional_users_price);
        $("#total").html(total);
    }

    $("#additional_users").on('change', function(){
        calculateTotal();        
    })

    calculateTotal();
</script>
