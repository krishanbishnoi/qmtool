<!--================Header Menu Area =================-->
        <header class="header_area">
            <div class="main_menu">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <div class="container box_1620">
                        <!-- Brand and toggle get grouped for better mobile display -->
                        <a class="navbar-brand logo_h" href="{{url('/')}}"><img src="qoute-now-logo_white.svg" alt="QuoteNow" width="200"></a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
                            <ul class="nav navbar-nav menu_nav justify-content-center">
                                <li class="nav-item active"><a class="nav-link" href="{{url('/')}}">Home</a></li> 
                                <li class="nav-item"><a class="nav-link" href="#">About</a></li> 
                                <li class="nav-item"><a class="nav-link" href="#">Services</a>
                                    <li class="nav-item"><a class="nav-link" href="{{url('plan_pricing')}}">Pricing</a>
                                <li class="nav-item submenu dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="false" aria-expanded="false">Quote Calculator</a>
                                    <!-- <ul class="dropdown-menu">
                                        <li class="nav-item"><a class="nav-link" href="blog.html">Blog</a></li>
                                        <li class="nav-item"><a class="nav-link" href="single-blog.html">Blog Details</a></li>
                                    </ul> -->
                                </li> 
                                <li class="nav-item"><a class="nav-link" href="/contact">Contact</a></li>
                            </ul>
                            <ul class="nav navbar-nav navbar-right">
                                <li class="nav-item">
                                    @if(Auth::User())
                                    <a href="/login" class="tickets_btn">Dashboard</a>
                                    @else
                                    <a href="/home" class="tickets_btn">Sign In</a>
                                    @endif
                                </li>
                            </ul>
                        </div> 
                    </div>
                </nav>
            </div>
        </header>
        <!--================Header Menu Area =================-->