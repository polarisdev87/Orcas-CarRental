<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<h1>
    <span>Email Settings</span>
</h1>
<form name="email_settings_form" action="<?php print($emailSettingsTabFormAction); ?>" method="post" class="email-settings-form">
    <table cellpadding="5" cellspacing="2" border="0">
        <tr>
            <td style="padding-right: 10px; padding-top: 10px; vertical-align: top;">
                <strong>Email Type:</strong>
            </td>
            <td style="padding-bottom: 10px;">
                <select name="email_id" class="email-dropdown" style="width: 475px;"><?php print($emailList); ?></select>
                <input class="back-to" type="button" name="email_preview" value="Preview"
                       onclick="" disabled="disabled"
                    />
            </td>
        </tr>
        <tr>
            <td style="padding-right: 10px; padding-top: 10px; vertical-align: top;"><strong>Email Subject:</strong></td>
            <td style="padding-bottom: 10px;">
                <input type="text" name="email_subject" class="email-subject required" style="width:475px" /><br />
                <span style="font-family: 'Courier New', Courier, mono; font-size: 10px;">
                    <span style="color:#000000;font-weight: bold">
                        <strong>Supported Codes:</strong>
                    </span>
                    <span style="color:#196601;">
                        [BOOKING_CODE], [CUSTOMER_NAME], [COMPANY_NAME]<?php if($showLocationBBCodes === TRUE): ?>, [LOCATION_NAME]<?php endif; ?>
                    </span>
                </span>
            </td>
        </tr>
        <tr>
            <td style="padding-top: 10px; padding-right: 10px; vertical-align: top;">
                <strong>Email Body:</strong>
            </td>
            <td>
                <textarea name="email_body" style="width:950px; height:300px;border: 1px solid #DDD;" class="email-body required"></textarea>
                <br />
                <span style="font-family: 'Courier New', Courier, mono; font-size: 10px;">
                    <span style="color:#000000;font-weight: bold">
                        <strong>Supported Codes:</strong>
                    </span><br />
                    <span style="color:#196601;">
                        [S]<strong><span style="color: #000000">STRONG</span></strong>[/S],
                        [EM]<em><span style="color: #000000">EMPHASIZED</span></em>[/EM],
                        [CENTER]<span style="color: #000000">CENTERED</span>[/CENTER], [HR],
                        [IMG]<span style="color: #000000">URL</span>[/IMG],
                        [BOOKING_CODE]<br />
                        [CUSTOMER_ID], [CUSTOMER_NAME], [INVOICE],
                        [SITE_URL], [COMPANY_NAME], [COMPANY_PHONE], [COMPANY_EMAIL]
                        <?php if($showLocationBBCodes === TRUE): ?>
                            <br />
                            [LOCATION_NAME], [LOCATION_PHONE], [LOCATION_EMAIL]
                        <?php endif; ?>
                    </span>
                </span>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <br />
                <input name="update_email" type="submit" value="Update" style="cursor:pointer;" disabled="disabled" />
            </td>
        </tr>
    </table>
</form>
<p>Please keep in mind that:</p>
<ol>
    <li>Email content, invoice and customer details width is 840px.</li>
    <li>URLs and email addresses in email content will be translated to links automatically.</li>
    <li>You have to be very careful with link emails - don&#39;t use the or use as few as possible to avoid emails going to SPAM folder.</li>
    <li>Email recipient has to allow images in emails to make pixel tracking work.</li>
    <li>You want to be consistent with your names for your campaign source, medium and content.<br />
        Any links in your email that go to your site should be tagged with the same source, medium and campaign as above.<br />
        Following the example bellow, your links should be formatted in this structure:<br />
        <span style="color:black;font-family: 'Courier New', Courier, mono; font-size: 10px;">
        <?php print(site_url()); ?>/landing-page/?utm_source=invoice&utm_medium=email&utm_campaign=061215
        </span>
    </li>
</ol>
<p>How to use in emails Google Analytics tracking pixel (image) via MailChimp STMP server:</p>
<ol>
    <li>Your demo shortcode will look like this:<br />
        <span style="color:black;font-family: 'Courier New', Courier, mono; font-size: 10px;">
            <span style="color:#196601;">[IMG]</span>http://www.google-analytics.com/collect?v=1&tid=UA-XXXXXXX-YY&cid=*|UNIQID|*&t=event&ec=email&ea=open&el=*|UNIQID|*&cs=invoice&cm=email&cn=061215&cm1=1<span style="color:#196601;">[/IMG]</span>
        </span>
    </li>
    <li>The breakdown of parameters above:<br />
        <ul style="font-family: 'Courier New', Courier, mono; font-size: 10px;">
            <li>
                <span style="font-weight: bold;color:black;">http://www.google-analytics.com/collect?</span>
                - This is the API endpoint for the Measurement Protocol.<br />
                In layman’s terms, this is where we’re sending the data. The data that’s being sent comes next, in the form of query parameters.
            </li>
            <li>
                <span style="font-weight: bold;color:black;">v = 1</span>
                - Protocol Version (required)
            </li>
            <li>
                <span style="font-weight: bold;color:black;">tid = UA-XXXXXX-YY</span>
                - Tracking ID / Web Property ID (required)
            </li>
            <li>
                <span style="font-weight: bold;color:black;">cid = *|UNIQID|*</span>
                - Client ID (required). This anonymously identifies a particular user, device, or browser.<br />
                The value – *|UNIQID|* – is a dynamic parameter (aka merge tag) in MailChimp that will fill in the user’s MailChimp ID.<br />
                If you are not using MailChimp, or if you want to track users by customer id, you can use [CUSTOMER_ID] shortcode instead.
            </li>
            <li>
                <span style="font-weight: bold;color:black;">t = event</span>
                - Hit type (required). We’re tracking this with event tracking, hence the event hit type.
            </li>
            <li>
                <span style="font-weight: bold;color:black;">ec = email</span>
                - Event Category
            </li>
            <li>
                <span style="font-weight: bold;color:black;">ea = open</span>
                - Event Action
            </li>
            <li>
                <span style="font-weight: bold;color:black;">el = *|UNIQID|*</span>
                - Event Label
            </li>
            <li>
                <span style="font-weight: bold;color:black;">cs = invoice</span>
                - Campaign Source
            </li>
            <li>
                <span style="font-weight: bold;color:black;">cm = email</span>
                - Campaign Medium
            </li>
            <li>
                <span style="font-weight: bold;color:black;">cn = 061215</span>
                - Campaign Name
            </li>
            <li>
                <span style="font-weight: bold;color:black;">cm1 = 1</span>
                - Custom Metric 1
            </li>
        </ul>
    </li>
</ol>
<script type="text/javascript">
jQuery(document).ready(function()
{
    var emailDropDown = jQuery('.email-settings-form .email-dropdown');

    emailDropDown.on('change', function()
    {
        getCarRentalEmailContent('<?php print($ajaxSecurityNonce); ?>', this.value);
    });
    getCarRentalEmailContent('<?php print($ajaxSecurityNonce); ?>', emailDropDown.val());

    jQuery('.email-settings-form').validate();
});
</script>