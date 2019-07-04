<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<h1>
    <span><?php print($emailSubject); ?></span>
    <input class="back-to" type="button" value="Back to Email Settings" onClick="window.location.href='<?php print($backToListURL); ?>'" />
</h1>
<div class="clear">
<?php print($emailBody); ?>
</div>