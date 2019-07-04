<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
// Scripts
wp_enqueue_script('jquery');
wp_enqueue_script('jquery.mousewheel'); // Optional for fancyBox
if($objSettings->getSetting('conf_load_fancybox_from_plugin') == 1):
    wp_enqueue_script('fancybox');
endif;

// Styles
if($objSettings->getSetting('conf_load_fancybox_from_plugin') == 1):
    wp_enqueue_style('fancybox');
endif;
if($objSettings->getSetting('conf_load_font_awesome_from_plugin') == 1):
    wp_enqueue_style('font-awesome');
endif;
wp_enqueue_style('car-rental-frontend');
?>
<div class="car-rental-wrapper car-rental-items-list">
<?php
if($gotResults):
    foreach($items as $item):
        include('partial.ItemsListOneType.php');
    endforeach;
else:
    print('<div class="no-items-available">'.$objLang->getText('NRS_NO_ITEMS_AVAILABLE_TEXT').'</div>');
endif;
?>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('.fancybox').fancybox();
});
</script>