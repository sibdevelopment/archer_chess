<!DOCTYPE html>
<html lang="en">
	<head>
		<title>{{ config('app.name') }}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="handheldfriendly" content="true" />
		<meta name="MobileOptimized" content="width" />
		<meta name="description" content="Mordenize" />
		<meta name="author" content="" />
		<meta name="keywords" content="Mordenize" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<!-- <link rel="shortcut icon" type="image/png" href="/backend/dist/images/logos/favicon.ico" /> -->
		<link  id="themeColors"  rel="stylesheet" href="/backend/dist/css/style.min.css" />
        <link id="themeColors" rel="stylesheet" href="/backend/dist/css/techincul.css" />
	</head>
    <style>
        .btn-custom {
            padding: 12px 24px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #545b62;
        }

        .login-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .btn-switch {
            padding: 6px 15px;
            font-size: 14px;
            border-radius: 6px;
            text-decoration: none;
            border: 1px solid #007bff;
            color: #007bff;
            background: transparent;
            transition: all 0.3s ease;
        }

        .btn-switch:hover, .btn-switch.active {
            background: #007bff;
            color: #fff;
        }

    </style>
	<body>
		<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
			<div class="position-relative overflow-hidden radial-gradient min-vh-100">
				<div class="position-relative z-index-5">
					<div class="row">
						<div class="col-xl-7 col-xxl-8">
							<a href="./index.html" class="text-nowrap logo-img d-block px-14 py-9 w-100">
							</a>
							<div class="d-none d-xl-flex align-items-center justify-content-center" style="height: calc(100vh - 80px);">
                                <img src="/frontend/tcul_img/tutor-login.svg" alt="" class="img-fluid" width="500">
							</div>
						</div>
						<div class="col-xl-5 col-xxl-4">
							<div class="authentication-login min-vh-100 bg-body row justify-content-center align-items-center p-4">
								<div class="col-sm-8 col-md-6 col-xl-9">
                                    <div class="login-buttons text-center">
                                        <a href="/student/login" class="btn btn-switch {{ request()->is('student/login') ? 'active' : '' }}">Student</a>
                                        <a href="/login" class="btn btn-switch {{ request()->is('login') ? 'active' : '' }}">Staff</a>
                                    </div>


									<div class="text-center">
										<img src="/backend/images/ArcherKids-logo.png" width="180" alt="" />
										<br/><br/>
										<h2 class="mb-3 fs-7 fw-bolder">Welcome to Archer Chess Academy</h2>
										<p class=" mb-9">Your Admin Dashboard</p>
									</div>
									<form method="POST" action="{{ route('login') }}">
										@csrf
										<div class="mb-3">
											<label for="exampleInputEmail1" class="form-label">E-Mail</label>
											<input type="email" class="form-control" name="email"/>
											@error('email')
												<span class="text-danger">{{ $message }}</span>
											@enderror

										</div>
										<div class="mb-4">
											<label for="exampleInputPassword1" class="form-label">Password</label>
											<input type="password" class="form-control" name="password">
										</div> 
										
										@error('password')
												<span class="text-danger">{{ $message }}</span>
											@enderror

											
										<button type="submit" class="btn btn-primary w-100 py-8 mb-4 rounded-2">Sign In</button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<script src="/backend/dist/libs/jquery/dist/jquery.min.js"></script>
		<script src="/backend/dist/libs/simplebar/dist/simplebar.min.js"></script>
		<script src="/backend/dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
		<script src="/backend/dist/js/app.min.js"></script>
		<script src="/backend/dist/js/app.init.js"></script>
		<script src="/backend/dist/js/app-style-switcher.js"></script>
		<script src="/backend/dist/js/sidebarmenu.js"></script>
		<script src="/backend/dist/js/custom.js"></script>
	</body>
</html>
