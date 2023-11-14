const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js') // Compile app.js to public/js/app.js
   .postCss('resources/css/styles.css', 'public/css', [
       require('tailwindcss'), // Add this line if you want to use Tailwind CSS
   ]);
