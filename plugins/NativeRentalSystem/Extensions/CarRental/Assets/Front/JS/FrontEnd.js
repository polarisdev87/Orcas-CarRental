/**
 * Car Rental System Front End JS
 * License: Licensed under the AGPL license.
 */
function carRentalCustomerDetailsLookup(paramExtension, paramAjaxSecurity, paramSiteURL, paramEmailAddress, paramYearOfBirth)
{
    var data = {
        'ajax_security': paramAjaxSecurity,
        '__car_rental_api': 1,
        'extension': paramExtension,
        'extension_action': 'customer-lookup',
        'email': paramEmailAddress,
        'year': paramYearOfBirth
    };

    jQuery.get(paramSiteURL, data, function (response)
    {
        if (response.error == 0)
        {
            jQuery('.car-rental-customer-form .title').html(response.title);
            jQuery('.car-rental-customer-form .first-name').val(response.first_name);
            jQuery('.car-rental-customer-form .last-name').val(response.last_name);
            jQuery('.car-rental-customer-form .birth-year').val(response.birth_year);
            jQuery('.car-rental-customer-form .birth-month').val(response.birth_month);
            jQuery('.car-rental-customer-form .birth-day').val(response.birth_day);
            jQuery('.car-rental-customer-form .street-address').val(response.street_address);
            jQuery('.car-rental-customer-form .city').val(response.city);
            jQuery('.car-rental-customer-form .state').val(response.state);
            jQuery('.car-rental-customer-form .zip-code').val(response.zip_code);
            jQuery('.car-rental-customer-form .country').val(response.country);
            jQuery('.car-rental-customer-form .phone').val(response.phone);
            jQuery('.car-rental-customer-form .email').val(response.email);
            jQuery('.car-rental-customer-form .comments').val(response.comments);
            jQuery('.car-rental-booking-details .ajax-loader').html("");
        } else
        {
            alert(response.message);
            jQuery('.car-rental-customer-form .first-name').val('');
            jQuery('.car-rental-customer-form .last-name').val('');
            jQuery('.car-rental-customer-form .birth-year').val('');
            jQuery('.car-rental-customer-form .birth-month').val('');
            jQuery('.car-rental-customer-form .birth-day').val('');
            jQuery('.car-rental-customer-form .street-address').val('');
            jQuery('.car-rental-customer-form .city').val('');
            jQuery('.car-rental-customer-form .state').val('');
            jQuery('.car-rental-customer-form .zip_code').val('');
            jQuery('.car-rental-customer-form .country').val('');
            jQuery('.car-rental-customer-form .phone').val('');
            jQuery('.car-rental-customer-form .email').val('');
            jQuery('.car-rental-customer-form .comments').val('');
            jQuery('.car-rental-booking-details .ajax-loader').html("");
        }
    }, "json");
}
function updateCarRentalOutput(defaultOptionId, value, outputField, optionField, dataList)
{
    jQuery('#'+outputField).val(value);
    var newSelectedOption = defaultOptionId;
    var objSelectedOption = jQuery('#'+dataList+' option').filter(function()
    {
        return jQuery(this).text() === value
    });
    if(objSelectedOption.length)
    {
        // Match existing element
        newSelectedOption = objSelectedOption.val();
    }
    jQuery('#'+optionField).val(newSelectedOption);
    //alert(newSelectedOption);
}