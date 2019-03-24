<div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-6 col-md-offset-3">
    <div class="form-group">
        <input class="form-control" type="text" value="{{ $price->item }}" name="item">
    </div>
    <div class="form-group">
        <div class="input-group">
            <span class="input-group-addon">$</span>
            <input type="text" class="form-control" name="amount" aria-label="Amount (to the nearest dollar)" value="{{ $price->amount }}">
        </div>
    </div>
    <div class="form-group text-right">
        <button class="btn btn-default"><i class="fa fa-save"></i> Save</button>
    </div>
</div>
