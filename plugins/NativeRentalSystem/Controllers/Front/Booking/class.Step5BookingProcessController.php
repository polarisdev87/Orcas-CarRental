<?php
/**
 * Booking step no. 5
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Front\Booking;
use NativeRentalSystem\Models\Booking\BookingsObserver;
use NativeRentalSystem\Models\Booking\ExtraBookingOption;
use NativeRentalSystem\Models\Booking\ItemBookingOption;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Customer\CustomersObserver;
use NativeRentalSystem\Models\Extra\Extra;
use NativeRentalSystem\Models\Formatting\StaticFormatter;
use NativeRentalSystem\Models\Item\Item;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Booking\Booking;
use NativeRentalSystem\Models\Customer\Customer;
use NativeRentalSystem\Models\Deposit\DepositsObserver;
use NativeRentalSystem\Models\Location\Location;
use NativeRentalSystem\Models\Location\LocationsObserver;
use NativeRentalSystem\Models\EMail\EMailsObserver;
use NativeRentalSystem\Models\PaymentMethod\PaymentMethod;
use NativeRentalSystem\Models\Prepayment\PrepaymentsObserver;
use NativeRentalSystem\Models\PaymentMethod\PaymentMethodsObserver;
use NativeRentalSystem\Models\Booking\Invoice;
use NativeRentalSystem\Models\Search\FrontEndSearchManager;
use NativeRentalSystem\Models\Search\ItemSearchManager;
use NativeRentalSystem\Controllers\Front\AbstractController;
use NativeRentalSystem\Models\Tax\TaxManager;
use NativeRentalSystem\Models\Validation\StaticValidator;
use ReCaptcha\ReCaptcha;

final class Step5BookingProcessController extends AbstractController
{
    private $objSearch	                = NULL;
    private $additionalErrors           = array();

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, $paramArrLimitations = array())
    {
        parent::__construct($paramConf, $paramLang, $paramArrLimitations);
        $this->objSearch = new FrontEndSearchManager($this->conf, $this->lang, $this->dbSettings->getSettings());
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

        // Fifth - validate time input
        $this->objSearch->validateTimeInput($this->objSearch->getPickupTimestamp(), $this->objSearch->getReturnTimestamp());

        // Sixth - validate pick-up
        $this->objSearch->validatePickupInput($this->objSearch->getPickupLocationId(), $this->objSearch->getPickupTimestamp());

        // Seventh - validate return
        $this->objSearch->validateReturnInput($this->objSearch->getReturnLocationId(), $this->objSearch->getReturnTimestamp());

        if ($this->objSearch->searchEnabled() && $this->objSearch->isValidSearch())
        {
            // Data defined successfully, now remove session variables
            $this->objSearch->removeSessionVariables();

            // Set fresh session variables
            $this->objSearch->setMySessionVars();
        }
    }

    private function isValidReCaptcha($paramReCaptchaResponse)
    {
        $retIsValidReCaptcha = TRUE;
        $checkReCaptcha = $this->dbSettings->getSetting('conf_recaptcha_enabled') == 1 && $this->dbSettings->getSetting('conf_recaptcha_secret_key') != '';
        if($checkReCaptcha === TRUE)
        {
            // If the form submission includes the "g-captcha-response" field
            // Create an instance of the service using your secret
            $objReCaptcha 			= new ReCaptcha($this->dbSettings->getSetting('conf_recaptcha_secret_key'));
            // If file_get_contents() is locked down on your PHP installation to disallow
            // its use with URLs, then you can use the alternative request method instead.
            // This makes use of fsockopen() instead.
            //  $objReCaptcha = new \ReCaptcha\ReCaptcha($this->reCaptchaSecretKey, new \ReCaptcha\RequestMethod\SocketPost());

            // Make the call to verify the response and also pass the user's IP address
            $objReCaptchaResponse = $objReCaptcha->verify($paramReCaptchaResponse, $_SERVER['REMOTE_ADDR']);

            if ($objReCaptchaResponse->isSuccess())
            {
                // If the response is a success, that's it!
                // Do nothing
                $retIsValidReCaptcha = TRUE;
            } else
            {
                $retIsValidReCaptcha = FALSE;
                // If it's not successful, then one or more error codes will be returned.
                $this->additionalErrors[] = $this->lang->getText('NRS_ERROR_INVALID_SECURITY_CODE_TEXT');
                if($this->wpDebugEnabledDisplay())
                {
                    // Error codes reference can be found at:
                    // https://developers.google.com/recaptcha/docs/verify#error-code-reference%22
                    echo "<br />ReCaptcha validator returned the following errors: ";
                    foreach ($objReCaptchaResponse->getErrorCodes() as $code)
                    {
                        echo '<br />'.$code.'';
                    }
                    echo '<br />Check the error code reference at ';
                    echo '<a href="https://developers.google.com/recaptcha/docs/verify#error-code-reference">';
                    echo 'https://developers.google.com/recaptcha/docs/verify#error-code-reference</a>';
                    echo '<br /><strong>Note:</strong> Error code <em>missing-input-response</em> may mean';
                    echo ' the user just didn&#39;t complete the reCAPTCHA.';
                }
            }
        }

        return $retIsValidReCaptcha;
    }

    private function isValidAge($paramCustomerId)
    {
        $retIsValidAge = TRUE;

        $objCustomer = new Customer($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramCustomerId);
        $itemIds = $this->objSearch->getItemIds();
        // ID was already updated, so the bellow is a correct call
        $customerAge = $objCustomer->getAge();
        foreach($itemIds AS $itemId)
        {
            $objItem = new Item($this->conf, $this->lang, $this->dbSettings->getSettings(), $itemId);
            if($objItem->canDriveByAge($customerAge) === FALSE)
            {
                $retIsValidAge = FALSE;
                $itemDetails = $objItem->getExtendedDetails();
                if(!is_null($itemDetails))
                {
                    $paramItemTitle = $itemDetails['print_translated_manufacturer_title'].' '.$itemDetails['print_translated_model_name'];
                    $paramItemTitle .= ' '.$itemDetails['print_via_partner'];
                    $this->additionalErrors[] = sprintf($this->lang->getText('NRS_ERROR_DRIVER_AGE_VIOLATION_FOR_ITEM_TEXT'), $paramItemTitle);
                } else
                {
                    $this->additionalErrors[] = $this->lang->getText('NRS_ERROR_DRIVER_AGE_VIOLATION_TEXT');
                }
            }
        }

        return $retIsValidAge;
    }

    /**
     * Are we booking only those items who has available units in stock?
     * @param $paramBookingId
     * @return int
     */
    private function getTotalAvailableOfSelectedItems($paramBookingId)
    {
        $objTaxManager = new TaxManager($this->conf, $this->lang, $this->dbSettings->getSettings());
        $taxPercentage = $objTaxManager->getTaxPercentage($this->objSearch->getPickupLocationId(), $this->objSearch->getReturnLocationId());
        $objLocation = new Location($this->conf, $this->lang, $this->dbSettings->getSettings(), $this->objSearch->getPickupLocationId());
        $locationCode = $objLocation->getCode();

        $objSearchManagerForItems = new ItemSearchManager(
            $this->conf, $this->lang, $this->dbSettings->getSettings(), $taxPercentage, $locationCode,
                $paramBookingId, $this->objSearch->getCouponCode()
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

        $totalSelectedItemsQuantity = $this->objSearch->getItemsTotalSelectedQuantity();
        if($totalSelectedItemsQuantity == 0)
        {
            // Additional Error: Please select at least one item
            $this->additionalErrors[] = $this->lang->getText('NRS_ERROR_PLEASE_SELECT_AT_LEAST_ONE_ITEM_TEXT');
        }

        $availableOfSelectedItems = $objSearchManagerForItems->getItemsWithPricesAndOptions(
            $selectedItemIds, $this->objSearch->getItemUnits(), $this->objSearch->getItemOptions(),
            $this->objSearch->getPickupTimestamp(), $this->objSearch->getReturnTimestamp(), FALSE
        );

        $totalAvailableOfSelectedItems = sizeof($availableOfSelectedItems);

        if($this->wpDebugEnabledDisplay())
        {
            // DEBUG
            echo "<br />Total items selected quantity: ".$totalSelectedItemsQuantity;
        }

        return $totalAvailableOfSelectedItems;
    }

    /**
     * #4
     * @note - use of $this->objSearch is forbidden in this method
     * @param string $paramLayout
     * @param int $paramBookingId
     * @param int $paramCustomerId
     * @param int $paramPaymentMethodId
     * @param $paramPriceSummary
     */
    private function createInvoice(
        $paramLayout = 'Table',
        $paramBookingId, $paramCustomerId, $paramPaymentMethodId,
        array $paramPriceSummary
    )
    {
        // Create mandatory local objects
        $objBooking = new Booking($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramBookingId);
        $objInvoice = new Invoice($this->conf, $this->lang, $this->dbSettings->getSettings(), $objBooking->getId());
        $objLocationsObserver = new LocationsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objPaymentMethodsObserver = new PaymentMethodsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objPaymentMethod = new PaymentMethod($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramPaymentMethodId);
        $objCustomer = new Customer($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramCustomerId);
        $objDepositsObserver = new DepositsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objPrepaymentsObserver = new PrepaymentsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());

        // For output only: start
        $paymentMethodDetails = $objPaymentMethod->getDetails();
        $pickupsHTML = $objLocationsObserver->getPrintPickups($this->objSearch->getPickupLocationId(), $this->objSearch->getLocalPickupDayOfWeek());
        $returnsHTML = $objLocationsObserver->getPrintReturns($this->objSearch->getReturnLocationId(), $this->objSearch->getLocalReturnDayOfWeek());
        $showLocationFees = FALSE;
        if(is_array($paramPriceSummary['pickup']) && $paramPriceSummary['pickup']['unit']['current_pickup_fee'] > 0.00)
        {
            $showLocationFees = TRUE;
        } else if(is_array($paramPriceSummary['return']) && $paramPriceSummary['return']['unit']['current_return_fee'] > 0.00)
        {
            $showLocationFees = TRUE;
        }

        // Set the view variables for invoice
        $this->fillSearchFieldsView(); // Fill search fields view
        $this->fillCustomerFieldsView(); // Fill customer fields view
        $this->view->extensionName = $this->conf->getExtensionName();
        $this->view->objSearch = $this->objSearch;
        $this->view->bookingCode = $objBooking->getPrintCode();
        $this->view->couponCode = $objBooking->getPrintCouponCode();
        $this->view->depositsEnabled = $objDepositsObserver->areDepositsEnabled();
        $this->view->prepaymentsEnabled = $objPrepaymentsObserver->arePrepaymentsEnabled();
        $this->view->payNowText = $this->lang->getText($objPaymentMethod->isOnlinePayment() ? 'NRS_STEP5_PAY_ONLINE_TEXT' : 'NRS_STEP5_PAY_AT_PICKUP_TEXT');
        $this->view->customerDetails =  $objCustomer->getDetails();
        $this->view->pickupLocations = $objLocationsObserver->getPrintPickups($this->objSearch->getPickupLocationId(), $this->objSearch->getLocalPickupDayOfWeek());
        $this->view->returnLocations = $objLocationsObserver->getPrintReturns($this->objSearch->getReturnLocationId(), $this->objSearch->getLocalReturnDayOfWeek());
        $this->view->pickupMainColspan = $this->dbSettings->getSearchFieldStatus("return_location", "VISIBLE") ? 1 : 3;
        $this->view->returnMainColspan = $this->dbSettings->getSearchFieldStatus("pickup_location", "VISIBLE") ? 2 : 3;
        $this->view->pickupColspan = $this->dbSettings->getSearchFieldStatus("return_date", "VISIBLE") ? 1 : 3;
        $this->view->returnColspan = $this->dbSettings->getSearchFieldStatus("pickup_date", "VISIBLE") ? 1 : 2;
        $this->view->showPaymentDetails = $objPrepaymentsObserver->arePrepaymentsEnabled() && $objPaymentMethodsObserver->getTotalEnabled() > 0;
        $this->view->paymentMethodName = isset($paymentMethodDetails['payment_method_name']) ? $paymentMethodDetails['print_translated_payment_method_name'] : "";
        $this->view->priceSummary = $paramPriceSummary;
        $this->view->showLocationFees = $showLocationFees;

        // Get the invoice HTML
        $invoiceHTML = $this->getTemplate('Booking', 'Step5BookingInvoice', $paramLayout);

        // Save invoice
        $customerDetails = $objCustomer->getDetails();
        $objInvoice->save(
            $customerDetails['print_full_name'], $customerDetails['email'],
            $paramPriceSummary['overall_print']['grand_total'], $paramPriceSummary['overall_print']['fixed_deposit_amount'],
            $paramPriceSummary['overall_print']['total_pay_now'], $paramPriceSummary['overall_print']['total_pay_later'],
            $pickupsHTML, $returnsHTML, $invoiceHTML
        );
    }

    /**
     * @param string $paramLayout
     * @return string
     */
    public function getContent($paramLayout = 'Table')
    {
        // Set default
        $processingPageContent = '';
        $emailSentSuccessfully = TRUE;

        // Create mandatory instances
        $objEMailsObserver = new EMailsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objBookingsObserver = new BookingsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $bookingId = $objBookingsObserver->getIdByCode($this->objSearch->getBookingCode());
        $objBooking = new Booking($this->conf, $this->lang, $this->dbSettings->getSettings(), $bookingId);
        $objCustomersObserver = new CustomersObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $customerId = isset($_POST['email']) ? $objCustomersObserver->getIdByEmail($_POST['email']) : 0;
        $objCustomer = new Customer($this->conf, $this->lang, $this->dbSettings->getSettings(), $customerId);
        $objPaymentMethodsObserver = new PaymentMethodsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
		
        if($bookingId > 0)
        {
            $paymentMethodId = $objPaymentMethodsObserver->getIdByCode($objBooking->getPaymentMethodCode());
        } else
        {
            $paymentMethodId = isset($_POST['payment_method_id']) ? StaticValidator::getValidPositiveInteger($_POST['payment_method_id'], 0) : 0;
        }
		
        $objPaymentMethod = new PaymentMethod($this->conf, $this->lang, $this->dbSettings->getSettings(), $paymentMethodId);
        $objPrepaymentsObserver = new PrepaymentsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $pmRequired = $objPrepaymentsObserver->arePrepaymentsEnabled() && $objPaymentMethodsObserver->getTotalEnabled() > 0 ? TRUE : FALSE;

        // First - get the prices summary
        // NOTE: Order is important booking can be saved with saveBookingData(..) only after objSearch->getPriceSummary() was called.
        // This is because if we would save data first, and we would have only 1 car in database for that period,
        // then our price summary would not be able to pull that car as available for the booking
        $priceSummary = $this->objSearch->getPriceSummary();

        $hasAvailableSelectedItems = $this->getTotalAvailableOfSelectedItems($objBooking->getId()) > 0 ? TRUE: FALSE;
        $canProcess = FALSE;
        if (
            $this->objSearch->searchEnabled() && $hasAvailableSelectedItems && $this->objSearch->isValidSearch()
            && ($pmRequired === FALSE || $objPaymentMethod->isValid()) && sizeof($this->additionalErrors) == 0
        )
        {
            // Second - save new customer data
            // We set update last visit timestamp to TRUE here, because we want that customers last visit time would be updated to current time
            $objCustomer->save();
            if(sizeof($objCustomer->getErrorMessages()) == 0)
            {
                // We can process only if there is no errors with customer fields
                $canProcess = TRUE;
                $objCustomer->updateLastVisit();
            }
        }

        if($canProcess === TRUE && $this->objSearch->isNewBooking())
        {
            $reCaptchaResponse	= isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';
            $canProcess = $this->isValidReCaptcha($reCaptchaResponse);
        }

        // Validate age only if birthdate is required
        if($canProcess === TRUE && $this->dbSettings->getCustomerFieldStatus("birthdate", "REQUIRED"))
        {
            $canProcess = $this->isValidAge($objCustomer->getId());
        }

        if($this->wpDebugEnabledDisplay())
        {
            // DEBUG
            echo "<br />Search enabled: " . var_export($this->objSearch->searchEnabled(), TRUE);
            echo "<br />Has available selected items: " . var_export($hasAvailableSelectedItems, TRUE);
            echo "<br />Is valid search: " . var_export($this->objSearch->isValidSearch(), TRUE);
            echo "<br />Is payment method required: " . var_export($pmRequired, TRUE);
            echo "<br />Is valid payment method: " . var_export($objPaymentMethod->isValid(), TRUE);
            echo "<br />Total additional errors: " . var_export(sizeof($this->additionalErrors), TRUE);
            echo "<br />Total customer saving errors: " . var_export(sizeof($objCustomer->getErrorMessages()), TRUE);
            echo "<br />Is new booking: " . var_export($this->objSearch->isNewBooking(), TRUE);
            echo "<br />Is valid ReCaptcha: " . var_export($this->objSearch->isNewBooking() ? $this->isValidReCaptcha(isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '') : "SKIP", TRUE);
            echo "<br />Birthdate required: " . var_export($this->dbSettings->getCustomerFieldStatus("birthdate", "REQUIRED"), TRUE);
            echo "<br />Is valid age (customer id - " . $objCustomer->getId() . "): " . var_export($this->isValidAge($objCustomer->getId()), TRUE);
            echo "<br />Can process: " . var_export($canProcess, TRUE);
            // We can die here, if we want to keep our _SESSION data without destroying yet for repeated debug for next page refresh with F5
            //die();
        }

        if($canProcess === TRUE)
        {
            // If that is online payment - we need to cancel old booking before
            if ($objBooking->getId() > 0 && $objPaymentMethod->isOnlinePayment())
            {
                // Cancel previous online booking
                $objBooking->cancel();
                $objEMailsObserver->sendBookingCancellationEmail($objBooking->getId(), TRUE);

                if($this->wpDebugEnabledDisplay())
                {
                    $arrMessages = array_merge(
                        $objBooking->getOkayMessages(), $objEMailsObserver->getSavedOkayMessages(),
                        $objBooking->getErrorMessages(), $objEMailsObserver->getSavedErrorMessages()
                    );
                    // Put notes of existing booking
                    echo '<br />Old booking message list:<br />'.StaticFormatter::getPrintMessage($arrMessages);
                }

                // Reset booking object and booking id to 0 for online payment to create a new booking
                $objBooking = new Booking($this->conf, $this->lang, $this->dbSettings->getSettings(), 0);
            }

            $objPickupLocation = new Location($this->conf, $this->lang, $this->dbSettings->getSettings(), $this->objSearch->getPickupLocationId());
            $objReturnLocation = new Location($this->conf, $this->lang, $this->dbSettings->getSettings(), $this->objSearch->getPickupLocationId());

            // Third - Pass all search data and save the booking (order is important, because we use booking customer id in booking manager
            // NOTE: Order is important, booking can only be saved after objCustomer->saveCustomerData(..) was called to not to loose customer id
            $objBooking->save($objCustomer->getId(), $objPaymentMethod->getCode(), $objPickupLocation->getCode(), $objReturnLocation->getCode(), $this->objSearch->getSearchInputDataArray());
            $bookingId = $objBooking->getId();

            // If this is an existing booking
            if ($objBooking->getId() > 0)
            {
                // Delete old booking options first
                $objBooking->deleteAllOptions();
            }

            // Add new item booking options
            $itemIds = $this->objSearch->getItemIds();
            foreach($itemIds AS $itemId)
            {
                $objItem = new Item($this->conf, $this->lang, $this->dbSettings->getSettings(), $itemId);
                $objBookingOption = new ItemBookingOption($this->conf, $this->lang, $this->dbSettings->getSettings(), $objItem->getSKU(), $bookingId);
                $objBookingOption->save($this->objSearch->getItemOption($itemId), $this->objSearch->getItemQuantity($itemId));
            }

            // Add new extra booking options
            $extraIds = $this->objSearch->getExtraIds();
            foreach($extraIds AS $extraId)
            {
                $objExtra = new Extra($this->conf, $this->lang, $this->dbSettings->getSettings(), $extraId);
                $objBookingOption = new ExtraBookingOption($this->conf, $this->lang, $this->dbSettings->getSettings(), $objExtra->getSKU(), $bookingId);
                $objBookingOption->save($this->objSearch->getExtraOption($extraId), $this->objSearch->getExtraQuantity($extraId));
            }

            // NOTE: Order is important, booking invoice html can be created only after BookingManager->saveBookingData(..) was called
            // Fifth - Create the invoice
            $this->createInvoice($paramLayout, $objBooking->getId(), $objCustomer->getId(), $objPaymentMethod->getId(), $priceSummary);

            // Sixth - Process payment method
            $paymentMethodDetails = $objPaymentMethod->getDetails();
            $paymentFolderAndFileName = isset($paymentMethodDetails['folder_and_file_name']) ? $paymentMethodDetails['folder_and_file_name'] : "";
            $paymentClassName = isset($paymentMethodDetails['class_name']) ? $paymentMethodDetails['class_name'] : "";
            $paymentFolderPathWithFileName = $this->conf->getLibrariesPath().$paymentFolderAndFileName;
            if ($paymentClassName != "" && is_readable($paymentFolderPathWithFileName))
            {
                require_once $paymentFolderPathWithFileName;
                // This is ok that the classes are not found
                if(class_exists($paymentClassName))
                {
                    $objPayment = new $paymentClassName($this->conf, $this->lang, $this->dbSettings->getSettings(), $objPaymentMethod->getId());
                    // Prepare processing page (most likely - a payment form)
                    if(method_exists($objPayment, 'setProcessingPage'))
                    {
                        $objPayment->setProcessingPage($objBooking->getCode(), $priceSummary['overall']['total_pay_now']);
                    }

                    if(method_exists($objPayment, 'getProcessingPageContent'))
                    {
                        $processingPageContent = $objPayment->getProcessingPageContent();
                    }
                }
            }

            // Seventh - Send confirmation email
            $emailSentSuccessfully = $objEMailsObserver->sendBookingDetailsEmail($objBooking->getId(), TRUE);
        }

        // Set the view variables
        $this->view->priceSummary = $priceSummary; // We need this for Enhanced Ecommerce
        $this->view->processingPageContent = $processingPageContent; // We need this for Enhanced Ecommerce
        $this->view->emailSentSuccessfully = $emailSentSuccessfully; // We need this for Booking Confirmed Page
        $this->view->newBooking = $this->objSearch->isNewBooking(); // We need this for Enhanced Ecommerce
        $this->view->bookingCode = $objBooking->getPrintCode(); // We need this for Booking Confirmed Page and Enhanced Ecommerce
        $this->view->couponCode = $objBooking->getPrintCouponCode(); // We need this for Enhanced Ecommerce
        $this->view->searchErrors = implode("<br /><br />", array_merge(
            $this->additionalErrors, $this->objSearch->getErrorMessages(), $objCustomer->getErrorMessages()
        ));

        // Get the template
        // NOTE! Do not use 'sizeof(items)' in verification bellow, because car was just booked and removed from available db
        if($canProcess)
        {
            $templateName = $objPaymentMethod->isOnlinePayment() ? 'Step5ProcessingPayment' : 'BookingConfirmed';
        } else
        {
            $templateName = 'BookingFailure'; // Failure template
        }
        $retContent = $this->objSearch->searchEnabled() ? $this->getTemplate('Booking', $templateName, '') : '';

        return $retContent;
    }
}