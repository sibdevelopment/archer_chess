@extends('layouts.revamp')
@section('title', 'Chess for Intermediate')
@section('content')

    <section class="breadcrumb pt-60 pb-20 bg-main-two-200 position-relative">
        <img src="/frontend1/assets/images/shape/banner-shape2.png" alt="shape"
            class="position-absolute bottom-0 tw-start-0 w-100">
        <img src="/frontend1/tcul-img/img/bag.svg" alt="shape"
            class="position-absolute top-0 tw-end-0 tw-mt-15 d-lg-block d-none animation-upDown">
        <img src="/frontend1/tcul-img/img/expert-1.svg" alt="shape"
            class="position-absolute bottom-0 tw-start-0 tw-h-100-px tw-ms-250-px d-lg-block d-none"
            style="bottom: 10px; left: -80px; ">

        <div class="tw-mb-140-px w-100">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div>
                            <h3 class="text-center tw-mb-6 text-neutral-950">Chess for Intermediate</h3>
                            <ul class="d-flex align-items-center justify-content-center tw-gap-4">
                                <li><a href="/design/home" class="text-main-600 hover-text-main-700 tw-text-405"> <i
                                            class="las la-home"></i> Home</a> </li>
                                <li><i class="text-main-600 hover-text-main-700 tw-text-405">/</i></li>
                                <li> <span class="text-main-600 hover-text-main-700 tw-text-405"> Chess for Intermediate
                                    </span> </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-80">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between tw-gap-4 flex-wrap tw-mb-2">
                <div>
                    <h2 class="fw-bold text-neutral-950 h4" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                        About the Course</h2>
                </div>
            </div>
            <div class="row gy-4">
                <div class="col-xl-12 col-lg-12 col-md-12" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                    <div class="w-100 tw-mb-0">
                        <div class="d-flex align-items-center tw-gap-3 tw-mb-3">
                            <span class="fw-medium tw-text-5 text-paragraph-500">The intermediate level marks a significant
                                milestone in one's journey towards mastery. At this stage, players have gained a solid
                                understanding of the game's fundamentals and are ready to delve deeper into strategic
                                concepts and tactical intricacies. Intermediate players often focus on developing their
                                positional understanding, honing their ability to evaluate pawn structures, piece activity,
                                and control of key squares.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Counter Section --}}
    <div style="background-image: url(/frontend1/assets/images/bg/choose-us-three-bottom-bg-img.png);" class="bg-img">
        <div class="py-110">
            <div class="container">
                <div class="row gy-4">
                    <div class="col-xl-3 col-lg-4 col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                        <div class="d-flex align-items-start tw-gap-5 flex-column animation-item">
                            <span>
                                <img src="/frontend1/assets/images/icon/our-galler-bottom-icon1.png" alt="icon"
                                    class="animate__swing">
                            </span>
                            <div>
                                <span class="fw-semibold tw-text-405 text-neutral-600 d-block tw-mb-2">
                                    Puzzle assigned
                                </span>
                                <h3 class="fw-normal text-main-600 counter">500+</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                        <div class="d-flex align-items-start tw-gap-5 flex-column animation-item">
                            <span>
                                <img src="/frontend1/tcul-img/icons/course4.svg" alt="icon"
                                    class="animate__swing">
                            </span>
                            <div>
                                <span class="fw-semibold tw-text-405 text-neutral-600 d-block tw-mb-2">
                                    Extra Class
                                </span>
                                <h3 class="fw-normal text-main-600 counter">12+</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="300">
                        <div class="d-flex align-items-start tw-gap-5 flex-column animation-item">
                            <span>
                                <img src="/frontend1/tcul-img/icons/age.svg" alt="icon"
                                    class="animate__swing">
                            </span>
                            <div>
                                <span class="fw-semibold tw-text-405 text-neutral-600 d-block tw-mb-2">
                                    Age Group
                                </span>
                                <h3 class="fw-normal text-main-600 counter">5+</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="350">
                        <div class="d-flex align-items-start tw-gap-5 flex-column animation-item">
                            <span>
                                <img src="/frontend1/assets/images/icon/our-galler-bottom-icon4.png" alt="icon"
                                    class="animate__swing">
                            </span>
                            <div>
                                <span class="fw-semibold tw-text-405 text-neutral-600 d-block tw-mb-2">
                                    Students
                                </span>
                                <h3 class="fw-normal text-main-600 counter">1500+</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Curriculum Section --}}
    <section class="py-80">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-12 mt-4">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold curriculum-title text-neutral-950">Curriculum</h2>
                    </div>
                    <div>
                        <div class="row gy-5">
                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                                <div class="bg-main-50 tw-py-11 tw-px-8 tw-rounded-all animation-item tw-rounded-all h-100">
                                    <img src="/frontend1/tcul-img/icons/course1.svg" alt="icon"
                                            class="tw-mb-5 animate__bounce d-block">
                                    <span class="fw-semibold tw-text-405 text-neutral-950 tw-mb-5">Basic Tactics</span>
                                    <p class="fw-normal text-paragraph-500">Basic tactics in chess are essential tools that
                                        allow
                                        players to gain an advantage through
                                        short-term, calculated sequences of moves. These tactics often involve creating threats,
                                        capturing opponent's pieces, or achieving a superior position. Some of the most common
                                        basic
                                        tactics include: The Pin, Double attack, discovered attack, skewer etc.</p>
                                </div>
                            </div>

                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                                <div class="bg-primary-100 tw-py-11 tw-px-8 tw-rounded-all animation-item tw-rounded-all h-100">
                                    <img src="/frontend1/tcul-img/icons/course2.svg" alt="icon"
                                            class="tw-mb-5 animate__bounce d-block">
                                    <span class="fw-semibold tw-text-405 text-neutral-950 tw-mb-5">Tactical Motif</span>
                                    <p class="fw-normal text-paragraph-500"> A tactical motif in chess refers to a recurring
                                        pattern or
                                        idea that can be used to achieve
                                        a tactical goal, such as winning material or delivering checkmate. These motifs are
                                        essential tools that help players recognize and execute tactical opportunities in a
                                        game.
                                        Understanding tactical motifs allows players to quickly spot chances to gain an
                                        advantage.
                                        Some common tactical motifs include: Deflection, decoy, Overloading, Clearance,
                                        interference</p>
                                </div>
                            </div>

                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                                <div class="bg-primary-100 tw-py-11 tw-px-8 tw-rounded-all animation-item tw-rounded-all h-100">
                                    <img src="/frontend1/tcul-img/icons/course3.svg" alt="icon"
                                            class="tw-mb-5 animate__bounce d-block">
                                    <span class="fw-semibold tw-text-405 text-neutral-950 tw-mb-5">Basic Pawn Ending</span>
                                    <p class="fw-normal text-paragraph-500"> Basic king and pawn endings in chess are critical
                                        to
                                        master, as they frequently determine
                                        the outcome of a game. In these endings, only kings and pawns remain, and the goal is
                                        typically to promote a pawn to a queen or another piece, which often leads to a winning
                                        advantage. Here are some fundamental concepts and principles for basic king and pawn
                                        endings: Opposition, Outflanking, Rule of the square</p>
                                </div>
                            </div>

                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                                <div class="bg-main-50 tw-py-11 tw-px-8 tw-rounded-all animation-item tw-rounded-all h-100">
                                    <img src="/frontend1/tcul-img/icons/course4.svg" alt="icon"
                                            class="tw-mb-5 animate__bounce d-block">
                                    <span class="fw-semibold tw-text-405 text-neutral-950 tw-mb-5">Zugzwang</span>
                                    <p class="fw-normal text-paragraph-500">Zugzwang is a German term used in chess to describe
                                        a
                                        situation where a player is forced to
                                        make a move, but every possible move worsens their position. In other words, the player
                                        would prefer to pass their turn (if that were allowed), as any move they make leads to a
                                        disadvantage, often resulting in a loss of material, a weakened position, or even
                                        checkmate.
                                        Zugzwang is a common concept in endgames, where limited pieces and options mean that
                                        every
                                        move becomes critical.</p>
                                </div>
                            </div>

                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                                <div class="bg-main-50 tw-py-11 tw-px-8 tw-rounded-all animation-item tw-rounded-all h-100">
                                    <img src="/frontend1/tcul-img/icons/course5.svg" alt="icon"
                                            class="tw-mb-5 animate__bounce d-block">
                                    <span class="fw-semibold tw-text-405 text-neutral-950 tw-mb-5">Basic Mating Pattern</span>
                                    <p class="fw-normal text-paragraph-500"> A mating pattern in chess refers to a specific
                                        arrangement
                                        of pieces that leads to
                                        checkmate, where the opposing king is trapped and unable to escape capture. Recognizing
                                        these patterns allows players to deliver checkmate efficiently and capitalize on
                                        opportunities to end the game. Here are some common mating patterns: Back Rank Mate,
                                        Smothered Mate, Anastasia's Mate, Arabian Mate.</p>
                                </div>
                            </div>


                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                                <div class="bg-primary-100 tw-py-11 tw-px-8 tw-rounded-all animation-item tw-rounded-all h-100">
                                    <img src="/frontend1/tcul-img/icons/course6.svg" alt="icon"
                                            class="tw-mb-5 animate__bounce d-block">
                                    <span class="fw-semibold tw-text-405 text-neutral-950 tw-mb-5">Counter Attack</span>
                                    <p class="fw-normal text-paragraph-500"> In chess, a counter-attack is a strategy where a
                                        player
                                        responds to their opponent's attack
                                        by launching an attack of their own, often targeting a different part of the board or
                                        threatening a critical piece. Instead of simply defending against the opponent's
                                        aggression,
                                        a counter-attack puts pressure on the opponent and forces them to react, potentially
                                        turning
                                        the tables in the game.</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    
@endsection
