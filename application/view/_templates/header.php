<!doctype html>
<html lang="<?php echo Session::get("user_lang"); ?>">
<head>
    <!-- META -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo Text::get("app_title"); ?></title>
    <link rel="shortcut icon" href="<?php echo Config::get('URL'); ?>img/icon.png">

    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>css/bootstrap.custom.css" />
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>css/style.css" />
    <link rel="stylesheet" href="<?php echo Config::get('URL'); ?>css/rangeslider.css" />

    <!-- Google Analytics -->
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
      ga('create', 'UA-62841086-1', 'auto');
      ga('send', 'pageview');
    </script>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span> 
                </button>
                <a class="navbar-brand" href="<?php echo Config::get('URL'); ?>">
                        <span><img alt="" src="<?php echo Config::get('URL'); ?>img/icon.png" style="width:20px;height:20px;margin-right:10px;margin-bottom:5px;"></span>
                        <span class="text-uppercase"><?php echo Text::get("app_title"); ?></span>
                </a>
            </div>

            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav">
                    <li <?php if (View::checkForActiveController($filename, "index")) { echo ' class="active" '; } ?> >
                <a href="<?php echo Config::get('URL'); ?>index/index">Index</a>
            </li>
            <li <?php if (View::checkForActiveController($filename, "overview")) { echo ' class="active" '; } ?> >
                <a href="<?php echo Config::get('URL'); ?>profile/index">Profiles</a>
            </li>
            <?php if (Session::userIsLoggedIn()) { ?>
                <li <?php if (View::checkForActiveController($filename, "uploads")) { echo ' class="active" '; } ?> >
                    <a href="<?php echo Config::get('URL'); ?>uploader/index">
                        <span class="glyphicon glyphicon-cloud-upload" aria-hidden="true"></span> Uploads
                    </a>
                </li>
                <li <?php if (View::checkForActiveController($filename, "custom")) { echo ' class="active" '; } ?> >
                    <a href="<?php echo Config::get('URL'); ?>custom/index">
                        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Make
                    </a>
                </li>
                <li <?php if (View::checkForActiveController($filename, "profile")) { echo ' class="active" '; } ?> >
                    <a href="<?php echo Config::get('URL'); ?>profile/infographics">
                        <span class="glyphicon glyphicon-picture" aria-hidden="true"></span> Your Infographics
                    </a>
                </li>
                <li class='dropdown <?php if (View::checkForActiveController($filename, "login")) { echo ' active'; } ?>'>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <span class="glyphicon glyphicon-user" aria-hidden="true"></span> Account <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="<?php echo Config::get('URL'); ?>login/showProfile">Profile</a>
                        </li>
                        <li>
                            <a href="<?php echo Config::get('URL'); ?>login/changeUserRole">Change account type</a>
                        </li>
                        <li>
                            <a href="<?php echo Config::get('URL'); ?>login/editusername">Change Username</a>
                        </li>
                        <li>
                            <a href="<?php echo Config::get('URL'); ?>login/edituseremail">Change Email</a>
                        </li>
                        <li role="separator" class="divider"></li>
                        <li>
                            <a href="<?php echo Config::get('URL'); ?>login/logout">Logout</a>
                        </li>
                    </ul>
                </li>
                <li <?php if (View::checkForActiveController($filename, "batch")) { echo ' class="active" '; } ?> >
                    <a href="<?php echo Config::get('URL'); ?>batch/upload">
                        <span class="glyphicon glyphicon-fast-forward" aria-hidden="true"></span> Batch Infographic
                    </a>
                </li>
                
            <?php } else { ?>
                <!-- for not logged in users -->
                <li <?php if (View::checkForActiveControllerAndAction($filename, "login/index")) { echo ' class="active" '; } ?> >
                    <a href="<?php echo Config::get('URL'); ?>login/index">Login</a>
                </li>
                <li <?php if (View::checkForActiveControllerAndAction($filename, "login/register")) { echo ' class="active" '; } ?> >
                    <a href="<?php echo Config::get('URL'); ?>login/register">Register</a>
                </li>
            <?php } ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- wrapper, to center website -->
    <div class="wrapper">
