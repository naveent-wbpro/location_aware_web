<div class="well well-sm">
    <div class="media">
        <div class="media-left">
            @if ($company->photo)
                <a class="form-group">
                    <img class="media-object" src="{{ $company->photo->url }}" alt="" style="max-width: 120px; max-height: 120px">
                </a>
            @endif
            <div class="text-center">
                <input type="checkbox" class="comparison-check" value="{{ $company->id }}" id="compare-{{ $company->id }}">
                <label for="compare-{{ $company->id }}">Compare</label>
            </div>
        </div>
        <div class="media-body">
            <div class="row">
                <div class="col-xs-9">
                    <h4 class="media-heading">
                        <a href="/companies/{{ $company->id}}">
                            {{ $company->name }}
                        </a>
                        <br>
                        <small class="text-muted">
                            {{ $company->trade->name  or '' }}
                            - 
                            {{ clean_number_format(reverse_rating($company->getAverageRating())) }}/4 rating 
                        </small>
                    </h4>
                    <p>
                        {{ $company->description }} 
                    </p>

                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center">Years of experience</th>
                                <th class="text-center">IICRC Certified</th>
                                <th class="text-center">Contractor Warranty</th>
                                <th class="text-center">Credentialed</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center">
                                <td>{{ $company->years_in_business }}</td>
                                <td>
                                    @if ($company->iicrc_certified)
                                        <i class="fa fa-check-circle text-success"></i>
                                    @else
                                        <i class="fa fa-times-circle text-danger"></i>
                                    @endif
                                </td>
                                <td>
                                    @if ($company->contractor_warranty)
                                        <i class="fa fa-check-circle text-success"></i>
                                    @else
                                        <i class="fa fa-times-circle text-danger"></i>
                                    @endif                                   
                                </td>
                                <td>
                                    @if ($company->credentialed)
                                        <i class="fa fa-check-circle text-success"></i>
                                    @else
                                        <i class="fa fa-times-circle text-danger"></i>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="clearfix"></div>

                </div>
                <div class="col-xs-3">
                    @if ($company->street_address)
                        <i class="fa fa-car"></i> 
                    {{ $company->street_address}}
                    <br>
                    {{ $company->city }}, {{ $company->state }}
                    <br>
                    {{ $company->zip_code }}
                @endif
                @if ($company->phone_number)
                    <div class="clearfix"></div>
                <i class="fa fa-phone"></i>
                {{ $company->phone_number}}
            @endif
            @if ($company->website)
                <div class="clearfix"></div>
            <i class="fa fa-globe"></i>
            {{ $company->website }}
        @endif

    </div>
</div>
</div>
</div>
</div>
