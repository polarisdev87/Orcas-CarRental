<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<h1>
    <span>Car Rental System Network Update</span>
</h1>
<form name="update_form" action="<?php print($networkUpdateTabFormAction); ?>" method="post" id="update_form">
    <div style="padding-bottom: 20px;" class="big-text">
        <strong>Network enabled:</strong> Yes<br />
        <strong>Your current plugin version:</strong> <?php print($databaseVersion); ?><br />
        <?php if($pluginUpToDate): ?>
            <br />
            You have the <span style="color: green; font-weight: bold">newest plugin version</span>. No network-update available.
        <?php else: ?>
            <strong>Newest version available:</strong> <?php print($version); ?><br />
            <br />
            <span style="color: #2da5da; font-weight: bold">Minor plugin update available!</span>
        <?php endif; ?>
    </div>
    <?php if($canUpdate): ?>
        <div style="font-weight: bold" class="big-text">
            Car rental system is ready for network-update
        </div>
        <div style="text-align: center" class="big-text">
            <br />
            <input type="submit" value="Network-update plugin to <?php print($version); ?> version" name="network_update" style="cursor: pointer;"/>
        </div>
    <?php else: ?>
        <?php print($objLang->getText('NRS_DATABASE_UPDATE_NETWORK_UPDATE_NOT_ALLOWED_ERROR_TEXT')); ?>
    <?php endif; ?>
</form>