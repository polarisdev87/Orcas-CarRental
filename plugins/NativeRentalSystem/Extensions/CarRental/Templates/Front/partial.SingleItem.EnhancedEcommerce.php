<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<script type="text/javascript">
// Add Enhanced commerce library code
ga('require', 'ec');

// Note: Do not translate values and do not add partner info to track well inter-language purchases
// The following code measures the impression (view) of a product in a list of search results:
ga('ec:addImpression', {            // Provide product details in an impressionFieldObject.
    'id': '<?php print($item['print_item_sku']); ?>',                                     // Product ID (string).
    'name': '<?php print($item['print_manufacturer_title'].' '.$item['print_model_name']); ?>', // Product name (string).
    'category': '<?php print($item['print_body_type_title']); ?>',                        // Product category (string).
    'brand': '<?php print($item['print_manufacturer_title']); ?>',                        // Product brand (string).
    'list': 'View Details'                                                          // Product list (string).
});

// The product being viewed.
ga('ec:addProduct', {                 // Provide product details in an productFieldObject.
    'id': '<?php print($item['print_item_sku']); ?>',                                     // Product ID (string).
    'name': '<?php print($item['print_manufacturer_title'].' '.$item['print_model_name']); ?>', // Product name (string).
    'category': '<?php print($item['print_body_type_title']); ?>',                        // Product category (string).
    'brand': '<?php print($item['print_manufacturer_title']); ?>'                        // Product brand (string).
});

ga('ec:setAction', 'detail');       // Detail action.

// Ecommerce data can only be sent with an existing hit, for example a pageview or event. If you use ecommerce commands
// but do not send any hits, or the hit is sent before the ecommerce command then the ecommerce data will not be sent.
// Note: we can't use 'pageview' hit here, because it is already sent in site headers and we don't want to count it twice
// But we still want to process these impressions, so we call non-interactive pageview
// Note 2: Do not translate events to track well inter-language events
ga('send', 'event', '<?php print($extensionName.' Enhanced Ecommerce'); ?>', 'Add Product Detail with Impression', {'nonInteraction': true});
</script>