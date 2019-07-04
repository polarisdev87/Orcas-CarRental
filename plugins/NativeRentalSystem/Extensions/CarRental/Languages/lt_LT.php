<?php
/**
 * Language specific file
 * @Language - Lithuanian
 * @Author - Kestutis Matuliauskas, Ugnius Persė
 * @Email - info@hackathon.lt, ugnius@perse.lt
 * @Website - http://codecanyon.net/user/KestutisIT, http://perse.lt/
 */
// Settings
$lang['LTR'] = FALSE;
$lang['NRS_RECAPTCHA_LANG'] = 'lt';

// Roles
$lang['NRS_PARTNER_ROLE_NAME_TEXT'] = 'Automobilių partneris';
$lang['NRS_ASSISTANT_ROLE_NAME_TEXT'] = 'Automobilių asistentas';
$lang['NRS_MANAGER_ROLE_NAME_TEXT'] = 'Automobilių vadybininkas';

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
$lang['NRS_ADMIN_VIEW_DETAILS_TEXT'] = 'Peržiūrėti';
$lang['NRS_ADMIN_VIEW_BOOKINGS_TEXT'] = 'Rezervacijos';
$lang['NRS_ADMIN_VIEW_UNPAID_BOOKINGS_TEXT'] = 'Neapmokėtos rezervacijos';
$lang['NRS_ADMIN_NO_BOOKINGS_YET_TEXT'] = 'Rezervacijų nėra';
$lang['NRS_ADMIN_BOOKING_DETAILS_TEXT'] = 'Rezervacijos duomenys';
$lang['NRS_ADMIN_CUSTOMER_DETAILS_FROM_DB_TEXT'] = 'Kliento duomenys (pagal naujausia versiją iš duomenų bazės)';
$lang['NRS_ADMIN_BOOKING_STATUS_TEXT'] = 'Rezervacijos būsena';
$lang['NRS_ADMIN_BOOKING_STATUS_UPCOMING_TEXT'] = 'Artėjantis';
$lang['NRS_ADMIN_BOOKING_STATUS_DEPARTED_TEXT'] = 'Išvyko';
$lang['NRS_ADMIN_BOOKING_STATUS_COMPLETED_EARLY_TEXT'] = 'Anksti pabaigtas';
$lang['NRS_ADMIN_BOOKING_STATUS_COMPLETED_TEXT'] = 'Pabaigtas';
$lang['NRS_ADMIN_BOOKING_STATUS_ACTIVE_TEXT'] = 'Aktyvus';
$lang['NRS_ADMIN_BOOKING_STATUS_CANCELLED_TEXT'] = 'Atšauktas';
$lang['NRS_ADMIN_BOOKING_STATUS_PAID_TEXT'] = 'Apmokėtas';
$lang['NRS_ADMIN_BOOKING_STATUS_UNPAID_TEXT'] = 'Neapmokėta';
$lang['NRS_ADMIN_BOOKING_STATUS_REFUNDED_TEXT'] = 'Grąžinta';
$lang['NRS_ADMIN_PRINT_INVOICE_TEXT'] = 'Spausdinti sąskaitą';
$lang['NRS_ADMIN_BACK_TO_CUSTOMER_BOOKING_LIST_TEXT'] = 'Grįžti į kliento rezervacijų sąrašą';
$lang['NRS_ADMIN_CUSTOMERS_BY_LAST_VISIT_TEXT'] = 'Vartotojai pagal paskutinį apsilankymą';
$lang['NRS_ADMIN_CUSTOMERS_BY_REGISTRATION_TEXT'] = 'Vartotojai pagal registracijos datą';
$lang['NRS_ADMIN_BOOKINGS_PERIOD_FROM_TO_TEXT'] = 'Rezerv. laikotarpis: %s - %s';
$lang['NRS_ADMIN_PICKUPS_PERIOD_FROM_TO_TEXT'] = 'Paėm. laikotarpis: %s - %s';
$lang['NRS_ADMIN_RETURNS_PERIOD_FROM_TO_TEXT'] = 'Grąž. laikotarpis: %s - %s';
$lang['NRS_ADMIN_UPCOMING_TEXT'] = 'Artimiausi';
$lang['NRS_ADMIN_PAST_TEXT'] = 'Praėję';
$lang['NRS_ADMIN_CUSTOMER_BOOKINGS_TEXT'] = 'Vartotojų rezervacijos';
$lang['NRS_ADMIN_BOOKINGS_BY_TEXT'] = 'Rezervacijos pagal %s';
$lang['NRS_ADMIN_ALL_BOOKINGS_TEXT'] = 'Visos rezervacijos';
$lang['NRS_ADMIN_ALL_PICKUPS_TEXT'] = 'Visi paėmimai';
$lang['NRS_ADMIN_ALL_RETURNS_TEXT'] = 'Visi grąžinimai';
$lang['NRS_ADMIN_MAX_ITEM_UNITS_PER_BOOKING_TEXT'] = 'Maksimalus automobilių kiekis per rezervaciją';
$lang['NRS_ADMIN_TOTAL_ITEM_UNITS_IN_STOCK_TEXT'] = 'Iš viso automobilių garaže';
$lang['NRS_ADMIN_MAX_EXTRA_UNITS_PER_BOOKING_TEXT'] = 'Maksimalus priedų kiekis per rezervaciją';
$lang['NRS_ADMIN_TOTAL_EXTRA_UNITS_IN_STOCK_TEXT'] = 'Viso priedo vienetų';
$lang['NRS_ADMIN_ITEM_PRICES_TEXT'] = 'Automobilių kainos';
$lang['NRS_ADMIN_ITEM_DEPOSITS_TEXT'] = 'Automobilių užstatai';
$lang['NRS_ADMIN_EXTRA_PRICES_TEXT'] = 'Priedų kainos';
$lang['NRS_ADMIN_EXTRA_DEPOSITS_TEXT'] = 'Priedų užstatai';
$lang['NRS_ADMIN_PICKUP_FEES_TEXT'] = 'Paėmimo mokesčiai';
$lang['NRS_ADMIN_DISTANCE_FEES_TEXT'] = 'Atstumo mokesčiai';
$lang['NRS_ADMIN_RETURN_FEES_TEXT'] = 'Grąžinimo mokesčiai';
$lang['NRS_ADMIN_REGULAR_PRICE_TEXT'] = 'Įprasta kaina';
$lang['NRS_ADMIN_PRICE_TYPE_TEXT'] = 'Kainos rūšis';
$lang['NRS_ADMIN_ON_THE_LEFT_TEXT'] = 'Kairėje pusėje';
$lang['NRS_ADMIN_ON_THE_RIGHT_TEXT'] = 'Dešinėje pusėje';
$lang['NRS_ADMIN_LOAD_FROM_OTHER_PLACE_TEXT'] = 'Įkrauti iš kitos vietos';
$lang['NRS_ADMIN_LOAD_FROM_PLUGIN_TEXT'] = 'Įkrauti iš šio įskiepio';
$lang['NRS_ADMIN_EMAIL_TEXT'] = 'El. paštas';
$lang['NRS_ADMIN_PUBLIC_TEXT'] = 'Viešas';
$lang['NRS_ADMIN_PRIVATE_TEXT'] = 'Slaptas';
$lang['NRS_ADMIN_CALENDAR_NO_CALENDARS_FOUND_TEXT'] = 'Pasirinktam laikotarpiui kalendorių nerasta';
$lang['NRS_ADMIN_CHOOSE_PAGE_TEXT'] = ' - Pasirinkite puslapį - ';
$lang['NRS_ADMIN_SELECT_EMAIL_TYPE_TEXT'] = '--- Pasirinkite el. laišką ---';
$lang['NRS_ADMIN_TOTAL_REQUESTS_LEFT_TEXT'] = 'Total requests left';
$lang['NRS_ADMIN_FAILED_REQUESTS_LEFT_TEXT'] = 'failed requests left';
$lang['NRS_ADMIN_EMAIL_ATTEMPTS_LEFT_TEXT'] = 'e-mail attempts left';

// Admin Menu
$lang['NRS_ADMIN_MENU_EXTENSION_PAGE_TITLE_TEXT'] = 'Nuomos sistema';
$lang['NRS_ADMIN_MENU_EXTENSION_LABEL_TEXT'] = 'Nuomos sistema';
$lang['NRS_ADMIN_MENU_SYSTEM_UPDATE_TEXT'] = 'Sistemos atnaujinimas';
$lang['NRS_ADMIN_MENU_NETWORK_UPDATE_TEXT'] = 'Tinklo atnaujinimas';
// Admin Menu - Benefit Manager
$lang['NRS_ADMIN_MENU_BENEFIT_MANAGER_TEXT'] = 'Privalumai';
$lang['NRS_ADMIN_MENU_ADD_EDIT_BENEFIT_TEXT'] = 'Pridėti / redaguoti privalumą';
// Admin Menu - Item Manager
$lang['NRS_ADMIN_MENU_ITEM_MANAGER_TEXT'] = 'Automobiliai';
$lang['NRS_ADMIN_MENU_ADD_EDIT_ITEM_TEXT'] = 'Pridėti / redaguoti automobilį';
$lang['NRS_ADMIN_MENU_ADD_EDIT_MANUFACTURER_TEXT'] = 'Pridėti / redaguoti gamintoją';
$lang['NRS_ADMIN_MENU_ADD_EDIT_BODY_TYPE_TEXT'] = 'Pridėti / redaguoti kėbulo tipą';
$lang['NRS_ADMIN_MENU_ADD_EDIT_FUEL_TYPE_TEXT'] = 'Pridėti / redaguoti kuro tipą';
$lang['NRS_ADMIN_MENU_ADD_EDIT_TRANSMISSION_TYPE_TEXT'] = 'Pridėti / redaguoti pavarų dėžės tipą';
$lang['NRS_ADMIN_MENU_ADD_EDIT_FEATURE_TEXT'] = 'Pridėti / redaguoti ypatybę';
$lang['NRS_ADMIN_MENU_ADD_EDIT_ITEM_OPTION_TEXT'] = 'Pridėti / redaguoti automobilio pasirinkimą';
$lang['NRS_ADMIN_MENU_BLOCK_ITEM_TEXT'] = 'Block Car';
// Admin Menu - Item Prices
$lang['NRS_ADMIN_MENU_ITEM_PRICES_TEXT'] = 'Automobilių kainos';
$lang['NRS_ADMIN_MENU_ADD_EDIT_PRICE_GROUP_TEXT'] = 'Pridėti / redaguoti kainų grupę';
$lang['NRS_ADMIN_MENU_ADD_EDIT_PRICE_PLAN_TEXT'] = 'Pridėti / redaguoti automobilio kainos planą';
$lang['NRS_ADMIN_MENU_ADD_EDIT_ITEM_DISCOUNT_TEXT'] = 'Pridėti / redaguoti automobilio nuolaidą';
// Admin Menu - Extras Manager
$lang['NRS_ADMIN_MENU_EXTRAS_MANAGER_TEXT'] = 'Priedai';
$lang['NRS_ADMIN_MENU_ADD_EDIT_EXTRA_TEXT'] = 'Pridėti / redaguoti papildomai';
$lang['NRS_ADMIN_MENU_ADD_EDIT_EXTRA_OPTION_TEXT'] = 'Pridėti / redaguoti papildomą variantą';
$lang['NRS_ADMIN_MENU_ADD_EDIT_EXTRA_DISCOUNT_TEXT'] = 'Pridėti / redaguoti papildomą nuolaidą';
$lang['NRS_ADMIN_MENU_BLOCK_EXTRA_TEXT'] = 'Block Extra';
// Admin Menu - Location Manager
$lang['NRS_ADMIN_MENU_LOCATION_MANAGER_TEXT'] = 'Nuomos vietos';
$lang['NRS_ADMIN_MENU_ADD_EDIT_LOCATION_TEXT'] = 'Pridėti / redaguoti vietą';
$lang['NRS_ADMIN_MENU_ADD_EDIT_DISTANCE_TEXT'] = 'Pridėti / redaguoti  atstumą';
// Admin Menu - Reservation Manager
$lang['NRS_ADMIN_MENU_BOOKING_MANAGER_TEXT'] = 'Rezervacijos';
$lang['NRS_ADMIN_MENU_BOOKING_SEARCH_RESULTS_TEXT'] = 'Rezervacijos paieškos rezultatai';
$lang['NRS_ADMIN_MENU_ITEM_CALENDAR_SEARCH_RESULTS_TEXT'] = 'Automobilių kalendoriaus paieškos rezultatai';
$lang['NRS_ADMIN_MENU_EXTRAS_CALENDAR_SEARCH_TEXT'] = 'Papildomi paieškos kalendoriaus paieškos rezultatai';
$lang['NRS_ADMIN_MENU_CUSTOMER_SEARCH_RESULTS_TEXT'] = 'Vartotojo paieškos rezultatai';
$lang['NRS_ADMIN_MENU_ADD_EDIT_CUSTOMER_TEXT'] = 'Pridėti/redaguoti vartotoją';
$lang['NRS_ADMIN_MENU_ADD_EDIT_BOOKING_TEXT'] = 'Pridėti/redaguoti rezervaciją';
$lang['NRS_ADMIN_MENU_VIEW_DETAILS_TEXT'] = 'Peržiūrėti rezervacijos detales';
$lang['NRS_ADMIN_MENU_PRINT_INVOICE_TEXT'] = 'Spausdinti sąskaitą';
// Admin Menu - Payments & Taxes
$lang['NRS_ADMIN_MENU_PAYMENTS_AND_TAXES_TEXT'] = 'Mokėjimo būdai';
$lang['NRS_ADMIN_MENU_ADD_EDIT_PAYMENT_METHOD_TEXT'] = 'Pridėti/redaguoti apmokėjimo būdą';
$lang['NRS_ADMIN_MENU_ADD_EDIT_PREPAYMENT_TEXT'] = 'Pridėti/redaguoti išankstinį mokėjimą';
$lang['NRS_ADMIN_MENU_ADD_EDIT_TAX_TEXT'] = 'Pridėti/redaguoti mokestį';
// Admin Menu - Settings
$lang['NRS_ADMIN_MENU_SINGLE_MANAGER_TEXT'] = 'Nustatymai';
$lang['NRS_ADMIN_MENU_ADD_EDIT_GLOBAL_SETTINGS_TEXT'] = 'Pridėti/redaguoti pagrindinius nustatymus';
$lang['NRS_ADMIN_MENU_ADD_EDIT_CUSTOMER_SETTINGS_TEXT'] = 'Pridėti/redaguoti vartotojo nustatymus';
$lang['NRS_ADMIN_MENU_ADD_EDIT_SEARCH_SETTINGS_TEXT'] = 'Pridėti/redaguoti paieškos nustatymus';
$lang['NRS_ADMIN_MENU_ADD_EDIT_PRICE_SETTINGS_TEXT'] = 'Pridėti/redaguoti kainos nustatymus';
$lang['NRS_ADMIN_MENU_ADD_EDIT_EMAIL_TEXT'] = 'Pridėti/redaguoti el. laišką';
$lang['NRS_ADMIN_MENU_IMPORT_DEMO_TEXT'] = 'Įkelti demonstraciją';
$lang['NRS_ADMIN_MENU_CONTENT_PREVIEW_TEXT'] = 'Turinio peržiūra';
// Admin Menu - Instructions
$lang['NRS_ADMIN_MENU_INSTRUCTIONS_TEXT'] = 'Instrukcijos';
// Admin Menu - Network Manager
$lang['NRS_ADMIN_MENU_NETWORK_MANAGER_TEXT'] = 'Tinklo valdymas';

// Admin Pages Post Type
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_NAME_TEXT'] = 'Nuomos puslapis'; // name
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_SINGULAR_NAME_TEXT'] = 'Nuomos puslapiai'; // singular_name
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_MENU_NAME_TEXT'] = 'Nuomos puslapiai'; // menu_name
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_PARENT_PAGE_COLON_TEXT'] = 'Pagrindinis nuomos puslapis'; // parent_item_colon
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_ALL_PAGES_TEXT'] = 'Visi puslapiai'; // all_items
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_VIEW_PAGE_TEXT'] = 'Peržiūrėti nuomos puslapį'; // view_item
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_ADD_NEW_PAGE_TEXT'] = 'Pridėti naują nuomos puslapį'; // add_new_item
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_ADD_NEW_TEXT'] = 'Pridėti naują puslapį'; // add_new
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_EDIT_PAGE_TEXT'] = 'Redaguoti nuomos puslapį'; // edit_item
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_UPDATE_PAGE_TEXT'] = 'Atnaujinti nuomos puslapį'; // update_item
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_SEARCH_PAGES_TEXT'] = 'Ieškoti nuomos puslapis'; // search_items
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_NOT_FOUND_TEXT'] = 'Nerasta'; // not_found
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_NOT_FOUND_IN_TRASH_TEXT'] = 'Nerasta šiukšlinėje'; // not_found_in_trash
$lang['NRS_ADMIN_PAGE_POST_TYPE_DESCRIPTION_TEXT'] = 'Nuomos puslapių sąrašas';

// Admin Item Post Type
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_NAME_TEXT'] = 'Automobilio puslapis'; // name
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_SINGULAR_NAME_TEXT'] = 'Automobilio puslapiai'; // singular_name
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_MENU_NAME_TEXT'] = 'Automobiliai'; // menu_name
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_PARENT_ITEM_COLON_TEXT'] = 'Pagrindinis automobilis'; // parent_item_colon
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_ALL_ITEMS_TEXT'] = 'Visi puslapiai'; // all_items
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_VIEW_ITEM_TEXT'] = 'Žiūrėti automobilio puslapį'; // view_item
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_ADD_NEW_ITEM_TEXT'] = 'Pridėti naują automobilio puslapį'; // add_new_item
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_ADD_NEW_TEXT'] = 'Pridėti naują puslapį'; // add_new
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_EDIT_ITEM_TEXT'] = 'Redaguoti automobilio puslapį'; // edit_item
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_UPDATE_ITEM_TEXT'] = 'Atnaujinti automobilio puslapį'; // update_item
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_SEARCH_ITEMS_TEXT'] = 'Ieškoti automobilio puslapio'; // search_items
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_NOT_FOUND_TEXT'] = 'Nerasta'; // not_found
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_NOT_FOUND_IN_TRASH_TEXT'] = 'Nerasta šiukšlinėje'; // not_found_in_trash
$lang['NRS_ADMIN_ITEM_POST_TYPE_DESCRIPTION_TEXT'] = 'Automobilių puslapių sąrašas';

// Admin Location Post Type
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_NAME_TEXT'] = 'Nuomos vieta'; // name
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_SINGULAR_NAME_TEXT'] = 'Nuomos vietos'; // singular_name
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_MENU_NAME_TEXT'] = 'Nuomos vietos'; // menu_name
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_PARENT_LOCATION_COLON_TEXT'] = 'Pagrindinė automobilio nuomos vieta'; // parent_item_colon
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_ALL_LOCATIONS_TEXT'] = 'Visos vietos'; // all_items
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_VIEW_LOCATION_TEXT'] = 'Žiūrėti automobilioautomobilio vietos puslapį'; // view_item
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_ADD_NEW_LOCATION_TEXT'] = 'Pridėti naują automobilio nuomos vietos puslapį'; // add_new_item
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_ADD_NEW_TEXT'] = 'Pridėti naują puslapį'; // add_new
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_EDIT_LOCATION_TEXT'] = 'Redaguoti automobilio nuomos vietos puslapį'; // edit_item
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_UPDATE_LOCATION_TEXT'] = 'Atnaujinti automobilio nuomos vietos puslapį'; // update_item
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_SEARCH_LOCATIONS_TEXT'] = 'Ieškoti automobilio nuomos vietos puslapio'; // search_items
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_NOT_FOUND_TEXT'] = 'Nerasta'; // not_found
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_NOT_FOUND_IN_TRASH_TEXT'] = 'Nerasta šiukšlinėje'; // not_found_in_trash
$lang['NRS_ADMIN_LOCATION_POST_TYPE_DESCRIPTION_TEXT'] = 'Automobilio vietos puslapių sąrašas';

// Admin Core
$lang['NRS_ADMIN_EDIT_TEXT'] = 'Redaguoti';
$lang['NRS_ADMIN_DELETE_TEXT'] = 'Ištrinti';
$lang['NRS_ADMIN_CANCEL_TEXT'] = 'Atšaukti';
$lang['NRS_ADMIN_UNBLOCK_TEXT'] = 'Atblokuoti';
$lang['NRS_ADMIN_MARK_PAID_TEXT'] = 'Žymėti kaip apmokėtą';
$lang['NRS_ADMIN_MARK_COMPLETED_EARLY_TEXT'] = 'Žymėti užbaigtą iš anksto';
$lang['NRS_ADMIN_REFUND_TEXT'] = 'Mokėjimo grąžinimas';
$lang['NRS_ADMIN_SELECT_LOCATION_TEXT'] = '-- Pasirinkti vietą --';
$lang['NRS_ADMIN_ALL_LOCATIONS_TEXT'] = 'Visos vietovės';
$lang['NRS_ADMIN_AVAILABLE_TEXT'] = 'Pasiekiamas';
$lang['NRS_ADMIN_DISPLAYED_TEXT'] = 'Rodomas';
$lang['NRS_ADMIN_VISIBLE_TEXT'] = 'Matomas';
$lang['NRS_ADMIN_HIDDEN_TEXT'] = 'Paslėptas';
$lang['NRS_ADMIN_ENABLED_TEXT'] = 'Įjungta';
$lang['NRS_ADMIN_DISABLED_TEXT'] = 'Išjungta';
$lang['NRS_ADMIN_ALLOWED_TEXT'] = 'Leista';
$lang['NRS_ADMIN_FAILED_TEXT'] = 'Nepavyko';
$lang['NRS_ADMIN_BLOCKED_TEXT'] = 'Užblokuota';
$lang['NRS_ADMIN_REQUEST_TEXT'] = 'Užklausa';
$lang['NRS_ADMIN_REQUESTS_TEXT'] = 'Užklausos';
$lang['NRS_ADMIN_IP_TEXT'] = 'IP';
$lang['NRS_ADMIN_CHECK_TEXT'] = 'Tikrinti';
$lang['NRS_ADMIN_SKIP_TEXT'] = 'Praleisti';
$lang['NRS_ADMIN_YES_TEXT'] = 'Taip';
$lang['NRS_ADMIN_NO_TEXT'] = 'Ne';
$lang['NRS_ADMIN_DAILY_TEXT'] = 'Kasdien';
$lang['NRS_ADMIN_HOURLY_TEXT'] = 'Kas valandą';
$lang['NRS_ADMIN_PER_BOOKING_TEXT'] = 'Per rezervaciją';
$lang['NRS_ADMIN_COMBINED_TEXT'] = 'Kombinuota - kasdien ir kas valandą';
$lang['NRS_ADMIN_NEVER_TEXT'] = 'Niekada';
$lang['NRS_ADMIN_DROPDOWN_TEXT'] = 'Išlendantis sąrašas';
$lang['NRS_ADMIN_SLIDER_TEXT'] = 'Slankiklis';
$lang['NRS_ADMIN_SELECT_DEMO_TEXT'] = ' --- Pasirinkite demonstracinę sistemą --- ';
$lang['NRS_ADMIN_WITHOUT_TRANSLATION_TEXT'] = 'Be vertimo';
$lang['NRS_ADMIN_VIEW_PAGE_IN_NEW_WINDOW_TEXT'] = 'Atverti %s puslapį naujame lange';

// Core
$lang['NRS_IMAGE_ALT_TEXT'] = 'Paveikslėlis';
$lang['NRS_PER_BOOKING_SHORT_TEXT'] = '';
$lang['NRS_PER_DAY_SHORT_TEXT'] = 'd.';
$lang['NRS_PER_HOUR_SHORT_TEXT'] = 'val';
$lang['NRS_PER_BOOKING_TEXT'] = 'rezervacija';
$lang['NRS_PER_DAY_TEXT'] = 'dienai';
$lang['NRS_PER_HOUR_TEXT'] = 'valandai';
$lang['NRS_SELECT_DATE_TEXT'] = 'Data';
$lang['NRS_SELECT_YEAR_TEXT'] = 'Metai';
$lang['NRS_SELECT_MONTH_TEXT'] = 'Mėnesis';
$lang['NRS_SELECT_DAY_TEXT'] = 'Diena';
$lang['NRS_PRICE_TEXT'] = 'Kaina';
$lang['NRS_PERIOD_TEXT'] = 'Laikotarpis';
$lang['NRS_DURATION_TEXT'] = 'Trukmė';
$lang['NRS_MON_TEXT'] = 'Pr';
$lang['NRS_TUE_TEXT'] = 'A';
$lang['NRS_WED_TEXT'] = 'T';
$lang['NRS_THU_TEXT'] = 'K';
$lang['NRS_FRI_TEXT'] = 'P';
$lang['NRS_SAT_TEXT'] = 'Š';
$lang['NRS_SUN_TEXT'] = 'S';
$lang['NRS_LUNCH_TEXT'] = 'Pietūs';
$lang['NRS_MONDAYS_TEXT'] = 'Pirmadieniais';
$lang['NRS_TUESDAYS_TEXT'] = 'Antradieniais';
$lang['NRS_WEDNESDAYS_TEXT'] = 'Trečiadieniais';
$lang['NRS_THURSDAYS_TEXT'] = 'Ketvirtadieniais';
$lang['NRS_FRIDAYS_TEXT'] = 'Penktadieniais';
$lang['NRS_SATURDAYS_TEXT'] = 'Šeštadieniais';
$lang['NRS_SUNDAYS_TEXT'] = 'Sekmadieniais';
$lang['NRS_LUNCH_TIME_TEXT'] = 'Pietų metas';
$lang['NRS_ALL_YEAR_TEXT'] = 'Visus metus';
$lang['NRS_ALL_DAY_TEXT'] = '24 val';
$lang['NRS_PARTIAL_DAY_TEXT'] = '%s - 12:00';
$lang['NRS_MIDNIGHT_TEXT'] = '00:00';
$lang['NRS_NOON_TEXT'] = '12:00';
$lang['NRS_CLOSED_TEXT'] = 'Uždaryta';
$lang['NRS_OPEN_TEXT'] = 'Atidaryta';
$lang['NRS_TODAY_TEXT'] = 'Šiandien';
$lang['NRS_DATE_TEXT'] = 'Data';
$lang['NRS_TIME_TEXT'] = 'Laikas';
$lang['NRS_DAYS_TEXT'] = 'dienos';
$lang['NRS_DAYS2_TEXT'] = 'dienų';
$lang['NRS_DAY_TEXT'] = 'diena';
$lang['NRS_HOURS_TEXT'] = 'valandos';
$lang['NRS_HOURS2_TEXT'] = 'valandų';
$lang['NRS_HOUR_TEXT'] = 'valanda';
$lang['NRS_MINUTES_TEXT'] = 'minutės';
$lang['NRS_MINUTES2_TEXT'] = 'minučių';
$lang['NRS_MINUTE_TEXT'] = 'minutė';
$lang['NRS_DAILY_TEXT'] = 'Kasdien';
$lang['NRS_HOURLY_TEXT'] = 'Kas valandą';
$lang['NRS_ON_ST_TEXT'] = '-ąją'; // On January 21st
$lang['NRS_ON_ND_TEXT'] = '-ąją'; // On January 22nd
$lang['NRS_ON_RD_TEXT'] = '-ąją'; // On January 23rd
$lang['NRS_ON_TH_TEXT'] = '-ąją'; // On January 24th
$lang['NRS_ON_TEXT'] = ''; // on
$lang['NRS_THE_ST_TEXT'] = '-a'; // 1st, do the search
$lang['NRS_THE_ND_TEXT'] = '-a'; // 2nd, select an item
$lang['NRS_THE_RD_TEXT'] = '-a'; // 3rd, choose extras
$lang['NRS_THE_TH_TEXT'] = '-a';// 4th, enter your booking details
$lang['NRS_UNCLASSIFIED_ITEM_TYPE_TEXT'] = 'Kiti';
$lang['NRS_NO_ITEMS_AVAILABLE_TEXT'] = 'Automobilių nerasta';
$lang['NRS_NO_ITEMS_AVAILABLE_IN_THIS_CLASS_TEXT'] = 'Šioje grupėje automobilių nerasta';
$lang['NRS_NO_EXTRAS_AVAILABLE_TEXT'] = 'Papildomų nerasta';
$lang['NRS_NO_MANUFACTURERS_AVAILABLE_TEXT'] = 'Gamintojų nėra';
$lang['NRS_NO_LOCATIONS_AVAILABLE_TEXT'] = 'Vietovių nėra';
$lang['NRS_NO_BENEFITS_AVAILABLE_TEXT'] = 'Privalumų nėra';
$lang['NRS_NA_TEXT'] = 'Nėra';
$lang['NRS_NONE_TEXT'] = 'Nė vienas';
$lang['NRS_NOT_SET_TEXT'] = 'Nenustatyta';
$lang['NRS_DO_NOT_EXIST_TEXT'] = 'Neegzistuoja';
$lang['NRS_EXIST_TEXT'] = 'Yra';
$lang['NRS_NOT_REQ_TEXT'] = 'Nereik.';
$lang['NRS_REQ_TEXT'] = 'Reik.';
$lang['NRS_NOT_REQUIRED_TEXT'] = 'Nereikalaujama';
$lang['NRS_REQUIRED_TEXT'] = 'Privalomas';
$lang['NRS_DONT_DISPLAY_TEXT'] = "Nerodyti";
$lang['NRS_WITH_TAX_TEXT'] = 'su mok.';
$lang['NRS_WITHOUT_TAX_TEXT'] = 'be mok.';
$lang['NRS_TAX_TEXT'] = 'Mok.';
$lang['NRS_FROM_TEXT'] = 'Nuo';
$lang['NRS_TO_TEXT'] = 'Iki';
$lang['NRS_ALL_TEXT'] = 'Visos';
$lang['NRS_OR_TEXT'] = 'Arba';
$lang['NRS_AND_TEXT'] = 'ir';
$lang['NRS_UNLIMITED_TEXT'] = 'Neribota';
$lang['NRS_DEPOSIT_TEXT'] = 'Užstatas';
$lang['NRS_DISCOUNT_TEXT'] = 'Nuolaida';
$lang['NRS_PREPAYMENT_TEXT'] = 'Išankstinio mokėjimo suma';
$lang['NRS_TOTAL_TEXT'] = 'Galutinė';
$lang['NRS_BACK_TEXT'] = 'Atgal';
$lang['NRS_CONTINUE_TEXT'] = 'Tęsti';
$lang['NRS_SEARCH_TEXT'] = 'Ieškoti';
$lang['NRS_SELECT_DROPDOWN_TEXT'] = '--- Pasirinkti ---';
$lang['NRS_ITEM_TEXT'] = 'Automobilis';
$lang['NRS_EXTRA_TEXT'] = 'Papildomi';
$lang['NRS_RENTAL_OPTION_TEXT'] = 'Nuomos pasirinkimas';
$lang['NRS_ITEMS_TEXT'] = 'Automobiliai';
$lang['NRS_EXTRAS_TEXT'] = 'Papildomi';
$lang['NRS_RENTAL_OPTIONS_TEXT'] = 'Nuomos pasirinkimai';
$lang['NRS_SHOW_ITEM_TEXT'] = 'Žiūrėti automobilį';
$lang['NRS_VIA_PARTNER_TEXT'] = 'per %s';
$lang['NRS_COUPON_TEXT'] = 'Kuponas';

// Booking step no. 1 - item search
$lang['NRS_BOOKING_TEXT'] = 'Rezervacija';
$lang['NRS_PICKUP_TEXT'] = 'Paėmimo';
$lang['NRS_RETURN_TEXT'] = 'Grąžinimo';
$lang['NRS_OTHER_TEXT'] = 'Kita';
$lang['NRS_INFORMATION_TEXT'] = 'informacija';
$lang['NRS_CITY_AND_LOCATION_TEXT'] = 'Miestas ir vieta:';
$lang['NRS_PICKUP_CITY_AND_LOCATION_TEXT'] = 'Paėmimo miestas ir vieta:';
$lang['NRS_RETURN_CITY_AND_LOCATION_TEXT'] = 'Grąžinimo miestas ir vieta:';
$lang['NRS_SELECT_BOOKING_DATE_TEXT'] = 'Data:';
$lang['NRS_SELECT_BOOKING_PERIOD_TEXT'] = 'Laikotarpis:';
$lang['NRS_COUPON_CODE_TEXT'] = 'Kuponas';
$lang['NRS_I_HAVE_BOOKING_CODE_TEXT'] = 'Turiu ankstesnio užsakymo numerį:';
$lang['NRS_I_HAVE_COUPON_CODE_TEXT'] = 'Turiu kuponą:';
$lang['NRS_PICKUP_LOCATION_TEXT'] = 'Paėmimo vieta';
$lang['NRS_RETURN_LOCATION_TEXT'] = 'Grąžinimo vieta';
$lang['NRS_ALL_BODY_TYPES_DROPDOWN_TEXT'] = '---- Visi tipai ----';
$lang['NRS_ALL_TRANSMISSION_TYPES_DROPDOWN_TEXT'] = '---- Visi pavarų dėžės tipai ----';
$lang['NRS_SELECT_PICKUP_LOCATION_TEXT'] = '-- Pasirinkti paėmimo vietą --';
$lang['NRS_SELECT_RETURN_LOCATION_TEXT'] = '-- Pasirinkti grąžinimo vietą  --';
$lang['NRS_PICKUP_DATE_TEXT'] = 'Paėmimo data';
$lang['NRS_RETURN_DATE_TEXT'] = 'Grąžinimo data';
$lang['NRS_PICKUP_DATE_ALERT_TEXT'] = 'Pasirinkite paėmimo datą!';
$lang['NRS_RETURN_DATE_ALERT_TEXT'] = 'Pasirinkite grąžinimo datą!';
$lang['NRS_BOOKING_PERIOD_ALERT_TEXT'] = 'Pasirinkite rezervacijos laikotarpį!';
$lang['NRS_PICKUP_LOCATION_ALERT_TEXT'] = 'Pasirinkite paėmimo vietą!';
$lang['NRS_RETURN_LOCATION_ALERT_TEXT'] = 'Pasirinkite grąžinimo vietą!';
$lang['NRS_COUPON_CODE_ALERT_TEXT'] = 'Įrašykite kupono kodą!';
$lang['NRS_SHOW_ITEM_DESCRIPTION_TEXT'] = 'Rodyti automobilio aprašymą';
$lang['NRS_UPDATE_BOOKING_TEXT'] = 'Atnaujinti mano rezervaciją';
$lang['NRS_CANCEL_BOOKING_TEXT'] = 'Atšaukti rezervaciją';
$lang['NRS_CHANGE_BOOKING_DATE_TIME_AND_LOCATION_TEXT'] = 'Pakeisti datą,laiką ir vietą';
$lang['NRS_CHANGE_BOOKED_ITEMS_TEXT'] = 'Pakeisti automobilius';
$lang['NRS_CHANGE_EXTRAS_TEXT'] = 'Pakeisti papildomus';
$lang['NRS_CHANGE_RENTAL_OPTIONS_TEXT'] = 'Pakeisti nuomos pasirinkimus';
$lang['NRS_IN_THIS_LOCATION_TEXT'] = 'Šioje vietovėje';
$lang['NRS_AFTERHOURS_PICKUP_IS_NOT_ALLOWED_TEXT'] = 'Neleidžiama';
$lang['NRS_AFTERHOURS_RETURN_IS_NOT_ALLOWED_TEXT'] = 'Neleidžiama';

// Booking step no. 2 - search results
$lang['NRS_DISTANCE_AWAY_TEXT'] = '%s toli';
$lang['NRS_BOOKING_DATA_TEXT'] = 'Rezervacijos detalės';
$lang['NRS_BOOKING_CODE_TEXT'] = 'Rezervacijos kodas';
$lang['NRS_BOOKING_EDIT_TEXT'] = 'redaguoti';
$lang['NRS_BOOKING_PICKUP_TEXT'] = 'Paėmimas';
$lang['NRS_BOOKING_BUSINESS_HOURS_TEXT'] = 'Darbo valandos';
$lang['NRS_BOOKING_FEE_TEXT'] = 'Mokestis';
$lang['NRS_BOOKING_RETURN_TEXT'] = 'Grąžinimas';
$lang['NRS_BOOKING_NIGHTLY_RATE_TEXT'] = 'po darbo valandų';
$lang['NRS_BOOKING_AFTERHOURS_TEXT'] = 'po darbo valandų';
$lang['NRS_BOOKING_EARLY_TEXT'] = 'Anksti';
$lang['NRS_BOOKING_LATE_TEXT'] = 'Vėlai';
$lang['NRS_BOOKING_AFTERHOURS_PICKUP_TEXT'] = 'Paėmimas po darbo valandų';
$lang['NRS_BOOKING_AFTERHOURS_PICKUP_IMPOSSIBLE_TEXT'] = 'Negalimas';
$lang['NRS_BOOKING_AFTERHOURS_RETURN_TEXT'] = 'Grąžinimas po darbo valandų';
$lang['NRS_BOOKING_AFTERHOURS_RETURN_IMPOSSIBLE_TEXT'] = 'Negalimas';
$lang['NRS_CHOOSE_TEXT'] = 'Pasirinkti';
$lang['NRS_SEARCH_RESULTS_TEXT'] = 'Paieškos rezultatai';
$lang['NRS_MILEAGE_TEXT'] = 'Rida';

// Booking step no. 3 - booking options
$lang['NRS_SELECT_RENTAL_OPTIONS_TEXT'] = 'Pasirinkti nuomos variantą';
$lang['NRS_SELECTED_ITEMS_TEXT'] = 'Pasirinkti automobiliai';
$lang['NRS_FOR_DEPENDANT_ITEM_TEXT'] = ' (%s automobiliui)';
$lang['NRS_NO_EXTRAS_AVAILABLE_CLICK_CONTINUE_TEXT'] = 'Automobilio priedai nerasti. Spauskite mygtuką tęsti.';

// Booking step no. 4 - booking details
$lang['NRS_PICKUP_DATE_AND_TIME_TEXT'] = 'Paėmimo data ir laikas';
$lang['NRS_RETURN_DATE_AND_TIME_TEXT'] = 'Grąžinimo data ir laikas';
$lang['NRS_UNIT_PRICE_TEXT'] = 'Vieneto kaina';
$lang['NRS_QUANTITY_TEXT'] = 'Kiekis';
$lang['NRS_QUANTITY_SHORT_TEXT'] = 'Vnt.';
$lang['NRS_PICKUP_FEE_TEXT'] = 'Paėmimo mokestis';
$lang['NRS_RETURN_FEE_TEXT'] = 'Grąžinimo mokestis';
$lang['NRS_NIGHTLY_PICKUP_RATE_APPLIED_TEXT'] = '(Taikomas naktinis tarifas)';
$lang['NRS_NIGHTLY_RETURN_RATE_APPLIED_TEXT'] = '(Taikomas naktinis tarifas)';
$lang['NRS_ITEM_QUANTITY_SUFFIX_TEXT'] = 'transporto priemonė(s)';
$lang['NRS_EXTRA_QUANTITY_SUFFIX_TEXT'] = 'priedas';
$lang['NRS_PAY_NOW_OR_AT_PICKUP_TEXT'] = 'Mokėti dabar / pasiėmimo metu';
$lang['NRS_PAY_NOW_TEXT'] = 'Mokėti dabar';
$lang['NRS_PAY_AT_PICKUP_TEXT'] = 'Mokėti pasiėmimo metu';
$lang['NRS_PAY_LATER_OR_ON_RETURN_TEXT'] = 'Mokėti vėliau / grąžinimo metu';
$lang['NRS_PAY_LATER_TEXT'] = 'Mokėti vėliau';
$lang['NRS_PAY_ON_RETURN_TEXT'] = 'Mokėti grąžinant';
$lang['NRS_ITEM_RENTAL_DETAILS_TEXT'] = 'Nuomos detalės';
$lang['NRS_MANUFACTURER_TEXT'] = 'Gamintojas';
$lang['NRS_ITEM_MODEL_TEXT'] = 'Automobilio modelis';
$lang['NRS_GROSS_TOTAL_TEXT'] = 'Tarpinė suma';
$lang['NRS_GRAND_TOTAL_TEXT'] = 'Galutinė suma';
$lang['NRS_BOOKING_DETAILS_TEXT'] = 'Rezervacijos detalės';
$lang['NRS_CUSTOMER_DETAILS_TEXT'] = 'Vartotojo detalės';
$lang['NRS_EXISTING_CUSTOMER_DETAILS_TEXT'] = 'Ieškoti egzistuojančių vartotojo detalių';
$lang['NRS_EXISTING_CUSTOMER_TEXT'] = 'Esamas vartotojas';
$lang['NRS_EMAIL_ADDRESS_TEXT'] = 'El.pašto adresas';
$lang['NRS_FETCH_CUSTOMER_DETAILS_TEXT'] = 'Rasti mano duomenis';
$lang['NRS_OR_ENTER_NEW_DETAILS_TEXT'] = 'Arba įvesti naujus duomenis';
$lang['NRS_CUSTOMER_TEXT'] = 'Vartotojas';
$lang['NRS_TITLE_TEXT'] = 'Pavadinimas';
$lang['NRS_MR_TEXT'] = 'Ponas';
$lang['NRS_MS_TEXT'] = 'Ponia';
$lang['NRS_MRS_TEXT'] = 'Ponia';
$lang['NRS_MISS_TEXT'] = 'Panelė';
$lang['NRS_DR_TEXT'] = 'Dr.';
$lang['NRS_PROF_TEXT'] = 'Prof.';
$lang['NRS_FIRST_NAME_TEXT'] = 'Vardas';
$lang['NRS_LAST_NAME_TEXT'] = 'Pavardė';
$lang['NRS_DATE_OF_BIRTH_TEXT'] = 'Gimimo data';
$lang['NRS_YEAR_OF_BIRTH_TEXT'] = 'Gimimo metai';
$lang['NRS_ADDRESS_TEXT'] = 'Adresas';
$lang['NRS_STREET_ADDRESS_TEXT'] = 'Adresas';
$lang['NRS_CITY_TEXT'] = 'Miestas';
$lang['NRS_STATE_TEXT'] = 'Apskritis';
$lang['NRS_ZIP_CODE_TEXT'] = 'Pašto kodas';
$lang['NRS_COUNTRY_TEXT'] = 'Šalis';
$lang['NRS_PHONE_TEXT'] = 'Telefono nr.';
$lang['NRS_EMAIL_TEXT'] = 'El. paštas';
$lang['NRS_ADDITIONAL_COMMENTS_TEXT'] = 'Papildomi komentarai';
$lang['NRS_CUSTOMER_ID_TEXT'] = 'Kliento ID';
$lang['NRS_IP_ADDRESS_TEXT'] = 'IP adresas';
$lang['NRS_PAY_BY_SHORT_TEXT'] = 'Mokėti';
$lang['NRS_I_AGREE_WITH_TERMS_AND_CONDITIONS_TEXT'] = 'Sutinku su nuostatomis ir sąlygomis';
$lang['NRS_TERMS_AND_CONDITIONS_TEXT'] = 'Nuostatos ir sąlygos';
$lang['NRS_CONFIRM_TEXT'] = 'Patvirtinti';
$lang['NRS_FIELD_REQUIRED_TEXT'] = 'Šis laukelis privalomas';

// Booking step no. 5 - process booking
$lang['NRS_PAYMENT_DETAILS_TEXT'] = 'Mokėjimo detalės';
$lang['NRS_PAYMENT_OPTION_TEXT'] = 'Mokėti';
$lang['NRS_PAYER_EMAIL_TEXT'] = 'Mokėtojo el.paštas';
$lang['NRS_TRANSACTION_ID_TEXT'] = 'Pavedimo ID';
$lang['NRS_PROCESSING_PAYMENT_TEXT'] = 'Vyksta mokėjimas...';
$lang['NRS_PLEASE_WAIT_UNTIL_PAYMENT_WILL_BE_PROCESSED_TEXT'] = 'Prašome palaukti, kol Jūsų apmokėjimas bus pradėtas ...';

//display-booking-confirm.php
$lang['NRS_STEP5_PAY_ONLINE_TEXT'] = 'Mokėti internetu';
$lang['NRS_STEP5_PAY_AT_PICKUP_TEXT'] = 'Mokėti paėmimo metu';
$lang['NRS_THANK_YOU_TEXT'] = 'Ačiū!';
$lang['NRS_YOUR_BOOKING_CONFIRMED_TEXT'] = 'Jūsų rezervacija patvirtinta. Rezervacijos kodas ';
$lang['NRS_INVOICE_SENT_TO_YOUR_EMAIL_ADDRESS_TEXT'] = 'Sąskaita-faktūra išsiųsta Jūsų el.pašto adresu';

//display-booking-failure.php
$lang['NRS_BOOKING_FAILURE_TEXT'] = 'Rezervacija nesėkminga';
$lang['NRS_BOOKING_FAILURE_SEARCH_ALL_ITEMS_TEXT'] = 'Ieškoti visų automobilių';

// display-item-price-table.php
$lang['NRS_DAY_PRICE_TEXT'] = 'Dienos kaina';
$lang['NRS_HOUR_PRICE_TEXT'] = 'Valandos kaina';
$lang['NRS_NO_ITEMS_IN_THIS_CATEGORY_TEXT'] = 'Nėra auotomobilių šioje kategorijoje';
$lang['NRS_PRICE_FOR_DAY_FROM_TEXT'] = 'Kaina dienai pradedant nuo';
$lang['NRS_PRICE_FOR_HOUR_FROM_TEXT'] = 'Kaina valandai pradedant nuo';
$lang['NRS_PRICE_WITH_APPLIED_TEXT'] = 'su taikoma';
$lang['NRS_WITH_APPLIED_DISCOUNT_TEXT'] = 'nuolaida';

// class.ItemsAvailability.php
$lang['NRS_MONTH_DAY_TEXT'] = 'Diena';
$lang['NRS_MONTH_DAYS_TEXT'] = 'Dienos';
$lang['NRS_ITEMS_AVAILABILITY_FOR_TEXT'] = 'Automobilių pasiekiamumas';
$lang['NRS_ITEMS_AVAILABILITY_IN_NEXT_30_DAYS_TEXT'] = 'Automobilių pasiekiamumas ateinančiomis 30 dienų';
$lang['NRS_ITEMS_PARTIAL_AVAILABILITY_FOR_TEXT'] = 'Dalinis automobilių pasiekiamumas';
$lang['NRS_ITEMS_AVAILABILITY_THIS_MONTH_TEXT'] = 'Automobilių pasiekiamumas šį mėnesį'; // Not used
$lang['NRS_ITEMS_AVAILABILITY_NEXT_MONTH_TEXT'] = 'Automobilių pasiekiamumas kitą mėnesį'; // Not used
$lang['NRS_ITEM_ID_TEXT'] = 'ID:';
$lang['NRS_TOTAL_ITEMS_TEXT'] = 'Viso automobilių:';

// class.ExtrasAvailability.php
$lang['NRS_EXTRAS_AVAILABILITY_FOR_TEXT'] = 'Priedų pasiekiamumas';
$lang['NRS_EXTRAS_AVAILABILITY_IN_NEXT_30_DAYS_TEXT'] = 'Priedų pasiekiamumas ateinančiom 30 dienų';
$lang['NRS_EXTRAS_PARTIAL_AVAILABILITY_FOR_TEXT'] = 'Dalinis priedų pasiekiamumas';
$lang['NRS_EXTRAS_AVAILABILITY_THIS_MONTH_TEXT'] = 'Priedų pasiekiamumas šį mėnesį'; // Not used
$lang['NRS_EXTRAS_AVAILABILITY_NEXT_MONTH_TEXT'] = 'Priedų pasiekiamumas kitą mėnesį'; // Not used
$lang['NRS_EXTRA_ID_TEXT'] = 'ID';
$lang['NRS_TOTAL_EXTRAS_TEXT'] = 'Iš viso priedų:';

// class.ItemsController.php
$lang['NRS_SHOW_ITEM_PAGE_TEXT'] = 'Rodyti automobilio aprašymą';
$lang['NRS_PARTNER_TEXT'] = 'Partneris';
$lang['NRS_BODY_TYPE_TEXT'] = 'Klasė';
$lang['NRS_TRANSMISSION_TYPE_TEXT'] = 'Pavarų dėžė';
$lang['NRS_FUEL_TYPE_TEXT'] = 'Kuras';
$lang['NRS_ITEM_FUEL_CONSUMPTION_TEXT'] = 'Kuro naudojimas';
$lang['NRS_ITEM_PASSENGERS_TEXT'] = 'Maksimalus keleivių skaičius';
$lang['NRS_ITEM_PRICE_FROM_TEXT'] = 'Kaina nuo';
$lang['NRS_INQUIRE_TEXT'] = 'Klausti';
$lang['NRS_GET_A_QUOTE_TEXT'] = 'Gauti kainą';
$lang['NRS_ITEM_FEATURES_TEXT'] = 'Ypatybės';
$lang['NRS_BOOK_ITEM_TEXT'] = 'Nuomotis';

// class.LocationsController.php
$lang['NRS_LOCATIONS_BUSINESS_HOURS_TEXT'] = 'Darbo valandos';
$lang['NRS_LOCATION_FEES_TEXT'] = 'Vietos mokesčiai';
$lang['NRS_EARLY_PICKUP_TEXT'] = 'Ankst. paėmim.';
$lang['NRS_LATE_PICKUP_TEXT'] = 'Vėlyv. paėmim.';
$lang['NRS_EARLY_RETURN_TEXT'] = 'Ankst. grąžin.';
$lang['NRS_LATE_RETURN_TEXT'] = 'Vėlyv. grąžin.';
$lang['NRS_EARLY_PICKUP_FEE_TEXT'] = 'Early pick-up fee';
$lang['NRS_LATE_RETURN_FEE_TEXT'] = 'Late return fee';
$lang['NRS_VIEW_LOCATION_TEXT'] = 'Peržiūrėti';

// class.SingleItemController.php
$lang['NRS_ITEM_ENGINE_CAPACITY_TEXT'] = 'Variklio tūris';
$lang['NRS_ITEM_LUGGAGE_TEXT'] = 'Maksimalus bagažas';
$lang['NRS_ITEM_DOORS_TEXT'] = 'Durys';
$lang['NRS_ITEM_DRIVER_AGE_TEXT'] = 'Minimalus vairuotojo amžius';
$lang['NRS_ADDITIONAL_INFORMATION_TEXT'] = 'Papildoma informacija';

// class.SingleLocationController.php
$lang['NRS_CONTACTS_TEXT'] = 'Kontaktai';
$lang['NRS_CONTACT_DETAILS_TEXT'] = 'Kontaktiniai duomenys';
$lang['NRS_BUSINESS_HOURS_FEES_TEXT'] = 'Mokesčiai darbo valandomis';
$lang['NRS_AFTERHOURS_FEES_TEXT'] = 'Mokesčiai po darbo valandų';

// template.BookingCancelled.php
$lang['NRS_CANCELLED_SUCCESSFULLY_TEXT'] = 'atšaukta sėkmingai';
$lang['NRS_NOT_CANCELLED_TEXT'] = 'Rezervacija nebuvo atšaukta';

// template.Step8EditBooking.php
$lang['NRS_EDIT_TEXT'] = 'Pakeisti';
$lang['NRS_BOOKING2_TEXT'] = 'rezervacija';
$lang['NRS_EDIT_BOOKING_BUTTON_TEXT'] = 'Pakeisti rezervaciją';
$lang['NRS_EDIT_BOOKING_PLEASE_ENTER_BOOKING_NUMBER_TEXT'] = 'Įrašykite rezervacijos numerį!';

// Admin System Errors
// Unfortunately, they are untranslatable
$lang['NRS_ERROR_IN_METHOD_TEXT'] = 'Klaida %s metode: ';

// Exceptions
$lang['NRS_ERROR_CANNOT_BIND_TEMPLATE_VARIABLE_TEXT'] = ' Negali susieti kintamojo pavadinimu &#39;templateFile&#39;.';
$lang['NRS_ERROR_TEMPLATE_NOT_EXIST_TEXT'] = 'Šablono failas %s neegzistuoja.';

// Errors
$lang['NRS_ERROR_EXTENSION_NAME_TEXT'] = 'Automobilių nuomos sistema';
$lang['NRS_ERROR_REQUIRED_FIELD_TEXT'] = 'Būtinas laukelis';
$lang['NRS_ERROR_IS_EMPTY_TEXT'] = 'yra tuščias';
$lang['NRS_ERROR_SLIDER_CANT_BE_DISPLAYED_TEXT'] = 'Slankiklis negali būti atvaizduojamas';
$lang['NRS_ERROR_CUSTOMER_DETAILS_NOT_FOUND_TEXT'] = 'Neegzistuoja vartotojas su numatytomis detalėmis. Sukurkite naują paskyrą.';
$lang['NRS_ERROR_CUSTOMER_DETAILS_NO_ERROR_TEXT'] = 'Jokių klaidų';
$lang['NRS_ERROR_EXCEEDED_CUSTOMER_LOOKUP_ATTEMPTS_TEXT'] = 'You have exceeded customer detail lookup attempts. Please enter your details manually in the form bellow.';
$lang['NRS_ERROR_CUSTOMER_DETAILS_UNKNOWN_ERROR_TEXT'] = 'Nežinoma klaida';
$lang['NRS_ERROR_BOOKING_DOES_NOT_EXIST_TEXT'] = 'neegzistuoja';
$lang['NRS_ERROR_PLEASE_SELECT_AT_LEAST_ONE_ITEM_TEXT'] = 'Pasirinkite nors vieną automobilį';
$lang['NRS_ERROR_ITEM_NOT_AVAILABLE_TEXT'] = 'Šis automobilis nepasiekiamas.';
$lang['NRS_ERROR_NO_ITEM_AVAILABLE_PLEASE_TRY_DIFFERENT_DATE_TEXT'] = 'Nėra laisvų automobilių. Pakeiskite rezervacijos periodą arba paieškos kriterijus.';
$lang['NRS_ERROR_SEARCH_ENGINE_DISABLED_TEXT'] = 'Rezervacijos sistema išjungta. Pabandykite vėliau.';
$lang['NRS_ERROR_OUT_BEFORE_IN_TEXT'] = 'Jūsų grąžinimo data privalo būti vėlesnė nei paėmimo data. Patikrinkite paėmimo ir grąžinimo datas.';
$lang['NRS_ERROR_MINIMUM_NUMBER_OF_NIGHT_SHOULD_NOT_BE_LESS_THAN_TEXT'] = 'Minimum number of night should not be less than';
$lang['NRS_ERROR_PLEASE_MODIFY_YOUR_SEARCH_CRITERIA_TEXT'] = 'Pakeiskite paieškos kriterijus.';
$lang['NRS_ERROR_PICKUP_IS_NOT_POSSIBLE_ON_TEXT'] = 'Paėmimas neįmanomas';
$lang['NRS_ERROR_PLEASE_MODIFY_YOUR_PICKUP_TIME_BY_WEBSITE_TIME_TEXT'] = 'Pakeiskite paėmimo datą ir laiką pagal nuomos vietą dabartinę datą ir laiką.';
$lang['NRS_ERROR_CURRENT_DATE_TIME_TEXT'] = 'Nuomos vietos dabartinė data ir laikas yra';
$lang['NRS_ERROR_EARLIEST_POSSIBLE_PICKUP_DATE_TIME_TEXT'] = 'Anksčiausia įmanoma paėmimo data ir laikas yra';
$lang['NRS_ERROR_OR_NEXT_BUSINESS_HOURS_OF_PICKUP_LOCATION_TEXT'] = 'arba pirmas kartas po to kai pasirinkta paėmimo vieta yra atidaryta';
$lang['NRS_ERROR_PICKUP_DATE_CANT_BE_LESS_THAN_RETURN_DATE_TEXT'] = 'Paėmimo data ir laikas negali būti trumpesnis nei grąžinimo data ir laikas. Pasirinkite teisingą paėmimo ir grąžinimo datą ir laiką.';
$lang['NRS_ERROR_PICKUP_LOCATION_IS_CLOSED_AT_THIS_DATE_TEXT'] = 'Paėmimo skyrius %s adresu %s yra uždarytas šią datą (%s).';
$lang['NRS_ERROR_PICKUP_LOCATION_IS_CLOSED_AT_THIS_TIME_TEXT'] = 'Paėmimo skyrius %s adresu %s yra uždarytas šiuo laiku (%s).';
$lang['NRS_ERROR_RETURN_LOCATION_IS_CLOSED_AT_THIS_DATE_TEXT'] = 'Grąžinimo skyrius %s adresu %s yra uždarytas šią datą (%s).';
$lang['NRS_ERROR_RETURN_LOCATION_IS_CLOSED_AT_THIS_TIME_TEXT'] = 'Grąžinimo skyrius %s adresu %s yra uždarytas šiuo laiku (%s).';
$lang['NRS_ERROR_AFTERHOURS_PICKUP_LOCATION_IS_CLOSED_AT_THIS_TIME_TEXT'] = 'Po darbo valandų paėmimo skyrius yra %s adresu %s bet šis skyrius taip pat uždarytas šiuo metu.';
$lang['NRS_ERROR_AFTERHOURS_RETURN_LOCATION_IS_CLOSED_AT_THIS_TIME_TEXT'] = 'Po darbo valandų grąžinimo skyrius yra %s adresu %s bet šis skyrius taip pat uždarytas šiuo metu.';
$lang['NRS_ERROR_LOCATION_OPEN_HOURS_ARE_TEXT'] = 'Šios vietos darbo valandos yra %s, %s yra %s.';
$lang['NRS_ERROR_LOCATION_WEEKLY_OPEN_HOURS_ARE_TEXT'] = 'Šios vietos darbo valandos per savaitę yra:';
$lang['NRS_ERROR_AFTERHOURS_PICKUP_LOCATION_OPEN_HOURS_ARE_TEXT'] = 'Po darbo valandų paėmimo vietos darbo valandos yra %s.';
$lang['NRS_ERROR_AFTERHOURS_RETURN_LOCATION_OPEN_HOURS_ARE_TEXT'] = 'Po darbo valandų grąžinimo vietos darbo valandos yra  %s.';
$lang['NRS_ERROR_AFTERHOURS_PICKUP_IS_NOT_ALLOWED_AT_LOCATION_TEXT'] = 'Po darbo valandų paėmimas yra negalimas šioje vietovėje.';
$lang['NRS_ERROR_AFTERHOURS_RETURN_IS_NOT_ALLOWED_AT_LOCATION_TEXT'] = 'Po darbo valandų grąžinimas yra negalimas šioje vietovėje.';
$lang['NRS_ERROR_MAXIMUM_BOOKING_LENGTH_CANT_BE_MORE_THAN_TEXT'] = 'Maksimalus rezervacijos ilgis negali būti ilgesnis nei (dienomis)';
$lang['NRS_ERROR_INVALID_BOOKING_CODE_TEXT'] = 'Neteisingas rezervacijos kodas arba ši rezervacija neegzistuoja.';
$lang['NRS_ERROR_INVALID_SECURITY_CODE_TEXT'] = 'Neteisingas saugos kodas.';
$lang['NRS_ERROR_DRIVER_AGE_VIOLATION_FOR_ITEM_TEXT'] = 'Pagal Jūsų gimimo datą, Jūsų amžius neatitinka minimalaus amžiaus reikalavimo vairuoti %s.';
$lang['NRS_ERROR_DRIVER_AGE_VIOLATION_TEXT'] = 'Pagal Jūsų gimimo datą, Jūsų amžius neatitinka minimalaus amžiaus reikalavimo vairuoti vieno iš pasirinktų automobilių.';
$lang['NRS_ERROR_DEPARTED_TEXT'] = 'Rezervacijos Nr. %s pažymėtas kaip įvykęs ir nepasiekiamas tolesniam redagavimui.';
$lang['NRS_ERROR_CANCELLED_TEXT'] = 'Rezervacijos Nr. %s buvo atšauktas.';
$lang['NRS_ERROR_REFUNDED_TEXT'] = 'Rezervacijos Nr. %s buvo grąžintas ir daugiau nepasiekiamas.';
$lang['NRS_ERROR_SYSTEM_IS_UNABLE_TO_SEND_EMAIL_TEXT'] = 'Klaida: sistema neišsiuntė patvirtinimo laiško.Arba sistemos el.pašto nustatymai nėra sukonfigūruoti tinkamai arba vartotojo &#39;s el.pašto adresas nėra teisingas.';
$lang['NRS_ERROR_PAYMENT_METHOD_IS_NOT_YET_IMPLEMENTED_TEXT'] = 'Klaida: bandė mokėti mokėjimo būdu kuris nepasiekiamas šiai sistemai.';
$lang['NRS_ERROR_OTHER_BOOKING_ERROR_TEXT'] = 'Kita rezervacijos klaida. Susisiekite su tinklapio administracija jeigu pakartotinai matote šią klaidą.';

// Admin Discount controller
$lang['NRS_ADMIN_DISCOUNT_ITEM_BOOKING_IN_ADVANCE_TEXT'] = 'Pridėti/redaguoti automobilio nuolaidą užsakymui iš anksto';
$lang['NRS_ADMIN_DISCOUNT_ITEM_BOOKING_DURATION_TEXT'] = 'Pridėti/redaguoti automobilio nuolaidą užsakymo laikotarpiui';
$lang['NRS_ADMIN_DISCOUNT_EXTRA_BOOKING_IN_ADVANCE_TEXT'] = 'Pridėti/redaguoti priedus nuolaidos užsakymui iš anksto automobilio nuolaidą užsakymui iš anksto';
$lang['NRS_ADMIN_DISCOUNT_EXTRA_BOOKING_DURATION_TEXT'] = ' Pridėti/redaguoti papildomą nuolaidą užsakymo laikotarpiui';
$lang['NRS_ADMIN_DISCOUNT_DURATION_BEFORE_TEXT'] = 'Trukmė prieš:';
$lang['NRS_ADMIN_DISCOUNT_DURATION_UNTIL_TEXT'] = 'Trukmė iki:';
$lang['NRS_ADMIN_DISCOUNT_DURATION_FROM_TEXT'] = 'Trukmė iš:';
$lang['NRS_ADMIN_DISCOUNT_DURATION_TILL_TEXT'] = 'Trukmė iki:';

// Admin Settings Controller
$lang['NRS_ADMIN_SETTINGS_OKAY_GLOBAL_SETTINGS_UPDATED_TEXT'] = 'Užbaigta: Pagrindiniai nustatymai atnaujinti sėkmingai!';
$lang['NRS_ADMIN_SETTINGS_OKAY_CUSTOMER_SETTINGS_UPDATED_TEXT'] = 'Užbaigta: Vartotojo nustatymai atnaujinti sėkmingai!';
$lang['NRS_ADMIN_SETTINGS_OKAY_SEARCH_SETTINGS_UPDATED_TEXT'] = 'Užbaigta: Paieškos nustatymai atnaujinti sėkmingai!';
$lang['NRS_ADMIN_SETTINGS_OKAY_PRICE_SETTINGS_UPDATED_TEXT'] = 'Užbaigta: Kainos nustatymai atnaujinti sėkmingai!';

// OK / Error Messages - Benefit Element
$lang['NRS_BENEFIT_TITLE_EXISTS_ERROR_TEXT'] = 'Klaida: Privalumas tokiu pavadinimu jau egzistuoja!';
$lang['NRS_BENEFIT_UPDATE_ERROR_TEXT'] = 'Klaida: MySQL atnaujinimo klaida egzistuojančiam privalumui!';
$lang['NRS_BENEFIT_UPDATED_TEXT'] = 'Užbaigta: Privalumas buvo atnaujintas sėkmingai!';
$lang['NRS_BENEFIT_INSERT_ERROR_TEXT'] = 'Klaida: MySQL įterpimo klaida naujam privalumui!';
$lang['NRS_BENEFIT_INSERTED_TEXT'] = 'Užbaigta: Naujas privalumas sėkmingai pridėta!';
$lang['NRS_BENEFIT_REGISTERED_TEXT'] = 'Privalumo pavadinimas užregistruodas vertimui.';
$lang['NRS_BENEFIT_DELETE_ERROR_TEXT'] = 'Klaida: MySQL ištrynimo klaida egzistuojančiam privalumui. Jokių eilučių iš duomenų bazės nebuvo ištrinta!';
$lang['NRS_BENEFIT_DELETED_TEXT'] = 'Užbaigta: Privalumas buvo ištrintas sėkmingai!';

// OK / Error Messages - Block Element
$lang['NRS_BLOCK_INSERT_ERROR_TEXT'] = 'Klaida: MySQL įterpimo klaida naujam blokui!';
$lang['NRS_BLOCK_INSERTED_TEXT'] = 'Užbaigta: Naujas blokas pridėtas sėkmingai!';
$lang['NRS_BLOCK_DELETE_ERROR_TEXT'] = 'Error: MySQL delete error for existing block. No rows were deleted from database!';
$lang['NRS_BLOCK_DELETED_TEXT'] = 'Completed: Block has been deleted successfully!';
$lang['NRS_BLOCK_DELETE_OPTIONS_ERROR_TEXT'] = 'Failed: No cars or extras were deleted from block!';
$lang['NRS_BLOCK_OPTIONS_DELETED_TEXT'] = 'Completed: All cars and extras were deleted from block!';

// OK / Error Messages - Body Type Element
$lang['NRS_BODY_TYPE_TITLE_EXISTS_ERROR_TEXT'] = 'Klaida: Kėbulo tipas su šiuo pavadinimu jau egzistuoja!';
$lang['NRS_BODY_TYPE_UPDATE_ERROR_TEXT'] = 'Klaida: MySQL atnaujinimo klaida esančiam kėbulo tipui!';
$lang['NRS_BODY_TYPE_UPDATED_TEXT'] = 'Užbaigta: Kėbulo tipas atnaujintas sėkmingai!';
$lang['NRS_BODY_TYPE_INSERT_ERROR_TEXT'] = 'Klaida: MySQL įterpimo klaida naujam kėbulo tipui!';
$lang['NRS_BODY_TYPE_INSERTED_TEXT'] = 'Užbaigta: Naujas kėbulo tipas pridėtas sėkmingai!';
$lang['NRS_BODY_TYPE_REGISTERED_TEXT'] = 'Kėbulo tipo pavadinimas užregistruotas vertimui.';
$lang['NRS_BODY_TYPE_DELETE_ERROR_TEXT'] = 'Klaida: MySQL ištrynimo klaida esančiam kėbulo tipui. Jokių eilučių iš duomenų bazės nebuvo ištrinta!';
$lang['NRS_BODY_TYPE_DELETED_TEXT'] = 'Užbaigta: Kėbulo tipas sėkmingai ištrintas!';

// OK / Error Messages - Booking Element
$lang['NRS_BOOKING_UPDATE_ERROR_TEXT'] = 'Klaida: MySQL atnaujinimo klaida esančiai rezervacijai!';
$lang['NRS_BOOKING_UPDATED_TEXT'] = 'Užbaigta: Rezervacija sėkmingai atnaujinta!';
$lang['NRS_BOOKING_INSERT_ERROR_TEXT'] = 'Klaida: MySQL įterpimo klaida naujai rezervacijai!';
$lang['NRS_BOOKING_INSERTED_TEXT'] = 'Užbaigta: Nauja rezervacija pridėta sėkmingai!';
$lang['NRS_BOOKING_CANCEL_ERROR_TEXT'] = 'Klaida: MySQL atnaujinimo klaida pasirodžiusią kai bandyta atšaukti rezervaciją!';
$lang['NRS_BOOKING_CANCELLED_TEXT'] = 'Užbaigta: Rezervacija atšaukta sėkmingai!';
$lang['NRS_BOOKING_DELETE_ERROR_TEXT'] = 'Klaida: MySQL ištrynimo klaida esančiai rezervcijai. Jokių eilučių iš duomenų bazės nebuvo ištrinta!';
$lang['NRS_BOOKING_DELETED_TEXT'] = 'Užbaigta: Rezervacija buvo ištrinta sėkmingai!';
$lang['NRS_BOOKING_DELETE_OPTIONS_ERROR_TEXT'] = 'Failed: No cars or extras were deleted from reservation!';
$lang['NRS_BOOKING_OPTIONS_DELETED_TEXT'] = 'Completed: All cars and extras were deleted from reservation!';
$lang['NRS_BOOKING_MARK_AS_PAID_ERROR_TEXT'] = 'Failed: Reservation was not marked as paid!';
$lang['NRS_BOOKING_MARKED_AS_PAID_TEXT'] = 'Completed: Reservation was marked as paid!';
$lang['NRS_BOOKING_MARK_COMPLETED_EARLY_ERROR_TEXT'] = 'Failed: Reservation was not marked as completed early!';
$lang['NRS_BOOKING_MARKED_COMPLETED_EARLY_TEXT'] = 'Completed: Reservation was marked as completed early!';
$lang['NRS_BOOKING_REFUND_ERROR_TEXT'] = 'Failed: Reservation was not refunded!';
$lang['NRS_BOOKING_REFUNDED_TEXT'] = 'Completed: Reservation was refunded successfully!';

// OK / Error Messages - (Extra) Booking Option Element
$lang['NRS_EXTRA_BOOKING_ID_QUANTITY_OPTION_SKU_ERROR_TEXT'] = 'Klaida: Naujas priedas nebuvo užblokuotas dėl netinkamo rezervacijos id (%s), SKU (%s) arba kiekio (%s)!';
$lang['NRS_EXTRA_BOOKING_OPTION_INSERT_ERROR_TEXT'] = 'Klaida: MySQL įterpimo klaida naujam priedui (SKU - %s) rezervacija/blokas!';
$lang['NRS_EXTRA_BOOKING_OPTION_INSERTED_TEXT'] = 'Užbaigta: Naujas priedas  (SKU - %s) buvo užblokuotas/rezervuotas!';
$lang['NRS_EXTRA_BOOKING_OPTION_DELETE_ERROR_TEXT'] = 'Klaida: MySQL ištrynimo klaida esančią rezervuotam/blokuotam priedui. Jokių eilučių iš duomenų bazės nebuvo ištrinta!';
$lang['NRS_EXTRA_BOOKING_OPTION_DELETED_TEXT'] = 'Užbaigta: Priedai sėkmingai ištrinti iš rezervacijos/bloko!';

// OK / Error Messages - (Item) Booking Option Element
$lang['NRS_ITEM_BOOKING_ID_QUANTITY_OPTION_SKU_ERROR_TEXT'] = 'Klaida: Naujas automobilis can&#39;t buvo užblokuotas dėl neteisingo rezervacijos id (%s), SKU (%s) arba kiekio (%s)!';
$lang['NRS_ITEM_BOOKING_OPTION_INSERT_ERROR_TEXT'] = 'Klaida: MySQL įterpimo klaida naujam automobiliui (SKU - %s) rezervacija/blokas!';
$lang['NRS_ITEM_BOOKING_OPTION_INSERTED_TEXT'] = 'Užbaigta: Naujas automobilis (SKU - %s) buvo užblokuotas/rezervuotas!';
$lang['NRS_ITEM_BOOKING_OPTION_DELETE_ERROR_TEXT'] = 'Klaida: MySQL ištrynimo klaida esančiai rezervuotai blokuotai mašinai . Jokių eilučių iš duomenų bazės nebuvo ištrinta!';
$lang['NRS_ITEM_BOOKING_OPTION_DELETED_TEXT'] = 'Užbaigta: Mašina ištrinta iš rezervacijos/bloko!';

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
$lang['NRS_CUSTOMER_UPDATE_ERROR_TEXT'] = 'Klaida: MySQL atnaujinimo klaida esamam vartotojui u!';
$lang['NRS_CUSTOMER_UPDATED_TEXT'] = 'Užbaigta: Vartotojas atnaujintas sėkmingai!';
$lang['NRS_CUSTOMER_INSERT_ERROR_TEXT'] = 'Klaida: MySQL įterpimo klaidą naujam vartotojui!';
$lang['NRS_CUSTOMER_INSERTED_TEXT'] = 'Užbaigta: Naujas vartotojas pridėtas sėkmingai!';
$lang['NRS_CUSTOMER_LAST_VISIT_UPDATE_ERROR_TEXT'] = 'Error: MySQL update error for customer last visit date!';
$lang['NRS_CUSTOMER_LAST_VISIT_UPDATED_TEXT'] = 'Completed: Customer last visit date has been updated!';
$lang['NRS_CUSTOMER_DELETE_ERROR_TEXT'] = 'Klaida: MySQL ištrynimo klaida esamam vartotojui. Jokių eilučių iš duomenų bazės nebuvo ištrinta!';
$lang['NRS_CUSTOMER_DELETED_TEXT'] = 'Užbaigta: Customer has been deleted successfully!';

// OK / Error Messages - Demo Element
$lang['NRS_DEMO_INSERT_ERROR_TEXT'] = 'Nepavyko: Demonstracinių duomenų importuoti nepavyko!';
$lang['NRS_DEMO_INSERTED_TEXT'] = 'Užbaigta: Demonstraciniai duomenys importuoti sėkmingai!';

// OK / Error Messages - Distance Element
$lang['NRS_DISTANCE_PICKUP_NOT_SELECTED_ERROR_TEXT'] = 'Klaida: Paėmimo vieta turi būti parinkta!';
$lang['NRS_DISTANCE_RETURN_NOT_SELECTED_ERROR_TEXT'] = 'Klaida: Grąžinimo vieta turi būti parinkta!';
$lang['NRS_DISTANCE_SAME_PICKUP_AND_RETURN_ERROR_TEXT'] = 'Klaida: Paėmimo ir grąžinimo vieta negali būti ta pati!';
$lang['NRS_DISTANCE_EXISTS_ERROR_TEXT'] = 'Klaida: Šis atstumas jau egzistuoja!';
$lang['NRS_DISTANCE_UPDATE_ERROR_TEXT'] = 'Klaida: MySQL atnaujinimo klaida esamam atstumui!';
$lang['NRS_DISTANCE_UPDATED_TEXT'] = 'Užbaigta: Atstumas atnaujintas sėkmingai!';
$lang['NRS_DISTANCE_INSERT_ERROR_TEXT'] = 'Klaida: MySQL įterpimo klaida naujam atstumui!';
$lang['NRS_DISTANCE_INSERTED_TEXT'] = 'Užbaigta: Naujas atstumas pridėtas sėkmingai!';
$lang['NRS_DISTANCE_DELETE_ERROR_TEXT'] = 'Klaida: MySQL ištrynimo klaida esančiam atstumui.  Jokių eilučių iš duomenų bazės nebuvo ištrinta!';
$lang['NRS_DISTANCE_DELETED_TEXT'] = 'Užbaigta: Atstumas ištrintas sėkmingai!';

// OK / Error Messages - (Extra) Discount Element
$lang['NRS_EXTRA_DISCOUNT_DAYS_INTERSECTION_ERROR_TEXT'] = 'Klaida: Papildoma nuolaida dienų laikotarpio netinka su kita papildoma nuolaida';
$lang['NRS_EXTRA_DISCOUNT_UPDATE_ERROR_TEXT'] = 'Klaida: MySQL atnaujinimo klaida esamai papildomai nuolaidai!';
$lang['NRS_EXTRA_DISCOUNT_UPDATED_TEXT'] = 'Užbaigta: Papildoma nuolaida atnaujinta sėkmingai!';
$lang['NRS_EXTRA_DISCOUNT_INSERT_ERROR_TEXT'] = 'Klaida: MySQL iterpe klaida naujai nuolaidai!';
$lang['NRS_EXTRA_DISCOUNT_INSERTED_TEXT'] = 'Užbaigta: nauja nuolaida prideta sekmingai!';
$lang['NRS_EXTRA_DISCOUNT_DELETE_ERROR_TEXT'] = 'Klaida: MySQL istryne klaida esamai papildomai nuolaidai. Jokių eilučių iš duomenų bazės nebuvo ištrinta!';
$lang['NRS_EXTRA_DISCOUNT_DELETED_TEXT'] = 'Užbaigta: Papildoma nuolaida sėkmingai ištrinta!';

// OK / Error Messages - (Price Plan) Discount Element
$lang['NRS_PRICE_PLAN_DISCOUNT_DAYS_INTERSECTION_ERROR_TEXT'] = 'Klaida: Kainų plano nuolaidu dienos sutampa su kitų priedų nuolaidomis (ar visais kitais priedais)!';
$lang['NRS_PRICE_PLAN_DISCOUNT_UPDATE_ERROR_TEXT'] = 'Klaida: MySQL atnaujinimo klaida egzistuojančiai kainų plano nuolaidai!';
$lang['NRS_PRICE_PLAN_DISCOUNT_UPDATED_TEXT'] = 'Užbaigta: Kainų plano nuolaida buvo atnaujinta sėkmingai!';
$lang['NRS_PRICE_PLAN_DISCOUNT_INSERT_ERROR_TEXT'] = 'Klaida: MySQL įterpimo klaida naujai kainų plano nuolaidai!';
$lang['NRS_PRICE_PLAN_DISCOUNT_INSERTED_TEXT'] = 'Užbaigta: Nauja kainų plano nuolaida buvo pridėtas sėkmingai!';
$lang['NRS_PRICE_PLAN_DISCOUNT_DELETE_ERROR_TEXT'] = 'Klaida: MySQL trinimo klaida egzistuojančiai kainų plano nuolaidai. Niekas nebuvo ištrinta iš duomenų bazės!';
$lang['NRS_PRICE_PLAN_DISCOUNT_DELETED_TEXT'] = 'Užbaigta: Kainų plano nuolaida buvo ištrinta sėkmingai!';

// OK / Error Messages - Email Element
$lang['NRS_EMAIL_DEMO_LOCATION_NAME_TEXT'] = 'Demonstracinė vieta';
$lang['NRS_EMAIL_DEMO_LOCATION_PHONE_TEXT'] = '+370 60000000';
$lang['NRS_EMAIL_DEMO_LOCATION_EMAIL_TEXT'] = 'info@vieta.lt';
$lang['NRS_EMAIL_SUBJECT_EXISTS_ERROR_TEXT'] = 'Klaida: Kitas el. laiškas adresas jau egzistuoja su šia tema!';
$lang['NRS_EMAIL_UPDATE_ERROR_TEXT'] = 'Klaida: MySQL atnaujinimo klaida egzistuojančiam el. laiškui!';
$lang['NRS_EMAIL_UPDATED_TEXT'] = 'Užbaigta: El. laiškas buvo atnaujintas sėkmingai!';
$lang['NRS_EMAIL_REGISTERED_TEXT'] = 'El. laiško tema ir pranešimas buvo priregistruoti vertimui.';
$lang['NRS_EMAIL_SENDING_ERROR_TEXT'] = 'Nepavyko: Sistema negalėjo išsiųsti el. laiško %s!';
$lang['NRS_EMAIL_SENT_TEXT'] = 'Užbaigta: El. laiškas buvo sėkmingai išsiųstas %s!';

// OK / Error Messages - Extra Element
$lang['NRS_EXTRA_SKU_EXISTS_ERROR_TEXT'] = 'Klaida: Priedas su šiuo SKU kodų jau egzistuoja!';
$lang['NRS_EXTRA_MORE_UNITS_PER_BOOKING_THAN_IN_STOCK_ERROR_TEXT'] = 'Klaida: Negalima leisti rezervuoti daugiau vienetų per vieną rezervaziją nei yra bendro kiekio!';
$lang['NRS_EXTRA_ITEM_DOES_NOT_EXIST_ERROR_TEXT'] = 'Klaida: Pažymėtas automobilis neegzistuoja!';
$lang['NRS_EXTRA_ITEM_ASSIGN_ERROR_TEXT'] = 'Klaida: Negalima priskirti priedu pasirinktam automobiliui!';
$lang['NRS_EXTRA_ITEM_SELECT_ERROR_TEXT'] = 'Klaida: Prašome pasirinkti automobil!';
$lang['NRS_EXTRA_UPDATE_ERROR_TEXT'] = 'Klaida: MySQL atnaujinimo klaida egzistuojančiam priedui!';
$lang['NRS_EXTRA_UPDATED_TEXT'] = 'Užbaigta: Priedas buvo atnaujintas sėkmingai!';
$lang['NRS_EXTRA_INSERT_ERROR_TEXT'] = 'Klaida: MySQL įterpimo klaida naujam priedui!';
$lang['NRS_EXTRA_INSERTED_TEXT'] = 'Užbaigta: Naujas priedas buvo pridėtas sėkmingai!';
$lang['NRS_EXTRA_REGISTERED_TEXT'] = 'Priedo pavadinimas užregistruotas vertimui.';
$lang['NRS_EXTRA_DELETE_ERROR_TEXT'] = 'Klaida: MySQL trinimo klaida egzistuojančiam priedui. Niekas nebuvo ištrinta iš duomenų bazės!';
$lang['NRS_EXTRA_DELETED_TEXT'] = 'Užbaigta: Priedas buvo ištrintas sėkmingai!';

// OK / Error Messages - Feature Element
$lang['NRS_FEATURE_TITLE_EXISTS_ERROR_TEXT'] = 'Klaida: Ypatybė su šiuo pavadinimu jau egzistuoja!';
$lang['NRS_FEATURE_UPDATE_ERROR_TEXT'] = 'Klaida: MySQL atnaujinimo klaida egzistuojančiai ypatybei!';
$lang['NRS_FEATURE_UPDATED_TEXT'] = 'Užbaigta: Ypatybė buvo atnaujinta sėkmingai!';
$lang['NRS_FEATURE_INSERT_ERROR_TEXT'] = 'Klaida: MySQL įterpimo klaida naujai ypatibei!';
$lang['NRS_FEATURE_INSERTED_TEXT'] = 'Užbaigta: Nauja ypatybė buvo pridėta sėkmingai!';
$lang['NRS_FEATURE_REGISTERED_TEXT'] = 'Ypatybės pavadinimas užregistruotas vertimui.';
$lang['NRS_FEATURE_DELETE_ERROR_TEXT'] = 'Klaida: MySQL trinimo klaida egzistuojančiai ypatybei. Niekas nebuvo ištrinta iš duomenų bazės!';
$lang['NRS_FEATURE_DELETED_TEXT'] = 'Užbaigta: Ypatybė buvo ištrinta sėkmingai!';

// OK / Error Messages - Fuel Type Element
$lang['NRS_FUEL_TYPE_TITLE_EXISTS_ERROR_TEXT'] = 'Klaida: Kuro tipas su šiuo pavadinimu jau egzistuoja!';
$lang['NRS_FUEL_TYPE_UPDATE_ERROR_TEXT'] = 'Klaida: MySQL atnaujinimo klaida egzistuojančiam kuro tipui!';
$lang['NRS_FUEL_TYPE_UPDATED_TEXT'] = 'Užbaigta: Kuro tipas buvo atnaujintas sėkmingai!';
$lang['NRS_FUEL_TYPE_INSERT_ERROR_TEXT'] = 'Klaida: MySQL įterpimo klaida naujam kuro tipui!';
$lang['NRS_FUEL_TYPE_INSERTED_TEXT'] = 'Užbaigta: Naujas kuro tipas buvo pridėtas sėkmingai!';
$lang['NRS_FUEL_TYPE_REGISTERED_TEXT'] = 'Kuro tipo pavadinimas užregistruotas vertimui.';
$lang['NRS_FUEL_TYPE_DELETE_ERROR_TEXT'] = 'Klaida: MySQL trinimo klaida egzistuojančiam kuro tipui. Niekas nebuvo ištrinta iš duomenų bazės!';
$lang['NRS_FUEL_TYPE_DELETED_TEXT'] = 'Užbaigta: Kuro tipas buvo ištrintas sėkmingai!';

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
$lang['NRS_INSTALL_DEFAULT_COMPANY_NAME_TEXT'] = 'Automobilių nuomos įmonė';
$lang['NRS_INSTALL_DEFAULT_COMPANY_STREET_ADDRESS_TEXT'] = '625 2nd Street';
$lang['NRS_INSTALL_DEFAULT_COMPANY_CITY_TEXT'] = 'San Francisco';
$lang['NRS_INSTALL_DEFAULT_COMPANY_STATE_TEXT'] = 'CA';
$lang['NRS_INSTALL_DEFAULT_COMPANY_ZIP_CODE_TEXT'] = '94107';
$lang['NRS_INSTALL_DEFAULT_COMPANY_COUNTRY_TEXT'] = '';
$lang['NRS_INSTALL_DEFAULT_COMPANY_PHONE_TEXT'] = '(450) 600 4000';
$lang['NRS_INSTALL_DEFAULT_COMPANY_EMAIL_TEXT'] = 'info@yourdomain.com';
$lang['NRS_INSTALL_DEFAULT_PAGE_URL_SLUG_TEXT'] = 'automobiliu-nuoma'; // Latin letters only
$lang['NRS_INSTALL_DEFAULT_ITEM_URL_SLUG_TEXT'] = 'automobilis'; // Latin letters only
$lang['NRS_INSTALL_DEFAULT_LOCATION_URL_SLUG_TEXT'] = 'nuomos-punktas'; // Latin letters only
$lang['NRS_INSTALL_DEFAULT_SEARCH_PAGE_URL_SLUG_TEXT'] = 'paieska'; // Latin letters only
$lang['NRS_INSTALL_DEFAULT_CANCELLED_PAYMENT_PAGE_TITLE_TEXT'] = 'Mokėjimas atšauktas';
$lang['NRS_INSTALL_DEFAULT_CANCELLED_PAYMENT_PAGE_CONTENT_TEXT'] = 'Mokėjimas buvo atšauktas. Jūsų rezervacija nepatvirtinta.';
$lang['NRS_INSTALL_DEFAULT_CONFIRMATION_PAGE_TITLE_TEXT'] = 'Rezervacija patvirtinta';
$lang['NRS_INSTALL_DEFAULT_CONFIRMATION_PAGE_CONTENT_TEXT'] = 'Dėkojame. Jūsų mokėjimą gavome. Rezervacija patvirtinta.';
$lang['NRS_INSTALL_DEFAULT_TERMS_AND_CONDITIONS_PAGE_TITLE_TEXT'] = 'Automobilių nuomos sąlygos';
$lang['NRS_INSTALL_DEFAULT_TERMS_AND_CONDITIONS_PAGE_CONTENT_TEXT'] = 'Privaloma laikytis bendrųjų automobilių nuomos sąlygų.';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAYPAL_TEXT'] = 'Internetu - PayPal';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAYPAL_DESCRIPTION_TEXT'] = 'Apsaugotas greitas mokėjimas';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_STRIPE_TEXT'] = 'Kredito kortele (per Stripe.com)';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_BANK_TEXT'] = 'Bankiniu pavedimu';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_BANK_DETAILS_TEXT'] = 'Jūsų banko duomenys';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAY_OVER_THE_PHONE_TEXT'] = 'Mokėkite telefonu';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAY_ON_ARRIVAL_TEXT'] = 'Atsiimant automobilį';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAY_ON_ARRIVAL_DETAILS_TEXT'] = 'Reikalinga kreditinė kortelė';
$lang['NRS_INSTALL_DEFAULT_DEAR_TEXT'] = 'Gerb.';
$lang['NRS_INSTALL_DEFAULT_REGARDS_TEXT'] = 'Pagarbiai';
$lang['NRS_INSTALL_DEFAULT_EMAIL_TITLE_BOOKING_DETAILS_TEXT'] = 'Rezervacijos detalės - nr. [BOOKING_CODE]';
$lang['NRS_INSTALL_DEFAULT_EMAIL_TITLE_BOOKING_CONFIRMED_TEXT'] = 'Rezervacijos nr. [BOOKING_CODE] - patvirtinta';
$lang['NRS_INSTALL_DEFAULT_EMAIL_TITLE_BOOKING_CANCELLED_TEXT'] = 'Rezervacijos nr. [BOOKING_CODE] - atšaukta';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_TITLE_BOOKING_DETAILS_TEXT'] = 'Pranešimas: nauja rezervacija- [BOOKING_CODE]';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_TITLE_BOOKING_CONFIRMED_TEXT'] = 'Pranešimas: rezervacija apmokėta - [BOOKING_CODE]';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_TITLE_BOOKING_CANCELLED_TEXT'] = 'Pranešimas: rezervacija atšaukta - [BOOKING_CODE]';
$lang['NRS_INSTALL_DEFAULT_EMAIL_BODY_BOOKING_RECEIVED_TEXT'] = 'Jūsų rezervacijos detalės buvo gautos.';
$lang['NRS_INSTALL_DEFAULT_EMAIL_BODY_BOOKING_DETAILS_TEXT'] = 'Jūsų rezervacijos detalės:';
$lang['NRS_INSTALL_DEFAULT_EMAIL_BODY_PAYMENT_RECEIVED_TEXT'] = 'Apmokėjimas gautas. Jūsų rezervacija patvirtinta.';
$lang['NRS_INSTALL_DEFAULT_EMAIL_BODY_BOOKING_CANCELLED_TEXT'] = 'Jūsų rezervacija nr. [BOOKING_CODE] buvo atšaukta.';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_BOOKING_RECEIVED_TEXT'] = ' Naujos rezervacijos nr. [BOOKING_CODE] gauta iš [CUSTOMER_NAME].';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_BOOKING_DETAILS_TEXT'] = 'Rezervacijos detalės:';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_BOOKING_PAID_TEXT'] = 'Rezervacijos nr. [BOOKING_CODE] neseniai apmokėtas vartotojo [CUSTOMER_NAME].';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_BOOKING_CANCELLED_TEXT'] = 'Rezervacijos nr. [BOOKING_CODE] naudotojui [CUSTOMER_NAME] buvo atšaukta.';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_CANCELLED_BOOKING_DETAILS_TEXT'] = 'Rezervacijos detalės, kurios buvo nepatvirtintos:';

// OK / Error Messages - Invoice Element
$lang['NRS_INVOICE_UPDATE_ERROR_TEXT'] = 'Klaida: MySQL atnaujinimo klaida egzistuojančiam invoice!';
$lang['NRS_INVOICE_UPDATED_TEXT'] = 'Užbaigta: Sąskaita buvo atnaujinta sėkmingai!';
$lang['NRS_INVOICE_INSERT_ERROR_TEXT'] = 'Klaida: MySQL įterpimo klaida naujai sąskaitai!';
$lang['NRS_INVOICE_INSERTED_TEXT'] = 'Užbaigta: Sąskaita buvo pridėta sėkmingai!';
$lang['NRS_INVOICE_APPEND_ERROR_TEXT'] = 'Klaida: MySQL atnaujinimo klaida bandant pridėti naują sąskaita. Niekas nebuvo atnaujina duomenų bazėje!';
$lang['NRS_INVOICE_APPENDED_TEXT'] = 'Užbaigta: Sąskaita buvo pridėta sėkmingai!';
$lang['NRS_INVOICE_DELETE_ERROR_TEXT'] = 'Klaida: MySQL trinimo klaida egzistuojančiai sąskaitai. Niekas nebuvo ištrinta iš duomenų bazės!';
$lang['NRS_INVOICE_DELETED_TEXT'] = 'Užbaigta: Sąskaita buvo ištrintas sėkmingai!';

// OK / Error Messages - Item Element
$lang['NRS_ITEM_SKU_EXISTS_ERROR_TEXT'] = 'Klaida: Automobilis su šiuo SKU kodu jau egzistuoja!';
$lang['NRS_ITEM_MORE_UNITS_PER_BOOKING_THAN_IN_STOCK_ERROR_TEXT'] = 'Klaida: Negalima leisti rezervuoti daugiau automobilių nei yra garaže!';
$lang['NRS_ITEM_UPDATE_ERROR_TEXT'] = 'Klaida: MySQL atnaujinimo klaida egzistuojančiam automobiliui!';
$lang['NRS_ITEM_UPDATED_TEXT'] = 'Užbaigta: Automobilis atnaujintos sėkmingai!';
$lang['NRS_ITEM_INSERT_ERROR_TEXT'] = 'Klaida: MySQL įterpimo klaida naujam automobiliui!';
$lang['NRS_ITEM_INSERTED_TEXT'] = 'Užbaigta: Naujas automobilis pridėtas sėkmingai!';
$lang['NRS_ITEM_REGISTERED_TEXT'] = 'Automobilio modelio pavadinimas užregistruotas vertimui.';
$lang['NRS_ITEM_DELETE_ERROR_TEXT'] = 'Klaida: MySQL trynimo klaida egzistuojančiam automobiliui. Niekas nebuvo ištrinta iš duomenų bazės!';
$lang['NRS_ITEM_DELETED_TEXT'] = 'Užbaigta: Automobilis buvo ištrintas sėkmingai!';

// OK / Error Messages - Location Element
$lang['NRS_LOCATION_CODE_EXISTS_ERROR_TEXT'] = 'Klaida: Nuomos vieta su šiuo kodu jau egzistuoja!';
$lang['NRS_LOCATION_NAME_EXISTS_ERROR_TEXT'] = 'Klaida: Nuomos vieta su šiuo pavadinimu jau egzistuoja!';
$lang['NRS_LOCATION_UPDATE_ERROR_TEXT'] = 'Klaida: MySQL atnaujinimo klaida egzistuojančiai nuomos vietai!';
$lang['NRS_LOCATION_UPDATED_TEXT'] = 'Užbaigta: Nuomos vieta buvo atnaujintas sėkmingai!';
$lang['NRS_LOCATION_INSERT_ERROR_TEXT'] = 'Klaida: MySQL įterpimo klaida naujai nuomos vietai!';
$lang['NRS_LOCATION_INSERTED_TEXT'] = 'Užbaigta: Nauja nuomos vieta buvo pridėta sėkmingai!';
$lang['NRS_LOCATION_REGISTERED_TEXT'] = 'Nuomos vietos pavadinimas užregistruotas vertimui.';
$lang['NRS_LOCATION_DELETE_ERROR_TEXT'] = 'Klaida: MySQL trinimo klaida egzistuojančiai nuomos vietai. Niekas nebuvo ištrinta iš duomenų bazės!';
$lang['NRS_LOCATION_DELETED_TEXT'] = 'Užbaigta: Nuomos vieta buvo ištrintas sėkmingai!';

// OK / Error Messages - Manufacturer Element
$lang['NRS_MANUFACTURER_TITLE_EXISTS_ERROR_TEXT'] = 'Klaida: Gamintojas su šiuo pavadinimu jau egzistuoja!';
$lang['NRS_MANUFACTURER_UPDATE_ERROR_TEXT'] = 'Klaida: MySQL atnaujinimo klaida egzistuojančiam gamintojui!';
$lang['NRS_MANUFACTURER_UPDATED_TEXT'] = 'Užbaigta: Gamintojas buvo atnaujintas sėkmingai!';
$lang['NRS_MANUFACTURER_INSERT_ERROR_TEXT'] = 'Klaida: MySQL įterpimo klaida naujam gamintojui!';
$lang['NRS_MANUFACTURER_INSERTED_TEXT'] = 'Užbaigta: Naujas gamintojas buvo pridėtas sėkmingai!';
$lang['NRS_MANUFACTURER_REGISTERED_TEXT'] = 'Gamintojo pavadinimas užregistruotas vertimui.';
$lang['NRS_MANUFACTURER_DELETE_ERROR_TEXT'] = 'Klaida: MySQL trinimo klaida egzistuojančiam gamintojui. Niekas nebuvo ištrinta iš duomenų bazės!';
$lang['NRS_MANUFACTURER_DELETED_TEXT'] = 'Užbaigta: Gamintojas buvo ištrintas sėkmingai!';

// OK / Error Messages - (Extra) Option Element
$lang['NRS_EXTRA_OPTION_PLEASE_SELECT_ERROR_TEXT'] = 'Klaida: Pasirinkite priedą!';
$lang['NRS_EXTRA_OPTION_NAME_EXISTS_ERROR_TEXT'] = 'Klaida: Pasirinkimas su šiuo pavadinimui jau egzistuoja šitam priedui!';
$lang['NRS_EXTRA_OPTION_UPDATE_ERROR_TEXT'] = 'Klaida: MySQL atnaujinimo klaida egzistuojančiam prieto pasirikimui!';
$lang['NRS_EXTRA_OPTION_UPDATED_TEXT'] = 'Užbaigta: Priedo pasirinkimas buvo atnaujintas sėkmingai!';
$lang['NRS_EXTRA_OPTION_INSERT_ERROR_TEXT'] = 'Klaida: MySQL įterpimo klaida naujam priedo pasirinkimui!';
$lang['NRS_EXTRA_OPTION_INSERTED_TEXT'] = 'Užbaigta: Naujas priedo pasirinkimas buvo pridėtas sėkmingai!';
$lang['NRS_EXTRA_OPTION_REGISTERED_TEXT'] = 'Priedo pasirinkimo pavadinimas užregistruotas vertimui.';
$lang['NRS_EXTRA_OPTION_DELETE_ERROR_TEXT'] = 'Klaida: MySQL trinimo klaida egzistuojančiam priedo pasirinkimui. Niekas nebuvo ištrinta iš duomenų bazės!';
$lang['NRS_EXTRA_OPTION_DELETED_TEXT'] = 'Užbaigta: Priedo apsirinkimas buvo ištrintas sėkmingai!';

// OK / Error Messages - (Item) Option Element
$lang['NRS_ITEM_OPTION_PLEASE_SELECT_ERROR_TEXT'] = 'Klaida: Pasirinkite automobilį!';
$lang['NRS_ITEM_OPTION_NAME_EXISTS_ERROR_TEXT'] = 'Klaida: Pasirinkimas su šiuo pavadinimu jau egzistuoja šiam automobiliui!';
$lang['NRS_ITEM_OPTION_UPDATE_ERROR_TEXT'] = 'Klaida: MySQL atnaujinimo klaida egzistuojančiam automobilio pasirinkimui!';
$lang['NRS_ITEM_OPTION_UPDATED_TEXT'] = 'Užbaigta: Automobilio pasirinkimas buvo atnaujintas sėkmingai!';
$lang['NRS_ITEM_OPTION_INSERT_ERROR_TEXT'] = 'Klaida: MySQL įterpimo klaida naujam automobilio pasirinkimui!';
$lang['NRS_ITEM_OPTION_INSERTED_TEXT'] = 'Užbaigta: Naujas automobilio pasirinkimas buvo pridėtas sėkmingai!';
$lang['NRS_ITEM_OPTION_REGISTERED_TEXT'] = 'Automobilio pasirinkimas pavadinimas užregistruotas vertimui.';
$lang['NRS_ITEM_OPTION_DELETE_ERROR_TEXT'] = 'Klaida: MySQL trinimo klaida egzistuojančiam automobilio pasirinkimui. Niekas nebuvo ištrinta iš duomenų bazės!';
$lang['NRS_ITEM_OPTION_DELETED_TEXT'] = 'Užbaigta: Automobilio pasirinkimas buvo ištrintas sėkmingai!';

// OK / Error Messages - Payment Method Element
$lang['NRS_PAYMENT_METHOD_CODE_EXISTS_ERROR_TEXT'] = 'Klaida: Atsiskaitymo metodas su šiuo kodu jau egzistuoja!';
$lang['NRS_PAYMENT_METHOD_INVALID_NAME_TEXT'] = 'Klaida: Įveskite tinkama atsiskaitymo metodo pavadinimą!';
$lang['NRS_PAYMENT_METHOD_UPDATE_ERROR_TEXT'] = 'Klaida: MySQL atnaujinimo klaida egzistuojančiam atsiskaitymo metodui!';
$lang['NRS_PAYMENT_METHOD_UPDATED_TEXT'] = 'Užbaigta: Atsiskaitymo metodas buvo atnaujintas sėkmingai!';
$lang['NRS_PAYMENT_METHOD_INSERT_ERROR_TEXT'] = 'Klaida: MySQL įterpimo klaida naujam atsiskaitymo metodui!';
$lang['NRS_PAYMENT_METHOD_INSERTED_TEXT'] = 'Užbaigta: Naujas atsiskaitymo metodas buvo pridėtas sėkmingai!';
$lang['NRS_PAYMENT_METHOD_REGISTERED_TEXT'] = 'Atsiskaitymo metodo pavadinimas ir aprašymas užregistruotas vertimui.';
$lang['NRS_PAYMENT_METHOD_DELETE_ERROR_TEXT'] = 'Klaida: MySQL trinimo klaida egzistuojančiam atsiskaitymo metodui. Niekas nebuvo ištrinta iš duomenų bazės!';
$lang['NRS_PAYMENT_METHOD_DELETED_TEXT'] = 'Užbaigta: Atsiskaitymo metodas buvo ištrintas sėkmingai!';

// OK / Error Messages - Prepayment Element
$lang['NRS_PREPAYMENT_DAYS_INTERSECTION_ERROR_TEXT'] = 'Klaida: Pasirinktos išankstinio apmokėjimo plano dienos susikerta su kitu planu!';
$lang['NRS_PREPAYMENT_UPDATE_ERROR_TEXT'] = 'Klaida: MySQL atnaujinimo klaida egzistuojančiam išankstinio apmokėjimo planui!';
$lang['NRS_PREPAYMENT_UPDATED_TEXT'] = 'Užbaigta: Išankstinio apmokėjimo planas buvo atnaujintas sėkmingai!';
$lang['NRS_PREPAYMENT_INSERT_ERROR_TEXT'] = 'Klaida: MySQL įterpimo klaida naujam išankstinio apmokėjimo planui!';
$lang['NRS_PREPAYMENT_INSERTED_TEXT'] = 'Užbaigta: Naujas išankstinio apmokėjimo planas buvo pridėtas sėkmingai!';
$lang['NRS_PREPAYMENT_DELETE_ERROR_TEXT'] = 'Klaida: MySQL trinimo klaida egzistuojančiam išankstinio apmokėjimo planui. Niekas nebuvo ištrinta iš duomenų bazės!';
$lang['NRS_PREPAYMENT_DELETED_TEXT'] = 'Užbaigta: Išankstinio apmokėjimo planas buvo ištrintas sėkmingai!';

// OK / Error Messages - Price Group Element
$lang['NRS_PRICE_GROUP_UPDATE_ERROR_TEXT'] = 'Klaida: MySQL atnaujinimo klaida egzistuojančiai kainų grupei!';
$lang['NRS_PRICE_GROUP_UPDATED_TEXT'] = 'Užbaigta: Kainų grupė buvo atnaujinta sėkmingai!';
$lang['NRS_PRICE_GROUP_INSERT_ERROR_TEXT'] = 'Klaida: MySQL įterpimo klaida naujai kainų grupei!';
$lang['NRS_PRICE_GROUP_INSERTED_TEXT'] = 'Užbaigta: Nauja kainų grupė buvo pridėta sėkmingai!';
$lang['NRS_PRICE_GROUP_REGISTERED_TEXT'] = 'Kainų grupės pavadinimas užregistruotas vertimui.';
$lang['NRS_PRICE_GROUP_DELETE_ERROR_TEXT'] = 'Klaida: MySQL trinimo klaida egzistuojančiai kainų grupei. Niekas nebuvo ištrinta iš duomenų bazės!';
$lang['NRS_PRICE_GROUP_DELETED_TEXT'] = 'Užbaigta: Kainų grupė buvo ištrinta sėkmingai!';

// OK / Error Messages - Price Plan Element
$lang['NRS_PRICE_PLAN_LATER_DATE_ERROR_TEXT'] = 'Klaida: Pradžios data turi būti ankstesnė už bagaigos datą!';
$lang['NRS_PRICE_PLAN_INVALID_PRICE_GROUP_ERROR_TEXT'] = 'Klaida: Pasirinkite tinkamą kainos grupę!';
$lang['NRS_PRICE_PLAN_EXISTS_FOR_DATE_RANGE_ERROR_TEXT'] = 'Klaida: Jau egzistuoja kainos planas su šiomis datomis arba šiuo kupono kodu!';
$lang['NRS_PRICE_PLAN_UPDATE_ERROR_TEXT'] = 'Klaida: MySQL atnaujinimo klaida egzistuojančiam kainos planui!';
$lang['NRS_PRICE_PLAN_UPDATED_TEXT'] = 'Užbaigta: Kainos planas buvo atnaujintas sėkmingai!';
$lang['NRS_PRICE_PLAN_INSERT_ERROR_TEXT'] = 'Klaida: MySQL įterpimo klaida naujam kainos planui!';
$lang['NRS_PRICE_PLAN_INSERTED_TEXT'] = 'Užbaigta: Naujas kainos planas buvo pridėtas sėkmingai!';
$lang['NRS_PRICE_PLAN_DELETE_ERROR_TEXT'] = 'Klaida: MySQL trinimo klaida egzistuojančiam kainos planui. Niekas nebuvo ištrinta iš duomenų bazės!';
$lang['NRS_PRICE_PLAN_DELETED_TEXT'] = 'Užbaigta: Kainos planas buvo ištrintas sėkmingai!';

// OK / Error Messages - Tax Element
$lang['NRS_TAX_UPDATE_ERROR_TEXT'] = 'Klaida: MySQL atnaujinimo klaida egzistuojančiam mokėsčiui!';
$lang['NRS_TAX_UPDATED_TEXT'] = 'Užbaigta: Mokėstis buvo atnaujintas sėkmingai!';
$lang['NRS_TAX_INSERT_ERROR_TEXT'] = 'Klaida: MySQL įterpimo klaida naujam mokėsčiui!';
$lang['NRS_TAX_INSERTED_TEXT'] = 'Užbaigta: Naujas mokėstis buvo pridėtas sėkmingai!';
$lang['NRS_TAX_REGISTERED_TEXT'] = 'Mokėsčio pavadinimas užregistruotas vertimui.';
$lang['NRS_TAX_DELETE_ERROR_TEXT'] = 'Klaida: MySQL trinimo klaida egzistuojančiam mokėsčiui. Niekas nebuvo ištrinta iš duomenų bazės!';
$lang['NRS_TAX_DELETED_TEXT'] = 'Užbaigta: Mokėstis buvo ištrintas sėkmingai!';

// OK / Error Messages - Transmission Type Element
$lang['NRS_TRANSMISSION_TYPE_TITLE_EXISTS_ERROR_TEXT'] = 'Klaida: Transmisijos tipas su šiuo pavadinimu jau egzistuoja!';
$lang['NRS_TRANSMISSION_TYPE_UPDATE_ERROR_TEXT'] = 'Klaida: MySQL atnaujinimo klaida egzistuojančiam transmisijos tipui!';
$lang['NRS_TRANSMISSION_TYPE_UPDATED_TEXT'] = 'Užbaigta: Transmisijos tipas buvo atnaujintas sėkmingai!';
$lang['NRS_TRANSMISSION_TYPE_INSERT_ERROR_TEXT'] = 'Klaida: MySQL įterpimo klaida naujam transmisijos tipui!';
$lang['NRS_TRANSMISSION_TYPE_INSERTED_TEXT'] = 'Užbaigta: Naujas transmisijos tipas buvo pridėtas sėkmingai!';
$lang['NRS_TRANSMISSION_TYPE_REGISTERED_TEXT'] = 'Transmisijos tipo pavadinimas užregistruotas vertimui.';
$lang['NRS_TRANSMISSION_TYPE_DELETE_ERROR_TEXT'] = 'Klaida: MySQL trinimo klaida egzistuojančiam transmisijos tipui. Niekas nebuvo ištrinta iš duomenų bazės!';
$lang['NRS_TRANSMISSION_TYPE_DELETED_TEXT'] = 'Užbaigta: Transmisijos tipas buvo ištrintas sėkmingai!';

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