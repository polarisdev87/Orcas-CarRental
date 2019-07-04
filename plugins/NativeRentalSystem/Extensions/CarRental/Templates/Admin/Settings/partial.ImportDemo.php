<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<h1>
    <span>Import Demo</span>
</h1>
<form name="import_demo_form" action="<?php print($importDemoTabFormAction); ?>" method="post" id="import_demo_form">
    <div class="big-labels">
        <select name="demo_id" class="required">
            <?php print($demosDropDownOptions); ?>
        </select> &nbsp;
        <input type="submit" value="Import" name="import_demo" onclick="return confirm('Are you sure? This will flush all the content of plugin you have!');"
               style="cursor:pointer;"
            />
    </div>
    <p>Please keep in mind that:</p>
    <ol>
        <li>By importing demo you will flush all your existing car rental data.</li>
        <li>Other website content, including pages content, won&#39;t be affected, so you still have to add the shortcodes by yourself.</li>
        <li>Make sure you have a latest DB backup with you before performing this action.</li>
    </ol>
</form>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery("#import_demo_form").validate();
});
</script>