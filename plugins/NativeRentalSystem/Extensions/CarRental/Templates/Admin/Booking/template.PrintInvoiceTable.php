<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php print($objLang->getText('NRS_ADMIN_PRINT_INVOICE_TEXT')); ?></title>
<script type="text/javascript">
window.print();
</script>
</head>
<body>
    <div align="center">
        <table cellpadding="3" border="0" width="750">
            <tr>
                <td width="400" align="left" valign="top">
                    <span style="font-family:Arial, Helvetica, sans-serif; font-size:24px; font-weight:bold;"><?php print($objSettings->getSetting('conf_company_name')); ?></span><br />
                    <br />
                    <span style="font-family:Arial, Helvetica, sans-serif; font-size:12px;"><?php print($objSettings->getSetting('conf_company_street_address')); ?></span><br />
                    <span style="font-family:Arial, Helvetica, sans-serif; font-size:12px;"><?php print($objSettings->getSetting('conf_company_city')); ?></span><br />
                    <span style="font-family:Arial, Helvetica, sans-serif; font-size:12px;"><?php print($objSettings->getSetting('conf_company_state').' '.$objSettings->getSetting('conf_company_zip_code')); ?></span><br />
                    <span style="font-family:Arial, Helvetica, sans-serif; font-size:12px;"><?php print($objSettings->getSetting('conf_company_country')); ?></span><br />
                </td>
                <td width="200" align="right" valign="top">
                    <span style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                        <strong><?php print($objLang->getText('NRS_PHONE_TEXT')); ?>:</strong> <?php print($objSettings->getSetting('conf_company_phone')); ?>
                    </span><br />
                    <span style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
                        <strong><?php print($objLang->getText('NRS_EMAIL_TEXT')); ?>:</strong> <?php print($objSettings->getSetting('conf_company_email')); ?>
                    </span><br />
                </td>
            </tr>
        </table>
        <br />
    <?php print($invoiceHTML); ?>
    </div>
</body>
</html>