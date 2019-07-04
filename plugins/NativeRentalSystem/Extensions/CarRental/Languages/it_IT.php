<?php
/**
 * Language specific file
 * @Language - Italian
 * @Author - NetHome, LTD
 * @Email - info@nethomelive.com
 * @Website - http://nethomelive.com
 */
// Settings
$lang['LTR'] = FALSE;
$lang['NRS_RECAPTCHA_LANG'] = 'it';

// Roles
$lang['NRS_PARTNER_ROLE_NAME_TEXT'] = 'Car Partner';
$lang['NRS_ASSISTANT_ROLE_NAME_TEXT'] = 'Car Assistant';
$lang['NRS_MANAGER_ROLE_NAME_TEXT'] = 'Car Manager';

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
$lang['NRS_ADMIN_VIEW_DETAILS_TEXT'] = 'Visualizza dettagli';
$lang['NRS_ADMIN_VIEW_BOOKINGS_TEXT'] = 'Visualizza prenotazione';
$lang['NRS_ADMIN_VIEW_UNPAID_BOOKINGS_TEXT'] = 'Visualizza prenotazioni non pagate';
$lang['NRS_ADMIN_NO_BOOKINGS_YET_TEXT'] = 'Nessuna prenotazione';
$lang['NRS_ADMIN_BOOKING_DETAILS_TEXT'] = 'Dettagli prenotazione';
$lang['NRS_ADMIN_CUSTOMER_DETAILS_FROM_DB_TEXT'] = 'Dettagli cliente (Latest version from database)';
$lang['NRS_ADMIN_BOOKING_STATUS_TEXT'] = 'Stato prenotazione';
$lang['NRS_ADMIN_BOOKING_STATUS_UPCOMING_TEXT'] = 'Upcoming';
$lang['NRS_ADMIN_BOOKING_STATUS_DEPARTED_TEXT'] = 'Partita';
$lang['NRS_ADMIN_BOOKING_STATUS_COMPLETED_EARLY_TEXT'] = 'Completed Early';
$lang['NRS_ADMIN_BOOKING_STATUS_COMPLETED_TEXT'] = 'Completed';
$lang['NRS_ADMIN_BOOKING_STATUS_ACTIVE_TEXT'] = 'Attiva';
$lang['NRS_ADMIN_BOOKING_STATUS_CANCELLED_TEXT'] = 'Cancellato';
$lang['NRS_ADMIN_BOOKING_STATUS_PAID_TEXT'] = 'Pagato';
$lang['NRS_ADMIN_BOOKING_STATUS_UNPAID_TEXT'] = 'Non pagato';
$lang['NRS_ADMIN_BOOKING_STATUS_REFUNDED_TEXT'] = 'Rimborsato';
$lang['NRS_ADMIN_PRINT_INVOICE_TEXT'] = 'Stampa fattura';
$lang['NRS_ADMIN_BACK_TO_CUSTOMER_BOOKING_LIST_TEXT'] = 'Torna alla lista prenotazioni clienti';
$lang['NRS_ADMIN_CUSTOMERS_BY_LAST_VISIT_TEXT'] = 'Customers by Last Visit Date';
$lang['NRS_ADMIN_CUSTOMERS_BY_REGISTRATION_TEXT'] = 'Customers by Registration Date';
$lang['NRS_ADMIN_BOOKINGS_PERIOD_FROM_TO_TEXT'] = 'Reserv. Period: %s - %s';
$lang['NRS_ADMIN_PICKUPS_PERIOD_FROM_TO_TEXT'] = 'Pick-ups Period: %s - %s';
$lang['NRS_ADMIN_RETURNS_PERIOD_FROM_TO_TEXT'] = 'Returns Period: %s - %s';
$lang['NRS_ADMIN_UPCOMING_TEXT'] = 'Upcoming';
$lang['NRS_ADMIN_PAST_TEXT'] = 'Past';
$lang['NRS_ADMIN_CUSTOMER_BOOKINGS_TEXT'] = 'Customer Reservations';
$lang['NRS_ADMIN_BOOKINGS_BY_TEXT'] = 'Reservations by %s';
$lang['NRS_ADMIN_ALL_BOOKINGS_TEXT'] = 'All Reservations';
$lang['NRS_ADMIN_ALL_PICKUPS_TEXT'] = 'All Pick-ups';
$lang['NRS_ADMIN_ALL_RETURNS_TEXT'] = 'All Returns';
$lang['NRS_ADMIN_MAX_ITEM_UNITS_PER_BOOKING_TEXT'] = 'Maximum car units per reservation';
$lang['NRS_ADMIN_TOTAL_ITEM_UNITS_IN_STOCK_TEXT'] = 'Total car units in garage';
$lang['NRS_ADMIN_MAX_EXTRA_UNITS_PER_BOOKING_TEXT'] = 'Maximum extra units per reservation';
$lang['NRS_ADMIN_TOTAL_EXTRA_UNITS_IN_STOCK_TEXT'] = 'Total extra units in stock';
$lang['NRS_ADMIN_ITEM_PRICES_TEXT'] = 'Car Prices';
$lang['NRS_ADMIN_ITEM_DEPOSITS_TEXT'] = 'Car Deposits';
$lang['NRS_ADMIN_EXTRA_PRICES_TEXT'] = 'Extra Prices';
$lang['NRS_ADMIN_EXTRA_DEPOSITS_TEXT'] = 'Extra Deposits';
$lang['NRS_ADMIN_PICKUP_FEES_TEXT'] = 'Pick-Up Fees';
$lang['NRS_ADMIN_DISTANCE_FEES_TEXT'] = 'Distance Fees';
$lang['NRS_ADMIN_RETURN_FEES_TEXT'] = 'Returns Fees';
$lang['NRS_ADMIN_REGULAR_PRICE_TEXT'] = 'Prezzo normale';
$lang['NRS_ADMIN_PRICE_TYPE_TEXT'] = 'Tipo di prezzo';
$lang['NRS_ADMIN_ON_THE_LEFT_TEXT'] = 'On the Left';
$lang['NRS_ADMIN_ON_THE_RIGHT_TEXT'] = 'On the Right';
$lang['NRS_ADMIN_LOAD_FROM_OTHER_PLACE_TEXT'] = 'Load from other place';
$lang['NRS_ADMIN_LOAD_FROM_PLUGIN_TEXT'] = 'Load from this plugin';
$lang['NRS_ADMIN_EMAIL_TEXT'] = 'E-mail';
$lang['NRS_ADMIN_PUBLIC_TEXT'] = 'Public';
$lang['NRS_ADMIN_PRIVATE_TEXT'] = 'Private';
$lang['NRS_ADMIN_CALENDAR_NO_CALENDARS_FOUND_TEXT'] = 'Nessun calendario per le date inserite';
$lang['NRS_ADMIN_CHOOSE_PAGE_TEXT'] = ' - Choose page - ';
$lang['NRS_ADMIN_SELECT_EMAIL_TYPE_TEXT'] = '--- Selezione tipo di Email ---';
$lang['NRS_ADMIN_TOTAL_REQUESTS_LEFT_TEXT'] = 'Total requests left';
$lang['NRS_ADMIN_FAILED_REQUESTS_LEFT_TEXT'] = 'failed requests left';
$lang['NRS_ADMIN_EMAIL_ATTEMPTS_LEFT_TEXT'] = 'e-mail attempts left';

// Admin Menu
$lang['NRS_ADMIN_MENU_EXTENSION_PAGE_TITLE_TEXT'] = 'Car Rental System';
$lang['NRS_ADMIN_MENU_EXTENSION_LABEL_TEXT'] = 'Car Rental';
$lang['NRS_ADMIN_MENU_SYSTEM_UPDATE_TEXT'] = 'System Update';
$lang['NRS_ADMIN_MENU_NETWORK_UPDATE_TEXT'] = 'Network Update';
// Admin Menu - Benefit Manager
$lang['NRS_ADMIN_MENU_BENEFIT_MANAGER_TEXT'] = 'Benefit Manager';
$lang['NRS_ADMIN_MENU_ADD_EDIT_BENEFIT_TEXT'] = 'Add / Edit Benefit';
// Admin Menu - Item Manager
$lang['NRS_ADMIN_MENU_ITEM_MANAGER_TEXT'] = 'Car Manager';
$lang['NRS_ADMIN_MENU_ADD_EDIT_ITEM_TEXT'] = 'Add / Edit Car';
$lang['NRS_ADMIN_MENU_ADD_EDIT_MANUFACTURER_TEXT'] = 'Add / Edit Manufacturer';
$lang['NRS_ADMIN_MENU_ADD_EDIT_BODY_TYPE_TEXT'] = 'Add / Edit Body Type';
$lang['NRS_ADMIN_MENU_ADD_EDIT_FUEL_TYPE_TEXT'] = 'Add / Edit Fuel Type';
$lang['NRS_ADMIN_MENU_ADD_EDIT_TRANSMISSION_TYPE_TEXT'] = 'Add / Edit Transmission Type';
$lang['NRS_ADMIN_MENU_ADD_EDIT_FEATURE_TEXT'] = 'Add / Edit Feature';
$lang['NRS_ADMIN_MENU_ADD_EDIT_ITEM_OPTION_TEXT'] = 'Add / Edit Car Option';
$lang['NRS_ADMIN_MENU_BLOCK_ITEM_TEXT'] = 'Block Car';
// Admin Menu - Item Prices
$lang['NRS_ADMIN_MENU_ITEM_PRICES_TEXT'] = 'Car Prices';
$lang['NRS_ADMIN_MENU_ADD_EDIT_PRICE_GROUP_TEXT'] = 'Add / Edit Price Group';
$lang['NRS_ADMIN_MENU_ADD_EDIT_PRICE_PLAN_TEXT'] = 'Add / Edit Car Price Plan';
$lang['NRS_ADMIN_MENU_ADD_EDIT_ITEM_DISCOUNT_TEXT'] = 'Add / Edit Car Discount';
// Admin Menu - Extras Manager
$lang['NRS_ADMIN_MENU_EXTRAS_MANAGER_TEXT'] = 'Extras Manager';
$lang['NRS_ADMIN_MENU_ADD_EDIT_EXTRA_TEXT'] = 'Add / Edit Extra';
$lang['NRS_ADMIN_MENU_ADD_EDIT_EXTRA_OPTION_TEXT'] = 'Add / Edit Extra Option';
$lang['NRS_ADMIN_MENU_ADD_EDIT_EXTRA_DISCOUNT_TEXT'] = 'Add / Edit Extra Discount';
$lang['NRS_ADMIN_MENU_BLOCK_EXTRA_TEXT'] = 'Block Extra';
// Admin Menu - Location Manager
$lang['NRS_ADMIN_MENU_LOCATION_MANAGER_TEXT'] = 'Location Manager';
$lang['NRS_ADMIN_MENU_ADD_EDIT_LOCATION_TEXT'] = 'Add / Edit Location';
$lang['NRS_ADMIN_MENU_ADD_EDIT_DISTANCE_TEXT'] = 'Add / Edit Distance';
// Admin Menu - Reservation Manager
$lang['NRS_ADMIN_MENU_BOOKING_MANAGER_TEXT'] = 'Reservation Manager';
$lang['NRS_ADMIN_MENU_BOOKING_SEARCH_RESULTS_TEXT'] = 'Reservation Search Results';
$lang['NRS_ADMIN_MENU_ITEM_CALENDAR_SEARCH_RESULTS_TEXT'] = 'Car Calendar Search Results';
$lang['NRS_ADMIN_MENU_EXTRAS_CALENDAR_SEARCH_TEXT'] = 'Extras Calendar Search Results';
$lang['NRS_ADMIN_MENU_CUSTOMER_SEARCH_RESULTS_TEXT'] = 'Customer Search Results';
$lang['NRS_ADMIN_MENU_ADD_EDIT_CUSTOMER_TEXT'] = 'Add/Edit Customer';
$lang['NRS_ADMIN_MENU_ADD_EDIT_BOOKING_TEXT'] = 'Add/Edit Reservation';
$lang['NRS_ADMIN_MENU_VIEW_DETAILS_TEXT'] = 'View Reservation Details';
$lang['NRS_ADMIN_MENU_PRINT_INVOICE_TEXT'] = 'Print Invoice';
// Admin Menu - Payments & Taxes
$lang['NRS_ADMIN_MENU_PAYMENTS_AND_TAXES_TEXT'] = 'Payments &amp; Taxes';
$lang['NRS_ADMIN_MENU_ADD_EDIT_PAYMENT_METHOD_TEXT'] = 'Add / Edit Payment Method';
$lang['NRS_ADMIN_MENU_ADD_EDIT_PREPAYMENT_TEXT'] = 'Add / Edit Prepayment';
$lang['NRS_ADMIN_MENU_ADD_EDIT_TAX_TEXT'] = 'Add / Edit Tax';
// Admin Menu - Settings
$lang['NRS_ADMIN_MENU_SINGLE_MANAGER_TEXT'] = 'Settings';
$lang['NRS_ADMIN_MENU_ADD_EDIT_GLOBAL_SETTINGS_TEXT'] = 'Add / Edit Global Settings';
$lang['NRS_ADMIN_MENU_ADD_EDIT_CUSTOMER_SETTINGS_TEXT'] = 'Add / Edit Customer Settings';
$lang['NRS_ADMIN_MENU_ADD_EDIT_SEARCH_SETTINGS_TEXT'] = 'Add / Edit Search Settings';
$lang['NRS_ADMIN_MENU_ADD_EDIT_PRICE_SETTINGS_TEXT'] = 'Add / Edit Price Settings';
$lang['NRS_ADMIN_MENU_ADD_EDIT_EMAIL_TEXT'] = 'Add / Edit Email';
$lang['NRS_ADMIN_MENU_IMPORT_DEMO_TEXT'] = 'Import Demo';
$lang['NRS_ADMIN_MENU_CONTENT_PREVIEW_TEXT'] = 'Content Preview';
// Admin Menu - Instructions
$lang['NRS_ADMIN_MENU_INSTRUCTIONS_TEXT'] = 'Instructions';
// Admin Menu - Network Manager
$lang['NRS_ADMIN_MENU_NETWORK_MANAGER_TEXT'] = 'Network Manager';

// Admin Pages Post Type
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_NAME_TEXT'] = 'Rental Page'; // name
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_SINGULAR_NAME_TEXT'] = 'Rental Pages'; // singular_name
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_MENU_NAME_TEXT'] = 'Rental Pages'; // menu_name
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_PARENT_PAGE_COLON_TEXT'] = 'Parent Rental Page'; // parent_item_colon
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_ALL_PAGES_TEXT'] = 'All Information Pages'; // all_items
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_VIEW_PAGE_TEXT'] = 'View Rental Page'; // view_item
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_ADD_NEW_PAGE_TEXT'] = 'Add New Rental Page'; // add_new_item
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_ADD_NEW_TEXT'] = 'Add New Page'; // add_new
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_EDIT_PAGE_TEXT'] = 'Edit Rental Page'; // edit_item
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_UPDATE_PAGE_TEXT'] = 'Update Rental Page'; // update_item
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_SEARCH_PAGES_TEXT'] = 'Search Rental Page'; // search_items
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_NOT_FOUND_TEXT'] = 'Not Found'; // not_found
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_NOT_FOUND_IN_TRASH_TEXT'] = 'Not found in Trash'; // not_found_in_trash
$lang['NRS_ADMIN_PAGE_POST_TYPE_DESCRIPTION_TEXT'] = 'List of car information pages';

// Admin Item Post Type
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_NAME_TEXT'] = 'Car Page'; // name
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_SINGULAR_NAME_TEXT'] = 'Car Pages'; // singular_name
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_MENU_NAME_TEXT'] = 'Car Pages'; // menu_name
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_PARENT_ITEM_COLON_TEXT'] = 'Parent Car'; // parent_item_colon
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_ALL_ITEMS_TEXT'] = 'All Car Pages'; // all_items
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_VIEW_ITEM_TEXT'] = 'View Car Page'; // view_item
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_ADD_NEW_ITEM_TEXT'] = 'Add New Car Page'; // add_new_item
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_ADD_NEW_TEXT'] = 'Add New Page'; // add_new
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_EDIT_ITEM_TEXT'] = 'Edit Car Page'; // edit_item
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_UPDATE_ITEM_TEXT'] = 'Update Car Page'; // update_item
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_SEARCH_ITEMS_TEXT'] = 'Search Car Page'; // search_items
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_NOT_FOUND_TEXT'] = 'Not Found'; // not_found
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_NOT_FOUND_IN_TRASH_TEXT'] = 'Not found in Trash'; // not_found_in_trash
$lang['NRS_ADMIN_ITEM_POST_TYPE_DESCRIPTION_TEXT'] = 'List of car pages';

// Admin Location Post Type
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_NAME_TEXT'] = 'Car Location'; // name
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_SINGULAR_NAME_TEXT'] = 'Car Locations'; // singular_name
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_MENU_NAME_TEXT'] = 'Car Locations'; // menu_name
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_PARENT_LOCATION_COLON_TEXT'] = 'Parent Car Location'; // parent_item_colon
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_ALL_LOCATIONS_TEXT'] = 'All Car Location Pages'; // all_items
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_VIEW_LOCATION_TEXT'] = 'View Car Location Page'; // view_item
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_ADD_NEW_LOCATION_TEXT'] = 'Add New Car Location Page'; // add_new_item
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_ADD_NEW_TEXT'] = 'Add New Page'; // add_new
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_EDIT_LOCATION_TEXT'] = 'Edit Car Location Page'; // edit_item
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_UPDATE_LOCATION_TEXT'] = 'Update Car Location Page'; // update_item
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_SEARCH_LOCATIONS_TEXT'] = 'Search Car Location Page'; // search_items
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_NOT_FOUND_TEXT'] = 'Not Found'; // not_found
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_NOT_FOUND_IN_TRASH_TEXT'] = 'Not found in Trash'; // not_found_in_trash
$lang['NRS_ADMIN_LOCATION_POST_TYPE_DESCRIPTION_TEXT'] = 'List of car location pages';

// Admin Core
$lang['NRS_ADMIN_EDIT_TEXT'] = 'Modifica';
$lang['NRS_ADMIN_DELETE_TEXT'] = 'Cancella';
$lang['NRS_ADMIN_CANCEL_TEXT'] = 'Cancella';
$lang['NRS_ADMIN_UNBLOCK_TEXT'] = 'Sblocca';
$lang['NRS_ADMIN_MARK_PAID_TEXT'] = 'Segna come pagato';
$lang['NRS_ADMIN_MARK_COMPLETED_EARLY_TEXT'] = 'Mark Completed Early';
$lang['NRS_ADMIN_REFUND_TEXT'] = 'Rimborso pagamento';
$lang['NRS_ADMIN_SELECT_LOCATION_TEXT'] = '-- Seleziona Luogo --';
$lang['NRS_ADMIN_ALL_LOCATIONS_TEXT'] = 'All Locations';
$lang['NRS_ADMIN_AVAILABLE_TEXT'] = 'Available';
$lang['NRS_ADMIN_DISPLAYED_TEXT'] = 'Displayed';
$lang['NRS_ADMIN_VISIBLE_TEXT'] = 'Visibile';
$lang['NRS_ADMIN_HIDDEN_TEXT'] = 'Nascosto';
$lang['NRS_ADMIN_ENABLED_TEXT'] = 'Abilitato';
$lang['NRS_ADMIN_DISABLED_TEXT'] = 'Disattivato';
$lang['NRS_ADMIN_ALLOWED_TEXT'] = 'Allowed';
$lang['NRS_ADMIN_FAILED_TEXT'] = 'Failed';
$lang['NRS_ADMIN_BLOCKED_TEXT'] = 'Blocked';
$lang['NRS_ADMIN_REQUEST_TEXT'] = 'Request';
$lang['NRS_ADMIN_REQUESTS_TEXT'] = 'Requests';
$lang['NRS_ADMIN_IP_TEXT'] = 'IP';
$lang['NRS_ADMIN_CHECK_TEXT'] = 'Check';
$lang['NRS_ADMIN_SKIP_TEXT'] = 'Skip';
$lang['NRS_ADMIN_YES_TEXT'] = 'Si';
$lang['NRS_ADMIN_NO_TEXT'] = 'No';
$lang['NRS_ADMIN_DAILY_TEXT'] = 'Giornaliero';
$lang['NRS_ADMIN_HOURLY_TEXT'] = 'Orario';
$lang['NRS_ADMIN_PER_BOOKING_TEXT'] = 'Per prenotazione';
$lang['NRS_ADMIN_COMBINED_TEXT'] = 'Combinato - Giornaliero e Orario';
$lang['NRS_ADMIN_NEVER_TEXT'] = 'Mai';
$lang['NRS_ADMIN_DROPDOWN_TEXT'] = 'Dropdown';
$lang['NRS_ADMIN_SLIDER_TEXT'] = 'Slider';
$lang['NRS_ADMIN_SELECT_DEMO_TEXT'] = ' --- Select Live Demo --- ';
$lang['NRS_ADMIN_WITHOUT_TRANSLATION_TEXT'] = 'Without translation';
$lang['NRS_ADMIN_VIEW_PAGE_IN_NEW_WINDOW_TEXT'] = 'View %s page in new window';

// Core
$lang['NRS_IMAGE_ALT_TEXT'] = 'Immagine';
$lang['NRS_PER_BOOKING_SHORT_TEXT'] = '';
$lang['NRS_PER_DAY_SHORT_TEXT'] = 'g.';
$lang['NRS_PER_HOUR_SHORT_TEXT'] = 'h';
$lang['NRS_PER_BOOKING_TEXT'] = 'prenotazione';
$lang['NRS_PER_DAY_TEXT'] = 'giorno';
$lang['NRS_PER_HOUR_TEXT'] = 'ora';
$lang['NRS_SELECT_DATE_TEXT'] = 'Data';
$lang['NRS_SELECT_YEAR_TEXT'] = 'Anno';
$lang['NRS_SELECT_MONTH_TEXT'] = 'Mese';
$lang['NRS_SELECT_DAY_TEXT'] = 'Giorno';
$lang['NRS_PRICE_TEXT'] = 'Prezzo';
$lang['NRS_PERIOD_TEXT'] = 'Periodo';
$lang['NRS_DURATION_TEXT'] = 'Durata';
$lang['NRS_MON_TEXT'] = 'Lun';
$lang['NRS_TUE_TEXT'] = 'Mar';
$lang['NRS_WED_TEXT'] = 'Mer';
$lang['NRS_THU_TEXT'] = 'Gio';
$lang['NRS_FRI_TEXT'] = 'Ven';
$lang['NRS_SAT_TEXT'] = 'Sab';
$lang['NRS_SUN_TEXT'] = 'Dom';
$lang['NRS_LUNCH_TEXT'] = 'Lunch';
$lang['NRS_MONDAYS_TEXT'] = 'Luned&igrave;';
$lang['NRS_TUESDAYS_TEXT'] = 'Marted&igrave;';
$lang['NRS_WEDNESDAYS_TEXT'] = 'Mercoled&igrave;';
$lang['NRS_THURSDAYS_TEXT'] = 'Gioved&igrave;';
$lang['NRS_FRIDAYS_TEXT'] = 'Venerd&igrave;';
$lang['NRS_SATURDAYS_TEXT'] = 'Sabato';
$lang['NRS_SUNDAYS_TEXT'] = 'Domenica';
$lang['NRS_LUNCH_TIME_TEXT'] = 'Lunch Time';
$lang['NRS_ALL_YEAR_TEXT'] = 'All Year';
$lang['NRS_ALL_DAY_TEXT'] = '00:00 - 24:00';
$lang['NRS_PARTIAL_DAY_TEXT'] = '%s - 12:00';
$lang['NRS_MIDNIGHT_TEXT'] = '00:00';
$lang['NRS_NOON_TEXT'] = '12:00';
$lang['NRS_CLOSED_TEXT'] = 'Chiuso';
$lang['NRS_OPEN_TEXT'] = 'Aperto';
$lang['NRS_TODAY_TEXT'] = 'Today';
$lang['NRS_DATE_TEXT'] = 'Data';
$lang['NRS_TIME_TEXT'] = 'Tempo';
$lang['NRS_DAYS_TEXT'] = 'giorni';
$lang['NRS_DAYS2_TEXT'] = 'giorni';
$lang['NRS_DAY_TEXT'] = 'giorni';
$lang['NRS_HOURS_TEXT'] = 'orario';
$lang['NRS_HOURS2_TEXT'] = 'orario';
$lang['NRS_HOUR_TEXT'] = 'orario';
$lang['NRS_MINUTES_TEXT'] = 'minuti';
$lang['NRS_MINUTES2_TEXT'] = 'minuti';
$lang['NRS_MINUTE_TEXT'] = 'minuti';
$lang['NRS_DAILY_TEXT'] = 'Giornaliero';
$lang['NRS_HOURLY_TEXT'] = 'Orario';
$lang['NRS_ON_ST_TEXT'] = 'st';
$lang['NRS_ON_ND_TEXT'] = 'nd';
$lang['NRS_ON_RD_TEXT'] = 'rd';
$lang['NRS_ON_TH_TEXT'] = 'th';
$lang['NRS_ON_TEXT'] = 'on'; // on
$lang['NRS_THE_ST_TEXT'] = 'st';
$lang['NRS_THE_ND_TEXT'] = 'nd';
$lang['NRS_THE_RD_TEXT'] = 'rd';
$lang['NRS_THE_TH_TEXT'] = 'th';
$lang['NRS_UNCLASSIFIED_ITEM_TYPE_TEXT'] = 'Altro';
$lang['NRS_NO_ITEMS_AVAILABLE_TEXT'] = 'Nessun veicolo diponibile';
$lang['NRS_NO_ITEMS_AVAILABLE_IN_THIS_CLASS_TEXT'] = 'Nessun veicolo diponibile per questa classe';
$lang['NRS_NO_EXTRAS_AVAILABLE_TEXT'] = 'Nessun optional diponibile';
$lang['NRS_NO_MANUFACTURERS_AVAILABLE_TEXT'] = 'No manufacturers available';
$lang['NRS_NO_LOCATIONS_AVAILABLE_TEXT'] = 'No locations available';
$lang['NRS_NO_BENEFITS_AVAILABLE_TEXT'] = 'No benefits available';
$lang['NRS_NA_TEXT'] = 'N/A';
$lang['NRS_NONE_TEXT'] = 'Nessuno';
$lang['NRS_NOT_SET_TEXT'] = 'Not set';
$lang['NRS_DO_NOT_EXIST_TEXT'] = 'Non esiste';
$lang['NRS_EXIST_TEXT'] = 'Esiste';
$lang['NRS_NOT_REQ_TEXT'] = 'Not req.';
$lang['NRS_REQ_TEXT'] = 'Req.';
$lang['NRS_NOT_REQUIRED_TEXT'] = 'Non richiesto';
$lang['NRS_REQUIRED_TEXT'] = 'Richiesto';
$lang['NRS_DONT_DISPLAY_TEXT'] = "Non visualizzare";
$lang['NRS_WITH_TAX_TEXT'] = 'con IVA';
$lang['NRS_WITHOUT_TAX_TEXT'] = 'privo IVA';
$lang['NRS_TAX_TEXT'] = 'IVA';
$lang['NRS_FROM_TEXT'] = 'Da';
$lang['NRS_TO_TEXT'] = 'A';
$lang['NRS_ALL_TEXT'] = 'All';
$lang['NRS_OR_TEXT'] = 'O';
$lang['NRS_AND_TEXT'] = 'e';
$lang['NRS_UNLIMITED_TEXT'] = 'Illimitato';
$lang['NRS_DEPOSIT_TEXT'] = 'Deposito';
$lang['NRS_DISCOUNT_TEXT'] = 'Sconto';
$lang['NRS_PREPAYMENT_TEXT'] = 'Importo prepagato';
$lang['NRS_TOTAL_TEXT'] = 'Totale';
$lang['NRS_BACK_TEXT'] = 'Indietro';
$lang['NRS_CONTINUE_TEXT'] = 'Continua';
$lang['NRS_SEARCH_TEXT'] = 'Cerca';
$lang['NRS_SELECT_DROPDOWN_TEXT'] = '--- Seleziona ---';
$lang['NRS_ITEM_TEXT'] = 'Veicolo';
$lang['NRS_EXTRA_TEXT'] = 'Extra';
$lang['NRS_RENTAL_OPTION_TEXT'] = 'Opzione noleggio';
$lang['NRS_ITEMS_TEXT'] = 'Auto';
$lang['NRS_EXTRAS_TEXT'] = 'Opzioni noleggio';
$lang['NRS_RENTAL_OPTIONS_TEXT'] = 'Opzioni noleggio';
$lang['NRS_SHOW_ITEM_TEXT'] = 'Visualizza vettura';
$lang['NRS_VIA_PARTNER_TEXT'] = 'via %s';
$lang['NRS_COUPON_TEXT'] = 'Coupon';

// Booking step no. 1 - item search
$lang['NRS_BOOKING_TEXT'] = 'Prenotazione';
$lang['NRS_PICKUP_TEXT'] = 'Ritiro';
$lang['NRS_RETURN_TEXT'] = 'Consegna';
$lang['NRS_OTHER_TEXT'] = 'Altre';
$lang['NRS_INFORMATION_TEXT'] = 'Informazioni';
$lang['NRS_CITY_AND_LOCATION_TEXT'] = 'Citt&agrave; e Luogo:';
$lang['NRS_PICKUP_CITY_AND_LOCATION_TEXT'] = 'Citt&agrave; e Luogo per il ritiro:';
$lang['NRS_RETURN_CITY_AND_LOCATION_TEXT'] = 'Citt&agrave; e Luogo per la riconsegna:';
$lang['NRS_SELECT_BOOKING_DATE_TEXT'] = 'Data:';
$lang['NRS_SELECT_BOOKING_PERIOD_TEXT'] = 'Periodo di prenotazione:';
$lang['NRS_COUPON_CODE_TEXT'] = 'Coupon code';
$lang['NRS_I_HAVE_BOOKING_CODE_TEXT'] = 'Ho un numero prenotazione:';
$lang['NRS_I_HAVE_COUPON_CODE_TEXT'] = 'I have coupon code:';
$lang['NRS_PICKUP_LOCATION_TEXT'] = 'Luogo di ritiro';
$lang['NRS_RETURN_LOCATION_TEXT'] = 'Luogo di riconsegna';
$lang['NRS_ALL_BODY_TYPES_DROPDOWN_TEXT'] = '---- Tutti i tipi ----';
$lang['NRS_ALL_TRANSMISSION_TYPES_DROPDOWN_TEXT'] = '---- Tutte le trasmissioni ----';
$lang['NRS_SELECT_PICKUP_LOCATION_TEXT'] = '-- Seleziona il luogo di ritiro --';
$lang['NRS_SELECT_RETURN_LOCATION_TEXT'] = '-- Seleziona il luogo di riconsegna --';
$lang['NRS_PICKUP_DATE_TEXT'] = 'Data di ritiro';
$lang['NRS_RETURN_DATE_TEXT'] = 'Data di riconsegna';
$lang['NRS_PICKUP_DATE_ALERT_TEXT'] = 'Per favore inserisci la data di ritiro!';
$lang['NRS_RETURN_DATE_ALERT_TEXT'] = 'Per favore inserisci la data riconsegna!';
$lang['NRS_BOOKING_PERIOD_ALERT_TEXT'] = 'Per favore inserisci la data Check Out!';
$lang['NRS_PICKUP_LOCATION_ALERT_TEXT'] = 'Per favore selezione il luogo del ritiro!';
$lang['NRS_RETURN_LOCATION_ALERT_TEXT'] = 'Per favore selezione il luogo per la riconsegna!';
$lang['NRS_COUPON_CODE_ALERT_TEXT'] = 'Please enter coupon code!';
$lang['NRS_SHOW_ITEM_DESCRIPTION_TEXT'] = 'Mostra la descrizione auto';
$lang['NRS_UPDATE_BOOKING_TEXT'] = 'Aggiornare la mia prenotazione';
$lang['NRS_CANCEL_BOOKING_TEXT'] = 'Cancella la mia prenotazione';
$lang['NRS_CHANGE_BOOKING_DATE_TIME_AND_LOCATION_TEXT'] = 'Cambia data, Ora e Luogo';
$lang['NRS_CHANGE_BOOKED_ITEMS_TEXT'] = 'Cambia Veicolo';
$lang['NRS_CHANGE_EXTRAS_TEXT'] = 'Cambia Opzioni';
$lang['NRS_CHANGE_RENTAL_OPTIONS_TEXT'] = 'Opzioni di noleggio';
$lang['NRS_IN_THIS_LOCATION_TEXT'] = 'In questa filiale';
$lang['NRS_AFTERHOURS_PICKUP_IS_NOT_ALLOWED_TEXT'] = 'Non consentito';
$lang['NRS_AFTERHOURS_RETURN_IS_NOT_ALLOWED_TEXT'] = 'Not consentito';

// Booking step no. 2 - search results
$lang['NRS_DISTANCE_AWAY_TEXT'] = '%s away';
$lang['NRS_BOOKING_DATA_TEXT'] = 'Dettagli prenotazione';
$lang['NRS_BOOKING_CODE_TEXT'] = 'Prenotazione n.';
$lang['NRS_BOOKING_EDIT_TEXT'] = 'modifica';
$lang['NRS_BOOKING_PICKUP_TEXT'] = 'Ritiro';
$lang['NRS_BOOKING_BUSINESS_HOURS_TEXT'] = 'Orario di lavoro';
$lang['NRS_BOOKING_FEE_TEXT'] = 'Tassa';
$lang['NRS_BOOKING_RETURN_TEXT'] = 'Restituzione';
$lang['NRS_BOOKING_NIGHTLY_RATE_TEXT'] = 'dopo ore';
$lang['NRS_BOOKING_AFTERHOURS_TEXT'] = 'dopo ore';
$lang['NRS_BOOKING_EARLY_TEXT'] = 'Early';
$lang['NRS_BOOKING_LATE_TEXT'] = 'Late';
$lang['NRS_BOOKING_AFTERHOURS_PICKUP_TEXT'] = 'Ritiro fuori orario';
$lang['NRS_BOOKING_AFTERHOURS_PICKUP_IMPOSSIBLE_TEXT'] = 'Impossibile';
$lang['NRS_BOOKING_AFTERHOURS_RETURN_TEXT'] = 'Restituzione fuori orario';
$lang['NRS_BOOKING_AFTERHOURS_RETURN_IMPOSSIBLE_TEXT'] = 'Impossibile';
$lang['NRS_CHOOSE_TEXT'] = 'Scegli';
$lang['NRS_SEARCH_RESULTS_TEXT'] = 'Risultati ricerca';
$lang['NRS_MILEAGE_TEXT'] = 'Chilometraggio';

// Booking step no. 3 - booking options
$lang['NRS_SELECT_RENTAL_OPTIONS_TEXT'] = 'Scegli gli optional';
$lang['NRS_SELECTED_ITEMS_TEXT'] = 'Veicolo selezionato';
$lang['NRS_FOR_DEPENDANT_ITEM_TEXT'] = ' (for %s)';
$lang['NRS_NO_EXTRAS_AVAILABLE_CLICK_CONTINUE_TEXT'] = 'Non sono diponibili altri optional. Clicca sul pulsante continua';

// Booking step no. 4 - booking details
$lang['NRS_PICKUP_DATE_AND_TIME_TEXT'] = 'Data e ora per il ritiro';
$lang['NRS_RETURN_DATE_AND_TIME_TEXT'] = 'Data e ora per la riconsegna';
$lang['NRS_UNIT_PRICE_TEXT'] = 'Prezzo unit&agrave;';
$lang['NRS_QUANTITY_TEXT'] = 'Quantit&agrave;';
$lang['NRS_QUANTITY_SHORT_TEXT'] = 'Qta.';
$lang['NRS_PICKUP_FEE_TEXT'] = 'Spesa ritiro';
$lang['NRS_RETURN_FEE_TEXT'] = 'Spesa restituzione';
$lang['NRS_NIGHTLY_PICKUP_RATE_APPLIED_TEXT'] = '(Prezzo notturno applicato)';
$lang['NRS_NIGHTLY_RETURN_RATE_APPLIED_TEXT'] = '(Prezzo notturno applicato)';
$lang['NRS_ITEM_QUANTITY_SUFFIX_TEXT'] = 'veicolo(i)';
$lang['NRS_EXTRA_QUANTITY_SUFFIX_TEXT'] = 'extra(s)';
$lang['NRS_PAY_NOW_OR_AT_PICKUP_TEXT'] = 'Paga ora / al ritiro';
$lang['NRS_PAY_NOW_TEXT'] = 'Paga ora';
$lang['NRS_PAY_AT_PICKUP_TEXT'] = 'Paga al ritiro';
$lang['NRS_PAY_LATER_OR_ON_RETURN_TEXT'] = 'Pay Later / on Return';
$lang['NRS_PAY_LATER_TEXT'] = 'Pay Later';
$lang['NRS_PAY_ON_RETURN_TEXT'] = 'Pay on Return';
$lang['NRS_ITEM_RENTAL_DETAILS_TEXT'] = 'Dettagli del noleggio';
$lang['NRS_MANUFACTURER_TEXT'] = 'Marchio Auto';
$lang['NRS_ITEM_MODEL_TEXT'] = 'Modello Auto';
$lang['NRS_GROSS_TOTAL_TEXT'] = 'Subtotale';
$lang['NRS_GRAND_TOTAL_TEXT'] = 'Totale';
$lang['NRS_BOOKING_DETAILS_TEXT'] = 'Dettagli prenotazione';
$lang['NRS_CUSTOMER_DETAILS_TEXT'] = 'Dettaglio clienti';
$lang['NRS_EXISTING_CUSTOMER_DETAILS_TEXT'] = 'Dettagli cliente esistente';
$lang['NRS_EXISTING_CUSTOMER_TEXT'] = 'Cliente esistente';
$lang['NRS_EMAIL_ADDRESS_TEXT'] = 'Indirizzo e-mail';
$lang['NRS_FETCH_CUSTOMER_DETAILS_TEXT'] = 'Dettagli consegna';
$lang['NRS_OR_ENTER_NEW_DETAILS_TEXT'] = 'O';
$lang['NRS_CUSTOMER_TEXT'] = 'Cliente';
$lang['NRS_TITLE_TEXT'] = 'Titolo';
$lang['NRS_MR_TEXT'] = 'Sig.';
$lang['NRS_MS_TEXT'] = 'Sig.na';
$lang['NRS_MRS_TEXT'] = 'Sig.ra';
$lang['NRS_MISS_TEXT'] = 'Miss';
$lang['NRS_DR_TEXT'] = 'Dott';
$lang['NRS_PROF_TEXT'] = 'Prof';
$lang['NRS_FIRST_NAME_TEXT'] = 'Nome';
$lang['NRS_LAST_NAME_TEXT'] = 'Cognome';
$lang['NRS_DATE_OF_BIRTH_TEXT'] = 'Data di nascita';
$lang['NRS_YEAR_OF_BIRTH_TEXT'] = 'Anno di nascita';
$lang['NRS_ADDRESS_TEXT'] = 'Indirizzo';
$lang['NRS_STREET_ADDRESS_TEXT'] = 'Indirizzo';
$lang['NRS_CITY_TEXT'] = 'Citt&agrave; ';
$lang['NRS_STATE_TEXT'] = 'Stato';
$lang['NRS_ZIP_CODE_TEXT'] = 'CAP';
$lang['NRS_COUNTRY_TEXT'] = 'Citt&agrave; ';
$lang['NRS_PHONE_TEXT'] = 'Telefono';
$lang['NRS_EMAIL_TEXT'] = 'Email';
$lang['NRS_ADDITIONAL_COMMENTS_TEXT'] = 'Note';
$lang['NRS_CUSTOMER_ID_TEXT'] = 'ID Cliente';
$lang['NRS_IP_ADDRESS_TEXT'] = 'Indirizzo IP';
$lang['NRS_PAY_BY_SHORT_TEXT'] = 'Pagamento';
$lang['NRS_I_AGREE_WITH_TERMS_AND_CONDITIONS_TEXT'] = 'Accetto i termini e condizioni';
$lang['NRS_TERMS_AND_CONDITIONS_TEXT'] = 'Termini e condizioni';
$lang['NRS_CONFIRM_TEXT'] = 'Conferma';
$lang['NRS_FIELD_REQUIRED_TEXT'] = 'Questo campo &egrave; richiesto';

// Booking step no. 5 - process booking
$lang['NRS_PAYMENT_DETAILS_TEXT'] = 'Dettagli pagamento';
$lang['NRS_PAYMENT_OPTION_TEXT'] = 'Opzioni pagamento';
$lang['NRS_PAYER_EMAIL_TEXT'] = 'E-mail pagatore';
$lang['NRS_TRANSACTION_ID_TEXT'] = 'ID transazione';
$lang['NRS_PROCESSING_PAYMENT_TEXT'] = 'Elaboro il Pagamento...';
$lang['NRS_PLEASE_WAIT_UNTIL_PAYMENT_WILL_BE_PROCESSED_TEXT'] = 'Attendere prego, il vostro ordine verr&agrave; elaborato...';

//display-booking-confirm.php
$lang['NRS_STEP5_PAY_ONLINE_TEXT'] = 'pagato online';
$lang['NRS_STEP5_PAY_AT_PICKUP_TEXT'] = 'Paga al ritiro';
$lang['NRS_THANK_YOU_TEXT'] = 'Grazie!';
$lang['NRS_YOUR_BOOKING_CONFIRMED_TEXT'] = 'La ta prenotazione &egrave; confermata. Prenotazione n. ';
$lang['NRS_INVOICE_SENT_TO_YOUR_EMAIL_ADDRESS_TEXT'] = 'Ricevuta inviata al tuo indirizzo e-mail';

//display-booking-failure.php
$lang['NRS_BOOKING_FAILURE_TEXT'] = 'Prenotazione fallita';
$lang['NRS_BOOKING_FAILURE_SEARCH_ALL_ITEMS_TEXT'] = 'Cerca tutti i veicoli';

// display-item-price-table.php
$lang['NRS_DAY_PRICE_TEXT'] = 'Prezzo al giorno';
$lang['NRS_HOUR_PRICE_TEXT'] = 'Prezzo per ora';
$lang['NRS_NO_ITEMS_IN_THIS_CATEGORY_TEXT'] = 'Non abbiamo veicoli in questa categoria';
$lang['NRS_PRICE_FOR_DAY_FROM_TEXT'] = 'Prezzo per un giorno a partire da';
$lang['NRS_PRICE_FOR_HOUR_FROM_TEXT'] = 'Prezzo per un ora a partire da';
$lang['NRS_PRICE_WITH_APPLIED_TEXT'] = 'con applicato';
$lang['NRS_WITH_APPLIED_DISCOUNT_TEXT'] = 'sconto';

// class.ItemsAvailability.php
$lang['NRS_MONTH_DAY_TEXT'] = 'Giorno';
$lang['NRS_MONTH_DAYS_TEXT'] = 'Days';
$lang['NRS_ITEMS_AVAILABILITY_FOR_TEXT'] = 'Disponibilit&agrave; veicoli';
$lang['NRS_ITEMS_AVAILABILITY_IN_NEXT_30_DAYS_TEXT'] = 'Cars Availability in Next 30 Days';
$lang['NRS_ITEMS_PARTIAL_AVAILABILITY_FOR_TEXT'] = 'Partial Cars Availability For';
$lang['NRS_ITEMS_AVAILABILITY_THIS_MONTH_TEXT'] = 'Disponibilit&agrave; veicoli questo mese';
$lang['NRS_ITEMS_AVAILABILITY_NEXT_MONTH_TEXT'] = 'Disponibilit&agrave; veicoli prossimo mese';
$lang['NRS_ITEM_ID_TEXT'] = 'ID:';
$lang['NRS_TOTAL_ITEMS_TEXT'] = 'Veicoli totali:';

// class.ExtrasAvailability.php
$lang['NRS_EXTRAS_AVAILABILITY_FOR_TEXT'] = 'Disponibilit&agrave; Extra per';
$lang['NRS_EXTRAS_AVAILABILITY_IN_NEXT_30_DAYS_TEXT'] = 'Extras Availability in Next 30 Days';
$lang['NRS_EXTRAS_PARTIAL_AVAILABILITY_FOR_TEXT'] = 'Partial Extras Availability For';
$lang['NRS_EXTRAS_AVAILABILITY_THIS_MONTH_TEXT'] = 'Disponibilit&agrave; Extra questo mese';
$lang['NRS_EXTRAS_AVAILABILITY_NEXT_MONTH_TEXT'] = 'Disponibilit&agrave; Extra prossimo mese';
$lang['NRS_EXTRA_ID_TEXT'] = 'ID';
$lang['NRS_TOTAL_EXTRAS_TEXT'] = 'Extra totali:';

// class.ItemsController.php
$lang['NRS_SHOW_ITEM_PAGE_TEXT'] = 'Visualizza la descrizione veicolo';
$lang['NRS_PARTNER_TEXT'] = 'Partner';
$lang['NRS_BODY_TYPE_TEXT'] = 'Classe';
$lang['NRS_TRANSMISSION_TYPE_TEXT'] = 'Cambio';
$lang['NRS_FUEL_TYPE_TEXT'] = 'Benzina';
$lang['NRS_ITEM_FUEL_CONSUMPTION_TEXT'] = 'Consumo';
$lang['NRS_ITEM_PASSENGERS_TEXT'] = 'Capacit&agrave; passeggeri';
$lang['NRS_ITEM_PRICE_FROM_TEXT'] = 'Prezzo da';
$lang['NRS_INQUIRE_TEXT'] = 'Call';
$lang['NRS_GET_A_QUOTE_TEXT'] = 'Get a quote';
$lang['NRS_ITEM_FEATURES_TEXT'] = 'Caratteristiche';
$lang['NRS_BOOK_ITEM_TEXT'] = 'Prenota';

// class.LocationsController.php
$lang['NRS_LOCATIONS_BUSINESS_HOURS_TEXT'] = 'Business Hours';
$lang['NRS_LOCATION_FEES_TEXT'] = 'Spese per il luogo';
$lang['NRS_EARLY_PICKUP_TEXT'] = 'Early Pick-Up';
$lang['NRS_LATE_PICKUP_TEXT'] = 'Late Pick-Up';
$lang['NRS_EARLY_RETURN_TEXT'] = 'Early Return';
$lang['NRS_LATE_RETURN_TEXT'] = 'Late Return';
$lang['NRS_EARLY_PICKUP_FEE_TEXT'] = 'Early pick-up fee';
$lang['NRS_LATE_RETURN_FEE_TEXT'] = 'Late return fee';
$lang['NRS_VIEW_LOCATION_TEXT'] = 'View Location';

// class.SingleItemController.php
$lang['NRS_ITEM_ENGINE_CAPACITY_TEXT'] = 'Cilindrata';
$lang['NRS_ITEM_LUGGAGE_TEXT'] = 'Bagagli Max';
$lang['NRS_ITEM_DOORS_TEXT'] = 'Sportelli';
$lang['NRS_ITEM_DRIVER_AGE_TEXT'] = 'Et&agrave; minima per la guida';
$lang['NRS_ADDITIONAL_INFORMATION_TEXT'] = 'Informazioni aggiuntive';

// class.SingleLocationController.php
$lang['NRS_CONTACTS_TEXT'] = 'Contacts';
$lang['NRS_CONTACT_DETAILS_TEXT'] = 'Contact Details';
$lang['NRS_BUSINESS_HOURS_FEES_TEXT'] = 'Business Hours Fees';
$lang['NRS_AFTERHOURS_FEES_TEXT'] = 'After Hours Fees';

// template.BookingCancelled.php
$lang['NRS_CANCELLED_SUCCESSFULLY_TEXT'] = 'Cancellata con successo';
$lang['NRS_NOT_CANCELLED_TEXT'] = 'La prenotazione non &egrave; stata cancellata';

// template.Step8EditBooking.php
$lang['NRS_EDIT_TEXT'] = 'Modifica';
$lang['NRS_BOOKING2_TEXT'] = 'prenotazione';
$lang['NRS_EDIT_BOOKING_BUTTON_TEXT'] = 'Modifica prenotazione';
$lang['NRS_EDIT_BOOKING_PLEASE_ENTER_BOOKING_NUMBER_TEXT'] = 'Per favore inserisci il numero prenotazione!';

// Admin System Errors
// Unfortunately, they are untranslatable
$lang['NRS_ERROR_IN_METHOD_TEXT'] = 'Error in %s method: ';

// Exceptions
$lang['NRS_ERROR_CANNOT_BIND_TEMPLATE_VARIABLE_TEXT'] = 'Cannot bind variable named &#39;templateFile&#39;.';
$lang['NRS_ERROR_TEMPLATE_NOT_EXIST_TEXT'] = 'Template file %s does not exist.';

// Errors
$lang['NRS_ERROR_EXTENSION_NAME_TEXT'] = 'Car Rental System';
$lang['NRS_ERROR_REQUIRED_FIELD_TEXT'] = 'Campo richiesto';
$lang['NRS_ERROR_IS_EMPTY_TEXT'] = '&egrave; vuoto';
$lang['NRS_ERROR_SLIDER_CANT_BE_DISPLAYED_TEXT'] = 'Slider can&#39;t be displayed';
$lang['NRS_ERROR_CUSTOMER_DETAILS_NOT_FOUND_TEXT'] = 'Dettagli non trovati. Per favore iscriviti';
$lang['NRS_ERROR_CUSTOMER_DETAILS_NO_ERROR_TEXT'] = 'Nessun errore';
$lang['NRS_ERROR_EXCEEDED_CUSTOMER_LOOKUP_ATTEMPTS_TEXT'] = 'You have exceeded customer detail lookup attempts. Please enter your details manually in the form bellow.';
$lang['NRS_ERROR_CUSTOMER_DETAILS_UNKNOWN_ERROR_TEXT'] = 'Errore sconosciuto';
$lang['NRS_ERROR_BOOKING_DOES_NOT_EXIST_TEXT'] = 'non esiste';
$lang['NRS_ERROR_PLEASE_SELECT_AT_LEAST_ONE_ITEM_TEXT'] = 'Per favore seleziona almeno una vettura';
$lang['NRS_ERROR_ITEM_NOT_AVAILABLE_TEXT'] = 'Questo veicolo non &egrave; disponibile.';
$lang['NRS_ERROR_NO_ITEM_AVAILABLE_PLEASE_TRY_DIFFERENT_DATE_TEXT'] = 'Nessuna vettura disponibile. Per favore prova una data diversa o modifica i criteri di ricerca.';
$lang['NRS_ERROR_SEARCH_ENGINE_DISABLED_TEXT'] = 'Prenotazione online al momento non disponibile. Per favore prova più tardi.';
$lang['NRS_ERROR_OUT_BEFORE_IN_TEXT'] = 'Hai inserito in criterio di ricerca non corretto. Per favore riprova con criteri di ricerca validi.';
$lang['NRS_ERROR_MINIMUM_NUMBER_OF_NIGHT_SHOULD_NOT_BE_LESS_THAN_TEXT'] = 'Il numero minimo di notti non deve essere inferiore a';
$lang['NRS_ERROR_PLEASE_MODIFY_YOUR_SEARCH_CRITERIA_TEXT'] = 'Si prega di modificare i criteri di ricerca.';
$lang['NRS_ERROR_PICKUP_IS_NOT_POSSIBLE_ON_TEXT'] = 'Prenotazione non possibile per la data di arrivo:';
$lang['NRS_ERROR_PLEASE_MODIFY_YOUR_PICKUP_TIME_BY_WEBSITE_TIME_TEXT'] = 'Si prega di modificare i criteri di ricerca in base alla prenotazione, posizione, data corrente e ora.';
$lang['NRS_ERROR_CURRENT_DATE_TIME_TEXT'] = 'Luogo di prenotazione corrente, data e ora:';
$lang['NRS_ERROR_EARLIEST_POSSIBLE_PICKUP_DATE_TIME_TEXT'] = 'La prima data utile per il ritiro &egrave;';
$lang['NRS_ERROR_OR_NEXT_BUSINESS_HOURS_OF_PICKUP_LOCATION_TEXT'] = 'or the first time after it, when selected pickup location is open';
$lang['NRS_ERROR_PICKUP_DATE_CANT_BE_LESS_THAN_RETURN_DATE_TEXT'] = 'La data di ritiro e l&rsquo;ora non possono essere inferiori alla data e ora per la restituzione. Per favore selezione una data e ora corretta per il ritiro e la restituzione';
$lang['NRS_ERROR_PICKUP_LOCATION_IS_CLOSED_AT_THIS_DATE_TEXT'] = 'Il ritiro presso questa filiale %s a questo indirizzo %s &egrave; chiuso in questo Data (%s).';
$lang['NRS_ERROR_PICKUP_LOCATION_IS_CLOSED_AT_THIS_TIME_TEXT'] = 'Il ritiro presso questa filiale %s a questo indirizzo %s &egrave; chiuso in questo momento (%s).';
$lang['NRS_ERROR_RETURN_LOCATION_IS_CLOSED_AT_THIS_DATE_TEXT'] = 'La restituzione presso questa filiale %s a questo indirizzo %s &egrave; chiusa in questo data (%s).';
$lang['NRS_ERROR_RETURN_LOCATION_IS_CLOSED_AT_THIS_TIME_TEXT'] = 'La restituzione presso questa filiale %s a questo indirizzo %s &egrave; chiusa in questo momento (%s).';
$lang['NRS_ERROR_AFTERHOURS_PICKUP_LOCATION_IS_CLOSED_AT_THIS_TIME_TEXT'] = 'Il ritiro presso questa filiale %s all&rsquo; indirizzo %s ma questa filiale &egrave; chiusa in questo momento.';
$lang['NRS_ERROR_AFTERHOURS_RETURN_LOCATION_IS_CLOSED_AT_THIS_TIME_TEXT'] = 'La restituzione presso questa filiale %s all&rsquo; indirizzo %s ma questa filiale &egrave; chiusa in questo momento.';
$lang['NRS_ERROR_LOCATION_OPEN_HOURS_ARE_TEXT'] = 'Gli orari di apertura di questa filiale los %s, %s sono %s.';
$lang['NRS_ERROR_LOCATION_WEEKLY_OPEN_HOURS_ARE_TEXT'] = 'This location open hours during the week are:';
$lang['NRS_ERROR_AFTERHOURS_PICKUP_LOCATION_OPEN_HOURS_ARE_TEXT'] = 'Gli orari per il ritiro fuori orario di questa filiale sono %s.';
$lang['NRS_ERROR_AFTERHOURS_RETURN_LOCATION_OPEN_HOURS_ARE_TEXT'] = 'Gli orari per il ritirola restituzione fuori orario di questa filiale sono %s.';
$lang['NRS_ERROR_AFTERHOURS_PICKUP_IS_NOT_ALLOWED_AT_LOCATION_TEXT'] = 'Il ritiro fuori orario non &egrave; possibile in questo luogo.';
$lang['NRS_ERROR_AFTERHOURS_RETURN_IS_NOT_ALLOWED_AT_LOCATION_TEXT'] = 'La restituzione fuori orario non &egrave; possibile in questo luogo.';
$lang['NRS_ERROR_MAXIMUM_BOOKING_LENGTH_CANT_BE_MORE_THAN_TEXT'] = 'La durata massima della prenotazione non può essere più lunga di (in giorni)';
$lang['NRS_ERROR_INVALID_BOOKING_CODE_TEXT'] = 'Codice prenotazione errato oppure questa prenotazione non esiste.';
$lang['NRS_ERROR_INVALID_SECURITY_CODE_TEXT'] = 'Security code is not valid.';
$lang['NRS_ERROR_DRIVER_AGE_VIOLATION_FOR_ITEM_TEXT'] = 'Based on your birthdate, your age does not match the minimum driver age requirement for %s.';
$lang['NRS_ERROR_DRIVER_AGE_VIOLATION_TEXT'] = 'Based on your birthdate, your age does not match the minimum driver age requirement for one of selected cars.';
$lang['NRS_ERROR_DEPARTED_TEXT'] = 'Prenotazione No. %s gi&agrave; contrassegnata come partito, e non &egrave; disponibile per ulteriori modifiche.';
$lang['NRS_ERROR_CANCELLED_TEXT'] = 'Prenotazione No. %s &egrave; stata cancellata.';
$lang['NRS_ERROR_REFUNDED_TEXT'] = 'Prenotazione No. %s &egrave; stata gi&agrave; rimborsata e non è pi&ugrave; disponibile.';
$lang['NRS_ERROR_SYSTEM_IS_UNABLE_TO_SEND_EMAIL_TEXT'] = 'Errore: Il sistema non invia Email di conferma. Le impostazioni non sono impostate correttamente, oppure la mail del cliente non &egrave; corretta.';
$lang['NRS_ERROR_PAYMENT_METHOD_IS_NOT_YET_IMPLEMENTED_TEXT'] = 'Errore: Stai provando a pagara con un metodo non disponibile per il sistema.';
$lang['NRS_ERROR_OTHER_BOOKING_ERROR_TEXT'] = 'Altro errore prenotazione. Se continui a vedere questo messaggio, contattare l&rsquo;amministratore del sito.';

// Admin Discount controller
$lang['NRS_ADMIN_DISCOUNT_ITEM_BOOKING_IN_ADVANCE_TEXT'] = 'Add/Edit Car Discount for Booking in Advance';
$lang['NRS_ADMIN_DISCOUNT_ITEM_BOOKING_DURATION_TEXT'] = 'Add/Edit Car Discount for Booking Duration';
$lang['NRS_ADMIN_DISCOUNT_EXTRA_BOOKING_IN_ADVANCE_TEXT'] = 'Add/Edit Extra Discount for Booking in Advance';
$lang['NRS_ADMIN_DISCOUNT_EXTRA_BOOKING_DURATION_TEXT'] = 'Add/Edit Extra Discount for Booking Duration';
$lang['NRS_ADMIN_DISCOUNT_DURATION_BEFORE_TEXT'] = 'Duration Before:';
$lang['NRS_ADMIN_DISCOUNT_DURATION_UNTIL_TEXT'] = 'Duration Until:';
$lang['NRS_ADMIN_DISCOUNT_DURATION_FROM_TEXT'] = 'Duration From:';
$lang['NRS_ADMIN_DISCOUNT_DURATION_TILL_TEXT'] = 'Duration Till:';

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
$lang['NRS_EMAIL_DEMO_LOCATION_NAME_TEXT'] = 'Demo Location';
$lang['NRS_EMAIL_DEMO_LOCATION_PHONE_TEXT'] = '+1 600 10000';
$lang['NRS_EMAIL_DEMO_LOCATION_EMAIL_TEXT'] = 'info@location.com';
$lang['NRS_EMAIL_SUBJECT_EXISTS_ERROR_TEXT'] = 'Error: Other email already exist with this subject!';
$lang['NRS_EMAIL_UPDATE_ERROR_TEXT'] = 'Error: MySQL update error for existing email!';
$lang['NRS_EMAIL_UPDATED_TEXT'] = 'Completed: Email has been updated successfully!';
$lang['NRS_EMAIL_REGISTERED_TEXT'] = 'Email subject and body registered for translation.';
$lang['NRS_EMAIL_SENDING_ERROR_TEXT'] = 'Failed: System was unable to send the email to %s!';
$lang['NRS_EMAIL_SENT_TEXT'] = 'Completed: Email was sent successfully to %s!';

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
$lang['NRS_INSTALL_DEFAULT_COMPANY_NAME_TEXT'] = 'Car Rental Company';
$lang['NRS_INSTALL_DEFAULT_COMPANY_STREET_ADDRESS_TEXT'] = '625 2nd Street';
$lang['NRS_INSTALL_DEFAULT_COMPANY_CITY_TEXT'] = 'San Francisco';
$lang['NRS_INSTALL_DEFAULT_COMPANY_STATE_TEXT'] = 'CA';
$lang['NRS_INSTALL_DEFAULT_COMPANY_ZIP_CODE_TEXT'] = '94107';
$lang['NRS_INSTALL_DEFAULT_COMPANY_COUNTRY_TEXT'] = '';
$lang['NRS_INSTALL_DEFAULT_COMPANY_PHONE_TEXT'] = '(450) 600 4000';
$lang['NRS_INSTALL_DEFAULT_COMPANY_EMAIL_TEXT'] = 'info@yourdomain.com';
$lang['NRS_INSTALL_DEFAULT_PAGE_URL_SLUG_TEXT'] = 'car-rental'; // Latin letters only
$lang['NRS_INSTALL_DEFAULT_ITEM_URL_SLUG_TEXT'] = 'auto'; // Latin letters only
$lang['NRS_INSTALL_DEFAULT_LOCATION_URL_SLUG_TEXT'] = 'car-location'; // Latin letters only
$lang['NRS_INSTALL_DEFAULT_SEARCH_PAGE_URL_SLUG_TEXT'] = 'search'; // Latin letters only
$lang['NRS_INSTALL_DEFAULT_CANCELLED_PAYMENT_PAGE_TITLE_TEXT'] = 'Payment cancelled';
$lang['NRS_INSTALL_DEFAULT_CANCELLED_PAYMENT_PAGE_CONTENT_TEXT'] = 'Payment was cancelled. Your reservation were not confirmed.';
$lang['NRS_INSTALL_DEFAULT_CONFIRMATION_PAGE_TITLE_TEXT'] = 'Reservation confirmed';
$lang['NRS_INSTALL_DEFAULT_CONFIRMATION_PAGE_CONTENT_TEXT'] = 'Thank you. We received your payment. Your reservation is now confirmed.';
$lang['NRS_INSTALL_DEFAULT_TERMS_AND_CONDITIONS_PAGE_TITLE_TEXT'] = 'Termini di utilizzo per il noleggio auto';
$lang['NRS_INSTALL_DEFAULT_TERMS_AND_CONDITIONS_PAGE_CONTENT_TEXT'] = 'È necessario seguire le linee guida e i termini per il noleggio.';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAYPAL_TEXT'] = 'Online - PayPal';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAYPAL_DESCRIPTION_TEXT'] = 'Secure Instant Payment';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_STRIPE_TEXT'] = 'Credit Card (via Stripe.com)';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_BANK_TEXT'] = 'Bonifico bancario';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_BANK_DETAILS_TEXT'] = 'I tuoi dettagli bancari';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAY_OVER_THE_PHONE_TEXT'] = 'Paga via telefono';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAY_ON_ARRIVAL_TEXT'] = 'Paga al ritiro';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAY_ON_ARRIVAL_DETAILS_TEXT'] = 'Carta di credito richiesta';
$lang['NRS_INSTALL_DEFAULT_DEAR_TEXT'] = 'Caro';
$lang['NRS_INSTALL_DEFAULT_REGARDS_TEXT'] = 'Saluti';
$lang['NRS_INSTALL_DEFAULT_EMAIL_TITLE_BOOKING_DETAILS_TEXT'] = 'Reservation details - no. [BOOKING_CODE]';
$lang['NRS_INSTALL_DEFAULT_EMAIL_TITLE_BOOKING_CONFIRMED_TEXT'] = 'Reservation no. [BOOKING_CODE] - confirmed';
$lang['NRS_INSTALL_DEFAULT_EMAIL_TITLE_BOOKING_CANCELLED_TEXT'] = 'Reservation no. [BOOKING_CODE] - cancelled';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_TITLE_BOOKING_DETAILS_TEXT'] = 'Notification: new reservation - [BOOKING_CODE]';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_TITLE_BOOKING_CONFIRMED_TEXT'] = 'Notification: reservation paid - [BOOKING_CODE]';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_TITLE_BOOKING_CANCELLED_TEXT'] = 'Notification: reservation cancelled - [BOOKING_CODE]';
$lang['NRS_INSTALL_DEFAULT_EMAIL_BODY_BOOKING_RECEIVED_TEXT'] = 'Your reservation details were received.';
$lang['NRS_INSTALL_DEFAULT_EMAIL_BODY_BOOKING_DETAILS_TEXT'] = 'Your reservation details:';
$lang['NRS_INSTALL_DEFAULT_EMAIL_BODY_PAYMENT_RECEIVED_TEXT'] = 'We received your payment. Your reservation is now confirmed.';
$lang['NRS_INSTALL_DEFAULT_EMAIL_BODY_BOOKING_CANCELLED_TEXT'] = 'Your reservation no. [BOOKING_CODE] were cancelled.';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_BOOKING_RECEIVED_TEXT'] = 'New reservation no. [BOOKING_CODE] received from [CUSTOMER_NAME].';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_BOOKING_DETAILS_TEXT'] = 'Reservation details:';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_BOOKING_PAID_TEXT'] = 'Reservation no. [BOOKING_CODE] was recently paid by [CUSTOMER_NAME].';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_BOOKING_CANCELLED_TEXT'] = 'Reservation no. [BOOKING_CODE] for [CUSTOMER_NAME] was recently cancelled.';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_CANCELLED_BOOKING_DETAILS_TEXT'] = 'Details of reservation, which were cancelled:';

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