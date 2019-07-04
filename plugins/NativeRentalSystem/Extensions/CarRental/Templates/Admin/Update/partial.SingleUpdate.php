<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<h1>
    <span>Car Rental System Update</span>
</h1>
<form name="update_form" action="<?php print($updateTabFormAction); ?>" method="post" id="update_form">
    <div style="padding-bottom: 20px;" class="big-text">
        <strong>Your current plugin version:</strong> <?php print($databaseVersion); ?><br />
        <?php if($pluginUpToDate): ?>
            <br />
            You have the <span style="color: green; font-weight: bold">newest plugin version</span>. No update available.
        <?php else: ?>
            <br />
            <strong>Newest version available:</strong> <?php print($version); ?><br />
            <br />
            <span style="color: #2da5da; font-weight: bold">Minor plugin update available!</span>
        <?php endif; ?>
    </div>
    <?php if($isNetworkEnabled === FALSE && $canUpdate): ?>
        <div style="font-weight: bold" class="big-text">
            Car Rental System is ready for update
        </div>
        <div style="text-align: center" class="big-text">
            <br />
            <input type="submit" value="Update plugin to <?php print($version); ?> version" name="update" style="cursor: pointer;"/>
        </div>
    <?php elseif($isNetworkEnabled === TRUE && $canUpdate): ?>
        <div style="font-weight: bold" class="big-text">
            To update the network-enabled plugin, go to Car Rental System Network Update menu item, located in WordPress Network Manager
        </div>
    <?php else: ?>
        <?php print($objLang->getText('NRS_DATABASE_UPDATE_NOT_ALLOWED_ERROR_TEXT')); ?>
    <?php endif; ?>
</form>