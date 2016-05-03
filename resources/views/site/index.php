<!DOCTYPE html>
<html lang="en">
<head>
    <title>Call us</title>
    <meta name="csrf-token" content="<?=csrf_token()?>">

    <link rel="stylesheet" href="/css/site.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="/js/flagstrap/dist/css/flags.css">
    <script>var countries = <?=$countries?>;</script>

    <style>
        #countries-list {
            display: inline;
        }
    </style>
</head>

<body>
<div class="container">
    <h1 class="text-center">CONTACT US</h1>
    <hr>

    <div class="row">
        <div class="col-md-12">
            <div id="phone-container" class="text-center">
                <?= $content ?>
            </div>
        </div>
    </div>
</div>

<!-- Include page dependencies -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="/js/flagstrap/dist/js/jquery.flagstrap.js"></script>
<script src="/js/app.js"></script>
</body>
</html>