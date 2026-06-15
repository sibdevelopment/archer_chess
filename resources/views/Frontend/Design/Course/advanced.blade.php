@extends('layouts.revamp')
@section('title', 'Chess for Advanced')
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
                            <h3 class="text-center tw-mb-6 text-neutral-950">Chess for Advanced</h3>
                            <ul class="d-flex align-items-center justify-content-center tw-gap-4">
                                <li><a href="/design/home" class="text-main-600 hover-text-main-700 tw-text-405"> <i
                                            class="las la-home"></i> Home</a> </li>
                                <li><i class="text-main-600 hover-text-main-700 tw-text-405">/</i></li>
                                <li> <span class="text-main-600 hover-text-main-700 tw-text-405"> Chess for Advanced
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
                            <span class="fw-medium tw-text-5 text-paragraph-500">Advanced players possess a deep knowledge
                                of opening theory, enabling them to navigate through the complexities of various openings
                                with precision and flexibility. They understand the nuances of pawn structures, recognizing
                                subtle positional advantages and formulating long-term strategic plans accordingly.
                                Moreover, advanced players excel in tactical warfare, effortlessly spotting combinations and
                                exploiting tactical motifs to gain material or positional superiority.
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
                        <div class="d-flex align-items-start tw-gap-5 animation-item flex-column">
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
                        <div class="d-flex align-items-start tw-gap-5 animation-item flex-column">
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
                        <div class="d-flex align-items-start tw-gap-5 animation-item flex-column">
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
                        <div class="d-flex align-items-start tw-gap-5 animation-item flex-column">
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
                                    <span class="fw-semibold tw-text-405 text-neutral-950 tw-mb-5">Advanced Tactics

                                    </span>
                                    <p class="fw-normal text-paragraph-500">Advanced tactics in chess involve intricate
                                        combinations and maneuvers that require a high level of calculation, foresight, and
                                        creativity. These tactics often go beyond simple tactical motifs like forks or pins,
                                        involving multiple pieces and moves to achieve a specific objective. Advanced tactics
                                        can include tactics such as sacrifices, deflections, interference, and Zugzwang, among
                                        others.
                                    </p>
                                </div>
                            </div>

                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                                <div class="bg-primary-100 tw-py-11 tw-px-8 tw-rounded-all animation-item tw-rounded-all h-100">
                                    <img src="/frontend1/tcul-img/icons/course2.svg" alt="icon"
                                                class="tw-mb-5 animate__bounce d-block">
                                    <span class="fw-semibold tw-text-405 text-neutral-950 tw-mb-5">End Game
                                    </span>
                                    <p class="fw-normal text-paragraph-500">In chess, the endgame refers to the stage of the
                                        game when most of the pieces have been exchanged or captured, leaving primarily kings
                                        and pawns on the board. The endgame is characterized by increased tactical and strategic
                                        complexity, as players aim to convert their remaining material into a winning advantage,
                                        typically through pawn promotion, checkmate, or achieving a decisive material advantage.
                                    </p>
                                </div>
                            </div>

                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                                <div class="bg-primary-100 tw-py-11 tw-px-8 tw-rounded-all animation-item tw-rounded-all h-100">
                                    <img src="/frontend1/tcul-img/icons/course3.svg" alt="icon"
                                                class="tw-mb-5 animate__bounce d-block">
                                    <span class="fw-semibold tw-text-405 text-neutral-950 tw-mb-5">Attacking King

                                    </span>
                                    <p class="fw-normal text-paragraph-500">Attacking the king in chess is a fundamental aspect
                                        of the game, where players aim to create threats against the opponent's king to either
                                        checkmate it or gain a decisive advantage. There are various ways to attack the king,
                                        ranging from direct assaults to subtle positional maneuvers.
                                        Direct attacks on the king often involve launching a coordinated assault with multiple
                                        pieces, aiming to force the king into a vulnerable position where it can be checkmated
                                        or subjected to a decisive combination. These attacks may include sacrificing material
                                        to open lines of attack, exploiting weaknesses in the opponent's pawn structure, or
                                        penetrating the opponent's position with powerful pieces.
                                    </p>
                                </div>
                            </div>

                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                                <div class="bg-main-50 tw-py-11 tw-px-8 tw-rounded-all animation-item tw-rounded-all h-100">
                                    <img src="/frontend1/tcul-img/icons/course4.svg" alt="icon"
                                                class="tw-mb-5 animate__bounce d-block">
                                    <span class="fw-semibold tw-text-405 text-neutral-950 tw-mb-5">Pawn Structure

                                    </span>
                                    <p class="fw-normal text-paragraph-500">Understanding and manipulating pawn structure is
                                        essential for strategic planning in chess. Players often strive to create favorable pawn
                                        structures that support their overall plans while exploiting weaknesses in their
                                        opponent's pawn formation. By carefully managing pawn breaks, controlling key squares,
                                        and assessing pawn structure dynamics, players can gain a significant strategic
                                        advantage in the game.
                                    </p>
                                </div>
                            </div>

                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                                <div class="bg-main-50 tw-py-11 tw-px-8 tw-rounded-all animation-item tw-rounded-all h-100">
                                    <img src="/frontend1/tcul-img/icons/course5.svg" alt="icon"
                                                class="tw-mb-5 animate__bounce d-block">
                                    <span class="fw-semibold tw-text-405 text-neutral-950 tw-mb-5">Out Post And Open File
                                    </span>
                                    <p class="fw-normal text-paragraph-500"> Outposts are typically located in the opponent's
                                        territory and are often occupied by a knight or bishop. These pieces stationed on
                                        outposts exert significant influence over the board, as they cannot be easily challenged
                                        or dislodged by opposing pawns. An open file, on the other hand, refers to a column on
                                        the chessboard that contains no pawns from either player. Open files provide a pathway
                                        for rooks to penetrate into the opponent's position, exerting pressure along the file
                                        and potentially targeting weak pawns or the opponent's king. Rooks placed on open files
                                        are often considered to be at their most effective, as they have unobstructed mobility
                                        and can engage in powerful attacks and tactical maneuvers.
                                        Both outposts and open files are key strategic elements in chess, offering players
                                        opportunities to enhance their piece activity, control important squares, and launch
                                        decisive attacks. Skilled players seek to exploit these strategic advantages while
                                        simultaneously defending against their opponent's attempts to do the same, as they
                                        navigate the complexities of the chessboard and strive for victory.
                                    </p>
                                </div>
                            </div>

                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                                <div class="bg-primary-100 tw-py-11 tw-px-8 tw-rounded-all animation-item tw-rounded-all h-100">
                                    <img src="/frontend1/tcul-img/icons/course6.svg" alt="icon"
                                                class="tw-mb-5 animate__bounce d-block">
                                    <span class="fw-semibold tw-text-405 text-neutral-950 tw-mb-5">Positional Chess
                                    </span>
                                    <p class="fw-normal text-paragraph-500"> Positional chess refers to a strategic approach in
                                        which players prioritize long-term advantages and maneuvering over immediate tactical
                                        gains. Unlike tactical chess, which focuses on short-term combinations and material
                                        gain, positional chess emphasizes the control of key squares, piece placement, pawn
                                        structure, and the exploitation of positional weaknesses in the opponent's position.
                                    </p>
                                </div>
                            </div>

                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="200">
                                <div class="bg-primary-100 tw-py-11 tw-px-8 tw-rounded-all animation-item tw-rounded-all h-100">
                                    <img src="/frontend1/tcul-img/icons/course1.svg" alt="icon"
                                                class="tw-mb-5 animate__bounce d-block">
                                    <span class="fw-semibold tw-text-405 text-neutral-950 tw-mb-5">Advanced Opening

                                    </span>
                                    <p class="fw-normal text-paragraph-500"> Advanced opening systems often prioritize control
                                        of the center, development of pieces, and harmonious pawn structure while also
                                        considering long-term strategic goals. They may involve complex variations and
                                        transpositions, allowing players to navigate through a maze of possibilities while
                                        aiming for favorable positions.
                                        Examples of advanced openings include the Sicilian Defense, Ruy Lopez, Queen's Gambit,
                                        Nimzo-Indian Defense, and Grünfeld Defense, among others. These openings have been
                                        extensively analyzed by top-level players and have rich histories of theory and practice
                                        behind them.
                                    </p>
                                </div>
                            </div>

                            <div class="col-sm-6" data-aos="fade-up" data-aos-duration="600" data-aos-delay="100">
                                <div class="bg-main-50 tw-py-11 tw-px-8 tw-rounded-all animation-item tw-rounded-all h-100">
                                    <img src="/frontend1/tcul-img/icons/course2.svg" alt="icon"
                                                class="tw-mb-5 animate__bounce d-block">
                                    <span class="fw-semibold tw-text-405 text-neutral-950 tw-mb-5">Passed Pawn
                                    </span>
                                    <p class="fw-normal text-paragraph-500"> A passed pawn in chess refers to a pawn that has
                                        advanced beyond the reach of any opposing pawns on adjacent files. This means that there
                                        are no enemy pawns on either side of the passed pawn's file, allowing it to potentially
                                        promote into a higher-value piece, typically a queen, without being captured by an
                                        opposing pawn. Passed pawns are a significant strategic asset because they have the
                                        potential to create powerful threats and exert significant pressure on the opponent's
                                        position.
                                        The strength of a passed pawn lies in its ability to advance towards the eighth rank,
                                        where it can promote to a more powerful piece, drastically altering the balance of power
                                        on the board. Advanced passed pawns can become formidable weapons in the endgame, where
                                        their promotion potential becomes more pronounced. </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
