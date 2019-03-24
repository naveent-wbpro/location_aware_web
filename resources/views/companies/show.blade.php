@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <div class="list-group">
                    <a href="/companies/{{ $company->id }}" class="list-group-item">
                        <i class="fa fa-fw fa-star"></i>
                        Reviews
                    </a>
                    <a href="/companies/{{ $company->id }}/photos" class="list-group-item">
                        <i class="fa fa-fw fa-camera"></i>
                        Photos
                    </a>
                    @if ($can_edit)
                        <a href="/companies/{{ $company->id }}/edit" class="list-group-item">
                            <i class="fa fa-fw fa-edit"></i>
                            Edit
                        </a>
                    @endif
                </div>
            </div>
            <div class="col-md-10">
                <div class="row">
                    <div class="col-xs-12">
                        @include('companies._snippet')
                    </div>
                    @if ($code_snippet !== null && isset($show_map))
                        <div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2 form-group" style="height: 400px">
                            @include('companies.code_snippet._code_snippet_code')
                        </div>
                    @endif
                </div>
                <div class="row">
                    @yield('sub-content')
                </div>
            </div>
        </div>
    </div>
@endsection
