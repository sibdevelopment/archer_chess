@extends('layouts.revamp')
@section('title', 'Chess for Expert')
@section('content')

     {{-- Breadcrumb Section --}}
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
                            <h3 class="text-center tw-mb-6 text-neutral-950">Chess for Expert</h3>
                            <ul class="d-flex align-items-center justify-content-center tw-gap-4">
                                <li><a href="/design/home" class="text-main-600 hover-text-main-700 tw-text-405"> <i
                                            class="las la-home"></i> Home</a> </li>
                                <li><i class="text-main-600 hover-text-main-700 tw-text-405">/</i></li>
                                <li> <span class="text-main-600 hover-text-main-700 tw-text-405"> Chess for Expert
                                    </span> </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- About the Course Section --}}
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
                            <span class="fw-medium tw-text-5 text-paragraph-500">At the expert level in chess, players
                                demonstrate a deep understanding of both tactics and strategy, consistently making precise
                                moves while foreseeing complex combinations many moves ahead. Experts have a solid grasp of
                                opening theory, allowing them to develop their pieces effectively while controlling key
                                areas of the board. They are skilled in middle-game planning, knowing how to attack and
                                defend based on the unique characteristics of the position. Furthermore, their endgame
                                knowledge is extensive, enabling them to convert small advantages into wins or defend
                                difficult positions to secure a draw. Experts are also adept at psychological aspects of the
                                game, understanding how to create pressure and force mistakes from their opponents. Their
                                games reflect a balance between creativity and calculation, making them formidable and
                                versatile competitors.

                            </span>
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
                                    <span class="fw-semibold tw-text-405 text-neutral-950 tw-mb-5">Calculation in chess
                                    </span>
                                    <p class="fw-normal text-paragraph-500">Calculation in chess refers to the process of
                                        accurately visualizing and evaluating a series of moves and counter-moves during a game.
                                        It's a mental skill that allows players to anticipate their opponent's responses and
                                        plan several moves ahead. Strong calculation is essential in sharp, tactical positions
                                        where precision is required to avoid blunders and find the best continuation. Key
                                        Elements of Chess Calculation: Visualization,Candidate Moves,Forcing Moves

                                    </p>
                                </div>
                            </div>

                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                                <div class="bg-primary-100 tw-py-11 tw-px-8 tw-rounded-all animation-item tw-rounded-all h-100">
                                    <img src="/frontend1/tcul-img/icons/course2.svg" alt="icon"
                                                class="tw-mb-5 animate__bounce d-block">
                                    <span class="fw-semibold tw-text-405 text-neutral-950 tw-mb-5">Decision making
                                    </span>
                                    <p class="fw-normal text-paragraph-500"> Decision-making in chess is the process of
                                        evaluating the position, considering various options, and choosing the best possible
                                        move. Strong decision-making involves balancing intuition, calculation, and strategic
                                        understanding. Here are the key elements involved in chess decision-making :Material,
                                        King Safety ,Activity of Piece ,Pawn Structure

                                    </p>
                                </div>
                            </div>

                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                                <div class="bg-primary-100 tw-py-11 tw-px-8 tw-rounded-all animation-item tw-rounded-all h-100">
                                    <img src="/frontend1/tcul-img/icons/course3.svg" alt="icon"
                                                class="tw-mb-5 animate__bounce d-block">
                                    <span class="fw-semibold tw-text-405 text-neutral-950 tw-mb-5">Right Exchange
                                    </span>
                                    <p class="fw-normal text-paragraph-500"> In chess, the "right exchange" refers to making a
                                        trade of pieces that benefits your position, either strategically or tactically.
                                        Deciding when to exchange pieces is crucial, as it can shape the course of the game,
                                        affect the balance of material, and influence the dynamics of both the middle game and
                                        endgame. The key is to evaluate each exchange carefully to ensure it improves your
                                        position.

                                    </p>
                                </div>
                            </div>

                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                                <div class="bg-main-50 tw-py-11 tw-px-8 tw-rounded-all animation-item tw-rounded-all h-100">
                                    <img src="/frontend1/tcul-img/icons/course4.svg" alt="icon"
                                                class="tw-mb-5 animate__bounce d-block">
                                    <span class="fw-semibold tw-text-405 text-neutral-950 tw-mb-5">Fortress
                                    </span>
                                    <p class="fw-normal text-paragraph-500">A fortress refers to a defensive setup where the
                                        weaker side creates an impenetrable position, making it impossible for the opponent to
                                        make progress or break through, despite having a material or positional advantage. A
                                        fortress is often used in endgames when the player with fewer resources seeks to secure
                                        a draw by setting up a position where the stronger player cannot improve their position
                                        or force a win.

                                    </p>
                                </div>
                            </div>

                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                                <div class="bg-main-50 tw-py-11 tw-px-8 tw-rounded-all animation-item tw-rounded-all h-100">
                                    <img src="/frontend1/tcul-img/icons/course5.svg" alt="icon"
                                                class="tw-mb-5 animate__bounce d-block">
                                    <span class="fw-semibold tw-text-405 text-neutral-950 tw-mb-5">Improving piece position
                                    </span>
                                    <p class="fw-normal text-paragraph-500"> In chess involves repositioning your pieces to
                                        more effective squares where they exert greater influence and contribute more to your
                                        overall strategy. Here’s how you can improve your piece position: Centralization
                                        ,Avoiding Passive Positions ,Piece Coordination ,Creating Strong Outposts , Improving
                                        Pawn Structure.</p>
                                </div>
                            </div>

                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                                <div class="bg-primary-100 tw-py-11 tw-px-8 tw-rounded-all animation-item tw-rounded-all h-100">
                                    <img src="/frontend1/tcul-img/icons/course6.svg" alt="icon"
                                                class="tw-mb-5 animate__bounce d-block">
                                    <span class="fw-semibold tw-text-405 text-neutral-950 tw-mb-5">Exploiting Opponent's
                                        Weaknesses</span>
                                    <p class="fw-normal text-paragraph-500"> Exploiting your opponent's weaknesses in chess
                                        involves
                                        identifying and taking advantage of flaws or vulnerabilities in their position to gain a
                                        strategic or tactical edge. Here’s how you can effectively exploit these weaknesses.
                                    </p>
                                </div>
                            </div>

                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                                <div class="bg-primary-100 tw-py-11 tw-px-8 tw-rounded-all animation-item tw-rounded-all h-100">
                                    <img src="/frontend1/tcul-img/icons/course1.svg" alt="icon"
                                                class="tw-mb-5 animate__bounce d-block">
                                    <span class="fw-semibold tw-text-405 text-neutral-950 tw-mb-5">Identifying Weaknesses
                                    </span>
                                    <p class="fw-normal text-paragraph-500"> Pawn Weaknesses: Look for isolated pawns (pawns
                                        with no friendly pawns on adjacent files), doubled pawns (two pawns on the same file),
                                        or backward pawns (pawns that are behind their neighbors and cannot be advanced without
                                        being captured). Weak Squares: Identify squares that are not protected by pawns and are
                                        easily accessible to your pieces. These can be targets for your attacks or outposts for
                                        your pieces. King Safety: Check if the opponent's king is exposed or poorly defended.
                                        This could include weaknesses like uncastled king, open files around the king, or
                                        weaknesses on the king’s side. Piece Activity: Look for opponent’s pieces that are
                                        poorly placed, inactive, or blocked. Pieces stuck behind pawns or in corners can often
                                        be targeted for tactical operations.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


