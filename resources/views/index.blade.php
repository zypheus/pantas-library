<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $brand['school_name'] }} | Home</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Favicon -->
    <link rel="icon" type="{{ $brand['favicon_mime'] ?? 'image/x-icon' }}" href="{{ $brand['favicon_url'] }}">
</head>

<body>
    <!-- ===========================
         HEADER
    ============================ -->

    <header>

        <div class="header-container">

            <a class="logo-section" href="{{ route('home') }}">

                <img src="{{ $brand['logo_compact_url'] ?: asset('images/moistlogo.jpg') }}" class="logo-icon" alt="{{ $brand['school_name'] }} Logo">

                <div class="logo-text">
                    <h2>{{ $brand['school_name'] }}</h2>
                </div>

            </a>

            <nav id="main-nav">

                <a href="#about">ABOUT</a>
                <a href="{{ route('landing') }}">OPAC</a>
                <a href="{{ $brand['zendy_url'] }}">ZENDY</a>
                <a href="#">CONTACT US</a>
                <a href="{{ url('/rooms/book') }}">ROOM RESERVATIONS</a>
                <a href="{{ route('feedback.create') }}" class="feedback-link">FEEDBACK</a>
                <a href="{{ route('login') }}" class="login-button">LOGIN</a>

            </nav>

            <button class="menu-toggle" id="menu-toggle" aria-label="Toggle navigation menu" aria-expanded="false" aria-controls="main-nav">
                <span></span>
                <span></span>
                <span></span>
            </button>

        </div>

    </header>



    <!-- ===========================
         HERO SECTION
    ============================ -->

    <section class="hero">

        <div class="hero-content">

            <!-- Video -->

            <div class="hero-video">

                <video autoplay muted loop playsinline controls preload="auto">

                    <source src="{{ asset('videos/howToRegister-zendy.mp4') }}" type="video/mp4">

                    Your browser does not support the video tag.

                </video>

            </div>


            <!-- OPAC SEARCH -->

            <div class="hero-search">

                <h2>Library OPAC</h2>

                <p>
                    Search books, journals, theses,
                    and other library resources.
                </p>

                <form class="search-box" method="GET" action="{{ route('landing') }}" role="search">

                    <input
                        type="search"
                        name="search"
                        placeholder="Search title, author, ISBN..."
                        autocomplete="off"
                        aria-label="Search library catalog"
                    >

                    <button type="submit">
                        Search
                    </button>

                </form>

            </div>

        </div>

    </section>



    <!-- ===========================
         WELCOME SECTION
    ============================ -->

    <section class="welcome-section" id="about">

        <div class="welcome-text">

            <span class="section-title">
                WELCOME TO
            </span>

            <h1 id="typing-title">

                Misamis Oriental Institute of Science
                and Technology (MOIST)

            </h1>

            <p class="welcome-description">

                Explore our website today to learn more about our academic
                offerings, research opportunities, student support services,
                and exciting campus life. Join us in shaping the future and
                making a difference in the world.

            </p>

            <a href="#" class="learn-btn">
                Learn More
            </a>

        </div>

    </section>



    <!-- ===========================
         LATEST COURSES
    ============================ -->

    <section class="info-section">

        <div class="info-courses">

            <h2 class="typing-courses">Latest Courses</h2>

            <p>

                "Explore cutting-edge topics in our latest courses,
                designed to empower students with practical knowledge
                and skills for today's rapidly changing world."

            </p>

        </div>


        <div class="info-news">

            <h3 class="typing-news">News & Events</h3>

            <p>

                Stay informed with the latest campus announcements,
                seminars, workshops, academic activities,
                and upcoming events at MOIST.

            </p>

        </div>

    </section>
    
    <!-- ===========================
         FOOTER
    ============================ -->

    <footer>

    <div class="footer-container">

        <!-- Column 1 -->

        <div class="footer-column">

            <h3>INFORMATION</h3>

            <ul>

                <li><a href="#">About Us</a></li>
                <li><a href="#">Features</a></li>
                <li><a href="#">Courses</a></li>
                <li><a href="#">Events</a></li>
                <li><a href="#">Terms of Use</a></li>

            </ul>

        </div>

        <!-- Column 2 -->

        <div class="footer-column">

            <h3>STUDENT HELP</h3>

            <ul>

                <li><a href="#">Get Started</a></li>
                <li><a href="#">My Questions</a></li>
                <li><a href="#">Download Files</a></li>
                <li><a href="#">Latest Courses</a></li>
                <li><a href="#">Academic News</a></li>

            </ul>

        </div>
 <div class="footer-column">

            <h3>CONTACT</h3>

            <p>Sta. Cruz, Cogon, Balingasag</p>

            <p>Misamis Oriental</p>

            <p>☎ PLDT: (088)-855-2885</p>

            <p>✉ moist@moist.edu.ph</p>

        </div>

    </div>

    <div class="footer-bottom">
        <p>&copy; {{ date('Y') }} MOIST. All rights reserved.</p>
    </div>

    </footer>

    <!-- JavaScript -->

    <script src="{{ asset('js/script.js') }}"></script>

</body>
</html>
