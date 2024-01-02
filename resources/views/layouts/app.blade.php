<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- ... other head elements ... -->
    <link rel="stylesheet" href="{{ mix('css/assets/codebase.css') }}">
    <link rel="stylesheet" href="{{ mix('assets/css/styles.css') }}">
</head>
<body>
    @inertia

    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
