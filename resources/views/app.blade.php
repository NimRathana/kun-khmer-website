<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/png" href="build/assets/icons/account-box-multiple.svg" />

        <title inertia>{{ config('app.name', 'Rathana') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css" rel="stylesheet">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Battambang:wght@100;300;400;700;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        <script src="//cdn.quilljs.com/1.3.6/quill.min.js"></script>
        <link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased" style="background-color: gray;font;font-family: 'Battambang';">
        @inertia
    </body>
</html>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        setTimeout(() => {
            //move button setting
            const button = document.querySelector('.setting_btn');
            let isDragging = false;
            let offsetX, offsetY;

            if(button != undefined){
                button.addEventListener('mousedown', (event) => {
                    isDragging = true;
                    // Calculate the initial offset of the mouse cursor from the button's position
                    offsetX = event.clientX - button.getBoundingClientRect().left;
                    offsetY = event.clientY - button.getBoundingClientRect().top;

                    // Add a style to prevent the text selection during dragging
                    document.body.style.userSelect = 'none';
                });

                document.addEventListener('mousemove', (event) => {
                    if (isDragging) {
                        const x = event.clientX - offsetX;
                        const y = event.clientY - offsetY;

                        // Update the button's position based on the mouse movement
                        button.style.left = `${x}px`;
                        button.style.top = `${y}px`;
                    }
                });

                document.addEventListener('mouseup', () => {
                    isDragging = false;
                    document.body.style.userSelect = ''; // Restore the text selection behavior
                });
            }

            //grid height
            const headElement = document.getElementsByClassName("head")[0];
            if (headElement) {
                const height = headElement.offsetHeight;
                const bodyHeight = document.getElementsByClassName("body")[0];
                bodyHeight.style.height = "calc(100% - " + height + "px)";
            }

            const txtsearch = document.getElementsByClassName("search")[0];
            let detailsElement = '';
            if(txtsearch != undefined){
                detailsElement = txtsearch.querySelector(".v-input__details");
            }

            if (detailsElement) {
                detailsElement.classList.remove("v-input__details");
            }

        }, 200);
    });
</script>
<style>
    body{
        transition: .25s;
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        scroll-behavior: smooth;
        line-height: 1.5;
    }
    input {
        background-color: transparent !important;
    }
    [type='text']:focus, input:where(:not([type])):focus, [type='email']:focus, [type='url']:focus, [type='password']:focus, [type='number']:focus, [type='date']:focus, [type='datetime-local']:focus, [type='month']:focus, [type='search']:focus, [type='tel']:focus, [type='time']:focus, [type='week']:focus, [multiple]:focus, textarea:focus, select:focus{
        --tw-ring-shadow: none !important;
    }
    .v-table{
        height: 100%;
    }
    .v-table__wrapper{
        height: 1px;
    }
    .rotate-animation {
    cursor: move;
    animation: rotation 2s infinite linear;
    }
    .v-theme--dark {
        --v-theme-background: 38, 50, 56;
    }
    .v-theme--light {
        --v-theme-background: 240, 245, 249;
    }
    @keyframes rotation {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
    }
</style>
