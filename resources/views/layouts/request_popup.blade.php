<!DOCTYPE html>
<html lang="en">

	<head>

	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <meta name="description" content="Location Aware website for helping business become geographically aware of their assets">
	    <meta name="author" content="">

	    <title>Location Aware | Location based services for businesses</title>

	    <!-- Bootstrap Core CSS -->
	    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

	    <!-- Custom CSS -->
	    <link href="/css/main.css" rel="stylesheet">

	    <!-- Custom Fonts -->
	    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">

	    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,300italic,300' rel='stylesheet' type='text/css'>
	    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
	    <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>

	    <!-- Javascript -->
	    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script> 
	    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	    <script src="https://cdn.jsdelivr.net/lodash/4.11.1/lodash.min.js"></script>
	    <script src="/js/master.js"></script>


	    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	    <!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	    <![endif]-->
            <script src='https://www.google.com/recaptcha/api.js'></script>

	</head>

	<body id="page-top" class="index">
	    <!-- Navigation -->
	    <nav class="navbar navbar-default navbar-fixed-top">
		<div class="container">
		    <!-- Brand and toggle get grouped for better mobile display -->
		    <div class="navbar-header page-scroll">
			<a class="navbar-brand page-scroll" href="/"><i class="fa fa-map-marker"></i> Location Aware</a>
		    </div>
		</div>
		<!-- /.container-fluid -->
	    </nav>
		<div class="container content">
			<div class='row'>
				<div class="col-xs-12 col-md-8 col-md-offset-2">
					@if (count($errors) > 0)
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
</html>
