/**
 * Car Rental System Admin JS
 * License: Licensed under the AGPL license.
 */

// Extension-specific dynamic variable
if(typeof carRentalLocale === "undefined")
{
    // The values here will come from WordPress script localizations,
    // but in case if they wouldn't, we have a backup initializer bellow
    var carRentalLocale = [];
}
function deleteCarRentalFeature(paramFeatureId)
{
    var approval = confirm(carRentalLocale.NRS_ADMIN_AJAX_DELETE_FEATURE_CONFIRM_TEXT);
    if(approval)
    {
        window.location = 'admin.php?page=car-rental-add-edit-feature&noheader=true&delete_feature=' + paramFeatureId;
    }
}

function deleteCarRentalBenefit(paramBenefitId)
{
    var approval = confirm(carRentalLocale.NRS_ADMIN_AJAX_DELETE_BENEFIT_CONFIRM_TEXT);
    if(approval)
    {
        window.location = 'admin.php?page=car-rental-add-edit-benefit&noheader=true&delete_benefit=' + paramBenefitId;
    }
}

function deleteCarRentalCustomer(paramCustomerId, paramBackToURLPart)
{
    var approval = confirm (carRentalLocale.NRS_ADMIN_AJAX_DELETE_CUSTOMER_CONFIRM_TEXT);
    if(approval)
    {
        window.location = 'admin.php?page=car-rental-add-edit-customer&noheader=true&delete_customer=' + paramCustomerId + paramBackToURLPart;
    }
}

function deleteCarRentalManufacturer(paramManufacturerId)
{
    var approval = confirm(carRentalLocale.NRS_ADMIN_AJAX_DELETE_MANUFACTURER_CONFIRM_TEXT);
    if(approval)
    {
        window.location = 'admin.php?page=car-rental-add-edit-manufacturer&noheader=true&delete_manufacturer=' + paramManufacturerId;
    }
}

function deleteCarRentalItem(paramItemId)
{
    var approval = confirm(carRentalLocale.NRS_ADMIN_AJAX_DELETE_ITEM_CONFIRM_TEXT);
    if(approval)
    {
        window.location = 'admin.php?page=car-rental-add-edit-item&noheader=true&delete_item=' + paramItemId;
    }
}

function deleteCarRentalBodyType(paramBodyTypeId)
{
    var approval = confirm(carRentalLocale.NRS_ADMIN_AJAX_DELETE_BODY_TYPE_CONFIRM_TEXT);
    if(approval)
    {
        window.location = 'admin.php?page=car-rental-add-edit-body-type&noheader=true&delete_body_type=' + paramBodyTypeId;
    }
}

function deleteCarRentalFuelType(paramFuelTypeId)
{
    var approval = confirm(carRentalLocale.NRS_ADMIN_AJAX_DELETE_FUEL_TYPE_CONFIRM_TEXT);
    if(approval)
    {
        window.location = 'admin.php?page=car-rental-add-edit-fuel-type&noheader=true&delete_fuel_type=' + paramFuelTypeId;
    }
}

function deleteCarRentalTransmissionType(paramTransmissionTypeId)
{
    var approval = confirm(carRentalLocale.NRS_ADMIN_AJAX_DELETE_TRANSMISSION_TYPE_TEXT);
    if(approval)
    {
        window.location = 'admin.php?page=car-rental-add-edit-transmission-type&noheader=true&delete_transmission_type=' + paramTransmissionTypeId;
    }
}

function deleteCarRentalExtra(paramExtraId)
{
    var approval = confirm(carRentalLocale.NRS_ADMIN_AJAX_DELETE_EXTRA_CONFIRM_TEXT);
    if(approval)
    {
        window.location = 'admin.php?page=car-rental-add-edit-extra&noheader=true&delete_extra=' + paramExtraId;
    }
}

function deleteCarRentalLocation(paramLocationId)
{
    var answer = confirm(carRentalLocale.NRS_ADMIN_AJAX_DELETE_LOCATION_CONFIRM_TEXT);
    if(answer)
    {
        window.location = "admin.php?page=car-rental-add-edit-location&noheader=true&delete_location="+paramLocationId;
    }
}

function deleteCarRentalDistance(paramDistanceId)
{
    var answer = confirm(carRentalLocale.NRS_ADMIN_AJAX_DELETE_DISTANCE_CONFIRM_TEXT);
    if(answer)
    {
        window.location = 'admin.php?page=car-rental-add-edit-distance&noheader=true&delete_distance=' + paramDistanceId;
    }
}

function deleteCarRentalPriceGroup(paramPriceGroupId)
{
    var approval = confirm(carRentalLocale.NRS_ADMIN_AJAX_DELETE_PRICE_GROUP_CONFIRM_TEXT);
    if(approval)
    {
        window.location = 'admin.php?page=car-rental-add-edit-price-group&noheader=true&delete_price_group=' + paramPriceGroupId;
    }
}

function getCarRentalPricePlans(paramAjaxSecurity, paramPriceGroupId, paramAjaxLoaderImageURL)
{
    if(paramPriceGroupId > 0)
    {
        jQuery('.price-group-html').html('<tr><td colspan="9"><img src="' + paramAjaxLoaderImageURL + '" class="price-group-loader" /></td></tr>');

        // WordPress admin Ajax, blog id (blog slug) is passed as url in ajaxurl, so we don't need to define it here
        var data = {
            'ajax_security': paramAjaxSecurity,
            'action': 'car_rental_admin_api',
            'extension': 'CarRental',
            'extension_action': 'price-plans',
            'price_group_id': paramPriceGroupId
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(ajaxurl, data, function(response)
        {
            if(response.error == 0)
            {

                jQuery('.price-group-html').html(response.message);
                jQuery('.price-group-loader').html('');
            } else
            {
                jQuery('.price-group-html').html('<tr><td colspan="9">' + carRentalLocale.NRS_ADMIN_AJAX_PRICE_PLANS_NOT_FOUND_TEXT + '</td></tr>');
            }
        }, "json");
    } else
    {
        jQuery('.price-group-html').html('<tr><td colspan="9">' + carRentalLocale.NRS_ADMIN_AJAX_PRICE_PLANS_PLEASE_SELECT_TEXT + '</td></tr>');
    }
}

function closeCarRentalOnSelectedDays(paramAjaxSecurity, paramLocationId, paramSelectedDatesArray)
{
    // WordPress admin Ajax, blog id (blog slug) is passed as url in ajaxurl, so we don't need to define it here
    var data = {
        'ajax_security': paramAjaxSecurity,
        'action': 'car_rental_admin_api',
        'extension': 'CarRental',
        'extension_action': 'closed-dates',
        'location_id': paramLocationId,
        'selected_dates': paramSelectedDatesArray
    };

    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(ajaxurl, data, function(response)
    {
        if(response.error == 0)
        {
            alert(carRentalLocale.NRS_ADMIN_AJAX_CLOSED_ON_SELECTED_DATES_TEXT);
        } else
        {
            alert(response.message);
        }
    }, "json");
}

function showCarRentalCalendar(paramCalendarId)
{
    var calendar = jQuery('.closed-dates-' + paramCalendarId);
    var selectedDates = jQuery('.selected-dates-' + paramCalendarId).val();
    var arrSelectedDates = selectedDates.split(',');
    //console.log('Dates: ' + selectedDates); console.log(arrSelectedDates);
    calendar.show();
    if(selectedDates.length > 0)
    {
        //console.log('Display with dates');
        calendar.multiDatesPicker(
        {
            dateFormat: "yy-mm-dd",
            numberOfMonths: [3,4],
            // Does not work even if we have more than one id. Will always do for the first one
            altField: '.selected-dates-' + paramCalendarId,
            addDates: arrSelectedDates,
            minDate: "-365D",
            maxDate: "+1095D"
        });
    } else
    {
        //console.log('Display without dates');
        calendar.multiDatesPicker(
        {
            dateFormat: "yy-mm-dd",
            numberOfMonths: [3,4],
            // Does not work even if we have more than one id. Will always do for the first one
            altField: '.selected-dates-' + paramCalendarId,
            minDate: "-365D",
            maxDate: "+1095D"
        });
    }
}

function previewCarRentalEmail(paramEmailId)
{
    window.open(
        'admin.php?page=car-rental-preview&email=' + paramEmailId,
        '_blank'
    );
}
function getCarRentalEmailContent(paramAjaxSecurity, paramEmailId)
{
    if(paramEmailId > 0)
    {
        // WordPress admin Ajax, blog id (blog slug) is passed as url in ajaxurl, so we don't need to define it here
        var data = {
            'ajax_security': paramAjaxSecurity,
            'action': 'car_rental_admin_api',
            'extension': 'CarRental',
            'extension_action': 'email',
            'email_id': paramEmailId
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        var objPreview, js, newClick;
        jQuery.post(ajaxurl, data, function(response)
        {
            if(response.error == 0)
            {
                //alert(response.message);
                jQuery('.email-settings-form input[name="update_email"]').removeAttr("disabled");
                jQuery('.email-settings-form .email-subject').val(response.email_subject);
                jQuery('.email-settings-form .email-body').val(response.email_body);

                // START: EMAIL PREVIEW
                objPreview = jQuery('.email-settings-form input[name="email_preview"]');
                objPreview.removeAttr("disabled");
                // create a function from the "js" string
                js = "previewCarRentalEmail(" + paramEmailId + ");";
                // create a function from the "js" string
                newClick = new Function(js);
                // clears onclick then sets click using jQuery
                objPreview.unbind('click');
                objPreview.bind('click', newClick);
                // END: EMAIL PREVIEW
            } else
            {
                alert(response.message);
                jQuery('.email-settings-form input[name="update_email"]').attr('disabled', true);
                jQuery('.email-settings-form .email-subject').val('');
                jQuery('.email-settings-form .email-body').val('');

                // START: EMAIL PREVIEW
                objPreview = jQuery('.email-settings-form input[name="email_preview"]');
                objPreview.attr('disabled', true);
                // clears onclick then sets click using jQuery
                objPreview.unbind('click');
                // END: EMAIL PREVIEW
            }
        }, "json");
    } else
    {
        jQuery('input[name="email_preview"]').attr('disabled', true);
        jQuery('input[name="update_email"]').attr('disabled', true);
        jQuery('.email-settings-form .email-subject').val('');
        jQuery('.email-settings-form .email-body').val('');
    }
}

function printCarRentalInvoicePopup(paramBookingId, paramBookingCode)
{
    var width = 900;
    var height = 650;
    var left = (screen.width - width)/2;
    var top = (screen.height - height)/2;
    var url = 'admin.php?page=car-rental-print-invoice&noheader=true' + '&booking_id=' + paramBookingId;
    var params = 'width=' + width + ', height=' + height;
    params += ', top=' + top + ', left=' + left;
    params += ', directories=no';
    params += ', location=no';
    params += ', menubar=no';
    params += ', resizable=no';
    params += ', scrollbars=yes';
    params += ', status=no';
    params += ', toolbar=no';
    var newWindow = window.open(url, carRentalLocale.NRS_ADMIN_AJAX_PRINT_INVOICE_POPUP_TITLE_TEXT + paramBookingCode, params);
    if (window.focus)
    {
        newWindow.focus();
    }
    return false;
}

function cancelCarRentalBooking(paramBookingId, paramBackToURLPart)
{
    var answer = confirm (carRentalLocale.NRS_ADMIN_AJAX_CANCEL_BOOKING_CONFIRM_TEXT);
    if (answer)
    {
        window.location = 'admin.php?page=car-rental-add-edit-booking&noheader=true&cancel_booking=' + paramBookingId + paramBackToURLPart;
    }
}

function deleteCarRentalBooking(paramBookingId, paramBackToURLPart)
{
    var answer = confirm (carRentalLocale.NRS_ADMIN_AJAX_DELETE_BOOKING_CONFIRM_TEXT);
    if (answer)
    {
        window.location = 'admin.php?page=car-rental-add-edit-booking&noheader=true&delete_booking=' + paramBookingId + paramBackToURLPart;
    }
}

function markPaidCarRentalBooking(paramBookingId, paramBackToURLPart)
{
    var answer = confirm (carRentalLocale.NRS_ADMIN_AJAX_MARK_PAID_BOOKING_TEXT);
    if (answer)
    {
        window.location = 'admin.php?page=car-rental-add-edit-booking&noheader=true&mark_paid_booking=' + paramBookingId + paramBackToURLPart;
    }
}

function markCarRentalCompletedEarly( paramBookingId, paramBackToURLPart)
{
    var answer = confirm (carRentalLocale.NRS_ADMIN_AJAX_MARK_COMPLETED_EARLY_CONFIRM_TEXT);
    if (answer)
    {
        window.location = 'admin.php?page=car-rental-add-edit-booking&noheader=true&mark_completed_early=' + paramBookingId + paramBackToURLPart;
    }
}
function refundCarRentalBooking(paramBookingId, paramBackToURLPart)
{
    var answer = confirm (carRentalLocale.NRS_ADMIN_AJAX_REFUND_BOOKING_CONFIRM_TEXT);
    if (answer)
    {
        window.location = 'admin.php?page=car-rental-add-edit-booking&noheader=true&refund_booking=' + paramBookingId + paramBackToURLPart;
    }
}