<?php
/*
Template Name: Add-ons
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
                            <div class="reservation__number reservation__number--active"></div>
                            <h6 class="reservation__text">Select Vehicle/Add-ons</h6>
                        </a>
                        <div class="reservation__content reservation__content--active">
                            <div class="reservation__first">
                                <h5>Type</h5>
                                <p>--</p>
                            </div>
                            <div class="reservation__second">
                                <h5>ADD-ONS</h5>
                                <p>Sirius XM Radio - $3/day, Paddle Board - $75/day, PARK PASS (MUST HAVE TO VISIT MORAN
                                    STATE PARK) - $5/day
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
                    <div class="col-lg-7 col-md-7">
                        <h1 class="archive__tittle">VEHICLE ADD-ONS</h1>
                    </div>
                    <div class="col-lg-5x col-md-5">
                        <div class="search">
                            <div class="search__wrapper">
                                <div class="search__inner">
                                    <input type="text" name="search_text" placeholder="Coupon code" value="" class="search__input">
                                </div>
                                <div class="search__arrow">
                                    <input type="submit" name="search__apply" class="search__arrow__input" value="">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <div class="container">
                <div class="row">
                    <!-- left section -->
                    <section class="col-lg-7 col-md-12 option">
                        <div class="options__wrapper">
                            <div class="option__item clearfix">
                                <div class="option__image">
                                    <img src="<?php echo get_template_directory_uri(); ?>/images/sirius-xm.jpeg" alt="">
                                </div>
                                <div class="option__information ">
                                    <div class="option__information__inner clearfix">
                                        <div class="option__information__content ">
                                            <div class="content__title">
                                                <h4 class="text-uppercase">Sirius XM Radio – $3/day</h4>
                                            </div>
                                            <div class="more-link">
                                                <div>
                                                    <span button_more="more_1" class="more_info">More information</span>
                                                    <i class="fa fa-angle-down"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="btn-more">
                                            <a href="javascript:voild(0);">Add</a>
                                        </div>
                                    </div>
                                    <div more_1 class="more more--active">
                                        As a remote island, AM/FM radio is spotty. Upgrade your ride with uninterrupted
                                        satellite radio!
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="options__wrapper">
                            <div class="option__item clearfix">
                                <div class="option__image">
                                    <img src="<?php echo get_template_directory_uri(); ?>/images/discover.png" alt="">
                                </div>
                                <div class="option__information ">
                                    <div class="option__information__inner clearfix">
                                        <div class="option__information__content ">
                                            <div class="content__title">
                                                <h4 class="text-uppercase">PARK PASS (MUST HAVE TO VISIT MORAN STATE
                                                    PARK) – $5/day</h4>
                                            </div>
                                            <div class="more-link">
                                                <div>
                                                    <span button_more="more_2"class="more_info">More information</span>
                                                    <i class="fa fa-angle-down"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="btn-more">
                                            <a href="javascript:voild(0);">Add</a>
                                        </div>
                                    </div>
                                    <div more_2 class="more more--active">
                                        Save 50% off retail price and the hassle of paperwork at the park entrance! The
                                        Discover Pass gets your vehicle access into Moran State Park, which includes
                                        Cascade Lake, Mountain Lake, and Mount Constitution, among so many other gems.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="options__wrapper">
                            <div class="option__item clearfix">
                                <div class="option__image">
                                    <img src="<?php echo get_template_directory_uri(); ?>/images/paddle-board.jpeg" alt="">
                                </div>
                                <div class="option__information ">
                                    <div class="option__information__inner clearfix">
                                        <div class="option__information__content ">
                                            <div class="content__title">
                                                <h4 class="text-uppercase">Paddle Board – $75/day</h4>
                                            </div>
                                            <div class="more-link">
                                                <div>
                                                    <span button_more="more_3" class="more_info">More information</span>
                                                    <i class="fa fa-angle-down"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="btn-more">
                                            <a href="javascript:voild(0);">Add</a>
                                        </div>
                                    </div>
                                    <div more_3 class="more more--active">
                                        Enjoy your days on the water with our premium, inflatable paddle boards! Current
                                        prices on the island start at $25/hour for paddle boards, so take advantage of
                                        our unbeatable deal. Features:
                                        <ul class="more--info__list">
                                            <li>Triple Layer Stringer Construction</li>
                                            <li>Dual Action High-Pressure Pump and Gauge</li>
                                            <li>Backpack</li>
                                            <li>3-Piece Adjustable Paddle</li>
                                            <li>Triple Dura-Fin Design</li>
                                        </ul>
                                        **Feel free to return the board(s) before the end of your rental and we will
                                        refund you the unused days
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="options__wrapper">
                            <div class="option__item clearfix">
                                <div class="option__image">
                                    <img src="<?php echo get_template_directory_uri(); ?>/images/additonal-driver.jpg" alt="">
                                </div>
                                <div class="option__information ">
                                    <div class="option__information__inner clearfix">
                                        <div class="option__information__content ">
                                            <div class="content__title">
                                                <h4 class="text-uppercase">Additional Driver – $25/day</h4>
                                            </div>
                                            <div class="more-link">
                                                <div>
                                                    <span button_more="more_4" class="more_info">More information</span>
                                                    <i class="fa fa-angle-down"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="btn-more">
                                            <div class="count">
                                                <input type="text"  name="count" value="1">
                                                <div class="count__option">
                                                    <div class="plus">+</div>
                                                    <div class="minus">-</div>
                                                </div>
                                            </div>
                                            <a href="javascript:voild(0);">Add</a>
                                        </div>
                                    </div>
                                    <div more_4 class="more more--active">
                                        Add a driver to your reservation so you have options!
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- End left section -->

                    <!-- right section -->
                    <section class="col-lg-5 col-md-12">
                        <div class="rent-order">
                            <div class="rent-order__tittle">
                                <h3>GOLD PACKAGE</h3>
                                <h6>Hyundai Elantra Ltd.</h6>
                            </div>
                            <img src="<?php echo get_template_directory_uri(); ?>/images/Hyundai_Elantra.jpg" alt="Hyundai_Elantra" class="img-fluid">

                            <div class="rent-order__table">
                                <h3 class="rent-order__table--tittle">Rate</h3>
                                <table>
                                    <thead>
                                    <tr>
                                        <td>QTY</td>
                                        <td>Rate</td>
                                        <td>Subtotal</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>1 Days</td>
                                        <td><span><span>$</span>120.00</span></td>
                                        <td><span><span>$</span>120.00</span></td>
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="2">Rental Charges Rate</td>
                                        <td><span><span>$</span>120.00</span></td>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="rent-order__table">
                                <h3 class="rent-order__table--tittle">Taxes</h3>
                                <table>
                                    <tbody class="rent-order__table--item">
                                    <tr>
                                        <td colspan="2">WA Sales Tax - 6.5%</td>
                                        <td>$7.80</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">San Juan Sales Tax - 1.6%</td>
                                        <td>$1.92</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">WA Rental Car Tax - 5.9%</td>
                                        <td>$7.08</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="divider"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="rent-order__table">
                                <h3 class="rent-order__table--tittle">Taxes</h3>

                                <table>
                                    <tbody>
                                    <tr>
                                        <td colspan="3" class="divider"></td>
                                    </tr>
                                    <tr class="cart-tax tax-security-deposit-for-gold-package-75198">
                                        <td colspan="2">Vehicle Security Deposit for Gold Package - #75198</td>
                                        <td>$100.00</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="more-table-link">
                                            <span button_more="more_5" class="more_info">More information</span><i class="fa fa-angle-down"></i>
                                            <div more_5 class="table__text more more--active">
                                                <p>In the unlikely and unfortunate event that the vehicle is returned
                                                    damaged, excessively dirty, with traces of pets or smoke, or a less
                                                    than full fuel tank, a $100 USD refundable security deposit is being
                                                    added to your reservation total. Upon the return of the vehicle in
                                                    the condition acceptable per Rental Agreement, we will refund the
                                                    full prepaid amount of $100 USD. Additionally, at the time you
                                                    pickup the rental vehicle, we may place a security hold on your
                                                    credit card in the amount of up to $1,000 USD.</p>
                                                <p>Should the vehicle be returned with issues listed in Rental
                                                    Agreement, we will deduct repair, extra cleaning, refueling costs,
                                                    and/or insurance deductible from your security deposit and/or credit
                                                    card security hold amount. We will refund the remaining balance, if
                                                    any, to the card to which it was originally charged.

                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <table class="tent-total">
                                    <tbody>
                                    <tr>
                                        <td class="rent-total__item">Estimated total</td>
                                        <td class="rent-total__item"><span><span>$</span>236.80</span></td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div class="rent-btn">
                                    <a href="#">Continue</a>
                                </div>
                            </div>
                        </div>

                    </section>
                    <!-- END right section -->

                </div>
            </div>
        </section>
        <!-- END Archive -->

    </div>
</div>

<script defer type='text/javascript' src='<?php echo get_template_directory_uri(); ?>/js/add-ons-page.js'></script>

<?php
get_footer();