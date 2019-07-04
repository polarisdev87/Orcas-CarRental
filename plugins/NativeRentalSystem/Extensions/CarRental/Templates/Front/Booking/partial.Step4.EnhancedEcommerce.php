<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<script type="text/javascript">
// Add Enhanced commerce library code
ga('require', 'ec');

<?php foreach ($priceSummary['items'] AS $item): ?>
// Note: we should provide item name only for tracking and it should not be translated
// The following code measures a click on a product link displayed in a list of search results:
ga('ec:addProduct', {               // Provide product details in a productFieldObject.
    'id': '<?php print($item['print_item_sku']); ?>',                           // Product ID (string).
    'name': '<?php print($item['print_manufacturer_title'].' '.$item['print_item_name']); ?>', // Product name (string).
    'category': '<?php print($item['print_body_type_title']); ?>',              // Product category (string).
    'brand': '<?php print($item['print_manufacturer_title']); ?>',              // Product brand (string).
    'variant': '<?php print($item['print_selected_option_name']); ?>'           // Product variant (string).
    'price': '<?php print($item['unit']['discounted_total_with_tax']); ?>',     // Product price (currency).
    'coupon': '<?php print($couponCode); ?>',                                   // Product coupon (string).
    'quantity': <?php print($item['selected_quantity']); ?>                     // Product quantity (number).
});
<?php endforeach; ?>

<?php foreach ($priceSummary['extras'] AS $extra): ?>
// Note: we should provide extra name only for tracking
ga('ec:addProduct', {               // Provide product details in an productFieldObject.
    'id': '<?php print($extra['print_extra_sku']); ?>',                     // Product ID (string).
    'name': '<?php print($extra['print_extra_name']); ?>',                  // Product name (string).
    'category': 'Extras',                                                   // Product category (string).
    'brand': 'Extra',                                                       // Product brand (string).
    'variant': '<?php print($extra['print_selected_option_name']); ?>',     // Product variant (string).
    'price': '<?php print($extra['discounted_total_with_tax']); ?>',        // Product price (currency).
    'coupon': '<?php print($couponCode); ?>',                               // Product coupon (string).
    'quantity': <?php print($extra['selected_quantity']); ?>                // Product quantity (number).
});
<?php endforeach; ?>

ga('ec:setAction', 'click', {       // click action.
    'list': 'Search Results'   // Product list (string).
});

// Ecommerce data can only be sent with an existing hit, for example a pageview or event. If you use ecommerce commands
// but do not send any hits, or the hit is sent before the ecommerce command then the ecommerce data will not be sent.
// Note: we can't use 'pageview' hit here, because it is already sent in site headers and we don't want to count it twice
// But we still want to process these impressions, so we call non-interactive pageview
// Note 2: Do not translate events to track well inter-language events
ga('send', 'event', '<?php print($extensionName.' Enhanced Ecommerce'); ?>', 'Reservation Summary', {'nonInteraction': true});
</script>