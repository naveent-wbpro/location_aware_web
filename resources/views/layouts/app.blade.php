<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Location Aware website for helping business become geographically aware of their assets">
        <meta name="author" content="">
        <meta name="csrf-token" content="{{ csrf_token() }}">


        <title>Location Aware | Location based services for businesses</title>

        <!-- Bootstrap Core CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

        <!-- Custom CSS -->
        <link href="/css/main.css" rel="stylesheet">
        <link href="/css/dropzone.css" rel="stylesheet">
        <link rel="stylesheet" href="/css/blueimp-gallery.min.css">

        <!-- Custom Fonts -->
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
            <link rel="stylesheet" href="https://cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">

        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,300italic,300' rel='stylesheet' type='text/css'>
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
        <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>

        <!-- Javascript -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script> 
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/lodash/4.11.1/lodash.min.js"></script>
        <script src="/js/master.js"></script>
        <script src="/js/dropzone.js"></script>
        <script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
    	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

	    <!-- Date picker -->
	    <!--
	    ALREDY ADDED jquery 2.2 and bootstrap so commented
	    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	    <script src=" https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script> -->
	    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.min.js"></script>
	    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/js/bootstrap-datetimepicker.min.js"></script> 

	    <!--Calendar-->
	    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>-->
	    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
	   <!-- <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">-->
	    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>

	</head>

    <body id="page-top" class="index">
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-fixed-top">
    	<div class="container">
    	    <!-- Brand and toggle get grouped for better mobile display -->
    	    <div class="navbar-header page-scroll">
    		<a class="navbar-brand page-scroll" href="/"><i class="fa fa-map-marker"></i> Location Aware</a>
    	    </div>
    	    @if (\Auth::check())
    		    <ul class="nav navbar-nav navbar-right">
    			    <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                            {{ \Auth::user()->name }}
                                            <i class="fa fa-caret-down"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="/account_settings">Account Settings</a></li>
                                    @if (!empty(\Auth::user()->company) && \Auth::user()->id == \Auth::user()->company->owner->id)
                                        <li><a href="/billing">Billing</a></li>
                                    @endif
                                    <li role="separator" class="divider"></li>
                                    <li>
                                        <a href="{{ url('/logout') }}"
                                            onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>
                                        <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
    		    </ul>
    	    @endif
    	</div>
    	<!-- /.container-fluid -->
        </nav>
    	<div class="container content">
    		<div class='row'>
    			<div class="col-xs-12 col-md-8 col-md-offset-2">
                                        @if (\Session::has('success'))
                                            <div class="alert alert-success">
                                                {{ \Session::get('success') }}
                                            </div>
                                        @endif
                                        @if (\Session::has('error'))
                                            <div class="alert alert-danger">
                                                {{ \Session::get('error') }}
    				    </div>
                                        @endif
    				@if (isset($errors) && count($errors) > 0)
    				    <div class="alert alert-danger">
    					<ul>
    					    @foreach ($errors->all() as $error)
    						<li>{{ $error }}</li>
    					    @endforeach
    					</ul>
    				    </div>
    				@endif
    			</div>
    		</div>
    		@yield ('content')
    	</div>
    </body>
    <script src="/js/jquery.blueimp-gallery.min.js"></script>
</html>
