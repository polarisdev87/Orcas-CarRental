<?php
/**
 * Booking step no. 4
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Front\Booking;
use NativeRentalSystem\Models\Booking\BookingsObserver;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Formatting\StaticFormatter;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Booking\Booking;
use NativeRentalSystem\Models\Customer\Customer;
use NativeRentalSystem\Models\Deposit\DepositsObserver;
use NativeRentalSystem\Models\Location\Location;
use NativeRentalSystem\Models\Location\LocationsObserver;
use NativeRentalSystem\Models\Prepayment\PrepaymentsObserver;
use NativeRentalSystem\Models\PaymentMethod\PaymentMethodsObserver;
use NativeRentalSystem\Models\PaymentMethod\PaymentMethod;
use NativeRentalSystem\Models\Search\FrontEndSearchManager;
use NativeRentalSystem\Models\Search\ItemSearchManager;
use NativeRentalSystem\Controllers\Front\AbstractController;
use NativeRentalSystem\Models\Tax\TaxManager;

final class Step4BookingDetailsController extends AbstractController
{
    private $objSearch          = NULL;
    private $additionalErrors   = array();

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, $paramArrLimitations = array())
    {
        parent::__construct($paramConf, $paramLang, $paramArrLimitations);

        $this->objSearch = new FrontEndSearchManager($this->conf, $this->lang, $this->dbSettings->getSettings());

        // First - process request
        $this->processRequest();
    }

    private function processRequest()
    {
        //echo "INITIAL REQUEST VARS: ".nl2br(print_r($_REQUEST, TRUE));
        //echo "INITIAL SESSION VARS: ".nl2br(print_r($_SESSION, TRUE));

        $objBookingObservers = new BookingsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());

        // First - clear old expired bookings
        $objBookingObservers->clearExpired();

        // Second - validate the booking code
        $this->objSearch->setBookingCode($this->dbSettings->getSearchFieldStatus("booking_code", "REQUIRED"));
        if($this->objSearch->getBookingCode() != '')
        {
            $objBookingsObserver = new BookingsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
            $this->objSearch->setVariablesByBookingId($objBookingsObserver->getIdByCode($this->objSearch->getBookingCode()));
        }

        // Third - set object variables - allow to override by _POST, _GET or _SESSION
        $this->objSearch->setVariablesByRequestOrSessionParams();

        // Fourth - validate time input
        $this->objSearch->validateTimeInput($this->objSearch->getPickupTimestamp(), $this->objSearch->getReturnTimestamp());

        // Fifth - validate pick-up
        $this->objSearch->validatePickupInput($this->objSearch->getPickupLocationId(), $this->objSearch->getPickupTimestamp());

        // Sixth - validate return
        $this->objSearch->validateReturnInput($this->objSearch->getReturnLocationId(), $this->objSearch->getReturnTimestamp());

        if ($this->objSearch->searchEnabled() && $this->objSearch->isValidSearch())
        {
            // Data defined successfully, now remove session variables
            $this->objSearch->removeSessionVariables();

            // Set fresh session variables
            $this->objSearch->setMySessionVars();
        }

        //echo "UPDATED SESSION VARS: ".nl2br(print_r($_SESSION, TRUE));
    }

    private function getTemplateNameAndLayout($paramLayout = 'Table')
    {
        // Create local mandatory instances
        $objBookingsObserver = new BookingsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objBooking = new Booking($this->conf, $this->lang, $this->dbSettings->getSettings(), $objBookingsObserver->getIdByCode($this->objSearch->getBookingCode()));
        $objTaxManager = new TaxManager($this->conf, $this->lang, $this->dbSettings->getSettings());
        $taxPercentage = $objTaxManager->getTaxPercentage($this->objSearch->getPickupLocationId(), $this->objSearch->getReturnLocationId());
        $objLocation = new Location($this->conf, $this->lang, $this->dbSettings->getSettings(), $this->objSearch->getPickupLocationId());
        $locationCode = $objLocation->getCode();

        // START: Are we booking only those items who has available units in stock?
        $objSearchManagerForItems = new ItemSearchManager(
            $this->conf, $this->lang, $this->dbSettings->getSettings(), $taxPercentage, $locationCode, $objBooking->getId(), $this->objSearch->getCouponCode()
        );
        $availableItemIds = $objSearchManagerForItems->getAvailableItemIds(
            $this->objSearch->getPickupLocationId(),
            $this->objSearch->getReturnLocationId(),
            $this->objSearch->getPartnerId(),
            $this->objSearch->getManufacturerId(),
            $this->objSearch->getBodyTypeId(),
            $this->objSearch->getTransmissionTypeId(),
            $this->objSearch->getFuelTypeId()
        );
        $selectedItemIds = $objSearchManagerForItems->getExistingSelectedItemIds($this->objSearch->getItemIds(), $availableItemIds);
        $itemsTotalSelectedQuantity = $this->objSearch->getItemsTotalSelectedQuantity();
        if($itemsTotalSelectedQuantity == 0)
        {
            // Additional Error: Please select at least one item
            $this->additionalErrors[] = $this->lang->getText('NRS_ERROR_PLEASE_SELECT_AT_LEAST_ONE_ITEM_TEXT');
        }
        $availableOfSelectedItems = $objSearchManagerForItems->getItemsWithPricesAndOptions(
            $selectedItemIds, $this->objSearch->getItemUnits(), $this->objSearch->getItemOptions(),
            $this->objSearch->getPickupTimestamp(), $this->objSearch->getReturnTimestamp(), FALSE
        );

        $totalSelectedItems = sizeof($selectedItemIds);
        $totalAvailableOfSelectedItems = sizeof($availableOfSelectedItems);
        // END: Are we booking only those items who has available units in stock?

        // Select the template
        if($totalAvailableOfSelectedItems > 0 && $this->objSearch->isValidSearch() && $itemsTotalSelectedQuantity > 0)
        {
            $templateName = 'Step4BookingDetails';
            $layout = sanitize_text_field($paramLayout);
        } else if(!$totalSelectedItems == 0 && $this->objSearch->isValidSearch() && $itemsTotalSelectedQuantity > 0)
        {
            $templateName = 'BookingFailureWithSearchAllButtons';
            $layout = '';
        } else
        {
            $templateName = 'BookingFailure'; // Failure template
            $layout = '';
        }

        return array("template_name" => $templateName, "layout" => $layout);
    }

    public function getContent($paramLayout = 'Table')
    {
        // Create local mandatory instances
        $objBookingsObserver = new BookingsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $bookingId = $objBookingsObserver->getIdByCode($this->objSearch->getBookingCode());
        $objBooking = new Booking($this->conf, $this->lang, $this->dbSettings->getSettings(), $bookingId);
        $objDepositsObserver = new DepositsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objPrepaymentsObserver = new PrepaymentsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objPaymentMethodsObserver = new PaymentMethodsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        // This is only possible if we are editing the booking
        $paymentMethodId = $bookingId > 0 ? $objPaymentMethodsObserver->getIdByCode($objBooking->getPaymentMethodCode()) : 0;
        $objPaymentMethod = new PaymentMethod($this->conf, $this->lang, $this->dbSettings->getSettings(), $paymentMethodId);
        $objLocationsObserver = new LocationsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objCustomer = new Customer($this->conf, $this->lang, $this->dbSettings->getSettings(), $objBooking->getCustomerId());
        $customerDetails = $objCustomer->getDetails(TRUE);

        // For output only: start
        if($this->objSearch->isNewBooking() == FALSE)
        {
            $pageLabel = $this->lang->getText('NRS_ITEM_RENTAL_DETAILS_TEXT')." - ".$this->lang->getText('NRS_BOOKING_CODE_TEXT')." ".$this->objSearch->getPrintBookingCode()." ".$this->lang->getText('NRS_BOOKING_EDIT_TEXT');
            if($this->objSearch->getCouponCode() != '')
            {
                $pageLabel .= '. '.$this->lang->getText('NRS_COUPON_TEXT').': '.$this->objSearch->getPrintCouponCode();
            }
        } else
        {
            $pageLabel = $this->lang->getText('NRS_ITEM_RENTAL_DETAILS_TEXT');
            if($this->objSearch->getCouponCode() != '')
            {
                $pageLabel .= '. '.$this->lang->getText('NRS_COUPON_TEXT').': '.$this->objSearch->getPrintCouponCode();
            }
        }

        $localBoolTitleRequired = $this->dbSettings->getCustomerFieldStatus("title", "REQUIRED") ? TRUE : FALSE;
        $localEnabledOnlineMethods = $objPaymentMethodsObserver->getTotalEnabledOnline();
        $localEnabledLocalMethods = $objPaymentMethodsObserver->getTotalEnabledLocally();
        if($localEnabledOnlineMethods > 0 && $localEnabledLocalMethods > 0)
        {
            $payNowText = $this->lang->getText('NRS_PAY_NOW_OR_AT_PICKUP_TEXT');
        } else if($localEnabledOnlineMethods > 0 && $localEnabledLocalMethods == 0)
        {
            $payNowText = $this->lang->getText('NRS_PAY_NOW_TEXT');
        } else
        {
            $payNowText = $this->lang->getText('NRS_PAY_AT_PICKUP_TEXT');
        }

        $selectedPaymentMethodName = "";
        $selectedPaymentMethodDescription = "";
        if($this->objSearch->isNewBooking() === FALSE)
        {
            // For edit only
            $localPaymentMethodDetails = $objPaymentMethod->getDetails();
            if(!is_null($localPaymentMethodDetails))
            {
                $selectedPaymentMethodName = $localPaymentMethodDetails['print_translated_payment_method_name'];
                $selectedPaymentMethodDescription = $localPaymentMethodDetails['translated_payment_method_description_html'];
            }
        }
        $priceSummary = $this->objSearch->getPriceSummary();
        // We must make check for ReCaptcha site key, because otherwise it will throw runtime error and break our template load, and we don't want that to happen
        $showReCaptcha = $this->dbSettings->getSetting('conf_recaptcha_enabled') == 1 && $this->dbSettings->getSetting('conf_recaptcha_site_key') != '';
        $showLocationFees = FALSE;
        if(is_array($priceSummary['pickup']) && $priceSummary['pickup']['unit']['current_pickup_fee'] > 0.00)
        {
            $showLocationFees = TRUE;
        } else if(is_array($priceSummary['return']) && $priceSummary['return']['unit']['current_return_fee'] > 0.00)
        {
            $showLocationFees = TRUE;
        }
        // For output only: end

        // Set the view variables
        $this->fillSearchFieldsView(); // Fill search fields view
        $this->fillCustomerFieldsView(); // Fill customer fields view
        $this->view->extensionName = $this->conf->getExtensionName();
        $this->view->objSearch = $this->objSearch;
        $this->view->depositsEnabled = $objDepositsObserver->areDepositsEnabled();
        $this->view->prepaymentsEnabled = $objPrepaymentsObserver->arePrepaymentsEnabled();
        $this->view->newBooking = $this->objSearch->isNewBooking();
        $this->view->titleDropDownOptions = $objCustomer->getTitleDropDownOptions($customerDetails['title'], $localBoolTitleRequired);
        $this->view->firstName = $customerDetails['edit_first_name'];
        $this->view->lastName = $customerDetails['edit_last_name'];
        $this->view->birthYearSearchDropDownOptions = StaticFormatter::generateDropDownOptions(current_time("Y") - 80, current_time("Y") - 10, '0000', "", $this->lang->getText('NRS_YEAR_OF_BIRTH_TEXT'), TRUE);
        $this->view->birthYearDropDownOptions = StaticFormatter::generateDropDownOptions(current_time("Y") - 80, current_time("Y") - 10, $customerDetails['birth_year'], "", $this->lang->getText('NRS_SELECT_YEAR_TEXT'), TRUE);
        $this->view->birthMonthDropDownOptions = StaticFormatter::generateDropDownOptions(1, 12, $customerDetails['birth_month'], "", $this->lang->getText('NRS_SELECT_MONTH_TEXT'), TRUE);
        $this->view->birthDayDropDownOptions = StaticFormatter::generateDropDownOptions(1, 31, $customerDetails['birth_day'], "", $this->lang->getText('NRS_SELECT_DAY_TEXT'), TRUE);
        $this->view->streetAddress = $customerDetails['edit_street_address'];
        $this->view->city = $customerDetails['edit_city'];
        $this->view->state = $customerDetails['edit_state'];
        $this->view->zipCode = $customerDetails['edit_zip_code'];
        $this->view->country = $customerDetails['edit_country'];
        $this->view->phone = $customerDetails['edit_phone'];
        $this->view->email = $customerDetails['edit_email'];
        $this->view->comments = $customerDetails['edit_comments'];

        $this->view->pageLabel = $pageLabel;
        $this->view->payNowText = $payNowText;
        $this->view->priceSummary = $priceSummary;
        $this->view->showLocationFees = $showLocationFees;
        $this->view->paymentMethods = $objPaymentMethodsObserver->getPaymentMethods($paymentMethodId, $priceSummary['overall']['total_pay_now']);
        $this->view->selectedPaymentMethodName = $selectedPaymentMethodName;
        $this->view->selectedPaymentMethodDescription = $selectedPaymentMethodDescription;
        $this->view->pickupLocations = $objLocationsObserver->getPrintPickups($this->objSearch->getPickupLocationId(), $this->objSearch->getLocalPickupDayOfWeek());
        $this->view->returnLocations = $objLocationsObserver->getPrintReturns($this->objSearch->getReturnLocationId(), $this->objSearch->getLocalReturnDayOfWeek());
        $this->view->pickupMainColspan = $this->dbSettings->getSearchFieldStatus("return_location", "VISIBLE") ? 1 : 3;
        $this->view->returnMainColspan = $this->dbSettings->getSearchFieldStatus("pickup_location", "VISIBLE") ? 2 : 3;
        $this->view->pickupColspan = $this->dbSettings->getSearchFieldStatus("return_date", "VISIBLE") ? 1 : 3;
        $this->view->returnColspan = $this->dbSettings->getSearchFieldStatus("pickup_date", "VISIBLE") ? 1 : 2;
        $this->view->showReCaptcha = $showReCaptcha;
        $this->view->reCaptchaSiteKey = esc_sql(sanitize_text_field($this->dbSettings->getSetting('conf_recaptcha_site_key')));
	    $this->view->reCaptchaLanguage = $this->lang->getText('NRS_RECAPTCHA_LANG');
        $this->view->searchErrors = implode("<br /><br />", array_merge($this->objSearch->getErrorMessages(), $this->additionalErrors));
        $this->view->ajaxSecurityNonce = wp_create_nonce($this->conf->getURLPrefix().'frontend-ajax-nonce');
        $this->view->extensionFolder = $this->conf->getExtensionFolder();
        $this->view->siteURL = get_site_url().'/';

        // Get the template
        $templateDetails = $this->getTemplateNameAndLayout($paramLayout);
        $retContent = $this->objSearch->searchEnabled() ? $this->getTemplate('Booking', $templateDetails['template_name'], $templateDetails['layout']) : '';

        return $retContent;
    }
}