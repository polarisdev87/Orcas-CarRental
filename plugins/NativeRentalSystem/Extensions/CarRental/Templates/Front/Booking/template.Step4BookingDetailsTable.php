<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
// Scripts
wp_enqueue_script('jquery');
wp_enqueue_script('jquery-validate');
wp_enqueue_script('car-rental-frontend');

// Styles
wp_enqueue_style('car-rental-frontend');
if($newBooking == TRUE && $objSettings->getSetting('conf_universal_analytics_enhanced_ecommerce') == 1):
    include('partial.Step4.EnhancedEcommerce.php');
endif;
?>
<div class="car-rental-wrapper car-rental-booking-details">
<h2 class="car-rental-page-title"><?php print($pageLabel); ?></h2>
<table cellpadding="4" cellspacing="1" border="0" width="100%" bgcolor="#FFFFFF">
<tbody>
<?php include('partial.BookingSummary.php'); ?>
</tbody>
</table>

<?php if($newBooking && $boolEmailRequired): ?>
    <h2 class="search-label top-padded"><?php print($objLang->getText('NRS_EXISTING_CUSTOMER_DETAILS_TEXT')); ?></h2>
    <div class="<?php print($boolBirthdateRequired ? 'form-row-wide': 'form-row'); ?>">
        <div class="email-search">
            <input type="text" name="search_email_address" class="search-email-address" value="<?php print($objLang->getText('NRS_EMAIL_ADDRESS_TEXT')); ?>"
                   onfocus="if(this.value == '<?php print($objLang->getText('NRS_EMAIL_ADDRESS_TEXT')); ?>') {this.value=''}"
                   onblur="if(this.value == ''){this.value ='<?php print($objLang->getText('NRS_EMAIL_ADDRESS_TEXT')); ?>'}"
            />
        </div>
        <?php if($boolBirthdateRequired): ?>
            <div class="birth-search">
                <select name="search_birth_year" class="search-birth-year">
                    <?php print($birthYearSearchDropDownOptions); ?>
                </select>
            </div>
        <?php endif; ?>
        <div class="customer-lookup-button">
            <button type="submit" name="customer_lookup" class="customer-lookup"><?php print($objLang->getText('NRS_FETCH_CUSTOMER_DETAILS_TEXT')); ?></button>
        </div>
    </div>
    <div class="ajax-loader">&nbsp;</div>
    <div class="clear">&nbsp;</div>
    <h2 class="search-label"><?php print($objLang->getText('NRS_OR_ENTER_NEW_DETAILS_TEXT')); ?></h2>
<?php else: ?>
    <h2 class="search-label top-padded"><?php print($objLang->getText('NRS_EXISTING_CUSTOMER_DETAILS_TEXT')); ?></h2>
<?php endif; ?>
<form name="customer_form" method="post" action="" class="car-rental-customer-form">
    <?php if($titleVisible): ?>
    <div class="form-row">
        <div class="customer-data-label">
            <strong><?php print($objLang->getText('NRS_TITLE_TEXT')); ?>:<span class="dynamic-text-item<?php print($titleRequired); ?>">*</span></strong>
        </div>
        <div class="customer-data-input">
            <select name="title" class="title<?php print($titleRequired); ?>">
                <?php print($titleDropDownOptions); ?>
            </select>
        </div>
    </div>
    <?php endif; ?>
    <?php if($firstNameVisible): ?>
    <div class="form-row">
        <div class="customer-data-label">
            <strong><?php print($objLang->getText('NRS_FIRST_NAME_TEXT')); ?>:<span class="dynamic-text-item<?php print($firstNameRequired); ?>">*</span></strong>
        </div>
        <div class="customer-data-input">
            <input type="text" name="first_name" value="<?php print($firstName); ?>" class="first-name<?php print($firstNameRequired); ?>" />
        </div>
    </div>
    <?php endif; ?>
    <?php if($lastNameVisible): ?>
    <div class="form-row">
        <div class="customer-data-label">
            <strong><?php print($objLang->getText('NRS_LAST_NAME_TEXT')); ?>:<span class="dynamic-text-item<?php print($lastNameRequired); ?>">*</span></strong>
        </div>
        <div class="customer-data-input">
            <input type="text" name="last_name" value="<?php print($lastName); ?>" class="last-name<?php print($lastNameRequired); ?>" />
        </div>
    </div>
    <?php endif; ?>
    <?php if($birthdateVisible): ?>
    <div class="form-row">
        <div class="customer-data-label">
            <strong><?php print($objLang->getText('NRS_DATE_OF_BIRTH_TEXT')); ?>:<span class="dynamic-text-item<?php print($birthdateRequired); ?>">*</span></strong>
        </div>
        <div class="customer-data-input customer-birthday-select">
            <select name="birth_year" class="birth-year<?php print($birthdateRequired); ?>"><?php print($birthYearDropDownOptions); ?></select>
            <select name="birth_month" class="birth-month<?php print($birthdateRequired); ?>"><?php print($birthMonthDropDownOptions); ?></select>
            <select name="birth_day" class="birth-day<?php print($birthdateRequired); ?>"><?php print($birthDayDropDownOptions); ?></select>
        </div>
    </div>
    <?php endif; ?>
    <?php if($streetAddressVisible): ?>
    <div class="form-row">
        <div class="customer-data-label">
            <strong><?php print($objLang->getText('NRS_ADDRESS_TEXT')); ?>:<span class="dynamic-text-item<?php print($streetAddressRequired); ?>">*</span></strong>
        </div>
        <div class="customer-data-input">
            <input type="text" name="street_address" value="<?php print($streetAddress); ?>" class="street-address<?php print($streetAddressRequired); ?>" />
        </div>
    </div>
    <?php endif; ?>
    <?php if($cityVisible): ?>
    <div class="form-row">
        <div class="customer-data-label">
            <strong><?php print($objLang->getText('NRS_CITY_TEXT')); ?>:<span class="dynamic-text-item<?php print($cityRequired); ?>">*</span></strong>
        </div>
        <div class="customer-data-input">
            <input type="text" name="city" value="<?php print($city); ?>" class="city<?php print($cityRequired); ?>" />
        </div>
    </div>
    <?php endif; ?>
    <?php if($stateVisible): ?>
    <div class="form-row">
        <div class="customer-data-label">
            <strong><?php print($objLang->getText('NRS_STATE_TEXT')); ?>:<span class="dynamic-text-item<?php print($stateRequired); ?>">*</span></strong>
        </div>
        <div class="customer-data-input">
            <input type="text" name="state" value="<?php print($state); ?>" class="state<?php print($stateRequired); ?>" />
        </div>
    </div>
    <?php endif; ?>
    <?php if($zipCodeVisible): ?>
    <div class="form-row">
        <div class="customer-data-label">
          <strong><?php print($objLang->getText('NRS_ZIP_CODE_TEXT')); ?>:<span class="dynamic-text-item<?php print($zipCodeRequired); ?>">*</span></strong>
        </div>
        <div class="customer-data-input">
            <input type="text" name="zip_code" value="<?php print($zipCode); ?>" class="zip-code<?php print($zipCodeRequired); ?>" />
        </div>
    </div>
    <?php endif; ?>
    <?php if($countryVisible): ?>
    <div class="form-row">
        <div class="customer-data-label">
            <strong><?php print($objLang->getText('NRS_COUNTRY_TEXT')); ?>:<span class="dynamic-text-item<?php print($countryRequired); ?>">*</span></strong>
        </div>
        <div class="customer-data-input">
            <input type="text" name="country" value="<?php print($country); ?>" class="country<?php print($countryRequired); ?>" />
        </div>
    </div>
    <?php endif; ?>
    <?php if($phoneVisible): ?>
    <div class="form-row">
        <div class="customer-data-label">
            <strong><?php print($objLang->getText('NRS_PHONE_TEXT')); ?>:<span class="dynamic-text-item<?php print($phoneRequired); ?>">*</span></strong>
        </div>
        <div class="customer-data-input">
            <input type="text" name="phone" value="<?php print($phone); ?>" class="phone<?php print($phoneRequired); ?>" />
        </div>
    </div>
    <?php endif; ?>
    <?php if($emailVisible): ?>
    <div class="form-row">
        <div class="customer-data-label">
            <strong><?php print($objLang->getText('NRS_EMAIL_TEXT')); ?>:<span class="dynamic-text-item<?php print($emailRequired); ?>">*</span></strong>
        </div>
        <div class="customer-data-input">
            <input type="text" name="email" value="<?php print($email); ?>" class="email<?php print($emailRequired); ?> email" />
        </div>
    </div>
    <?php endif; ?>
    <?php if($commentsVisible): ?>
    <div class="form-row">
        <div class="customer-data-label">
            <strong><?php print($objLang->getText('NRS_ADDITIONAL_COMMENTS_TEXT')); ?>:<span class="dynamic-text-item<?php print($commentsRequired); ?>">*</span></strong>
        </div>
        <div class="customer-data-input customer-textarea">
            <textarea name="comments" class="comments<?php print($commentsRequired); ?>"><?php print($comments); ?></textarea>
        </div>
    </div>
    <?php endif; ?>
	
	<?php if($prepaymentsEnabled && sizeof($paymentMethods) > 0): ?>
    <div class="form-row">
        <div class="customer-data-label">
            <strong><?php print($objLang->getText('NRS_PAY_BY_SHORT_TEXT')); ?>:<span class="item-required">*</span></strong>
        </div>
        <div class="customer-data-input">
            <?php
            if($newBooking == FALSE):
                // only 1 payment method
                if($selectedPaymentMethodName != ""):
                    print('<div class="payment-method-name">'.$selectedPaymentMethodName.'</div>');
                    if($selectedPaymentMethodDescription != ""):
                        print('<div class="payment-method-description">'.$selectedPaymentMethodDescription.'</div>');
                    endif;
                endif;
            else:
                if(sizeof($paymentMethods) > 1):
                    foreach($paymentMethods AS $paymentMethod):
                        print('<div class="payment-method-name">');
                        print('<input type="radio" name="payment_method_id" value="'.$paymentMethod['payment_method_id'].'"'.$paymentMethod['print_checked'].' class="required" />');
                        print($paymentMethod['payment_method_name']);
                        print('</div>');
                        if($paymentMethod['payment_method_description_html'] != ""):
                            print('<div class="padded-payment-method-description">'.$paymentMethod['payment_method_description_html'].'</div>');
                        endif;
                    endforeach;
                elseif(sizeof($paymentMethods) == 1):
                    // only 1 payment method
                    foreach($paymentMethods AS $paymentMethod):
                        print('<div class="payment-method-name">');
                        print('<input type="hidden" name="payment_method_code" value="'.$paymentMethod['payment_method_code'].'" />');
                        print($paymentMethod['payment_method_name']);
                        print('</div>');
                        if($paymentMethod['payment_method_description_html'] != ""):
                            print('<div class="payment-method-description">'.$paymentMethod['payment_method_description_html'].'</div>');
                        endif;
                    endforeach;
                endif;
            endif;
            ?>
            <label class="error" generated="true" for="payment_method_code" style="display:none;"><?php print($objLang->getText('NRS_FIELD_REQUIRED_TEXT')); ?>.</label>
        </div>
    </div>
    <?php endif; ?>
	
	  <?php if($newBooking && $showReCaptcha): ?>
        <div class="form-row">
            <div class="customer-data-label">&nbsp;</div>
            <div class="customer-data-input">
                <div class="g-recaptcha" data-sitekey="<?php print($reCaptchaSiteKey); ?>"></div>
                <script type="text/javascript"
                    src="https://www.google.com/recaptcha/api.js?hl=<?php print($reCaptchaLanguage) ?>">
                </script>
            </div>
        </div>
    <?php endif; ?>

    <?php if($objSettings->getSetting('conf_terms_and_conditions_page_id') != "" && $objSettings->getSetting('conf_terms_and_conditions_page_id') != 0): ?>
        <div class="form-row">
            <div class="customer-data-label">&nbsp;</div>
            <div class="customer-data-input">
                <?php
                if($newBooking == FALSE):
                    print('<input type="checkbox" name="terms_and_conditions" value="" checked="checked" style="width:15px !important" class="terms-and-conditions required" />');
                    print('&nbsp; <a href="'.get_permalink($objSettings->getSetting('conf_terms_and_conditions_page_id')).'" target="_blank">'.$objLang->getText('NRS_I_AGREE_WITH_TERMS_AND_CONDITIONS_TEXT').'</a>');
                else:

                    print('<input type="checkbox" name="terms_and_conditions" value="" class="terms-and-conditions required" />');
                    print('&nbsp; <a href="'.get_permalink($objSettings->getSetting('conf_terms_and_conditions_page_id')).'" target="_blank">'.$objLang->getText('NRS_I_AGREE_WITH_TERMS_AND_CONDITIONS_TEXT').'</a>');
                endif;
                ?>
            </div>
        </div>
    <?php endif; ?>
	


  

    <div id="customer-buttons" class="customer-buttons">
        <?php
        if($newBooking):
            if($objSettings->getSetting('conf_universal_analytics_events_tracking') == 1):
                // Note: Do not translate events to track well inter-language events
                $onClick = "ga('send', 'event', 'Car Rental', 'Click', '4. Confirm reservation');";
                print('<button type="submit" name="car_rental_do_search4" class="register-button" onClick="'.esc_js($onClick).'">'.$objLang->getText('NRS_CONFIRM_TEXT').'</button>');
            else:
                print('<button id="register-button" type="submit" name="car_rental_do_search4" class="register-button">'.$objLang->getText('NRS_CONFIRM_TEXT').'</button>');
                print('<div id="customer-buttons-div"> </div>');
                print('<button id="register-button2" type="submit" name="car_rental_do_search4" class="register-button" style="opacity:0 !important;width: 0px !important;overflow: hidden !important;">'.$objLang->getText('NRS_CONFIRM_TEXT').'</button>');
            endif;
        else:
            print('<input type="submit" name="car_rental_cancel_booking" value="'.$objLang->getText('NRS_CANCEL_BOOKING_TEXT').'" />');
            print('<input type="submit" name="car_rental_do_search0" value="'.$objLang->getText('NRS_CHANGE_BOOKING_DATE_TIME_AND_LOCATION_TEXT').'" />');
            print('<input type="submit" name="car_rental_do_search" value="'.$objLang->getText('NRS_CHANGE_BOOKED_ITEMS_TEXT').'" />');
            print('<input type="submit" name="car_rental_do_search2" value="'.$objLang->getText('NRS_CHANGE_RENTAL_OPTIONS_TEXT').'" />');
            print('<button name="car_rental_do_search4" class="register-button" type="submit">'.$objLang->getText('NRS_UPDATE_BOOKING_TEXT').'</button>');
        endif;
        ?>
    </div>
</form>


			  <div class="cell stripe-form stripe-final-form">
				<form action="/charge" method="post" id="payment-form">
				  <div data-locale-reversible>
					<div class="row">
					  
					  <div class="field quarter-width">
						<input id="stripe-final-form-zip" data-tid="elements_stripeforms.form.postal_code_placeholder" class="input" type="text" placeholder="7000" required="" autocomplete="postal-code">
						<label for="stripe-final-form-zip" data-tid="elements_stripeforms.form.postal_code_label">ZIP</label>
						<div class="baseline"></div>
					  </div>
					</div>
				  </div>
				  <div class="row">
					<div class="field">
					  <div id="stripe-final-form-card-number" class="input empty"></div>
					  <label for="stripe-final-form-card-number" data-tid="elements_stripe-forms.form.card_number_label">Card number</label>
					  <div class="baseline"></div>
					</div>
				  </div>
				  <div class="row">
					<div class="field half-width">
					  <div id="stripe-final-form-card-expiry" class="input empty"></div>
					  <label for="stripe-final-form-card-expiry" data-tid="elements_stripe-forms.form.card_expiry_label">Expiration</label>
					  <div class="baseline"></div>
					</div>
					<div class="field half-width">
					  <div id="stripe-final-form-card-cvc" class="input empty"></div>
					  <label for="stripe-final-form-card-cvc" data-tid="elements_stripe-forms.form.card_cvc_label">CVC</label>
					  <div class="baseline"></div>
					</div>
				  </div>
				<button type="submit" data-tid="elements_stripe-forms.form.pay_button">Pay Now</button>
				  <div class="error" role="alert"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17">
					  <path class="base" fill="#000" d="M8.5,17 C3.80557963,17 0,13.1944204 0,8.5 C0,3.80557963 3.80557963,0 8.5,0 C13.1944204,0 17,3.80557963 17,8.5 C17,13.1944204 13.1944204,17 8.5,17 Z"></path>
					  <path class="glyph" fill="#FFF" d="M8.5,7.29791847 L6.12604076,4.92395924 C5.79409512,4.59201359 5.25590488,4.59201359 4.92395924,4.92395924 C4.59201359,5.25590488 4.59201359,5.79409512 4.92395924,6.12604076 L7.29791847,8.5 L4.92395924,10.8739592 C4.59201359,11.2059049 4.59201359,11.7440951 4.92395924,12.0760408 C5.25590488,12.4079864 5.79409512,12.4079864 6.12604076,12.0760408 L8.5,9.70208153 L10.8739592,12.0760408 C11.2059049,12.4079864 11.7440951,12.4079864 12.0760408,12.0760408 C12.4079864,11.7440951 12.4079864,11.2059049 12.0760408,10.8739592 L9.70208153,8.5 L12.0760408,6.12604076 C12.4079864,5.79409512 12.4079864,5.25590488 12.0760408,4.92395924 C11.7440951,4.59201359 11.2059049,4.59201359 10.8739592,4.92395924 L8.5,7.29791847 L8.5,7.29791847 Z"></path>
					</svg>
					<span class="message"></span></div>
				</form>
				<div class="success">
				  <div class="icon">
					<svg width="84px" height="84px" viewBox="0 0 84 84" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
					  <circle class="border" cx="42" cy="42" r="40" stroke-linecap="round" stroke-width="4" stroke="#000" fill="none"></circle>
					  <path class="checkmark" stroke-linecap="round" stroke-linejoin="round" d="M23.375 42.5488281 36.8840688 56.0578969 64.891932 28.0500338" stroke-width="4" stroke="#000" fill="none"></path>
					</svg>
				  </div>
				  <h3 class="title" data-tid="elements_stripe-forms.success.title">Payment successful</h3>
				  <p class="message"><span data-tid="elements_stripe-forms.success.message">Than you for working with us</span></p>
				  <a class="reset" href="#">
					<svg width="32px" height="32px" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
					  <path fill="#000000" d="M15,7.05492878 C10.5000495,7.55237307 7,11.3674463 7,16 C7,20.9705627 11.0294373,25 16,25 C20.9705627,25 25,20.9705627 25,16 C25,15.3627484 24.4834055,14.8461538 23.8461538,14.8461538 C23.2089022,14.8461538 22.6923077,15.3627484 22.6923077,16 C22.6923077,19.6960595 19.6960595,22.6923077 16,22.6923077 C12.3039405,22.6923077 9.30769231,19.6960595 9.30769231,16 C9.30769231,12.3039405 12.3039405,9.30769231 16,9.30769231 L16,12.0841673 C16,12.1800431 16.0275652,12.2738974 16.0794108,12.354546 C16.2287368,12.5868311 16.5380938,12.6540826 16.7703788,12.5047565 L22.3457501,8.92058924 L22.3457501,8.92058924 C22.4060014,8.88185624 22.4572275,8.83063012 22.4959605,8.7703788 C22.6452866,8.53809377 22.5780351,8.22873685 22.3457501,8.07941076 L22.3457501,8.07941076 L16.7703788,4.49524351 C16.6897301,4.44339794 16.5958758,4.41583275 16.5,4.41583275 C16.2238576,4.41583275 16,4.63969037 16,4.91583275 L16,7 L15,7 L15,7.05492878 Z M16,32 C7.163444,32 0,24.836556 0,16 C0,7.163444 7.163444,0 16,0 C24.836556,0 32,7.163444 32,16 C32,24.836556 24.836556,32 16,32 Z"></path>
					</svg>
				  </a>
				</div>

				
			  </div>	
				
</div>
<script type="text/javascript">
jQuery(document).ready(function($) {
    jQuery.extend(jQuery.validator.messages, {
        required: "<?php print($objLang->getText('NRS_REQUIRED_TEXT')); ?>"
    });
	
		jQuery('.car-rental-customer-form').validate({
		  submitHandler: function(form) {
			if($('input[name="payment_method_id"]:checked').val()==2 && !($("#stripeToken").length)){
				$("#payment-form>button").trigger( "click" );                       
			}else{
				form.submit();
			}
		  }
		});
    
    jQuery('.car-rental-booking-details .customer-lookup').click(function()
    {
        var objCustomerEmailAddress = jQuery('.car-rental-booking-details .search-email-address');
        var objCustomerYearOfBirth = jQuery('.car-rental-booking-details .search-birth-year');
        var customerEmailAddress = "SKIP";
        var customerYearOfBirth = "SKIP";

        <?php if($boolEmailRequired): ?>
            if(objCustomerEmailAddress.length)
            {
                customerEmailAddress = objCustomerEmailAddress.val();
            }
        <?php endif; ?>

        <?php if($boolBirthdateRequired): ?>
            if(objCustomerEmailAddress.length)
            {
                customerYearOfBirth = objCustomerYearOfBirth.val();
            }
        <?php endif; ?>

        //console.log(customerEmailAddress);
        jQuery('.car-rental-booking-details .ajax-loader').html("<img src='<?php print($objConf->getExtensionFrontImagesURL('AjaxLoader.gif')); ?>' border='0'>");
        carRentalCustomerDetailsLookup(
            '<?php print($extensionFolder); ?>',
            '<?php print($ajaxSecurityNonce); ?>',
            '<?php print($siteURL); ?>',
            customerEmailAddress,
            customerYearOfBirth
        );
    });
});
</script>