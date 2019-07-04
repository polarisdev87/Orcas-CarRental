<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<h1>
    <span>Car Rental System Network Upgrade</span>
</h1>
<form name="upgrade_form" action="<?php print($networkUpgradeTabFormAction); ?>" method="post" id="upgrade_form">
    <div style="padding-bottom: 20px;" class="big-text">
        <strong>Network enabled:</strong> Yes<br />
        <strong>Your current plugin version:</strong> <?php print($databaseVersion); ?><br />
        <?php if($pluginUpToDate): ?>
            <br />
            You have the <span style="color: green; font-weight: bold">newest plugin version</span>. No network-upgrade available.
        <?php else: ?>
            <strong>Newest version available:</strong> <?php print($version); ?><br />
            <br />
            <span style="color: red; font-weight: bold">Major plugin upgrade available!</span>
        <?php endif; ?>
    </div>
        <?php if($canUpdate): ?>
            <div style="font-weight: bold" class="big-text">
                Car rental system is ready for network-upgrade
            </div>
            <div style="text-align: center" class="big-text">
                <br />
                <input type="submit" value="Network-upgrade plugin to <?php print($version); ?> version" name="network_update" style="cursor: pointer;"/>
            </div>
        <?php else: ?>
            <?php print($objLang->getText('NRS_DATABASE_UPDATE_NETWORK_UPGRADE_NOT_ALLOWED_ERROR_TEXT')); ?>
        <?php endif; ?>
</form>