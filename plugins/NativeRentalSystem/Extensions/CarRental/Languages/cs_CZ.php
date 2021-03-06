<?php
/**
 * Language specific file
 * @Language - Czech
 * @Author - Lukas Smrcek
 * @Email - lukas.smrcek@tedkup.cz
 * @Website - http://tedkup.cz
 */
// Settings
$lang['LTR'] = FALSE;
$lang['NRS_RECAPTCHA_LANG'] = 'cs';

// Roles
$lang['NRS_PARTNER_ROLE_NAME_TEXT'] = 'Auto partner';
$lang['NRS_ASSISTANT_ROLE_NAME_TEXT'] = 'Auto asistent';
$lang['NRS_MANAGER_ROLE_NAME_TEXT'] = 'Auto manager';

// Admin - Ajax
$lang['NRS_ADMIN_AJAX_DELETE_FEATURE_CONFIRM_TEXT'] = 'Do you really want to delete this feature?';
$lang['NRS_ADMIN_AJAX_DELETE_BENEFIT_CONFIRM_TEXT'] = 'Do you really want to delete this benefit?';
$lang['NRS_ADMIN_AJAX_DELETE_CUSTOMER_CONFIRM_TEXT'] = 'Do you really want to delete this customer? Remember that all reservations made by this customer, invoices and all reserved cars will also be deleted.';
$lang['NRS_ADMIN_AJAX_DELETE_TRANSMISSION_TYPE_TEXT'] = 'Do you really want to delete this transmission type? Remember that cars, using this transmission type and related reservations also will be deleted.';
$lang['NRS_ADMIN_AJAX_DELETE_MANUFACTURER_CONFIRM_TEXT'] = 'Do you really want to delete this manufacturer? Remember that cars, made by this manufacturer and related reservations also will be deleted.';
$lang['NRS_ADMIN_AJAX_DELETE_ITEM_CONFIRM_TEXT'] = 'Do you want to delete this car? Remember that all corresponding reservations will also be deleted.';
$lang['NRS_ADMIN_AJAX_DELETE_FUEL_TYPE_CONFIRM_TEXT'] = 'Do you really want to delete this fuel type? Remember that cars, using this fuel type and related reservations also will be deleted.';
$lang['NRS_ADMIN_AJAX_DELETE_BODY_TYPE_CONFIRM_TEXT'] = 'Do you really want to delete this body type? Remember that cars, using this body type and related reservations also will be deleted.';
$lang['NRS_ADMIN_AJAX_DELETE_EXTRA_CONFIRM_TEXT'] = 'Do you want to delete this extra? Remember that all corresponding discounts will also be deleted.';
$lang['NRS_ADMIN_AJAX_DELETE_LOCATION_CONFIRM_TEXT'] = 'Do you want to delete this location? All related distances and upcoming reservations from/to this location will be deleted. Cars won\'t be deleted, just locations will be unassigned from them .';
$lang['NRS_ADMIN_AJAX_DELETE_DISTANCE_CONFIRM_TEXT'] = 'Do you want to delete this distance?';
$lang['NRS_ADMIN_AJAX_DELETE_PRICE_GROUP_CONFIRM_TEXT'] = 'Do you really want to delete this price group? Remember that all price plans attached to this price group will also be deleted.';
$lang['NRS_ADMIN_AJAX_PRICE_PLANS_NOT_FOUND_TEXT'] = 'No available price plans found!';
$lang['NRS_ADMIN_AJAX_PRICE_PLANS_PLEASE_SELECT_TEXT'] = 'Please select a price group first!';
$lang['NRS_ADMIN_AJAX_CLOSED_ON_SELECTED_DATES_TEXT'] = 'All or specified location were closed on selected dates!';
$lang['NRS_ADMIN_AJAX_PRINT_INVOICE_POPUP_TITLE_TEXT'] = 'Invoice #';
$lang['NRS_ADMIN_AJAX_CANCEL_BOOKING_CONFIRM_TEXT'] = 'Are you sure that you want to cancel this reservation?';
$lang['NRS_ADMIN_AJAX_DELETE_BOOKING_CONFIRM_TEXT'] = 'Are you sure that you want to delete this reservation? Remember once reservation deleted, it will be deleted forever from your database.';
$lang['NRS_ADMIN_AJAX_MARK_PAID_BOOKING_TEXT'] = 'Are you sure that customer paid for this reservation?';
$lang['NRS_ADMIN_AJAX_MARK_COMPLETED_EARLY_CONFIRM_TEXT'] = 'Are you sure that you want to mark this reservation as completed right now?';
$lang['NRS_ADMIN_AJAX_REFUND_BOOKING_CONFIRM_TEXT'] = 'Are you sure that you want to refund this reservation to customer? Remember that you will have to send payment refund manually to the customer.';
$lang['NRS_ADMIN_AJAX_EMAIL_DOES_NOT_EXIST_ERROR_TEXT'] = 'Sorry, no email found for this id.';
$lang['NRS_ADMIN_AJAX_CLOSE_DATE_ACCESS_ERROR_TEXT'] = 'Sorry, you are not allowed to manage locations.';
$lang['NRS_ADMIN_AJAX_PRICE_PLAN_DOES_NOT_EXIST_ERROR_TEXT'] = 'Sorry, no data found.';
$lang['NRS_ADMIN_AJAX_UNKNOWN_ERROR_TEXT'] = 'Unknown error.';

// Admin - Global
$lang['NRS_ADMIN_VIEW_DETAILS_TEXT'] = 'Zobrazit detaily';
$lang['NRS_ADMIN_VIEW_BOOKINGS_TEXT'] = 'Zobrazit rezervace';
$lang['NRS_ADMIN_VIEW_UNPAID_BOOKINGS_TEXT'] = 'Zobrazit nezaplacen?? rezervace';
$lang['NRS_ADMIN_NO_BOOKINGS_YET_TEXT'] = '????dn?? rezervace';
$lang['NRS_ADMIN_BOOKING_DETAILS_TEXT'] = 'Detaily rezervace';
$lang['NRS_ADMIN_CUSTOMER_DETAILS_FROM_DB_TEXT'] = 'Detaily z??kazn??ka (Posledn?? verze z datab??ze)';
$lang['NRS_ADMIN_BOOKING_STATUS_TEXT'] = 'Status rezervace';
$lang['NRS_ADMIN_BOOKING_STATUS_UPCOMING_TEXT'] = 'P????choz??';
$lang['NRS_ADMIN_BOOKING_STATUS_DEPARTED_TEXT'] = 'Odchoz??';
$lang['NRS_ADMIN_BOOKING_STATUS_COMPLETED_EARLY_TEXT'] = 'Ned??vno dokon??en??';
$lang['NRS_ADMIN_BOOKING_STATUS_COMPLETED_TEXT'] = 'Dokon??en??';
$lang['NRS_ADMIN_BOOKING_STATUS_ACTIVE_TEXT'] = 'Aktivn??';
$lang['NRS_ADMIN_BOOKING_STATUS_CANCELLED_TEXT'] = 'Zru??en??';
$lang['NRS_ADMIN_BOOKING_STATUS_PAID_TEXT'] = 'Zaplacen??';
$lang['NRS_ADMIN_BOOKING_STATUS_UNPAID_TEXT'] = 'Nezaplacen??';
$lang['NRS_ADMIN_BOOKING_STATUS_REFUNDED_TEXT'] = 'Vr??cen??';
$lang['NRS_ADMIN_PRINT_INVOICE_TEXT'] = 'Tisk faktury';
$lang['NRS_ADMIN_BACK_TO_CUSTOMER_BOOKING_LIST_TEXT'] = 'Zp??t k z??kazn??kovi&#39;s Seznam rezervac??';
$lang['NRS_ADMIN_CUSTOMERS_BY_LAST_VISIT_TEXT'] = 'Z??kazn??ci dle posledn?? n??v??t??vy';
$lang['NRS_ADMIN_CUSTOMERS_BY_REGISTRATION_TEXT'] = 'Z??kazn??ci dle rezervace';
$lang['NRS_ADMIN_BOOKINGS_PERIOD_FROM_TO_TEXT'] = 'D??lka rezervace: %s - %s';
$lang['NRS_ADMIN_PICKUPS_PERIOD_FROM_TO_TEXT'] = 'Vyzvednut?? - obdob??: %s - %s';
$lang['NRS_ADMIN_RETURNS_PERIOD_FROM_TO_TEXT'] = 'Vr??cen?? obdob??: %s - %s';
$lang['NRS_ADMIN_UPCOMING_TEXT'] = 'Nadch??zej??c??';
$lang['NRS_ADMIN_PAST_TEXT'] = 'Minul??';
$lang['NRS_ADMIN_CUSTOMER_BOOKINGS_TEXT'] = 'Rezervace z??kazn??k??';
$lang['NRS_ADMIN_BOOKINGS_BY_TEXT'] = 'Rezervace dle %s';
$lang['NRS_ADMIN_ALL_BOOKINGS_TEXT'] = 'V??echny rezervace';
$lang['NRS_ADMIN_ALL_PICKUPS_TEXT'] = 'V??echny vyzvednut??';
$lang['NRS_ADMIN_ALL_RETURNS_TEXT'] = 'V??echny vr??cen??';
$lang['NRS_ADMIN_MAX_ITEM_UNITS_PER_BOOKING_TEXT'] = 'Maximum vozidel pro rezervaci';
$lang['NRS_ADMIN_TOTAL_ITEM_UNITS_IN_STOCK_TEXT'] = 'V??echna vozidla v gar????i';
$lang['NRS_ADMIN_MAX_EXTRA_UNITS_PER_BOOKING_TEXT'] = 'Maximum dopl??k?? na rezervaci';
$lang['NRS_ADMIN_TOTAL_EXTRA_UNITS_IN_STOCK_TEXT'] = 'V??echny dopl??ky na sklad??';
$lang['NRS_ADMIN_ITEM_PRICES_TEXT'] = 'Ceny aut';
$lang['NRS_ADMIN_ITEM_DEPOSITS_TEXT'] = 'Ceny kauce';
$lang['NRS_ADMIN_EXTRA_PRICES_TEXT'] = 'Ceny dopl??k??';
$lang['NRS_ADMIN_EXTRA_DEPOSITS_TEXT'] = 'Kauce dopl??k??';
$lang['NRS_ADMIN_PICKUP_FEES_TEXT'] = 'Poplatek za vyzvedut??';
$lang['NRS_ADMIN_DISTANCE_FEES_TEXT'] = 'Poplatek za p??evoz vozidla';
$lang['NRS_ADMIN_RETURN_FEES_TEXT'] = 'Poplatek za vr??cen??';
$lang['NRS_ADMIN_REGULAR_PRICE_TEXT'] = 'Cena p??j??en??';
$lang['NRS_ADMIN_PRICE_TYPE_TEXT'] = 'Typ ceny';
$lang['NRS_ADMIN_ON_THE_LEFT_TEXT'] = 'Na lev?? stran??';
$lang['NRS_ADMIN_ON_THE_RIGHT_TEXT'] = 'Na prav?? stran??';
$lang['NRS_ADMIN_LOAD_FROM_OTHER_PLACE_TEXT'] = 'Na??ten?? z jin??ho m??sta';
$lang['NRS_ADMIN_LOAD_FROM_PLUGIN_TEXT'] = 'Na??ten?? z pluginu';
$lang['NRS_ADMIN_EMAIL_TEXT'] = 'E-mail';
$lang['NRS_ADMIN_PUBLIC_TEXT'] = 'Ve??ejn??';
$lang['NRS_ADMIN_PRIVATE_TEXT'] = 'Skryt??';
$lang['NRS_ADMIN_CALENDAR_NO_CALENDARS_FOUND_TEXT'] = 'Nenalezen ????dn?? kalend???? pro vybran?? rozsah data';
$lang['NRS_ADMIN_CHOOSE_PAGE_TEXT'] = ' - Vyberte str??nku - ';
$lang['NRS_ADMIN_SELECT_EMAIL_TYPE_TEXT'] = '--- Vyberte typ e-mailu ---';
$lang['NRS_ADMIN_TOTAL_REQUESTS_LEFT_TEXT'] = 'Celkov?? po??et obdr??en??ch ????dost??';
$lang['NRS_ADMIN_FAILED_REQUESTS_LEFT_TEXT'] = 'Po??et chybn??ch ????dost??';
$lang['NRS_ADMIN_EMAIL_ATTEMPTS_LEFT_TEXT'] = 'Po??et pokus?? p??es e-mail';

// Admin Menu
$lang['NRS_ADMIN_MENU_EXTENSION_PAGE_TITLE_TEXT'] = 'Syst??m autop??j??ovny';
$lang['NRS_ADMIN_MENU_EXTENSION_LABEL_TEXT'] = 'Autop??j??ovna';
$lang['NRS_ADMIN_MENU_SYSTEM_UPDATE_TEXT'] = 'Aktualizace syst??mu';
$lang['NRS_ADMIN_MENU_NETWORK_UPDATE_TEXT'] = 'Aktualizace s??t??';
// Admin Menu - Benefit Manager
$lang['NRS_ADMIN_MENU_BENEFIT_MANAGER_TEXT'] = 'Mana??er benefit??';
$lang['NRS_ADMIN_MENU_ADD_EDIT_BENEFIT_TEXT'] = 'P??idat / Upravit Benefity';
// Admin Menu - Item Manager
$lang['NRS_ADMIN_MENU_ITEM_MANAGER_TEXT'] = 'Auto - mana??er';
$lang['NRS_ADMIN_MENU_ADD_EDIT_ITEM_TEXT'] = 'P??idat / Upravit Auta';
$lang['NRS_ADMIN_MENU_ADD_EDIT_MANUFACTURER_TEXT'] = 'P??idat / Upravit V??robce';
$lang['NRS_ADMIN_MENU_ADD_EDIT_BODY_TYPE_TEXT'] = 'P??idat / Upravit Karos??rii';
$lang['NRS_ADMIN_MENU_ADD_EDIT_FUEL_TYPE_TEXT'] = 'P??idat / Upravit Typ paliva';
$lang['NRS_ADMIN_MENU_ADD_EDIT_TRANSMISSION_TYPE_TEXT'] = 'P??idat / Upravit Typ p??evodovky';
$lang['NRS_ADMIN_MENU_ADD_EDIT_FEATURE_TEXT'] = 'P??idat / Upravit Vlastnosti';
$lang['NRS_ADMIN_MENU_ADD_EDIT_ITEM_OPTION_TEXT'] = 'P??idat / Upravit Mo??nosti voz??';
$lang['NRS_ADMIN_MENU_BLOCK_ITEM_TEXT'] = 'Blokovat auto';
// Admin Menu - Item Prices
$lang['NRS_ADMIN_MENU_ITEM_PRICES_TEXT'] = 'Ceny auta';
$lang['NRS_ADMIN_MENU_ADD_EDIT_PRICE_GROUP_TEXT'] = 'P??idat / Upravit Cenu skupiny';
$lang['NRS_ADMIN_MENU_ADD_EDIT_PRICE_PLAN_TEXT'] = 'P??idat / Upravit Cenov?? pl??n vozu';
$lang['NRS_ADMIN_MENU_ADD_EDIT_ITEM_DISCOUNT_TEXT'] = 'P??idat / Upravit Slevu pro auto';
// Admin Menu - Extras Manager
$lang['NRS_ADMIN_MENU_EXTRAS_MANAGER_TEXT'] = 'Dopl??ky - mana??er';
$lang['NRS_ADMIN_MENU_ADD_EDIT_EXTRA_TEXT'] = 'P??idat / Upravit Dopl??ky';
$lang['NRS_ADMIN_MENU_ADD_EDIT_EXTRA_OPTION_TEXT'] = 'P??idat / Upravit Mo??nosti dopl??k??';
$lang['NRS_ADMIN_MENU_ADD_EDIT_EXTRA_DISCOUNT_TEXT'] = 'P??idat / Upravit Slevu dopl??k??';
$lang['NRS_ADMIN_MENU_BLOCK_EXTRA_TEXT'] = 'Blokovat dopl??ek';
// Admin Menu - Location Manager
$lang['NRS_ADMIN_MENU_LOCATION_MANAGER_TEXT'] = 'Um??st??n?? - mana??er';
$lang['NRS_ADMIN_MENU_ADD_EDIT_LOCATION_TEXT'] = 'P??idat / Upravit Um??st??n??';
$lang['NRS_ADMIN_MENU_ADD_EDIT_DISTANCE_TEXT'] = 'P??idat / Upravit Vzd??lenost';
// Admin Menu - Reservation Manager
$lang['NRS_ADMIN_MENU_BOOKING_MANAGER_TEXT'] = 'Rezervace - mana??er';
$lang['NRS_ADMIN_MENU_BOOKING_SEARCH_RESULTS_TEXT'] = 'Rezervace vyhled??v??n?? v??sledk??';
$lang['NRS_ADMIN_MENU_ITEM_CALENDAR_SEARCH_RESULTS_TEXT'] = 'Kalend???? voz?? - vyhled??v??n?? v??sledk??';
$lang['NRS_ADMIN_MENU_EXTRAS_CALENDAR_SEARCH_TEXT'] = 'Kalend???? dopl??k?? - vyhled??v??n?? v??sledk??';
$lang['NRS_ADMIN_MENU_CUSTOMER_SEARCH_RESULTS_TEXT'] = 'Z??kazn??ci - vyhled??v??n?? v??sledk??';
$lang['NRS_ADMIN_MENU_ADD_EDIT_CUSTOMER_TEXT'] = 'P??idat / Upravit Z??kazn??ka';
$lang['NRS_ADMIN_MENU_ADD_EDIT_BOOKING_TEXT'] = 'P??idat / Upravit Rezervaci';
$lang['NRS_ADMIN_MENU_VIEW_DETAILS_TEXT'] = 'Zobrazit detaily rezervace';
$lang['NRS_ADMIN_MENU_PRINT_INVOICE_TEXT'] = 'Print Invoice';
// Admin Menu - Payments & Taxes
$lang['NRS_ADMIN_MENU_PAYMENTS_AND_TAXES_TEXT'] = 'Platby &amp; Dan??';
$lang['NRS_ADMIN_MENU_ADD_EDIT_PAYMENT_METHOD_TEXT'] = 'P??idat / Upravit Zp??sob platby';
$lang['NRS_ADMIN_MENU_ADD_EDIT_PREPAYMENT_TEXT'] = 'P??idat / Upravit P??edplacen??';
$lang['NRS_ADMIN_MENU_ADD_EDIT_TAX_TEXT'] = 'P??idat / Upravit Da??';
// Admin Menu - Settings
$lang['NRS_ADMIN_MENU_SINGLE_MANAGER_TEXT'] = 'Nastaven??';
$lang['NRS_ADMIN_MENU_ADD_EDIT_GLOBAL_SETTINGS_TEXT'] = 'P??idat / Upravit Hlavn?? nastaven??';
$lang['NRS_ADMIN_MENU_ADD_EDIT_CUSTOMER_SETTINGS_TEXT'] = 'P??idat / Upravit Nastaven?? z??kazn??ka';
$lang['NRS_ADMIN_MENU_ADD_EDIT_SEARCH_SETTINGS_TEXT'] = 'P??idat / Upravit Nastaven?? vyhled??v??n??';
$lang['NRS_ADMIN_MENU_ADD_EDIT_PRICE_SETTINGS_TEXT'] = 'P??idat / Upravit Nastaven?? ceny';
$lang['NRS_ADMIN_MENU_ADD_EDIT_EMAIL_TEXT'] = 'P??idat / Upravit E-mail';
$lang['NRS_ADMIN_MENU_IMPORT_DEMO_TEXT'] = 'Importovat nastaven?? DEMO ';
$lang['NRS_ADMIN_MENU_CONTENT_PREVIEW_TEXT'] = 'Zobrazen?? obsahu ';
// Admin Menu - Instructions
$lang['NRS_ADMIN_MENU_INSTRUCTIONS_TEXT'] = 'Instrukce';
// Admin Menu - Network Manager
$lang['NRS_ADMIN_MENU_NETWORK_MANAGER_TEXT'] = 'S???? - mana??er';

// Admin Pages Post Type
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_NAME_TEXT'] = 'Autop??j??ovna str??nka'; // name
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_SINGULAR_NAME_TEXT'] = 'Autop??j??ovna str??nky'; // singular_name
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_MENU_NAME_TEXT'] = 'Autop??j??ovna str??nky'; // menu_name
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_PARENT_PAGE_COLON_TEXT'] = 'Z??kladn?? informace o autu'; // parent_item_colon
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_ALL_PAGES_TEXT'] = 'Informace o v??ech autech'; // all_items
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_VIEW_PAGE_TEXT'] = 'Zobrazit informace o autech'; // view_item
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_ADD_NEW_PAGE_TEXT'] = 'P??idat nov?? auto informa??n?? str??nka'; // add_new_item
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_ADD_NEW_TEXT'] = 'P??idat novou str??nku'; // add_new
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_EDIT_PAGE_TEXT'] = 'Upravit informace o autu'; // edit_item
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_UPDATE_PAGE_TEXT'] = 'Aktualizovat str??nku o autech'; // update_item
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_SEARCH_PAGES_TEXT'] = 'Vyhled??vat informace o autech'; // search_items
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_NOT_FOUND_TEXT'] = 'Nenalezeno'; // not_found
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_NOT_FOUND_IN_TRASH_TEXT'] = 'Nenalezeno v ko??i'; // not_found_in_trash
$lang['NRS_ADMIN_PAGE_POST_TYPE_DESCRIPTION_TEXT'] = 'Seznam aut - info';

// Admin Item Post Type
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_NAME_TEXT'] = 'Str??nka aut'; // name
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_SINGULAR_NAME_TEXT'] = 'Str??nky aut'; // singular_name
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_MENU_NAME_TEXT'] = 'Str??nky aut'; // menu_name
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_PARENT_ITEM_COLON_TEXT'] = 'Z??kladn?? auto'; // parent_item_colon
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_ALL_ITEMS_TEXT'] = 'V??echny auta str??nky'; // all_items
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_VIEW_ITEM_TEXT'] = 'Zobraz auto str??nka'; // view_item
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_ADD_NEW_ITEM_TEXT'] = 'P??idej auto str??nka'; // add_new_item
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_ADD_NEW_TEXT'] = 'P??idej novou str??nku'; // add_new
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_EDIT_ITEM_TEXT'] = 'Uprav auto str??nka'; // edit_item
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_UPDATE_ITEM_TEXT'] = 'Aktualizuj auto str??nka'; // update_item
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_SEARCH_ITEMS_TEXT'] = 'Vyhled??v??n?? aut str??nka'; // search_items
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_NOT_FOUND_TEXT'] = 'Nenalezeno'; // not_found
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_NOT_FOUND_IN_TRASH_TEXT'] = 'Nenalezeno v ko??i'; // not_found_in_trash
$lang['NRS_ADMIN_ITEM_POST_TYPE_DESCRIPTION_TEXT'] = 'Seznam aut - info';

// Admin Location Post Type
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_NAME_TEXT'] = 'Um??st??n?? vozu'; // name
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_SINGULAR_NAME_TEXT'] = 'Um??st??n?? vozu'; // singular_name
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_MENU_NAME_TEXT'] = 'Um??st??n?? vozu'; // menu_name
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_PARENT_LOCATION_COLON_TEXT'] = 'Z??kladn?? um??st??n?? vozu'; // parent_item_colon
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_ALL_LOCATIONS_TEXT'] = 'V??echna um??st??n?? voz??'; // all_items
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_VIEW_LOCATION_TEXT'] = 'Zobrazit um??st??n?? voz?? str??nka'; // view_item
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_ADD_NEW_LOCATION_TEXT'] = 'P??idat nov?? um??st??n?? vozu'; // add_new_item
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_ADD_NEW_TEXT'] = 'P??idat novou str??nku'; // add_new
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_EDIT_LOCATION_TEXT'] = 'Upravit um??st??n?? vozu str??nku'; // edit_item
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_UPDATE_LOCATION_TEXT'] = 'Aktualizovat um??st??n?? vozu str??nku'; // update_item
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_SEARCH_LOCATIONS_TEXT'] = 'Vyhled??vat um??st??n?? vozu str??nka'; // search_items
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_NOT_FOUND_TEXT'] = 'Nenalezeno'; // not_found
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_NOT_FOUND_IN_TRASH_TEXT'] = 'Nenalezeno v ko??i'; // not_found_in_trash
$lang['NRS_ADMIN_LOCATION_POST_TYPE_DESCRIPTION_TEXT'] = 'Seznam um??st??n?? voz?? str??nka';

// Admin Core
$lang['NRS_ADMIN_EDIT_TEXT'] = 'Upravit';
$lang['NRS_ADMIN_DELETE_TEXT'] = 'Smazat';
$lang['NRS_ADMIN_CANCEL_TEXT'] = 'Zru??it';
$lang['NRS_ADMIN_UNBLOCK_TEXT'] = 'Odblokovat';
$lang['NRS_ADMIN_MARK_PAID_TEXT'] = 'Ozna??it jako zaplacen??';
$lang['NRS_ADMIN_MARK_COMPLETED_EARLY_TEXT'] = 'Ozna??it jako kompletn?? brzy';
$lang['NRS_ADMIN_REFUND_TEXT'] = 'Vr??tit platbu';
$lang['NRS_ADMIN_SELECT_LOCATION_TEXT'] = '-- Vybrat um??st??n?? --';
$lang['NRS_ADMIN_ALL_LOCATIONS_TEXT'] = 'V??echny um??st??n??';
$lang['NRS_ADMIN_AVAILABLE_TEXT'] = 'K dispozici';
$lang['NRS_ADMIN_DISPLAYED_TEXT'] = 'Zobrazeno';
$lang['NRS_ADMIN_VISIBLE_TEXT'] = 'Viditeln??';
$lang['NRS_ADMIN_HIDDEN_TEXT'] = 'Skryt??';
$lang['NRS_ADMIN_ENABLED_TEXT'] = 'Zapnut??';
$lang['NRS_ADMIN_DISABLED_TEXT'] = 'Vypnut??';
$lang['NRS_ADMIN_ALLOWED_TEXT'] = 'Povolen??';
$lang['NRS_ADMIN_FAILED_TEXT'] = 'Chybn??';
$lang['NRS_ADMIN_BLOCKED_TEXT'] = 'Blokovan??';
$lang['NRS_ADMIN_REQUEST_TEXT'] = 'Po??adavek';
$lang['NRS_ADMIN_REQUESTS_TEXT'] = 'Po??adavky';
$lang['NRS_ADMIN_IP_TEXT'] = 'IP';
$lang['NRS_ADMIN_CHECK_TEXT'] = 'Check';
$lang['NRS_ADMIN_SKIP_TEXT'] = 'Skip';
$lang['NRS_ADMIN_YES_TEXT'] = 'Ano';
$lang['NRS_ADMIN_NO_TEXT'] = 'Ne';
$lang['NRS_ADMIN_DAILY_TEXT'] = 'Denn??';
$lang['NRS_ADMIN_HOURLY_TEXT'] = 'Hodinov??';
$lang['NRS_ADMIN_PER_BOOKING_TEXT'] = 'Za rezervaci';
$lang['NRS_ADMIN_COMBINED_TEXT'] = 'Kombinovan?? - Denn?? &amp; Hodinov??';
$lang['NRS_ADMIN_NEVER_TEXT'] = 'Nikdy';
$lang['NRS_ADMIN_DROPDOWN_TEXT'] = 'Rozev??rac??';
$lang['NRS_ADMIN_SLIDER_TEXT'] = 'Slider';
$lang['NRS_ADMIN_SELECT_DEMO_TEXT'] = ' --- Vybrat ??iv?? Demo --- ';
$lang['NRS_ADMIN_WITHOUT_TRANSLATION_TEXT'] = 'Bez p??ekladu';
$lang['NRS_ADMIN_VIEW_PAGE_IN_NEW_WINDOW_TEXT'] = 'Zobrazit %s str??nky v nov??m okn??';

// Core
$lang['NRS_IMAGE_ALT_TEXT'] = 'Obr??zek';
$lang['NRS_PER_BOOKING_SHORT_TEXT'] = '';
$lang['NRS_PER_DAY_SHORT_TEXT'] = 'd';
$lang['NRS_PER_HOUR_SHORT_TEXT'] = 'hod';
$lang['NRS_PER_BOOKING_TEXT'] = 'rezervace';
$lang['NRS_PER_DAY_TEXT'] = 'Den';
$lang['NRS_PER_HOUR_TEXT'] = 'Hodina';
$lang['NRS_SELECT_DATE_TEXT'] = 'Datum';
$lang['NRS_SELECT_YEAR_TEXT'] = 'Rok';
$lang['NRS_SELECT_MONTH_TEXT'] = 'M??s??c';
$lang['NRS_SELECT_DAY_TEXT'] = 'Den';
$lang['NRS_PRICE_TEXT'] = 'Cena';
$lang['NRS_PERIOD_TEXT'] = 'Doba';
$lang['NRS_DURATION_TEXT'] = 'D??lka';
$lang['NRS_MON_TEXT'] = 'Po';
$lang['NRS_TUE_TEXT'] = '??t';
$lang['NRS_WED_TEXT'] = 'St';
$lang['NRS_THU_TEXT'] = '??t';
$lang['NRS_FRI_TEXT'] = 'P??';
$lang['NRS_SAT_TEXT'] = 'So';
$lang['NRS_SUN_TEXT'] = 'Ne';
$lang['NRS_LUNCH_TEXT'] = 'Ob??d';
$lang['NRS_MONDAYS_TEXT'] = 'Pond??lky';
$lang['NRS_TUESDAYS_TEXT'] = '??terky';
$lang['NRS_WEDNESDAYS_TEXT'] = 'St??edy';
$lang['NRS_THURSDAYS_TEXT'] = '??tvrtky';
$lang['NRS_FRIDAYS_TEXT'] = 'P??tky';
$lang['NRS_SATURDAYS_TEXT'] = 'Soboty';
$lang['NRS_SUNDAYS_TEXT'] = 'Ned??le';
$lang['NRS_LUNCH_TIME_TEXT'] = 'Poledn?? pauza';
$lang['NRS_ALL_YEAR_TEXT'] = 'All Year';
$lang['NRS_ALL_DAY_TEXT'] = '24 HOD';
$lang['NRS_PARTIAL_DAY_TEXT'] = '%s - 12:00';
$lang['NRS_MIDNIGHT_TEXT'] = '00:00';
$lang['NRS_NOON_TEXT'] = '12:00';
$lang['NRS_CLOSED_TEXT'] = 'Zav??eno';
$lang['NRS_OPEN_TEXT'] = 'Otev??eno';
$lang['NRS_TODAY_TEXT'] = 'Today';
$lang['NRS_DATE_TEXT'] = 'Datum';
$lang['NRS_TIME_TEXT'] = '??as';
$lang['NRS_DAYS_TEXT'] = 'dny';
$lang['NRS_DAYS2_TEXT'] = 'dny';
$lang['NRS_DAY_TEXT'] = 'den';
$lang['NRS_HOURS_TEXT'] = 'hodiny';
$lang['NRS_HOURS2_TEXT'] = 'hodiny';
$lang['NRS_HOUR_TEXT'] = 'hod';
$lang['NRS_MINUTES_TEXT'] = 'minuty';
$lang['NRS_MINUTES2_TEXT'] = 'minuty';
$lang['NRS_MINUTE_TEXT'] = 'minuta';
$lang['NRS_DAILY_TEXT'] = 'Denn??';
$lang['NRS_HOURLY_TEXT'] = 'Hod';
$lang['NRS_ON_ST_TEXT'] = ''; // On January 21st
$lang['NRS_ON_ND_TEXT'] = ''; // On January 21st
$lang['NRS_ON_RD_TEXT'] = ''; // On January 21st
$lang['NRS_ON_TH_TEXT'] = ''; // On January 21st
$lang['NRS_ON_TEXT'] = 'v'; // on
$lang['NRS_THE_ST_TEXT'] = 'Prvn??'; // 1st, do the search
$lang['NRS_THE_ND_TEXT'] = 'Druh??'; // 2nd, select an item
$lang['NRS_THE_RD_TEXT'] = 'T??et??'; // 3rd, choose extras
$lang['NRS_THE_TH_TEXT'] = '??tvrt??'; // 4th, enter your booking details
$lang['NRS_UNCLASSIFIED_ITEM_TYPE_TEXT'] = 'Ostatn??';
$lang['NRS_NO_ITEMS_AVAILABLE_TEXT'] = '????dn?? vozy k dispozici';
$lang['NRS_NO_ITEMS_AVAILABLE_IN_THIS_CLASS_TEXT'] = '????dn?? vozy nejsou k dispozici v t??to kategorii';
$lang['NRS_NO_EXTRAS_AVAILABLE_TEXT'] = '????dn?? dopl??ky nejsou k dispozici';
$lang['NRS_NO_MANUFACTURERS_AVAILABLE_TEXT'] = 'No manufacturers available';
$lang['NRS_NO_LOCATIONS_AVAILABLE_TEXT'] = 'No locations available';
$lang['NRS_NO_BENEFITS_AVAILABLE_TEXT'] = 'No benefits available';
$lang['NRS_NA_TEXT'] = 'N/A';
$lang['NRS_NONE_TEXT'] = 'NE';
$lang['NRS_NOT_SET_TEXT'] = 'Nen?? nastaveno';
$lang['NRS_DO_NOT_EXIST_TEXT'] = 'Neexistuje';
$lang['NRS_EXIST_TEXT'] = 'Existuje';
$lang['NRS_NOT_REQ_TEXT'] = 'Not req.';
$lang['NRS_REQ_TEXT'] = 'Req.';
$lang['NRS_NOT_REQUIRED_TEXT'] = 'Nen?? po??adov??no';
$lang['NRS_REQUIRED_TEXT'] = 'Nutn?? vyplnit';
$lang['NRS_DONT_DISPLAY_TEXT'] = 'Nezo&#39;brazovat';
$lang['NRS_WITH_TAX_TEXT'] = 's DPH';
$lang['NRS_WITHOUT_TAX_TEXT'] = 'bez DPH';
$lang['NRS_TAX_TEXT'] = 'DPH';
$lang['NRS_FROM_TEXT'] = 'Od';
$lang['NRS_TO_TEXT'] = 'Do';
$lang['NRS_ALL_TEXT'] = 'All';
$lang['NRS_OR_TEXT'] = 'Nebo';
$lang['NRS_AND_TEXT'] = 'a';
$lang['NRS_UNLIMITED_TEXT'] = 'Bez limitu';
$lang['NRS_DEPOSIT_TEXT'] = 'Depozit - kauce';
$lang['NRS_DISCOUNT_TEXT'] = 'Sleva';
$lang['NRS_PREPAYMENT_TEXT'] = 'P??edplacen?? ????stka';
$lang['NRS_TOTAL_TEXT'] = 'Celkem';
$lang['NRS_BACK_TEXT'] = 'Zp??t';
$lang['NRS_CONTINUE_TEXT'] = 'Pokra??ovat';
$lang['NRS_SEARCH_TEXT'] = 'Vyhledat';
$lang['NRS_SELECT_DROPDOWN_TEXT'] = '--- Vybrat ---';
$lang['NRS_ITEM_TEXT'] = 'Auto';
$lang['NRS_EXTRA_TEXT'] = 'Dopl??ky';
$lang['NRS_RENTAL_OPTION_TEXT'] = 'Mo??nosti zap??j??en??';
$lang['NRS_ITEMS_TEXT'] = 'Auta';
$lang['NRS_EXTRAS_TEXT'] = 'Dopl??ky';
$lang['NRS_RENTAL_OPTIONS_TEXT'] = 'Mo??nosti zap??j??en??';
$lang['NRS_SHOW_ITEM_TEXT'] = 'Zobrazit auto';
$lang['NRS_VIA_PARTNER_TEXT'] = 'p??es %s';
$lang['NRS_COUPON_TEXT'] = 'Kup??n';

// Booking step no. 1 - item search
$lang['NRS_BOOKING_TEXT'] = 'Rezervace';
$lang['NRS_PICKUP_TEXT'] = 'Vyzvednut??';
$lang['NRS_RETURN_TEXT'] = 'Vr??cen??';
$lang['NRS_OTHER_TEXT'] = 'Ostatn??';
$lang['NRS_INFORMATION_TEXT'] = 'Informace';
$lang['NRS_CITY_AND_LOCATION_TEXT'] = 'M??sto &amp; um??st??n??:';
$lang['NRS_PICKUP_CITY_AND_LOCATION_TEXT'] = 'M??sto vyzvednut?? &amp; Um??st??n??:';
$lang['NRS_RETURN_CITY_AND_LOCATION_TEXT'] = 'M??sto vr??cen?? &amp; Um??st??n??:';
$lang['NRS_SELECT_BOOKING_DATE_TEXT'] = 'Datum:';
$lang['NRS_SELECT_BOOKING_PERIOD_TEXT'] = 'D??lka rezervace:';
$lang['NRS_COUPON_CODE_TEXT'] = 'K??d kup??nu';
$lang['NRS_I_HAVE_BOOKING_CODE_TEXT'] = 'J?? m??m rezerva??n?? kup??n:';
$lang['NRS_I_HAVE_COUPON_CODE_TEXT'] = 'J?? m??m k??d kup??nu:';
$lang['NRS_PICKUP_LOCATION_TEXT'] = 'M??sto vyzvednut??';
$lang['NRS_RETURN_LOCATION_TEXT'] = 'M??sto vr??cen??';
$lang['NRS_ALL_BODY_TYPES_DROPDOWN_TEXT'] = '---- V??echny typy ----';
$lang['NRS_ALL_TRANSMISSION_TYPES_DROPDOWN_TEXT'] = '---- V??echny p??evodovky ----';
$lang['NRS_SELECT_PICKUP_LOCATION_TEXT'] = '-- Vybrat m??sto vyzvednut?? --';
$lang['NRS_SELECT_RETURN_LOCATION_TEXT'] = '-- Vybrat m??sto vr??cen?? --';
$lang['NRS_PICKUP_DATE_TEXT'] = 'Datum vyzvednut??';
$lang['NRS_RETURN_DATE_TEXT'] = 'Datum vr??cen??';
$lang['NRS_PICKUP_DATE_ALERT_TEXT'] = 'Pros??m zvolte datum vyzvednut??!';
$lang['NRS_RETURN_DATE_ALERT_TEXT'] = 'Pros??m zvolte datum vr??cen??!';
$lang['NRS_BOOKING_PERIOD_ALERT_TEXT'] = 'Pros??m vyberte d??lku zap??j??en??!';
$lang['NRS_PICKUP_LOCATION_ALERT_TEXT'] = 'Pros??m vyberte m??sto zap??j??en??!';
$lang['NRS_RETURN_LOCATION_ALERT_TEXT'] = 'Pros??m vyberte m??sto vr??cen??!';
$lang['NRS_COUPON_CODE_ALERT_TEXT'] = 'Pros??m vlo??te k??d kup??nu!';
$lang['NRS_SHOW_ITEM_DESCRIPTION_TEXT'] = 'Zobrazit popis auta';
$lang['NRS_UPDATE_BOOKING_TEXT'] = 'Aktualizovat mou rezervaci';
$lang['NRS_CANCEL_BOOKING_TEXT'] = 'Zru??it mou rezervaci';
$lang['NRS_CHANGE_BOOKING_DATE_TIME_AND_LOCATION_TEXT'] = 'Zm??nit datum, ??as &amp; um??st??n??';
$lang['NRS_CHANGE_BOOKED_ITEMS_TEXT'] = 'Zm??nit auta';
$lang['NRS_CHANGE_EXTRAS_TEXT'] = 'Zm??nit dopl??ky';
$lang['NRS_CHANGE_RENTAL_OPTIONS_TEXT'] = 'Zm??nit mo??nosti zap??j??en??';
$lang['NRS_IN_THIS_LOCATION_TEXT'] = 'V tomto um??st??n??';
$lang['NRS_AFTERHOURS_PICKUP_IS_NOT_ALLOWED_TEXT'] = 'Nen?? dovoleno';
$lang['NRS_AFTERHOURS_RETURN_IS_NOT_ALLOWED_TEXT'] = 'Nen?? dovoleno';

// Booking step no. 2 - search results
$lang['NRS_DISTANCE_AWAY_TEXT'] = '%s pry??';
$lang['NRS_BOOKING_DATA_TEXT'] = 'Detaily rezervace';
$lang['NRS_BOOKING_CODE_TEXT'] = 'K??d rezervace';
$lang['NRS_BOOKING_EDIT_TEXT'] = 'upravit';
$lang['NRS_BOOKING_PICKUP_TEXT'] = 'Vyzvednout';
$lang['NRS_BOOKING_BUSINESS_HOURS_TEXT'] = 'Pracovn?? doba';
$lang['NRS_BOOKING_FEE_TEXT'] = 'Poplatek';
$lang['NRS_BOOKING_RETURN_TEXT'] = 'Vr??cen??';
$lang['NRS_BOOKING_NIGHTLY_RATE_TEXT'] = 'pozdn?? hodiny';
$lang['NRS_BOOKING_AFTERHOURS_TEXT'] = 'pozdn?? hodiny';
$lang['NRS_BOOKING_EARLY_TEXT'] = 'Early';
$lang['NRS_BOOKING_LATE_TEXT'] = 'Late';
$lang['NRS_BOOKING_AFTERHOURS_PICKUP_TEXT'] = 'Pozd??j???? vyzvednut??';
$lang['NRS_BOOKING_AFTERHOURS_PICKUP_IMPOSSIBLE_TEXT'] = 'Mo??n??';
$lang['NRS_BOOKING_AFTERHOURS_RETURN_TEXT'] = 'Pozd??j???? vr??cen??';
$lang['NRS_BOOKING_AFTERHOURS_RETURN_IMPOSSIBLE_TEXT'] = 'Mo??n??';
$lang['NRS_CHOOSE_TEXT'] = 'Zvolte';
$lang['NRS_SEARCH_RESULTS_TEXT'] = 'V??sledky vyhled??v??n??';
$lang['NRS_MILEAGE_TEXT'] = 'KM';

// Booking step no. 3 - booking options
$lang['NRS_SELECT_RENTAL_OPTIONS_TEXT'] = 'Vyberte mo??nosti zap??j??en??';
$lang['NRS_SELECTED_ITEMS_TEXT'] = 'Vybran?? auta';
$lang['NRS_FOR_DEPENDANT_ITEM_TEXT'] = ' (za %s)';
$lang['NRS_NO_EXTRAS_AVAILABLE_CLICK_CONTINUE_TEXT'] = '????dn?? dopl??ky nejsou k dispozici. Klikn??te na tla????tko pokra??ovat';

// Booking step no. 4 - booking details
$lang['NRS_PICKUP_DATE_AND_TIME_TEXT'] = 'Datum vyzvednut?? &amp; ??as';
$lang['NRS_RETURN_DATE_AND_TIME_TEXT'] = 'Datum vr??cen?? &amp; ??as';
$lang['NRS_UNIT_PRICE_TEXT'] = 'Cena za ks';
$lang['NRS_QUANTITY_TEXT'] = 'Mno??stv??';
$lang['NRS_QUANTITY_SHORT_TEXT'] = 'Mno??.';
$lang['NRS_PICKUP_FEE_TEXT'] = 'Vyzvednut?? poplatek';
$lang['NRS_RETURN_FEE_TEXT'] = 'Vr??cen?? poplatek';
$lang['NRS_NIGHTLY_PICKUP_RATE_APPLIED_TEXT'] = '(V??etn?? no??n?? p??ir????ky)';
$lang['NRS_NIGHTLY_RETURN_RATE_APPLIED_TEXT'] = '(V??etn?? no??n?? p??ir????ky)';
$lang['NRS_ITEM_QUANTITY_SUFFIX_TEXT'] = 'auto(/a)';
$lang['NRS_EXTRA_QUANTITY_SUFFIX_TEXT'] = 'dopln??k(/y)';
$lang['NRS_PAY_NOW_OR_AT_PICKUP_TEXT'] = 'Zapla?? ihned / p??i vyzvednut??';
$lang['NRS_PAY_NOW_TEXT'] = 'Zapla?? ihned';
$lang['NRS_PAY_AT_PICKUP_TEXT'] = 'Zapla?? p??i vyzvednut??';
$lang['NRS_PAY_LATER_OR_ON_RETURN_TEXT'] = 'Zapla?? pozd??ji / p??i vr??cen??';
$lang['NRS_PAY_LATER_TEXT'] = 'Zapla?? pozd??ji';
$lang['NRS_PAY_ON_RETURN_TEXT'] = 'Zapla?? p??i vr??cen??';
$lang['NRS_ITEM_RENTAL_DETAILS_TEXT'] = 'Detaily zap??j??en??';
$lang['NRS_MANUFACTURER_TEXT'] = 'V??robce';
$lang['NRS_ITEM_MODEL_TEXT'] = 'Model vozu';
$lang['NRS_GROSS_TOTAL_TEXT'] = 'Mezisou??et';
$lang['NRS_GRAND_TOTAL_TEXT'] = 'Celkov?? sou??et';
$lang['NRS_BOOKING_DETAILS_TEXT'] = 'Detaily rezervace';
$lang['NRS_CUSTOMER_DETAILS_TEXT'] = 'Detaily z??kazn??ka';
$lang['NRS_EXISTING_CUSTOMER_DETAILS_TEXT'] = 'Hledej nebo detaily existuj??c??ho z??kazn??ka';
$lang['NRS_EXISTING_CUSTOMER_TEXT'] = 'Existuj??c?? z??kazn??k';
$lang['NRS_EMAIL_ADDRESS_TEXT'] = 'E-mailov?? adresa';
$lang['NRS_FETCH_CUSTOMER_DETAILS_TEXT'] = 'Na????st ??daje';
$lang['NRS_OR_ENTER_NEW_DETAILS_TEXT'] = 'Nebo vytvo??it nov?? ????et';
$lang['NRS_CUSTOMER_TEXT'] = 'Z??kazn??k';
$lang['NRS_TITLE_TEXT'] = 'Osloven??';
$lang['NRS_MR_TEXT'] = 'Pan';
$lang['NRS_MS_TEXT'] = 'Pan??';
$lang['NRS_MRS_TEXT'] = 'Sle??na';
$lang['NRS_MISS_TEXT'] = 'Ing.';
$lang['NRS_DR_TEXT'] = 'Mgr.';
$lang['NRS_PROF_TEXT'] = 'MUDr.';
$lang['NRS_FIRST_NAME_TEXT'] = 'Jm??no';
$lang['NRS_LAST_NAME_TEXT'] = 'P????jmen??';
$lang['NRS_DATE_OF_BIRTH_TEXT'] = 'Datum narozen??';
$lang['NRS_YEAR_OF_BIRTH_TEXT'] = 'Rok narozen??';
$lang['NRS_ADDRESS_TEXT'] = 'Adresa';
$lang['NRS_STREET_ADDRESS_TEXT'] = 'Adresa';
$lang['NRS_CITY_TEXT'] = 'M??sto';
$lang['NRS_STATE_TEXT'] = 'St??t';
$lang['NRS_ZIP_CODE_TEXT'] = 'PS??';
$lang['NRS_COUNTRY_TEXT'] = 'Zem??';
$lang['NRS_PHONE_TEXT'] = 'Telefon';
$lang['NRS_EMAIL_TEXT'] = 'E-mail';
$lang['NRS_ADDITIONAL_COMMENTS_TEXT'] = 'Dodate??n?? koment????';
$lang['NRS_CUSTOMER_ID_TEXT'] = 'Z??kaznick?? ID';
$lang['NRS_IP_ADDRESS_TEXT'] = 'IP Adresa';
$lang['NRS_PAY_BY_SHORT_TEXT'] = 'Zaplatit';
$lang['NRS_I_AGREE_WITH_TERMS_AND_CONDITIONS_TEXT'] = 'Souhlas??m s podm??nkami &amp; u????v??n??';
$lang['NRS_TERMS_AND_CONDITIONS_TEXT'] = 'Podm??nky &amp; U????v??n??';
$lang['NRS_CONFIRM_TEXT'] = 'Potvrdit';
$lang['NRS_FIELD_REQUIRED_TEXT'] = 'Toto pole je nutn?? vyplnit';

// Booking step no. 5 - process booking
$lang['NRS_PAYMENT_DETAILS_TEXT'] = 'Detaily platby';
$lang['NRS_PAYMENT_OPTION_TEXT'] = 'Zaplatit';
$lang['NRS_PAYER_EMAIL_TEXT'] = 'E-mail pl??tce';
$lang['NRS_TRANSACTION_ID_TEXT'] = 'ID transakce';
$lang['NRS_PROCESSING_PAYMENT_TEXT'] = 'Prov??d??m platbu???';
$lang['NRS_PLEASE_WAIT_UNTIL_PAYMENT_WILL_BE_PROCESSED_TEXT'] = 'Pros??m po??kejte, dokud nebude platba provedena???';

//display-booking-confirm.php
$lang['NRS_STEP5_PAY_ONLINE_TEXT'] = 'Zaplatit on-line';
$lang['NRS_STEP5_PAY_AT_PICKUP_TEXT'] = 'Zaplatit p??i vyzvednut??';
$lang['NRS_THANK_YOU_TEXT'] = 'D??kujeme V??m!';
$lang['NRS_YOUR_BOOKING_CONFIRMED_TEXT'] = 'Va??e rezervace je potvrzena. Rzerva??n?? k??d ';
$lang['NRS_INVOICE_SENT_TO_YOUR_EMAIL_ADDRESS_TEXT'] = 'Faktura odesl??na na V???? e-mail';

//display-booking-failure.php
$lang['NRS_BOOKING_FAILURE_TEXT'] = 'Selh??n?? rezervace';
$lang['NRS_BOOKING_FAILURE_SEARCH_ALL_ITEMS_TEXT'] = 'Vyhled??vat v??echny vozy';

// display-item-price-table.php
$lang['NRS_DAY_PRICE_TEXT'] = 'Cena za den';
$lang['NRS_HOUR_PRICE_TEXT'] = 'Cena za hodinu';
$lang['NRS_NO_ITEMS_IN_THIS_CATEGORY_TEXT'] = 'V t??to kategorii nejsou ????dn?? vozy';
$lang['NRS_PRICE_FOR_DAY_FROM_TEXT'] = 'Cena za den za????n?? od';
$lang['NRS_PRICE_FOR_HOUR_FROM_TEXT'] = 'Cena za hod za????n?? od';
$lang['NRS_PRICE_WITH_APPLIED_TEXT'] = 'v??etn??';
$lang['NRS_WITH_APPLIED_DISCOUNT_TEXT'] = 'slevy';

// class.ItemsAvailability.php
$lang['NRS_MONTH_DAY_TEXT'] = 'Den';
$lang['NRS_MONTH_DAYS_TEXT'] = 'Dny';
$lang['NRS_ITEMS_AVAILABILITY_FOR_TEXT'] = 'Vozy k dispozici za';
$lang['NRS_ITEMS_AVAILABILITY_IN_NEXT_30_DAYS_TEXT'] = 'Vozy k dispozici v p??????t??ch 30dnech';
$lang['NRS_ITEMS_PARTIAL_AVAILABILITY_FOR_TEXT'] = '????ste??n?? dostupn?? vozy';
$lang['NRS_ITEMS_AVAILABILITY_THIS_MONTH_TEXT'] = 'Auta k dispozici tento m??s??c'; // Not used
$lang['NRS_ITEMS_AVAILABILITY_NEXT_MONTH_TEXT'] = 'Auta k dispozici p??????t?? m??s??c'; // Not used
$lang['NRS_ITEM_ID_TEXT'] = 'ID:';
$lang['NRS_TOTAL_ITEMS_TEXT'] = 'Celkem aut:';

// class.ExtrasAvailability.php
$lang['NRS_EXTRAS_AVAILABILITY_FOR_TEXT'] = 'Dopl??ky k dispozici za ';
$lang['NRS_EXTRAS_AVAILABILITY_IN_NEXT_30_DAYS_TEXT'] = 'Dopl??ky k dispozici za 30dn??';
$lang['NRS_EXTRAS_PARTIAL_AVAILABILITY_FOR_TEXT'] = '????ste??n?? dostupnost dopl??k??';
$lang['NRS_EXTRAS_AVAILABILITY_THIS_MONTH_TEXT'] = 'Dopl??ky k dispozici tento m??s??c'; // Not used
$lang['NRS_EXTRAS_AVAILABILITY_NEXT_MONTH_TEXT'] = 'Dopl??ky k dispozici p??????t?? m??s??c'; // Not used
$lang['NRS_EXTRA_ID_TEXT'] = 'ID';
$lang['NRS_TOTAL_EXTRAS_TEXT'] = 'Celkem dopl??ky:';

// class.ItemsController.php
$lang['NRS_SHOW_ITEM_PAGE_TEXT'] = 'Zobrazit popis vozu';
$lang['NRS_PARTNER_TEXT'] = 'Partner';
$lang['NRS_BODY_TYPE_TEXT'] = 'Kategorie';
$lang['NRS_TRANSMISSION_TYPE_TEXT'] = 'P??evodovka';
$lang['NRS_FUEL_TYPE_TEXT'] = 'Palivo';
$lang['NRS_ITEM_FUEL_CONSUMPTION_TEXT'] = 'Spot??eba';
$lang['NRS_ITEM_PASSENGERS_TEXT'] = 'Max po??et cestuj??c??ch';
$lang['NRS_ITEM_PRICE_FROM_TEXT'] = 'Cena od';
$lang['NRS_INQUIRE_TEXT'] = 'Call';
$lang['NRS_GET_A_QUOTE_TEXT'] = 'Po????dat o cenu';
$lang['NRS_ITEM_FEATURES_TEXT'] = 'Vlastnosti';
$lang['NRS_BOOK_ITEM_TEXT'] = 'P??j??it';

// class.LocationsController.php
$lang['NRS_LOCATIONS_BUSINESS_HOURS_TEXT'] = 'Business Hours';
$lang['NRS_LOCATION_FEES_TEXT'] = 'Um??st??n?? - poplatky';
$lang['NRS_EARLY_PICKUP_TEXT'] = 'Early Pick-Up';
$lang['NRS_LATE_PICKUP_TEXT'] = 'Late Pick-Up';
$lang['NRS_EARLY_RETURN_TEXT'] = 'Early Return';
$lang['NRS_LATE_RETURN_TEXT'] = 'Late Return';
$lang['NRS_EARLY_PICKUP_FEE_TEXT'] = 'Early pick-up fee';
$lang['NRS_LATE_RETURN_FEE_TEXT'] = 'Late return fee';
$lang['NRS_VIEW_LOCATION_TEXT'] = 'View Location';

// class.SingleItemController.php
$lang['NRS_ITEM_ENGINE_CAPACITY_TEXT'] = 'Obsah motoru';
$lang['NRS_ITEM_LUGGAGE_TEXT'] = 'Max po??et kufr??';
$lang['NRS_ITEM_DOORS_TEXT'] = 'Dve??e';
$lang['NRS_ITEM_DRIVER_AGE_TEXT'] = 'Minim??ln?? v??k ??idi??e';
$lang['NRS_ADDITIONAL_INFORMATION_TEXT'] = 'Dodate??n?? informace';

// class.SingleLocationController.php
$lang['NRS_CONTACTS_TEXT'] = 'Contacts';
$lang['NRS_CONTACT_DETAILS_TEXT'] = 'Contact Details';
$lang['NRS_BUSINESS_HOURS_FEES_TEXT'] = 'Business Hours Fees';
$lang['NRS_AFTERHOURS_FEES_TEXT'] = 'After Hours Fees';

// template.BookingCancelled.php
$lang['NRS_CANCELLED_SUCCESSFULLY_TEXT'] = '??sp????n?? zru??eno';
$lang['NRS_NOT_CANCELLED_TEXT'] = 'Rezervace nebyla zru??ena';

// template.Step8EditBooking.php
$lang['NRS_EDIT_TEXT'] = 'Zm??nit';
$lang['NRS_BOOKING2_TEXT'] = 'rezervaci';
$lang['NRS_EDIT_BOOKING_BUTTON_TEXT'] = 'Zm??nit rezervaci';
$lang['NRS_EDIT_BOOKING_PLEASE_ENTER_BOOKING_NUMBER_TEXT'] = 'Pros??m napi??te ????slo rezervace!';

// Admin System Errors
// Unfortunately, they are untranslatable
$lang['NRS_ERROR_IN_METHOD_TEXT'] = 'Chyba ve %s zp??sobu: ';

// Exceptions
$lang['NRS_ERROR_CANNOT_BIND_TEMPLATE_VARIABLE_TEXT'] = 'nem????e b??t variabiln?? pojmenov??no &#39;templateFile&#39;.';
$lang['NRS_ERROR_TEMPLATE_NOT_EXIST_TEXT'] = 'Soubor s ??ablonou %s neexistuje.';

// Errors
$lang['NRS_ERROR_EXTENSION_NAME_TEXT'] = 'Syst??m autop??j??ovny';
$lang['NRS_ERROR_REQUIRED_FIELD_TEXT'] = 'Po??adovan?? pole';
$lang['NRS_ERROR_IS_EMPTY_TEXT'] = 'je pr??zdn??';
$lang['NRS_ERROR_SLIDER_CANT_BE_DISPLAYED_TEXT'] = 'Slider nem&#39;????e b??t zobrazen';
$lang['NRS_ERROR_CUSTOMER_DETAILS_NOT_FOUND_TEXT'] = 'Neexistuje ????dn?? z??kazn??k s t??mito ??daji. Pros??m vytvo??te nov?? ????et.';
$lang['NRS_ERROR_CUSTOMER_DETAILS_NO_ERROR_TEXT'] = '????dn?? chyby';
$lang['NRS_ERROR_EXCEEDED_CUSTOMER_LOOKUP_ATTEMPTS_TEXT'] = 'P??ekro??ili jste po??et nahl??dnut?? do detailu z??kazn??ka. Pros??m vlo??te detaily manu??ln?? do formul????e n????e.';
$lang['NRS_ERROR_CUSTOMER_DETAILS_UNKNOWN_ERROR_TEXT'] = 'Nezn??m?? chyba';
$lang['NRS_ERROR_BOOKING_DOES_NOT_EXIST_TEXT'] = 'neexistuje';
$lang['NRS_ERROR_PLEASE_SELECT_AT_LEAST_ONE_ITEM_TEXT'] = 'Pros??m zvolte alespo?? jedno vozidlo';
$lang['NRS_ERROR_ITEM_NOT_AVAILABLE_TEXT'] = 'Toto vozidlo nen?? k dispozici.';
$lang['NRS_ERROR_NO_ITEM_AVAILABLE_PLEASE_TRY_DIFFERENT_DATE_TEXT'] = '????dn?? vozy k dispozici. Pros??m zm????te ??as p??j??en?? nebo krit??ria vyhled??v??n??.';
$lang['NRS_ERROR_SEARCH_ENGINE_DISABLED_TEXT'] = 'On-line rezervace je moment??ln?? vypnut??. Pros??m zkuste to znovu pozd??ji.';
$lang['NRS_ERROR_OUT_BEFORE_IN_TEXT'] = 'Datum vr??cen?? mus?? b??t pozd??ji ne?? datum p??j??en??. Pros??m zvolte platn?? data pro p??j??en?? a vr??cen??.';
$lang['NRS_ERROR_MINIMUM_NUMBER_OF_NIGHT_SHOULD_NOT_BE_LESS_THAN_TEXT'] = 'Minim??ln?? po??et noc?? nem????e b??t men???? ne??';
$lang['NRS_ERROR_PLEASE_MODIFY_YOUR_SEARCH_CRITERIA_TEXT'] = 'Pros??m upravte krit??ria vyhled??v??n??.';
$lang['NRS_ERROR_PICKUP_IS_NOT_POSSIBLE_ON_TEXT'] = 'Vyzvednut?? nen?? mo??n?? v';
$lang['NRS_ERROR_PLEASE_MODIFY_YOUR_PICKUP_TIME_BY_WEBSITE_TIME_TEXT'] = 'Pros??m upravte datum vyzvednut?? a &amp; ??as podle m??stn??ho data a ??asu vyp??j??en??.';
$lang['NRS_ERROR_CURRENT_DATE_TIME_TEXT'] = 'M??stn?? datum a  &amp; ??as p??j??ovny je';
$lang['NRS_ERROR_EARLIEST_POSSIBLE_PICKUP_DATE_TIME_TEXT'] = 'Nejbli?????? mo??n?? datum &amp; a ??as vyzvednut?? je';
$lang['NRS_ERROR_OR_NEXT_BUSINESS_HOURS_OF_PICKUP_LOCATION_TEXT'] = 'nebo prvn?? mo??nost po t??, kdy je ve vybran??m um??st??n?? otev??eno';
$lang['NRS_ERROR_PICKUP_DATE_CANT_BE_LESS_THAN_RETURN_DATE_TEXT'] = 'Datum vyzvednut?? &amp; ??as nem????e b??t krat???? ne?? datum & ??as. Pros??m zvolte spr??vn?? ??as vyzvednut?? & vr??cen??';
$lang['NRS_ERROR_PICKUP_LOCATION_IS_CLOSED_AT_THIS_DATE_TEXT'] = 'M??sto vyzvednut?? %s na adrese %s je zav??en?? v tento datum (%s).';
$lang['NRS_ERROR_PICKUP_LOCATION_IS_CLOSED_AT_THIS_TIME_TEXT'] = 'M??sto vyzvednut?? %s na adrese %s je zav??en?? v tento ??as (%s).';
$lang['NRS_ERROR_RETURN_LOCATION_IS_CLOSED_AT_THIS_DATE_TEXT'] = 'M??sto vr??cen?? %s na adrese %s je zav??en?? v tento datum (%s).';
$lang['NRS_ERROR_RETURN_LOCATION_IS_CLOSED_AT_THIS_TIME_TEXT'] = 'M??sto vr??cen?? %s na adrese %s je zav??en?? v tento ??as (%s).';
$lang['NRS_ERROR_AFTERHOURS_PICKUP_LOCATION_IS_CLOSED_AT_THIS_TIME_TEXT'] = 'Po pracovn?? dob?? vyzvednut?? je %s na adrese %s ale toto m??sto je tak?? v tento ??as zav??en??.';
$lang['NRS_ERROR_AFTERHOURS_RETURN_LOCATION_IS_CLOSED_AT_THIS_TIME_TEXT'] = 'Po pracovn?? dob?? vr??cen?? je %s na adrese %s ale toto m??sto je tak?? v tento ??as zav??en??.';
$lang['NRS_ERROR_LOCATION_OPEN_HOURS_ARE_TEXT'] = 'V tomto m??st?? je otev??eno v %s, %s je %s.';
$lang['NRS_ERROR_LOCATION_WEEKLY_OPEN_HOURS_ARE_TEXT'] = 'Otev??rac?? doba v tomto m??st?? p??es t??den je:';
$lang['NRS_ERROR_AFTERHOURS_PICKUP_LOCATION_OPEN_HOURS_ARE_TEXT'] = 'V m??st?? vyzvednut?? je mimo pracovn?? dobu %s.';
$lang['NRS_ERROR_AFTERHOURS_RETURN_LOCATION_OPEN_HOURS_ARE_TEXT'] = 'V m??st?? vr??cen?? je mimo pracovn?? dobu %s.';
$lang['NRS_ERROR_AFTERHOURS_PICKUP_IS_NOT_ALLOWED_AT_LOCATION_TEXT'] = 'V tomto um??st??n?? nen?? mo??n?? zap??j??en?? po otev??rac?? dob??.';
$lang['NRS_ERROR_AFTERHOURS_RETURN_IS_NOT_ALLOWED_AT_LOCATION_TEXT'] = 'V tomto um??st??n?? nen?? mo??n?? vr??cen?? po otev??rac?? dob??.';
$lang['NRS_ERROR_MAXIMUM_BOOKING_LENGTH_CANT_BE_MORE_THAN_TEXT'] = 'Maxim??ln?? d??lka rezervace je (v dnech)';
$lang['NRS_ERROR_INVALID_BOOKING_CODE_TEXT'] = 'Chybn?? k??d rezervace nebo tento k??d neexistuje v??bec.';
$lang['NRS_ERROR_INVALID_SECURITY_CODE_TEXT'] = 'Bezpe??nostn?? k??d nen?? platn??.';
$lang['NRS_ERROR_DRIVER_AGE_VIOLATION_FOR_ITEM_TEXT'] = 'Based on your birthdate, your age does not match the minimum driver age requirement for %s.';
$lang['NRS_ERROR_DRIVER_AGE_VIOLATION_TEXT'] = 'Based on your birthdate, your age does not match the minimum driver age requirement for one of selected cars.';
$lang['NRS_ERROR_DEPARTED_TEXT'] = 'Rezervace ??. %s je ozna??ena jako vyp??j??en??, a nejsou mo??n?? dal???? ??pravy.';
$lang['NRS_ERROR_CANCELLED_TEXT'] = 'Rezervace ??. %s byla zru??ena.';
$lang['NRS_ERROR_REFUNDED_TEXT'] = 'Rezervace ??. %s byla refundov??na - vr??cena zp??t z??kazn??kovi a d??le nen?? k dispozici.';
$lang['NRS_ERROR_SYSTEM_IS_UNABLE_TO_SEND_EMAIL_TEXT'] = 'Chyba: Syst??m nen?? schopen odeslat potvrzuj??c?? e-mail. Nastaven?? e-mailu nen?? spr??vn?? nastaveno, nebo z??kazn??kovo&#39;s e-mailov?? schr??nka nen?? spr??vn??.';
$lang['NRS_ERROR_PAYMENT_METHOD_IS_NOT_YET_IMPLEMENTED_TEXT'] = 'Chyba: Vy&39; zkou????te platit touto platebn?? metodou, kter?? nen?? k dispozici ve Va??em syst??mu.';
$lang['NRS_ERROR_OTHER_BOOKING_ERROR_TEXT'] = 'Other reservation error. If you keep seeing this message, please contact website administrator.';

// Admin Discount controller
$lang['NRS_ADMIN_DISCOUNT_ITEM_BOOKING_IN_ADVANCE_TEXT'] = 'P??idat/Upravit slevu aut za rezervaci dop??edu';
$lang['NRS_ADMIN_DISCOUNT_ITEM_BOOKING_DURATION_TEXT'] = 'P??idat/Upravit Slevu aut za d??lku zap??j??en??';
$lang['NRS_ADMIN_DISCOUNT_EXTRA_BOOKING_IN_ADVANCE_TEXT'] = 'P??idat/Upravit Slevu dopl??k?? za rezervaci dop??edu';
$lang['NRS_ADMIN_DISCOUNT_EXTRA_BOOKING_DURATION_TEXT'] = 'P??idat/Upravit Slevu dopl??k?? za d??lku zap??j??en??';
$lang['NRS_ADMIN_DISCOUNT_DURATION_BEFORE_TEXT'] = 'D??lka p??ed t??m:';
$lang['NRS_ADMIN_DISCOUNT_DURATION_UNTIL_TEXT'] = 'D??lka ne??:';
$lang['NRS_ADMIN_DISCOUNT_DURATION_FROM_TEXT'] = 'D??lka od:';
$lang['NRS_ADMIN_DISCOUNT_DURATION_TILL_TEXT'] = 'D??lka do:';

// Admin Settings Controller
$lang['NRS_ADMIN_SETTINGS_OKAY_GLOBAL_SETTINGS_UPDATED_TEXT'] = 'Completed: Global settings updated successfully!';
$lang['NRS_ADMIN_SETTINGS_OKAY_CUSTOMER_SETTINGS_UPDATED_TEXT'] = 'Completed: Customer settings updated successfully!';
$lang['NRS_ADMIN_SETTINGS_OKAY_SEARCH_SETTINGS_UPDATED_TEXT'] = 'Completed: Search settings updated successfully!';
$lang['NRS_ADMIN_SETTINGS_OKAY_PRICE_SETTINGS_UPDATED_TEXT'] = 'Completed: Price settings updated successfully!';

// OK / Error Messages - Benefit Element
$lang['NRS_BENEFIT_TITLE_EXISTS_ERROR_TEXT'] = 'Error: Benefit with this title already exists!';
$lang['NRS_BENEFIT_UPDATE_ERROR_TEXT'] = 'Error: MySQL update error for existing benefit!';
$lang['NRS_BENEFIT_UPDATED_TEXT'] = 'Completed: Benefit has been updated successfully!';
$lang['NRS_BENEFIT_INSERT_ERROR_TEXT'] = 'Error: MySQL insert error for new benefit!';
$lang['NRS_BENEFIT_INSERTED_TEXT'] = 'Completed: New benefit has been added successfully!';
$lang['NRS_BENEFIT_REGISTERED_TEXT'] = 'Benefit title registered for translation.';
$lang['NRS_BENEFIT_DELETE_ERROR_TEXT'] = 'Error: MySQL delete error for existing benefit. No rows were deleted from database!';
$lang['NRS_BENEFIT_DELETED_TEXT'] = 'Completed: Benefit has been deleted successfully!';

// OK / Error Messages - Block Element
$lang['NRS_BLOCK_INSERT_ERROR_TEXT'] = 'Error: MySQL insert error for new block!';
$lang['NRS_BLOCK_INSERTED_TEXT'] = 'Completed: New block has been added successfully!';
$lang['NRS_BLOCK_DELETE_ERROR_TEXT'] = 'Error: MySQL delete error for existing block. No rows were deleted from database!';
$lang['NRS_BLOCK_DELETED_TEXT'] = 'Completed: Block has been deleted successfully!';
$lang['NRS_BLOCK_DELETE_OPTIONS_ERROR_TEXT'] = 'Failed: No cars or extras were deleted from block!';
$lang['NRS_BLOCK_OPTIONS_DELETED_TEXT'] = 'Completed: All cars and extras were deleted from block!';

// OK / Error Messages - Body Type Element
$lang['NRS_BODY_TYPE_TITLE_EXISTS_ERROR_TEXT'] = 'Error: Body type with this title already exists!';
$lang['NRS_BODY_TYPE_UPDATE_ERROR_TEXT'] = 'Error: MySQL update error for existing body type!';
$lang['NRS_BODY_TYPE_UPDATED_TEXT'] = 'Completed: Body type has been updated successfully!';
$lang['NRS_BODY_TYPE_INSERT_ERROR_TEXT'] = 'Error: MySQL insert error for new body type!';
$lang['NRS_BODY_TYPE_INSERTED_TEXT'] = 'Completed: New body type has been added successfully!';
$lang['NRS_BODY_TYPE_REGISTERED_TEXT'] = 'Body type title registered for translation.';
$lang['NRS_BODY_TYPE_DELETE_ERROR_TEXT'] = 'Error: MySQL delete error for existing body type. No rows were deleted from database!';
$lang['NRS_BODY_TYPE_DELETED_TEXT'] = 'Completed: Body type has been deleted successfully!';

// OK / Error Messages - Booking Element
$lang['NRS_BOOKING_UPDATE_ERROR_TEXT'] = 'Error: MySQL update error for existing reservation!';
$lang['NRS_BOOKING_UPDATED_TEXT'] = 'Completed: Reservation has been updated successfully!';
$lang['NRS_BOOKING_INSERT_ERROR_TEXT'] = 'Error: MySQL insert error for new reservation!';
$lang['NRS_BOOKING_INSERTED_TEXT'] = 'Completed: New reservation has been added successfully!';
$lang['NRS_BOOKING_CANCEL_ERROR_TEXT'] = 'Error: MySQL update error appeared when tried to cancel existing reservation!';
$lang['NRS_BOOKING_CANCELLED_TEXT'] = 'Completed: Reservation has been cancelled successfully!';
$lang['NRS_BOOKING_DELETE_ERROR_TEXT'] = 'Error: MySQL delete error for existing reservation. No rows were deleted from database!';
$lang['NRS_BOOKING_DELETED_TEXT'] = 'Completed: Reservation has been deleted successfully!';
$lang['NRS_BOOKING_DELETE_OPTIONS_ERROR_TEXT'] = 'Failed: No cars or extras were deleted from reservation!';
$lang['NRS_BOOKING_OPTIONS_DELETED_TEXT'] = 'Completed: All cars and extras were deleted from reservation!';
$lang['NRS_BOOKING_MARK_AS_PAID_ERROR_TEXT'] = 'Failed: Reservation was not marked as paid!';
$lang['NRS_BOOKING_MARKED_AS_PAID_TEXT'] = 'Completed: Reservation was marked as paid!';
$lang['NRS_BOOKING_MARK_COMPLETED_EARLY_ERROR_TEXT'] = 'Failed: Reservation was not marked as completed early!';
$lang['NRS_BOOKING_MARKED_COMPLETED_EARLY_TEXT'] = 'Completed: Reservation was marked as completed early!';
$lang['NRS_BOOKING_REFUND_ERROR_TEXT'] = 'Failed: Reservation was not refunded!';
$lang['NRS_BOOKING_REFUNDED_TEXT'] = 'Completed: Reservation was refunded successfully!';

// OK / Error Messages - (Extra) Booking Option Element
$lang['NRS_EXTRA_BOOKING_ID_QUANTITY_OPTION_SKU_ERROR_TEXT'] = 'Error: New extra can&#39;t be blocked because of bad reservation id (%s), SKU (%s) or quantity (%s)!';
$lang['NRS_EXTRA_BOOKING_OPTION_INSERT_ERROR_TEXT'] = 'Error: MySQL insert error for new extra (SKU - %s) reservation/block!';
$lang['NRS_EXTRA_BOOKING_OPTION_INSERTED_TEXT'] = 'Completed: New extra (SKU - %s) has been blocked/reserved!';
$lang['NRS_EXTRA_BOOKING_OPTION_DELETE_ERROR_TEXT'] = 'Error: MySQL delete error for existing reserved/blocked extra. No rows row were deleted from database!';
$lang['NRS_EXTRA_BOOKING_OPTION_DELETED_TEXT'] = 'Completed: Extra has been removed from reservation/block!';

// OK / Error Messages - (Item) Booking Option Element
$lang['NRS_ITEM_BOOKING_ID_QUANTITY_OPTION_SKU_ERROR_TEXT'] = 'Error: New car can&#39;t be blocked because of bad reservation id (%s), SKU (%s) or quantity (%s)!';
$lang['NRS_ITEM_BOOKING_OPTION_INSERT_ERROR_TEXT'] = 'Error: MySQL insert error for new car (SKU - %s) reservation/block!';
$lang['NRS_ITEM_BOOKING_OPTION_INSERTED_TEXT'] = 'Completed: New car (SKU - %s) has been blocked/reserved!';
$lang['NRS_ITEM_BOOKING_OPTION_DELETE_ERROR_TEXT'] = 'Error: MySQL delete error for existing reserved/blocked car. No rows row were deleted from database!';
$lang['NRS_ITEM_BOOKING_OPTION_DELETED_TEXT'] = 'Completed: Car has been removed from reservation/block!';

// OK / Error Messages - Customer Element
$lang['NRS_CUSTOMER_TITLE_REQUIRED_ERROR_TEXT'] = 'Error: Customer title is required!';
$lang['NRS_CUSTOMER_FIRST_NAME_REQUIRED_ERROR_TEXT'] = 'Error: Customer first name is required!';
$lang['NRS_CUSTOMER_LAST_NAME_REQUIRED_ERROR_TEXT'] = 'Error: Customer last name is required!';
$lang['NRS_CUSTOMER_BIRTHDATE_REQUIRED_ERROR_TEXT'] = 'Error: Customer birthdate is required!';
$lang['NRS_CUSTOMER_STREET_ADDRESS_REQUIRED_ERROR_TEXT'] = 'Error: Customer street address is required!';
$lang['NRS_CUSTOMER_CITY_REQUIRED_ERROR_TEXT'] = 'Error: Customer city is required!';
$lang['NRS_CUSTOMER_STATE_REQUIRED_ERROR_TEXT'] = 'Error: Customer state is required!';
$lang['NRS_CUSTOMER_ZIP_CODE_REQUIRED_ERROR_TEXT'] = 'Error: Customer zip code is required!';
$lang['NRS_CUSTOMER_COUNTRY_REQUIRED_ERROR_TEXT'] = 'Error: Customer country is required!';
$lang['NRS_CUSTOMER_PHONE_REQUIRED_ERROR_TEXT'] = 'Error: Customer phone is required!';
$lang['NRS_CUSTOMER_EMAIL_REQUIRED_ERROR_TEXT'] = 'Error: Customer email is required!';
$lang['NRS_CUSTOMER_COMMENTS_REQUIRED_ERROR_TEXT'] = 'Error: Customer comments is required!';
$lang['NRS_CUSTOMER_UPDATE_ERROR_TEXT'] = 'Error: MySQL update error for existing customer!';
$lang['NRS_CUSTOMER_UPDATED_TEXT'] = 'Completed: Customer has been updated successfully!';
$lang['NRS_CUSTOMER_INSERT_ERROR_TEXT'] = 'Error: MySQL insert error for new customer!';
$lang['NRS_CUSTOMER_INSERTED_TEXT'] = 'Completed: New customer has been added successfully!';
$lang['NRS_CUSTOMER_LAST_VISIT_UPDATE_ERROR_TEXT'] = 'Error: MySQL update error for customer last visit date!';
$lang['NRS_CUSTOMER_LAST_VISIT_UPDATED_TEXT'] = 'Completed: Customer last visit date has been updated!';
$lang['NRS_CUSTOMER_DELETE_ERROR_TEXT'] = 'Error: MySQL delete error for existing customer. No rows were deleted from database!';
$lang['NRS_CUSTOMER_DELETED_TEXT'] = 'Completed: Customer has been deleted successfully!';

// OK / Error Messages - Demo Element
$lang['NRS_DEMO_INSERT_ERROR_TEXT'] = 'Error: MySQL insert error in demo data import!';
$lang['NRS_DEMO_INSERTED_TEXT'] = 'Completed: Demo data has been imported successfully!';

// OK / Error Messages - Distance Element
$lang['NRS_DISTANCE_PICKUP_NOT_SELECTED_ERROR_TEXT'] = 'Error: Pick up location must be selected!';
$lang['NRS_DISTANCE_RETURN_NOT_SELECTED_ERROR_TEXT'] = 'Error: Return location must be selected!';
$lang['NRS_DISTANCE_SAME_PICKUP_AND_RETURN_ERROR_TEXT'] = 'Error: Pick-up and return locations cannot be the same!';
$lang['NRS_DISTANCE_EXISTS_ERROR_TEXT'] = 'Error: This distance already exists!';
$lang['NRS_DISTANCE_UPDATE_ERROR_TEXT'] = 'Error: MySQL update error for existing distance!';
$lang['NRS_DISTANCE_UPDATED_TEXT'] = 'Completed: Distance has been updated successfully!';
$lang['NRS_DISTANCE_INSERT_ERROR_TEXT'] = 'Error: MySQL insert error for new distance!';
$lang['NRS_DISTANCE_INSERTED_TEXT'] = 'Completed: New distance has been added successfully!';
$lang['NRS_DISTANCE_DELETE_ERROR_TEXT'] = 'Error: MySQL delete error for existing distance. No rows were deleted from database!';
$lang['NRS_DISTANCE_DELETED_TEXT'] = 'Completed: Distance has been deleted successfully!';

// OK / Error Messages - (Extra) Discount Element
$lang['NRS_EXTRA_DISCOUNT_DAYS_INTERSECTION_ERROR_TEXT'] = 'Error: Extra discount days range intersects other extra discount for select extra (or all extras)!';
$lang['NRS_EXTRA_DISCOUNT_UPDATE_ERROR_TEXT'] = 'Error: MySQL update error for existing extra discount!';
$lang['NRS_EXTRA_DISCOUNT_UPDATED_TEXT'] = 'Completed: Extra discount has been updated successfully!';
$lang['NRS_EXTRA_DISCOUNT_INSERT_ERROR_TEXT'] = 'Error: MySQL insert error for new extra discount!';
$lang['NRS_EXTRA_DISCOUNT_INSERTED_TEXT'] = 'Completed: New extra discount has been added successfully!';
$lang['NRS_EXTRA_DISCOUNT_DELETE_ERROR_TEXT'] = 'Error: MySQL delete error for existing extra discount. No rows were deleted from database!';
$lang['NRS_EXTRA_DISCOUNT_DELETED_TEXT'] = 'Completed: Extra discount has been deleted successfully!';

// OK / Error Messages - (Price Plan) Discount Element
$lang['NRS_PRICE_PLAN_DISCOUNT_DAYS_INTERSECTION_ERROR_TEXT'] = 'Error: Price plan discount days range intersects other discount for specific price plan or all price plans!';
$lang['NRS_PRICE_PLAN_DISCOUNT_UPDATE_ERROR_TEXT'] = 'Error: MySQL update error for existing price plan discount!';
$lang['NRS_PRICE_PLAN_DISCOUNT_UPDATED_TEXT'] = 'Completed: Price plan discount has been updated successfully!';
$lang['NRS_PRICE_PLAN_DISCOUNT_INSERT_ERROR_TEXT'] = 'Error: MySQL insert error for new price plan discount!';
$lang['NRS_PRICE_PLAN_DISCOUNT_INSERTED_TEXT'] = 'Completed: New price plan discount has been added successfully!';
$lang['NRS_PRICE_PLAN_DISCOUNT_DELETE_ERROR_TEXT'] = 'Error: MySQL delete error for existing price plan discount. No rows were deleted from database!';
$lang['NRS_PRICE_PLAN_DISCOUNT_DELETED_TEXT'] = 'Completed: Price plan discount has been deleted successfully!';

// OK / Error Messages - Email Element
$lang['NRS_EMAIL_DEMO_LOCATION_NAME_TEXT'] = 'Demo Um??st??n??';
$lang['NRS_EMAIL_DEMO_LOCATION_PHONE_TEXT'] = '+420 606 123 456';
$lang['NRS_EMAIL_DEMO_LOCATION_EMAIL_TEXT'] = 'info@umisteni.cz';
$lang['NRS_EMAIL_SUBJECT_EXISTS_ERROR_TEXT'] = 'Chyba: Jin?? e-mail existuje s t??mto p??edm??tem!';
$lang['NRS_EMAIL_UPDATE_ERROR_TEXT'] = 'Chyba: MySQL chyba aktualizace pro existuj??c?? email!';
$lang['NRS_EMAIL_UPDATED_TEXT'] = 'Kompletn??: E-mail byl ??sp????n?? aktualizov??n!';
$lang['NRS_EMAIL_REGISTERED_TEXT'] = 'E-mail: T??lo a p??edm??t registrov??n pro p??eklad.';
$lang['NRS_EMAIL_SENDING_ERROR_TEXT'] = 'Chyba: System nem????e odeslat e-mail %s!';
$lang['NRS_EMAIL_SENT_TEXT'] = 'Kompletn??: E-mail byl odesl??n %s!';

// OK / Error Messages - Extra Element
$lang['NRS_EXTRA_SKU_EXISTS_ERROR_TEXT'] = 'Error: Extra with this stock keeping unit (SKU) already exist!';
$lang['NRS_EXTRA_MORE_UNITS_PER_BOOKING_THAN_IN_STOCK_ERROR_TEXT'] = 'Error: You can&#39;t allow to reserve more extra units per one reservation than you have in stack!';
$lang['NRS_EXTRA_ITEM_DOES_NOT_EXIST_ERROR_TEXT'] = 'Error: Selected car does not exist!';
$lang['NRS_EXTRA_ITEM_ASSIGN_ERROR_TEXT'] = 'Error: You are not allowed to assign extras to that car!';
$lang['NRS_EXTRA_ITEM_SELECT_ERROR_TEXT'] = 'Error: Please select a car!';
$lang['NRS_EXTRA_UPDATE_ERROR_TEXT'] = 'Error: MySQL update error for existing extra!';
$lang['NRS_EXTRA_UPDATED_TEXT'] = 'Completed: Extra has been updated successfully!';
$lang['NRS_EXTRA_INSERT_ERROR_TEXT'] = 'Error: MySQL insert error for new extra!';
$lang['NRS_EXTRA_INSERTED_TEXT'] = 'Completed: New extra has been added successfully!';
$lang['NRS_EXTRA_REGISTERED_TEXT'] = 'Extra name registered for translation.';
$lang['NRS_EXTRA_DELETE_ERROR_TEXT'] = 'Error: MySQL delete error for existing extra. No rows were deleted from database!';
$lang['NRS_EXTRA_DELETED_TEXT'] = 'Completed: Extra has been deleted successfully!';

// OK / Error Messages - Feature Element
$lang['NRS_FEATURE_TITLE_EXISTS_ERROR_TEXT'] = 'Error: Feature with this title already exists!';
$lang['NRS_FEATURE_UPDATE_ERROR_TEXT'] = 'Error: MySQL update error for existing feature!';
$lang['NRS_FEATURE_UPDATED_TEXT'] = 'Completed: Feature has been updated successfully!';
$lang['NRS_FEATURE_INSERT_ERROR_TEXT'] = 'Error: MySQL insert error for new feature!';
$lang['NRS_FEATURE_INSERTED_TEXT'] = 'Completed: New feature has been added successfully!';
$lang['NRS_FEATURE_REGISTERED_TEXT'] = 'Feature title registered for translation.';
$lang['NRS_FEATURE_DELETE_ERROR_TEXT'] = 'Error: MySQL delete error for existing feature. No rows were deleted from database!';
$lang['NRS_FEATURE_DELETED_TEXT'] = 'Completed: Feature has been deleted successfully!';

// OK / Error Messages - Fuel Type Element
$lang['NRS_FUEL_TYPE_TITLE_EXISTS_ERROR_TEXT'] = 'Error: Fuel type with this title already exists!';
$lang['NRS_FUEL_TYPE_UPDATE_ERROR_TEXT'] = 'Error: MySQL update error for existing fuel type!';
$lang['NRS_FUEL_TYPE_UPDATED_TEXT'] = 'Completed: Fuel type has been updated successfully!';
$lang['NRS_FUEL_TYPE_INSERT_ERROR_TEXT'] = 'Error: MySQL insert error for new fuel type!';
$lang['NRS_FUEL_TYPE_INSERTED_TEXT'] = 'Completed: New fuel type has been added successfully!';
$lang['NRS_FUEL_TYPE_REGISTERED_TEXT'] = 'Fuel type title registered for translation.';
$lang['NRS_FUEL_TYPE_DELETE_ERROR_TEXT'] = 'Error: MySQL delete error for existing fuel type. No rows were deleted from database!';
$lang['NRS_FUEL_TYPE_DELETED_TEXT'] = 'Completed: Fuel type has been deleted successfully!';

// OK / Error Messages - Install Element
$lang['NRS_INSTALL_INSERT_ERROR_TEXT'] = 'Error on blog #%s: MySQL insert error in new installation!';
$lang['NRS_INSTALL_INSERTED_TEXT'] = 'Completed on blog #%s: Installation data has been inserted successfully!';
$lang['NRS_INSTALL_REPLACE_ERROR_TEXT'] = 'Error on blog #%s: MySQL replace error for installation data!';
$lang['NRS_INSTALL_REPLACED_TEXT'] = 'Completed on blog #%s: Installation data has been replaced successfully!';
$lang['NRS_INSTALL_QUERY_FAILED_FOR_EXTENSION_TABLE_CREATE_ERROR_TEXT'] = 'Error on blog #%s: MySQL extension table creation query could not be processed for &#39;%s&#39; table!';
$lang['NRS_INSTALL_QUERY_FAILED_FOR_EXTENSION_TABLE_DROP_ERROR_TEXT'] = 'Error on blog #%s: MySQL extension table drop query could not be processed for tables:&#39;%s&#39;!';
$lang['NRS_INSTALL_QUERY_FAILED_FOR_WP_TABLE_INSERT_ERROR_TEXT'] = 'Error on blog #%s: MySQL WordPress table insert query could not be processed for &#39;%s&#39; table!';
$lang['NRS_INSTALL_QUERY_FAILED_FOR_EXTENSION_TABLE_INSERT_ERROR_TEXT'] = 'Error on blog #%s: MySQL extension table insert query could not be processed in &#39;%s&#39; table!';
$lang['NRS_INSTALL_QUERY_FAILED_FOR_WP_TABLE_REPLACE_ERROR_TEXT'] = 'Error on blog #%s: MySQL WordPress table replace query could not be processed in &#39;%s&#39; table!';
$lang['NRS_INSTALL_QUERY_FAILED_FOR_EXTENSION_TABLE_REPLACE_ERROR_TEXT'] = 'Error on blog #%s: MySQL extension table replace query could not be processed in &#39;%s&#39; table!';
$lang['NRS_INSTALL_QUERY_FAILED_FOR_WP_TABLE_DELETE_ERROR_TEXT'] = 'Error on blog #%s: MySQL WordPress delete table content query could not be processed in &#39;%s&#39; table!';
$lang['NRS_INSTALL_QUERY_FAILED_FOR_EXTENSION_TABLE_DELETE_ERROR_TEXT'] = 'Error on blog #%s: MySQL extension delete table content query could not be processed for &#39;%s&#39; table!';

// Install Element
$lang['NRS_INSTALL_DEFAULT_COMPANY_NAME_TEXT'] = 'Autop??j??ovna - spole??nost';
$lang['NRS_INSTALL_DEFAULT_COMPANY_STREET_ADDRESS_TEXT'] = '625 2nd Street';
$lang['NRS_INSTALL_DEFAULT_COMPANY_CITY_TEXT'] = 'San Francisco';
$lang['NRS_INSTALL_DEFAULT_COMPANY_STATE_TEXT'] = 'CA';
$lang['NRS_INSTALL_DEFAULT_COMPANY_ZIP_CODE_TEXT'] = '94107';
$lang['NRS_INSTALL_DEFAULT_COMPANY_COUNTRY_TEXT'] = '';
$lang['NRS_INSTALL_DEFAULT_COMPANY_PHONE_TEXT'] = '(450) 600 4000';
$lang['NRS_INSTALL_DEFAULT_COMPANY_EMAIL_TEXT'] = 'info@yourdomain.com';
$lang['NRS_INSTALL_DEFAULT_PAGE_URL_SLUG_TEXT'] = 'car-pujcovna'; // Latin letters only
$lang['NRS_INSTALL_DEFAULT_ITEM_URL_SLUG_TEXT'] = 'auto'; // Latin letters only
$lang['NRS_INSTALL_DEFAULT_LOCATION_URL_SLUG_TEXT'] = 'auto-umisteni'; // Latin letters only
$lang['NRS_INSTALL_DEFAULT_SEARCH_PAGE_URL_SLUG_TEXT'] = 'search'; // Latin letters only
$lang['NRS_INSTALL_DEFAULT_CANCELLED_PAYMENT_PAGE_TITLE_TEXT'] = 'Platba zru??ena';
$lang['NRS_INSTALL_DEFAULT_CANCELLED_PAYMENT_PAGE_CONTENT_TEXT'] = 'Platba byla zru??ena. Va??e rezervace nen?? potvrzena.';
$lang['NRS_INSTALL_DEFAULT_CONFIRMATION_PAGE_TITLE_TEXT'] = 'Rezervace potvrzena';
$lang['NRS_INSTALL_DEFAULT_CONFIRMATION_PAGE_CONTENT_TEXT'] = 'D??kujeme V??m. Va???? platbu jsme p??ijali. Rezervace je potvrzen??.';
$lang['NRS_INSTALL_DEFAULT_TERMS_AND_CONDITIONS_PAGE_TITLE_TEXT'] = 'Podm??nky pro zap??j??en?? vozu.';
$lang['NRS_INSTALL_DEFAULT_TERMS_AND_CONDITIONS_PAGE_CONTENT_TEXT'] = 'Mus??te souhlasit s n??sleduj??c??mi &amp; podm??nkami pro zap??j??en?? vozu.';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAYPAL_TEXT'] = 'Online - PayPal';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAYPAL_DESCRIPTION_TEXT'] = 'Zabezpe??en?? okam??it?? platba';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_STRIPE_TEXT'] = 'Credit Card (p??es Stripe.com)';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_BANK_TEXT'] = 'Bankovn?? p??evod';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_BANK_DETAILS_TEXT'] = 'Va??e bankovn?? ??daje';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAY_OVER_THE_PHONE_TEXT'] = 'Platba p??es telefon';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAY_ON_ARRIVAL_TEXT'] = 'Platba p??i vyzvednut?? vozu';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAY_ON_ARRIVAL_DETAILS_TEXT'] = 'Platebn?? karta nutn??';
$lang['NRS_INSTALL_DEFAULT_DEAR_TEXT'] = 'V????en??';
$lang['NRS_INSTALL_DEFAULT_REGARDS_TEXT'] = 'S pozdravem';
$lang['NRS_INSTALL_DEFAULT_EMAIL_TITLE_BOOKING_DETAILS_TEXT'] = 'Detaily rezervace - ??. [BOOKING_CODE]';
$lang['NRS_INSTALL_DEFAULT_EMAIL_TITLE_BOOKING_CONFIRMED_TEXT'] = 'Rezervace ??. [BOOKING_CODE] - potvrzena';
$lang['NRS_INSTALL_DEFAULT_EMAIL_TITLE_BOOKING_CANCELLED_TEXT'] = 'Rezervace ??. [BOOKING_CODE] - zru??ena';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_TITLE_BOOKING_DETAILS_TEXT'] = 'Upozorn??n??: nov?? rezervace - [BOOKING_CODE]';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_TITLE_BOOKING_CONFIRMED_TEXT'] = 'Upozorn??n??: rezervace zaplacena - [BOOKING_CODE]';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_TITLE_BOOKING_CANCELLED_TEXT'] = 'Upozorn??n??: rezervace zru??ena - [BOOKING_CODE]';
$lang['NRS_INSTALL_DEFAULT_EMAIL_BODY_BOOKING_RECEIVED_TEXT'] = 'Detaily Va???? rezervace doru??eny.';
$lang['NRS_INSTALL_DEFAULT_EMAIL_BODY_BOOKING_DETAILS_TEXT'] = 'Detaily Va???? rezervace:';
$lang['NRS_INSTALL_DEFAULT_EMAIL_BODY_PAYMENT_RECEIVED_TEXT'] = 'P??ijali name Va???? platbu. Va??e rezervace je nyn?? POTVRZENA.';
$lang['NRS_INSTALL_DEFAULT_EMAIL_BODY_BOOKING_CANCELLED_TEXT'] = 'Va??e rezercace ??. [BOOKING_CODE] byla zru??ena.';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_BOOKING_RECEIVED_TEXT'] = 'Nov?? rezervace ??. [BOOKING_CODE] p??ijata od [CUSTOMER_NAME].';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_BOOKING_DETAILS_TEXT'] = 'Detaily rezervace:';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_BOOKING_PAID_TEXT'] = 'Rezervace ??. [BOOKING_CODE] byla uhrazena [CUSTOMER_NAME].';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_BOOKING_CANCELLED_TEXT'] = 'Rezervace ??. [BOOKING_CODE] pro [CUSTOMER_NAME] byla zru??ena.';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_CANCELLED_BOOKING_DETAILS_TEXT'] = 'Detaily rezervace, kter?? byla zru??ena:';

// OK / Error Messages - Invoice Element
$lang['NRS_INVOICE_UPDATE_ERROR_TEXT'] = 'Error: MySQL update error for existing invoice!';
$lang['NRS_INVOICE_UPDATED_TEXT'] = 'Completed: Invoice has been updated successfully!';
$lang['NRS_INVOICE_INSERT_ERROR_TEXT'] = 'Error: MySQL insert error for new invoice!';
$lang['NRS_INVOICE_INSERTED_TEXT'] = 'Completed: Invoice has been added successfully!';
$lang['NRS_INVOICE_APPEND_ERROR_TEXT'] = 'Error: MySQL update error when trying to append the existing invoice. No rows were updated in database!';
$lang['NRS_INVOICE_APPENDED_TEXT'] = 'Completed: Invoice has been appended successfully!';
$lang['NRS_INVOICE_DELETE_ERROR_TEXT'] = 'Error: MySQL delete error for existing invoice. No rows were deleted from database!';
$lang['NRS_INVOICE_DELETED_TEXT'] = 'Completed: Invoice has been deleted successfully!';

// OK / Error Messages - Item Element
$lang['NRS_ITEM_SKU_EXISTS_ERROR_TEXT'] = 'Error: Car with this stock keeping unit (SKU) already exist!';
$lang['NRS_ITEM_MORE_UNITS_PER_BOOKING_THAN_IN_STOCK_ERROR_TEXT'] = 'Error: You can&#39;t allow to reserve more car units per one reservation than you have in garage!';
$lang['NRS_ITEM_UPDATE_ERROR_TEXT'] = 'Error: MySQL update error for existing car!';
$lang['NRS_ITEM_UPDATED_TEXT'] = 'Completed: Car details has been updated successfully!';
$lang['NRS_ITEM_INSERT_ERROR_TEXT'] = 'Error: MySQL insert error for new car!';
$lang['NRS_ITEM_INSERTED_TEXT'] = 'Completed: New car has been added successfully!';
$lang['NRS_ITEM_REGISTERED_TEXT'] = 'Car model name registered for translation.';
$lang['NRS_ITEM_DELETE_ERROR_TEXT'] = 'Error: MySQL delete error for existing car. No rows were deleted from database!';
$lang['NRS_ITEM_DELETED_TEXT'] = 'Completed: Car has been deleted successfully!';

// OK / Error Messages - Location Element
$lang['NRS_LOCATION_CODE_EXISTS_ERROR_TEXT'] = 'Error: Location with this code already exists!';
$lang['NRS_LOCATION_NAME_EXISTS_ERROR_TEXT'] = 'Error: Location with this name already exists!';
$lang['NRS_LOCATION_UPDATE_ERROR_TEXT'] = 'Error: MySQL update error for existing location!';
$lang['NRS_LOCATION_UPDATED_TEXT'] = 'Completed: Location has been updated successfully!';
$lang['NRS_LOCATION_INSERT_ERROR_TEXT'] = 'Error: MySQL insert error for new location!';
$lang['NRS_LOCATION_INSERTED_TEXT'] = 'Completed: New location has been added successfully!';
$lang['NRS_LOCATION_REGISTERED_TEXT'] = 'Location name registered for translation.';
$lang['NRS_LOCATION_DELETE_ERROR_TEXT'] = 'Error: MySQL delete error for existing location. No rows were deleted from database!';
$lang['NRS_LOCATION_DELETED_TEXT'] = 'Completed: Location has been deleted successfully!';

// OK / Error Messages - Manufacturer Element
$lang['NRS_MANUFACTURER_TITLE_EXISTS_ERROR_TEXT'] = 'Error: Manufacturer with this title already exists!';
$lang['NRS_MANUFACTURER_UPDATE_ERROR_TEXT'] = 'Error: MySQL update error for existing manufacturer!';
$lang['NRS_MANUFACTURER_UPDATED_TEXT'] = 'Completed: Manufacturer has been updated successfully!';
$lang['NRS_MANUFACTURER_INSERT_ERROR_TEXT'] = 'Error: MySQL insert error for new manufacturer!';
$lang['NRS_MANUFACTURER_INSERTED_TEXT'] = 'Completed: New manufacturer has been added successfully!';
$lang['NRS_MANUFACTURER_REGISTERED_TEXT'] = 'Manufacturer title registered for translation.';
$lang['NRS_MANUFACTURER_DELETE_ERROR_TEXT'] = 'Error: MySQL delete error for existing manufacturer. No rows were deleted from database!';
$lang['NRS_MANUFACTURER_DELETED_TEXT'] = 'Completed: Manufacturer has been deleted successfully!';

// OK / Error Messages - (Extra) Option Element
$lang['NRS_EXTRA_OPTION_PLEASE_SELECT_ERROR_TEXT'] = 'Error: Please select an extra!';
$lang['NRS_EXTRA_OPTION_NAME_EXISTS_ERROR_TEXT'] = 'Error: Option with chosen name already exists for this extra!';
$lang['NRS_EXTRA_OPTION_UPDATE_ERROR_TEXT'] = 'Error: MySQL update error for existing extra option!';
$lang['NRS_EXTRA_OPTION_UPDATED_TEXT'] = 'Completed: Extra option has been updated successfully!';
$lang['NRS_EXTRA_OPTION_INSERT_ERROR_TEXT'] = 'Error: MySQL insert error for new extra option!';
$lang['NRS_EXTRA_OPTION_INSERTED_TEXT'] = 'Completed: New extra option has been added successfully!';
$lang['NRS_EXTRA_OPTION_REGISTERED_TEXT'] = 'Extra option name registered for translation.';
$lang['NRS_EXTRA_OPTION_DELETE_ERROR_TEXT'] = 'Error: MySQL delete error for existing extra option. No rows were deleted from database!';
$lang['NRS_EXTRA_OPTION_DELETED_TEXT'] = 'Completed: Extra option has been deleted successfully!';

// OK / Error Messages - (Item) Option Element
$lang['NRS_ITEM_OPTION_PLEASE_SELECT_ERROR_TEXT'] = 'Error: Please select a car!';
$lang['NRS_ITEM_OPTION_NAME_EXISTS_ERROR_TEXT'] = 'Error: Option with chosen name already exists for this car!';
$lang['NRS_ITEM_OPTION_UPDATE_ERROR_TEXT'] = 'Error: MySQL update error for existing car option!';
$lang['NRS_ITEM_OPTION_UPDATED_TEXT'] = 'Completed: Car option has been updated successfully!';
$lang['NRS_ITEM_OPTION_INSERT_ERROR_TEXT'] = 'Error: MySQL insert error for new car option!';
$lang['NRS_ITEM_OPTION_INSERTED_TEXT'] = 'Completed: New car option has been added successfully!';
$lang['NRS_ITEM_OPTION_REGISTERED_TEXT'] = 'Car option name registered for translation.';
$lang['NRS_ITEM_OPTION_DELETE_ERROR_TEXT'] = 'Error: MySQL delete error for existing car option. No rows were deleted from database!';
$lang['NRS_ITEM_OPTION_DELETED_TEXT'] = 'Completed: Car option has been deleted successfully!';

// OK / Error Messages - Payment Method Element
$lang['NRS_PAYMENT_METHOD_CODE_EXISTS_ERROR_TEXT'] = 'Error: Payment method with this code already exist!';
$lang['NRS_PAYMENT_METHOD_INVALID_NAME_TEXT'] = 'Error: Please enter valid payment method name!';
$lang['NRS_PAYMENT_METHOD_UPDATE_ERROR_TEXT'] = 'Error: MySQL update error for existing payment method!';
$lang['NRS_PAYMENT_METHOD_UPDATED_TEXT'] = 'Completed: Payment method has been updated successfully!';
$lang['NRS_PAYMENT_METHOD_INSERT_ERROR_TEXT'] = 'Error: MySQL insert error for new payment method!';
$lang['NRS_PAYMENT_METHOD_INSERTED_TEXT'] = 'Completed: New payment method has been added successfully!';
$lang['NRS_PAYMENT_METHOD_REGISTERED_TEXT'] = 'Payment method name and description registered for translation.';
$lang['NRS_PAYMENT_METHOD_DELETE_ERROR_TEXT'] = 'Error: MySQL delete error for existing payment method. No rows were deleted from database!';
$lang['NRS_PAYMENT_METHOD_DELETED_TEXT'] = 'Completed: Payment method has been deleted successfully!';

// OK / Error Messages - Prepayment Element
$lang['NRS_PREPAYMENT_DAYS_INTERSECTION_ERROR_TEXT'] = 'Error: Prepayment plan days range intersects other prepayment plan!';
$lang['NRS_PREPAYMENT_UPDATE_ERROR_TEXT'] = 'Error: MySQL update error for existing prepayment plan!';
$lang['NRS_PREPAYMENT_UPDATED_TEXT'] = 'Completed: Prepayment plan has been updated successfully!';
$lang['NRS_PREPAYMENT_INSERT_ERROR_TEXT'] = 'Error: MySQL insert error for new prepayment plan!';
$lang['NRS_PREPAYMENT_INSERTED_TEXT'] = 'Completed: New prepayment plan has been added successfully!';
$lang['NRS_PREPAYMENT_DELETE_ERROR_TEXT'] = 'Error: MySQL delete error for existing prepayment plan. No rows were deleted from database!';
$lang['NRS_PREPAYMENT_DELETED_TEXT'] = 'Completed: Prepayment plan has been deleted successfully!';

// OK / Error Messages - Price Group Element
$lang['NRS_PRICE_GROUP_UPDATE_ERROR_TEXT'] = 'Error: MySQL update error for existing price group!';
$lang['NRS_PRICE_GROUP_UPDATED_TEXT'] = 'Completed: Price group has been updated successfully!';
$lang['NRS_PRICE_GROUP_INSERT_ERROR_TEXT'] = 'Error: MySQL insert error for new price group!';
$lang['NRS_PRICE_GROUP_INSERTED_TEXT'] = 'Completed: New price group has been added successfully!';
$lang['NRS_PRICE_GROUP_REGISTERED_TEXT'] = 'Price group name registered for translation.';
$lang['NRS_PRICE_GROUP_DELETE_ERROR_TEXT'] = 'Error: MySQL delete error for existing price group. No rows were deleted from database!';
$lang['NRS_PRICE_GROUP_DELETED_TEXT'] = 'Completed: Price group has been deleted successfully!';

// OK / Error Messages - Price Plan Element
$lang['NRS_PRICE_PLAN_LATER_DATE_ERROR_TEXT'] = 'Error: Start date can&#39;t be later or equal than end date!';
$lang['NRS_PRICE_PLAN_INVALID_PRICE_GROUP_ERROR_TEXT'] = 'Error: Please select valid price group!';
$lang['NRS_PRICE_PLAN_EXISTS_FOR_DATE_RANGE_ERROR_TEXT'] = 'Error: There is an existing price plan for this date range without coupon code or with same coupon code!';
$lang['NRS_PRICE_PLAN_UPDATE_ERROR_TEXT'] = 'Error: MySQL update error for existing price plan!';
$lang['NRS_PRICE_PLAN_UPDATED_TEXT'] = 'Completed: Price plan has been updated successfully!';
$lang['NRS_PRICE_PLAN_INSERT_ERROR_TEXT'] = 'Error: MySQL insert error for new price plan!';
$lang['NRS_PRICE_PLAN_INSERTED_TEXT'] = 'Completed: New price plan has been added successfully!';
$lang['NRS_PRICE_PLAN_DELETE_ERROR_TEXT'] = 'Error: MySQL delete error for existing price plan. No rows were deleted from database!';
$lang['NRS_PRICE_PLAN_DELETED_TEXT'] = 'Completed: Price plan has been deleted successfully!';

// OK / Error Messages - Tax Element
$lang['NRS_TAX_UPDATE_ERROR_TEXT'] = 'Error: MySQL update error for existing tax!';
$lang['NRS_TAX_UPDATED_TEXT'] = 'Completed: Tax has been updated successfully!';
$lang['NRS_TAX_INSERT_ERROR_TEXT'] = 'Error: MySQL insert error for new tax!';
$lang['NRS_TAX_INSERTED_TEXT'] = 'Completed: New tax has been added successfully!';
$lang['NRS_TAX_REGISTERED_TEXT'] = 'Tax name registered for translation.';
$lang['NRS_TAX_DELETE_ERROR_TEXT'] = 'Error: MySQL delete error for existing tax. No rows were deleted from database!';
$lang['NRS_TAX_DELETED_TEXT'] = 'Completed: Tax has been deleted successfully!';

// OK / Error Messages - Transmission Type Element
$lang['NRS_TRANSMISSION_TYPE_TITLE_EXISTS_ERROR_TEXT'] = 'Error: Transmission type with this title already exists!';
$lang['NRS_TRANSMISSION_TYPE_UPDATE_ERROR_TEXT'] = 'Error: MySQL update error for existing transmission type!';
$lang['NRS_TRANSMISSION_TYPE_UPDATED_TEXT'] = 'Completed: Transmission type has been updated successfully!';
$lang['NRS_TRANSMISSION_TYPE_INSERT_ERROR_TEXT'] = 'Error: MySQL insert error for new transmission type!';
$lang['NRS_TRANSMISSION_TYPE_INSERTED_TEXT'] = 'Completed: New transmission type has been added successfully!';
$lang['NRS_TRANSMISSION_TYPE_REGISTERED_TEXT'] = 'Transmission type title registered for translation.';
$lang['NRS_TRANSMISSION_TYPE_DELETE_ERROR_TEXT'] = 'Error: MySQL delete error for existing transmission type. No rows were deleted from database!';
$lang['NRS_TRANSMISSION_TYPE_DELETED_TEXT'] = 'Completed: Transmission type has been deleted successfully!';

// OK / Error Messages - (Database) Update
$lang['NRS_DATABASE_UPDATE_EARLY_STRUCTURE_ALTER_ERROR_TEXT'] = 'Error on blog #%s: MySQL alter error for plugin early structure!';
$lang['NRS_DATABASE_UPDATE_EARLY_STRUCTURE_ALTERED_TEXT'] = 'Completed on blog #%s: Plugin early structure has been altered successfully!';
$lang['NRS_DATABASE_UPDATE_LATE_STRUCTURE_ALTER_ERROR_TEXT'] = 'Error on blog #%s: MySQL alter error for plugin late structure!';
$lang['NRS_DATABASE_UPDATE_LATE_STRUCTURE_ALTERED_TEXT'] = 'Completed on blog #%s: Plugin late structure has been altered successfully!';
$lang['NRS_DATABASE_UPDATE_DATA_UPDATE_ERROR_TEXT'] = 'Error on blog #%s: MySQL update error for plugin data!';
$lang['NRS_DATABASE_UPDATE_DATA_UPDATED_TEXT'] = 'Completed on blog #%s: Plugin data has been updated successfully!';
$lang['NRS_DATABASE_UPDATE_QUERY_FAILED_FOR_TABLE_ERROR_TEXT'] = 'Error on blog #%s: MySQL query could not be processed in &#39;%s&#39; table at counter #%s!';
$lang['NRS_DATABASE_UPDATE_VERSION_UPDATE_ERROR_TEXT'] = 'Error on blog #%s: MySQL update error for plugin database version!';
$lang['NRS_DATABASE_UPDATE_VERSION_UPDATED_TEXT'] = 'Completed on blog #%s: Plugin database version has been updated to %s!';
$lang['NRS_DATABASE_UPDATE_NOT_ALLOWED_ERROR_TEXT'] = 'Failed: You are not allowed to update. You either already updated the plugin, 
or you have plugin version older than 4.0 (in that case please install 4.X plugin version first).';
$lang['NRS_DATABASE_UPDATE_UPGRADE_NOT_ALLOWED_ERROR_TEXT'] = 'Failed: You are not allowed to upgrade. You either already upgraded the plugin, 
or you have plugin version older than 4.0 (in that case please install 4.X plugin version first).';
$lang['NRS_DATABASE_UPDATE_NETWORK_UPDATE_NOT_ALLOWED_ERROR_TEXT'] = 'Failed: You are not allowed to network-update. You either already updated the plugin, 
or you have plugin version older than 4.0 (in that case please install 4.X plugin version first).';
$lang['NRS_DATABASE_UPDATE_NETWORK_UPGRADE_NOT_ALLOWED_ERROR_TEXT'] = 'Failed: You are not allowed to network-upgrade. You either already upgraded the plugin, 
or you have plugin version older than 4.0 (in that case please install 4.X plugin version first).';