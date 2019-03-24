@extends ('companies.show')

@section ('sub-content')
        <div class="col-xs-12 col-md-4">
            <div class="well well-sm">
                <p class="lead text-center">
                    Requests Completed                        
                </p>
                <p class="lead text-center">
                    {{ $closed_requests }} 
                </p>
            </div>
        </div>

        <div class="col-xs-12 col-md-4">
            <div class="well well-sm">
                <p class="lead text-center">
                    Number of Reviews
                </p>
                <p class="lead text-center">
                    {{ $ratings->count() }}
                </p>
            </div>
        </div>
        <div class="col-xs-12 col-md-4">
            <div class="well well-sm">
                <p class="lead text-center">
                    Average Review Score
                </p>
                <p class="lead text-center">
                    @if (ceil($average_rating) == 1)
                        <i class="fa fa-star fa-fw text-success"></i>
                    @elseif (ceil($average_rating) == 2)
                        <i class="fa fa-smile-o fa-fw text-success"></i>
                    @elseif (ceil($average_rating) == 3)
                        <i class="fa fa-meh-o fa-fw text-warning"></i>
                    @elseif (ceil($average_rating) == 4)
                        <i class="fa fa-frown-o fa-fw text-danger"></i>
                    @endif
                    {{ clean_number_format(reverse_rating($average_rating)) }} / 4
                </p>
            </div>
        </div>

    @foreach ($ratings as $review)
        @if ($review->survey_comment !== null)
            <div class="col-xs-12">
                <blockquote>
                    <p>
                        @if (ceil($average_rating) == 1)
                            <i class="fa fa-star fa-fw text-success"></i>
                        @elseif (ceil($average_rating) == 2)
                            <i class="fa fa-smile-o fa-fw text-success"></i>
                        @elseif (ceil($average_rating) == 3)
                            <i class="fa fa-meh-o fa-fw text-warning"></i>
                        @elseif (ceil($average_rating) == 4)
                            <i class="fa fa-frown-o fa-fw text-danger"></i>
                        @endif
                        {{ $review->survey_comment }}
                    </p>
                    <footer>
                        {{ $review->customer_name }} gave a {{ reverse_rating($review->survey_result) }}/4 rating  on {{ $review->surveyed_at->toDayDateTimeString() }}
                    </footer>
            </div>
        @endif
    @endforeach
@endsection
