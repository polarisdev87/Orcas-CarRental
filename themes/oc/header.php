<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php wp_head(); ?>

    <!-- Icon -->
    <?php \app\helpers\TemplatesHelper::show_favicon(); ?>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <meta name="theme-color" content="#ffffff">

</head>
<body class="home stm-template-car_rental">
<!-- Header -->
<header class="header" id="header">
    <div class="container">
        <div class="row">
            <div class="col-md-2 header__logo">
                <a href="<?php echo get_site_url(); ?>/home"><img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="Logo"></a>
            </div>
            <!-- Menu desktop -->
            <?php wp_nav_menu([
                'container' => 'nav',
                'container_class' => 'col-md-10 header__menu--desktop',
                'menu_class' => 'header__menu',
                'theme_location' => 'main_menu',
            ]); ?>
            <!-- END Menu desktop -->

            <!-- Menu mobile -->
            <nav role="navigation" class="navigation-mobile">
                <div id="menuToggle">
                    <input type="checkbox"/>

                    <span></span>
                    <span></span>
                    <span></span>

                    <?php wp_nav_menu([
                        'container' => '',
                        'menu_class' => 'menu-mobile__nav',
                        'menu_id' => 'menu',
                        'theme_location' => 'main_menu',
                    ]); ?>
                </div>
            </nav>
            <!-- END Menu mobile -->

        </div>
    </div>
</header>
<!--<div class="background"></div>-->
<div class="rental-overlay"></div>
<!-- END Header -->