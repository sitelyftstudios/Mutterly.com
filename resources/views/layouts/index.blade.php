<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>

        <title><?php echo config('app.name'); ?> | The premier poetry community</title>

        <!-- Meta -->
        <meta charset="UTF-8">

        <meta name="description" content="<?php echo config('app.description'); ?>">
        <meta name="keywords" content="<?php echo config('app.keywords'); ?>">
        <meta name="author" content="<?php echo config('app.author'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="alternate" hreflang="en" href="https://bea.com/" />

        <!-- Google -->

        <!-- Favicon -->
        <link rel="shortcut icon" type="image/png" href=""/>

        <link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700,800,900" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">

        <!-- Specific page stylesheet -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="{{ asset('css/'.$stylesheet.'.css') }}" rel="stylesheet" type="text/css"/>

        <!-- Script -->
        <script defer src="https://use.fontawesome.com/releases/v5.0.2/js/all.js"></script>
    </head>
    <body>
        <div class="website">
            <div class="header-hold">
                @include('templates.header')
            </div>
            <div class="inner-website">
                @yield('content')
            </div>
            <div class="footer-hold">
                @include('templates.footer')
            </div>
        </div>

        <!-- Javascript -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="{{  asset('js/global.js') }}" type="text/javascript"></script>

        <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        </script>

    <script src="https://js.pusher.com/4.1/pusher.min.js"></script>
    <script>
    $(function(){
        // Enable pusher logging - don't include this in production
        <?php if(env('APP_ENV') !== 'production'){ ?>
            Pusher.logToConsole = true;
        <?php } ?>

        var pusher = new Pusher("<?php echo env('PUSHER_APP_KEY'); ?>", {
            cluster: '<?php echo env('PUSHER_APP_CLUSTER'); ?>',
            encrypted: true
        });


        // Notifications
    });
    </script>
    </body>
</html>