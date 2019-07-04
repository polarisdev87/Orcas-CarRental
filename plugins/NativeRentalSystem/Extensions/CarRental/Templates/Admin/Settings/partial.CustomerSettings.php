<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<h1>
    <span>Customer Settings</span>
</h1>
<form name="customer_settings_form" action="<?php print($customerSettingsTabFormAction); ?>" method="post" id="customer_settings_form">
    <table class="big-text" cellpadding="5" cellspacing="2" border="0" width="100%" style="line-height: 2">
        <thead>
        <tr>
            <th align="left" style="width: 350px">Customer Field</th>
            <th align="center" style="width: 100px">Visible</th>
            <th align="center" style="width: 100px">Required</th>
            <th>Actions</th>
        </tr>
        <tr>
            <th colspan="4"><hr /></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <?php print($objLang->getText('NRS_TITLE_TEXT')); ?>
            </td>
            <td align="center">
                <input type="checkbox" name="conf_customer_title_visible" value="yes"<?php print($titleVisibleChecked); ?> />
            </td>
            <td align="center">
                <input type="checkbox" name="conf_customer_title_required" value="yes"<?php print($titleRequiredChecked); ?> />
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <?php print($objLang->getText('NRS_FIRST_NAME_TEXT')); ?>
            </td>
            <td align="center">
                <input type="checkbox" name="conf_customer_first_name_visible" value="yes"<?php print($firstNameVisibleChecked); ?> />
            </td>
            <td align="center">
                <input type="checkbox" name="conf_customer_first_name_required" value="yes"<?php print($firstNameRequiredChecked); ?> />
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <?php print($objLang->getText('NRS_LAST_NAME_TEXT')); ?>
            </td>
            <td align="center">
                <input type="checkbox" name="conf_customer_last_name_visible" value="yes"<?php print($lastNameVisibleChecked); ?> />
            </td>
            <td align="center">
                <input type="checkbox" name="conf_customer_last_name_required" value="yes"<?php print($lastNameRequiredChecked); ?> />
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <?php print($objLang->getText('NRS_DATE_OF_BIRTH_TEXT')); ?>
            </td>
            <td align="center">
                <input type="checkbox" name="conf_customer_birthdate_visible" value="yes"<?php print($birthdateVisibleChecked); ?> />
            </td>
            <td align="center">
                <input type="checkbox" name="conf_customer_birthdate_required" value="yes"<?php print($birthdateRequiredChecked); ?> />
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <?php print($objLang->getText('NRS_STREET_ADDRESS_TEXT')); ?>
            </td>
            <td align="center">
                <input type="checkbox" name="conf_customer_street_address_visible" value="yes"<?php print($streetAddressVisibleChecked); ?> />
            </td>
            <td align="center">
                <input type="checkbox" name="conf_customer_street_address_required" value="yes"<?php print($streetAddressRequiredChecked); ?> />
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <?php print($objLang->getText('NRS_CITY_TEXT')); ?>
            </td>
            <td align="center">
                <input type="checkbox" name="conf_customer_city_visible" value="yes"<?php print($cityVisibleChecked); ?> />
            </td>
            <td align="center">
                <input type="checkbox" name="conf_customer_city_required" value="yes"<?php print($cityRequiredChecked); ?> />
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <?php print($objLang->getText('NRS_STATE_TEXT')); ?>
            </td>
            <td align="center">
                <input type="checkbox" name="conf_customer_state_visible" value="yes"<?php print($stateVisibleChecked); ?> />
            </td>
            <td align="center">
                <input type="checkbox" name="conf_customer_state_required" value="yes"<?php print($stateRequiredChecked); ?> />
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <?php print($objLang->getText('NRS_ZIP_CODE_TEXT')); ?>
            </td>
            <td align="center">
                <input type="checkbox" name="conf_customer_zip_code_visible" value="yes"<?php print($zipCodeVisibleChecked); ?> />
            </td>
            <td align="center">
                <input type="checkbox" name="conf_customer_zip_code_required" value="yes"<?php print($zipCodeRequiredChecked); ?> />
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <?php print($objLang->getText('NRS_COUNTRY_TEXT')); ?>
            </td>
            <td align="center">
                <input type="checkbox" name="conf_customer_country_visible" value="yes"<?php print($countryVisibleChecked); ?> />
            </td>
            <td align="center">
                <input type="checkbox" name="conf_customer_country_required" value="yes"<?php print($countryRequiredChecked); ?> />
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <?php print($objLang->getText('NRS_PHONE_TEXT')); ?>
            </td>
            <td align="center">
                <input type="checkbox" name="conf_customer_phone_visible" value="yes"<?php print($phoneVisibleChecked); ?> />
            </td>
            <td align="center">
                <input type="checkbox" name="conf_customer_phone_required" value="yes"<?php print($phoneRequiredChecked); ?> />
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <?php print($objLang->getText('NRS_EMAIL_TEXT')); ?>
            </td>
            <td align="center">
                <input type="checkbox" name="conf_customer_email_visible" value="yes"<?php print($emailVisibleChecked); ?> />
            </td>
            <td align="center">
                <input type="checkbox" name="conf_customer_email_required" value="yes"<?php print($emailRequiredChecked); ?> />
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <?php print($objLang->getText('NRS_ADDITIONAL_COMMENTS_TEXT')); ?>
            </td>
            <td align="center">
                <input type="checkbox" name="conf_customer_comments_visible" value="yes"<?php print($commentsVisibleChecked); ?> />
            </td>
            <td align="center">
                <input type="checkbox" name="conf_customer_comments_required" value="yes"<?php print($commentsRequiredChecked); ?> />
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4" align="center">
                <br />
                <input type="submit" value="Update customer settings" name="update_customer_settings" style="cursor:pointer;"/>
            </td>
        </tr>
    </table>
</form>
<p>Please keep in mind that:</p>
<ol>
    <li>If you will hide or make optional the &quot;Customer email&quot; field, you will also disable Ajax user data pre-fill box, where there is a search by email. All data will have to be then entered manually on every new booking.</li>
    <li>For reservation edit, it will still pull customer&#39;s data, because system will use customer id, which is attached to reservation id.</li>
    <li>If you mark customer birth date as a mandatory field, it will be used as a 2nd parameter for customer details lookup to make security stronger.</li>
</ol>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery("#customer_settings_form").validate();
});
</script>