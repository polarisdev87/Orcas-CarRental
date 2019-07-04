<?php
/**
 * Language specific file
 * @Language - Español
 * @Author - Ana Victoria Rodríguez Guerrero, Edner Zephir, Anthony Ortega
 * @Email - nanaluna18@hotmail.com, zephiredner@hotmail.com, tony@soportepuq.net
 * @Website - Not specified
 */
// Settings
$lang['LTR'] = FALSE;
$lang['NRS_RECAPTCHA_LANG'] = 'es';

// Roles
$lang['NRS_PARTNER_ROLE_NAME_TEXT'] = 'Socio de Carro';
$lang['NRS_ASSISTANT_ROLE_NAME_TEXT'] = 'Asistente de Carro';
$lang['NRS_MANAGER_ROLE_NAME_TEXT'] = 'Administrador de Carros';

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
$lang['NRS_ADMIN_VIEW_DETAILS_TEXT'] = 'Ver Detalles';
$lang['NRS_ADMIN_VIEW_BOOKINGS_TEXT'] = 'Ver Reserva';
$lang['NRS_ADMIN_VIEW_UNPAID_BOOKINGS_TEXT'] = 'Ver reservas pendientes de pago';
$lang['NRS_ADMIN_NO_BOOKINGS_YET_TEXT'] = 'No se hacen reservas aún';
$lang['NRS_ADMIN_BOOKING_DETAILS_TEXT'] = 'Detalles de la Reserva';
$lang['NRS_ADMIN_CUSTOMER_DETAILS_FROM_DB_TEXT'] = 'Datos del cliente ( última versión de la base de datos )';
$lang['NRS_ADMIN_BOOKING_STATUS_TEXT'] = 'Estado de la Reserva';
$lang['NRS_ADMIN_BOOKING_STATUS_UPCOMING_TEXT'] = 'Próximas';
$lang['NRS_ADMIN_BOOKING_STATUS_DEPARTED_TEXT'] = 'Expirada';
$lang['NRS_ADMIN_BOOKING_STATUS_COMPLETED_EARLY_TEXT'] = 'Finalizada antes de tiempo';
$lang['NRS_ADMIN_BOOKING_STATUS_COMPLETED_TEXT'] = 'Completada';
$lang['NRS_ADMIN_BOOKING_STATUS_ACTIVE_TEXT'] = 'Activa';
$lang['NRS_ADMIN_BOOKING_STATUS_CANCELLED_TEXT'] = 'Cancelada';
$lang['NRS_ADMIN_BOOKING_STATUS_PAID_TEXT'] = 'Pagado';
$lang['NRS_ADMIN_BOOKING_STATUS_UNPAID_TEXT'] = 'Sin Pagar';
$lang['NRS_ADMIN_BOOKING_STATUS_REFUNDED_TEXT'] = 'Devuelta';
$lang['NRS_ADMIN_PRINT_INVOICE_TEXT'] = 'Imprimir Factura';
$lang['NRS_ADMIN_BACK_TO_CUSTOMER_BOOKING_LIST_TEXT'] = 'Volver a la lista de Reservas del Cliente';
$lang['NRS_ADMIN_CUSTOMERS_BY_LAST_VISIT_TEXT'] = 'Clientes por fecha de la última visita';
$lang['NRS_ADMIN_CUSTOMERS_BY_REGISTRATION_TEXT'] = 'Clientes por fecha de registro';
$lang['NRS_ADMIN_BOOKINGS_PERIOD_FROM_TO_TEXT'] = 'Reserv. Período:%s -%s ';
$lang['NRS_ADMIN_PICKUPS_PERIOD_FROM_TO_TEXT'] = 'Período de recogida:%s -%s';
$lang['NRS_ADMIN_RETURNS_PERIOD_FROM_TO_TEXT'] = 'Periodo de Retorno:%s -%s';
$lang['NRS_ADMIN_UPCOMING_TEXT'] = 'Próximos';
$lang['NRS_ADMIN_PAST_TEXT'] = 'Pasado';
$lang['NRS_ADMIN_CUSTOMER_BOOKINGS_TEXT'] = 'Reservas del cliente';
$lang['NRS_ADMIN_BOOKINGS_BY_TEXT'] = 'Reservaciones por%s';
$lang['NRS_ADMIN_ALL_BOOKINGS_TEXT'] = 'Todas las reservas';
$lang['NRS_ADMIN_ALL_PICKUPS_TEXT'] = 'Todas las recogidas';
$lang['NRS_ADMIN_ALL_RETURNS_TEXT'] = 'Todas las devoluciones';
$lang['NRS_ADMIN_MAX_ITEM_UNITS_PER_BOOKING_TEXT'] = 'Unidades de Carro máximas por reserva';
$lang['NRS_ADMIN_TOTAL_ITEM_UNITS_IN_STOCK_TEXT'] = 'Total de unidades de Carros en el garaje';
$lang['NRS_ADMIN_MAX_EXTRA_UNITS_PER_BOOKING_TEXT'] = 'Máximo de unidades adicionales por reserva';
$lang['NRS_ADMIN_TOTAL_EXTRA_UNITS_IN_STOCK_TEXT'] = 'Total de unidades adicionales disponibles';
$lang['NRS_ADMIN_ITEM_PRICES_TEXT'] = 'Precios del Carro';
$lang['NRS_ADMIN_ITEM_DEPOSITS_TEXT'] = 'Depósitos de Carros';
$lang['NRS_ADMIN_EXTRA_PRICES_TEXT'] = 'Precios adicionales';
$lang['NRS_ADMIN_EXTRA_DEPOSITS_TEXT'] = 'Depósitos adicionales';
$lang['NRS_ADMIN_PICKUP_FEES_TEXT'] = 'Cuotas de recogida';
$lang['NRS_ADMIN_DISTANCE_FEES_TEXT'] = 'Cuota de Distancia';
$lang['NRS_ADMIN_RETURN_FEES_TEXT'] = 'Cuota de Retorno';
$lang['NRS_ADMIN_REGULAR_PRICE_TEXT'] = 'Precio Regular';
$lang['NRS_ADMIN_PRICE_TYPE_TEXT'] = 'Tipo de Precio';
$lang['NRS_ADMIN_ON_THE_LEFT_TEXT'] = 'A la izquierda';
$lang['NRS_ADMIN_ON_THE_RIGHT_TEXT'] = 'A la derecha';
$lang['NRS_ADMIN_LOAD_FROM_OTHER_PLACE_TEXT'] = 'Cargar desde otro lugar';
$lang['NRS_ADMIN_LOAD_FROM_PLUGIN_TEXT'] = 'Cargar desde este plugin';
$lang['NRS_ADMIN_EMAIL_TEXT'] = 'E-mail';
$lang['NRS_ADMIN_PUBLIC_TEXT'] = 'Público';
$lang['NRS_ADMIN_PRIVATE_TEXT'] = 'Secreto';
$lang['NRS_ADMIN_CALENDAR_NO_CALENDARS_FOUND_TEXT'] = 'No se ha metido en el calendario para el rango de fechas seleccionado';
$lang['NRS_ADMIN_CHOOSE_PAGE_TEXT'] = '- Elegir la página -';
$lang['NRS_ADMIN_SELECT_EMAIL_TYPE_TEXT'] = '--- Seleccione el tipo de email ---';
$lang['NRS_ADMIN_TOTAL_REQUESTS_LEFT_TEXT'] = 'Total de peticiones dejadas';
$lang['NRS_ADMIN_FAILED_REQUESTS_LEFT_TEXT'] = 'peticiones fallidas';
$lang['NRS_ADMIN_EMAIL_ATTEMPTS_LEFT_TEXT'] = 'intentos de correo electrónico dejado';

// Admin Menu
$lang['NRS_ADMIN_MENU_EXTENSION_PAGE_TITLE_TEXT'] = 'Car Rental System';
$lang['NRS_ADMIN_MENU_EXTENSION_LABEL_TEXT'] = 'Alquiler de Carros';
$lang['NRS_ADMIN_MENU_SYSTEM_UPDATE_TEXT'] = 'Actualización del sistema';
$lang['NRS_ADMIN_MENU_NETWORK_UPDATE_TEXT'] = 'Actualización de la red';
// Admin Menu - Benefit Manager
$lang['NRS_ADMIN_MENU_BENEFIT_MANAGER_TEXT'] = ' Administrar Beneficio';
$lang['NRS_ADMIN_MENU_ADD_EDIT_BENEFIT_TEXT'] = 'Añadir / Editar Beneficio';
// Admin Menu - Administrador de artículos
$lang['NRS_ADMIN_MENU_ITEM_MANAGER_TEXT'] = 'Administrador de Carros';
$lang['NRS_ADMIN_MENU_ADD_EDIT_ITEM_TEXT'] = 'Añadir / Editar Carro';
$lang['NRS_ADMIN_MENU_ADD_EDIT_MANUFACTURER_TEXT'] = 'Añadir / Editar fabricante';
$lang['NRS_ADMIN_MENU_ADD_EDIT_BODY_TYPE_TEXT'] = 'Añadir / Editar tipo de carrocería';
$lang['NRS_ADMIN_MENU_ADD_EDIT_FUEL_TYPE_TEXT'] = 'Añadir / Editar Tipo de Combustible';
$lang['NRS_ADMIN_MENU_ADD_EDIT_TRANSMISSION_TYPE_TEXT'] = 'Añadir / Editar Tipo de Transmisión';
$lang['NRS_ADMIN_MENU_ADD_EDIT_FEATURE_TEXT'] = 'Añadir / Editar Característica';
$lang['NRS_ADMIN_MENU_ADD_EDIT_ITEM_OPTION_TEXT'] = 'Añadir / Editar Opciones de Carro';
$lang['NRS_ADMIN_MENU_BLOCK_ITEM_TEXT'] = 'Bloquear Carro';
// Admin Menu - Precios de artículos
$lang['NRS_ADMIN_MENU_ITEM_PRICES_TEXT'] = 'Precios del Carro';
$lang['NRS_ADMIN_MENU_ADD_EDIT_PRICE_GROUP_TEXT'] = 'Añadir / Editar grupo de precios';
$lang['NRS_ADMIN_MENU_ADD_EDIT_PRICE_PLAN_TEXT'] = 'Añadir / Editar Plan de Precios de Carros';
$lang['NRS_ADMIN_MENU_ADD_EDIT_ITEM_DISCOUNT_TEXT'] = 'Añadir / Editar descuento de Carro';
// Admin Menu - Administrador de Extras
$lang['NRS_ADMIN_MENU_EXTRAS_MANAGER_TEXT'] = 'Administrador de extras';
$lang['NRS_ADMIN_MENU_ADD_EDIT_EXTRA_TEXT'] = 'Añadir / Editar Extra';
$lang['NRS_ADMIN_MENU_ADD_EDIT_EXTRA_OPTION_TEXT'] = 'Añadir / Editar Opción de Extra';
$lang['NRS_ADMIN_MENU_ADD_EDIT_EXTRA_DISCOUNT_TEXT'] = 'Añadir / Editar Descuento Adicional';
$lang['NRS_ADMIN_MENU_BLOCK_EXTRA_TEXT'] = 'Bloquear extra';
// Menú Administrador - Administrador de ubicación
$lang['NRS_ADMIN_MENU_LOCATION_MANAGER_TEXT'] = 'Administrador de ubicaciones';
$lang['NRS_ADMIN_MENU_ADD_EDIT_LOCATION_TEXT'] = 'Añadir / Editar ubicación';
$lang['NRS_ADMIN_MENU_ADD_EDIT_DISTANCE_TEXT'] = 'Agregar / Editar Distancia';
// Admin Menu - Administrador de reservas
$lang['NRS_ADMIN_MENU_BOOKING_MANAGER_TEXT'] = 'Administrador de reservas';
$lang['NRS_ADMIN_MENU_BOOKING_SEARCH_RESULTS_TEXT'] = 'Resultados de la búsqueda de reservas';
$lang['NRS_ADMIN_MENU_ITEM_CALENDAR_SEARCH_RESULTS_TEXT'] = 'Resultados de la búsqueda del calendario de Carros';
$lang['NRS_ADMIN_MENU_EXTRAS_CALENDAR_SEARCH_TEXT'] = 'Resultados de la Búsqueda del Calendario de Extras';
$lang['NRS_ADMIN_MENU_CUSTOMER_SEARCH_RESULTS_TEXT'] = 'Resultados de la búsqueda del cliente';
$lang['NRS_ADMIN_MENU_ADD_EDIT_CUSTOMER_TEXT'] = 'Añadir / Editar Cliente';
$lang['NRS_ADMIN_MENU_ADD_EDIT_BOOKING_TEXT'] = 'Añadir / Editar Reservación';
$lang['NRS_ADMIN_MENU_VIEW_DETAILS_TEXT'] = 'Ver detalles de la reserva';
$lang['NRS_ADMIN_MENU_PRINT_INVOICE_TEXT'] = 'Print Invoice';
// Menú de administración - Pagos e impuestos
$lang['NRS_ADMIN_MENU_PAYMENTS_AND_TAXES_TEXT'] = 'Pagos e Impuestos';
$lang['NRS_ADMIN_MENU_ADD_EDIT_PAYMENT_METHOD_TEXT'] = 'Añadir / Editar Método de Pago';
$lang['NRS_ADMIN_MENU_ADD_EDIT_PREPAYMENT_TEXT'] = 'Añadir / Editar ypago anticipado';
$lang['NRS_ADMIN_MENU_ADD_EDIT_TAX_TEXT'] = 'Añadir / Editar Impuestos';
// Admin Menu - Configuración
$lang['NRS_ADMIN_MENU_SINGLE_MANAGER_TEXT'] = 'Configuración';
$lang['NRS_ADMIN_MENU_ADD_EDIT_GLOBAL_SETTINGS_TEXT'] = 'Añadir / Editar Ajustes Globales';
$lang['NRS_ADMIN_MENU_ADD_EDIT_CUSTOMER_SETTINGS_TEXT'] = 'Añadir / Editar configuración del cliente';
$lang['NRS_ADMIN_MENU_ADD_EDIT_SEARCH_SETTINGS_TEXT'] = 'Añadir / Editar configuración de búsqueda';
$lang['NRS_ADMIN_MENU_ADD_EDIT_PRICE_SETTINGS_TEXT'] = 'Añadir / Editar ajustes de precios';
$lang['NRS_ADMIN_MENU_ADD_EDIT_EMAIL_TEXT'] = 'Añadir / Editar correo electrónico';
$lang['NRS_ADMIN_MENU_IMPORT_DEMO_TEXT'] = 'Importar Demo';
$lang['NRS_ADMIN_MENU_CONTENT_PREVIEW_TEXT'] = 'Previsualización del contenido';
// Admin Menu - Instrucciones
$lang['NRS_ADMIN_MENU_INSTRUCTIONS_TEXT'] = 'Instrucciones';
// Menú Admin. - Administrador de red
$lang['NRS_ADMIN_MENU_NETWORK_MANAGER_TEXT'] = 'Administrador de red';

// Admin Pages Tipo de publicación
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_NAME_TEXT'] = 'Página de Alquiler'; // nombre
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_SINGULAR_NAME_TEXT'] = 'Páginas de Alquiler'; // singular_name
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_MENU_NAME_TEXT'] = 'Páginas de Alquiler'; // menu_name
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_PARENT_PAGE_COLON_TEXT'] = 'Información del Carro pricipal'; // parent_item_colon
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_ALL_PAGES_TEXT'] = 'Página de Todos los Carros'; // todos los artículos
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_VIEW_PAGE_TEXT'] = 'Página de Ver información del Carro'; // ver ítem
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_ADD_NEW_PAGE_TEXT'] = 'Página de Añadir nueva información de Carros'; // agregar ítem nuevo
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_ADD_NEW_TEXT'] = 'Añadir nueva página'; // add_new
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_EDIT_PAGE_TEXT'] = 'Página de Editar información del Carro'; // edit_item
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_UPDATE_PAGE_TEXT'] = 'Página de Actualizar información de Carros'; // update_item
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_SEARCH_PAGES_TEXT'] = 'Página de Búsqueda de información de Carros'; // search_items
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_NOT_FOUND_TEXT'] = 'No encontrado'; // not_found
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_NOT_FOUND_IN_TRASH_TEXT'] = 'No encontrado en la Papelera'; // not_found_in_trash
$lang['NRS_ADMIN_PAGE_POST_TYPE_DESCRIPTION_TEXT'] = 'Página de Lista de Información de Carros';

// Admin Item Post Type
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_NAME_TEXT'] = 'Página de Carro'; // nombre
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_SINGULAR_NAME_TEXT'] = 'Páginas de Carros'; // singular_name
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_MENU_NAME_TEXT'] = 'Páginas de Carros'; // menu_name
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_PARENT_ITEM_COLON_TEXT'] = 'Carro pricipal'; // parent_item_colon
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_ALL_ITEMS_TEXT'] = 'Página de Todos los Carros'; // all_items
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_VIEW_ITEM_TEXT'] = 'Página de Ver Carros'; // view_item
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_ADD_NEW_ITEM_TEXT'] = 'Página de Añadir nuevo Carro'; // add_new_item
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_ADD_NEW_TEXT'] = 'Añadir nueva página'; // add_new
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_EDIT_ITEM_TEXT'] = 'Página de Editar Carros'; // edit_item
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_UPDATE_ITEM_TEXT'] = 'Página de Actualizar Carros'; // update_item
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_SEARCH_ITEMS_TEXT'] = 'Página de Búsqueda de Carros'; // search_items
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_NOT_FOUND_TEXT'] = 'No encontrado'; // not_found
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_NOT_FOUND_IN_TRASH_TEXT'] = 'No encontrado en la Papelera'; // not_found_in_trash
$lang['NRS_ADMIN_ITEM_POST_TYPE_DESCRIPTION_TEXT'] = 'Página de Lista de Autos';

// Admin Ubicación Tipo de publicación
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_NAME_TEXT'] = 'Ubicación del Carro'; // nombre
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_SINGULAR_NAME_TEXT'] = 'Ubicaciones de Carros'; // singular_name
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_MENU_NAME_TEXT'] = 'Ubicaciones de Carros'; // menu_name
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_PARENT_LOCATION_COLON_TEXT'] = 'Ubicación del vehículo principal'; // parent_item_colon
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_ALL_LOCATIONS_TEXT'] = 'Página de Todas las ubicaciones de Carros'; // todos los artículos
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_VIEW_LOCATION_TEXT'] = 'Página de Ver ubicación del Carro'; // ver ítem
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_ADD_NEW_LOCATION_TEXT'] = 'Página de Añadir nueva ubicación de Carros'; // agregar ítem nuevo
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_ADD_NEW_TEXT'] = 'Añadir nueva página'; // add_new
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_EDIT_LOCATION_TEXT'] = 'Página de editar ubicación del Carro'; // edit_item
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_UPDATE_LOCATION_TEXT'] = 'Página de Actualizar ubicación del Carro'; // update_item
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_SEARCH_LOCATIONS_TEXT'] = 'Página de Búsqueda de ubicación de Carros'; // search_items
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_NOT_FOUND_TEXT'] = 'No encontrado'; // not_found
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_NOT_FOUND_IN_TRASH_TEXT'] = 'No encontrado en la Papelera'; // not_found_in_trash
$lang['NRS_ADMIN_LOCATION_POST_TYPE_DESCRIPTION_TEXT'] = 'Página de Lista de ubicación de Carros';

// Admin Core
$lang['NRS_ADMIN_EDIT_TEXT'] = 'Editar';
$lang['NRS_ADMIN_DELETE_TEXT'] = 'Borrar';
$lang['NRS_ADMIN_CANCEL_TEXT'] = 'Cancelar';
$lang['NRS_ADMIN_UNBLOCK_TEXT'] = 'Desbloquear';
$lang['NRS_ADMIN_MARK_PAID_TEXT'] = 'Marcar como Pagado';
$lang['NRS_ADMIN_MARK_COMPLETED_EARLY_TEXT'] = 'Marca finalizada antes de tiempo';
$lang['NRS_ADMIN_REFUND_TEXT'] = 'Pago reembolso';
$lang['NRS_ADMIN_SELECT_LOCATION_TEXT'] = '- Seleccionar ubicación -';
$lang['NRS_ADMIN_ALL_LOCATIONS_TEXT'] = 'Todas las ubicaciones';
$lang['NRS_ADMIN_AVAILABLE_TEXT'] = 'Disponible';
$lang['NRS_ADMIN_DISPLAYED_TEXT'] = 'Se muestra';
$lang['NRS_ADMIN_VISIBLE_TEXT'] = 'Visible';
$lang['NRS_ADMIN_HIDDEN_TEXT'] = 'Oculto';
$lang['NRS_ADMIN_ENABLED_TEXT'] = 'Activado';
$lang['NRS_ADMIN_DISABLED_TEXT'] = 'Desactivado';
$lang['NRS_ADMIN_ALLOWED_TEXT'] = 'Permitido';
$lang['NRS_ADMIN_FAILED_TEXT'] = 'Fallido';
$lang['NRS_ADMIN_BLOCKED_TEXT'] = 'Bloqueado';
$lang['NRS_ADMIN_REQUEST_TEXT'] = 'Solicitud';
$lang['NRS_ADMIN_REQUESTS_TEXT'] = 'Peticiones';
$lang['NRS_ADMIN_IP_TEXT'] = 'IP';
$lang['NRS_ADMIN_CHECK_TEXT'] = 'Check';
$lang['NRS_ADMIN_SKIP_TEXT'] = 'Skip';
$lang['NRS_ADMIN_YES_TEXT'] = 'Si';
$lang['NRS_ADMIN_NO_TEXT'] = 'No';
$lang['NRS_ADMIN_DAILY_TEXT'] = 'Diario';
$lang['NRS_ADMIN_HOURLY_TEXT'] = 'Por Hora';
$lang['NRS_ADMIN_PER_BOOKING_TEXT'] = 'Por Reserva';
$lang['NRS_ADMIN_COMBINED_TEXT'] = 'Combinado - Diario y Por Hora';
$lang['NRS_ADMIN_NEVER_TEXT'] = 'Nunca';
$lang['NRS_ADMIN_DROPDOWN_TEXT'] = 'Desplegable';
$lang['NRS_ADMIN_SLIDER_TEXT'] = 'Slider';
$lang['NRS_ADMIN_SELECT_DEMO_TEXT'] = '--- Seleccionar demostración en vivo ---';
$lang['NRS_ADMIN_WITHOUT_TRANSLATION_TEXT'] = 'Sin traducción';
$lang['NRS_ADMIN_VIEW_PAGE_IN_NEW_WINDOW_TEXT'] = 'Ver la página %s en una nueva ventana';

// Core
$lang['NRS_IMAGE_ALT_TEXT'] = 'Imagen';
$lang['NRS_PER_BOOKING_SHORT_TEXT'] = '';
$lang['NRS_PER_DAY_SHORT_TEXT'] = 'd.';
$lang['NRS_PER_HOUR_SHORT_TEXT'] = 'hr';
$lang['NRS_PER_BOOKING_TEXT'] = 'reserva';
$lang['NRS_PER_DAY_TEXT'] = 'día';
$lang['NRS_PER_HOUR_TEXT'] = 'hora';
$lang['NRS_SELECT_DATE_TEXT'] = 'Fecha';
$lang['NRS_SELECT_YEAR_TEXT'] = 'Año';
$lang['NRS_SELECT_MONTH_TEXT'] = 'Mes';
$lang['NRS_SELECT_DAY_TEXT'] = 'Día';
$lang['NRS_PRICE_TEXT'] = 'Precio';
$lang['NRS_PERIOD_TEXT'] = 'Periodo';
$lang['NRS_DURATION_TEXT'] = 'Duración:';
$lang['NRS_MON_TEXT'] = 'Lun';
$lang['NRS_TUE_TEXT'] = 'Mar';
$lang['NRS_WED_TEXT'] = 'Mie';
$lang['NRS_THU_TEXT'] = 'Jue';
$lang['NRS_FRI_TEXT'] = 'Vie';
$lang['NRS_SAT_TEXT'] = 'Sab';
$lang['NRS_SUN_TEXT'] = 'Dom';
$lang['NRS_LUNCH_TEXT'] = 'Almuerzo';
$lang['NRS_MONDAYS_TEXT'] = 'Lunes';
$lang['NRS_TUESDAYS_TEXT'] = 'Martes';
$lang['NRS_WEDNESDAYS_TEXT'] = 'Miercoles';
$lang['NRS_THURSDAYS_TEXT'] = 'Jueves';
$lang['NRS_FRIDAYS_TEXT'] = 'Viernes';
$lang['NRS_SATURDAYS_TEXT'] = 'Sábados';
$lang['NRS_SUNDAYS_TEXT'] = 'Domingos';
$lang['NRS_LUNCH_TIME_TEXT'] = 'Hora del almuerzo';
$lang['NRS_ALL_YEAR_TEXT'] = 'All Year';
$lang['NRS_ALL_DAY_TEXT'] = '00:00 - 24:00';
$lang['NRS_PARTIAL_DAY_TEXT'] = '%s - 24:00';
$lang['NRS_MIDNIGHT_TEXT'] = '00:00';
$lang['NRS_NOON_TEXT'] = '12:00';
$lang['NRS_CLOSED_TEXT'] = 'Cerrado';
$lang['NRS_OPEN_TEXT'] = 'Abierto';
$lang['NRS_TODAY_TEXT'] = 'Today';
$lang['NRS_DATE_TEXT'] = 'Fecha';
$lang['NRS_TIME_TEXT'] = 'Hora';
$lang['NRS_DAYS_TEXT'] = 'días';
$lang['NRS_DAYS2_TEXT'] = 'dias';
$lang['NRS_DAY_TEXT'] = 'día';
$lang['NRS_HOURS_TEXT'] = 'horas'.
$lang['NRS_HOURS2_TEXT'] = 'horas';
$lang['NRS_HOUR_TEXT'] = 'hora';
$lang['NRS_MINUTES_TEXT'] = 'minutos';
$lang['NRS_MINUTES2_TEXT'] = 'minutos';
$lang['NRS_MINUTE_TEXT'] = 'minuto';
$lang['NRS_DAILY_TEXT'] = 'Diario';
$lang['NRS_HOURLY_TEXT'] = 'Por Hora';
$lang['NRS_ON_ST_TEXT'] = 'ero';
$lang['NRS_ON_ND_TEXT'] = 'do';
$lang['NRS_ON_RD_TEXT'] = 'ero';
$lang['NRS_ON_TH_TEXT'] = 'to';
$lang['NRS_ON_TEXT'] = 'en'; // en
$lang['NRS_THE_ST_TEXT'] = 'ero';
$lang['NRS_THE_ND_TEXT'] = 'do';
$lang['NRS_THE_RD_TEXT'] = 'ero';
$lang['NRS_THE_TH_TEXT'] = 'ro';
$lang['NRS_UNCLASSIFIED_ITEM_TYPE_TEXT'] = 'Otro';
$lang['NRS_NO_ITEMS_AVAILABLE_TEXT'] = 'No hay Carros disponibles';
$lang['NRS_NO_ITEMS_AVAILABLE_IN_THIS_CLASS_TEXT'] = 'No hay Carros disponibles de esta clase';
$lang['NRS_NO_EXTRAS_AVAILABLE_TEXT'] = 'No hay extras disponibles';
$lang['NRS_NO_MANUFACTURERS_AVAILABLE_TEXT'] = 'No manufacturers available';
$lang['NRS_NO_LOCATIONS_AVAILABLE_TEXT'] = 'No locations available';
$lang['NRS_NO_BENEFITS_AVAILABLE_TEXT'] = 'No benefits available';
$lang['NRS_NA_TEXT'] = 'N / A';
$lang['NRS_NONE_TEXT'] = 'Ninguno';
$lang['NRS_NOT_SET_TEXT'] = 'No establecido';
$lang['NRS_DO_NOT_EXIST_TEXT'] = 'No existe';
$lang['NRS_EXIST_TEXT'] = 'Existe';
$lang['NRS_NOT_REQ_TEXT'] = 'Not req.';
$lang['NRS_REQ_TEXT'] = 'Req.';
$lang['NRS_NOT_REQUIRED_TEXT'] = 'No requerido';
$lang['NRS_REQUIRED_TEXT'] = 'Requerido';
$lang['NRS_DONT_DISPLAY_TEXT'] = 'No mostrar';
$lang['NRS_WITH_TAX_TEXT'] = 'con IVA';
$lang['NRS_WITHOUT_TAX_TEXT'] = 'sin IVA';
$lang['NRS_TAX_TEXT'] = 'IVA';
$lang['NRS_FROM_TEXT'] = 'Desde';
$lang['NRS_TO_TEXT'] = 'Hasta';
$lang['NRS_ALL_TEXT'] = 'All';
$lang['NRS_OR_TEXT'] = 'O';
$lang['NRS_AND_TEXT'] = 'y';
$lang['NRS_UNLIMITED_TEXT'] = 'Ilimitado';
$lang['NRS_DEPOSIT_TEXT'] = 'Depósito';
$lang['NRS_DISCOUNT_TEXT'] = 'Descuento';
$lang['NRS_PREPAYMENT_TEXT'] = 'Monto de ypago anticipado';
$lang['NRS_TOTAL_TEXT'] = 'Total';
$lang['NRS_BACK_TEXT'] = 'Atrás';
$lang['NRS_CONTINUE_TEXT'] = 'Continuar';
$lang['NRS_SEARCH_TEXT'] = 'Buscar';
$lang['NRS_SELECT_DROPDOWN_TEXT'] = '--- Seleccionar ---';
$lang['NRS_ITEM_TEXT'] = 'Carro';
$lang['NRS_EXTRA_TEXT'] = 'Extra';
$lang['NRS_RENTAL_OPTION_TEXT'] = 'Opciones de Alquiler';
$lang['NRS_ITEMS_TEXT'] = 'Carros';
$lang['NRS_EXTRAS_TEXT'] = 'Extras';
$lang['NRS_RENTAL_OPTIONS_TEXT'] = 'Opciones de Alquiler';
$lang['NRS_SHOW_ITEM_TEXT'] = 'Ver Carro';
$lang['NRS_VIA_PARTNER_TEXT'] = 'a través de %s';
$lang['NRS_COUPON_TEXT'] = 'Cupón';

// Etapa de reserva no. 1 - búsqueda de artículos
$lang['NRS_BOOKING_TEXT'] = 'Reservación';
$lang['NRS_PICKUP_TEXT'] = 'Recogida';
$lang['NRS_RETURN_TEXT'] = 'Devolución';
$lang['NRS_OTHER_TEXT'] = 'Tipo de Carro';
$lang['NRS_INFORMATION_TEXT'] = 'Información';
$lang['NRS_CITY_AND_LOCATION_TEXT'] = 'Ciudad y Ubicación: ';
$lang['NRS_PICKUP_CITY_AND_LOCATION_TEXT'] = 'Ciudad de Recogida y Ubicación: ';
$lang['NRS_RETURN_CITY_AND_LOCATION_TEXT'] = 'Ciudad de Devolución y Ubicación: ';
$lang['NRS_SELECT_BOOKING_DATE_TEXT'] = 'Fecha:';
$lang['NRS_SELECT_BOOKING_PERIOD_TEXT'] = 'Periodo de Reserva:';
$lang['NRS_COUPON_CODE_TEXT'] = 'Código de cupón';
$lang['NRS_I_HAVE_BOOKING_CODE_TEXT'] = 'Tengo un número de reserva:';
$lang['NRS_I_HAVE_COUPON_CODE_TEXT'] = 'Tengo código de cupón:';
$lang['NRS_PICKUP_LOCATION_TEXT'] = 'Ubicación de Recogida';
$lang['NRS_RETURN_LOCATION_TEXT'] = 'Ubicación de Devolución';
$lang['NRS_ALL_BODY_TYPES_DROPDOWN_TEXT'] = '---- Todos los Tipos ----';
$lang['NRS_ALL_TRANSMISSION_TYPES_DROPDOWN_TEXT'] = '---- Todas las Transmisiones ----';
$lang['NRS_SELECT_PICKUP_LOCATION_TEXT'] = '- Seleccione el Lugar de Recogida -';
$lang['NRS_SELECT_RETURN_LOCATION_TEXT'] = '- Seleccione el Lugar de Devolución -';
$lang['NRS_PICKUP_DATE_TEXT'] = 'Fecha de Recogida';
$lang['NRS_RETURN_DATE_TEXT'] = 'Fecha de Devolución';
$lang['NRS_PICKUP_DATE_ALERT_TEXT'] = 'Por favor, Ingrese la fecha de Llegada!';
$lang['NRS_RETURN_DATE_ALERT_TEXT'] = 'Por favor, Ingrese la Fecha de Salida!';
$lang['NRS_BOOKING_PERIOD_ALERT_TEXT'] = 'Por favor, Ingrese la Fecha de Salida!';
$lang['NRS_PICKUP_LOCATION_ALERT_TEXT'] = 'Por favor, seleccione el lugar de encuentro!';
$lang['NRS_RETURN_LOCATION_ALERT_TEXT'] = 'Por favor, seleccione el lugar de Devolución!';
$lang['NRS_COUPON_CODE_ALERT_TEXT'] = '¡Por favor, ingrese el código de cupón!';
$lang['NRS_SHOW_ITEM_DESCRIPTION_TEXT'] = 'Mostrar descripción del carro';
$lang['NRS_UPDATE_BOOKING_TEXT'] = 'Actualizar mi reserva';
$lang['NRS_CANCEL_BOOKING_TEXT'] = 'Cancelar Reserva';
$lang['NRS_CHANGE_BOOKING_DATE_TIME_AND_LOCATION_TEXT'] = 'Cambiar Fecha, Hora y Ubicación ';
$lang['NRS_CHANGE_BOOKED_ITEMS_TEXT'] = 'Cambiar Carros';
$lang['NRS_CHANGE_EXTRAS_TEXT'] = 'Cambiar Extras';
$lang['NRS_CHANGE_RENTAL_OPTIONS_TEXT'] = 'Cambiar opciones de alquiler';
$lang['NRS_IN_THIS_LOCATION_TEXT'] = 'En esta ubicación';
$lang['NRS_AFTERHOURS_PICKUP_IS_NOT_ALLOWED_TEXT'] = 'No Permitido';
$lang['NRS_AFTERHOURS_RETURN_IS_NOT_ALLOWED_TEXT'] = 'No Permitido';

// Etapa de reserva no. 2 - resultados de búsqueda
$lang['NRS_DISTANCE_AWAY_TEXT'] = '%s lejos';
$lang['NRS_BOOKING_DATA_TEXT'] = 'Detalles de la Reserva';
$lang['NRS_BOOKING_CODE_TEXT'] = 'Código de la Reserva';
$lang['NRS_BOOKING_EDIT_TEXT'] = 'editar';
$lang['NRS_BOOKING_PICKUP_TEXT'] = 'Recogida';
$lang['NRS_BOOKING_BUSINESS_HOURS_TEXT'] = 'Horas Laborales';
$lang['NRS_BOOKING_FEE_TEXT'] = 'Recargo';
$lang['NRS_BOOKING_RETURN_TEXT'] = 'Regreso';
$lang['NRS_BOOKING_NIGHTLY_RATE_TEXT'] = 'Horario Adicional';
$lang['NRS_BOOKING_AFTERHOURS_TEXT'] = 'Horario Adicional';
$lang['NRS_BOOKING_EARLY_TEXT'] = 'Antes de hora';
$lang['NRS_BOOKING_LATE_TEXT'] = 'Llegada tarde';
$lang['NRS_BOOKING_AFTERHOURS_PICKUP_TEXT'] = 'Horario adicional de Recogida';
$lang['NRS_BOOKING_AFTERHOURS_PICKUP_IMPOSSIBLE_TEXT'] = 'Imposible';
$lang['NRS_BOOKING_AFTERHOURS_RETURN_TEXT'] = 'Horario Adicional de Entrega';
$lang['NRS_BOOKING_AFTERHOURS_RETURN_IMPOSSIBLE_TEXT'] = 'Imposible';
$lang['NRS_CHOOSE_TEXT'] = 'Escoger';
$lang['NRS_SEARCH_RESULTS_TEXT'] = 'Buscar Resultado';
$lang['NRS_MILEAGE_TEXT'] = 'Millaje';

// Etapa de reserva no. 3 - opciones de reserva
$lang['NRS_SELECT_RENTAL_OPTIONS_TEXT'] = 'Escoger Opciones de alquiler';
$lang['NRS_SELECTED_ITEMS_TEXT'] = 'Carros seleccionados';
$lang['NRS_FOR_DEPENDANT_ITEM_TEXT'] = '(para %s)';
$lang['NRS_NO_EXTRAS_AVAILABLE_CLICK_CONTINUE_TEXT'] = 'No hay extras disponibles. Haga clic en el botón Continuar';

// Etapa de reserva no. 4 - detalles de la reserva
$lang['NRS_PICKUP_DATE_AND_TIME_TEXT'] = 'Fecha de Recogida y Hora ';
$lang['NRS_RETURN_DATE_AND_TIME_TEXT'] = 'Fecha de Devolución y Hora ';
$lang['NRS_UNIT_PRICE_TEXT'] = 'Precio Unitario';
$lang['NRS_QUANTITY_TEXT'] = 'Cantidad';
$lang['NRS_QUANTITY_SHORT_TEXT'] = 'Cant.';
$lang['NRS_PICKUP_FEE_TEXT'] = 'Recargo por Recogida';
$lang['NRS_RETURN_FEE_TEXT'] = 'Recargo por Devolución';
$lang['NRS_NIGHTLY_PICKUP_RATE_APPLIED_TEXT'] = '(Recargo Nocturno)';
$lang['NRS_NIGHTLY_RETURN_RATE_APPLIED_TEXT'] = '(Recargo Nocturno)';
$lang['NRS_ITEM_QUANTITY_SUFFIX_TEXT'] = 'vehículo (s)';
$lang['NRS_EXTRA_QUANTITY_SUFFIX_TEXT'] = 'extra (s)';
$lang['NRS_PAY_NOW_OR_AT_PICKUP_TEXT'] = 'Pagar Ahora / al Recoger';
$lang['NRS_PAY_NOW_TEXT'] = 'Pagar Ahora';
$lang['NRS_PAY_AT_PICKUP_TEXT'] = 'Pagar al Recoger';
$lang['NRS_PAY_LATER_OR_ON_RETURN_TEXT'] = 'Pagar después / al regresar';
$lang['NRS_PAY_LATER_TEXT'] = 'Pagar más tarde';
$lang['NRS_PAY_ON_RETURN_TEXT'] = 'Pagar a la vuelta';
$lang['NRS_ITEM_RENTAL_DETAILS_TEXT'] = 'Detalles del alquiler';
$lang['NRS_MANUFACTURER_TEXT'] = 'Fabricante';
$lang['NRS_ITEM_MODEL_TEXT'] = 'Modelo de Carro';
$lang['NRS_GROSS_TOTAL_TEXT'] = 'Sub Total';
$lang['NRS_GRAND_TOTAL_TEXT'] = 'Gran Total';
$lang['NRS_BOOKING_DETAILS_TEXT'] = 'Datos de la Reserva';
$lang['NRS_CUSTOMER_DETAILS_TEXT'] = 'Datos del Cliente';
$lang['NRS_EXISTING_CUSTOMER_DETAILS_TEXT'] = 'Datos de cliente existentes';
$lang['NRS_EXISTING_CUSTOMER_TEXT'] = 'Cliente existente';
$lang['NRS_EMAIL_ADDRESS_TEXT'] = 'Correo electrónico';
$lang['NRS_FETCH_CUSTOMER_DETAILS_TEXT'] = 'Obtener los datos';
$lang['NRS_OR_ENTER_NEW_DETAILS_TEXT'] = 'O introduzca nuevos datos';
$lang['NRS_CUSTOMER_TEXT'] = 'Cliente';
$lang['NRS_TITLE_TEXT'] = 'Tratamiento';
$lang['NRS_MR_TEXT'] = 'Sr';
$lang['NRS_MS_TEXT'] = 'Don';
$lang['NRS_MRS_TEXT'] = 'Sra';
$lang['NRS_MISS_TEXT'] = 'Srta';
$lang['NRS_DR_TEXT'] = 'Dr';
$lang['NRS_PROF_TEXT'] = 'Prof';
$lang['NRS_FIRST_NAME_TEXT'] = 'Nombres';
$lang['NRS_LAST_NAME_TEXT'] = 'Apellidos';
$lang['NRS_DATE_OF_BIRTH_TEXT'] = 'Fecha de Nacimiento';
$lang['NRS_YEAR_OF_BIRTH_TEXT'] = 'Año de Nacimiento';
$lang['NRS_ADDRESS_TEXT'] = 'Dirección';
$lang['NRS_STREET_ADDRESS_TEXT'] = 'Dirección';
$lang['NRS_CITY_TEXT'] = 'Ciudad';
$lang['NRS_STATE_TEXT'] = 'Estado';
$lang['NRS_ZIP_CODE_TEXT'] = 'Código Postal';
$lang['NRS_COUNTRY_TEXT'] = 'País';
$lang['NRS_PHONE_TEXT'] = 'Teléfono';
$lang['NRS_EMAIL_TEXT'] = 'Correo electrónico';
$lang['NRS_ADDITIONAL_COMMENTS_TEXT'] = 'Comentarios Adicionales';
$lang['NRS_CUSTOMER_ID_TEXT'] = 'ID de Cliente';
$lang['NRS_IP_ADDRESS_TEXT'] = 'Dirección IP';
$lang['NRS_PAY_BY_SHORT_TEXT'] = 'Pagado por';
$lang['NRS_I_AGREE_WITH_TERMS_AND_CONDITIONS_TEXT'] = 'Estoy de acuerdo con los términos y Condiciones ';
$lang['NRS_TERMS_AND_CONDITIONS_TEXT'] = 'Términos y Condiciones ';
$lang['NRS_CONFIRM_TEXT'] = 'Confirmar';
$lang['NRS_FIELD_REQUIRED_TEXT'] = 'Este campo es requerido';

// Etapa de reserva no. 5 - proceso de reserva
$lang['NRS_PAYMENT_DETAILS_TEXT'] = 'Detalles del Pago';
$lang['NRS_PAYMENT_OPTION_TEXT'] = 'Pagado por';
$lang['NRS_PAYER_EMAIL_TEXT'] = 'E-Mail del Pagador';
$lang['NRS_TRANSACTION_ID_TEXT'] = 'ID de transacción';
$lang['NRS_PROCESSING_PAYMENT_TEXT'] = 'Pago procesandose ...';
$lang['NRS_PLEASE_WAIT_UNTIL_PAYMENT_WILL_BE_PROCESSED_TEXT'] = 'Por favor espere, su pedido será procesado ...';

//display-booking-confirm.php
$lang['NRS_STEP5_PAY_ONLINE_TEXT'] = 'Pagado en Línea';
$lang['NRS_STEP5_PAY_AT_PICKUP_TEXT'] = 'Pagar al Recoger';
$lang['NRS_THANK_YOU_TEXT'] = 'Gracias!';
$lang['NRS_YOUR_BOOKING_CONFIRMED_TEXT'] = 'Reserva confirmada. [BOOKING_CODE]';
$lang['NRS_INVOICE_SENT_TO_YOUR_EMAIL_ADDRESS_TEXT'] = 'Factura enviada a su e-mail';

//display-booking-failure.php
$lang['NRS_BOOKING_FAILURE_TEXT'] = 'Falla en la Reserva';
$lang['NRS_BOOKING_FAILURE_SEARCH_ALL_ITEMS_TEXT'] = 'Buscar todos los Carros';

// display-item-price-table.php
$lang['NRS_DAY_PRICE_TEXT'] = 'Precio diario';
$lang['NRS_HOUR_PRICE_TEXT'] = 'Precio por hora';
$lang['NRS_NO_ITEMS_IN_THIS_CATEGORY_TEXT'] = 'No hay Carros en esta categoría';
$lang['NRS_PRICE_FOR_DAY_FROM_TEXT'] = 'Precio por un día a partir de';
$lang['NRS_PRICE_FOR_HOUR_FROM_TEXT'] = 'Precio por hora a partir de';
$lang['NRS_PRICE_WITH_APPLIED_TEXT'] = 'con opciones';
$lang['NRS_WITH_APPLIED_DISCOUNT_TEXT'] = 'descuento';

// class.ItemsAvailability.php
$lang['NRS_MONTH_DAY_TEXT'] = 'Día';
$lang['NRS_MONTH_DAYS_TEXT'] = 'Días';
$lang['NRS_ITEMS_AVAILABILITY_FOR_TEXT'] = 'Carros disponibles para';
$lang['NRS_ITEMS_AVAILABILITY_IN_NEXT_30_DAYS_TEXT'] = 'Disponibilidad de Carros en los próximos 30 días';
$lang['NRS_ITEMS_PARTIAL_AVAILABILITY_FOR_TEXT'] = 'Disponibilidad parcial de Carros para';
$lang['NRS_ITEMS_AVAILABILITY_THIS_MONTH_TEXT'] = 'Carros disponibles este mes'; // No utilizado
$lang['NRS_ITEMS_AVAILABILITY_NEXT_MONTH_TEXT'] = 'Carros disponibles el próximo mes'; // No utilizado
$lang['NRS_ITEM_ID_TEXT'] = 'ID:';
$lang['NRS_TOTAL_ITEMS_TEXT'] = 'Total de Vehículos:';

// class.ExtrasAvailability.php
$lang['NRS_EXTRAS_AVAILABILITY_FOR_TEXT'] = 'Extras disponibles para';
$lang['NRS_EXTRAS_AVAILABILITY_IN_NEXT_30_DAYS_TEXT'] = 'Disponibilidad de extras en los próximos 30 días';
$lang['NRS_EXTRAS_PARTIAL_AVAILABILITY_FOR_TEXT'] = 'Disponibilidad parcial de los extras';
$lang['NRS_EXTRAS_AVAILABILITY_THIS_MONTH_TEXT'] = 'Extras disponibles este mes'; // No utilizado
$lang['NRS_EXTRAS_AVAILABILITY_NEXT_MONTH_TEXT'] = 'Extras disponibles el próximo mes'; // No utilizado
$lang['NRS_EXTRA_ID_TEXT'] = 'ID';
$lang['NRS_TOTAL_EXTRAS_TEXT'] = 'Total de extras:';

// class.ItemsController.php
$lang['NRS_SHOW_ITEM_PAGE_TEXT'] = 'Mostrar descripción del Carro';
$lang['NRS_PARTNER_TEXT'] = 'Socio';
$lang['NRS_BODY_TYPE_TEXT'] = 'Tipo';
$lang['NRS_TRANSMISSION_TYPE_TEXT'] = 'Caja de cambios';
$lang['NRS_FUEL_TYPE_TEXT'] = 'Combustible';
$lang['NRS_ITEM_FUEL_CONSUMPTION_TEXT'] = 'Consumo de Combustible';
$lang['NRS_ITEM_PASSENGERS_TEXT'] = 'Capacidad de Pasajeros';
$lang['NRS_ITEM_PRICE_FROM_TEXT'] = 'Precio desde';
$lang['NRS_INQUIRE_TEXT'] = 'Call';
$lang['NRS_GET_A_QUOTE_TEXT'] = 'Obtener una cotización';
$lang['NRS_ITEM_FEATURES_TEXT'] = 'Características';
$lang['NRS_BOOK_ITEM_TEXT'] = 'Reserva';

// class.LocationsController.php
$lang['NRS_LOCATIONS_BUSINESS_HOURS_TEXT'] = 'Horario de trabajo';
$lang['NRS_LOCATION_FEES_TEXT'] = 'Recargo por Ubicación';
$lang['NRS_EARLY_PICKUP_TEXT'] = 'Recogida antes de hora';
$lang['NRS_LATE_PICKUP_TEXT'] = 'Recogida después de hora';
$lang['NRS_EARLY_RETURN_TEXT'] = 'Retorno antes de hora';
$lang['NRS_LATE_RETURN_TEXT'] = 'Retorno después de hora';
$lang['NRS_EARLY_PICKUP_FEE_TEXT'] = 'Recargo por Retorno antes de hora';
$lang['NRS_LATE_RETURN_FEE_TEXT'] = 'Recargo por Retorno después de hora';
$lang['NRS_VIEW_LOCATION_TEXT'] = 'Ver Ubicación';

// class.SingleItemController.php
$lang['NRS_ITEM_ENGINE_CAPACITY_TEXT'] = 'Capacidad del Motor';
$lang['NRS_ITEM_LUGGAGE_TEXT'] = 'Equipaje Máximo';
$lang['NRS_ITEM_DOORS_TEXT'] = 'Puertas';
$lang['NRS_ITEM_DRIVER_AGE_TEXT'] = 'Edad mínima del conductor';
$lang['NRS_ADDITIONAL_INFORMATION_TEXT'] = 'Información Adicional';

// class.SingleLocationController.php
$lang['NRS_CONTACTS_TEXT'] = 'Contacts';
$lang['NRS_CONTACT_DETAILS_TEXT'] = 'Contact Details';
$lang['NRS_BUSINESS_HOURS_FEES_TEXT'] = 'Business Hours Fees';
$lang['NRS_AFTERHOURS_FEES_TEXT'] = 'After Hours Fees';

// template.BookingCancelled.php
$lang['NRS_CANCELLED_SUCCESSFULLY_TEXT'] = 'Cancelado exitósamente';
$lang['NRS_NOT_CANCELLED_TEXT'] = 'La reserva no se ha cancelado';

// template.Step8EditBooking.php
$lang['NRS_EDIT_TEXT'] = 'Cambiar';
$lang['NRS_BOOKING2_TEXT'] = 'reservación';
$lang['NRS_EDIT_BOOKING_BUTTON_TEXT'] = 'Cambiar reservación';
$lang['NRS_EDIT_BOOKING_PLEASE_ENTER_BOOKING_NUMBER_TEXT'] = 'Por favor, introduzca el número de reserva!';

// Errores del sistema de administración
// Desafortunadamente, son intraducibles
$lang['NRS_ERROR_IN_METHOD_TEXT'] = 'Error en el método%s:';

// Excepciones
$lang['NRS_ERROR_CANNOT_BIND_TEMPLATE_VARIABLE_TEXT'] = 'No se puede enlazar la variable llamada &#39;templateFile&#39;.';
$lang['NRS_ERROR_TEMPLATE_NOT_EXIST_TEXT'] = 'El archivo de plantilla%s no existe.';

// Errores
$lang['NRS_ERROR_EXTENSION_NAME_TEXT'] = 'Car Rental System';
$lang['NRS_ERROR_REQUIRED_FIELD_TEXT'] = 'Campo requerido';
$lang['NRS_ERROR_IS_EMPTY_TEXT'] = 'está vacio';
$lang['NRS_ERROR_SLIDER_CANT_BE_DISPLAYED_TEXT'] = 'No se puede mostrar el slider';
$lang['NRS_ERROR_CUSTOMER_DETAILS_NOT_FOUND_TEXT'] = 'Detalles no encontrados. Por favor regístrese ';
$lang['NRS_ERROR_CUSTOMER_DETAILS_NO_ERROR_TEXT'] = 'No hay errores';
$lang['NRS_ERROR_EXCEEDED_CUSTOMER_LOOKUP_ATTEMPTS_TEXT'] = 'Ha superado los intentos de búsqueda de detalles de clientes. Introduzca sus datos manualmente en el siguiente formulario. ';
$lang['NRS_ERROR_CUSTOMER_DETAILS_UNKNOWN_ERROR_TEXT'] = 'error desconocido';
$lang['NRS_ERROR_BOOKING_DOES_NOT_EXIST_TEXT'] = 'No existe';
$lang['NRS_ERROR_PLEASE_SELECT_AT_LEAST_ONE_ITEM_TEXT'] = 'Por favor, seleccione al menos un Carro';
$lang['NRS_ERROR_ITEM_NOT_AVAILABLE_TEXT'] = 'Este Carro no está disponible.';
$lang['NRS_ERROR_NO_ITEM_AVAILABLE_PLEASE_TRY_DIFFERENT_DATE_TEXT'] = 'No hay Carros disponibles. Por favor, modifique los criterios de búsqueda. ';
$lang['NRS_ERROR_SEARCH_ENGINE_DISABLED_TEXT'] = 'Lo sentimos la reserva online no está disponible. Por favor intente mas tarde. ';
$lang['NRS_ERROR_OUT_BEFORE_IN_TEXT'] = 'Lo sentimos ha ingresado en un criterio de búsqueda no válido. Por favor, intente con los criterios de búsqueda válidos';
$lang['NRS_ERROR_MINIMUM_NUMBER_OF_NIGHT_SHOULD_NOT_BE_LESS_THAN_TEXT'] = 'El número mínimo de noches no debe ser inferior a';
$lang['NRS_ERROR_PLEASE_MODIFY_YOUR_SEARCH_CRITERIA_TEXT'] = 'Modificar sus criterios de búsqueda.';
$lang['NRS_ERROR_PICKUP_IS_NOT_POSSIBLE_ON_TEXT'] = 'La recogida es imposible en';
$lang['NRS_ERROR_PLEASE_MODIFY_YOUR_PICKUP_TIME_BY_WEBSITE_TIME_TEXT'] = 'Por favor modifique su criterio de búsqueda de acuerdo a la reserva de ubicación, fecha y Hora';
$lang['NRS_ERROR_CURRENT_DATE_TIME_TEXT'] = 'La ubicación actual de la renta en fecha y Hora es ';
$lang['NRS_ERROR_EARLIEST_POSSIBLE_PICKUP_DATE_TIME_TEXT'] = 'La fecha más reciente de reconocimiento y Hora es ';
$lang['NRS_ERROR_OR_NEXT_BUSINESS_HOURS_OF_PICKUP_LOCATION_TEXT'] = 'o la primera vez después, cuando la ubicación de recogida seleccionada está abierta';
$lang['NRS_ERROR_PICKUP_DATE_CANT_BE_LESS_THAN_RETURN_DATE_TEXT'] = 'La fecha de entrega del Vehículo Y Hora no puede ser anterior a la fecha y hora de su Devolución. Por Favor marca una fecha y hora correctas, de entrega y devolución ';
$lang['NRS_ERROR_PICKUP_LOCATION_IS_CLOSED_AT_THIS_DATE_TEXT'] = 'El lugar de entrega está cerrado en esta fecha (%s).';
$lang['NRS_ERROR_PICKUP_LOCATION_IS_CLOSED_AT_THIS_TIME_TEXT'] = 'El lugar de entrega está en esta ubicación (%s).';
$lang['NRS_ERROR_RETURN_LOCATION_IS_CLOSED_AT_THIS_DATE_TEXT'] = 'El lugar de la devolución%s en esta ubicación%s está cerrado en esta fecha (%s).';
$lang['NRS_ERROR_RETURN_LOCATION_IS_CLOSED_AT_THIS_TIME_TEXT'] = 'El lugar de la devolución en esta ubicación es cerrado a esta hora (%s).';
$lang['NRS_ERROR_AFTERHOURS_PICKUP_LOCATION_IS_CLOSED_AT_THIS_TIME_TEXT'] = 'Discúlpenos. El lugar de entrega en horario adicional es en esta ubicación.';
$lang['NRS_ERROR_AFTERHOURS_RETURN_LOCATION_IS_CLOSED_AT_THIS_TIME_TEXT'] = 'El lugar de devolución en horario adicional es en esta ubicación.';
$lang['NRS_ERROR_LOCATION_OPEN_HOURS_ARE_TEXT'] = 'El horario de atención en esta ubicación de %s,%s es de%s.';
$lang['NRS_ERROR_LOCATION_WEEKLY_OPEN_HOURS_ARE_TEXT'] = 'Esta ubicación abre horas durante la semana son:';
$lang['NRS_ERROR_AFTERHOURS_PICKUP_LOCATION_OPEN_HOURS_ARE_TEXT'] = 'El horario adicional en esta ubicación para recoger un vehículo empieza a funcionar a las %s.';
$lang['NRS_ERROR_AFTERHOURS_RETURN_LOCATION_OPEN_HOURS_ARE_TEXT'] = 'El horario adicional en esta ubicación para devolver un vehículo empieza a funcionar a las %s.';
$lang['NRS_ERROR_AFTERHOURS_PICKUP_IS_NOT_ALLOWED_AT_LOCATION_TEXT'] = 'El horario adicional recoger un vehículo no está habilitado en esta ubicación.';
$lang['NRS_ERROR_AFTERHOURS_RETURN_IS_NOT_ALLOWED_AT_LOCATION_TEXT'] = 'El horario adicional para devolver un vehículo no está habilitado en esta ubicación.';
$lang['NRS_ERROR_MAXIMUM_BOOKING_LENGTH_CANT_BE_MORE_THAN_TEXT'] = 'La Reservación de cualquier vehículo no podrá superar los (número de días)';
$lang['NRS_ERROR_INVALID_BOOKING_CODE_TEXT'] = 'Identificación de la reserva no válida o reserva ya no existe.';
$lang['NRS_ERROR_INVALID_SECURITY_CODE_TEXT'] = 'El código de seguridad no es válido.';
$lang['NRS_ERROR_DRIVER_AGE_VIOLATION_FOR_ITEM_TEXT'] = 'Based on your birthdate, your age does not match the minimum driver age requirement for %s.';
$lang['NRS_ERROR_DRIVER_AGE_VIOLATION_TEXT'] = 'Based on your birthdate, your age does not match the minimum driver age requirement for one of selected cars.';
$lang['NRS_ERROR_DEPARTED_TEXT'] = 'Reserva No. %s está marcada como expirada, y no está disponible para futura edición.';
$lang['NRS_ERROR_CANCELLED_TEXT'] = 'Reserva No.%s ha sido cancelada.';
$lang['NRS_ERROR_REFUNDED_TEXT'] = 'Reserva No. %s fue devuelta y no está disponible';
$lang['NRS_ERROR_SYSTEM_IS_UNABLE_TO_SEND_EMAIL_TEXT'] = 'Error: El sistema no puede enviar un correo electrónico de confirmación. La configuración del correo electrónico no es la adecuada, la dirección de correo electrónico del cliente no es correcta. ';
$lang['NRS_ERROR_PAYMENT_METHOD_IS_NOT_YET_IMPLEMENTED_TEXT'] = 'Error: Usted reintenta pagar con un método de pago, que no está disponible todavía para su uso en el sistema.';
$lang['NRS_ERROR_OTHER_BOOKING_ERROR_TEXT'] = 'Otro error en la reserva. Si sigue viendo este mensaje, póngase en contacto con el administrador del sitio web.';

// Administrador de descuento del controlador
$lang['NRS_ADMIN_DISCOUNT_ITEM_BOOKING_IN_ADVANCE_TEXT'] = 'Añadir / Editar Descuento de Carro para reservar con antelación';
$lang['NRS_ADMIN_DISCOUNT_ITEM_BOOKING_DURATION_TEXT'] = 'Añadir / Editar Descuento de Carro para la duración de la reserva';
$lang['NRS_ADMIN_DISCOUNT_EXTRA_BOOKING_IN_ADVANCE_TEXT'] = 'Añadir / Editar Descuento Adicional para Reservas por adelantado';
$lang['NRS_ADMIN_DISCOUNT_EXTRA_BOOKING_DURATION_TEXT'] = 'Añadir / Editar Descuento Extra para la Duración de la Reserva';
$lang['NRS_ADMIN_DISCOUNT_DURATION_BEFORE_TEXT'] = 'Duración antes:';
$lang['NRS_ADMIN_DISCOUNT_DURATION_UNTIL_TEXT'] = 'Duración hasta:';
$lang['NRS_ADMIN_DISCOUNT_DURATION_FROM_TEXT'] = 'Duración de:';
$lang['NRS_ADMIN_DISCOUNT_DURATION_TILL_TEXT'] = 'Duración Hasta:';

// Ajustes administrativos Controlador
$lang['NRS_ADMIN_SETTINGS_OKAY_GLOBAL_SETTINGS_UPDATED_TEXT'] = 'Completado: Configuración global actualizada con éxito!';
$lang['NRS_ADMIN_SETTINGS_OKAY_CUSTOMER_SETTINGS_UPDATED_TEXT'] = 'Completado: Configuración del cliente actualizada con éxito!';
$lang['NRS_ADMIN_SETTINGS_OKAY_SEARCH_SETTINGS_UPDATED_TEXT'] = 'Completado: Configuración de búsqueda actualizada con éxito!';
$lang['NRS_ADMIN_SETTINGS_OKAY_PRICE_SETTINGS_UPDATED_TEXT'] = 'Completado: Configuración de precios actualizada con éxito!';

// OK / Mensajes de error - Elemento de beneficio
$lang['NRS_BENEFIT_TITLE_EXISTS_ERROR_TEXT'] = 'Error: Beneficio con este título ya existe!';
$lang['NRS_BENEFIT_UPDATE_ERROR_TEXT'] = 'Error: Error de actualización de MySQL para beneficio existente!';
$lang['NRS_BENEFIT_UPDATED_TEXT'] = 'Completado: El beneficio se ha actualizado con éxito!';
$lang['NRS_BENEFIT_INSERT_ERROR_TEXT'] = 'Error: error de inserción de MySQL para el nuevo beneficio!';
$lang['NRS_BENEFIT_INSERTED_TEXT'] = 'Completado: Se ha agregado un nuevo beneficio con éxito!';
$lang['NRS_BENEFIT_REGISTERED_TEXT'] = 'Título de beneficio registrado para la traducción.';
$lang['NRS_BENEFIT_DELETE_ERROR_TEXT'] = 'Error: error de eliminación de MySQL para el beneficio existente. No se eliminaron filas de la base de datos! ';
$lang['NRS_BENEFIT_DELETED_TEXT'] = 'Completado: El beneficio se ha eliminado con éxito!';

// OK / Mensajes de error - Elemento del bloque
$lang['NRS_BLOCK_INSERT_ERROR_TEXT'] = 'Error: error de inserción de MySQL para el nuevo bloque!';
$lang['NRS_BLOCK_INSERTED_TEXT'] = 'Completado: Nuevo bloque ha sido añadido con éxito!';
$lang['NRS_BLOCK_DELETE_ERROR_TEXT'] = 'Error: MySQL delete error for existing block. No rows were deleted from database!';
$lang['NRS_BLOCK_DELETED_TEXT'] = 'Completed: Block has been deleted successfully!';
$lang['NRS_BLOCK_DELETE_OPTIONS_ERROR_TEXT'] = 'Failed: No cars or extras were deleted from block!';
$lang['NRS_BLOCK_OPTIONS_DELETED_TEXT'] = 'Completed: All cars and extras were deleted from block!';

// OK / Mensajes de error - Elemento del tipo de carrocería
$lang['NRS_BODY_TYPE_TITLE_EXISTS_ERROR_TEXT'] = 'Error: El tipo de carrocería con este título ya existe!';
$lang['NRS_BODY_TYPE_UPDATE_ERROR_TEXT'] = 'Error: Error de actualización de MySQL para el tipo de carrocería existente!';
$lang['NRS_BODY_TYPE_UPDATED_TEXT'] = 'Completado: El tipo de carrocería se ha actualizado con éxito!';
$lang['NRS_BODY_TYPE_INSERT_ERROR_TEXT'] = 'Error: error de inserción de MySQL para nuevo tipo de carrocería!';
$lang['NRS_BODY_TYPE_INSERTED_TEXT'] = 'Completado: Nuevo tipo de carrocería ha sido añadido con éxito!';
$lang['NRS_BODY_TYPE_REGISTERED_TEXT'] = 'Título del tipo de carrocería registrado para traducción.';
$lang['NRS_BODY_TYPE_DELETE_ERROR_TEXT'] = 'Error: error de eliminación de MySQL para tipo de carrocería existente. No se eliminaron filas de la base de datos! ';
$lang['NRS_BODY_TYPE_DELETED_TEXT'] = 'Completado: El tipo de carrocería ha sido eliminado correctamente';

// OK / Mensajes de error - Elemento de reserva
$lang['NRS_BOOKING_UPDATE_ERROR_TEXT'] = 'Error: Error de actualización de MySQL para la reserva existente!';
$lang['NRS_BOOKING_UPDATED_TEXT'] = 'Completado: La reserva se ha actualizado con éxito!';
$lang['NRS_BOOKING_INSERT_ERROR_TEXT'] = 'Error: error de inserción de MySQL para la nueva reserva!';
$lang['NRS_BOOKING_INSERTED_TEXT'] = 'Completado: Se ha añadido una nueva reserva con éxito!';
$lang['NRS_BOOKING_CANCEL_ERROR_TEXT'] = 'Error: Error de actualización de MySQL al intentar cancelar la reserva existente!';
$lang['NRS_BOOKING_CANCELLED_TEXT'] = 'Completado: la reserva ha sido cancelada con éxito!';
$lang['NRS_BOOKING_DELETE_ERROR_TEXT'] = 'Error: MySQL elimina el error de reserva / bloque existente. No se eliminaron filas de la base de datos! ';
$lang['NRS_BOOKING_DELETED_TEXT'] = 'Completado: Reserva / bloque ha sido eliminado con éxito!';
$lang['NRS_BOOKING_DELETE_OPTIONS_ERROR_TEXT'] = 'Failed: No cars or extras were deleted from reservation!';
$lang['NRS_BOOKING_OPTIONS_DELETED_TEXT'] = 'Completed: All cars and extras were deleted from reservation!';
$lang['NRS_BOOKING_MARK_AS_PAID_ERROR_TEXT'] = 'Failed: Reservation was not marked as paid!';
$lang['NRS_BOOKING_MARKED_AS_PAID_TEXT'] = 'Completed: Reservation was marked as paid!';
$lang['NRS_BOOKING_MARK_COMPLETED_EARLY_ERROR_TEXT'] = 'Failed: Reservation was not marked as completed early!';
$lang['NRS_BOOKING_MARKED_COMPLETED_EARLY_TEXT'] = 'Completed: Reservation was marked as completed early!';
$lang['NRS_BOOKING_REFUND_ERROR_TEXT'] = 'Failed: Reservation was not refunded!';
$lang['NRS_BOOKING_REFUNDED_TEXT'] = 'Completed: Reservation was refunded successfully!';

// OK / Mensajes de error - (Extra) Elemento de opción de reserva
$lang['NRS_EXTRA_BOOKING_ID_QUANTITY_OPTION_SKU_ERROR_TEXT'] = 'Error: No se puede bloquear un nuevo extra debido a un ID de reserva incorrecto (%s), SKU (%s) o cantidad (%s)!';
$lang['NRS_EXTRA_BOOKING_OPTION_INSERT_ERROR_TEXT'] = 'Error: error de inserción de MySQL para nuevo extra (SKU -%s) reserva / bloque!';
$lang['NRS_EXTRA_BOOKING_OPTION_INSERTED_TEXT'] = 'Completado: Nuevo extra (SKU -%s) ha sido insertado / reservado!';
$lang['NRS_EXTRA_BOOKING_OPTION_DELETE_ERROR_TEXT'] = 'Error: MySQL elimina el error para el extra existente reservado / bloqueado. Ninguna fila de filas se ha eliminado de la base de datos! ';
$lang['NRS_EXTRA_BOOKING_OPTION_DELETED_TEXT'] = 'Completado: Extra se ha eliminado de la reserva / bloque!';

// OK / Mensajes de error - (Elemento) Elemento de opción de reserva
$lang['NRS_ITEM_BOOKING_ID_QUANTITY_OPTION_SKU_ERROR_TEXT'] = 'Error: No se puede bloquear el Carro nuevo debido a una mala identificación de reserva (%s), SKU (%s) o cantidad (%s)!';
$lang['NRS_ITEM_BOOKING_OPTION_INSERT_ERROR_TEXT'] = 'Error: error de inserción de MySQL para nuevo Carro (SKU -%s) reserva / bloque!';
$lang['NRS_ITEM_BOOKING_OPTION_INSERTED_TEXT'] = 'Completado: Nuevo Carro (SKU -%s) ha sido bloqueado / reservado!';
$lang['NRS_ITEM_BOOKING_OPTION_DELETE_ERROR_TEXT'] = 'Error: MySQL elimina el error del Carro reservado / bloqueado existente. Ninguna fila de filas se ha eliminado de la base de datos! ';
$lang['NRS_ITEM_BOOKING_OPTION_DELETED_TEXT'] = 'Completado: El Carro ha sido retirado de la reserva / bloque!';

// OK / Mensajes de error - Elemento de cliente
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
$lang['NRS_CUSTOMER_UPDATE_ERROR_TEXT'] = 'Error: Error de actualización de MySQL para el cliente existente!';
$lang['NRS_CUSTOMER_UPDATED_TEXT'] = 'Completado: El cliente ha sido actualizado con éxito!';
$lang['NRS_CUSTOMER_INSERT_ERROR_TEXT'] = 'Error: error de inserción de MySQL para nuevo cliente!';
$lang['NRS_CUSTOMER_INSERTED_TEXT'] = 'Completado: Nuevo cliente ha sido añadido con éxito!';
$lang['NRS_CUSTOMER_LAST_VISIT_UPDATE_ERROR_TEXT'] = 'Error: MySQL update error for customer last visit date!';
$lang['NRS_CUSTOMER_LAST_VISIT_UPDATED_TEXT'] = 'Completed: Customer last visit date has been updated!';
$lang['NRS_CUSTOMER_DELETE_ERROR_TEXT'] = 'Error: MySQL elimina el error del cliente existente. No se eliminaron filas de la base de datos! ';
$lang['NRS_CUSTOMER_DELETED_TEXT'] = 'Completado: El cliente ha sido eliminado correctamente';

// OK / Mensajes de error - Elemento de demo
$lang['NRS_DEMO_INSERT_ERROR_TEXT'] = 'Error: MySQL insert error in demo data import!';
$lang['NRS_DEMO_INSERTED_TEXT'] = 'Completed: Demo data has been imported successfully!';

// OK / Mensajes de error - Elemento de distancia
$lang['NRS_DISTANCE_PICKUP_NOT_SELECTED_ERROR_TEXT'] = 'Error: Se debe seleccionar la ubicación de recogida!';
$lang['NRS_DISTANCE_RETURN_NOT_SELECTED_ERROR_TEXT'] = 'Error: Se debe seleccionar la ubicación de devolución!';
$lang['NRS_DISTANCE_SAME_PICKUP_AND_RETURN_ERROR_TEXT'] = 'Error: Pick-up and return locations cannot be the same!';
$lang['NRS_DISTANCE_EXISTS_ERROR_TEXT'] = 'Error: Esta distancia ya existe!';
$lang['NRS_DISTANCE_UPDATE_ERROR_TEXT'] = 'Error: error de actualización de MySQL para la distancia existente!';
$lang['NRS_DISTANCE_UPDATED_TEXT'] = 'Completado: La distancia ha sido actualizada con éxito!';
$lang['NRS_DISTANCE_INSERT_ERROR_TEXT'] = 'Error: error de inserción de MySQL para nueva distancia!';
$lang['NRS_DISTANCE_INSERTED_TEXT'] = 'Completado: Se ha añadido nueva distancia con éxito!';
$lang['NRS_DISTANCE_DELETE_ERROR_TEXT'] = 'Error: MySQL elimina el error de la distancia existente. No se eliminaron filas de la base de datos! ';
$lang['NRS_DISTANCE_DELETED_TEXT'] = 'Completado: La distancia ha sido borrada con éxito!';

// OK / Mensajes de error - (Extra) Elemento de descuento
$lang['NRS_EXTRA_DISCOUNT_DAYS_INTERSECTION_ERROR_TEXT'] = 'Error: El intervalo de días de descuento extra se intersecta con otro descuento adicional para el extra seleccionado (o todos los extras)!';
$lang['NRS_EXTRA_DISCOUNT_UPDATE_ERROR_TEXT'] = 'Error: error de actualización de MySQL para descuento extra existente';
$lang['NRS_EXTRA_DISCOUNT_UPDATED_TEXT'] = 'Completado: ¡El descuento extra se ha actualizado con éxito!';
$lang['NRS_EXTRA_DISCOUNT_INSERT_ERROR_TEXT'] = 'Error: error de inserción de MySQL para nuevo descuento adicional!';
$lang['NRS_EXTRA_DISCOUNT_INSERTED_TEXT'] = 'Completado: Se ha añadido un nuevo descuento extra con éxito!';
$lang['NRS_EXTRA_DISCOUNT_DELETE_ERROR_TEXT'] = 'Error: MySQL elimina el error de descuento extra existente. No se eliminaron filas de la base de datos! ';
$lang['NRS_EXTRA_DISCOUNT_DELETED_TEXT'] = 'Completado: ¡El descuento extra ha sido eliminado con éxito!';

// OK / Mensajes de error - (Plan de precios) Elemento de descuento
$lang['NRS_PRICE_PLAN_DISCOUNT_DAYS_INTERSECTION_ERROR_TEXT'] = 'Error: El plan de precios de los días de descuento coincide con otro descuento adicional para el extra seleccionado (o todos los extras)!';
$lang['NRS_PRICE_PLAN_DISCOUNT_UPDATE_ERROR_TEXT'] = 'Error: error de actualización de MySQL para el descuento del plan de precios existente!';
$lang['NRS_PRICE_PLAN_DISCOUNT_UPDATED_TEXT'] = 'Completado: ¡El descuento del plan de precios se ha actualizado con éxito!';
$lang['NRS_PRICE_PLAN_DISCOUNT_INSERT_ERROR_TEXT'] = 'Error: Error de inserción de MySQL para el descuento del nuevo plan de precios!';
$lang['NRS_PRICE_PLAN_DISCOUNT_INSERTED_TEXT'] = 'Completado: Se ha agregado un nuevo descuento en el plan de precios con éxito!';
$lang['NRS_PRICE_PLAN_DISCOUNT_DELETE_ERROR_TEXT'] = 'Error: error de eliminación de MySQL para el descuento del plan de precios existente. No se eliminaron filas de la base de datos! ';
$lang['NRS_PRICE_PLAN_DISCOUNT_DELETED_TEXT'] = 'Completado: ¡El descuento del plan de precios ha sido eliminado con éxito!';

// OK / Mensajes de error - Elemento de correo electrónico
$lang['NRS_EMAIL_DEMO_LOCATION_NAME_TEXT'] = 'Ubicación del Demo';
$lang['NRS_EMAIL_DEMO_LOCATION_PHONE_TEXT'] = '+1 600 10000';
$lang['NRS_EMAIL_DEMO_LOCATION_EMAIL_TEXT'] = 'info@location.com';
$lang['NRS_EMAIL_SUBJECT_EXISTS_ERROR_TEXT'] = 'Error: ¡Otro correo electrónico ya existe con este tema!';
$lang['NRS_EMAIL_UPDATE_ERROR_TEXT'] = 'Error: Error de actualización de MySQL para correo electrónico existente';
$lang['NRS_EMAIL_UPDATED_TEXT'] = 'Completado: El correo electrónico se ha actualizado con éxito!';
$lang['NRS_EMAIL_REGISTERED_TEXT'] = 'Asunto y cuerpo del correo electrónico registrado para la traducción.';
$lang['NRS_EMAIL_SENDING_ERROR_TEXT'] = 'Falló: El sistema no pudo enviar el correo electrónico a%s!';
$lang['NRS_EMAIL_SENT_TEXT'] = 'Completado: El correo electrónico se envió correctamente a%s!';

// OK / Mensajes de error - Elemento extra
$lang['NRS_EXTRA_SKU_EXISTS_ERROR_TEXT'] = 'Error: Extra con esta unidad de mantenimiento de stock (SKU) ya existe!';
$lang['NRS_EXTRA_MORE_UNITS_PER_BOOKING_THAN_IN_STOCK_ERROR_TEXT'] = 'Error: No se puede reservar para reservar más unidades adicionales por una reserva que las que tiene en espera!';
$lang['NRS_EXTRA_ITEM_DOES_NOT_EXIST_ERROR_TEXT'] = 'Error: Carro seleccionado no existe!';
$lang['NRS_EXTRA_ITEM_ASSIGN_ERROR_TEXT'] = 'Error: No se le permite asignar extras a ese Carro!';
$lang['NRS_EXTRA_ITEM_SELECT_ERROR_TEXT'] = 'Error: Por favor, seleccione un Carro!';
$lang['NRS_EXTRA_UPDATE_ERROR_TEXT'] = 'Error: error de actualización de MySQL para el extra existente!';
$lang['NRS_EXTRA_UPDATED_TEXT'] = 'Completado: Extra se ha actualizado con éxito!';
$lang['NRS_EXTRA_INSERT_ERROR_TEXT'] = 'Error: error de inserción de MySQL para nuevo extra!';
$lang['NRS_EXTRA_INSERTED_TEXT'] = 'Completado: Se ha añadido nuevo extra con éxito!';
$lang['NRS_EXTRA_REGISTERED_TEXT'] = 'Nombre extra registrado para la traducción.';
$lang['NRS_EXTRA_DELETE_ERROR_TEXT'] = 'Error: MySQL elimina el error por el extra existente. No se eliminaron filas de la base de datos! ';
$lang['NRS_EXTRA_DELETED_TEXT'] = 'Completado: Extra se ha eliminado con éxito!';

// OK / Mensajes de error - Elemento de característica
$lang['NRS_FEATURE_TITLE_EXISTS_ERROR_TEXT'] = 'Error: La característica con este título ya existe!';
$lang['NRS_FEATURE_UPDATE_ERROR_TEXT'] = 'Error: error de actualización de MySQL para la característica existente!';
$lang['NRS_FEATURE_UPDATED_TEXT'] = 'Completado: La característica se ha actualizado con éxito!';
$lang['NRS_FEATURE_INSERT_ERROR_TEXT'] = 'Error: error de inserción de MySQL para la nueva característica!';
$lang['NRS_FEATURE_INSERTED_TEXT'] = 'Completado: Se ha añadido una nueva característica con éxito!';
$lang['NRS_FEATURE_REGISTERED_TEXT'] = 'Título de la característica registrado para la traducción.';
$lang['NRS_FEATURE_DELETE_ERROR_TEXT'] = 'Error: error de eliminación de MySQL para la característica existente. No se eliminaron filas de la base de datos! ';
$lang['NRS_FEATURE_DELETED_TEXT'] = 'Completado: ¡La característica ha sido eliminada con éxito!';

// OK / Mensajes de error - Elemento del tipo de combustible
$lang['NRS_FUEL_TYPE_TITLE_EXISTS_ERROR_TEXT'] = 'Error: El tipo de combustible con este título ya existe!';
$lang['NRS_FUEL_TYPE_UPDATE_ERROR_TEXT'] = 'Error: Error de actualización de MySQL para el tipo de combustible existente!';
$lang['NRS_FUEL_TYPE_UPDATED_TEXT'] = 'Completado: El tipo de combustible se ha actualizado con éxito!';
$lang['NRS_FUEL_TYPE_INSERT_ERROR_TEXT'] = 'Error: error de inserción de MySQL para el nuevo tipo de combustible!';
$lang['NRS_FUEL_TYPE_INSERTED_TEXT'] = 'Completado: Nuevo tipo de combustible ha sido añadido con éxito!';
$lang['NRS_FUEL_TYPE_REGISTERED_TEXT'] = 'Título del tipo de combustible registrado para la traducción.';
$lang['NRS_FUEL_TYPE_DELETE_ERROR_TEXT'] = 'Error: error de eliminación de MySQL para el tipo de combustible existente. No se eliminaron filas de la base de datos! ';
$lang['NRS_FUEL_TYPE_DELETED_TEXT'] = 'Completado: El tipo de combustible ha sido borrado con éxito!';

// OK / Mensajes de error - Elemento de instancia
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

// Elemento de instancia
$lang['NRS_INSTALL_DEFAULT_COMPANY_NAME_TEXT'] = 'Empresa de Alquiler de Carros';
$lang['NRS_INSTALL_DEFAULT_COMPANY_STREET_ADDRESS_TEXT'] = '625 2nd Street';
$lang['NRS_INSTALL_DEFAULT_COMPANY_CITY_TEXT'] = 'San Francisco';
$lang['NRS_INSTALL_DEFAULT_COMPANY_STATE_TEXT'] = 'CA';
$lang['NRS_INSTALL_DEFAULT_COMPANY_ZIP_CODE_TEXT'] = '94107';
$lang['NRS_INSTALL_DEFAULT_COMPANY_COUNTRY_TEXT'] = '';
$lang['NRS_INSTALL_DEFAULT_COMPANY_PHONE_TEXT'] = '(450) 600 4000';
$lang['NRS_INSTALL_DEFAULT_COMPANY_EMAIL_TEXT'] = 'info@yourdomain.com';
$lang['NRS_INSTALL_DEFAULT_PAGE_URL_SLUG_TEXT'] = 'alquiler de Carros'; // Letras en latín solamente
$lang['NRS_INSTALL_DEFAULT_ITEM_URL_SLUG_TEXT'] = 'carro'; // Letras en latín solamente
$lang['NRS_INSTALL_DEFAULT_LOCATION_URL_SLUG_TEXT'] = 'ubicación del Carro'; // Letras en latín solamente
$lang['NRS_INSTALL_DEFAULT_SEARCH_PAGE_URL_SLUG_TEXT'] = 'search'; // Letras en latín solamente
$lang['NRS_INSTALL_DEFAULT_CANCELLED_PAYMENT_PAGE_TITLE_TEXT'] = 'Pago cancelado';
$lang['NRS_INSTALL_DEFAULT_CANCELLED_PAYMENT_PAGE_CONTENT_TEXT'] = 'El pago se canceló. Tu reserva no ha sido confirmada. ';
$lang['NRS_INSTALL_DEFAULT_CONFIRMATION_PAGE_TITLE_TEXT'] = 'Reserva confirmada';
$lang['NRS_INSTALL_DEFAULT_CONFIRMATION_PAGE_CONTENT_TEXT'] = 'Gracias. Recibimos su pago. Su reserva está confirmada. ';
$lang['NRS_INSTALL_DEFAULT_TERMS_AND_CONDITIONS_PAGE_TITLE_TEXT'] = 'Términos de uso para alquilar un Carro';
$lang['NRS_INSTALL_DEFAULT_TERMS_AND_CONDITIONS_PAGE_CONTENT_TEXT'] = 'Debe seguir las directrices y Términos para rentar un Carro. ';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAYPAL_TEXT'] = 'En línea - PayPal';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAYPAL_DESCRIPTION_TEXT'] = 'Pago instantáneo seguro';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_STRIPE_TEXT'] = 'Tarjeta de crédito (a través de Stripe.com)';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_BANK_TEXT'] = 'Transferencia Bancaria';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_BANK_DETAILS_TEXT'] = 'Sus datos bancarios';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAY_OVER_THE_PHONE_TEXT'] = 'Pago vía telefónica';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAY_ON_ARRIVAL_TEXT'] = 'Pagar al recoger el vehículo';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAY_ON_ARRIVAL_DETAILS_TEXT'] = 'Requiere tarjeta de crédito';
$lang['NRS_INSTALL_DEFAULT_DEAR_TEXT'] = 'Apreciado';
$lang['NRS_INSTALL_DEFAULT_REGARDS_TEXT'] = 'Saludos';
$lang['NRS_INSTALL_DEFAULT_EMAIL_TITLE_BOOKING_DETAILS_TEXT'] = 'Detalles de la reserva - no. [BOOKING_CODE]';
$lang['NRS_INSTALL_DEFAULT_EMAIL_TITLE_BOOKING_CONFIRMED_TEXT'] = 'Reserva no. [BOOKING_CODE] - confirmada ';
$lang['NRS_INSTALL_DEFAULT_EMAIL_TITLE_BOOKING_CANCELLED_TEXT'] = 'Reserva no. [BOOKING_CODE] - cancelada ';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_TITLE_BOOKING_DETAILS_TEXT'] = 'Notificación: nueva reserva - [BOOKING_CODE]';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_TITLE_BOOKING_CONFIRMED_TEXT'] = 'Notificación: reserva pagada - [BOOKING_CODE]';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_TITLE_BOOKING_CANCELLED_TEXT'] = 'Notificación: reserva cancelada - [BOOKING_CODE]';
$lang['NRS_INSTALL_DEFAULT_EMAIL_BODY_BOOKING_RECEIVED_TEXT'] = 'Se han recibido los detalles de su reserva.';
$lang['NRS_INSTALL_DEFAULT_EMAIL_BODY_BOOKING_DETAILS_TEXT'] = 'Tus datos de reserva:';
$lang['NRS_INSTALL_DEFAULT_EMAIL_BODY_PAYMENT_RECEIVED_TEXT'] = 'Recibimos su pago. Su reserva está confirmada. ';
$lang['NRS_INSTALL_DEFAULT_EMAIL_BODY_BOOKING_CANCELLED_TEXT'] = 'Su reserva no. [BOOKING_CODE] se cancelaron. ';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_BOOKING_RECEIVED_TEXT'] = 'Nueva reserva no. [BOOKING_CODE] recibido de [CUSTOMER_NAME]. ';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_BOOKING_DETAILS_TEXT'] = 'Detalles de la reserva:';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_BOOKING_PAID_TEXT'] = 'Reserva no. [BOOKING_CODE] ha sido pagada recientemente por [CUSTOMER_NAME].';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_BOOKING_CANCELLED_TEXT'] = 'Reserva no. [BOOKING_CODE] de [CUSTOMER_NAME] ha sido cancelada recientemente.';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_CANCELLED_BOOKING_DETAILS_TEXT'] = 'Detalles de la reserva, que fueron cancelados:';

// OK / Mensajes de error - Elemento de factura
$lang['NRS_INVOICE_UPDATE_ERROR_TEXT'] = 'Error: Error de actualización de MySQL para la factura existente!';
$lang['NRS_INVOICE_UPDATED_TEXT'] = 'Completado: ¡La factura se ha actualizado con éxito!';
$lang['NRS_INVOICE_INSERT_ERROR_TEXT'] = 'Error: error de inserción de MySQL para nueva factura!';
$lang['NRS_INVOICE_INSERTED_TEXT'] = 'Completado: La factura se ha añadido con éxito!';
$lang['NRS_INVOICE_APPEND_ERROR_TEXT'] = 'Error: error de actualización de MySQL al intentar anexar la factura existente. No se actualizaron filas en la base de datos! ';
$lang['NRS_INVOICE_APPENDED_TEXT'] = 'Completado: ¡La factura se ha añadido con éxito!';
$lang['NRS_INVOICE_DELETE_ERROR_TEXT'] = 'Error: MySQL elimina el error de la factura existente. No se eliminaron filas de la base de datos! ';
$lang['NRS_INVOICE_DELETED_TEXT'] = 'Completado: ¡La factura ha sido eliminada con éxito!';

// OK / Mensajes de error - Elemento Elemento
$lang['NRS_ITEM_SKU_EXISTS_ERROR_TEXT'] = 'Error: El Carro con esta SKU ya existe!';
$lang['NRS_ITEM_MORE_UNITS_PER_BOOKING_THAN_IN_STOCK_ERROR_TEXT'] = 'Error: No se puede reservar una cantidad de carros mayor a los disponibles!';
$lang['NRS_ITEM_UPDATE_ERROR_TEXT'] = 'Error: Error de actualización de MySQL para el Carro existente!';
$lang['NRS_ITEM_UPDATED_TEXT'] = 'Completado: Detalles del Carro se ha actualizado con éxito!';
$lang['NRS_ITEM_INSERT_ERROR_TEXT'] = 'Error: error de inserción de MySQL para un Carro nuevo!';
$lang['NRS_ITEM_INSERTED_TEXT'] = 'Completado: Se ha agregado un Carro nuevo con éxito!';
$lang['NRS_ITEM_REGISTERED_TEXT'] = 'Nombre del modelo de Carro registrado para la traducción.';
$lang['NRS_ITEM_DELETE_ERROR_TEXT'] = 'Error: MySQL elimina el error del Carro existente. No se eliminaron filas de la base de datos! ';
$lang['NRS_ITEM_DELETED_TEXT'] = 'Completado: El Carro ha sido eliminado correctamente';

// OK / Mensajes de error - Elemento de ubicación
$lang['NRS_LOCATION_CODE_EXISTS_ERROR_TEXT'] = 'Error: La ubicación con este código ya existe!';
$lang['NRS_LOCATION_NAME_EXISTS_ERROR_TEXT'] = 'Error: La ubicación con este nombre ya existe!';
$lang['NRS_LOCATION_UPDATE_ERROR_TEXT'] = 'Error: Error de actualización de MySQL para la ubicación existente!';
$lang['NRS_LOCATION_UPDATED_TEXT'] = 'Completado: La ubicación se ha actualizado con éxito!';
$lang['NRS_LOCATION_INSERT_ERROR_TEXT'] = 'Error: error de inserción de MySQL para nueva ubicación!';
$lang['NRS_LOCATION_INSERTED_TEXT'] = 'Completado: Se ha agregado una nueva ubicación con éxito!';
$lang['NRS_LOCATION_REGISTERED_TEXT'] = 'Nombre de la ubicación registrado para la traducción.';
$lang['NRS_LOCATION_DELETE_ERROR_TEXT'] = 'Error: error de eliminación de MySQL para la ubicación existente. No se eliminaron filas de la base de datos! ';
$lang['NRS_LOCATION_DELETED_TEXT'] = 'Completado: La ubicación ha sido eliminada correctamente';

// OK / Mensajes de error - Elemento del fabricante
$lang['NRS_MANUFACTURER_TITLE_EXISTS_ERROR_TEXT'] = 'Error: Fabricante con este título ya existe!';
$lang['NRS_MANUFACTURER_UPDATE_ERROR_TEXT'] = 'Error: error de actualización de MySQL para el fabricante existente!';
$lang['NRS_MANUFACTURER_UPDATED_TEXT'] = 'Completado: El fabricante ha sido actualizado con éxito!';
$lang['NRS_MANUFACTURER_INSERT_ERROR_TEXT'] = 'Error: error de inserción de MySQL para el nuevo fabricante!';
$lang['NRS_MANUFACTURER_INSERTED_TEXT'] = 'Completado: Se ha agregado un nuevo fabricante con éxito!';
$lang['NRS_MANUFACTURER_REGISTERED_TEXT'] = 'Título del fabricante registrado para la traducción.';
$lang['NRS_MANUFACTURER_DELETE_ERROR_TEXT'] = 'Error: error de eliminación de MySQL para el fabricante existente. No se eliminaron filas de la base de datos! ';
$lang['NRS_MANUFACTURER_DELETED_TEXT'] = 'Completado: El fabricante ha sido eliminado con éxito!';

// OK / Mensajes de error - Elemento de opción (extra)
$lang['NRS_EXTRA_OPTION_PLEASE_SELECT_ERROR_TEXT'] = 'Error: Seleccione un extra!';
$lang['NRS_EXTRA_OPTION_NAME_EXISTS_ERROR_TEXT'] = 'Error: La opción con el nombre elegido ya existe para este extra!';
$lang['NRS_EXTRA_OPTION_UPDATE_ERROR_TEXT'] = 'Error: error de actualización de MySQL para la opción extra existente!';
$lang['NRS_EXTRA_OPTION_UPDATED_TEXT'] = 'Completado: La opción Extra se ha actualizado con éxito!';
$lang['NRS_EXTRA_OPTION_INSERT_ERROR_TEXT'] = 'Error: error de inserción de MySQL para la nueva opción extra!';
$lang['NRS_EXTRA_OPTION_INSERTED_TEXT'] = 'Completado: Se ha añadido una nueva opción extra con éxito!';
$lang['NRS_EXTRA_OPTION_REGISTERED_TEXT'] = 'Nombre de la opción extra registrado para la traducción.';
$lang['NRS_EXTRA_OPTION_DELETE_ERROR_TEXT'] = 'Error: error de eliminación de MySQL para la opción extra existente. No se eliminaron filas de la base de datos! ';
$lang['NRS_EXTRA_OPTION_DELETED_TEXT'] = 'Completado: La opción Extra ha sido eliminada con éxito!';

// OK / Mensajes de error - (Elemento) Elemento de opción
$lang['NRS_ITEM_OPTION_PLEASE_SELECT_ERROR_TEXT'] = 'Error: Por favor seleccione un Carro!';
$lang['NRS_ITEM_OPTION_NAME_EXISTS_ERROR_TEXT'] = 'Error: Ya existe la opción con el nombre elegido para este Carro!';
$lang['NRS_ITEM_OPTION_UPDATE_ERROR_TEXT'] = 'Error: error de actualización de MySQL para la opción de Carro existente!';
$lang['NRS_ITEM_OPTION_UPDATED_TEXT'] = 'Completado: La opción de Carro se ha actualizado con éxito!';
$lang['NRS_ITEM_OPTION_INSERT_ERROR_TEXT'] = 'Error: error de inserción de MySQL para la opción de Carro nuevo!';
$lang['NRS_ITEM_OPTION_INSERTED_TEXT'] = 'Completado: La opción de Carro nuevo se ha añadido con éxito!';
$lang['NRS_ITEM_OPTION_REGISTERED_TEXT'] = 'Nombre de la opción de Carro registrado para la traducción.';
$lang['NRS_ITEM_OPTION_DELETE_ERROR_TEXT'] = 'Error: error de eliminación de MySQL para la opción de Carro existente. No se eliminaron filas de la base de datos! ';
$lang['NRS_ITEM_OPTION_DELETED_TEXT'] = 'Completado: La opción de Carro ha sido eliminada con éxito!';

// OK / Mensajes de error - Elemento del método de pago
$lang['NRS_PAYMENT_METHOD_CODE_EXISTS_ERROR_TEXT'] = 'Error: El método de pago con este código ya existe!';
$lang['NRS_PAYMENT_METHOD_INVALID_NAME_TEXT'] = 'Error: ¡Introduzca el nombre del método de pago válido!';
$lang['NRS_PAYMENT_METHOD_UPDATE_ERROR_TEXT'] = 'Error: Error de actualización de MySQL para el método de pago existente!';
$lang['NRS_PAYMENT_METHOD_UPDATED_TEXT'] = 'Completado: El método de pago se ha actualizado con éxito!';
$lang['NRS_PAYMENT_METHOD_INSERT_ERROR_TEXT'] = 'Error: error de inserción de MySQL para el nuevo método de pago!';
$lang['NRS_PAYMENT_METHOD_INSERTED_TEXT'] = 'Completado: Nuevo método de pago ha sido añadido con éxito!';
$lang['NRS_PAYMENT_METHOD_REGISTERED_TEXT'] = 'Nombre y descripción del método de pago registrados para la traducción.';
$lang['NRS_PAYMENT_METHOD_DELETE_ERROR_TEXT'] = 'Error: MySQL elimina el error del método de pago existente. No se eliminaron filas de la base de datos! ';
$lang['NRS_PAYMENT_METHOD_DELETED_TEXT'] = 'Completado: El método de pago ha sido eliminado con éxito!';

// OK / Mensajes de error - Elemento de pago anticipado
$lang['NRS_PREPAYMENT_DAYS_INTERSECTION_ERROR_TEXT'] = 'Error: El intervalo de días del plan de ypago anticipado coincide con otro plan de pago anticipado!';
$lang['NRS_PREPAYMENT_UPDATE_ERROR_TEXT'] = 'Error: error de actualización de MySQL para el plan de ypago anticipado existente';
$lang['NRS_PREPAYMENT_UPDATED_TEXT'] = 'Completado: el plan de pago anticipado se ha actualizado con éxito!';
$lang['NRS_PREPAYMENT_INSERT_ERROR_TEXT'] = 'Error: error de inserción de MySQL para el nuevo plan de ypago anticipado!';
$lang['NRS_PREPAYMENT_INSERTED_TEXT'] = 'Completado: Nuevo plan de ypago anticipado ha sido añadido con éxito!';
$lang['NRS_PREPAYMENT_DELETE_ERROR_TEXT'] = 'Error: error de eliminación de MySQL para el plan de ypago anticipado existente. No se eliminaron filas de la base de datos! ';
$lang['NRS_PREPAYMENT_DELETED_TEXT'] = 'Completado: ¡El plan de pago anticipado ha sido eliminado con éxito!';

// OK / Mensajes de error - Elemento del grupo de precios
$lang['NRS_PRICE_GROUP_UPDATE_ERROR_TEXT'] = 'Error: error de actualización de MySQL para el grupo de precios existente!';
$lang['NRS_PRICE_GROUP_UPDATED_TEXT'] = 'Completado: El grupo de precios ha sido actualizado con éxito!';
$lang['NRS_PRICE_GROUP_INSERT_ERROR_TEXT'] = 'Error: error de inserción de MySQL para el nuevo grupo de precios!';
$lang['NRS_PRICE_GROUP_INSERTED_TEXT'] = 'Completado: Nuevo grupo de precios ha sido añadido con éxito!';
$lang['NRS_PRICE_GROUP_REGISTERED_TEXT'] = 'Nombre del grupo de precios registrado para la traducción.';
$lang['NRS_PRICE_GROUP_DELETE_ERROR_TEXT'] = 'Error: error de eliminación de MySQL para el grupo de precios existente. No se eliminaron filas de la base de datos! ';
$lang['NRS_PRICE_GROUP_DELETED_TEXT'] = 'Completado: El grupo de precios ha sido eliminado correctamente';

// OK / Mensajes de error - Elemento del plan de precios
$lang['NRS_PRICE_PLAN_LATER_DATE_ERROR_TEXT'] = 'Error: La fecha de inicio no puede ser posterior o igual a la fecha de finalización!';
$lang['NRS_PRICE_PLAN_INVALID_PRICE_GROUP_ERROR_TEXT'] = 'Error: Seleccione un grupo de precios válido!';
$lang['NRS_PRICE_PLAN_EXISTS_FOR_DATE_RANGE_ERROR_TEXT'] = 'Error: Existe un plan de precios existente para este rango de fechas sin código de cupón o con el mismo código de cupón!';
$lang['NRS_PRICE_PLAN_UPDATE_ERROR_TEXT'] = 'Error: error de actualización de MySQL para el plan de precios existente!';
$lang['NRS_PRICE_PLAN_UPDATED_TEXT'] = 'Completado: ¡El plan de precios ha sido actualizado con éxito!';
$lang['NRS_PRICE_PLAN_INSERT_ERROR_TEXT'] = 'Error: error de inserción de MySQL para el nuevo plan de precios!';
$lang['NRS_PRICE_PLAN_INSERTED_TEXT'] = 'Completado: Nuevo plan de precios ha sido añadido con éxito!';
$lang['NRS_PRICE_PLAN_DELETE_ERROR_TEXT'] = 'Error: Error de eliminación de MySQL para el plan de precios existente. No se eliminaron filas de la base de datos! ';
$lang['NRS_PRICE_PLAN_DELETED_TEXT'] = 'Completado: ¡El plan de precios ha sido eliminado con éxito!';

// OK / Mensajes de error - Elemento impositivo
$lang['NRS_TAX_UPDATE_ERROR_TEXT'] = 'Error: error de actualización de MySQL para el impuesto existente!';
$lang['NRS_TAX_UPDATED_TEXT'] = 'Finalizado: ¡El impuesto ha sido actualizado con éxito!';
$lang['NRS_TAX_INSERT_ERROR_TEXT'] = 'Error: error de inserción de MySQL para nuevo impuesto!';
$lang['NRS_TAX_INSERTED_TEXT'] = 'Completado: Nuevo impuesto ha sido añadido con éxito!';
$lang['NRS_TAX_REGISTERED_TEXT'] = 'Nombre del impuesto registrado para la traducción.';
$lang['NRS_TAX_DELETE_ERROR_TEXT'] = 'Error: error de eliminación de MySQL para el impuesto existente. No se eliminaron filas de la base de datos! ';
$lang['NRS_TAX_DELETED_TEXT'] = 'Completado: ¡El impuesto ha sido eliminado con éxito!';

// OK / Mensajes de error - Elemento de tipo de transmisión
$lang['NRS_TRANSMISSION_TYPE_TITLE_EXISTS_ERROR_TEXT'] = 'Error: El tipo de transmisión con este título ya existe!';
$lang['NRS_TRANSMISSION_TYPE_UPDATE_ERROR_TEXT'] = 'Error: error de actualización de MySQL para el tipo de transmisión existente!';
$lang['NRS_TRANSMISSION_TYPE_UPDATED_TEXT'] = 'Completado: El tipo de transmisión se ha actualizado con éxito!';
$lang['NRS_TRANSMISSION_TYPE_INSERT_ERROR_TEXT'] = 'Error: error de inserción de MySQL para el nuevo tipo de transmisión!';
$lang['NRS_TRANSMISSION_TYPE_INSERTED_TEXT'] = 'Completado: Nuevo tipo de transmisión ha sido añadido con éxito!';
$lang['NRS_TRANSMISSION_TYPE_REGISTERED_TEXT'] = 'Título del tipo de transmisión registrado para la traducción.';
$lang['NRS_TRANSMISSION_TYPE_DELETE_ERROR_TEXT'] = 'Error: error de eliminación de MySQL para el tipo de transmisión existente. No se eliminaron filas de la base de datos! ';
$lang['NRS_TRANSMISSION_TYPE_DELETED_TEXT'] = 'Completado: El tipo de transmisión se ha eliminado correctamente!';

// OK / Mensajes de error - Elemento de tipo de actualizar
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