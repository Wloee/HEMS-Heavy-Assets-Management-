<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HEMS</title>
    <!-- Bootstrap CSS -->
     <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @yield('styles')
</head>
<body>


    <main class="py-4">
        @include('layouts.navbar')
        @yield('content')
    </main>

    <!-- Bootstrap JS (opsional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    @yield('scripts')
</body>
</html>
