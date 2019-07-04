<?php
defined( "ABSPATH" ) or die( "No script kiddies, please!" );
?>
<h2 class="car-rental-page-title"><?php print($pageLabel); ?></h2>
<?php
if($pickupDateVisible || $returnDateVisible || $pickupLocationVisible || $returnLocationVisible):
    print('<div class="booking-data">');

    // Line 1
    if($pickupDateVisible || $returnDateVisible):
        print('<div class="booking-data-row">');
            print('<div class="booking-data-group">'.$objLang->getText('NRS_PERIOD_TEXT').'</div>');
            print('<div class="booking-data-group-items">');
                print('<div class="booking-data-group-item">');
                    print('<div class="booking-data-icon"><i class="fa fa-calendar"></i></div>');
                    print('<div class="booking-data-text">');
                        print($objSearch->getPrintPickupDate().' <strong>'.$objSearch->getPrintPickupTime().'</strong>');
                        if($pickupInAfterHours):
                            print(' ('.$objLang->getText('NRS_BOOKING_NIGHTLY_RATE_TEXT').')');
                        endif;
                    print('</div>');
                print('</div>');
                print('<div class="booking-data-group-item">');
                    print('<div class="booking-data-icon"><i class="fa fa-calendar"></i></div>');
                    print('<div class="booking-data-text">');
                        print($objSearch->getPrintReturnDate().' <strong>'.$objSearch->getPrintReturnTime().'</strong>');
                        if($returnInAfterHours):
                            print(' ('.$objLang->getText('NRS_BOOKING_NIGHTLY_RATE_TEXT').')');
                        endif;
                    print('</div>');
                print('</div>');
                print('<div class="booking-data-group-item">');
                    print('<div class="booking-data-icon"><i class="fa fa-clock-o"></i></div>');
                    print('<div class="booking-data-text">');
                        print('<strong>'.$objLang->getText('NRS_DURATION_TEXT').':</strong> '.$objSearch->getPrintBookingDuration());
                    print('</div>');
                print('</div>');
                if($pickup['print_full_address'] != "" || $return['print_full_address'] != ""):
                    print('<div class="booking-data-group-item">');
                    print($pickup['two_lines_address'] == TRUE || $return['two_lines_address'] == TRUE ? '<br />&nbsp;' : '&nbsp;');
                    print('</div>');
                endif;
                if($distance['print_distance'] != ""):
                    print('<div class="booking-data-group-item">&nbsp;</div>');
                endif;
                if($pickup['lunch_enabled'] || $return['lunch_enabled']):
                    print('<div class="booking-data-group-item">&nbsp;</div>');
                endif;
            print('</div>');
        print('</div>');
    else:
        print('<div class="booking-data-row">&nbsp;</div>');
    endif;

    if($pickupLocationVisible && $objSearch->getPickupLocationId() > 0):
        print('<div class="booking-data-row">');
            print('<div class="booking-data-group">'.$objLang->getText('NRS_BOOKING_PICKUP_TEXT').':</div>');
            print('<div class="booking-data-group-items">');
                print('<div class="booking-data-group-item">');
                    print('<div class="booking-data-icon"><i class="fa fa-map-marker"></i></div>');
                    print('<div class="booking-data-text">'.$pickup['print_translated_location_name'].'</div>');
                print('</div>');
                if($pickup['print_full_address'] != ""):
                    // If pickup address is set
                    print('<div class="booking-data-group-item">');
                        print('<div class="booking-data-icon"><i class="fa fa-map-signs"></i></div>');
                        print('<div class="booking-data-text">'.$pickup['print_full_address'].'</div>');
                    print('</div>');
                elseif($return['print_full_address'] != ""):
                    // If there is a return address set, then add a blank line
                    print('<div class="booking-data-group-item">'.($return['two_lines_address'] == TRUE ? '<br />&nbsp;' : '&nbsp;').'</div>');
                endif;
                if($distance['print_distance'] != ""):
                    print('<div class="booking-data-group-item">&nbsp;</div>');
                endif;
                print('<div class="booking-data-group-item">');
                    print('<div class="booking-data-icon"><i class="fa fa-clock-o"></i></div>');
                    print('<div class="booking-data-text">');
                        print('<strong>'.$objLang->getText('NRS_BOOKING_BUSINESS_HOURS_TEXT').':</strong> '.$pickup['print_open_hours']);
                    print('</div>');
                print('</div>');
                if($pickup['lunch_enabled']):
                    print('<div class="booking-data-group-item">');
                        print('<div class="booking-data-icon"><i class="fa fa-cutlery"></i></div>');
                        print('<div class="booking-data-text">');
                            print('<strong>'.$objLang->getText('NRS_LUNCH_TIME_TEXT').':</strong> '.$pickup['print_lunch_hours']);
                        print('</div>');
                    print('</div>');
                elseif($return['lunch_enabled']):
                    print('<div class="booking-data-group-item">&nbsp;</div>');
                endif;
                print('<div class="booking-data-group-item">');
                    print('<div class="booking-data-icon"><i class="fa fa-money"></i></div>');
                    print('<div class="booking-data-text">');
                        print('<strong>'.$objLang->getText('NRS_BOOKING_FEE_TEXT').':</strong> ');
                        print('<span title="'.$pickup['print_current_pickup_fee_details'].'">');
                            print($pickup['unit_print']['pickup_fee_dynamic']);
                        print('</span>');
                    print('</div>');
                print('</div>');
            print('</div>');
        print('</div>');
    else:
        print('<div class="booking-data-row">&nbsp;</div>');
    endif;

    if($returnLocationVisible && $objSearch->getReturnLocationId() > 0):
        print('<div class="booking-data-row">');
            print('<div class="booking-data-group">'.$objLang->getText('NRS_BOOKING_RETURN_TEXT').':</div>');
            print('<div class="booking-data-group-items">');
                print('<div class="booking-data-group-item">');
                    print('<div class="booking-data-icon"><i class="fa fa-map-marker"></i></div>');
                    print('<div class="booking-data-text">'.$return['print_translated_location_name'].'</div>');
                print('</div>');
                if($return['print_full_address'] != ""):
                    // If pickup address is set
                    print('<div class="booking-data-group-item">');
                    print('<div class="booking-data-icon"><i class="fa fa-map-signs"></i></div>');
                    print('<div class="booking-data-text">'.$return['print_full_address'].'</div>');
                    print('</div>');
                elseif($pickup['print_full_address'] != ""):
                    // If there is a pickup address set, then add a blank line
                    print('<div class="booking-data-group-item">'.($pickup['two_lines_address'] == TRUE ? '<br />&nbsp;' : '&nbsp;').'</div>');
                endif;
                if($distance['print_distance'] != ""):
                    print('<div class="booking-data-group-item">');
                        print('<div class="booking-data-icon"><i class="fa fa-location-arrow"></i></div>');
                        print('<div class="booking-data-text">');
                            printf($objLang->getText('NRS_DISTANCE_AWAY_TEXT'), $distance['print_distance']);
                        print('</div>');
                    print('</div>');
                endif;
                print('<div class="booking-data-group-item">');
                    print('<div class="booking-data-icon"><i class="fa fa-clock-o"></i></div>');
                    print('<div class="booking-data-text">');
                        print('<strong>'.$objLang->getText('NRS_BOOKING_BUSINESS_HOURS_TEXT').':</strong> '.$return['print_open_hours']);
                    print('</div>');
                print('</div>');
                if($return['lunch_enabled']):
                    print('<div class="booking-data-group-item">');
                        print('<div class="booking-data-icon"><i class="fa fa-cutlery"></i></div>');
                        print('<div class="booking-data-text">');
                            print('<strong>'.$objLang->getText('NRS_LUNCH_TIME_TEXT').':</strong> '.$return['print_lunch_hours']);
                        print('</div>');
                    print('</div>');
                elseif($pickup['lunch_enabled']):
                    print('<div class="booking-data-group-item">&nbsp;</div>');
                endif;
                print('<div class="booking-data-group-item">');
                    print('<div class="booking-data-icon"><i class="fa fa-money"></i></div>');
                    print('<div class="booking-data-text">');
                        print('<strong>'.$objLang->getText('NRS_BOOKING_FEE_TEXT').':</strong> ');
                        print('<span title="'.$return['print_current_return_with_distance_fee_details'].'">');
                            print($return['unit_print']['return_with_distance_fee_dynamic']);
                        print('</span>');
                    print('</div>');
                print('</div>');
            print('</div>');
        print('</div>');
    else:
        print('<div class="booking-data-row">&nbsp;</div>');
    endif;

    // Line 2
    if($pickupLocationVisible || $returnLocationVisible):
        print('<div class="booking-data-row-wide">&nbsp;</div>');

        if($pickupLocationVisible && $pickupIsWorkingInAfterHours === TRUE):
            print('<div class="booking-data-row-wide">');
                print('<div class="booking-data-group">'.$objLang->getText('NRS_BOOKING_AFTERHOURS_PICKUP_TEXT').':</div>');
                print('<div class="booking-data-group-items">');
                    print('<div class="booking-data-group-item">');
                        print('<div class="booking-data-icon"><i class="fa fa-map-marker"></i></div>');
                        print('<div class="booking-data-text">'.$afterHoursPickupDetails['print_translated_location_name'].'</div>');
                    print('</div>');
                    if($afterHoursPickupDetails['print_full_address'] != ""):
                        // If after hours pickup address is set
                        print('<div class="booking-data-group-item">');
                        print('<div class="booking-data-icon"><i class="fa fa-map-signs"></i></div>');
                        print('<div class="booking-data-text">'.$afterHoursPickupDetails['print_full_address'].'</div>');
                        print('</div>');
                    elseif(
                        $returnIsWorkingInAfterHours === TRUE &&
                        $afterHoursReturnDetails['print_full_address'] != ""
                    ):
                        // If there is a after hours return address set, then add a blank line
                        print('<div class="booking-data-group-item">'.($afterHoursReturnDetails['two_lines_address'] == TRUE ? '<br />&nbsp;' : '&nbsp;').'</div>');
                    endif;
                    print('<div class="booking-data-group-item">');
                        print('<div class="booking-data-icon"><i class="fa fa-clock-o"></i></div>');
                        print('<div class="booking-data-text">');
                            print('<strong>'.$objLang->getText('NRS_TIME_TEXT').':</strong> '.$afterHoursPickupDetails['print_open_hours']);
                        print('</div>');
                    print('</div>');
                    if($afterHoursPickupDetails['lunch_enabled']):
                        print('<div class="booking-data-group-item">');
                            print('<div class="booking-data-icon"><i class="fa fa-cutlery"></i></div>');
                            print('<div class="booking-data-text">');
                                print('<strong>'.$objLang->getText('NRS_LUNCH_TIME_TEXT').':</strong> '.$afterHoursPickupDetails['print_lunch_hours']);
                            print('</div>');
                        print('</div>');
                    elseif($afterHoursReturnDetails['lunch_enabled']):
                        print('<div class="booking-data-group-item">&nbsp;</div>');
                    endif;
                    print('<div class="booking-data-group-item">');
                        print('<div class="booking-data-icon"><i class="fa fa-money"></i></div>');
                        print('<div class="booking-data-text">');
                            print('<strong>'.$objLang->getText('NRS_BOOKING_FEE_TEXT').':</strong> ');
                            print('<span title="'.$pickup['print_current_pickup_fee_details'].'">');
                                print($pickup['unit_print']['afterhours_pickup_fee_dynamic']);
                            print('</span>');
                        print('</div>');
                    print('</div>');
                print('</div>');
            print('</div>');
        else:
            print('<div class="booking-data-row-wide">&nbsp;</div>');
        endif;

        if($returnLocationVisible && $returnIsWorkingInAfterHours === TRUE):
            print('<div class="booking-data-row-wide">');
                print('<div class="booking-data-group">'.$objLang->getText('NRS_BOOKING_AFTERHOURS_RETURN_TEXT').':</div>');
                print('<div class="booking-data-group-items">');
                    print('<div class="booking-data-group-item">');
                        print('<div class="booking-data-icon"><i class="fa fa-map-marker"></i></div>');
                        print('<div class="booking-data-text">'.$afterHoursReturnDetails['print_translated_location_name'].'</div>');
                    print('</div>');
                    if($afterHoursReturnDetails['print_full_address'] != ""):
                        // If after hours pickup address is set
                        print('<div class="booking-data-group-item">');
                        print('<div class="booking-data-icon"><i class="fa fa-map-signs"></i></div>');
                        print('<div class="booking-data-text">'.$afterHoursReturnDetails['print_full_address'].'</div>');
                        print('</div>');
                    elseif(
                        $pickupIsWorkingInAfterHours === TRUE &&
                        $afterHoursPickupDetails['print_full_address'] != ""
                    ):
                        // If there is a after hours return address set, then add a blank line
                        print('<div class="booking-data-group-item">'.($afterHoursPickupDetails['two_lines_address'] == TRUE ? '<br />&nbsp;' : '&nbsp;').'</div>');
                    endif;
                    print('<div class="booking-data-group-item">');
                        print('<div class="booking-data-icon"><i class="fa fa-clock-o"></i></div>');
                        print('<div class="booking-data-text">');
                            print('<strong>'.$objLang->getText('NRS_TIME_TEXT').':</strong> '.$afterHoursReturnDetails['print_open_hours']);
                        print('</div>');
                    print('</div>');
                    if($afterHoursReturnDetails['lunch_enabled']):
                        print('<div class="booking-data-group-item">');
                            print('<div class="booking-data-icon"><i class="fa fa-cutlery"></i></div>');
                            print('<div class="booking-data-text">');
                                print('<strong>'.$objLang->getText('NRS_LUNCH_TIME_TEXT').':</strong> '.$afterHoursReturnDetails['print_lunch_hours']);
                            print('</div>');
                        print('</div>');
                    elseif($afterHoursPickupDetails['lunch_enabled']):
                        print('<div class="booking-data-group-item">&nbsp;</div>');
                    endif;
                    print('<div class="booking-data-group-item">');
                        print('<div class="booking-data-icon"><i class="fa fa-money"></i></div>');
                        print('<div class="booking-data-text">');
                            print('<strong>'.$objLang->getText('NRS_BOOKING_FEE_TEXT').':</strong> ');
                              print('<span title="'.$return['print_current_return_with_distance_fee_details'].'">');
                                print($return['unit_print']['afterhours_return_with_distance_fee_dynamic']);
                            print('</span>');
                        print('</div>');
                    print('</div>');
                print('</div>');
            print('</div>');
        else:
            print('<div class="booking-data-row-wide">&nbsp;</div>');
        endif;
    endif;

    print('</div>');
endif;