<?php
/** @var \UTI\Lib\Data $data */
/** @var \UTI\Core\View $view */
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="bbr">
    <link rel="icon" href="<?= $data('base') ?>assets/img/favicon.ico">
    <link href="<?= $data('base') ?>assets/css/plan.min.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <title><?= $data('title') ?></title>
</head>
<body>
<div class="wait-src js-wait-scr">
    <div class="jumbotron">
        <div id="spinningSquaresG">
            <div id="spinningSquaresG_1" class="spinningSquaresG">
            </div>
            <div id="spinningSquaresG_2" class="spinningSquaresG">
            </div>
            <div id="spinningSquaresG_3" class="spinningSquaresG">
            </div>
            <div id="spinningSquaresG_4" class="spinningSquaresG">
            </div>
            <div id="spinningSquaresG_5" class="spinningSquaresG">
            </div>
            <div id="spinningSquaresG_6" class="spinningSquaresG">
            </div>
            <div id="spinningSquaresG_7" class="spinningSquaresG">
            </div>
            <div id="spinningSquaresG_8" class="spinningSquaresG">
            </div>
        </div>
        <!--<span>Подождите пожалуйста...</span>-->
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="plan col-lg-8 col-lg-offset-2">
            <!-- header -->
            <header class="header">
                <nav class="navbar navbar-default">
                    <div class="container-fluid">
                        <!-- Brand and toggle get grouped for better mobile display -->
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                                    data-target="#bs-example-navbar-collapse-1">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand title" href="<?= $data('base') ?>">План лечения</a>
                        </div>

                        <!-- navbar-collapse -->
                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                            <ul class="nav navbar-nav navbar-right">
                                <!--<li class="dropdown">-->
                                <!--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Dropdown <span class="caret"></span></a>-->
                                <!--<ul class="dropdown-menu" role="menu">-->
                                <!--<li><a href="#">Action</a></li>-->
                                <!--<li><a href="#">Another action</a></li>-->
                                <!--<li><a href="#">Something else here</a></li>-->
                                <!--<li class="divider"></li>-->
                                <!--<li><a href="#">Separated link</a></li>-->
                                <!--</ul>-->
                                <!--</li>-->
                                <!--<li><a href="#doctors">Врачи</a></li>-->
                                <!--<li><a class="disabled" href="#plans">Планы</a></li>-->
                                <li><a href="<?= $data('plan.logout') ?>">Выйти</a></li>
                            </ul>

                            <!-- search -->
                            <!--<form class="navbar-form navbar-right" role="search">-->
                            <!--<div class="form-group">-->
                            <!--<input type="text" class="form-control" placeholder="Search">-->
                            <!--</div>-->
                            <!--<button type="submit" class="btn btn-default">Поиск</button>-->
                            <!--</form>-->
                            <!-- /search -->
                        </div>
                        <!-- /navbar-collapse -->
                    </div>
                </nav>
            </header>
            <!-- /header -->

            <noscript>
                <div class="alert alert-danger text-center lead">Необходимо включить JavaScript для нормальной работы страницы!</div>
            </noscript>

            <?= $view->block('plan_form') ?>
        </div>
    </div>
</div>
<script src="<?= $data('base') ?>assets/js/app.min.js"></script>
</body>
</html>