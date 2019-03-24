@extends ('layouts/welcome')

@section ('content')

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top welcome-nav">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand page-scroll" href="#page-top">
                    <img src="images/uuWn8iw.png" alt="" style="width: 50px" class="pull-left">
                    Location Aware
                </a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    <li>
                        <a href="/companies">
                            Company Search
                        </a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#services">Services</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#workflow">How It Works</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#pricing">Pricing</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#contact">Contact</a>
                    </li>
		    <li>
			<a href="/login">Login</a>
		    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>

    <!-- Header -->
    <header>
        <div class="container">
            <div class="intro-text">
                <div class="intro-lead-in hide-slow">
                    Providing EVERY Company in ANY Industry On-Demand Service Technology
                    <br>
                    <small style="font-size: 19px">
                        Improve Customer Service, Track Mobile Employees, or Network With Companies to Cross Sell
                    </small>
                </div>
                <div class="intro-heading move-up"><i>Location Aware</i></div>
                <div class="col-xs-12 text-center form-group">
                    <div class="fa-3x form-group">
                        <div id="video" style="display: none">
                            <div id="player" width="600px" height="300px">
                            </div>
                            <div class="clearfix"></div>
                            <span id="close-video" class="btn btn-default btn-lg">
                                <i class="fa fa-stop"></i> Stop
                            </span>
                        </div>
                        <span id="learn-more" class="btn btn-default btn-xl">
                            <i class="fa fa-film"></i>
                            Learn More
                        </span>
                        <a class="btn btn-default btn-xl" href="/register">
                            <i class="fa fa-thumbs-up"></i>
                            Register
                        </a>
                    </div>
                    <hr>
                    
                    @if ($ads->count() > 0)
                        <div class="text-center">
                            <p class="lead">
                                Learn More about LocationAware in your industry.
                            </p>
                            <select id="ad" class="form-control" name="" style="margin-left: auto; margin-right: auto;">
                                <option value="">Select Your Industry</option>
                                @foreach ($ads as $ad) 
                                    <option value="{{ $ad->url }}">{{ $ad->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <section id="mission-statement">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <p class="lead">
                    Location Aware allows customers to request your services quickly with employee GPS tracking and "Uber" styled maps.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Services</h2>
                    <h3 class="section-subheading text-muted">Use GPS technology to empower your business, connect with customers, and create a competitive advantage.</h3>
                </div>
            </div>
            <div class="row text-center">
                <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-map-marker fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="service-heading">GPS Technology</h4>
		    <p class="text-muted">
			Download our <i class="fa fa-android text-success"></i> and <i class="fa fa-apple"></i> applications and take advantage of geo tracking which enables outstanding service, saves on windshield time, and provides greater efficiency.
		    </p>
                </div>
                <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-group fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="service-heading">Employee Management</h4>
		    <p class="text-muted">
			Human resources are your most valuable assets which makes it important to know where your employees are at all times.
		    </p>
                </div>
                <div class="col-md-4">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-smile-o fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="service-heading">Happy Customers</h4>
		    <p class="text-muted">
                        We live in a world of instant gratification, being able to know where people are is invaluable. 
                        Delivering your services on demand makes happy customers and gives you 5 star ratings.
		    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Portfolio Grid Section -->
    <section id="workflow" class="bg-light-gray">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">How It Works</h2>
                    <h3 class="section-subheading text-muted">Location Aware will get you and your business up to speed with the latest technlogy quickly and efficiently.</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6 workflow-item">
                    <a href="#workflowModal1" class="workflow-link" data-toggle="modal">
                        <img src="/images/oNLuvCy.png" class="img-responsive" alt="">
                    </a>
                    <div class="workflow-caption">
                        <h4>Setup Your Company</h4>
                        <p class="text-muted">
                            Create your company and add any employees you would like to be GPS enabled.
                        </p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 workflow-item">
                    <a href="#workflowModal2" class="workflow-link" data-toggle="modal">
                        <img src="/images/y6ynEMG.png" class="img-responsive" alt="">
                    </a>
                    <div class="workflow-caption">
                        <h4>Enable GPS Tracking</h4>
                        <p class="text-muted">
                            Copy 1 line of code unique to your company and have a live GPS map of your employees.
                        </p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 workflow-item">
                    <a href="#workflowModal3" class="workflow-link" data-toggle="modal">
                        <img src="/images/Z3eepr3.png" class="img-responsive" alt="">
                    </a>
                    <div class="workflow-caption">
                        <h4>Connect with Customers...</h4>
                        <p class="text-muted">
                            Allow your customers to request your products and services via the GPS map on your site.
                        </p>
                    </div>
                </div>
                <div class="col-xs-12">
                    <p class="lead text-center fa-2x">
                        In 3 easy steps, your business will be more efficient, more advanced, and more user friendly. 
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section id="pricing">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">One Low Price</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="price-circle-container text-center">
                        <i class="fa fa-circle" style=""></i>
                        <span>
                            $36 / mo
                        </span>
                        <br>
                        <small>Based on Annual Subscription</small>
                        <img src="/images/JGVB6N9.png" alt="" class="img-responsive">
                    </div>
                </div>
                <div class="col-sm-6 pricing-features">
                    <ul class="lead">
                        <li>Price is per company and include:</li>
                        <ul>
                            <li>5 Mobile User</li>
                            <li>Unlimited Offices</li>
                            <li>Free apps on both iOS and Android Platforms</li>
                            <li>24/7 Tracking</li>
                            <li>Free code snippets to embed maps on your websites</li>
                            <li>Create and connect with networks of different companies</li>
                            <li>Receive job requests instantly from visitors clicking on your map</li>
                        </ul>
                        <li>Additional adds</li>
                        <ul>
                            <li>Add as many additional users as needed at $5 a month each</li>
                        </ul>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Contact Us</h2>
                    <h3 class="section-subheading text-muted">Contact us for more information and account setup.</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
		    {!! Form::open(['url' => '/', 'id' => 'contactForm']) !!}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="name" placeholder="Your Name *" id="name" required data-validation-required-message="Please enter your name.">
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email" placeholder="Your Email *" id="email" required data-validation-required-message="Please enter your email address.">
                                    <p class="help-block text-danger"></p>
                                </div>
                                <div class="form-group">
                                    <input type="tel" class="form-control" name="phone" placeholder="Your Phone *" id="phone" required data-validation-required-message="Please enter your phone number.">
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <textarea class="form-control" name="message" placeholder="Your Message *" id="message" required data-validation-required-message="Please enter a message."></textarea>
                                    <p class="help-block text-danger"></p>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-12 text-center">
                                <div id="success"></div>
                                <button type="submit" class="btn btn-xl">Send Message</button>
                            </div>
                        </div>
		    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </section>

    <footer class="welcome-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <span class="copyright">Copyright &copy; Nsurance Solutions {!! date('Y') !!}</span>
                </div>
                <div class="col-md-4">
                    <ul class="list-inline social-buttons">
                        <li><a href="#"><i class="fa fa-twitter"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-facebook"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-linkedin"></i></a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <ul class="list-inline quicklinks">
                        <li><a href="/privacy_policy">Privacy Policy</a>
                        </li>
                        <li><a href="/terms_of_use">Terms of Use</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="/js/welcome.js"></script>
@endsection
