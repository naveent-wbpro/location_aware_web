@extends ('companies.show')

@section ('sub-content')
    <p class="lead">You can drag and drop images to to the box below or you can click the box to open the image picker to upload your image. </p>
    <form action="/file-upload" class="dropzone" id="my-awesome-dropzone"></form>
    <script>
        Dropzone.options.myAwesomeDropzone = {
            init: function() {
                this.on('queuecomplete', function() {
                    location.reload()        
                })
            },
            paramName: "photo", // The name that will be used to transfer the file
            url: '/companies/'+<?= $company->id ?>+'/photos',
            maxFilesize: 5,
            acceptedFiles: 'image/*',
            headers: {'X-CSRF-TOKEN': '<?= csrf_token() ?>'}
        };
    </script>

    <hr>

    <a href="/companies/{{ $company->id }}" class="btn btn-default">
        Cancel
    </a>

    <div class="clearfix"></div>

    <hr>

    @foreach ($photos as $photo)
        <div class="col-xs-6">
            <img src="{{ $photo->url }}" alt="" class="img-responsive">
        </div>
        <div class="col-xs-6">
            {{ Form::open(['url' => '/companies/'.$company->id.'/photos/'.$photo->id, 'method' => 'delete', 'onsubmit' => 'return confirm("Do you want to delete this photo?");']) }} 
                <button class="btn btn-danger">
                    Delete Photo
                </button>
            {{ Form::close() }}
        </div>

        <div class="clearfix"></div>
        <hr>
    @endforeach
@endsection
