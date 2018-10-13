<!DOCTYPE html>
<html lang="en" >
<!-- begin::Head -->
<head>
    <meta charset="utf-8" />
    <title>
        AlterBooks | 404
    </title>
    <meta name="description" content="Page Not Found">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!--begin::Web font -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
        WebFont.load({
            google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>
    <!--end::Web font -->
    <!--begin::Base Styles -->
    <link href="{{ url('/metronic/css/vendors.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ url('/metronic/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Base Styles -->
    <link rel="manifest" href="{{ url('/manifest.json') }}">
    <link rel="icon" type="image/png" href="{{ url('/img/icon-16.png') }}" sizes="16x16">
    <link rel="icon" type="image/png" href="{{ url('/img/icon-32.png') }}" sizes="32x32">
    <link rel="icon" type="image/png" href="{{ url('/img/icon-180.png') }}" sizes="180x180">
    <link rel="icon" type="image/png" href="{{ url('/img/icon-192.png') }}" sizes="192x192">
</head>
<!-- end::Head -->
<!-- end::Body -->
<body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default"  >
<!-- begin:: Page -->
<div class="m-grid m-grid--hor m-grid--root m-page">
    <div class="m-grid__item m-grid__item--fluid m-grid  m-error-1" style="background-image: url({{ url('/metronic/img/404-background.jpg') }});">
        <div class="m-error_container">
					<span class="m-error_number">
						<h1>
							404
						</h1>
					</span>
            <p class="m-error_desc">
                Page Not Found
            </p>
            <p class="m-error_desc">
                <a href="{{ route('dashboard') }}">вернуться на главную</a>
            </p>
        </div>
    </div>
</div>
<!-- end:: Page -->
<!--begin::Base Scripts -->
<script src="{{ url('/metronic/js/vendors.bundle.js') }}" type="text/javascript"></script>
<script src="{{ url('/metronic/js/scripts.bundle.js') }}" type="text/javascript"></script>
<!--end::Base Scripts -->
</body>
<!-- end::Body -->
</html>
