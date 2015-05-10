<?php ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="bbr">
    <link rel="icon" href="assets/img/favicon.ico">
    <link href="assets/css/plan.min.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <title>План лечения</title>
</head>
<body>
<!-- plan -->
<div class="container plan">
    <div class="header clearfix">
        <nav class="header__nav">
            <ul class="nav nav-pills pull-right">
                <!--<li class="header__nav-item active" role="presentation"><a href="#">Список планов</a></li>-->
                <li class="header__nav-item" role="presentation"><a href="<?=$data['links']['logout']?>">Выйти</a></li>
            </ul>
        </nav>
        <h1 class="header__title text-muted">План лечения</h1>
    </div>

    <?php include $contentView ?>

</div>
<!-- /plan -->
<footer class="footer">
    <p class="footer__text">&copy; 2015</p>
</footer>
<script src="assets/js/app.min.js"></script>
</body>
</html>