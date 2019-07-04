<?php

use app\helpers\AssetsHelper;

wp_enqueue_script('googleapis-js', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDip1rjUt_mapLF585PbXrimIHxAs0sMeo&callback', false, '1.0', 'all');
AssetsHelper::enqueue_js('google-maps-js', 'js/google-maps.js', ['jquery']);
AssetsHelper::enqueue_js('moment-js', 'libs/moment.min.js', ['jquery']);
AssetsHelper::enqueue_js('homepage-js', 'js/home-page.js', ['jquery']);

$locations = getLocationsList();

get_header();
?>

    <!--pick up-->
    <section class="wrapper-pick_up">
        <div class="row justify-content-md-center">
            <div class="col-lg-10 col-md-12 col-sm-12 car-rental-form">
                <div class="car-rental-form__inner">
                    <form id="car_rental_form" action="<?php echo home_url('/car-rental'); ?>" class="row" method="post">
                        <input type="hidden" name="pickup_date" value="">
                        <input type="hidden" name="pickup_time" value="">
                        <input type="hidden" name="return_date" value="">
                        <input type="hidden" name="return_time" value="">
                        <input type="hidden" name="body_type_id" value="-1">
                        <input type="hidden" name="fuel_type_id" value="-1">
                        <input type="hidden" name="car_rental_came_from_step1" value="yes">
                        <input type="hidden" name="car_rental_do_search" value="Search">
                        <div class="col-lg-5 col-md-12 car-rental-form__location-section">
                            <div class="car-rental-form__location-pickup">
                                <div class="car-rental-form__location-pickup-selects">
                                    <div class="car-rental-form__location-pickup-select">
                                        <div class="car-rental-form__label">
                                            <label>Pickup <span class="car-rental-form__location-pickup-label__return-text">&amp; return</span></label>
                                            <div class="car-rental-form__location-same car-rental-form__checkbox">
                                                <input type="checkbox" id="car_rental_same_return" checked="checked">
                                                <span>Return at pickup location</span>
                                            </div>
                                        </div>
                                        <div class="car-rental-form__select-box">
                                            <i class="stm-service-icon-pin"></i>
                                            <select name="pickup_location_id" id="car_rental_pickup_location" class="car-rental-form__select select2">
                                                <option value="">Choose location</option>
                                                <?php foreach ($locations as $key => $location): ?>
                                                    <option value="<?php echo $key; ?>"><?php echo $location; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="car-rental-form__location-pickup-select car-rental-form__location-return-select">
                                        <div class="car-rental-form__label">
                                            <label>Return</label>
                                            <div class="car-rental-form__location-same car-rental-form__location-same__return">
                                                x
                                            </div>
                                        </div>
                                        <div class="car-rental-form__select-box">
                                            <i class="stm-service-icon-pin"></i>
                                            <select name="return_location_id" id="car_rental_return_location" class="car-rental-form__select select2">
                                                <option value="">Choose location</option>
                                                <?php foreach ($locations as $key => $location): ?>
                                                    <option value="<?php echo $key; ?>"><?php echo $location; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="car-rental-form__errors"></div>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-12 car-rental-form__datetime-section">
                            <div class="row">
                                <div class="col-md-6 col-6 car-rental-form__datetime-section__pickup">
                                    <div class="car-rental-form__label">
                                        <label>PICKUP TIME</label>
                                    </div>
                                    <div class="car-rental-form__datetime-picker">
                                        <div class="car-rental-form__date-picker">
                                            <i class="stm-icon-date"></i>
                                            <input id="car_rental_pickup_date" class="car-rental-form__date-picker__input" type="text" title="" value="" name="" readonly required>
                                        </div>
                                        <div class="car-rental-form__time-picker">
                                            <input id="car_rental_pickup_time" class="car-rental-form__time-picker__input" type="text" title="" value="" readonly required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-6 car-rental-form__datetime-section__return">
                                    <div class="car-rental-form__label">
                                        <label>RETURN TIME</label>
                                    </div>
                                    <div class="car-rental-form__datetime-picker">
                                        <div class="car-rental-form__date-picker">
                                            <i class="stm-icon-date"></i>
                                            <input id="car_rental_return_date" class="car-rental-form__date-picker__input" type="text" title="" value="" readonly required>
                                        </div>
                                        <div class="car-rental-form__time-picker">
                                            <input id="car_rental_return_time" class="car-rental-form__time-picker__input" type="text" title="" value="" readonly required>
                                        </div>
                                    </div>
                                    <span class="car-rental-form__have-promocode checked"></span>
                                    <div class="car-rental-form__promocode">
                                        <div class="car-rental-form__label">
                                            <label>PROMO CODE</label>
                                        </div>
                                        <input class="car-rental-form__input" type="text" name="coupon_code" title="" value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-12 car-rental-form__button-section">
                            <button class="car-rental-form__button-section__button" type="submit"><span>Continue</span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!--pick up-->

    <!-- Reserve -->
    <section class="reserve">
        <div class="container">
            <div class="row reserve__block">
                <div class="reserve__item col-lg-6 col-md-12">
                    <h1><span>ORCAS ISLAND</span> CAR RENTAL</h1>
                </div>

                <div class="reserve__item col-lg-6 col-md-12">
                    <div class="reserve__right">
                        <p>Don't Wait, Reserve Today</p>
                        <a href="#"><i class="fa fa-location-arrow"></i> Reserve</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- END Reserve -->

    <?php if (have_rows('front_page_packages')): ?>
        <!--our cars-->
        <section class="our-cars">
            <div class="our-cars__parallax">
                <div class="container">
                    <div class="col-md-12">
                        <div class="our-cars__inner">
                            <h4>OUR CARS</h4>
                            <div class="our-cars__products">
                                <?php while (have_rows('front_page_packages')) : the_row();
                                    $image = get_sub_field('image');
                                    $priceSub = get_sub_field('price_sub');
                                ?>
                                    <div class="our-cars__item">
                                        <a href="<?php the_sub_field( 'url' ); ?>" class="our-cars__item__wrapper">
                                            <div class="our-cars__item__top clearfix">
                                                <div class="our-cars__item__left">
                                                    <h3><?php the_sub_field('title'); ?></h3>
                                                    <div class="our-cars__item__title"><?php the_sub_field('car_type'); ?></div>
                                                    <div class="our-cars__prize">
                                                        <mark><?php the_sub_field( 'price_header' ); ?></mark>
                                                        <span class="our-cars__prize__amount">
                                                        <span class="our-cars__prize__woocommerce"><?php the_sub_field( 'price' ); ?></span>
                                                        <?php if ($priceSub): ?>
                                                            <span class="our-cars__prize__day"><?php echo $priceSub; ?></span>
                                                        <?php endif; ?>
                                                    </span>
                                                    </div>
                                                </div>
                                                <?php if (have_rows('features')): ?>
                                                    <div class="our-cars__item__right">
                                                        <?php while (have_rows('features')) : the_row(); ?>
                                                            <div class="our-cars__item__right__info">
                                                                <?php if (get_row_layout() == 'seats') : ?>
                                                                    <i class="stm stm-rental-seats"></i>
                                                                    <span><?php the_sub_field('number'); ?> Seats</span>
                                                                <?php elseif (get_row_layout() == 'bags') : ?>
                                                                    <i class="stm stm-rental-bag"></i>
                                                                    <span><?php the_sub_field('number'); ?> Bags</span>
                                                                <?php elseif (get_row_layout() == 'doors') : ?>
                                                                    <i class="stm stm-rental-door"></i>
                                                                    <span><?php the_sub_field('number'); ?> Doors</span>
                                                                <?php elseif (get_row_layout() == 'air-condition') : ?>
                                                                    <i class="stm stm-rental-ac"></i>
                                                                    <span>A/C</span>
                                                                <?php endif; ?>
                                                            </div>
                                                        <?php endwhile; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <?php if ($image): ?>
                                                <div class="our-cars__item__image">
                                                    <img src="<?php echo $image['sizes']['our-cars-block-image']; ?>" alt="<?php echo $image['alt']; ?>" />
                                                </div>
                                            <?php endif; ?>
                                        </a>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--our cars-->
    <?php endif; ?>

    <?php if ( have_rows( 'front_page_advantages' ) ) : ?>
        <!-- Rent -->
        <section class="rent">
            <div class="container">
                <h1 class="rent__tittle">WHY RENT WITH US</h1>
                <div class="row rent__block">
                    <?php while ( have_rows( 'front_page_advantages' ) ) : the_row(); ?>
                        <div class="rent__item col-md-4 col-sm-12">
                            <div>
                                <div class="rent__item__icon">
                                    <i class="<?php the_sub_field( 'icon' ); ?>"></i>
                                </div>
                            </div>
                            <div class="rent__item__content">
                                <h6><?php the_sub_field( 'title' ); ?></h6>
                                <p><?php the_sub_field( 'text' ); ?></p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
        <!-- END Rent -->
    <?php endif; ?>

    <!-- google-maps -->
    <div id="map"></div>
    <!-- google-maps -->
<?php
get_footer();