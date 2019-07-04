<?php
/*
Template Name: Checkout
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
                            <div class="reservation__number reservation__number--active"></div>
                            <h6 class="reservation__text">Your Itinerary</h6>
                        </a>
                        <div class="reservation__content">
                            <div class="reservation__first">
                                <h5>PICK UP</h5>
                                <p>Rosario Resort, Rosario Rd, Eastsound, WA 98245</p>
                                <p>2018/08/25 03:00</p>
                            </div>
                            <div class="reservation__second">
                                <h5>DROP OFF</h5>
                                <p>Rosario Resort, Rosario Rd, Eastsound, WA 98245</p>
                                <p>2018/08/26 02:00</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 reservation__block">
                        <a href="#" class="reservation__header">
                            <div class="reservation__number reservation__number--active"></div>
                            <h6 class="reservation__text">Select Vehicle/Add-ons</h6>
                        </a>
                        <div class="reservation__content ">
                            <div class="reservation__first">
                                <h5>Gold Package Type</h5>
                                <p>Hyundai Elantra Ltd.</p>
                            </div>
                            <div class="reservation__second">
                                <h5>AD-ODNS</h5>
                                <p>Paddle Board - $75/day, PARK PASS (MUST HAVE TO VISIT MORAN STATE PARK) - $5/day,
                                    Sirius XM Radio - $3/day
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 reservation__block">
                        <a href="#" class="reservation__header">
                            <div class="reservation__number">3</div>
                            <h6 class="reservation__text">Reserve Your Vehicle</h6>
                        </a>
                        <div class="reservation__content reservation__content--active">
                            <div class="reservation__first">
                                <h5>YOUR INFORMATION</h5>
                                <p>--</p>
                            </div>
                            <div class="reservation__second">
                                <h5>PAYMENT INFORMATION</h5>
                                <p>Estimated Total - $331.42 </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- END Reservation -->

        <!-- Begin Checkout -->
        <section class="archive">
            <div class="container">
                <div class="row archive__tittle__header">
                    <div class="col-lg-7 col-md-7">
                        <h1 class="archive__tittle">Checkout</h1>
                    </div>
                    <div class="col-lg-5x col-md-5">
                        <div class="search">
                            <div class="search__wrapper">
                                <div class="search__inner">
                                    <input type="text" name="search_text" placeholder="Coupon code" value=""
                                           class="search__input">
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

                    <!-- right section -->
                    <section class="col-lg-5 col-md-12">
                        <div class="rent-order">
                            <div class="rent-order__tittle">
                                <h3>GOLD PACKAGE</h3>
                                <h6>Hyundai Elantra Ltd.</h6>
                            </div>
                            <img src="<?php echo get_template_directory_uri(); ?>/images/Hyundai_Elantra.jpg" alt="Hyundai_Elantra" class="img-fluid">
                            <!-- todo Koly: more information -->
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
                                <h3 class="rent-order__table--tittle">Add-ons</h3>
                                <table class="checkout__table">
                                    <thead>
                                    <tr>
                                        <td>QTY</td>
                                        <td>Rate</td>
                                        <td>Subtotal</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>1 x Paddle Board - $75/day for 1 day(s)</td>
                                        <td><span><span>$</span>75.00</span></td>
                                        <td><span><span>$</span>75.00</span></td>
                                    </tr>
                                    <tr>
                                        <td>1 x PARK PASS (MUST HAVE TO VISIT MORAN STATE PARK) - $5/day for 1 day(s)
                                        </td>
                                        <td><span><span>$</span>5.00</span></td>
                                        <td><span><span>$</span>5.00</span></td>
                                    </tr>
                                    <tr>
                                        <td>1 x Sirius XM Radio - $3/day for 1 day(s)</td>
                                        <td><span><span>$</span>3.00</span></td>
                                        <td><span><span>$</span>3.00</span></td>
                                    </tr>
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <td colspan="2">Add-ons Charges Rate</td>
                                        <td><span><span>$</span>83.00</span></td>
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
                                        <td>$13.20</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">San Juan Sales Tax - 1.6%</td>
                                        <td>$3.25</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">WA Rental Car Tax - 5.9%</td>
                                        <td>$11.98</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="divider"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="rent-order__table">
                                <h3 class="rent-order__table--tittle">Refundable Security Deposit</h3>

                                <table>
                                    <tbody>
                                    <tr>
                                        <td colspan="3" class="divider"></td>
                                    </tr>
                                    <tr class="cart-tax tax-security-deposit-for-gold-package-75198">
                                        <td colspan="2">Vehicle Security Deposit for Gold Package - #98101</td>
                                        <td>$100.00</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="more-table-link">
                                            <span button_more="more_1">More information</span><i class="fa fa-angle-down"></i>
                                            <div more_1 class="table__text more--active">
                                                <p>In the unlikely and unfortunate event that the vehicle is returned
                                                    damaged, excessively dirty, with traces of pets or smoke, or a less
                                                    than full fuel tank, a $100 USD refundable security deposit is being
                                                    added to your reservation total. Upon the return of the vehicle in
                                                    the condition acceptable per Rental Agreement, we will refund the
                                                    full prepaid amount of $100 USD. Additionally, at the time you
                                                    pickup the rental vehicle, we may place a security hold on your
                                                    credit card in the amount of up to $1,000 USD. </p>
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
                                        <td class="rent-total__item"><span><span>$</span>331.42</span></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="checkout">
                            <div class="checkout__header">
                                <h3>Rate Terms</h3>
                                <p>The following terms apply at the time of reserving a vehicle with OrcasCars.com </p>
                            </div>
                            <div class="checkout__item">
                                <h4>Cancellation Policy</h4>
                                <span><a button_more="more_2">More information</a><i class="fa fa-angle-down"></i></span>
                                <div more_2 class="checkout__block-more more--active">
                                    <p>Your rental rate is calculated based on the information provided at the time of
                                        booking. Any change to a reservation will be recalculated based on the
                                        availability and prices at the time the change is made. This may be greater or
                                        lesser than the price originally booked. This applies to changes made to: </p>
                                    <p>
                                        Drop off location<br>
                                        Pick up date and time<br>
                                        Drop off time and date<br>
                                        Vehicle package<br>
                                        All other vehicle add-ons<br>
                                    </p>
                                    <p>Any changes to a reservation must be done by emailing hello@orcascars.com. Please
                                        include your reservation number in the subject of your email.</p>
                                    <p>If a reservation is canceled 24 hours before the Pick Up Time, we will refund
                                        your full prepaid amount. A $50 cancellation fee will also be assessed. </p>
                                    <p>If a reservation is canceled less than 24 hours before the Pick Up Time, we will
                                        refund your full prepaid amount, less a full day’s rental rate. </p>
                                    <p>
                                        If you fail to cancel your reservation prior to the Pick Up Time and do not
                                        collect the vehicle on the Pick Up Date, or if you fail to comply with the Terms
                                        of the Rental Agreement, no portion of the prepaid amount will be refunded.
                                    </p>
                                    <p>Refunds will go back on the card that the original reservation was made on. </p>
                                </div>
                            </div>
                            <div class="checkout__item">
                                <h4>Changes At Time Of Pick Up Or Return</h4>
                                <span><a button_more="more_3">More information</a><i class="fa fa-angle-down"></i></span>
                                <div more_3 class="checkout__block-more more--active">
                                    <p>If at the time of vehicle pick up you wish to rent a different car or rent for a
                                        longer period, you may do so subject to availability, on payment of additional
                                        charges. Such charges may be at a higher rate than those previously quoted. </p>
                                    <p>1 Day minimum rental required. </p>
                                </div>
                            </div>
                            <div class="checkout__item">
                                <h4>Rental Requirements And Qualifications</h4>
                                <span><a button_more="more_4">More information</a><i class="fa fa-angle-down"></i></span>
                                <div more_4 class="checkout__block-more more--active">
                                    <p>Unfortunately we are not permitted to rent to those under the age of 25. </p>
                                    <p>1 Day minimum rental required. </p>
                                </div>
                            </div>
                        </div>


                    </section>
                    <!-- END right section -->
                    <!-- left section -->
                    <section class="col-lg-7 col-md-12">
                        <div class="billing-details">
                            <h4 class="text-uppercase">Billing details</h4>
                            <div class="billing-details__wrapper">
                                <p class="privacy_policy">
                                    <label  class="checbox">
                                <span class="checker">
                                    <span class="bg_image checked" >
                                            <input type="checkbox" id="confirm">
                                    </span>
                                </span>

                                    I confirm that I am at least 25 years old and therefore qualify to rent a vehicle
                                    from OrcasCars.com.
                                    <abbr class="required" title="required">*</abbr>
                                    </label>
                                </p>
                            </div>
                            <div class="fields__wrapper">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="billing_first_name">First name
                                                <abbr class="required" title="required">*</abbr>
                                            </label>
                                            <input type="text" id="billing_first_name" value="" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="billing_last_name">Last name
                                                <abbr class="required" title="required">*</abbr>
                                            </label>
                                            <input type="text" id="billing_last_name" value="" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="billing_driver_license">Driver license
                                                <abbr class="required" title="required">*</abbr>
                                            </label>
                                            <input type="text" id="billing_driver_license" value="" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="billing_driver_insurance">Insurance Policy Number
                                                <abbr class="required" title="required">*</abbr>
                                            </label>
                                            <input type="text" id="billing_driver_insurance" value="" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="country">Country
                                                <abbr class="required" title="required">*</abbr>
                                            </label>
                                            <input type="text" id="country" value="" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="billing_address">Street address
                                                <abbr class="required" title="required">*</abbr>
                                            </label>
                                            <input type="text" id="billing_address" name="billing_address" value="" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="billing_city">Town / City
                                                <abbr class="required" title="required">*</abbr>
                                            </label>
                                            <input type="text" id="billing_city" name="billing_city" value="" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="billing_state">State / County
                                                <abbr class="required" title="required">*</abbr>
                                            </label>
                                            <input type="text" id="billing_state" name="billing_state" value="" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="billing_postcode">Postcode / ZIP
                                                <abbr class="required" title="required">*</abbr>
                                            </label>
                                            <input type="text" name="billing_postcode" id="billing_postcode" value="" placeholder="">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="billing_phone">Phone
                                                <abbr class="required" title="required">*</abbr>
                                            </label>
                                            <input type="tel" name="billing_phone" id="billing_phone" value="" placeholder="" autocomplete="tel">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="billing_email">Email address
                                                <abbr class="required" title="required">*</abbr>
                                            </label>
                                            <input type="email" name="billing_email" id="billing_email" value="" placeholder="" autocomplete="email username">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="payment">
                            <h3>payment</h3>
                            <p>Your personal data will be used to process your order, support your experience throughout
                                this website, and for other purposes described in our
                                <a href="#" target="_blank">privacy policy</a>.</p>
                            <label for="">
                                <input type="checkbox">
                                <span>I have read and agree to the website <a href="#">terms and conditions</a> <span
                                        class="required">*</span></span>
                            </label>

                            <input type="button" value="Place order" class="payment__btn">

                        </div>
                    </section>
                    <!-- End left section -->

                </div>
            </div>
        </section>
        <!-- END Checkout -->

    </div>
</div>

<?php
get_footer();