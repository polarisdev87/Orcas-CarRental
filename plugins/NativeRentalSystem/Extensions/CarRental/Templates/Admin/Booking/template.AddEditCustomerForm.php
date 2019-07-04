<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
// Scripts
wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui-core');
wp_enqueue_script('jquery-ui-tabs');
wp_enqueue_script('jquery-ui-datepicker', array('jquery','jquery-ui-core'));
wp_enqueue_script('jquery-validate');
wp_enqueue_script('car-rental-admin');

// Styles
wp_enqueue_style('datepicker');
wp_enqueue_style('jquery-validate');
wp_enqueue_style('car-rental-admin');
?>
<p>&nbsp;</p>
<div id="container-inside" style="width:1000px;">
  <span style="font-size:16px; font-weight:bold">Customer Add/Edit</span>
  <input type="button" value="Back To Customer List" onClick="window.location.href='<?php print($backToListURL); ?>'" style="background: #EFEFEF; float:right; cursor:pointer;"/>
  <hr style="margin-top:10px;"/>
  <form action="<?php print($formAction); ?>" method="post" class="customer-form">
    <table cellpadding="5" cellspacing="2" border="0">
      <input type="hidden" name="customer_id" value="<?php print($customerId); ?>">
      <?php if($titleVisible): ?>
          <tr>
            <td><strong><?php print($objLang->getText('NRS_TITLE_TEXT')); ?>:<span class="dynamic-text-item<?php print($titleRequired); ?>">*</span></strong></td>
            <td>
                <select name="title" class="title<?php print($titleRequired); ?>">
                    <?php print($titleDropDownOptions); ?>
                </select>
            </td>
          </tr>
      <?php endif; ?>
      <?php if($firstNameVisible): ?>
          <tr>
            <td align="left"><strong><?php print($objLang->getText('NRS_FIRST_NAME_TEXT')); ?>:<span class="dynamic-text-item<?php print($firstNameRequired); ?>">*</span></strong></td>
            <td><input type="text" name="first_name" value="<?php print($firstName); ?>" class="first-name<?php print($firstNameRequired); ?>" style="width:250px;"/></td>
          </tr>
      <?php endif; ?>
      <?php if($lastNameVisible): ?>
          <tr>
            <td align="left"><strong><?php print($objLang->getText('NRS_LAST_NAME_TEXT')); ?>:<span class="dynamic-text-item<?php print($lastNameRequired); ?>">*</span></strong></td>
            <td><input type="text" name="last_name" value="<?php print($lastName); ?>" class="last-name<?php print($lastNameRequired); ?>" style="width:250px;"/></td>
          </tr>
      <?php endif; ?>
      <?php if($birthdateVisible): ?>
          <tr>
            <td align="left"><strong><?php print($objLang->getText('NRS_DATE_OF_BIRTH_TEXT')); ?>:<span class="dynamic-text-item<?php print($birthdateRequired); ?>">*</span></strong></td>
            <td>
                <select name="birth_year" class="birth-year<?php print($birthdateRequired); ?>"><?php print($birthYearDropDownOptions); ?></select>
                <select name="birth_month" class="birth-month<?php print($birthdateRequired); ?>"><?php print($birthMonthDropDownOptions); ?></select>
                <select name="birth_day" class="birth-day<?php print($birthdateRequired); ?>"><?php print($birthDayDropDownOptions); ?></select>
            </td>
          </tr>
      <?php endif; ?>
      <?php if($streetAddressVisible): ?>
          <tr>
            <td align="left"><strong><?php print($objLang->getText('NRS_STREET_ADDRESS_TEXT')); ?>:<span class="dynamic-text-item<?php print($streetAddressRequired); ?>">*</span></strong></td>
            <td><input type="text" name="street_address" value="<?php print($streetAddress); ?>" class="street-address<?php print($streetAddressRequired); ?>" style="width:250px;" /></td>
          </tr>
      <?php endif; ?>
      <?php if($cityVisible): ?>
          <tr>
            <td align="left"><strong><?php print($objLang->getText('NRS_CITY_TEXT')); ?>:<span class="dynamic-text-item<?php print($cityRequired); ?>">*</span></strong></td>
            <td><input type="text" name="city" value="<?php print($city); ?>" class="city<?php print($cityRequired); ?>" style="width:250px;"/></td>
          </tr>
      <?php endif; ?>
      <?php if($stateVisible): ?>
          <tr>
            <td align="left"><strong><?php print($objLang->getText('NRS_STATE_TEXT')); ?>:<span class="dynamic-text-item<?php print($stateRequired); ?>">*</span></strong></td>
            <td><input type="text" name="state" value="<?php print($state); ?>" class="state<?php print($stateRequired); ?>" style="width:250px;"/></td>
          </tr>
      <?php endif; ?>
      <?php if($zipCodeVisible): ?>
          <tr>
            <td align="left"><strong><?php print($objLang->getText('NRS_ZIP_CODE_TEXT')); ?>:<span class="dynamic-text-item<?php print($zipCodeRequired); ?>">*</span></strong></td>
            <td><input type="text" name="zip_code" value="<?php print($zipCode); ?>" class="zip-code<?php print($zipCodeRequired); ?>" style="width:250px;"/></td>
          </tr>
      <?php endif; ?>
      <?php if($countryVisible): ?>
          <tr>
            <td align="left"><strong><?php print($objLang->getText('NRS_COUNTRY_TEXT')); ?>:<span class="dynamic-text-item<?php print($countryRequired); ?>">*</span></strong></td>
            <td><input type="text" name="country" value="<?php print($country); ?>" class="country<?php print($countryRequired); ?>" style="width:250px;" /></td>
          </tr>
      <?php endif; ?>
      <?php if($phoneVisible): ?>
          <tr>
            <td align="left"><strong><?php print($objLang->getText('NRS_PHONE_TEXT')); ?>:<span class="dynamic-text-item<?php print($phoneRequired); ?>">*</span></strong></td>
            <td>
              <input type="text" name="phone" value="<?php print($phone); ?>" class="phone<?php print($phoneRequired); ?>" style="width:250px;"/>
            </td>
          </tr>
      <?php endif; ?>
      <?php if($emailVisible): ?>
          <tr>
            <td align="left"><strong><?php print($objLang->getText('NRS_EMAIL_TEXT')); ?>:<span class="dynamic-text-item<?php print($emailRequired); ?>">*</span></strong></td>
            <td>
              <input type="text" name="email" value="<?php print($email); ?>" class="email<?php print($emailRequired); ?>" style="width:250px;"/>
            </td>
          </tr>
      <?php endif; ?>
      <?php if($commentsVisible): ?>
          <tr>
            <td align="left"><strong><?php print($objLang->getText('NRS_ADDITIONAL_COMMENTS_TEXT')); ?>:<span class="dynamic-text-item<?php print($commentsRequired); ?>">*</span></strong></td>
            <td>
              <textarea name="comments" class="comments<?php print($commentsRequired); ?>" rows="3" cols="50"><?php print($comments); ?></textarea>
            </td>
          </tr>
      <?php endif; ?>
      <?php if($existingCustomer): ?>
        <tr>
          <td align="left"><strong><?php print($objLang->getText('NRS_CUSTOMER_ID_TEXT')); ?>:</strong></td>
          <td>
            <strong><?php print($customerId); ?></strong>
          </td>
        </tr>
        <tr>
          <td align="left"><strong><?php print($objLang->getText('NRS_IP_ADDRESS_TEXT')); ?>:</strong></td>
          <td>
            <strong><?php print($ip); ?></strong>
          </td>
        </tr>
        <tr>
          <td align="left"><strong><?php print($objLang->getText('NRS_EXISTING_CUSTOMER_TEXT')); ?>:</strong></td>
          <td>
            <strong><?php print($existingCustomer ? $objLang->getText('NRS_ADMIN_YES_TEXT'): $objLang->getText('NRS_ADMIN_NO_TEXT')); ?></strong>
          </td>
        </tr>
      <?php endif; ?>
      <tr>
        <td width="100px"></td>
        <td align="left">
          <input name="save_customer" type="submit" value="Save Customer" style="cursor:pointer;"/>
        </td>
      </tr> 
    </table>
  </form>
</div>
<script type="text/javascript">
jQuery().ready(function() {
  jQuery.extend(jQuery.validator.messages, {
    required: "<?php print($objLang->getText('NRS_REQUIRED_TEXT')); ?>"
  });
    jQuery(".customer-form").validate();
});
</script>