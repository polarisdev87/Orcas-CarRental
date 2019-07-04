<?php
/*
Template Name: Date Reservation
*/
?>
<?php get_header();?>

<div class="main-wrapper">


    <div class="wrapper-date-reservation">
        <!-- Reservation  -->
        <section class="reservation">
            <div class="container">
                <h1 class="reservation__tittle">RESERVATION</h1>
                <div class="row">
                    <div class="col-lg-4 col-md-12 reservation__block">
                        <a href="#" class="reservation__header">
                            <div class="reservation__number">1</div>
                            <h6 class="reservation__text">Your Itinerary</h6>
                        </a>
                        <div class="reservation__content">
                            <div class="reservation__first">
                                <h5>PICK UP</h5>
                                <p>--</p>
                                <p>--</p>
                            </div>
                            <div class="reservation__second">
                                <h5>DROP OFF</h5>
                                <p>--</p>
                                <p>--</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 reservation__block">
                        <a href="#" class="reservation__header">
                            <div class="reservation__number reservation__number--active">2</div>
                            <h6 class="reservation__text">Select Vehicle/Add-ons</h6>
                        </a>
                        <div class="reservation__content reservation__content--active">
                            <div class="reservation__first">
                                <h5>Type</h5>
                                <p>--</p>
                            </div>
                            <div class="reservation__second">
                                <h5>ADD-ONS</h5>
                                <p>Sirius XM Radio - $3/day, Paddle Board - $75/day, PARK PASS (MUST HAVE TO VISIT MORAN STATE PARK) - $5/day
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 reservation__block">
                        <a href="#" class="reservation__header">
                            <div class="reservation__number">3</div>
                            <h6 class="reservation__text">Reserve Your Vehicle</h6>
                        </a>
                        <div class="reservation__content">
                            <div class="reservation__first">
                                <h5>YOUR INFORMATION</h5>
                                <p>--</p>
                            </div>
                            <div class="reservation__second">
                                <h5>PAYMENT INFORMATION</h5>
                                <p>Estimated Total - $94.62</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- END Reservation -->

        <!-- Archive -->
        <section class="archive">
            <div class="container">
                <div class="row archive__tittle__header">
                    <div class="col-lg-6 col-md-12">
                        <h1 class="archive__tittle">SELECT VEHICLE/ADD-ONS</h1>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <!-- todo Koly: Select -->
                    </div>
                </div>
            </div>

            <div class="archive__block">
                <div class="container">
                    <div class="row">
                        <div class="col-12  archive__item archive__item--active">
                            <div class="archive__img">
                                <img src="<?php echo get_template_directory_uri(); ?>/images/Hyundai_Elantra.jpg" alt="Hyundai Elantra" class="img-fluid">
                            </div>
                            <div class="archive__info">
                                    <div class="archive__header">
                                        <h3>GOLD PACKAGE</h3>
                                        <h6>Hyundai Elantra Ltd.</h6>
                                    </div>
                                    <div class="archive__main">
                                        <div class="archive__main--item">
                                            <i class="stm-rental-seats"></i>
                                            <span>5 Seats</span>
                                        </div>
                                        <div class="archive__main--item">
                                            <i class="stm-rental-bag"></i>
                                            <span>4 bags</span>
                                        </div>
                                        <div class="archive__main--item">
                                            <i class="stm-rental-door"></i>
                                            <span>4 doors</span>
                                        </div>
                                        <div class="archive__main--item">
                                            <i class="stm-rental-ac"></i>
                                            <span>a/c</span>
                                        </div>
                                    </div>
                               <div class="more-link ">
                                   <div>
                                       <span class="more_info">More information</span>
                                       <i class="fa fa-angle-down"></i>
                                   </div>
                               </div>
                                <div class="more more--active">
                                    <div class="lists-inline">
                                        <p>Our Elantra Limiteds are premium models with luxury features, like:</p>
                                        <ul>
                                            <li>Leather interior with heated front seats</li>
                                            <li>Leather-wrapped steering wheel and shift knob</li>
                                            <li>Proximity Key entry with push-button start</li>
                                            <li>Power driver seat with lumbar support</li>
                                            <li>7-inch display audio with Android Auto™ and Apple CarPlay™</li>
                                            <li>Blue Link® Technology Package</li>
                                            <li>Rearview camera with dynamic guidelines</li>
                                            <li>Automatic headlight control</li>
                                            <li>Blind Spot Detection with Rear Cross-traffic Alert</li>
                                            <li>Hands-free smart trunk</li>
                                            <li>Side mirror turn-signal indicators</li>
                                            <li>Door handle approach lights</li>
                                            <li>Chrome beltline molding &amp; grille</li>
                                            <li>Auto-dimming rearview mirror with HomeLink®</li>
                                            <li>Dual charging USB ports</li>
                                            <li>Dual automatic temperature control and Auto Defogging System</li>
                                            <li>17-inch alloy wheels</li>
                                        </ul>
                                    </div>
                                </div>
                                </div>
                            <div class="archive__price">
                                    <h4 class="archive__price--total">$150.00/Total</h4>
                                    <h6 class="archive__price--day">$150.00/Day</h6>
                                    <a href="#" class="archive__price--btn">pay now</a>
                                </div>
                         </div>
                        <div class="col-12  archive__item">
                            <div class="archive__img">
                                <img src="<?php echo get_template_directory_uri(); ?>/images/Hyundai_Elantra.jpg" alt="Hyundai Elantra" class="img-fluid">
                            </div>
                            <div class="archive__info">
                                    <div class="archive__header">
                                        <h3>GOLD PACKAGE</h3>
                                        <h6>Hyundai Elantra Ltd.</h6>
                                    </div>
                                    <div class="archive__main">
                                        <div class="archive__main--item">
                                            <i class="stm-rental-seats"></i>
                                            <span>5 Seats</span>
                                        </div>
                                        <div class="archive__main--item">
                                            <i class="stm-rental-bag"></i>
                                            <span>4 bags</span>
                                        </div>
                                        <div class="archive__main--item">
                                            <i class="stm-rental-door"></i>
                                            <span>4 doors</span>
                                        </div>
                                        <div class="archive__main--item">
                                            <i class="stm-rental-ac"></i>
                                            <span>a/c</span>
                                        </div>
                                    </div>
                                </div>
                            <div class="archive__price">
                                    <h4 class="archive__price--total">$150.00/Total</h4>
                                    <h6 class="archive__price--day">$150.00/Day</h6>
                                    <a href="#" class="archive__price--btn">pay now</a>
                                </div>
                         </div>
                        <div class="col-12  archive__item">
                            <div class="archive__img">
                                <img src="<?php echo get_template_directory_uri(); ?>/images/Hyundai_Elantra.jpg" alt="Hyundai Elantra" class="img-fluid">
                            </div>
                            <div class="archive__info">
                                    <div class="archive__header">
                                        <h3>GOLD PACKAGE</h3>
                                        <h6>Hyundai Elantra Ltd.</h6>
                                    </div>
                                    <div class="archive__main">
                                        <div class="archive__main--item">
                                            <i class="stm-rental-seats"></i>
                                            <span>5 Seats</span>
                                        </div>
                                        <div class="archive__main--item">
                                            <i class="stm-rental-bag"></i>
                                            <span>4 bags</span>
                                        </div>
                                        <div class="archive__main--item">
                                            <i class="stm-rental-door"></i>
                                            <span>4 doors</span>
                                        </div>
                                        <div class="archive__main--item">
                                            <i class="stm-rental-ac"></i>
                                            <span>a/c</span>
                                        </div>
                                    </div>
                                </div>
                            <div class="archive__price">
                                    <h4 class="archive__price--total">$150.00/Total</h4>
                                    <h6 class="archive__price--day">$150.00/Day</h6>
                                    <a href="#" class="archive__price--btn">pay now</a>
                                </div>
                         </div>
                     </div>
                </div>
             </div>
        </section>
        <!-- END Archive -->

    </div>

</div>
<?php
get_footer();
