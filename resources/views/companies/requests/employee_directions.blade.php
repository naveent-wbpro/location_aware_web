@extends ('layouts/app')

@section ('content')
    <script>
        window.location = 'https://maps.apple.com/?daddr={{$request->latitude }},{{ $request->longitude }}&dirflgd'
    </script>
@endsection
