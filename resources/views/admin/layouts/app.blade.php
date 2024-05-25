<!doctype html>
<html lang="en">

<head>
	@include('admin.layouts.head')
</head>

<body>
    @php
        use Carbon\Carbon;
    @endphp
	<!--wrapper-->
	<div class="wrapper">
		<!--sidebar wrapper -->
		<div class="sidebar-wrapper" data-simplebar="true">
			<div class="sidebar-header">
				<div>
					<img src="https://3.bp.blogspot.com/-84AZcdvvo6k/XxcAS-ve2mI/AAAAAAAAatg/MsweQPwt57oqf95KhA5Qg-Y2GUnqeqp4gCLcBGAsYHQ/s1600/Lambang-Kabupaten-Madiun_237-design.png" class="logo-icon" alt="logo icon">
				</div>
				<div>
					<h4 class="logo-text">BKK Madiun</h4>
				</div>
				<div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
				</div>
			</div>
			<!--navigation-->
			@include('admin.layouts.left-side-menu')
			<!--end navigation-->
		</div>
		<!--end sidebar wrapper -->
		<!--start header -->
		<header>
			<div class="topbar d-flex align-items-center">
				<nav class="navbar navbar-expand gap-3">
					<div class="top-menu ms-auto">
					</div>
					<div class="user-box dropdown px-3">
						<a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
							<img src="{{ asset('rocker/assets/images/avatars/avatar-2.png') }}" class="user-img" alt="user avatar">
							<div class="user-info">
								<p class="user-name mb-0">Admin BKK</p>
								<p class="designattion mb-0">Admin</p>
							</div>
						</a>
						<ul class="dropdown-menu dropdown-menu-end">
							<li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.logout') }}">
                                    <i class="bx bx-log-out-circle"></i><span>Logout</span>
                                </a>
							</li>
						</ul>
					</div>
				</nav>
			</div>
		</header>
		<!--end header -->
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
				<!--breadcrumb-->
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">@yield('subtitle', 'Dashboard')</div>
				</div>
				<!--end breadcrumb-->

                @yield('content')

			</div>
		</div>
		<!--end page wrapper -->
		<!--start overlay-->
		<div class="overlay toggle-icon"></div>
		<!--end overlay-->
		<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->
		<footer class="page-footer">
			<p class="mb-0">© {{Carbon::now()->year}} Bappeda Madiun All Rights Reserved.</p>
		</footer>
	</div>
    @include('sweetalert::alert')
	@include('admin.layouts.js')
</body>

</html>
