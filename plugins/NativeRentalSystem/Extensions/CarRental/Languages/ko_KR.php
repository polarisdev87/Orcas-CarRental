<?php
/**
 * Language specific file
 * @Language - Korean
 * @Author - Eric Jazz
 * @Email - jazz302098@gmail.com
 * @Website - Not specified
 */
// Settings
$lang['LTR'] = FALSE;
$lang['NRS_RECAPTCHA_LANG'] = 'en';

// Roles
$lang['NRS_PARTNER_ROLE_NAME_TEXT'] = '렌트카 회사';
$lang['NRS_ASSISTANT_ROLE_NAME_TEXT'] = '차 도우미';
$lang['NRS_MANAGER_ROLE_NAME_TEXT'] = '차 매니저';

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
$lang['NRS_ADMIN_VIEW_DETAILS_TEXT'] = '자세히 보기';
$lang['NRS_ADMIN_VIEW_BOOKINGS_TEXT'] = '예약 보기';
$lang['NRS_ADMIN_VIEW_UNPAID_BOOKINGS_TEXT'] = '미결제 예약 보기';
$lang['NRS_ADMIN_NO_BOOKINGS_YET_TEXT'] = '예약 없음';
$lang['NRS_ADMIN_BOOKING_DETAILS_TEXT'] = '예약 디테일';
$lang['NRS_ADMIN_CUSTOMER_DETAILS_FROM_DB_TEXT'] = '고객 정보';
$lang['NRS_ADMIN_BOOKING_STATUS_TEXT'] = '예약 상태';
$lang['NRS_ADMIN_BOOKING_STATUS_UPCOMING_TEXT'] = '곧';
$lang['NRS_ADMIN_BOOKING_STATUS_DEPARTED_TEXT'] = '출발함';
$lang['NRS_ADMIN_BOOKING_STATUS_COMPLETED_EARLY_TEXT'] = '이미 완료';
$lang['NRS_ADMIN_BOOKING_STATUS_COMPLETED_TEXT'] = '완료됨';
$lang['NRS_ADMIN_BOOKING_STATUS_ACTIVE_TEXT'] = '현재';
$lang['NRS_ADMIN_BOOKING_STATUS_CANCELLED_TEXT'] = '취소됨';
$lang['NRS_ADMIN_BOOKING_STATUS_PAID_TEXT'] = '결제됨';
$lang['NRS_ADMIN_BOOKING_STATUS_UNPAID_TEXT'] = '결제 안 됨';
$lang['NRS_ADMIN_BOOKING_STATUS_REFUNDED_TEXT'] = '호나불됨';
$lang['NRS_ADMIN_PRINT_INVOICE_TEXT'] = '영수증 프린트';
$lang['NRS_ADMIN_BACK_TO_CUSTOMER_BOOKING_LIST_TEXT'] = '예약 리스트로 돌아가기';
$lang['NRS_ADMIN_CUSTOMERS_BY_LAST_VISIT_TEXT'] = '예약자: 마지막 날짜';
$lang['NRS_ADMIN_CUSTOMERS_BY_REGISTRATION_TEXT'] = '예약자: 레지스트레이션 데이트';
$lang['NRS_ADMIN_BOOKINGS_PERIOD_FROM_TO_TEXT'] = '예약 기간: %s - %s';
$lang['NRS_ADMIN_PICKUPS_PERIOD_FROM_TO_TEXT'] = '픽업 기간: %s - %s';
$lang['NRS_ADMIN_RETURNS_PERIOD_FROM_TO_TEXT'] = '리턴 기간: %s - %s';
$lang['NRS_ADMIN_UPCOMING_TEXT'] = '곧';
$lang['NRS_ADMIN_PAST_TEXT'] = '예전';
$lang['NRS_ADMIN_CUSTOMER_BOOKINGS_TEXT'] = '고객 예약';
$lang['NRS_ADMIN_BOOKINGS_BY_TEXT'] = '예약 %s';
$lang['NRS_ADMIN_ALL_BOOKINGS_TEXT'] = '모든 예약';
$lang['NRS_ADMIN_ALL_PICKUPS_TEXT'] = '모든 픽업';
$lang['NRS_ADMIN_ALL_RETURNS_TEXT'] = '모두 리턴됨';
$lang['NRS_ADMIN_MAX_ITEM_UNITS_PER_BOOKING_TEXT'] = '최대 렌트 갯수';
$lang['NRS_ADMIN_TOTAL_ITEM_UNITS_IN_STOCK_TEXT'] = '총 렌트카 숫자';
$lang['NRS_ADMIN_MAX_EXTRA_UNITS_PER_BOOKING_TEXT'] = '맥시멈 펄 레져베이션';
$lang['NRS_ADMIN_TOTAL_EXTRA_UNITS_IN_STOCK_TEXT'] = '토탈 엑스트라 유닛츠';
$lang['NRS_ADMIN_ITEM_PRICES_TEXT'] = '자 가격';
$lang['NRS_ADMIN_ITEM_DEPOSITS_TEXT'] = '차 디포짓';
$lang['NRS_ADMIN_EXTRA_PRICES_TEXT'] = '엑스트라 프라이스';
$lang['NRS_ADMIN_EXTRA_DEPOSITS_TEXT'] = '엑스트라 이포짓';
$lang['NRS_ADMIN_PICKUP_FEES_TEXT'] = '픽업 수수료';
$lang['NRS_ADMIN_DISTANCE_FEES_TEXT'] = '거리 수수료';
$lang['NRS_ADMIN_RETURN_FEES_TEXT'] = '리턴 수수료';
$lang['NRS_ADMIN_REGULAR_PRICE_TEXT'] = '레귤러 수수료';
$lang['NRS_ADMIN_PRICE_TYPE_TEXT'] = '가격 타입';
$lang['NRS_ADMIN_ON_THE_LEFT_TEXT'] = '왼쪽';
$lang['NRS_ADMIN_ON_THE_RIGHT_TEXT'] = '오른쪽';
$lang['NRS_ADMIN_LOAD_FROM_OTHER_PLACE_TEXT'] = '다른곳에서 로드';
$lang['NRS_ADMIN_LOAD_FROM_PLUGIN_TEXT'] = '이곳에서 로드';
$lang['NRS_ADMIN_EMAIL_TEXT'] = '이메일';
$lang['NRS_ADMIN_PUBLIC_TEXT'] = '퍼블릭';
$lang['NRS_ADMIN_PRIVATE_TEXT'] = '비밀';
$lang['NRS_ADMIN_CALENDAR_NO_CALENDARS_FOUND_TEXT'] = '발견된 캘린더가 없습니다';
$lang['NRS_ADMIN_CHOOSE_PAGE_TEXT'] = ' - 페이지 선택 - ';
$lang['NRS_ADMIN_SELECT_EMAIL_TYPE_TEXT'] = '--- 이메일 타입 선택 ---';
$lang['NRS_ADMIN_TOTAL_REQUESTS_LEFT_TEXT'] = '토탈 요청 수';
$lang['NRS_ADMIN_FAILED_REQUESTS_LEFT_TEXT'] = '실패된 요청 수';
$lang['NRS_ADMIN_EMAIL_ATTEMPTS_LEFT_TEXT'] = '이메일 요청 수';

// Admin Menu
$lang['NRS_ADMIN_MENU_EXTENSION_PAGE_TITLE_TEXT'] = '렌트카 시스템';
$lang['NRS_ADMIN_MENU_EXTENSION_LABEL_TEXT'] = '차 렌트';
$lang['NRS_ADMIN_MENU_SYSTEM_UPDATE_TEXT'] = '시스템 업데이트';
$lang['NRS_ADMIN_MENU_NETWORK_UPDATE_TEXT'] = '네트워크 업데이트';
// Admin Menu - Benefit Manager
$lang['NRS_ADMIN_MENU_BENEFIT_MANAGER_TEXT'] = '베네핏 매니저';
$lang['NRS_ADMIN_MENU_ADD_EDIT_BENEFIT_TEXT'] = '추가/수정 베네핏';
// Admin Menu - Item Manager
$lang['NRS_ADMIN_MENU_ITEM_MANAGER_TEXT'] = '차 매니저';
$lang['NRS_ADMIN_MENU_ADD_EDIT_ITEM_TEXT'] = '추가/수정';
$lang['NRS_ADMIN_MENU_ADD_EDIT_MANUFACTURER_TEXT'] = '추가/수정 제조사';
$lang['NRS_ADMIN_MENU_ADD_EDIT_BODY_TYPE_TEXT'] = '추가/수정 바디 타입';
$lang['NRS_ADMIN_MENU_ADD_EDIT_FUEL_TYPE_TEXT'] = '추가/수정 연료';
$lang['NRS_ADMIN_MENU_ADD_EDIT_TRANSMISSION_TYPE_TEXT'] = '추가/수정 트렌스미션';
$lang['NRS_ADMIN_MENU_ADD_EDIT_FEATURE_TEXT'] = '추가/수정 옵션';
$lang['NRS_ADMIN_MENU_ADD_EDIT_ITEM_OPTION_TEXT'] = '추가/수정 옵션';
$lang['NRS_ADMIN_MENU_BLOCK_ITEM_TEXT'] = '차단';
// Admin Menu - Item Prices
$lang['NRS_ADMIN_MENU_ITEM_PRICES_TEXT'] = '차가격들';
$lang['NRS_ADMIN_MENU_ADD_EDIT_PRICE_GROUP_TEXT'] = '추가/수정 가격 그룹';
$lang['NRS_ADMIN_MENU_ADD_EDIT_PRICE_PLAN_TEXT'] = '추가/수정 가격';
$lang['NRS_ADMIN_MENU_ADD_EDIT_ITEM_DISCOUNT_TEXT'] = '추가/수정 차 디스카운트';
// Admin Menu - Extras Manager
$lang['NRS_ADMIN_MENU_EXTRAS_MANAGER_TEXT'] = '엑스트라 매니저';
$lang['NRS_ADMIN_MENU_ADD_EDIT_EXTRA_TEXT'] = '추가/수정 엑스트라';
$lang['NRS_ADMIN_MENU_ADD_EDIT_EXTRA_OPTION_TEXT'] = '추가/수정 엑스트라 옵션';
$lang['NRS_ADMIN_MENU_ADD_EDIT_EXTRA_DISCOUNT_TEXT'] = '추가/수정 엑스트라 디스카운트';
$lang['NRS_ADMIN_MENU_BLOCK_EXTRA_TEXT'] = '엑스트라 차단';
// Admin Menu - Location Manager
$lang['NRS_ADMIN_MENU_LOCATION_MANAGER_TEXT'] = '위치 매니저';
$lang['NRS_ADMIN_MENU_ADD_EDIT_LOCATION_TEXT'] = '추가/수정 위치';
$lang['NRS_ADMIN_MENU_ADD_EDIT_DISTANCE_TEXT'] = '추가/수정 거리';
// Admin Menu - Reservation Manager
$lang['NRS_ADMIN_MENU_BOOKING_MANAGER_TEXT'] = '예약 매니저 ';
$lang['NRS_ADMIN_MENU_BOOKING_SEARCH_RESULTS_TEXT'] = '예약 검색 결과';
$lang['NRS_ADMIN_MENU_ITEM_CALENDAR_SEARCH_RESULTS_TEXT'] = '스케쥴 검색 결과';
$lang['NRS_ADMIN_MENU_EXTRAS_CALENDAR_SEARCH_TEXT'] = '엑스트라 스케쥴 검색 결과';
$lang['NRS_ADMIN_MENU_CUSTOMER_SEARCH_RESULTS_TEXT'] = '고객 검색 결과';
$lang['NRS_ADMIN_MENU_ADD_EDIT_CUSTOMER_TEXT'] = '추가/수정 고객';
$lang['NRS_ADMIN_MENU_ADD_EDIT_BOOKING_TEXT'] = '추가/수정 예약';
$lang['NRS_ADMIN_MENU_VIEW_DETAILS_TEXT'] = '예약 디테일 보기';
$lang['NRS_ADMIN_MENU_PRINT_INVOICE_TEXT'] = 'Print Invoice';
// Admin Menu - Payments & Taxes
$lang['NRS_ADMIN_MENU_PAYMENTS_AND_TAXES_TEXT'] = '세금과 결제';
$lang['NRS_ADMIN_MENU_ADD_EDIT_PAYMENT_METHOD_TEXT'] = '추가/수정 결제 방식';
$lang['NRS_ADMIN_MENU_ADD_EDIT_PREPAYMENT_TEXT'] = '추가/수정 결제';
$lang['NRS_ADMIN_MENU_ADD_EDIT_TAX_TEXT'] = '추가/수정 세금';
// Admin Menu - Settings
$lang['NRS_ADMIN_MENU_SINGLE_MANAGER_TEXT'] = '설정';
$lang['NRS_ADMIN_MENU_ADD_EDIT_GLOBAL_SETTINGS_TEXT'] = '추가/수정 글로발 설정';
$lang['NRS_ADMIN_MENU_ADD_EDIT_CUSTOMER_SETTINGS_TEXT'] = '추가/수정 고객 설정';
$lang['NRS_ADMIN_MENU_ADD_EDIT_SEARCH_SETTINGS_TEXT'] = '추가/수정 검색 설정';
$lang['NRS_ADMIN_MENU_ADD_EDIT_PRICE_SETTINGS_TEXT'] = '추가/수정 가격 설정';
$lang['NRS_ADMIN_MENU_ADD_EDIT_EMAIL_TEXT'] = '추가/수정 이메일';
$lang['NRS_ADMIN_MENU_IMPORT_DEMO_TEXT'] = '예제 불러오기';
$lang['NRS_ADMIN_MENU_CONTENT_PREVIEW_TEXT'] = '미리보기';
// Admin Menu - Instructions
$lang['NRS_ADMIN_MENU_INSTRUCTIONS_TEXT'] = '설명서';
// Admin Menu - Network Manager
$lang['NRS_ADMIN_MENU_NETWORK_MANAGER_TEXT'] = '네트워크 매니저';

// Admin Pages Post Type
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_NAME_TEXT'] = '렌트 페이지'; // name
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_SINGULAR_NAME_TEXT'] = '렌트 페이지'; // singular_name
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_MENU_NAME_TEXT'] = '렌트페이지'; // menu_name
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_PARENT_PAGE_COLON_TEXT'] = '부모 차 정보'; // parent_item_colon
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_ALL_PAGES_TEXT'] = '모든 차 정보'; // all_items
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_VIEW_PAGE_TEXT'] = '차정보 보기'; // view_item
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_ADD_NEW_PAGE_TEXT'] = '새로운 차 입력 페이지'; // add_new_item
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_ADD_NEW_TEXT'] = '새로 추가 페이지'; // add_new
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_EDIT_PAGE_TEXT'] = '차 수정 페이지'; // edit_item
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_UPDATE_PAGE_TEXT'] = '차 업데이트 페이지'; // update_item
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_SEARCH_PAGES_TEXT'] = '차 검색 페이지'; // search_items
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_NOT_FOUND_TEXT'] = '발견되지 않음'; // not_found
$lang['NRS_ADMIN_PAGE_POST_TYPE_LABEL_NOT_FOUND_IN_TRASH_TEXT'] = '발견되지 않음 쓰레기통'; // not_found_in_trash
$lang['NRS_ADMIN_PAGE_POST_TYPE_DESCRIPTION_TEXT'] = '차정보 리스트';

// Admin Item Post Type
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_NAME_TEXT'] = '차 페이지'; // name
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_SINGULAR_NAME_TEXT'] = '차 페이지'; // singular_name
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_MENU_NAME_TEXT'] = '차 페이지'; // menu_name
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_PARENT_ITEM_COLON_TEXT'] = '부모 차'; // parent_item_colon
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_ALL_ITEMS_TEXT'] = '모든 차 페이지'; // all_items
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_VIEW_ITEM_TEXT'] = '차 페이지 보기'; // view_item
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_ADD_NEW_ITEM_TEXT'] = '새 차 페이지'; // add_new_item
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_ADD_NEW_TEXT'] = '새로 더하기 페이지'; // add_new
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_EDIT_ITEM_TEXT'] = '아이템 수정'; // edit_item
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_UPDATE_ITEM_TEXT'] = '차 업데이트 페이지'; // update_item
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_SEARCH_ITEMS_TEXT'] = '차 검색 페이지'; // search_items
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_NOT_FOUND_TEXT'] = '발견되지 않음'; // not_found
$lang['NRS_ADMIN_ITEM_POST_TYPE_LABEL_NOT_FOUND_IN_TRASH_TEXT'] = '쓰레기통에서 발견되지 않음'; // not_found_in_trash
$lang['NRS_ADMIN_ITEM_POST_TYPE_DESCRIPTION_TEXT'] = '차 리스트 페이지';

// Admin Location Post Type
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_NAME_TEXT'] = '차 위치'; // name
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_SINGULAR_NAME_TEXT'] = '차 위치'; // singular_name
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_MENU_NAME_TEXT'] = '차 위치'; // menu_name
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_PARENT_LOCATION_COLON_TEXT'] = '부모 차 위치'; // parent_item_colon
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_ALL_LOCATIONS_TEXT'] = '모든 차 위치'; // all_items
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_VIEW_LOCATION_TEXT'] = '모든 차 위치'; // view_item
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_ADD_NEW_LOCATION_TEXT'] = '새로운 위치 페이지'; // add_new_item
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_ADD_NEW_TEXT'] = '새로운 페이지'; // add_new
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_EDIT_LOCATION_TEXT'] = '차 위치 수정 페이지'; // edit_item
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_UPDATE_LOCATION_TEXT'] = '차 위치 업데이트 페이지'; // update_item
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_SEARCH_LOCATIONS_TEXT'] = '차 위치 검색 페이지'; // search_items
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_NOT_FOUND_TEXT'] = '발견 안 됨'; // not_found
$lang['NRS_ADMIN_LOCATION_POST_TYPE_LABEL_NOT_FOUND_IN_TRASH_TEXT'] = '쓰레기통에서도 발견 안 됨'; // not_found_in_trash
$lang['NRS_ADMIN_LOCATION_POST_TYPE_DESCRIPTION_TEXT'] = '차 위치 리스트 페이지';

// Admin Core
$lang['NRS_ADMIN_EDIT_TEXT'] = '수정';
$lang['NRS_ADMIN_DELETE_TEXT'] = '삭제';
$lang['NRS_ADMIN_CANCEL_TEXT'] = '취소';
$lang['NRS_ADMIN_UNBLOCK_TEXT'] = '블락헤제';
$lang['NRS_ADMIN_MARK_PAID_TEXT'] = '결제완료로 표시';
$lang['NRS_ADMIN_MARK_COMPLETED_EARLY_TEXT'] = '일찍 끝남';
$lang['NRS_ADMIN_REFUND_TEXT'] = '환불';
$lang['NRS_ADMIN_SELECT_LOCATION_TEXT'] = '-- 위치 선택 --';
$lang['NRS_ADMIN_ALL_LOCATIONS_TEXT'] = '모든 위치';
$lang['NRS_ADMIN_AVAILABLE_TEXT'] = '가능';
$lang['NRS_ADMIN_DISPLAYED_TEXT'] = '디스플레이';
$lang['NRS_ADMIN_VISIBLE_TEXT'] = '보임';
$lang['NRS_ADMIN_HIDDEN_TEXT'] = '숨김';
$lang['NRS_ADMIN_ENABLED_TEXT'] = '가능';
$lang['NRS_ADMIN_DISABLED_TEXT'] = '불가능';
$lang['NRS_ADMIN_ALLOWED_TEXT'] = '허락';
$lang['NRS_ADMIN_FAILED_TEXT'] = '실패';
$lang['NRS_ADMIN_BLOCKED_TEXT'] = '블락된';
$lang['NRS_ADMIN_REQUEST_TEXT'] = '요청';
$lang['NRS_ADMIN_REQUESTS_TEXT'] = '요청들';
$lang['NRS_ADMIN_IP_TEXT'] = '아이피';
$lang['NRS_ADMIN_CHECK_TEXT'] = 'Check';
$lang['NRS_ADMIN_SKIP_TEXT'] = 'Skip';
$lang['NRS_ADMIN_YES_TEXT'] = '예';
$lang['NRS_ADMIN_NO_TEXT'] = '아니오';
$lang['NRS_ADMIN_DAILY_TEXT'] = '하루당';
$lang['NRS_ADMIN_HOURLY_TEXT'] = '시간당';
$lang['NRS_ADMIN_PER_BOOKING_TEXT'] = '예약 당';
$lang['NRS_ADMIN_COMBINED_TEXT'] = '시간당과 하루당 모두';
$lang['NRS_ADMIN_NEVER_TEXT'] = '네버';
$lang['NRS_ADMIN_DROPDOWN_TEXT'] = '드롭다운';
$lang['NRS_ADMIN_SLIDER_TEXT'] = '슬라이더';
$lang['NRS_ADMIN_SELECT_DEMO_TEXT'] = ' --- 예제 선택 --- ';
$lang['NRS_ADMIN_WITHOUT_TRANSLATION_TEXT'] = '번역 없이';
$lang['NRS_ADMIN_VIEW_PAGE_IN_NEW_WINDOW_TEXT'] = '새 창에서 열기';

// Core
$lang['NRS_IMAGE_ALT_TEXT'] = '이미지';
$lang['NRS_PER_BOOKING_SHORT_TEXT'] = '';
$lang['NRS_PER_DAY_SHORT_TEXT'] = 'd.';
$lang['NRS_PER_HOUR_SHORT_TEXT'] = '시간';
$lang['NRS_PER_BOOKING_TEXT'] = '예약';
$lang['NRS_PER_DAY_TEXT'] = '하루';
$lang['NRS_PER_HOUR_TEXT'] = '시간';
$lang['NRS_SELECT_DATE_TEXT'] = '날짜';
$lang['NRS_SELECT_YEAR_TEXT'] = '연도';
$lang['NRS_SELECT_MONTH_TEXT'] = '달';
$lang['NRS_SELECT_DAY_TEXT'] = '하루';
$lang['NRS_PRICE_TEXT'] = '가격';
$lang['NRS_PERIOD_TEXT'] = '기간';
$lang['NRS_DURATION_TEXT'] = '기간';
$lang['NRS_MON_TEXT'] = '월';
$lang['NRS_TUE_TEXT'] = '화';
$lang['NRS_WED_TEXT'] = '수';
$lang['NRS_THU_TEXT'] = '목';
$lang['NRS_FRI_TEXT'] = '금';
$lang['NRS_SAT_TEXT'] = '토';
$lang['NRS_SUN_TEXT'] = '일';
$lang['NRS_LUNCH_TEXT'] = '점심';
$lang['NRS_MONDAYS_TEXT'] = '월요일';
$lang['NRS_TUESDAYS_TEXT'] = '화요일';
$lang['NRS_WEDNESDAYS_TEXT'] = '수요일';
$lang['NRS_THURSDAYS_TEXT'] = '목요일';
$lang['NRS_FRIDAYS_TEXT'] = '금요일';
$lang['NRS_SATURDAYS_TEXT'] = '토요일';
$lang['NRS_SUNDAYS_TEXT'] = '일요일';
$lang['NRS_LUNCH_TIME_TEXT'] = '점심 시간';
$lang['NRS_ALL_YEAR_TEXT'] = 'All Year';
$lang['NRS_ALL_DAY_TEXT'] = '24 시간';
$lang['NRS_PARTIAL_DAY_TEXT'] = '%s - 12:00 AM';
$lang['NRS_MIDNIGHT_TEXT'] = '00:00';
$lang['NRS_NOON_TEXT'] = '12:00';
$lang['NRS_CLOSED_TEXT'] = '닫힘';
$lang['NRS_OPEN_TEXT'] = '열림';
$lang['NRS_TODAY_TEXT'] = 'Today';
$lang['NRS_DATE_TEXT'] = '날짜';
$lang['NRS_TIME_TEXT'] = '시간';
$lang['NRS_DAYS_TEXT'] = '하루들';
$lang['NRS_DAYS2_TEXT'] = '하루들';
$lang['NRS_DAY_TEXT'] = '하루';
$lang['NRS_HOURS_TEXT'] = '시간';
$lang['NRS_HOURS2_TEXT'] = '시간';
$lang['NRS_HOUR_TEXT'] = '시간';
$lang['NRS_MINUTES_TEXT'] = '분';
$lang['NRS_MINUTES2_TEXT'] = '분';
$lang['NRS_MINUTE_TEXT'] = '분';
$lang['NRS_DAILY_TEXT'] = '매일';
$lang['NRS_HOURLY_TEXT'] = '시간당';
$lang['NRS_ON_ST_TEXT'] = 'st'; // On January 21st
$lang['NRS_ON_ND_TEXT'] = 'nd'; // On January 21st
$lang['NRS_ON_RD_TEXT'] = 'rd'; // On January 21st
$lang['NRS_ON_TH_TEXT'] = 'th'; // On January 21st
$lang['NRS_ON_TEXT'] = '온'; // on
$lang['NRS_THE_ST_TEXT'] = 'st'; // 1st, do the search
$lang['NRS_THE_ND_TEXT'] = 'nd'; // 2nd, select an item
$lang['NRS_THE_RD_TEXT'] = 'rd'; // 3rd, choose extras
$lang['NRS_THE_TH_TEXT'] = 'th'; // 4th, enter your booking details
$lang['NRS_UNCLASSIFIED_ITEM_TYPE_TEXT'] = '다른';
$lang['NRS_NO_ITEMS_AVAILABLE_TEXT'] = '차 없음';
$lang['NRS_NO_ITEMS_AVAILABLE_IN_THIS_CLASS_TEXT'] = '그 종류의 차 없음';
$lang['NRS_NO_EXTRAS_AVAILABLE_TEXT'] = '엑스트라 없음';
$lang['NRS_NO_MANUFACTURERS_AVAILABLE_TEXT'] = 'No manufacturers available';
$lang['NRS_NO_LOCATIONS_AVAILABLE_TEXT'] = 'No locations available';
$lang['NRS_NO_BENEFITS_AVAILABLE_TEXT'] = 'No benefits available';
$lang['NRS_NA_TEXT'] = '없음';
$lang['NRS_NONE_TEXT'] = '없음';
$lang['NRS_NOT_SET_TEXT'] = '안 됨';
$lang['NRS_DO_NOT_EXIST_TEXT'] = '존재하지 않음';
$lang['NRS_EXIST_TEXT'] = '존재함';
$lang['NRS_NOT_REQ_TEXT'] = 'Not req.';
$lang['NRS_REQ_TEXT'] = 'Req.';
$lang['NRS_NOT_REQUIRED_TEXT'] = '안 됨';
$lang['NRS_REQUIRED_TEXT'] = '리콰이어됨';
$lang['NRS_DONT_DISPLAY_TEXT'] = '숨기기';
$lang['NRS_WITH_TAX_TEXT'] = '세괌 포함';
$lang['NRS_WITHOUT_TAX_TEXT'] = '세금 미포함';
$lang['NRS_TAX_TEXT'] = '세금';
$lang['NRS_FROM_TEXT'] = '으로부터';
$lang['NRS_TO_TEXT'] = '에게';
$lang['NRS_ALL_TEXT'] = 'All';
$lang['NRS_OR_TEXT'] = '혹은';
$lang['NRS_AND_TEXT'] = '그리고';
$lang['NRS_UNLIMITED_TEXT'] = '무제한';
$lang['NRS_DEPOSIT_TEXT'] = '선입금';
$lang['NRS_DISCOUNT_TEXT'] = '세일';
$lang['NRS_PREPAYMENT_TEXT'] = '선결제 금액';
$lang['NRS_TOTAL_TEXT'] = '총';
$lang['NRS_BACK_TEXT'] = '뒤로';
$lang['NRS_CONTINUE_TEXT'] = '계속하기';
$lang['NRS_SEARCH_TEXT'] = '검색';
$lang['NRS_SELECT_DROPDOWN_TEXT'] = '--- 선택 ---';
$lang['NRS_ITEM_TEXT'] = '차';
$lang['NRS_EXTRA_TEXT'] = '엑스트라';
$lang['NRS_RENTAL_OPTION_TEXT'] = '렌트 옵션';
$lang['NRS_ITEMS_TEXT'] = '차';
$lang['NRS_EXTRAS_TEXT'] = '엑스트라';
$lang['NRS_RENTAL_OPTIONS_TEXT'] = '렌트 옵션';
$lang['NRS_SHOW_ITEM_TEXT'] = '차 보기';
$lang['NRS_VIA_PARTNER_TEXT'] = '통해 %s';
$lang['NRS_COUPON_TEXT'] = '쿠폰';

// Booking step no. 1 - item search
$lang['NRS_BOOKING_TEXT'] = '예약';
$lang['NRS_PICKUP_TEXT'] = '픽업';
$lang['NRS_RETURN_TEXT'] = '리턴';
$lang['NRS_OTHER_TEXT'] = '다른';
$lang['NRS_INFORMATION_TEXT'] = '정보';
$lang['NRS_CITY_AND_LOCATION_TEXT'] = '도시 정보:';
$lang['NRS_PICKUP_CITY_AND_LOCATION_TEXT'] = '픽업 도시 정보:';
$lang['NRS_RETURN_CITY_AND_LOCATION_TEXT'] = '리턴 도시 정보:';
$lang['NRS_SELECT_BOOKING_DATE_TEXT'] = '날짜:';
$lang['NRS_SELECT_BOOKING_PERIOD_TEXT'] = '예약 날짜:';
$lang['NRS_COUPON_CODE_TEXT'] = '쿠폰 코드';
$lang['NRS_I_HAVE_BOOKING_CODE_TEXT'] = '예약 코드:';
$lang['NRS_I_HAVE_COUPON_CODE_TEXT'] = '쿠폰코드:';
$lang['NRS_PICKUP_LOCATION_TEXT'] = '픽업 장소';
$lang['NRS_RETURN_LOCATION_TEXT'] = '리턴 장소';
$lang['NRS_ALL_BODY_TYPES_DROPDOWN_TEXT'] = '---- 모든 타입 ----';
$lang['NRS_ALL_TRANSMISSION_TYPES_DROPDOWN_TEXT'] = '---- 모든 트랜스미션 ----';
$lang['NRS_SELECT_PICKUP_LOCATION_TEXT'] = '-- 픽업 장소 --';
$lang['NRS_SELECT_RETURN_LOCATION_TEXT'] = '-- 리턴 장소 --';
$lang['NRS_PICKUP_DATE_TEXT'] = '픽업 날짜';
$lang['NRS_RETURN_DATE_TEXT'] = '리턴 날짜';
$lang['NRS_PICKUP_DATE_ALERT_TEXT'] = '픽업 날짜 선택!';
$lang['NRS_RETURN_DATE_ALERT_TEXT'] = '리턴 날짜 선택!';
$lang['NRS_BOOKING_PERIOD_ALERT_TEXT'] = '예약 날짜 선택!';
$lang['NRS_PICKUP_LOCATION_ALERT_TEXT'] = '픽업 장소 선택!';
$lang['NRS_RETURN_LOCATION_ALERT_TEXT'] = '리턴 날짜 선택!';
$lang['NRS_COUPON_CODE_ALERT_TEXT'] = '쿠폰 코드 입력!';
$lang['NRS_SHOW_ITEM_DESCRIPTION_TEXT'] = '차 정보 보기';
$lang['NRS_UPDATE_BOOKING_TEXT'] = '예약 업데이트';
$lang['NRS_CANCEL_BOOKING_TEXT'] = '예약 취소';
$lang['NRS_CHANGE_BOOKING_DATE_TIME_AND_LOCATION_TEXT'] = '날짜 시간 장소 수정';
$lang['NRS_CHANGE_BOOKED_ITEMS_TEXT'] = '차 수정';
$lang['NRS_CHANGE_EXTRAS_TEXT'] = '엑스트라 수정';
$lang['NRS_CHANGE_RENTAL_OPTIONS_TEXT'] = '렌트 옵션 수정';
$lang['NRS_IN_THIS_LOCATION_TEXT'] = '여기서';
$lang['NRS_AFTERHOURS_PICKUP_IS_NOT_ALLOWED_TEXT'] = '불가';
$lang['NRS_AFTERHOURS_RETURN_IS_NOT_ALLOWED_TEXT'] = '불가';

// Booking step no. 2 - search results
$lang['NRS_DISTANCE_AWAY_TEXT'] = '%s 멈';
$lang['NRS_BOOKING_DATA_TEXT'] = '예약 정보';
$lang['NRS_BOOKING_CODE_TEXT'] = '예약 코드';
$lang['NRS_BOOKING_EDIT_TEXT'] = '수정';
$lang['NRS_BOOKING_PICKUP_TEXT'] = '픽업';
$lang['NRS_BOOKING_BUSINESS_HOURS_TEXT'] = '오픈시간';
$lang['NRS_BOOKING_FEE_TEXT'] = '수수료';
$lang['NRS_BOOKING_RETURN_TEXT'] = '리턴';
$lang['NRS_BOOKING_NIGHTLY_RATE_TEXT'] = '에프터아워';
$lang['NRS_BOOKING_AFTERHOURS_TEXT'] = '에프터아워';
$lang['NRS_BOOKING_EARLY_TEXT'] = 'Early';
$lang['NRS_BOOKING_LATE_TEXT'] = 'Late';
$lang['NRS_BOOKING_AFTERHOURS_PICKUP_TEXT'] = '에프터아워 픽업';
$lang['NRS_BOOKING_AFTERHOURS_PICKUP_IMPOSSIBLE_TEXT'] = '불가능';
$lang['NRS_BOOKING_AFTERHOURS_RETURN_TEXT'] = '에프터아워 리턴';
$lang['NRS_BOOKING_AFTERHOURS_RETURN_IMPOSSIBLE_TEXT'] = '불가능';
$lang['NRS_CHOOSE_TEXT'] = '선택';
$lang['NRS_SEARCH_RESULTS_TEXT'] = '검색 결과';
$lang['NRS_MILEAGE_TEXT'] = '마일리지';

// Booking step no. 3 - booking options
$lang['NRS_SELECT_RENTAL_OPTIONS_TEXT'] = '렌트 옵션';
$lang['NRS_SELECTED_ITEMS_TEXT'] = '선택된 차';
$lang['NRS_FOR_DEPENDANT_ITEM_TEXT'] = ' (를 %s)';
$lang['NRS_NO_EXTRAS_AVAILABLE_CLICK_CONTINUE_TEXT'] = '엑스트라 없음. 컨티뉴';

// Booking step no. 4 - booking details
$lang['NRS_PICKUP_DATE_AND_TIME_TEXT'] = '픽업 날짜 시간';
$lang['NRS_RETURN_DATE_AND_TIME_TEXT'] = '리턴 날짜 시간';
$lang['NRS_UNIT_PRICE_TEXT'] = '개당 가격';
$lang['NRS_QUANTITY_TEXT'] = '수량';
$lang['NRS_QUANTITY_SHORT_TEXT'] = '수.';
$lang['NRS_PICKUP_FEE_TEXT'] = '픽업 수수료';
$lang['NRS_RETURN_FEE_TEXT'] = '리턴 수수료';
$lang['NRS_NIGHTLY_PICKUP_RATE_APPLIED_TEXT'] = '(밤 가격 )';
$lang['NRS_NIGHTLY_RETURN_RATE_APPLIED_TEXT'] = '(밤 가격)';
$lang['NRS_ITEM_QUANTITY_SUFFIX_TEXT'] = '자동차';
$lang['NRS_EXTRA_QUANTITY_SUFFIX_TEXT'] = '엑스트라';
$lang['NRS_PAY_NOW_OR_AT_PICKUP_TEXT'] = '지금결제/마나서결제';
$lang['NRS_PAY_NOW_TEXT'] = '지금결제';
$lang['NRS_PAY_AT_PICKUP_TEXT'] = '만나서결제';
$lang['NRS_PAY_LATER_OR_ON_RETURN_TEXT'] = '나중에결제';
$lang['NRS_PAY_LATER_TEXT'] = '나중에결제';
$lang['NRS_PAY_ON_RETURN_TEXT'] = '리턴 때 결제';
$lang['NRS_ITEM_RENTAL_DETAILS_TEXT'] = '렌트 정보';
$lang['NRS_MANUFACTURER_TEXT'] = '제조사';
$lang['NRS_ITEM_MODEL_TEXT'] = '모델';
$lang['NRS_GROSS_TOTAL_TEXT'] = '합계';
$lang['NRS_GRAND_TOTAL_TEXT'] = '총 합계';
$lang['NRS_BOOKING_DETAILS_TEXT'] = '예약 정보';
$lang['NRS_CUSTOMER_DETAILS_TEXT'] = '고객 정보';
$lang['NRS_EXISTING_CUSTOMER_DETAILS_TEXT'] = '고객 검색 하기';
$lang['NRS_EXISTING_CUSTOMER_TEXT'] = '존재 고객';
$lang['NRS_EMAIL_ADDRESS_TEXT'] = '이메일 주소';
$lang['NRS_FETCH_CUSTOMER_DETAILS_TEXT'] = '결과';
$lang['NRS_OR_ENTER_NEW_DETAILS_TEXT'] = '새 계정 만들기';
$lang['NRS_CUSTOMER_TEXT'] = '고객';
$lang['NRS_TITLE_TEXT'] = '이름';
$lang['NRS_MR_TEXT'] = '씨';
$lang['NRS_MS_TEXT'] = '씨';
$lang['NRS_MRS_TEXT'] = '씨';
$lang['NRS_MISS_TEXT'] = '씨';
$lang['NRS_DR_TEXT'] = '의사';
$lang['NRS_PROF_TEXT'] = '교수';
$lang['NRS_FIRST_NAME_TEXT'] = '이름';
$lang['NRS_LAST_NAME_TEXT'] = '성';
$lang['NRS_DATE_OF_BIRTH_TEXT'] = '생년월일';
$lang['NRS_YEAR_OF_BIRTH_TEXT'] = '생년';
$lang['NRS_ADDRESS_TEXT'] = '주소';
$lang['NRS_STREET_ADDRESS_TEXT'] = '주소';
$lang['NRS_CITY_TEXT'] = '도시';
$lang['NRS_STATE_TEXT'] = '스테이트';
$lang['NRS_ZIP_CODE_TEXT'] = '집코드';
$lang['NRS_COUNTRY_TEXT'] = '나라';
$lang['NRS_PHONE_TEXT'] = '핸드폰';
$lang['NRS_EMAIL_TEXT'] = '이메일';
$lang['NRS_ADDITIONAL_COMMENTS_TEXT'] = '추가 정보';
$lang['NRS_CUSTOMER_ID_TEXT'] = '고객 아이디';
$lang['NRS_IP_ADDRESS_TEXT'] = '아이피 주소';
$lang['NRS_PAY_BY_SHORT_TEXT'] = '페이';
$lang['NRS_I_AGREE_WITH_TERMS_AND_CONDITIONS_TEXT'] = '계약서에 동의합니다';
$lang['NRS_TERMS_AND_CONDITIONS_TEXT'] = '계약서';
$lang['NRS_CONFIRM_TEXT'] = '완료';
$lang['NRS_FIELD_REQUIRED_TEXT'] = '꼭 입력해야합니다. ';

// Booking step no. 5 - process booking
$lang['NRS_PAYMENT_DETAILS_TEXT'] = '결제 디테일';
$lang['NRS_PAYMENT_OPTION_TEXT'] = '페이';
$lang['NRS_PAYER_EMAIL_TEXT'] = '결제자 이메일';
$lang['NRS_TRANSACTION_ID_TEXT'] = '결제 아이디';
$lang['NRS_PROCESSING_PAYMENT_TEXT'] = '결제 중...';
$lang['NRS_PLEASE_WAIT_UNTIL_PAYMENT_WILL_BE_PROCESSED_TEXT'] = '좀만 기다려주세요...';

//display-booking-confirm.php
$lang['NRS_STEP5_PAY_ONLINE_TEXT'] = '온라인 페이';
$lang['NRS_STEP5_PAY_AT_PICKUP_TEXT'] = '픽업 페이';
$lang['NRS_THANK_YOU_TEXT'] = '감사합니다!';
$lang['NRS_YOUR_BOOKING_CONFIRMED_TEXT'] = '완료되었습니다. 예약 코드: ';
$lang['NRS_INVOICE_SENT_TO_YOUR_EMAIL_ADDRESS_TEXT'] = '영수증이 이메일로 보내졌습니다. ';

//display-booking-failure.php
$lang['NRS_BOOKING_FAILURE_TEXT'] = '예약 실패';
$lang['NRS_BOOKING_FAILURE_SEARCH_ALL_ITEMS_TEXT'] = '차 검색';

// display-item-price-table.php
$lang['NRS_DAY_PRICE_TEXT'] = '하루 가격';
$lang['NRS_HOUR_PRICE_TEXT'] = '시간 가격';
$lang['NRS_NO_ITEMS_IN_THIS_CATEGORY_TEXT'] = '차가 없습니다 ';
$lang['NRS_PRICE_FOR_DAY_FROM_TEXT'] = '하루당 가격 시작';
$lang['NRS_PRICE_FOR_HOUR_FROM_TEXT'] = '시간 당 가격 시작';
$lang['NRS_PRICE_WITH_APPLIED_TEXT'] = '적용된';
$lang['NRS_WITH_APPLIED_DISCOUNT_TEXT'] = '세일';

// class.ItemsAvailability.php
$lang['NRS_MONTH_DAY_TEXT'] = '하루';
$lang['NRS_MONTH_DAYS_TEXT'] = '하루';
$lang['NRS_ITEMS_AVAILABILITY_FOR_TEXT'] = '가능한 날짜';
$lang['NRS_ITEMS_AVAILABILITY_IN_NEXT_30_DAYS_TEXT'] = '30일 이내 가능 차';
$lang['NRS_ITEMS_PARTIAL_AVAILABILITY_FOR_TEXT'] = '부분 차 가능 날';
$lang['NRS_ITEMS_AVAILABILITY_THIS_MONTH_TEXT'] = '이번달 차 스케쥴'; // Not used
$lang['NRS_ITEMS_AVAILABILITY_NEXT_MONTH_TEXT'] = '다음달 차 스케쥴'; // Not used
$lang['NRS_ITEM_ID_TEXT'] = '아이디:';
$lang['NRS_TOTAL_ITEMS_TEXT'] = '모든 차:';

// class.ExtrasAvailability.php
$lang['NRS_EXTRAS_AVAILABILITY_FOR_TEXT'] = '엑스트라 가능한';
$lang['NRS_EXTRAS_AVAILABILITY_IN_NEXT_30_DAYS_TEXT'] = '30일 이내 가능한 엑스트라';
$lang['NRS_EXTRAS_PARTIAL_AVAILABILITY_FOR_TEXT'] = '부분 가능한 엑스트라';
$lang['NRS_EXTRAS_AVAILABILITY_THIS_MONTH_TEXT'] = '이번달 엑스트라'; // Not used
$lang['NRS_EXTRAS_AVAILABILITY_NEXT_MONTH_TEXT'] = '다음달 엑스트라'; // Not used
$lang['NRS_EXTRA_ID_TEXT'] = '아이디';
$lang['NRS_TOTAL_EXTRAS_TEXT'] = '모든 엑스트라:';

// class.ItemsController.php
$lang['NRS_SHOW_ITEM_PAGE_TEXT'] = '차 정보보기';
$lang['NRS_PARTNER_TEXT'] = '파트너';
$lang['NRS_BODY_TYPE_TEXT'] = '클래스';
$lang['NRS_TRANSMISSION_TYPE_TEXT'] = '트랜스미션';
$lang['NRS_FUEL_TYPE_TEXT'] = '연료';
$lang['NRS_ITEM_FUEL_CONSUMPTION_TEXT'] = '연료사용';
$lang['NRS_ITEM_PASSENGERS_TEXT'] = '최대 승객';
$lang['NRS_ITEM_PRICE_FROM_TEXT'] = '시작 가';
$lang['NRS_INQUIRE_TEXT'] = 'Call';
$lang['NRS_GET_A_QUOTE_TEXT'] = '가격 요청';
$lang['NRS_ITEM_FEATURES_TEXT'] = '정보';
$lang['NRS_BOOK_ITEM_TEXT'] = '빌리기';

// class.LocationsController.php
$lang['NRS_LOCATIONS_BUSINESS_HOURS_TEXT'] = 'Business Hours';
$lang['NRS_LOCATION_FEES_TEXT'] = '장소 수수료';
$lang['NRS_EARLY_PICKUP_TEXT'] = 'Early Pick-Up';
$lang['NRS_LATE_PICKUP_TEXT'] = 'Late Pick-Up';
$lang['NRS_EARLY_RETURN_TEXT'] = 'Early Return';
$lang['NRS_LATE_RETURN_TEXT'] = 'Late Return';
$lang['NRS_EARLY_PICKUP_FEE_TEXT'] = 'Early pick-up fee';
$lang['NRS_LATE_RETURN_FEE_TEXT'] = 'Late return fee';
$lang['NRS_VIEW_LOCATION_TEXT'] = 'View Location';

// class.SingleItemController.php
$lang['NRS_ITEM_ENGINE_CAPACITY_TEXT'] = '엔진 스펙';
$lang['NRS_ITEM_LUGGAGE_TEXT'] = '최대 러기지';
$lang['NRS_ITEM_DOORS_TEXT'] = '문짝';
$lang['NRS_ITEM_DRIVER_AGE_TEXT'] = '최소 연령';
$lang['NRS_ADDITIONAL_INFORMATION_TEXT'] = '추가 정보';

// class.SingleLocationController.php
$lang['NRS_CONTACTS_TEXT'] = 'Contacts';
$lang['NRS_CONTACT_DETAILS_TEXT'] = 'Contact Details';
$lang['NRS_BUSINESS_HOURS_FEES_TEXT'] = 'Business Hours Fees';
$lang['NRS_AFTERHOURS_FEES_TEXT'] = 'After Hours Fees';

// template.BookingCancelled.php
$lang['NRS_CANCELLED_SUCCESSFULLY_TEXT'] = '성공적으로 취소';
$lang['NRS_NOT_CANCELLED_TEXT'] = '취소 안 되었습';

// template.Step8EditBooking.php
$lang['NRS_EDIT_TEXT'] = '추가';
$lang['NRS_BOOKING2_TEXT'] = '예약';
$lang['NRS_EDIT_BOOKING_BUTTON_TEXT'] = '예약 수정';
$lang['NRS_EDIT_BOOKING_PLEASE_ENTER_BOOKING_NUMBER_TEXT'] = '예약 넘버 입력!';

// Admin System Errors
// Unfortunately, they are untranslatable
$lang['NRS_ERROR_IN_METHOD_TEXT'] = '에러 %s 메소드: ';

// Exceptions
$lang['NRS_ERROR_CANNOT_BIND_TEMPLATE_VARIABLE_TEXT'] = '찾을 수 없음 &#39;templateFile&#39;.';
$lang['NRS_ERROR_TEMPLATE_NOT_EXIST_TEXT'] = '템플릿 파일 %s 존재하지 않음.';

// Errors
$lang['NRS_ERROR_EXTENSION_NAME_TEXT'] = '렌트카 시스템';
$lang['NRS_ERROR_REQUIRED_FIELD_TEXT'] = '필수 항목';
$lang['NRS_ERROR_IS_EMPTY_TEXT'] = '빔';
$lang['NRS_ERROR_SLIDER_CANT_BE_DISPLAYED_TEXT'] = '슬라이더 에러';
$lang['NRS_ERROR_CUSTOMER_DETAILS_NOT_FOUND_TEXT'] = '계정이 없습니다. 새로운 계정.';
$lang['NRS_ERROR_CUSTOMER_DETAILS_NO_ERROR_TEXT'] = '에러 없음';
$lang['NRS_ERROR_EXCEEDED_CUSTOMER_LOOKUP_ATTEMPTS_TEXT'] = '시도 한도를 넘었습니다. 매뉴얼로 작성해주세요.';
$lang['NRS_ERROR_CUSTOMER_DETAILS_UNKNOWN_ERROR_TEXT'] = '이상한 에러';
$lang['NRS_ERROR_BOOKING_DOES_NOT_EXIST_TEXT'] = '존재안함';
$lang['NRS_ERROR_PLEASE_SELECT_AT_LEAST_ONE_ITEM_TEXT'] = '하나는 선택';
$lang['NRS_ERROR_ITEM_NOT_AVAILABLE_TEXT'] = '불가능.';
$lang['NRS_ERROR_NO_ITEM_AVAILABLE_PLEASE_TRY_DIFFERENT_DATE_TEXT'] = '차없음. 검색을 바꿔보세요.';
$lang['NRS_ERROR_SEARCH_ENGINE_DISABLED_TEXT'] = '온라인 예약이 마비됨 나중에 다시 시도.';
$lang['NRS_ERROR_OUT_BEFORE_IN_TEXT'] = '리턴 날짜 입력 이상. 다시입력.';
$lang['NRS_ERROR_MINIMUM_NUMBER_OF_NIGHT_SHOULD_NOT_BE_LESS_THAN_TEXT'] = '최대 날짜 에러';
$lang['NRS_ERROR_PLEASE_MODIFY_YOUR_SEARCH_CRITERIA_TEXT'] = '검색 설정 에러.';
$lang['NRS_ERROR_PICKUP_IS_NOT_POSSIBLE_ON_TEXT'] = '픽업이 불가능한';
$lang['NRS_ERROR_PLEASE_MODIFY_YOUR_PICKUP_TIME_BY_WEBSITE_TIME_TEXT'] = '픽업날짜 수정 현재에 따라.';
$lang['NRS_ERROR_CURRENT_DATE_TIME_TEXT'] = '렌트 장소 &amp; 시간은';
$lang['NRS_ERROR_EARLIEST_POSSIBLE_PICKUP_DATE_TIME_TEXT'] = '일찍 날짜 &amp; 시간';
$lang['NRS_ERROR_OR_NEXT_BUSINESS_HOURS_OF_PICKUP_LOCATION_TEXT'] = '픽업날짜와 이런 시간들';
$lang['NRS_ERROR_PICKUP_DATE_CANT_BE_LESS_THAN_RETURN_DATE_TEXT'] = '픽업날짜 &amp; 되지가 않음. 다른 날짜 선택';
$lang['NRS_ERROR_PICKUP_LOCATION_IS_CLOSED_AT_THIS_DATE_TEXT'] = '픽업 %s 장소 %s 안됨 (%s).';
$lang['NRS_ERROR_PICKUP_LOCATION_IS_CLOSED_AT_THIS_TIME_TEXT'] = '픽업 %s 장소 %s 날짜안됨 (%s).';
$lang['NRS_ERROR_RETURN_LOCATION_IS_CLOSED_AT_THIS_DATE_TEXT'] = '리턴 %s 장소 %s 이날짜안됨(%s).';
$lang['NRS_ERROR_RETURN_LOCATION_IS_CLOSED_AT_THIS_TIME_TEXT'] = '리턴 %s 장소 %s 이날짜안됨 (%s).';
$lang['NRS_ERROR_AFTERHOURS_PICKUP_LOCATION_IS_CLOSED_AT_THIS_TIME_TEXT'] = '에프터아워 %s 장소 %s 장소가 잠김.';
$lang['NRS_ERROR_AFTERHOURS_RETURN_LOCATION_IS_CLOSED_AT_THIS_TIME_TEXT'] = '에프터아워 장소 %s 주소 %s 하지만 잠김.';
$lang['NRS_ERROR_LOCATION_OPEN_HOURS_ARE_TEXT'] = '이장소는 이시간에 %s, %s 을 %s.';
$lang['NRS_ERROR_LOCATION_WEEKLY_OPEN_HOURS_ARE_TEXT'] = '이장소는 다음주에:';
$lang['NRS_ERROR_AFTERHOURS_PICKUP_LOCATION_OPEN_HOURS_ARE_TEXT'] = '에프터아워 픽업 시간들은 %s.';
$lang['NRS_ERROR_AFTERHOURS_RETURN_LOCATION_OPEN_HOURS_ARE_TEXT'] = '에프터아워 리턴 시간들은 %s.';
$lang['NRS_ERROR_AFTERHOURS_PICKUP_IS_NOT_ALLOWED_AT_LOCATION_TEXT'] = '에프터아우어 안되는 ㅈ아소.';
$lang['NRS_ERROR_AFTERHOURS_RETURN_IS_NOT_ALLOWED_AT_LOCATION_TEXT'] = '에프터아우어 안되는 장소.';
$lang['NRS_ERROR_MAXIMUM_BOOKING_LENGTH_CANT_BE_MORE_THAN_TEXT'] = '최대 날짜가 안되는 (in days)';
$lang['NRS_ERROR_INVALID_BOOKING_CODE_TEXT'] = '맞지 않는 예약 넘버.';
$lang['NRS_ERROR_INVALID_SECURITY_CODE_TEXT'] = '시큐리티 코드 안 맞음.';
$lang['NRS_ERROR_DRIVER_AGE_VIOLATION_FOR_ITEM_TEXT'] = 'Based on your birthdate, your age does not match the minimum driver age requirement for %s.';
$lang['NRS_ERROR_DRIVER_AGE_VIOLATION_TEXT'] = 'Based on your birthdate, your age does not match the minimum driver age requirement for one of selected cars.';
$lang['NRS_ERROR_DEPARTED_TEXT'] = '예약 %s 출발함, 수정불가능.';
$lang['NRS_ERROR_CANCELLED_TEXT'] = '예약. %s 취소됨.';
$lang['NRS_ERROR_REFUNDED_TEXT'] = '예약. %s 이미 환불되었음.';
$lang['NRS_ERROR_SYSTEM_IS_UNABLE_TO_SEND_EMAIL_TEXT'] = '에러: 이메일이 불가능. 설정이 잘못 되었거나 고객 정보가 잘못됨.';
$lang['NRS_ERROR_PAYMENT_METHOD_IS_NOT_YET_IMPLEMENTED_TEXT'] = '에러: 결제정보가 잘못됨, 이 시스템에 안 됨.';
$lang['NRS_ERROR_OTHER_BOOKING_ERROR_TEXT'] = 'Other reservation error. If you keep seeing this message, please contact website administrator.';

// Admin Discount controller
$lang['NRS_ADMIN_DISCOUNT_ITEM_BOOKING_IN_ADVANCE_TEXT'] = '추가/수정 차 세일';
$lang['NRS_ADMIN_DISCOUNT_ITEM_BOOKING_DURATION_TEXT'] = '추가/수정 차 기간';
$lang['NRS_ADMIN_DISCOUNT_EXTRA_BOOKING_IN_ADVANCE_TEXT'] = '추가/수정 부킹 엑스트라';
$lang['NRS_ADMIN_DISCOUNT_EXTRA_BOOKING_DURATION_TEXT'] = '추가/수정 기간';
$lang['NRS_ADMIN_DISCOUNT_DURATION_BEFORE_TEXT'] = '전:';
$lang['NRS_ADMIN_DISCOUNT_DURATION_UNTIL_TEXT'] = '까지:';
$lang['NRS_ADMIN_DISCOUNT_DURATION_FROM_TEXT'] = '으로:';
$lang['NRS_ADMIN_DISCOUNT_DURATION_TILL_TEXT'] = '까지:';

// Admin Settings Controller
$lang['NRS_ADMIN_SETTINGS_OKAY_GLOBAL_SETTINGS_UPDATED_TEXT'] = '성공 완료: 글로발 세팅!';
$lang['NRS_ADMIN_SETTINGS_OKAY_CUSTOMER_SETTINGS_UPDATED_TEXT'] = '성공 완료: 커스터머!';
$lang['NRS_ADMIN_SETTINGS_OKAY_SEARCH_SETTINGS_UPDATED_TEXT'] = '성공 완료: 검색 세팅!';
$lang['NRS_ADMIN_SETTINGS_OKAY_PRICE_SETTINGS_UPDATED_TEXT'] = '성공 완료: 가격 세팅!';

// OK / Error Messages - Benefit Element
$lang['NRS_BENEFIT_TITLE_EXISTS_ERROR_TEXT'] = '실패: 이경우엔 존재하지 않음 베네핏!';
$lang['NRS_BENEFIT_UPDATE_ERROR_TEXT'] = '실패: 이경우엔 존재하지 않음 MySQL!';
$lang['NRS_BENEFIT_UPDATED_TEXT'] = '실패: 이경우엔 존재하지 않음!';
$lang['NRS_BENEFIT_INSERT_ERROR_TEXT'] = '실패: 이경우엔 존재하지 않음 인서트 텍스트';
$lang['NRS_BENEFIT_INSERTED_TEXT'] = '실패: 이경우엔 존재하지 않음 인서드!';
$lang['NRS_BENEFIT_REGISTERED_TEXT'] = '실패: 이경우엔 존재하지 않음 레지스터.';
$lang['NRS_BENEFIT_DELETE_ERROR_TEXT'] = '실패: 이경우엔 존재하지 않음 에러!';
$lang['NRS_BENEFIT_DELETED_TEXT'] = '실패: 이경우엔 존재하지 않음 텍스트!';

// OK / Error Messages - Block Element
$lang['NRS_BLOCK_INSERT_ERROR_TEXT'] = 'Error: MySQL insert error for new block!';
$lang['NRS_BLOCK_INSERTED_TEXT'] = 'Completed: New block has been added successfully!';
$lang['NRS_BLOCK_DELETE_ERROR_TEXT'] = 'Error: MySQL delete error for existing block. No rows were deleted from database!';
$lang['NRS_BLOCK_DELETED_TEXT'] = 'Completed: Block has been deleted successfully!';
$lang['NRS_BLOCK_DELETE_OPTIONS_ERROR_TEXT'] = 'Failed: No cars or extras were deleted from block!';
$lang['NRS_BLOCK_OPTIONS_DELETED_TEXT'] = 'Completed: All cars and extras were deleted from block!';

// OK / Error Messages - Body Type Element
$lang['NRS_BODY_TYPE_TITLE_EXISTS_ERROR_TEXT'] = '실패: 이미 존재하는 바디타입!';
$lang['NRS_BODY_TYPE_UPDATE_ERROR_TEXT'] = '실패: 이미 존재하는 바디타입 MySQL!';
$lang['NRS_BODY_TYPE_UPDATED_TEXT'] = '성공: 바디 타입 성곡!';
$lang['NRS_BODY_TYPE_INSERT_ERROR_TEXT'] = '실패: 이미 존재하는 바디타입 이미!';
$lang['NRS_BODY_TYPE_INSERTED_TEXT'] = '성공: 모두 성공!';
$lang['NRS_BODY_TYPE_REGISTERED_TEXT'] = '바디타입 실패됨.';
$lang['NRS_BODY_TYPE_DELETE_ERROR_TEXT'] = '실패: 이미 존재하는 바디타입 이리어 머리아 마라아!';
$lang['NRS_BODY_TYPE_DELETED_TEXT'] = '성공: 바디타입 성공!';

// OK / Error Messages - Booking Element
$lang['NRS_BOOKING_UPDATE_ERROR_TEXT'] = '에러 이미 존재하는!';
$lang['NRS_BOOKING_UPDATED_TEXT'] = '완료 이미 오나료!';
$lang['NRS_BOOKING_INSERT_ERROR_TEXT'] = '에러 이미 존재하는 MySQL !';
$lang['NRS_BOOKING_INSERTED_TEXT'] = '완료가 도이습니다!';
$lang['NRS_BOOKING_CANCEL_ERROR_TEXT'] = '에러 이미 존재하는 이미 존재!';
$lang['NRS_BOOKING_CANCELLED_TEXT'] = '예약이 성공적!';
$lang['NRS_BOOKING_DELETE_ERROR_TEXT'] = '에러 이미 존재하는 서버가 데이트베이스에서!';
$lang['NRS_BOOKING_DELETED_TEXT'] = '완료: 성공적으로';
$lang['NRS_BOOKING_DELETE_OPTIONS_ERROR_TEXT'] = 'Failed: No cars or extras were deleted from reservation!';
$lang['NRS_BOOKING_OPTIONS_DELETED_TEXT'] = 'Completed: All cars and extras were deleted from reservation!';
$lang['NRS_BOOKING_MARK_AS_PAID_ERROR_TEXT'] = 'Failed: Reservation was not marked as paid!';
$lang['NRS_BOOKING_MARKED_AS_PAID_TEXT'] = 'Completed: Reservation was marked as paid!';
$lang['NRS_BOOKING_MARK_COMPLETED_EARLY_ERROR_TEXT'] = 'Failed: Reservation was not marked as completed early!';
$lang['NRS_BOOKING_MARKED_COMPLETED_EARLY_TEXT'] = 'Completed: Reservation was marked as completed early!';
$lang['NRS_BOOKING_REFUND_ERROR_TEXT'] = 'Failed: Reservation was not refunded!';
$lang['NRS_BOOKING_REFUNDED_TEXT'] = 'Completed: Reservation was refunded successfully!';

// OK / Error Messages - (Extra) Booking Option Element
$lang['NRS_EXTRA_BOOKING_ID_QUANTITY_OPTION_SKU_ERROR_TEXT'] = '에러아이디강 너러아리남 어러아 (%s), SKU (%s) 어러러 (%s)!';
$lang['NRS_EXTRA_BOOKING_OPTION_INSERT_ERROR_TEXT'] = '에러 아아러어 나러어 (SKU - %s) 예약!';
$lang['NRS_EXTRA_BOOKING_OPTION_INSERTED_TEXT'] = '완료: 새로 (SKU - %s) 언아!';
$lang['NRS_EXTRA_BOOKING_OPTION_DELETE_ERROR_TEXT'] = '새로 블라에서  MySQL 이라 작동잉라 데이트!';
$lang['NRS_EXTRA_BOOKING_OPTION_DELETED_TEXT'] = '성공 블락된!';

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
$lang['NRS_INSTALL_DEFAULT_COMPANY_NAME_TEXT'] = '렌트카 회사';
$lang['NRS_INSTALL_DEFAULT_COMPANY_STREET_ADDRESS_TEXT'] = '625 2nd Street';
$lang['NRS_INSTALL_DEFAULT_COMPANY_CITY_TEXT'] = 'San Francisco';
$lang['NRS_INSTALL_DEFAULT_COMPANY_STATE_TEXT'] = 'CA';
$lang['NRS_INSTALL_DEFAULT_COMPANY_ZIP_CODE_TEXT'] = '94107';
$lang['NRS_INSTALL_DEFAULT_COMPANY_COUNTRY_TEXT'] = '';
$lang['NRS_INSTALL_DEFAULT_COMPANY_PHONE_TEXT'] = '(450) 600 4000';
$lang['NRS_INSTALL_DEFAULT_COMPANY_EMAIL_TEXT'] = 'info@yourdomain.com';
$lang['NRS_INSTALL_DEFAULT_PAGE_URL_SLUG_TEXT'] = 'page'; // Latin letters only
$lang['NRS_INSTALL_DEFAULT_ITEM_URL_SLUG_TEXT'] = 'car'; // Latin letters only
$lang['NRS_INSTALL_DEFAULT_LOCATION_URL_SLUG_TEXT'] = 'location'; // Latin letters only
$lang['NRS_INSTALL_DEFAULT_SEARCH_PAGE_URL_SLUG_TEXT'] = 'search'; // Latin letters only
$lang['NRS_INSTALL_DEFAULT_CANCELLED_PAYMENT_PAGE_TITLE_TEXT'] = '결제 취소';
$lang['NRS_INSTALL_DEFAULT_CANCELLED_PAYMENT_PAGE_CONTENT_TEXT'] = '결제가 취소되었습니다. 예약이 취소되었습니다.';
$lang['NRS_INSTALL_DEFAULT_CONFIRMATION_PAGE_TITLE_TEXT'] = '예약 완료';
$lang['NRS_INSTALL_DEFAULT_CONFIRMATION_PAGE_CONTENT_TEXT'] = '결제가 완료되었습니다. 예약이 완료되었습니다.';
$lang['NRS_INSTALL_DEFAULT_TERMS_AND_CONDITIONS_PAGE_TITLE_TEXT'] = '렌트카 설명서';
$lang['NRS_INSTALL_DEFAULT_TERMS_AND_CONDITIONS_PAGE_CONTENT_TEXT'] = '렌트카를 이용하기 위해서는 가이드라인을 따라야만 합니다.';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAYPAL_TEXT'] = '온라인 - 페이팔';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAYPAL_DESCRIPTION_TEXT'] = '신속 결제';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_STRIPE_TEXT'] = '신용카드 (stripe.com)';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_BANK_TEXT'] = '계좌 이체';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_BANK_DETAILS_TEXT'] = '은행 정보';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAY_OVER_THE_PHONE_TEXT'] = '전화 결제';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAY_ON_ARRIVAL_TEXT'] = '만나서 결제';
$lang['NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAY_ON_ARRIVAL_DETAILS_TEXT'] = '신용카드 필요';
$lang['NRS_INSTALL_DEFAULT_DEAR_TEXT'] = '께';
$lang['NRS_INSTALL_DEFAULT_REGARDS_TEXT'] = '관하여';
$lang['NRS_INSTALL_DEFAULT_EMAIL_TITLE_BOOKING_DETAILS_TEXT'] = '예약 디테일 [BOOKING_CODE]';
$lang['NRS_INSTALL_DEFAULT_EMAIL_TITLE_BOOKING_CONFIRMED_TEXT'] = '예약 [BOOKING_CODE] - 완료';
$lang['NRS_INSTALL_DEFAULT_EMAIL_TITLE_BOOKING_CANCELLED_TEXT'] = '예약 [BOOKING_CODE] - 캔슬';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_TITLE_BOOKING_DETAILS_TEXT'] = '알림: 새 예약 - [BOOKING_CODE]';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_TITLE_BOOKING_CONFIRMED_TEXT'] = '알림: 예약 결제 완료 - [BOOKING_CODE]';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_TITLE_BOOKING_CANCELLED_TEXT'] = '알림: 예약 취소 - [BOOKING_CODE]';
$lang['NRS_INSTALL_DEFAULT_EMAIL_BODY_BOOKING_RECEIVED_TEXT'] = '예약 정보가 성공적으로 입력되었습니다.';
$lang['NRS_INSTALL_DEFAULT_EMAIL_BODY_BOOKING_DETAILS_TEXT'] = '예약 정보:';
$lang['NRS_INSTALL_DEFAULT_EMAIL_BODY_PAYMENT_RECEIVED_TEXT'] = '결제가 완료되었습니다.';
$lang['NRS_INSTALL_DEFAULT_EMAIL_BODY_BOOKING_CANCELLED_TEXT'] = '예약 [BOOKING_CODE] 이 캔슬되었습니다.';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_BOOKING_RECEIVED_TEXT'] = '새 예약 [BOOKING_CODE] 이 [CUSTOMER_NAME]으로부터.';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_BOOKING_DETAILS_TEXT'] = '예약 디테일:';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_BOOKING_PAID_TEXT'] = '예약 [BOOKING_CODE] 이[CUSTOMER_NAME] 으로부터 캔슬되었습니다.';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_BOOKING_CANCELLED_TEXT'] = '예약 [BOOKING_CODE]  [CUSTOMER_NAME]이 캔슬되었습니다.';
$lang['NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_CANCELLED_BOOKING_DETAILS_TEXT'] = '캔슬된 예약 정보:';

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