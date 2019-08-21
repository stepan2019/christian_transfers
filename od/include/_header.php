<?php include_once("dbconfig.php");?><!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?= $title ?></title>
    <meta name="description" content="<?= $description ?>">
    <meta name="keywords" content="Open Data, Powered by the Transport for London Journey Planner API"/>

    <meta name="robots" content="index, follow, all"/>
    <base href="https://www.christiantransfers.eu/od/index.php">
    <link rel='shortcut icon' href='favicon.ico' type='image/x-icon'/>

    <link href="https://fonts.googleapis.com/css?family=Raleway:400,500,600,700|Montserrat:400,700&amp;subset=latin-ext"
          rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
          integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/od/css/style.css?v=<?= time() ?>"/>
    <link rel="stylesheet" href="/od/css/theme-pink.css"/>
    <link rel="stylesheet" href="/od/css/animate.css"/>
    <link rel="stylesheet" href="/od/css/icons.css"/>
    <link rel="stylesheet" href="/od/css/custom-style.css?v=<?= time() ?>">
    <script src="https://use.fontawesome.com/e808bf9397.js"></script>
    <script type="text/javascript" src="js/banners.min.js"></script> <!-- Banner rotator top start & end -->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-16218826-26"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());
        gtag('config', 'UA-16218826-26');
    </script>
    <!-- Global site tag = Google Analytics END-->
</head>

<body>

<?php include('_top_nav.php') ?>


<!-- Main -->
<main class="main" role="main">

    <?php include('_breadcrumb.php') ?>


    <div class="wrap">
        <div class="row">

            <?php include('_sidebar.php');//čćžšđ ?>


            <!--- Content -->
            <div class="full-width content">
                <!-- Post -->
                <article class="single hentry">
                    <div class="entry-content">





