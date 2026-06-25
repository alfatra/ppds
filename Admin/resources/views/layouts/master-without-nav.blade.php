<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | Nazox - Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesdesign" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('build/images/logo_rssm.png') }}">

    @include('layouts.head-css')
</head>

<body class="auth-body-bg">

    @yield('content')

    @include('layouts.vendor-scripts')
</body>

</html>
