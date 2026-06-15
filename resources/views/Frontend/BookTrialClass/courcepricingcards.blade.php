<style>
    .basic-plan-h3 {
        font-size: 24px;
        color: #ff5364;
        margin-bottom: 15px;
    }


    /* Make the row flex */
    #pricing .row {
        display: flex;
        flex-wrap: wrap;
    }

    /* Make each column behave like a flex item and allow full height stretch */
    #pricing .col-6 {
        display: flex;
    }

    /* Ensure the card takes full height and stretches visually */
    #pricing .student-widget.select-plan-group {
        flex: 1;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    /* This helps with consistency if any padding/margins are causing issues */
    #pricing .student-widget-group {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    ul li {
        list-style-type: none !important;
        margin-top: 25px !important;
    }
</style>

 
    @php
        $pricingData = [
            'SINGAPORE' => [
                'currency' => 'SGD',
                'beginners' => [
                    'price' => 50,
                    'sessions' => 10,
                    'full_course_price' => 135,
                    'total_sessions' => 30,
                    'link' => 'https://rzp.io/l/beginnerssingapore',
                ],
                'intermediate' => [
                    'price' => 55,
                    'sessions' => 10,
                    'full_course_price' => 297,
                    'total_sessions' => 60,
                    'link' => 'https://rzp.io/l/intermediatesingapore',
                ],
                'advanced' => [
                    'price' => 60,
                    'sessions' => 10,
                    'full_course_price' => 324,
                    'total_sessions' => 60,
                    'link' => 'https://rzp.io/l/advancedsingapore',
                ],
                'expert' => [
                    'price' => 65,
                    'sessions' => 10,
                    'full_course_price' => 351,
                    'total_sessions' => 60,
                    'link' => 'https://rzp.io/l/expertsingapore',
                ],
            ],
            'CANADA' => [
                'currency' => 'CAD',
                'beginners' => [
                    'price' => 63,
                    'sessions' => 10,
                    'full_course_price' => 170,
                    'total_sessions' => 30,
                    'link' => 'https://rzp.io/l/beginnerscanada',
                ],
                'intermediate' => [
                    'price' => 70,
                    'sessions' => 10,
                    'full_course_price' => 372,
                    'total_sessions' => 60,
                    'link' => 'https://rzp.io/l/intermediatecanada',
                ],
                'advanced' => [
                    'price' => 77,
                    'sessions' => 10,
                    'full_course_price' => 416,
                    'total_sessions' => 60,
                    'link' => 'https://rzp.io/l/advancedcanada',
                ],
                'expert' => [
                    'price' => 83,
                    'sessions' => 10,
                    'full_course_price' => 452,
                    'total_sessions' => 60,
                    'link' => 'https://rzp.io/l/expertcanada',
                ],
            ],
            'INDIA' => [
                'currency' => 'Rs',
                'beginners' => [
                    'price' => 3000,
                    'sessions' => 10,
                    'full_course_price' => 8100,
                    'total_sessions' => 30,
                    'link' => 'https://rzp.io/l/beginnersindia',
                ],
                'intermediate' => [
                    'price' => 3300,
                    'sessions' => 10,
                    'full_course_price' => 17820,
                    'total_sessions' => 60,
                    'link' => 'https://rzp.io/l/intermediateindia',
                ],
                'advanced' => [
                    'price' => 3600,
                    'sessions' => 10,
                    'full_course_price' => 19440,
                    'total_sessions' => 60,
                    'link' => 'https://rzp.io/l/advancedindia',
                ],
                'expert' => [
                    'price' => 4000,
                    'sessions' => 10,
                    'full_course_price' => 21600,
                    'total_sessions' => 60,
                    'link' => 'https://rzp.io/l/expertindia',
                ],
            ],
            'USA' => [
                'currency' => 'USD',
                'beginners' => [
                    'price' => 45,
                    'sessions' => 10,
                    'full_course_price' => 122,
                    'total_sessions' => 30,
                    'link' => 'https://rzp.io/l/beginnersusa',
                ],
                'intermediate' => [
                    'price' => 50,
                    'sessions' => 10,
                    'full_course_price' => 270,
                    'total_sessions' => 60,
                    'link' => 'https://rzp.io/l/intermediateusa',
                ],
                'advanced' => [
                    'price' => 55,
                    'sessions' => 10,
                    'full_course_price' => 297,
                    'total_sessions' => 60,
                    'link' => 'https://rzp.io/l/advancedusa',
                ],
                'expert' => [
                    'price' => 60,
                    'sessions' => 10,
                    'full_course_price' => 324,
                    'total_sessions' => 60,
                    'link' => 'https://rzp.io/l/expertusa',
                ],
            ],
            'UAE' => [
                'currency' => 'AED',
                'beginners' => [
                    'price' => 125,
                    'sessions' => 10,
                    'full_course_price' => 338,
                    'total_sessions' => 30,
                    'link' => 'https://rzp.io/l/beginnersuae',
                ],
                'intermediate' => [
                    'price' => 138,
                    'sessions' => 10,
                    'full_course_price' => 729,
                    'total_sessions' => 60,
                    'link' => 'https://rzp.io/l/intermediateuae',
                ],
                'advanced' => [
                    'price' => 163,
                    'sessions' => 10,
                    'full_course_price' => 881,
                    'total_sessions' => 60,
                    'link' => 'https://rzp.io/l/advanceduae',
                ],
                'expert' => [
                    'price' => 190,
                    'sessions' => 10,
                    'full_course_price' => 1026,
                    'total_sessions' => 60,
                    'link' => 'https://rzp.io/l/expertuae',
                ],
            ],
            'QATAR' => [
                'currency' => 'QR',
                'beginners' => [
                    'price' => 125,
                    'sessions' => 10,
                    'full_course_price' => 338,
                    'total_sessions' => 30,
                    'link' => 'https://rzp.io/l/beginnersuae',
                ],
                'intermediate' => [
                    'price' => 138,
                    'sessions' => 10,
                    'full_course_price' => 729,
                    'total_sessions' => 60,
                    'link' => 'https://rzp.io/l/intermediateuae',
                ],
                'advanced' => [
                    'price' => 163,
                    'sessions' => 10,
                    'full_course_price' => 881,
                    'total_sessions' => 60,
                    'link' => 'https://rzp.io/l/advanceduae',
                ],
                'expert' => [
                    'price' => 190,
                    'sessions' => 10,
                    'full_course_price' => 1026,
                    'total_sessions' => 60,
                    'link' => 'https://rzp.io/l/expertuae',
                ],
            ],
        ];

        $prices = $pricingData[$country];
    @endphp
    {{-- @if ($country == 'INDIA') --}}
    <div class="container py-5 text-center" id="pricing">
        <span style="color:#ff5364;font-size:32px; text-center">Course Structure</span>
        <div class="section-header text-center">
            <h2 class="fw-bold" style="-webkit-text-stroke:1px black; color:#ffe88f;">
                From beginner to expert – structured online chess classes with weekly sessions, tournaments, puzzles,
                and GM-led activities.
            </h2>
        </div>
        <div class="row g-4">
            <!-- Beginners -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-lg border-0 rounded-4 text-center p-4">
                    <h4 class="fw-bold text-primary">Beginners</h4>
                    <h6 class="text-muted">Explore The World Of Chess</h6>
                    <hr>
                    <ul class="list-unstyled text-start">
                        <li><i class="fas fa-chess-pawn text-primary me-2"></i>30 Training Sessions – step-by-step
                            foundation</li>
                        <li><i class="fas fa-chess-knight text-success me-2"></i>15+ Online Tournaments – real
                            practice</li>
                        <li><i class="fas fa-chess-bishop text-warning me-2"></i>15+ Opening Classes – chess
                            openings & strategies</li>
                        <li><i class="fas fa-chess-rook text-danger me-2"></i>Live Puzzle Homework – daily practice
                            portal</li>
                        <li><i class="fas fa-chess-queen text-info me-2"></i>PDF Theory Material – GM-recommended
                            content</li>
                    </ul>
                    <a href="{{ $prices['beginners']['link'] }}" class="btn btn-primary rounded-pill mt-3">Explore
                        More</a>
                </div>
            </div>

            <!-- Intermediate -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-lg border-0 rounded-4 text-center p-4">
                    <h4 class="fw-bold text-success">Intermediate</h4>
                    <h6 class="text-muted">Road to Championship</h6>
                    <hr>
                    <ul class="list-unstyled text-start">
                        <li><i class="fas fa-chess-pawn text-primary me-2"></i>60 Training Sessions – strategy &
                            endgames</li>
                        <li><i class="fas fa-chess-knight text-success me-2"></i>30+ Online Tournaments – sharpen
                            skills</li>
                        <li><i class="fas fa-chess-bishop text-warning me-2"></i>30+ Opening Classes – advanced
                            variations</li>
                        <li><i class="fas fa-chess-rook text-danger me-2"></i>Live Puzzle Homework – daily
                            challenges</li>
                        <li><i class="fas fa-chess-queen text-info me-2"></i>PDF Theory Material – structured GM
                            notes</li>
                    </ul>
                    <a href="{{ $prices['intermediate']['link'] }}" class="btn btn-success rounded-pill mt-3">Explore
                        More</a>
                </div>
            </div>

            <!-- Advanced -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-lg border-0 rounded-4 text-center p-4">
                    <h4 class="fw-bold text-warning">Advanced</h4>
                    <h6 class="text-muted">Ready To Get FIDE Rating</h6>
                    <hr>
                    <ul class="list-unstyled text-start">
                        <li><i class="fas fa-chess-pawn text-primary me-2"></i>60 Training Sessions – advanced
                            strategy</li>
                        <li><i class="fas fa-chess-knight text-success me-2"></i>30+ Online Tournaments – stronger
                            opponents</li>
                        <li><i class="fas fa-chess-bishop text-warning me-2"></i>30+ Opening Classes – deep theory
                        </li>
                        <li><i class="fas fa-chess-rook text-danger me-2"></i>Live Puzzle Homework – tactical
                            exercises</li>
                        <li><i class="fas fa-chess-queen text-info me-2"></i>PDF Theory Material – GM designed
                            content</li>
                    </ul>
                    <a href="{{ $prices['advanced']['link'] }}" class="btn btn-warning rounded-pill mt-3">Explore
                        More</a>
                </div>
            </div>

            <!-- Expert -->
            <div class="col-md-6 col-lg-3">
                <div class="card h-100 shadow-lg border-0 rounded-4 text-center p-4">
                    <h4 class="fw-bold text-danger">Expert</h4>
                    <h6 class="text-muted">FIDE Rating Course</h6>
                    <hr>
                    <ul class="list-unstyled text-start">
                        <li><i class="fas fa-chess-pawn text-primary me-2"></i>Training Sessions – By GM/IM/FM Coaches
                        </li>
                        <li><i class="fas fa-chess-knight text-success me-2"></i>Online Tournaments – pro-level
                            practice</li>
                        <li><i class="fas fa-chess-bishop text-warning me-2"></i>Opening Repertoire Classes – By
                            GM/IM/FM
                            Coaches
                        </li>
                        <li><i class="fas fa-chess-rook text-danger me-2"></i>Live Puzzle Homework – high-level
                            tactics</li>
                        <li><i class="fas fa-chess-queen text-info me-2"></i>PDF Theory Material – GM structured
                            learning</li>
                    </ul>
                    <a href="{{ $prices['expert']['link'] }}" class="btn btn-danger rounded-pill mt-3">Explore
                        More</a>
                </div>
            </div>
        </div>
    </div>
    {{-- @endif --}}

    <div class="container my-4">
        <div class="row justify-content-center">
            <div class="col-md-6 mb-4">
                <div class="module-details text-white p-4" style=" border-radius: 10px;">
                    <h4 class="text-warning mb-3">📘 <strong>Course Structure and Weekly Schedule</strong></h4>
                    <ul class="text-start" style="list-style-type: disc; padding-left: 1.5rem;">
                        <li>There are <strong>3 sessions per week</strong>, each lasting 50 minutes.</li>
                        <li><strong>Timing:</strong> Flexible based on the student's level.</li>
                    </ul>
                    <h5 class="mt-4 text-success">🎯 <strong>Free Activities</strong></h5>
                    <ul class="text-start" style="list-style-type: circle; padding-left: 1.5rem;">
                        <li><strong>Sunday:</strong> Practice Tournament & Extra Session</li>
                        <li><strong>Assignment:</strong> Homework + Class Recordings provided</li>
                        <li><strong>Assessment:</strong> Test Review + Parent Meeting once in a month</li>
                        <li><strong>Rewards:</strong> Monthly Prize Tournament</li>
                        <li><strong>GM Camp:</strong> Special sessions with Grandmaster coaches</li>
                    </ul>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="module-details text-white p-4" style=" border-radius: 10px;">
                    <h5 class="text-info mb-3">🌍 <strong>Special Features</strong></h5>
                    <ul class="text-start" style="list-style-type: circle; padding-left: 1.5rem;">
                        <li>Weekly Online International FIDE Rating Tournament</li>
                    </ul>

                    <h5 class="mt-4 text-primary">👥 <strong>Group Size</strong></h5>
                    <ul class="text-start" style="list-style-type: circle; padding-left: 1.5rem;">
                        <li><strong>Regular Batches:</strong> Typically 5 to 6 kids per group</li>
                        <li><strong>With Titled Player (FM, IM, GM):</strong> Group size may be 10+ kids</li>
                    </ul>

                    <div class="mt-4 text-center">
                        <em>Above structures is for Group Classes only</em> <br>
                        <em>Private Coaching is customized as per the student's requirements</em>
                    </div>
                </div>
            </div>
        </div>
    </div> 