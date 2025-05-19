<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.head')
</head>
<body>
    <div class="container">
        @include('partials.logo')
        
        <div class="content">
            @yield('content')
        </div>

        @include('partials.footer')
    </div>
</body>
</html>