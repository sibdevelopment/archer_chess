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
                        <h2 class="breadcrumb-title">Chess For Expert</h2>
                        <nav aria-label="breadcrumb" class="page-breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Chess For Expert</li>
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
                    <span class="h2" style="font-size: 25px;">Chess Expert</span>
                </div>
            </div>
            <img src="/frontend/tcul_img/other.jpeg" alt="Img" class="img-fluid mb-5">
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
                            <p>At the expert level in chess, players demonstrate a deep understanding of both tactics and strategy, consistently making precise moves while foreseeing complex combinations many moves ahead. Experts have a solid grasp of opening theory, allowing them to develop their pieces effectively while controlling key areas of the board. They are skilled in middle-game planning, knowing how to attack and defend based on the unique characteristics of the position. Furthermore, their endgame knowledge is extensive, enabling them to convert small advantages into wins or defend difficult positions to secure a draw. Experts are also adept at psychological aspects of the game, understanding how to create pressure and force mistakes from their opponents. Their games reflect a balance between creativity and calculation, making them formidable and versatile competitors.
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
                                        <img src="/frontend/assets/img/skills/skills-02.svg" class="bg-info" alt="Stay motivated">
                                    </div>
                                    <div class="skill-five-content">
                                        <h3>Calculation in chess</h3>
                                        <p>Calculation in chess refers to the process of accurately visualizing and evaluating a series of moves and counter-moves during a game. It's a mental skill that allows players to anticipate their opponent's responses and plan several moves ahead. Strong calculation is essential in sharp, tactical positions where precision is required to avoid blunders and find the best continuation.
                                            Key Elements of Chess Calculation: Visualization,Candidate Moves,Forcing Moves
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
                                        <h3>Decision making</h3>
                                        <p>Decision-making in chess is the process of evaluating the position, considering various options, and choosing the best possible move. Strong decision-making involves balancing intuition, calculation, and strategic understanding. Here are the key elements involved in chess decision-making :Material, King Safety ,Activity of Piece ,Pawn Structure
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
                                        <h3>Right Exchange</h3>
                                        <p>In chess, the "right exchange" refers to making a trade of pieces that benefits your position, either strategically or tactically. Deciding when to exchange pieces is crucial, as it can shape the course of the game, affect the balance of material, and influence the dynamics of both the middle game and endgame. The key is to evaluate each exchange carefully to ensure it improves your position.
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
                                        <h3> Fortress
                                        </h3>
                                        <p>A fortress refers to a defensive setup where the weaker side creates an impenetrable position, making it impossible for the opponent to make progress or break through, despite having a material or positional advantage. A fortress is often used in endgames when the player with fewer resources seeks to secure a draw by setting up a position where the stronger player cannot improve their position or force a win.
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
                                        <h3>Improving piece position 
                                        </h3>
                                        <p>In chess involves repositioning your pieces to more effective squares where they exert greater influence and contribute more to your overall strategy. Here’s how you can improve your piece position: Centralization ,Avoiding Passive Positions ,Piece Coordination ,Creating Strong Outposts , Improving Pawn Structure
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 aos-init aos-animate" data-aos="fade-down">
                                <div class="skill-five-item">
                                    <div class="skill-five-icon">
                                        <img src="/frontend/assets/img/skills/skills-01.svg" class="bg-warning" alt="Stay motivated">
                                    </div>
                                    <div class="skill-five-content">
                                        <h3>Exploiting Opponent's Weaknesses</h3>
                                        <p>Exploiting your opponent's weaknesses in chess involves identifying and taking advantage of flaws or vulnerabilities in their position to gain a strategic or tactical edge. Here’s how you can effectively exploit these weaknesses.
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
                                        <h3>Identifying Weaknesses</h3>
                                        <p>Pawn Weaknesses: Look for isolated pawns (pawns with no friendly pawns on adjacent files), doubled pawns (two pawns on the same file), or backward pawns (pawns that are behind their neighbors and cannot be advanced without being captured).
                                            Weak Squares: Identify squares that are not protected by pawns and are easily accessible to your pieces. These can be targets for your attacks or outposts for your pieces.
                                            King Safety: Check if the opponent's king is exposed or poorly defended. This could include weaknesses like uncastled king, open files around the king, or weaknesses on the king’s side.
                                            Piece Activity: Look for opponent’s pieces that are poorly placed, inactive, or blocked. Pieces stuck behind pawns or in corners can often be targeted for tactical operations.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-12 ">
                        <div class="all-btn all-category d-flex align-items-center justify-content-center">
                            <a  href="/#trail_form"  class="btn btn-primary">Book your free trial class now!</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- -------------------------------------------------------------------------------------------------- :: -->
@endsection
