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
                    <h2 class="breadcrumb-title">Chess For Intermediate</h2>
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Chess For Intermediate</li>
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
                <span class="h2" style="font-size: 25px;">Chess Intermediate</span>
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
                        <p>The intermediate level marks a significant milestone in one's journey towards mastery. At this stage, players have gained a solid understanding of the game's fundamentals and are ready to delve deeper into strategic concepts and tactical intricacies. Intermediate players often focus on developing their positional understanding, honing their ability to evaluate pawn structures, piece activity, and control of key squares.
                        </p>
                    </div>
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
                                    <img src="/frontend/assets/img/skills/skills-01.svg" class="bg-warning"
                                        alt="Stay motivated">
                                </div>
                                <div class="skill-five-content">
                                    <h3>Basic Tactics</h3>
                                    <p>Basic tactics in chess are essential tools that allow players to gain an advantage through short-term, calculated sequences of moves. These tactics often involve creating threats, capturing opponent's pieces, or achieving a superior position. Some of the most common basic tactics include: The Pin, Double attack, discovered attack, skewer etc.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 aos-init aos-animate" data-aos="fade-down">
                            <div class="skill-five-item">
                                <div class="skill-five-icon">
                                    <img src="/frontend/assets/img/skills/skills-02.svg" class="bg-info"
                                        alt="Stay motivated">
                                </div>
                                <div class="skill-five-content">
                                    <h3>Tactical Motif</h3>
                                    <p>A tactical motif in chess refers to a recurring pattern or idea that can be used to achieve a tactical goal, such as winning material or delivering checkmate. These motifs are essential tools that help players recognize and execute tactical opportunities in a game. Understanding tactical motifs allows players to quickly spot chances to gain an advantage. Some common tactical motifs include: Deflection, decoy, Overloading, Clearance, interference
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 aos-init aos-animate" data-aos="fade-down">
                            <div class="skill-five-item">
                                <div class="skill-five-icon">
                                    <img src="/frontend/assets/img/skills/skills-03.svg" class="bg-danger"
                                        alt="Stay motivated">
                                </div>
                                <div class="skill-five-content">
                                    <h3>Basic Pawn Ending</h3>
                                    <p>Basic king and pawn endings in chess are critical to master, as they frequently determine the outcome of a game. In these endings, only kings and pawns remain, and the goal is typically to promote a pawn to a queen or another piece, which often leads to a winning advantage. Here are some fundamental concepts and principles for basic king and pawn endings: Opposition, Outflanking, Rule of the square 
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
                                    <h3>Zugzwang</h3>
                                    <p>Zugzwang is a German term used in chess to describe a situation where a player is forced to make a move, but every possible move worsens their position. In other words, the player would prefer to pass their turn (if that were allowed), as any move they make leads to a disadvantage, often resulting in a loss of material, a weakened position, or even checkmate. Zugzwang is a common concept in endgames, where limited pieces and options mean that every move becomes critical. 
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
                                    <h3>Basic Mating Pattern </h3>
                                    <p>A mating pattern in chess refers to a specific arrangement of pieces that leads to checkmate, where the opposing king is trapped and unable to escape capture. Recognizing these patterns allows players to deliver checkmate efficiently and capitalize on opportunities to end the game. Here are some common mating patterns: Back Rank Mate, Smothered Mate, Anastasia's Mate, Arabian Mate.
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
                                    <h3>Counter Attack</h3>
                                    <p>In chess, a counter-attack is a strategy where a player responds to their opponent's attack by launching an attack of their own, often targeting a different part of the board or threatening a critical piece. Instead of simply defending against the opponent's aggression, a counter-attack puts pressure on the opponent and forces them to react, potentially turning the tables in the game.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-sm-12 ">
                    <div class="all-btn all-category d-flex align-items-center justify-content-center">
                        <a href="/#trail_form"  class="btn btn-primary">Book your free
                            trial class now!</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- -------------------------------------------------------------------------------------------------- :: -->
@endsection