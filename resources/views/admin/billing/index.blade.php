@extends ('admin.layout')

@section ('sub-content')
<div class="col-xs-12 col-sm-10 col-sm-offset-1">
    <a href="/admin/billing/create" class="btn btn-default">
        Create Price
    </a>
    <table class="table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($prices as $price)
                <tr>
                    <td>{{ $price->item }}</td>
                    <td>{{ $price->amount }}</td>
                    <td>
                        <a href="/admin/billing/{{ $price->id }}/edit">
                            Edit
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
