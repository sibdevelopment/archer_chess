@extends('layouts.frontend')
@section('title')
    Page Name | Website Name
@endsection
@section('content')
    <style>
        .course-info {
            padding-top: 15px;
            border: none;
        }
    </style>


    <div class="breadcrumb-bar page-banner breadcrumb-bar-info mb-0">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-12">
                    <div class="breadcrumb-list">
                        <h2 class="breadcrumb-title">Chess For Beginner</h2>
                        <nav aria-label="breadcrumb" class="page-breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Chess For Beginner</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!----------------------------------------------------------------------------------------------------- :: -->


    <section class="course-content pb-0  new-course" style="background: none">
        <div class="container">
            <div class="section-header aos pb-4" data-aos="fade-up">
                <div class="section-sub-head">
                    <span class="h2" style="font-size: 25px;">Chess Beginner</span>
                </div>
            </div>
            <img src="/frontend/tcul_img/biggner.jpeg" alt="Img" class="img-fluid mb-5">
        </div>
    </section>

    <!-- -------------------------------------------------------------------------------------------------- :: -->


    <section class="course-content" style="background:none; padding: 0px !important;">
        <div class="container">
            <div class="section-header aos" data-aos="fade-up">
                <div class="sub-head tcul pb-0">
                    <strong class="h2 fw-bold" style="color: #002058"> About the course</strong>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="blog">
                        <div class="header-two-text">
                            <p>Are you looking to learn the basics of chess? Our beginner's course is designed to introduce
                                you to the fundamentals of the game. Whether you're completely new to chess or looking to
                                refresh your knowledge, this course will cover everything you need to know to start playing
                                confidently. From understanding how the pieces move to learning basic strategies and
                                tactics, we'll guide you through each step of your chess journey. By the end of the course,
                                you'll have a solid foundation to build upon as you continue to explore the fascinating
                                world of chess.
                            </p>
                        </div>
                        {{-- <div class="course-info d-flex align-items-center">
                        <div class="cou-info">
                            <img src="/frontend/assets/img/icon/play.svg" alt="Img">
                            <p>5 Courses</p>
                        </div>
                        <div class="cou-info">
                            <img src="/frontend/assets/img/icon/icon-01.svg" alt="Img">
                            <p>12+ Lesson</p>
                        </div>
                        <div class="cou-info">
                            <img src="/frontend/assets/img/icon/icon-02.svg" alt="Img">
                            <p>9hr 30min</p>
                        </div>
                        <div class="cou-info">
                            <img src="/frontend/assets/img/icon/people.svg" alt="Img">
                            <p>270,866 students enrolled</p>
                        </div>
                    </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="goals-section-five">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-x-4 col-lg-3 col-md-12 col-sm-12" data-aos="fade-down">
                    <div class="header-five-title mb-0">
                        <h2 class="mb-0">Acheive your Goals with Archer</h2>
                    </div>
                </div>
                <div class="col-x-8 col-lg-9 col-md-12 col-sm-12">
                    <div class="row text-center align-items-center">

                        <div class="col-lg-3 col-sm-3" data-aos="fade-down">
                            <div class="goals-count-five goals-five-one">
                                <div class="goals-content-five course-count ms-0">
                                    <h4><span class="">500</span>+</h4>
                                    <p>Puzzle assigned</p>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-3 col-sm-3" data-aos="fade-down">
                            <div class="goals-count-five goals-five-two">
                                <div class="goals-content-five course-count ms-0">
                                    <h4><span class="">12</span>+</h4>
                            <p>Extra class</p>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-3 col-sm-3" data-aos="fade-down">
                            <div class="goals-count-five goals-five-three">
                                <div class="goals-content-five course-count ms-0">
                                    <h4><span class="">5</span>+</h4>
                            <p>Age group</p>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-3 col-sm-3" data-aos="fade-down">
                            <div class="goals-count-five goals-five-four goals-five-last">
                                <div class="goals-content-five course-count ms-0">
                                    <h4><span class="">1500</span>+</h4>
                                    <p class="mb-0">Students</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- -------------------------------------------------------------------------------------------------- :: -->
    <div class="section share-knowledge">
        <section class="master-section-five" style="background:none">
            <div class="container">
                <div class="section-header aos" data-aos="fade-up">
                    <div class="sub-head tcul pb-0">
                        <strong class="h2 fw-bold" style="color: #002058">Curriculum</strong>
                    </div>
                </div>
                <div class="master-five-vector">
                    <img class="ellipse-right" src="/frontend/assets/img/bg/master-vector-left.svg" alt="Img">
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-12 col-sm-12">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 aos-init aos-animate" data-aos="fade-down">
                                <div class="skill-five-item">
                                    <div class="skill-five-icon">
                                        <img src="/frontend/assets/img/skills/skills-01.svg" class="bg-warning" alt="Stay motivated">
                                    </div>
                                    <div class="skill-five-content">
                                        <h3>Introduction</h3>
                                        <p>Let's get started with an introduction to the board- naming the squares and how to set the board. Learn about the pieces of chess board and their movement, followed by a short review.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 aos-init aos-animate" data-aos="fade-down">
                                <div class="skill-five-item">
                                    <div class="skill-five-icon">
                                        <img src="/frontend/assets/img/skills/skills-02.svg" class="bg-info" alt="Stay motivated">
                                    </div>
                                    <div class="skill-five-content">
                                        <h3>Gaining material</h3>
                                        <p>In this module we cover pawn promotion, draw and how to capture an unprotected piece. Also learn how to gain by exchange.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 aos-init aos-animate" data-aos="fade-down">
                                <div class="skill-five-item">
                                    <div class="skill-five-icon">
                                        <img src="/frontend/assets/img/skills/skills-03.svg" class="bg-danger" alt="Stay motivated">
                                    </div>
                                    <div class="skill-five-content">
                                        <h3>Attack</h3>
                                        <p>You know that developing is very important in the opening, but how does one win with a lead in development? The key is to "attack". Learn how to create an attack and polish your knowledge with a test review.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 aos-init aos-animate" data-aos="fade-down">
                                <div class="skill-five-item">
                                    <div class="skill-five-icon">
                                        <img src="/frontend/tcul_img/defence.png" class="bg-light-green"
                                            alt="Stay motivated">
                                    </div>
                                    <div class="skill-five-content">
                                        <h3>Defence</h3>
                                        <p>We have learnt how to attack, but how do we avoid or protect yourself against an undesirable outcome? This module is an introduction to "defense".
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 aos-init aos-animate" data-aos="fade-down">
                                <div class="skill-five-item">
                                    <div class="skill-five-icon">
                                        <img src="/frontend/tcul_img/special-move.png" class="bg-info" alt="Stay motivated">
                                    </div>
                                    <div class="skill-five-content">
                                        <h3>Special Move</h3>
                                        <p>Get introduced to the first special rule of chess- castling.Halfway through the beginner module- it's now time to learn another special move, called "En passant".
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 aos-init aos-animate" data-aos="fade-down">
                                <div class="skill-five-item">
                                    <div class="skill-five-icon">
                                        <img src="/frontend/tcul_img/mate.png" class="bg-danger" alt="Stay motivated">
                                    </div>
                                    <div class="skill-five-content">
                                        <h3>Mate</h3>
                                        <p>Did you know that one side can force your king to the edge of the board? Learn about the double rook checkmate for your winning move! Also learn about the famous "mate" or checkmate move.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-12 ">
                        <div class="all-btn all-category d-flex align-items-center justify-content-center">
                            <a href="/#trail_form" class="btn btn-primary">Book your free trial class now!</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- -------------------------------------------------------------------------------------------------- :: -->
@endsection
