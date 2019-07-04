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
$lang['NRS_ADMIN_VIEW_UNPAID_BOOKINGS_TEXT'] = 'Zobrazit nezaplacené rezervace';
$lang['NRS_ADMIN_NO_BOOKINGS_YET_TEXT'] = 'Žádné rezervace';
$lang['NRS_ADMIN_BOOKING_DETAILS_TEXT'] = 'Detaily rezervace';
$lang['NRS_ADMIN_CUSTOMER_DETAILS_FROM_DB_TEXT'] = 'Detaily zákazníka (Poslední verze z databáze)';
$lang['NRS_ADMIN_BOOKING_STATUS_TEXT'] = 'Status rezervace';
$lang['NRS_ADMIN_BOOKING_STATUS_UPCOMING_TEXT'] = 'Příchozí';
$lang['NRS_ADMIN_BOOKING_STATUS_DEPARTED_TEXT'] = 'Odchozí';
$lang['NRS_ADMIN_BOOKING_STATUS_COMPLETED_EARLY_TEXT'] = 'Nedávno dokončené';
$lang['NRS_ADMIN_BOOKING_STATUS_COMPLETED_TEXT'] = 'Dokončené';
$lang['NRS_ADMIN_BOOKING_STATUS_ACTIVE_TEXT'] = 'Aktivní';
$lang['NRS_ADMIN_BOOKING_STATUS_CANCELLED_TEXT'] = 'Zrušené';
$lang['NRS_ADMIN_BOOKING_STATUS_PAID_TEXT'] = 'Zaplacené';
$lang['NRS_ADMIN_BOOKING_STATUS_UNPAID_TEXT'] = 'Nezaplacené';
$lang['NRS_ADMIN_BOOKING_STATUS_REFUNDED_TEXT'] = 'Vrácené';
$lang['NRS_ADMIN_PRINT_INVOICE_TEXT'] = 'Tisk faktury';
$lang['NRS_ADMIN_BACK_TO_CUSTOMER_BOOKING_LIST_TEXT'] = 'Zpět k zákazníkovi&#39;s Seznam rezervací';
$lang['NRS_ADMIN_CUSTOMERS_BY_LAST_VISIT_TEXT'] = 'Zákazníci dle poslední návštěvy';
$lang['NRS_ADMIN_CUSTOMERS_BY_REGISTRATION_TEXT'] = 'Zákazníci dle rezervace';
$lang['NRS_ADMIN_BOOKINGS_PERIOD_FROM_TO_TEXT'] = 'Délka rezervace: %s - %s';
$lang['NRS_ADMIN_PICKUPS_PERIOD_FROM_TO_TEXT'] = 'Vyzvednutí - období: %s - %s';
$lang['NRS_ADMIN_RETURNS_PERIOD_FROM_TO_TEXT'] = 'Vrácení období: %s - %s';
$lang['NRS_ADMIN_UPCOMING_TEXT'] = 'Nadcházející';
$lang['NRS_ADMIN_PAST_TEXT'] = 'Minulé';
$lang['NRS_ADMIN_CUSTOMER_BOOKINGS_TEXT'] = 'Rezervace zákazníků';
$lang['NRS_ADMIN_BOOKINGS_BY_TEXT'] = 'Rezervace dle %s';
$lang['NRS_ADMIN_ALL_BOOKINGS_TEXT'] = 'Všechny rezervace';
$lang['NRS_ADMIN_ALL_PICKUPS_TEXT'] = 'Všechny vyzvednutí';
$lang['NRS_ADMIN_ALL_RETURNS_TEXT'] = 'Všechny vrácení';
$lang['NRS_ADMIN_MAX_ITEM_UNITS_PER_BOOKING_TEXT'] = 'Maximum vozidel pro rezervaci';
$lang['NRS_ADMIN_TOTAL_ITEM_UNITS_IN_STOCK_TEXT'] = 'Všechna vozidla v garáži';
$lang['NRS_ADMIN_MAX_EXTRA_UNITS_PER_BOOKING_TEXT'] = 'Maximum doplňků na rezervaci';
$lang['NRS_ADMIN_TOTAL_EXTRA_UNITS_IN_STOCK_TEXT'] = 'Všechny doplňky na skladě';
$lang['NRS_ADMIN_ITEM_PRICES_TEXT'] = 'Ceny aut';
$lang['NRS_ADMIN_ITEM_DEPOSITS_TEXT'] = 'Ceny kauce';
$lang['NRS_ADMIN_EXTRA_PRICES_TEXT'] = 'Ceny doplňků';
$lang['NRS_ADMIN_EXTRA_DEPOSITS_TEXT'] = 'Kauce doplňků';
$lang['NRS_ADMIN_PICKUP_FEES_TEXT'] = 'Poplatek za vyzvedutí';
$lang['NRS_ADMIN_DISTANCE_FEES_TEXT'] = 'Poplatek za převoz vozidla';
$lang['NRS_ADMIN_RETURN_FEES_TEXT'] = 'Poplatek za vrácení';
$lang['NRS_ADMIN_REGULAR_PRICE_TEXT'] = 'Cena půjčení';
$lang['NRS_ADMIN_PRICE_TYPE_TEXT'] = 'Typ ceny';
$lang['NRS_ADMIN_ON_THE_LEFT_TEXT'] = 'Na levé straně';
$lang['NRS_ADMIN_ON_THE_RIGHT_TEXT'] = 'Na pravé straně';
$lang['NRS_ADMIN_LOAD_FROM_OTHER_PLACE_TEXT'] = 'Načtení z jiného místa';
$lang['NRS_ADMIN_LOAD_FROM_PLUGIN_TEXT'] = 'Načtení z pluginu';
$lang['NRS_ADMIN_EMAIL_TEXT'] = 'E-mail';
$lang['NRS_ADMIN_PUBLIC_TEXT'] = 'Veřejný';
$lang['NRS_ADMIN_PRIVATE_TEXT'] = 'Skrytý';
$lang['NRS_ADMIN_CALENDAR_NO_CALENDARS_FOUND_TEXT'] = 'Nenalezen žádný kalendář pro vybraný rozsah data';
$lang['NRS_ADMIN_CHOOSE_PAGE_TEXT'] = ' - Vyberte stránku - ';
$lang['NRS_ADMIN_SELECT_EMAIL_TYPE_TEXT'] = '--- Vyberte typ e-mailu ---';
$lang['NRS_ADMIN_TOTAL_REQUESTS_LEFT_TEXT'] = 'Celkový počet obdržených žádostí';
$lang['NRS_ADMIN_FAILED_REQUESTS_LEFT_TEXT'] = 'Počet chybných žádostí';
$lang['NRS_ADMIN_EMAIL_ATTEMPTS_LEFT_TEXT'] = 'Počet pokusů přes e-mail';

// Admin Menu
$lang['NRS_ADMIN_MENU_EXTENSION_PAGE_TITLE_TEXT'] = 'Systém autopůjčovny';
$lang['NRS_ADMIN_MENU_EXTENSION_LABEL_TEXT'] = 'Autopůjčovna';
$lang['NRS_ADMIN_MENU_SYSTEM_UPDATE_TEXT'] = 'Aktualizace systému';
$lang['NRS_ADMIN_MENU_NETWORK_UPDATE_TEXT'] = 'Aktualizace sítě';
// Admin Menu - Benefit Manager
$lang['NRS_ADMIN_MENU_BENEFIT_MANAGER_TEXT'] = 'Manažer benefitů';
$lang['NRS_ADMIN_MENU_ADD_EDIT_BENEFIT_TEXT'] = 'Přidat / Upravit Benefity';
// Admin Menu - Item Manager
$lang['NRS_ADMIN_MENU_ITEM_MANAGER_TEXT'] = 'Auto - manažer';
$lang['NRS_ADMIN_MENU_ADD_EDIT_ITEM_TEXT'] = 'Přidat / Upravit Auta';
$lang['NRS_ADMIN_MENU_ADD_EDIT_MANUFACTURER_TEXT'] = 'Přidat / Upravit Výrobce';
$lang['NRS_ADMIN_MENU_ADD_EDIT_BODY_TYPE_TEXT'] = 'Přidat / Upravit Karosérii';
$lang['NRS_ADMIN_MENU_ADD_EDIT_FUEL_TYPE_TEXT'] = 'Přidat / Upravit Typ paliva';
$lang['NRS_ADMIN_MENU_ADD_EDIT_TRANSMISSION_TYPE_TEXT'] = 'Přidat / Upravit Typ převodovky';
$lang['NRS_ADMIN_MENU_ADD_EDIT_FEATURE_TEXT'] = 'Přidat / Upravit Vlastnosti';
$lang['NRS_ADMIN_MENU_ADD_EDIT_ITEM_OPTION_TEXT'] = 'Přidat / Upravit Možnosti vozů';
$lang['NRS_ADMIN_MENU_BLOCK_ITEM_TEXT'] = 'Blokovat auto';
// Admin Menu - Item Prices
$lang['NRS_ADMIN_MENU_ITEM_PRICES_TEXT'] = 'Ceny auta';
$lang['NRS_ADMIN_MENU_ADD_EDIT_PRICE_GROUP_TEXT'] = 'Přidat / Upravit Cenu skupiny';
$lang['NRS_ADMIN_MENU_ADD_EDIT_PRICE_PLAN_TEXT'] = 'Přidat / Upravit Cenový plán vozu';
$lang['NRS_ADMIN_MENU_ADD_EDIT_ITEM_DISCOUNT_TEXT'] = 'Přidat / Upravit Slevu pro auto';
// Admin Menu - Extras Manager
$lang['NRS_ADMIN_MENU_EXTRAS_MANAGER_TEXT'] = 'Doplňky - manažer';
$lang['NRS_ADMIN_MENU_ADD_EDIT_EXTRA_TEXT'] = 'Přidat / Upravit Doplňky';
$lang['NRS_ADMIN_MENU_ADD_EDIT_EXTRA_OPTION_TEXT'] = 'Přidat / Upravit Možnosti doplňků';
$lang['NRS_ADMIN_MENU_ADD_EDIT_EXTRA_DISCOUNT_TEXT'] = 'Přidat / Upravit Slevu doplňků';
$lang['NRS_ADMIN_MENU_BLOCK_EXTRA_TEXT'] = 'Blokovat doplňek';
// Admin Menu - Location Manager
$lang['NRS_ADMIN_MENU_LOCATION_MANAGER_TEXT'] = 'Umístění - manažer';
$lang['NRS_ADMIN_MENU_ADD_EDIT_LOCATION_TEXT'] = 'Přidat / Upravit Umístění';
$lang['NRS_ADMIN_MENU_ADD_EDIT_DISTANCE_TEXT'] = 'Přidat / Upravit Vzdálenost';
// Admin Menu - Reservation Manager
$lang['NRS_ADMIN_MENU_BOOKING_MANAGER_TEXT'] = 'Rezervace - manažer';
$lang['NRS_ADMIN_MENU_BOOKING_SEARCH_RESULTS_TEXT'] = 'Rezervace vyhledávání výsledků';
$lang['NRS_ADMIN_MENU_ITEM_CALENDAR_SEARCH_RESULTS_TEXT'] = 'Kalendář vozů - vyhledávání výsledků';
$lang['NRS_ADMIN_MENU_EXTRAS_CALENDAR_SEARCH_TEXT'] = 'Kalendář doplňků - vyhledávání výsledků';
$lang['NRS_ADMIN_MENU_CUSTOMER_SEARCH_RESULTS_TEXT'] = 'Zákazníci - vyhledávání výsledků';
$lang['NRS_ADMIN_MENU_ADD_EDIT_CUSTOMER_TEXT'] = 'Přidat / Upravit Zákazníka';
$lang['NRS_ADMIN_MENU_ADD_EDIT_BOOKING_TEXT'] = 'Přidat / Upravit Rezervaci';
$lang['NRS_ADMIN_MENU_VIEW_DETAILS_TEXT'] = 'Zobrazit detaily rezervace';
$lang['NRS_ADMIN_MENU_PRINT_INVOICE_TEXT'] = 'Print Invoice';
// Admin Menu - Payments & Taxes
$lang['NRS_ADMIN_MENU_PAYMENTS_AND_TAXES_TEXT'] = 'Platby &amp; Daně';
$lang['NRS_ADMIN_MENU_ADD_EDIT_PAYMENT_METHOD_TEXT'] = 'Přidat / Upravit Způsob platby';
$lang['NRS_ADMIN_MENU_ADD_EDIT_PREPAYMENT_TEXT'] = 'Přidat / Upravit Předplacení';
$lang['NRS_ADMIN_MENU_ADD_EDIT_TAX_TEXT'] = 'Přidat / Upravit Daň';
// Admin Menu - Settings
$lang['NRS_ADMIN_MENU_SINGLE_MANAGER_TEXT'] = 'Nastavení';
$lang['NRS_ADMIN_MENU_ADD_EDIT_GLOBAL_SETTINGS_TEXT'] = 'Přidat / Upravit Hlavní nastavení';
$lang['NRS_ADMIN_MENU_ADD_EDIT_CUSTOMER_SETTINGS_TEXT'] = 'Přidat / Upravit Nastavení zákazníka';
$lang['NRS_ADMIN_MENU_ADD_EDIT_SEARCH_SETTINGS_TEXT'] = 'Přidat / Upravit Nastavení vyhledávání';
$lang['NRS_ADMIN_MENU_ADD_EDIT_PRICE_SETTINGS_TEXT'] = 'Přidat / Upravit Nastavení ceny';
$lang['NRS_ADMIN_MENU_ADD_EDIT_EMAIL_TEXT'] = 'Přidat / Upravit E-mail';
$lang['NRS_ADMIN_MENU_IMPORT_DEMO_TEXT'] = 'Importovat nastavení DEMO ';
$lang['NRS_ADMIN_MENU_CONTENT_PREVIEW_TEXT'] = 'Zobrazení obsahu ';
// Admin Menu - Instructions
$lang['NRS_ADMIN_MENU_INSTRUCTIONS_TEXT'] = 'Instrukce';
// Admin Menu - Network Manager
$lang['NRS_ADMIN_MENU_NETWORK_MANAGER_TEXT'] = 'Síť - manažer';

// Admin Pages Post Type
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_NAME_TEXT'] = 'Autopůjčovna stránka'; // name
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_SINGULAR_NAME_TEXT'] = 'Autopůjčovna stránky'; // singular_name
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_MENU_NAME_TEXT'] = 'Autopůjčovna stránky'; // menu_name
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_PARENT_PAGE_COLON_TEXT'] = 'Základní informace o autu'; // parent_item_colon
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_ALL_PAGES_TEXT'] = 'Informace o všech autech'; // all_items
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_VIEW_PAGE_TEXT'] = 'Zobrazit informace o autech'; // view_item
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_ADD_NEW_PAGE_TEXT'] = 'Přidat nové auto informační stránka'; // add_new_item
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_ADD_NEW_TEXT'] = 'Přidat novou stránku'; // add_new
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_EDIT_PAGE_TEXT'] = 'Upravit informace o autu'; // edit_item
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_UPDATE_PAGE_TEXT'] = 'Aktualizovat stránku o autech'; // update_item
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_SEARCH_PAGES_TEXT'] = 'Vyhledávat informace o autech'; // search_items
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_NOT_FOUND_TEXT'] = 'Nenalezeno'; // not_found
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_NOT_FOUND_IN_TRASH_TEXT'] = 'Nenalezeno v koši'; // not_found_in_trash
$lang['NRS_ADMIN_PAGE_POST_TYPE_DESCRIPTION_TEXT'] = 'Seznam aut - info';

// Admin Item Post Type
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_NAME_TEXT'] = 'Stránka aut'; // name
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_SINGULAR_NAME_TEXT'] = 'Stránky aut'; // singular_name
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_MENU_NAME_TEXT'] = 'Stránky aut'; // menu_name
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_PARENT_ITEM_COLON_TEXT'] = 'Základní auto'; // parent_item_colon
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_ALL_ITEMS_TEXT'] = 'Všechny auta stránky'; // all_items
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_VIEW_ITEM_TEXT'] = 'Zobraz auto stránka'; // view_item
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_ADD_NEW_ITEM_TEXT'] = 'Přidej auto stránka'; // add_new_item
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_ADD_NEW_TEXT'] = 'Přidej novou stránku'; // add_new
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_EDIT_ITEM_TEXT'] = 'Uprav auto stránka'; // edit_item
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_UPDATE_ITEM_TEXT'] = 'Aktualizuj auto stránka'; // update_item
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_SEARCH_ITEMS_TEXT'] = 'Vyhledávání aut stránka'; // search_items
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_NOT_FOUND_TEXT'] = 'Nenalezeno'; // not_found
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_NOT_FOUND_IN_TRASH_TEXT'] = 'Nenalezeno v koši'; // not_found_in_trash
$lang['NRS_ADMIN_ITEM_POST_TYPE_DESCRIPTION_TEXT'] = 'Seznam aut - info';

// Admin Location Post Type
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_NAME_TEXT'] = 'Umístění vozu'; // name
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_SINGULAR_NAME_TEXT'] = 'Umístění vozu'; // singular_name
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_MENU_NAME_TEXT'] = 'Umístění vozu'; // menu_name
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_PARENT_LOCATION_COLON_TEXT'] = 'Základní umístění vozu'; // parent_item_colon
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_ALL_LOCATIONS_TEXT'] = 'Všechna umístění vozů'; // all_items
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_VIEW_LOCATION_TEXT'] = 'Zobrazit umístění vozů stránka'; // view_item
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_ADD_NEW_LOCATION_TEXT'] = 'Přidat nové umístění vozu'; // add_new_item
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_ADD_NEW_TEXT'] = 'Přidat novou stránku'; // add_new
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_EDIT_LOCATION_TEXT'] = 'Upravit umístění vozu stránku'; // edit_item
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_UPDATE_LOCATION_TEXT'] = 'Aktualizovat umístění vozu stránku'; // update_item
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_SEARCH_LOCATIONS_TEXT'] = 'Vyhledávat umístění vozu stránka'; // search_items
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_NOT_FOUND_TEXT'] = 'Nenalezeno'; // not_found
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_NOT_FOUND_IN_TRASH_TEXT'] = 'Nenalezeno v koši'; // not_found_in_trash
$lang['NRS_ADMIN_LOCATION_POST_TYPE_DESCRIPTION_TEXT'] = 'Seznam umístění vozů stránka';

// Admin Core
$lang['NRS_ADMIN_EDIT_TEXT'] = 'Upravit';
$lang['NRS_ADMIN_DELETE_TEXT'] = 'Smazat';
$lang['NRS_ADMIN_CANCEL_TEXT'] = 'Zrušit';
$lang['NRS_ADMIN_UNBLOCK_TEXT'] = 'Odblokovat';
$lang['NRS_ADMIN_MARK_PAID_TEXT'] = 'Označit jako zaplacené';
$lang['NRS_ADMIN_MARK_COMPLETED_EARLY_TEXT'] = 'Označit jako kompletní brzy';
$lang['NRS_ADMIN_REFUND_TEXT'] = 'Vrátit platbu';
$lang['NRS_ADMIN_SELECT_LOCATION_TEXT'] = '-- Vybrat umístění --';
$lang['NRS_ADMIN_ALL_LOCATIONS_TEXT'] = 'Všechny umístění';
$lang['NRS_ADMIN_AVAILABLE_TEXT'] = 'K dispozici';
$lang['NRS_ADMIN_DISPLAYED_TEXT'] = 'Zobrazeno';
$lang['NRS_ADMIN_VISIBLE_TEXT'] = 'Viditelné';
$lang['NRS_ADMIN_HIDDEN_TEXT'] = 'Skryté';
$lang['NRS_ADMIN_ENABLED_TEXT'] = 'Zapnuté';
$lang['NRS_ADMIN_DISABLED_TEXT'] = 'Vypnuté';
$lang['NRS_ADMIN_ALLOWED_TEXT'] = 'Povolené';
$lang['NRS_ADMIN_FAILED_TEXT'] = 'Chybné';
$lang['NRS_ADMIN_BLOCKED_TEXT'] = 'Blokované';
$lang['NRS_ADMIN_REQUEST_TEXT'] = 'Požadavek';
$lang['NRS_ADMIN_REQUESTS_TEXT'] = 'Požadavky';
$lang['NRS_ADMIN_IP_TEXT'] = 'IP';
$lang['NRS_ADMIN_CHECK_TEXT'] = 'Check';
$lang['NRS_ADMIN_SKIP_TEXT'] = 'Skip';
$lang['NRS_ADMIN_YES_TEXT'] = 'Ano';
$lang['NRS_ADMIN_NO_TEXT'] = 'Ne';
$lang['NRS_ADMIN_DAILY_TEXT'] = 'Denně';
$lang['NRS_ADMIN_HOURLY_TEXT'] = 'Hodinově';
$lang['NRS_ADMIN_PER_BOOKING_TEXT'] = 'Za rezervaci';
$lang['NRS_ADMIN_COMBINED_TEXT'] = 'Kombinované - Denně &amp; Hodinově';
$lang['NRS_ADMIN_NEVER_TEXT'] = 'Nikdy';
$lang['NRS_ADMIN_DROPDOWN_TEXT'] = 'Rozevírací';
$lang['NRS_ADMIN_SLIDER_TEXT'] = 'Slider';
$lang['NRS_ADMIN_SELECT_DEMO_TEXT'] = ' --- Vybrat Živé Demo --- ';
$lang['NRS_ADMIN_WITHOUT_TRANSLATION_TEXT'] = 'Bez překladu';
$lang['NRS_ADMIN_VIEW_PAGE_IN_NEW_WINDOW_TEXT'] = 'Zobrazit %s stránky v novém okně';

// Core
$lang['NRS_IMAGE_ALT_TEXT'] = 'Obrázek';
$lang['NRS_PER_BOOKING_SHORT_TEXT'] = '';
$lang['NRS_PER_DAY_SHORT_TEXT'] = 'd';
$lang['NRS_PER_HOUR_SHORT_TEXT'] = 'hod';
$lang['NRS_PER_BOOKING_TEXT'] = 'rezervace';
$lang['NRS_PER_DAY_TEXT'] = 'Den';
$lang['NRS_PER_HOUR_TEXT'] = 'Hodina';
$lang['NRS_SELECT_DATE_TEXT'] = 'Datum';
$lang['NRS_SELECT_YEAR_TEXT'] = 'Rok';
$lang['NRS_SELECT_MONTH_TEXT'] = 'Měsíc';
$lang['NRS_SELECT_DAY_TEXT'] = 'Den';
$lang['NRS_PRICE_TEXT'] = 'Cena';
$lang['NRS_PERIOD_TEXT'] = 'Doba';
$lang['NRS_DURATION_TEXT'] = 'Délka';
$lang['NRS_MON_TEXT'] = 'Po';
$lang['NRS_TUE_TEXT'] = 'Út';
$lang['NRS_WED_TEXT'] = 'St';
$lang['NRS_THU_TEXT'] = 'Čt';
$lang['NRS_FRI_TEXT'] = 'Pá';
$lang['NRS_SAT_TEXT'] = 'So';
$lang['NRS_SUN_TEXT'] = 'Ne';
$lang['NRS_LUNCH_TEXT'] = 'Oběd';
$lang['NRS_MONDAYS_TEXT'] = 'Pondělky';
$lang['NRS_TUESDAYS_TEXT'] = 'Úterky';
$lang['NRS_WEDNESDAYS_TEXT'] = 'Středy';
$lang['NRS_THURSDAYS_TEXT'] = 'Čtvrtky';
$lang['NRS_FRIDAYS_TEXT'] = 'Pátky';
$lang['NRS_SATURDAYS_TEXT'] = 'Soboty';
$lang['NRS_SUNDAYS_TEXT'] = 'Neděle';
$lang['NRS_LUNCH_TIME_TEXT'] = 'Polední pauza';
$lang['NRS_ALL_YEAR_TEXT'] = 'All Year';
$lang['NRS_ALL_DAY_TEXT'] = '24 HOD';
$lang['NRS_PARTIAL_DAY_TEXT'] = '%s - 12:00';
$lang['NRS_MIDNIGHT_TEXT'] = '00:00';
$lang['NRS_NOON_TEXT'] = '12:00';
$lang['NRS_CLOSED_TEXT'] = 'Zavřeno';
$lang['NRS_OPEN_TEXT'] = 'Otevřeno';
$lang['NRS_TODAY_TEXT'] = 'Today';
$lang['NRS_DATE_TEXT'] = 'Datum';
$lang['NRS_TIME_TEXT'] = 'Čas';
$lang['NRS_DAYS_TEXT'] = 'dny';
$lang['NRS_DAYS2_TEXT'] = 'dny';
$lang['NRS_DAY_TEXT'] = 'den';
$lang['NRS_HOURS_TEXT'] = 'hodiny';
$lang['NRS_HOURS2_TEXT'] = 'hodiny';
$lang['NRS_HOUR_TEXT'] = 'hod';
$lang['NRS_MINUTES_TEXT'] = 'minuty';
$lang['NRS_MINUTES2_TEXT'] = 'minuty';
$lang['NRS_MINUTE_TEXT'] = 'minuta';
$lang['NRS_DAILY_TEXT'] = 'Denně';
$lang['NRS_HOURLY_TEXT'] = 'Hod';
$lang['NRS_ON_ST_TEXT'] = ''; // On January 21st
$lang['NRS_ON_ND_TEXT'] = ''; // On January 21st
$lang['NRS_ON_RD_TEXT'] = ''; // On January 21st
$lang['NRS_ON_TH_TEXT'] = ''; // On January 21st
$lang['NRS_ON_TEXT'] = 'v'; // on
$lang['NRS_THE_ST_TEXT'] = 'První'; // 1st, do the search
$lang['NRS_THE_ND_TEXT'] = 'Druhý'; // 2nd, select an item
$lang['NRS_THE_RD_TEXT'] = 'Třetí'; // 3rd, choose extras
$lang['NRS_THE_TH_TEXT'] = 'Čtvrtý'; // 4th, enter your booking details
$lang['NRS_UNCLASSIFIED_ITEM_TYPE_TEXT'] = 'Ostatní';
$lang['NRS_NO_ITEMS_AVAILABLE_TEXT'] = 'Žádné vozy k dispozici';
$lang['NRS_NO_ITEMS_AVAILABLE_IN_THIS_CLASS_TEXT'] = 'Žádné vozy nejsou k dispozici v této kategorii';
$lang['NRS_NO_EXTRAS_AVAILABLE_TEXT'] = 'Žádné doplňky nejsou k dispozici';
$lang['NRS_NO_MANUFACTURERS_AVAILABLE_TEXT'] = 'No manufacturers available';
$lang['NRS_NO_LOCATIONS_AVAILABLE_TEXT'] = 'No locations available';
$lang['NRS_NO_BENEFITS_AVAILABLE_TEXT'] = 'No benefits available';
$lang['NRS_NA_TEXT'] = 'N/A';
$lang['NRS_NONE_TEXT'] = 'NE';
$lang['NRS_NOT_SET_TEXT'] = 'Není nastaveno';
$lang['NRS_DO_NOT_EXIST_TEXT'] = 'Neexistuje';
$lang['NRS_EXIST_TEXT'] = 'Existuje';
$lang['NRS_NOT_REQ_TEXT'] = 'Not req.';
$lang['NRS_REQ_TEXT'] = 'Req.';
$lang['NRS_NOT_REQUIRED_TEXT'] = 'Není požadováno';
$lang['NRS_REQUIRED_TEXT'] = 'Nutné vyplnit';
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
$lang['NRS_PREPAYMENT_TEXT'] = 'Předplacená částka';
$lang['NRS_TOTAL_TEXT'] = 'Celkem';
$lang['NRS_BACK_TEXT'] = 'Zpět';
$lang['NRS_CONTINUE_TEXT'] = 'Pokračovat';
$lang['NRS_SEARCH_TEXT'] = 'Vyhledat';
$lang['NRS_SELECT_DROPDOWN_TEXT'] = '--- Vybrat ---';
$lang['NRS_ITEM_TEXT'] = 'Auto';
$lang['NRS_EXTRA_TEXT'] = 'Doplňky';
$lang['NRS_RENTAL_OPTION_TEXT'] = 'Možnosti zapůjčení';
$lang['NRS_ITEMS_TEXT'] = 'Auta';
$lang['NRS_EXTRAS_TEXT'] = 'Doplňky';
$lang['NRS_RENTAL_OPTIONS_TEXT'] = 'Možnosti zapůjčení';
$lang['NRS_SHOW_ITEM_TEXT'] = 'Zobrazit auto';
$lang['NRS_VIA_PARTNER_TEXT'] = 'přes %s';
$lang['NRS_COUPON_TEXT'] = 'Kupón';

// Booking step no. 1 - item search
$lang['NRS_BOOKING_TEXT'] = 'Rezervace';
$lang['NRS_PICKUP_TEXT'] = 'Vyzvednutí';
$lang['NRS_RETURN_TEXT'] = 'Vrácení';
$lang['NRS_OTHER_TEXT'] = 'Ostatní';
$lang['NRS_INFORMATION_TEXT'] = 'Informace';
$lang['NRS_CITY_AND_LOCATION_TEXT'] = 'Město &amp; umístění:';
$lang['NRS_PICKUP_CITY_AND_LOCATION_TEXT'] = 'Město vyzvednutí &amp; Umístění:';
$lang['NRS_RETURN_CITY_AND_LOCATION_TEXT'] = 'Město vrácení &amp; Umístění:';
$lang['NRS_SELECT_BOOKING_DATE_TEXT'] = 'Datum:';
$lang['NRS_SELECT_BOOKING_PERIOD_TEXT'] = 'Délka rezervace:';
$lang['NRS_COUPON_CODE_TEXT'] = 'Kód kupónu';
$lang['NRS_I_HAVE_BOOKING_CODE_TEXT'] = 'Já mám rezervační kupón:';
$lang['NRS_I_HAVE_COUPON_CODE_TEXT'] = 'Já mám kód kupónu:';
$lang['NRS_PICKUP_LOCATION_TEXT'] = 'Místo vyzvednutí';
$lang['NRS_RETURN_LOCATION_TEXT'] = 'Místo vrácení';
$lang['NRS_ALL_BODY_TYPES_DROPDOWN_TEXT'] = '---- Všechny typy ----';
$lang['NRS_ALL_TRANSMISSION_TYPES_DROPDOWN_TEXT'] = '---- Všechny převodovky ----';
$lang['NRS_SELECT_PICKUP_LOCATION_TEXT'] = '-- Vybrat místo vyzvednutí --';
$lang['NRS_SELECT_RETURN_LOCATION_TEXT'] = '-- Vybrat místo vrácení --';
$lang['NRS_PICKUP_DATE_TEXT'] = 'Datum vyzvednutí';
$lang['NRS_RETURN_DATE_TEXT'] = 'Datum vrácení';
$lang['NRS_PICKUP_DATE_ALERT_TEXT'] = 'Prosím zvolte datum vyzvednutí!';
$lang['NRS_RETURN_DATE_ALERT_TEXT'] = 'Prosím zvolte datum vrácení!';
$lang['NRS_BOOKING_PERIOD_ALERT_TEXT'] = 'Prosím vyberte délku zapůjčení!';
$lang['NRS_PICKUP_LOCATION_ALERT_TEXT'] = 'Prosím vyberte místo zapůjčení!';
$lang['NRS_RETURN_LOCATION_ALERT_TEXT'] = 'Prosím vyberte místo vrácení!';
$lang['NRS_COUPON_CODE_ALERT_TEXT'] = 'Prosím vložte kód kupónu!';
$lang['NRS_SHOW_ITEM_DESCRIPTION_TEXT'] = 'Zobrazit popis auta';
$lang['NRS_UPDATE_BOOKING_TEXT'] = 'Aktualizovat mou rezervaci';
$lang['NRS_CANCEL_BOOKING_TEXT'] = 'Zrušit mou rezervaci';
$lang['NRS_CHANGE_BOOKING_DATE_TIME_AND_LOCATION_TEXT'] = 'Změnit datum, čas &amp; umístění';
$lang['NRS_CHANGE_BOOKED_ITEMS_TEXT'] = 'Změnit auta';
$lang['NRS_CHANGE_EXTRAS_TEXT'] = 'Změnit doplňky';
$lang['NRS_CHANGE_RENTAL_OPTIONS_TEXT'] = 'Změnit možnosti zapůjčení';
$lang['NRS_IN_THIS_LOCATION_TEXT'] = 'V tomto umístění';
$lang['NRS_AFTERHOURS_PICKUP_IS_NOT_ALLOWED_TEXT'] = 'Není dovoleno';
$lang['NRS_AFTERHOURS_RETURN_IS_NOT_ALLOWED_TEXT'] = 'Není dovoleno';

// Booking step no. 2 - search results
$lang['NRS_DISTANCE_AWAY_TEXT'] = '%s pryč';
$lang['NRS_BOOKING_DATA_TEXT'] = 'Detaily rezervace';
$lang['NRS_BOOKING_CODE_TEXT'] = 'Kód rezervace';
$lang['NRS_BOOKING_EDIT_TEXT'] = 'upravit';
$lang['NRS_BOOKING_PICKUP_TEXT'] = 'Vyzvednout';
$lang['NRS_BOOKING_BUSINESS_HOURS_TEXT'] = 'Pracovní doba';
$lang['NRS_BOOKING_FEE_TEXT'] = 'Poplatek';
$lang['NRS_BOOKING_RETURN_TEXT'] = 'Vrácení';
$lang['NRS_BOOKING_NIGHTLY_RATE_TEXT'] = 'pozdní hodiny';
$lang['NRS_BOOKING_AFTERHOURS_TEXT'] = 'pozdní hodiny';
$lang['NRS_BOOKING_EARLY_TEXT'] = 'Early';
$lang['NRS_BOOKING_LATE_TEXT'] = 'Late';
$lang['NRS_BOOKING_AFTERHOURS_PICKUP_TEXT'] = 'Pozdější vyzvednutí';
$lang['NRS_BOOKING_AFTERHOURS_PICKUP_IMPOSSIBLE_TEXT'] = 'Možné';
$lang['NRS_BOOKING_AFTERHOURS_RETURN_TEXT'] = 'Pozdější vrácení';
$lang['NRS_BOOKING_AFTERHOURS_RETURN_IMPOSSIBLE_TEXT'] = 'Možné';
$lang['NRS_CHOOSE_TEXT'] = 'Zvolte';
$lang['NRS_SEARCH_RESULTS_TEXT'] = 'Výsledky vyhledávání';
$lang['NRS_MILEAGE_TEXT'] = 'KM';

// Booking step no. 3 - booking options
$lang['NRS_SELECT_RENTAL_OPTIONS_TEXT'] = 'Vyberte možnosti zapůjčení';
$lang['NRS_SELECTED_ITEMS_TEXT'] = 'Vybrané auta';
$lang['NRS_FOR_DEPENDANT_ITEM_TEXT'] = ' (za %s)';
$lang['NRS_NO_EXTRAS_AVAILABLE_CLICK_CONTINUE_TEXT'] = 'Žádné doplňky nejsou k dispozici. Klikněte na tlačítko pokračovat';

// Booking step no. 4 - booking details
$lang['NRS_PICKUP_DATE_AND_TIME_TEXT'] = 'Datum vyzvednutí &amp; Čas';
$lang['NRS_RETURN_DATE_AND_TIME_TEXT'] = 'Datum vrácení &amp; čas';
$lang['NRS_UNIT_PRICE_TEXT'] = 'Cena za ks';
$lang['NRS_QUANTITY_TEXT'] = 'Množství';
$lang['NRS_QUANTITY_SHORT_TEXT'] = 'Množ.';
$lang['NRS_PICKUP_FEE_TEXT'] = 'Vyzvednutí poplatek';
$lang['NRS_RETURN_FEE_TEXT'] = 'Vrácení poplatek';
$lang['NRS_NIGHTLY_PICKUP_RATE_APPLIED_TEXT'] = '(Včetně noční přirážky)';
$lang['NRS_NIGHTLY_RETURN_RATE_APPLIED_TEXT'] = '(Včetně noční přirážky)';
$lang['NRS_ITEM_QUANTITY_SUFFIX_TEXT'] = 'auto(/a)';
$lang['NRS_EXTRA_QUANTITY_SUFFIX_TEXT'] = 'doplněk(/y)';
$lang['NRS_PAY_NOW_OR_AT_PICKUP_TEXT'] = 'Zaplať ihned / při vyzvednutí';
$lang['NRS_PAY_NOW_TEXT'] = 'Zaplať ihned';
$lang['NRS_PAY_AT_PICKUP_TEXT'] = 'Zaplať při vyzvednutí';
$lang['NRS_PAY_LATER_OR_ON_RETURN_TEXT'] = 'Zaplať později / při vrácení';
$lang['NRS_PAY_LATER_TEXT'] = 'Zaplať později';
$lang['NRS_PAY_ON_RETURN_TEXT'] = 'Zaplať při vrácení';
$lang['NRS_ITEM_RENTAL_DETAILS_TEXT'] = 'Detaily zapůjčení';
$lang['NRS_MANUFACTURER_TEXT'] = 'Výrobce';
$lang['NRS_ITEM_MODEL_TEXT'] = 'Model vozu';
$lang['NRS_GROSS_TOTAL_TEXT'] = 'Mezisoučet';
$lang['NRS_GRAND_TOTAL_TEXT'] = 'Celkový součet';
$lang['NRS_BOOKING_DETAILS_TEXT'] = 'Detaily rezervace';
$lang['NRS_CUSTOMER_DETAILS_TEXT'] = 'Detaily zákazníka';
$lang['NRS_EXISTING_CUSTOMER_DETAILS_TEXT'] = 'Hledej nebo detaily existujícího zákazníka';
$lang['NRS_EXISTING_CUSTOMER_TEXT'] = 'Existující zákazník';
$lang['NRS_EMAIL_ADDRESS_TEXT'] = 'E-mailová adresa';
$lang['NRS_FETCH_CUSTOMER_DETAILS_TEXT'] = 'Načíst údaje';
$lang['NRS_OR_ENTER_NEW_DETAILS_TEXT'] = 'Nebo vytvořit nový účet';
$lang['NRS_CUSTOMER_TEXT'] = 'Zákazník';
$lang['NRS_TITLE_TEXT'] = 'Oslovení';
$lang['NRS_MR_TEXT'] = 'Pan';
$lang['NRS_MS_TEXT'] = 'Paní';
$lang['NRS_MRS_TEXT'] = 'Slečna';
$lang['NRS_MISS_TEXT'] = 'Ing.';
$lang['NRS_DR_TEXT'] = 'Mgr.';
$lang['NRS_PROF_TEXT'] = 'MUDr.';
$lang['NRS_FIRST_NAME_TEXT'] = 'Jméno';
$lang['NRS_LAST_NAME_TEXT'] = 'Příjmení';
$lang['NRS_DATE_OF_BIRTH_TEXT'] = 'Datum narození';
$lang['NRS_YEAR_OF_BIRTH_TEXT'] = 'Rok narození';
$lang['NRS_ADDRESS_TEXT'] = 'Adresa';
$lang['NRS_STREET_ADDRESS_TEXT'] = 'Adresa';
$lang['NRS_CITY_TEXT'] = 'Město';
$lang['NRS_STATE_TEXT'] = 'Stát';
$lang['NRS_ZIP_CODE_TEXT'] = 'PSČ';
$lang['NRS_COUNTRY_TEXT'] = 'Země';
$lang['NRS_PHONE_TEXT'] = 'Telefon';
$lang['NRS_EMAIL_TEXT'] = 'E-mail';
$lang['NRS_ADDITIONAL_COMMENTS_TEXT'] = 'Dodatečný komentář';
$lang['NRS_CUSTOMER_ID_TEXT'] = 'Zákaznické ID';
$lang['NRS_IP_ADDRESS_TEXT'] = 'IP Adresa';
$lang['NRS_PAY_BY_SHORT_TEXT'] = 'Zaplatit';
$lang['NRS_I_AGREE_WITH_TERMS_AND_CONDITIONS_TEXT'] = 'Souhlasím s podmínkami &amp; užívání';
$lang['NRS_TERMS_AND_CONDITIONS_TEXT'] = 'Podmínky &amp; Užívání';
$lang['NRS_CONFIRM_TEXT'] = 'Potvrdit';
$lang['NRS_FIELD_REQUIRED_TEXT'] = 'Toto pole je nutné vyplnit';

// Booking step no. 5 - process booking
$lang['NRS_PAYMENT_DETAILS_TEXT'] = 'Detaily platby';
$lang['NRS_PAYMENT_OPTION_TEXT'] = 'Zaplatit';
$lang['NRS_PAYER_EMAIL_TEXT'] = 'E-mail plátce';
$lang['NRS_TRANSACTION_ID_TEXT'] = 'ID transakce';
$lang['NRS_PROCESSING_PAYMENT_TEXT'] = 'Provádím platbu…';
$lang['NRS_PLEASE_WAIT_UNTIL_PAYMENT_WILL_BE_PROCESSED_TEXT'] = 'Prosím počkejte, dokud nebude platba provedena…';

//display-booking-confirm.php
$lang['NRS_STEP5_PAY_ONLINE_TEXT'] = 'Zaplatit on-line';
$lang['NRS_STEP5_PAY_AT_PICKUP_TEXT'] = 'Zaplatit při vyzvednutí';
$lang['NRS_THANK_YOU_TEXT'] = 'Děkujeme Vám!';
$lang['NRS_YOUR_BOOKING_CONFIRMED_TEXT'] = 'Vaše rezervace je potvrzena. Rzervační kód ';
$lang['NRS_INVOICE_SENT_TO_YOUR_EMAIL_ADDRESS_TEXT'] = 'Faktura odeslána na Váš e-mail';

//display-booking-failure.php
$lang['NRS_BOOKING_FAILURE_TEXT'] = 'Selhání rezervace';
$lang['NRS_BOOKING_FAILURE_SEARCH_ALL_ITEMS_TEXT'] = 'Vyhledávat všechny vozy';

// display-item-price-table.php
$lang['NRS_DAY_PRICE_TEXT'] = 'Cena za den';
$lang['NRS_HOUR_PRICE_TEXT'] = 'Cena za hodinu';
$lang['NRS_NO_ITEMS_IN_THIS_CATEGORY_TEXT'] = 'V této kategorii nejsou žádné vozy';
$lang['NRS_PRICE_FOR_DAY_FROM_TEXT'] = 'Cena za den začíná od';
$lang['NRS_PRICE_FOR_HOUR_FROM_TEXT'] = 'Cena za hod začíná od';
$lang['NRS_PRICE_WITH_APPLIED_TEXT'] = 'včetně';
$lang['NRS_WITH_APPLIED_DISCOUNT_TEXT'] = 'slevy';

// class.ItemsAvailability.php
$lang['NRS_MONTH_DAY_TEXT'] = 'Den';
$lang['NRS_MONTH_DAYS_TEXT'] = 'Dny';
$lang['NRS_ITEMS_AVAILABILITY_FOR_TEXT'] = 'Vozy k dispozici za';
$lang['NRS_ITEMS_AVAILABILITY_IN_NEXT_30_DAYS_TEXT'] = 'Vozy k dispozici v příštích 30dnech';
$lang['NRS_ITEMS_PARTIAL_AVAILABILITY_FOR_TEXT'] = 'Částečně dostupné vozy';
$lang['NRS_ITEMS_AVAILABILITY_THIS_MONTH_TEXT'] = 'Auta k dispozici tento měsíc'; // Not used
$lang['NRS_ITEMS_AVAILABILITY_NEXT_MONTH_TEXT'] = 'Auta k dispozici příští měsíc'; // Not used
$lang['NRS_ITEM_ID_TEXT'] = 'ID:';
$lang['NRS_TOTAL_ITEMS_TEXT'] = 'Celkem aut:';

// class.ExtrasAvailability.php
$lang['NRS_EXTRAS_AVAILABILITY_FOR_TEXT'] = 'Doplňky k dispozici za ';
$lang['NRS_EXTRAS_AVAILABILITY_IN_NEXT_30_DAYS_TEXT'] = 'Doplňky k dispozici za 30dní';
$lang['NRS_EXTRAS_PARTIAL_AVAILABILITY_FOR_TEXT'] = 'Částečná dostupnost doplňků';
$lang['NRS_EXTRAS_AVAILABILITY_THIS_MONTH_TEXT'] = 'Doplňky k dispozici tento měsíc'; // Not used
$lang['NRS_EXTRAS_AVAILABILITY_NEXT_MONTH_TEXT'] = 'Doplňky k dispozici příští měsíc'; // Not used
$lang['NRS_EXTRA_ID_TEXT'] = 'ID';
$lang['NRS_TOTAL_EXTRAS_TEXT'] = 'Celkem doplňky:';

// class.ItemsController.php
$lang['NRS_SHOW_ITEM_PAGE_TEXT'] = 'Zobrazit popis vozu';
$lang['NRS_PARTNER_TEXT'] = 'Partner';
$lang['NRS_BODY_TYPE_TEXT'] = 'Kategorie';
$lang['NRS_TRANSMISSION_TYPE_TEXT'] = 'Převodovka';
$lang['NRS_FUEL_TYPE_TEXT'] = 'Palivo';
$lang['NRS_ITEM_FUEL_CONSUMPTION_TEXT'] = 'Spotřeba';
$lang['NRS_ITEM_PASSENGERS_TEXT'] = 'Max počet cestujících';
$lang['NRS_ITEM_PRICE_FROM_TEXT'] = 'Cena od';
$lang['NRS_INQUIRE_TEXT'] = 'Call';
$lang['NRS_GET_A_QUOTE_TEXT'] = 'Požádat o cenu';
$lang['NRS_ITEM_FEATURES_TEXT'] = 'Vlastnosti';
$lang['NRS_BOOK_ITEM_TEXT'] = 'Půjčit';

// class.LocationsController.php
$lang['NRS_LOCATIONS_BUSINESS_HOURS_TEXT'] = 'Business Hours';
$lang['NRS_LOCATION_FEES_TEXT'] = 'Umístění - poplatky';
$lang['NRS_EARLY_PICKUP_TEXT'] = 'Early Pick-Up';
$lang['NRS_LATE_PICKUP_TEXT'] = 'Late Pick-Up';
$lang['NRS_EARLY_RETURN_TEXT'] = 'Early Return';
$lang['NRS_LATE_RETURN_TEXT'] = 'Late Return';
$lang['NRS_EARLY_PICKUP_FEE_TEXT'] = 'Early pick-up fee';
$lang['NRS_LATE_RETURN_FEE_TEXT'] = 'Late return fee';
$lang['NRS_VIEW_LOCATION_TEXT'] = 'View Location';

// class.SingleItemController.php
$lang['NRS_ITEM_ENGINE_CAPACITY_TEXT'] = 'Obsah motoru';
$lang['NRS_ITEM_LUGGAGE_TEXT'] = 'Max počet kufrů';
$lang['NRS_ITEM_DOORS_TEXT'] = 'Dveře';
$lang['NRS_ITEM_DRIVER_AGE_TEXT'] = 'Minimální věk řidiče';
$lang['NRS_ADDITIONAL_INFORMATION_TEXT'] = 'Dodatečné informace';

// class.SingleLocationController.php
$lang['NRS_CONTACTS_TEXT'] = 'Contacts';
$lang['NRS_CONTACT_DETAILS_TEXT'] = 'Contact Details';
$lang['NRS_BUSINESS_HOURS_FEES_TEXT'] = 'Business Hours Fees';
$lang['NRS_AFTERHOURS_FEES_TEXT'] = 'After Hours Fees';

// template.BookingCancelled.php
$lang['NRS_CANCELLED_SUCCESSFULLY_TEXT'] = 'Úspěšně zrušeno';
$lang['NRS_NOT_CANCELLED_TEXT'] = 'Rezervace nebyla zrušena';

// template.Step8EditBooking.php
$lang['NRS_EDIT_TEXT'] = 'Změnit';
$lang['NRS_BOOKING2_TEXT'] = 'rezervaci';
$lang['NRS_EDIT_BOOKING_BUTTON_TEXT'] = 'Změnit rezervaci';
$lang['NRS_EDIT_BOOKING_PLEASE_ENTER_BOOKING_NUMBER_TEXT'] = 'Prosím napište číslo rezervace!';

// Admin System Errors
// Unfortunately, they are untranslatable
$lang['NRS_ERROR_IN_METHOD_TEXT'] = 'Chyba ve %s způsobu: ';

// Exceptions
$lang['NRS_ERROR_CANNOT_BIND_TEMPLATE_VARIABLE_TEXT'] = 'nemůže být variabilně pojmenováno &#39;templateFile&#39;.';
$lang['NRS_ERROR_TEMPLATE_NOT_EXIST_TEXT'] = 'Soubor s šablonou %s neexistuje.';

// Errors
$lang['NRS_ERROR_EXTENSION_NAME_TEXT'] = 'Systém autopůjčovny';
$lang['NRS_ERROR_REQUIRED_FIELD_TEXT'] = 'Požadované pole';
$lang['NRS_ERROR_IS_EMPTY_TEXT'] = 'je prázdný';
$lang['NRS_ERROR_SLIDER_CANT_BE_DISPLAYED_TEXT'] = 'Slider nem&#39;ůže být zobrazen';
$lang['NRS_ERROR_CUSTOMER_DETAILS_NOT_FOUND_TEXT'] = 'Neexistuje žádný zákazník s těmito údaji. Prosím vytvořte nový účet.';
$lang['NRS_ERROR_CUSTOMER_DETAILS_NO_ERROR_TEXT'] = 'Žádné chyby';
$lang['NRS_ERROR_EXCEEDED_CUSTOMER_LOOKUP_ATTEMPTS_TEXT'] = 'Překročili jste počet nahlédnutí do detailu zákazníka. Prosím vložte detaily manuálně do formuláře níže.';
$lang['NRS_ERROR_CUSTOMER_DETAILS_UNKNOWN_ERROR_TEXT'] = 'Neznámá chyba';
$lang['NRS_ERROR_BOOKING_DOES_NOT_EXIST_TEXT'] = 'neexistuje';
$lang['NRS_ERROR_PLEASE_SELECT_AT_LEAST_ONE_ITEM_TEXT'] = 'Prosím zvolte alespoň jedno vozidlo';
$lang['NRS_ERROR_ITEM_NOT_AVAILABLE_TEXT'] = 'Toto vozidlo není k dispozici.';
$lang['NRS_ERROR_NO_ITEM_AVAILABLE_PLEASE_TRY_DIFFERENT_DATE_TEXT'] = 'Žádné vozy k dispozici. Prosím změňte čas půjčení nebo kritéria vyhledávání.';
$lang['NRS_ERROR_SEARCH_ENGINE_DISABLED_TEXT'] = 'On-line rezervace je momentálně vypnutá. Prosím zkuste to znovu později.';
$lang['NRS_ERROR_OUT_BEFORE_IN_TEXT'] = 'Datum vrácení musí být později než datum půjčení. Prosím zvolte platná data pro půjčení a vrácení.';
$lang['NRS_ERROR_MINIMUM_NUMBER_OF_NIGHT_SHOULD_NOT_BE_LESS_THAN_TEXT'] = 'Minimální počet nocí nemůže být menší než';
$lang['NRS_ERROR_PLEASE_MODIFY_YOUR_SEARCH_CRITERIA_TEXT'] = 'Prosím upravte kritéria vyhledávání.';
$lang['NRS_ERROR_PICKUP_IS_NOT_POSSIBLE_ON_TEXT'] = 'Vyzvednutí není možné v';
$lang['NRS_ERROR_PLEASE_MODIFY_YOUR_PICKUP_TIME_BY_WEBSITE_TIME_TEXT'] = 'Prosím upravte datum vyzvednutí a &amp; čas podle místního data a času vypůjčení.';
$lang['NRS_ERROR_CURRENT_DATE_TIME_TEXT'] = 'Místní datum a  &amp; čas půjčovny je';
$lang['NRS_ERROR_EARLIEST_POSSIBLE_PICKUP_DATE_TIME_TEXT'] = 'Nejbližší možný datum &amp; a čas vyzvednutí je';
$lang['NRS_ERROR_OR_NEXT_BUSINESS_HOURS_OF_PICKUP_LOCATION_TEXT'] = 'nebo první možnost po té, kdy je ve vybraném umístění otevřeno';
$lang['NRS_ERROR_PICKUP_DATE_CANT_BE_LESS_THAN_RETURN_DATE_TEXT'] = 'Datum vyzvednutí &amp; čas nemůže být kratší než datum & čas. Prosím zvolte správný čas vyzvednutí & vrácení';
$lang['NRS_ERROR_PICKUP_LOCATION_IS_CLOSED_AT_THIS_DATE_TEXT'] = 'Místo vyzvednutí %s na adrese %s je zavřené v tento datum (%s).';
$lang['NRS_ERROR_PICKUP_LOCATION_IS_CLOSED_AT_THIS_TIME_TEXT'] = 'Místo vyzvednutí %s na adrese %s je zavřené v tento čas (%s).';
$lang['NRS_ERROR_RETURN_LOCATION_IS_CLOSED_AT_THIS_DATE_TEXT'] = 'Místo vrácení %s na adrese %s je zavřené v tento datum (%s).';
$lang['NRS_ERROR_RETURN_LOCATION_IS_CLOSED_AT_THIS_TIME_TEXT'] = 'Místo vrácení %s na adrese %s je zavřené v tento čas (%s).';
$lang['NRS_ERROR_AFTERHOURS_PICKUP_LOCATION_IS_CLOSED_AT_THIS_TIME_TEXT'] = 'Po pracovní době vyzvednutí je %s na adrese %s ale toto místo je také v tento čas zavřené.';
$lang['NRS_ERROR_AFTERHOURS_RETURN_LOCATION_IS_CLOSED_AT_THIS_TIME_TEXT'] = 'Po pracovní době vrácení je %s na adrese %s ale toto místo je také v tento čas zavřené.';
$lang['NRS_ERROR_LOCATION_OPEN_HOURS_ARE_TEXT'] = 'V tomto místě je otevřeno v %s, %s je %s.';
$lang['NRS_ERROR_LOCATION_WEEKLY_OPEN_HOURS_ARE_TEXT'] = 'Otevírací doba v tomto místě přes týden je:';
$lang['NRS_ERROR_AFTERHOURS_PICKUP_LOCATION_OPEN_HOURS_ARE_TEXT'] = 'V místě vyzvednutí je mimo pracovní dobu %s.';
$lang['NRS_ERROR_AFTERHOURS_RETURN_LOCATION_OPEN_HOURS_ARE_TEXT'] = 'V místě vrácení je mimo pracovní dobu %s.';
$lang['NRS_ERROR_AFTERHOURS_PICKUP_IS_NOT_ALLOWED_AT_LOCATION_TEXT'] = 'V tomto umístění není možné zapůjčení po otevírací době.';
$lang['NRS_ERROR_AFTERHOURS_RETURN_IS_NOT_ALLOWED_AT_LOCATION_TEXT'] = 'V tomto umístění není možné vrácení po otevírací době.';
$lang['NRS_ERROR_MAXIMUM_BOOKING_LENGTH_CANT_BE_MORE_THAN_TEXT'] = 'Maximální délka rezervace je (v dnech)';
$lang['NRS_ERROR_INVALID_BOOKING_CODE_TEXT'] = 'Chybný kód rezervace nebo tento kód neexistuje vůbec.';
$lang['NRS_ERROR_INVALID_SECURITY_CODE_TEXT'] = 'Bezpečnostní kód není platný.';
$lang['NRS_ERROR_DRIVER_AGE_VIOLATION_FOR_ITEM_TEXT'] = 'Based on your birthdate, your age does not match the minimum driver age requirement for %s.';
$lang['NRS_ERROR_DRIVER_AGE_VIOLATION_TEXT'] = 'Based on your birthdate, your age does not match the minimum driver age requirement for one of selected cars.';
$lang['NRS_ERROR_DEPARTED_TEXT'] = 'Rezervace č. %s je označena jako vypůjčená, a nejsou možné další úpravy.';
$lang['NRS_ERROR_CANCELLED_TEXT'] = 'Rezervace č. %s byla zrušena.';
$lang['NRS_ERROR_REFUNDED_TEXT'] = 'Rezervace č. %s byla refundována - vrácena zpět zákazníkovi a dále není k dispozici.';
$lang['NRS_ERROR_SYSTEM_IS_UNABLE_TO_SEND_EMAIL_TEXT'] = 'Chyba: Systém není schopen odeslat potvrzující e-mail. Nastavení e-mailu není správně nastaveno, nebo zákazníkovo&#39;s e-mailová schránka není správná.';
$lang['NRS_ERROR_PAYMENT_METHOD_IS_NOT_YET_IMPLEMENTED_TEXT'] = 'Chyba: Vy&39; zkoušíte platit touto platební metodou, která není k dispozici ve Vašem systému.';
$lang['NRS_ERROR_OTHER_BOOKING_ERROR_TEXT'] = 'Other reservation error. If you keep seeing this message, please contact website administrator.';

// Admin Discount controller
$lang['NRS_ADMIN_DISCOUNT_ITEM_BOOKING_IN_ADVANCE_TEXT'] = 'Přidat/Upravit slevu aut za rezervaci dopředu';
$lang['NRS_ADMIN_DISCOUNT_ITEM_BOOKING_DURATION_TEXT'] = 'Přidat/Upravit Slevu aut za délku zapůjčení';
$lang['NRS_ADMIN_DISCOUNT_EXTRA_BOOKING_IN_ADVANCE_TEXT'] = 'Přidat/Upravit Slevu doplňků za rezervaci dopředu';
$lang['NRS_ADMIN_DISCOUNT_EXTRA_BOOKING_DURATION_TEXT'] = 'Přidat/Upravit Slevu doplňků za délku zapůjčení';
$lang['NRS_ADMIN_DISCOUNT_DURATION_BEFORE_TEXT'] = 'Délka před tím:';
$lang['NRS_ADMIN_DISCOUNT_DURATION_UNTIL_TEXT'] = 'Délka než:';
$lang['NRS_ADMIN_DISCOUNT_DURATION_FROM_TEXT'] = 'Délka od:';
$lang['NRS_ADMIN_DISCOUNT_DURATION_TILL_TEXT'] = 'Délka do:';

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
$lang['NRS_EMAIL_DEMO_LOCATION_NAME_TEXT'] = 'Demo Umístění';
$lang['NRS_EMAIL_DEMO_LOCATION_PHONE_TEXT'] = '+420 606 123 456';
$lang['NRS_EMAIL_DEMO_LOCATION_EMAIL_TEXT'] = 'info@umisteni.cz';
$lang['NRS_EMAIL_SUBJECT_EXISTS_ERROR_TEXT'] = 'Chyba: Jiný e-mail existuje s tímto předmětem!';
$lang['NRS_EMAIL_UPDATE_ERROR_TEXT'] = 'Chyba: MySQL chyba aktualizace pro existující email!';
$lang['NRS_EMAIL_UPDATED_TEXT'] = 'Kompletní: E-mail byl úspěšně aktualizován!';
$lang['NRS_EMAIL_REGISTERED_TEXT'] = 'E-mail: Tělo a předmět registrován pro překlad.';
$lang['NRS_EMAIL_SENDING_ERROR_TEXT'] = 'Chyba: System nemůže odeslat e-mail %s!';
$lang['NRS_EMAIL_SENT_TEXT'] = 'Kompletní: E-mail byl odeslán %s!';

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
$lang['NRS_INSTALL_DEFAULT_COMPANY_NAME_TEXT'] = 'Autopůjčovna - společnost';
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
$lang['NRS_INSTALL_DEFAULT_CANCELLED_PAYMENT_PAGE_TITLE_TEXT'] = 'Platba zrušena';
$lang['NRS_INSTALL_DEFAULT_CANCELLED_PAYMENT_PAGE_CONTENT_TEXT'] = 'Platba byla zrušena. Vaše rezervace není potvrzena.';
$lang['NRS_INSTALL_DEFAULT_CONFIRMATION_PAGE_TITLE_TEXT'] = 'Rezervace potvrzena';
$lang['NRS_INSTALL_DEFAULT_CONFIRMATION_PAGE_CONTENT_TEXT'] = 'Děkujeme Vám. Vaší platbu jsme přijali. Rezervace je potvrzená.';
$lang['NRS_INSTALL_DEFAULT_TERMS_AND_CONDITIONS_PAGE_TITLE_TEXT'] = 'Podmínky pro zapůjčení vozu.';
$lang['NRS_INSTALL_DEFAULT_TERMS_AND_CONDITIONS_PAGE_CONTENT_TEXT'] = 'Musíte souhlasit s následujícími &amp; podmínkami pro zapůjčení vozu.';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAYPAL_TEXT'] = 'Online - PayPal';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAYPAL_DESCRIPTION_TEXT'] = 'Zabezpečená okamžitá platba';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_STRIPE_TEXT'] = 'Credit Card (přes Stripe.com)';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_BANK_TEXT'] = 'Bankovní převod';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_BANK_DETAILS_TEXT'] = 'Vaše bankovní údaje';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAY_OVER_THE_PHONE_TEXT'] = 'Platba přes telefon';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAY_ON_ARRIVAL_TEXT'] = 'Platba při vyzvednutí vozu';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAY_ON_ARRIVAL_DETAILS_TEXT'] = 'Platební karta nutná';
$lang['NRS_INSTALL_DEFAULT_DEAR_TEXT'] = 'Vážený';
$lang['NRS_INSTALL_DEFAULT_REGARDS_TEXT'] = 'S pozdravem';
$lang['NRS_INSTALL_DEFAULT_EMAIL_TITLE_BOOKING_DETAILS_TEXT'] = 'Detaily rezervace - č. [BOOKING_CODE]';
$lang['NRS_INSTALL_DEFAULT_EMAIL_TITLE_BOOKING_CONFIRMED_TEXT'] = 'Rezervace č. [BOOKING_CODE] - potvrzena';
$lang['NRS_INSTALL_DEFAULT_EMAIL_TITLE_BOOKING_CANCELLED_TEXT'] = 'Rezervace č. [BOOKING_CODE] - zrušena';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_TITLE_BOOKING_DETAILS_TEXT'] = 'Upozornění: nová rezervace - [BOOKING_CODE]';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_TITLE_BOOKING_CONFIRMED_TEXT'] = 'Upozornění: rezervace zaplacena - [BOOKING_CODE]';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_TITLE_BOOKING_CANCELLED_TEXT'] = 'Upozornění: rezervace zrušena - [BOOKING_CODE]';
$lang['NRS_INSTALL_DEFAULT_EMAIL_BODY_BOOKING_RECEIVED_TEXT'] = 'Detaily Vaší rezervace doručeny.';
$lang['NRS_INSTALL_DEFAULT_EMAIL_BODY_BOOKING_DETAILS_TEXT'] = 'Detaily Vaší rezervace:';
$lang['NRS_INSTALL_DEFAULT_EMAIL_BODY_PAYMENT_RECEIVED_TEXT'] = 'Přijali name Vaší platbu. Vaše rezervace je nyní POTVRZENA.';
$lang['NRS_INSTALL_DEFAULT_EMAIL_BODY_BOOKING_CANCELLED_TEXT'] = 'Vaše rezercace č. [BOOKING_CODE] byla zrušena.';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_BOOKING_RECEIVED_TEXT'] = 'Nová rezervace č. [BOOKING_CODE] přijata od [CUSTOMER_NAME].';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_BOOKING_DETAILS_TEXT'] = 'Detaily rezervace:';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_BOOKING_PAID_TEXT'] = 'Rezervace č. [BOOKING_CODE] byla uhrazena [CUSTOMER_NAME].';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_BOOKING_CANCELLED_TEXT'] = 'Rezervace č. [BOOKING_CODE] pro [CUSTOMER_NAME] byla zrušena.';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_CANCELLED_BOOKING_DETAILS_TEXT'] = 'Detaily rezervace, která byla zrušena:';

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