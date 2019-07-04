<?php
/**
 * PayPal process class
 * Final class cannot be inherited anymore. We use them when creating new instances
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Logging\Log;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\PaymentMethod\PaymentMethod;
use NativeRentalSystem\Models\PaymentResource\iPaymentResource;
use NativeRentalSystem\Models\Language\Language;

class NRSStripe implements iPaymentResource
{
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $debugMode 	            = 1;
    protected $settings                 = array();

    // Array holds the fields to submit to PayPal
    protected $fields                   = array();
    protected $use_ssl                  = TRUE;

    protected $businessEmail            = "";
    protected $useSandbox               = FALSE;
    protected $currencySymbol	        = '$';
    protected $currencyCode		        = 'USD';
    protected $companyName              = "";
    protected $confirmationPageId       = 0;
    protected $cancelledPaymentPageId   = 0;
    protected $publicKey                = '';
    protected $privateKey               = '';

    protected $bookingCode              = "";
    protected $totalPayNow              = 0.00;

    /**
     * NRSStripe constructor.
     * @param ExtensionConfiguration &$paramConf
     * @param Language &$paramLang
     * @param array $paramSettings
     * @param int $paramPaymentMethodId
     */
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings, $paramPaymentMethodId)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
        $this->settings = $paramSettings;

        $objPaymentMethod = new PaymentMethod($this->conf, $this->lang, $paramSettings, $paramPaymentMethodId);
        $paymentMethodDetails = $objPaymentMethod->getDetails();
        $this->businessEmail = isset($paymentMethodDetails['payment_method_email']) ? sanitize_email($paymentMethodDetails['payment_method_email']) : "";
        $this->useSandbox = !empty($paymentMethodDetails['sandbox_mode']) ? TRUE : FALSE;
        $this->publicKey = !empty($paymentMethodDetails['public_key']) ? sanitize_text_field($paymentMethodDetails['public_key']) : '';
        $this->privateKey = !empty($paymentMethodDetails['private_key']) ? sanitize_text_field($paymentMethodDetails['private_key']) : '';
        // Process to PayPal order page
        $this->currencySymbol = StaticValidator::getValidSetting($paramSettings, 'conf_currency_symbol', "textval", "$");
        $this->currencyCode = StaticValidator::getValidSetting($paramSettings, 'conf_conf_currency_code', "textval", "USD");
        $this->companyName = StaticValidator::getValidSetting($paramSettings, 'conf_company_name', "textval", "");
        $this->confirmationPageId = StaticValidator::getValidSetting($paramSettings, 'conf_confirmation_page_id', 'positive_integer', 0);
        $this->cancelledPaymentPageId = StaticValidator::getValidSetting($paramSettings, 'conf_cancelled_payment_page_id', 'positive_integer', 0);
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    /******************************************************************************************/
    /* Default methods                                                                        */
    /******************************************************************************************/

    /**
     * Based on https://stripe.com/docs/checkout#integration-custom
     * @param $paramCurrentDescription
     * @param $paramTotalPayNow = '0.00'
     * @return string
     */
    public function getDescriptionHTML($paramCurrentDescription, $paramTotalPayNow = '0.00')
    {
        $validAmountInCents = $paramTotalPayNow > 0 ? round(floatval($paramTotalPayNow), 2) * 100 : 0;

			
			$ret = '	
							<style>
					* {
					  box-sizing: border-box;
					}

					

					@font-face {
					  font-family: StripeIcons;
					  src: url(data:application/octet-stream;base64,d09GRk9UVE8AAAZUAAoAAAAAB6AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABDRkYgAAADKAAAAx8AAAOKkWuAp0dTVUIAAAZIAAAACgAAAAoAAQAAT1MvMgAAAXAAAABJAAAAYGcdjVZjbWFwAAACvAAAAFYAAACUKEhKfWhlYWQAAAD8AAAAMAAAADYJAklYaGhlYQAAAVAAAAAgAAAAJAYoAa5obXR4AAABLAAAACQAAAAoEOAAWW1heHAAAAD0AAAABgAAAAYAClAAbmFtZQAAAbwAAAD%2FAAABuXejDuxwb3N0AAADFAAAABMAAAAg%2F7gAMgAAUAAACgAAeNpjYGRgYABifeaSpHh%2Bm68MzMwHgCIMl08yqyDo%2F95Mkcy8QC4zAxNIFAD8tAiweNpjfMAQyfiAgYEpgoGBcQmQlmFgYPgAZOtAcQZEDgCHaQVGeNpjYGRgYD7z34eBgSmCgeH%2Ff6ZIBqAICuACAHpYBNp42mNgZtzAOIGBlYGDqYDJgYGBwQNCMwYwGDEcA%2FKBUthBqHe4H4MDg4L6Imae%2Fz4MB5jPMGwBCjOC5Bi9mKYAKQUGBgAFHgteAAAAeNplkMFqwkAURU9itBVKF6XLLrLsxiGKMYH0B4IgoqjdRokajAmNUfolhX5Df7IvZhBt5zHMeffduQwDPPCFQbWM81mzyZ3uocEz95qtK0%2BTN140t2jzLk7DaotiEmk2eWSlucErH5otnvjW3OSTH82tSg8n8eaYRkVXOY4TzIaLURB2tDaPi0OSZ3Y9G09tx6lxm5erPDtVA%2BX7wT7axXm5Vmmy7ClXDfqe515CCJkQs%2BFIKk8t6KJwzhUwY8iCkVBI54%2FvvzKXruBAQk6GfZM0ZipKxdfqVpylfErlP11uKHypgL2k7iSz8qxFTSV5SU%2FIlT2gjyfl%2FgKN9EDsAHjaY2BgYGaA4DAGRgYQkAHyGMF8NgYrIM3JIAHEEACj8QNOBhYGOyDNAYRMQFpBcZL6ov%2F%2Foaw5%2F%2F%2F%2Ff3kvH8iD2McCxExAO1kYWIE2cjCwAwAgUQwvAAB42mNgZgCD%2F1sZjBiwAAAswgHqAHjaNVFbbxNHGN2JMmtlNnIoZFFx1F2nDoTWgJLIhRQqWlRowyXiUkqE1IZLVW0dJzHYjpAhxnbYi8HXdWxsEKCIi0DdqjxVyhOKkBBS%2FdAX%2FkJfmiCe0Gz4orbjLNFo5uj79B19Z85BXGsLhxAiB7ef%2BFmZGj8XaVb9dgdn%2B5Dd02J%2F2JqFIXtpeQ5Lc6h1YzKbXcN2F%2F2qg373wZ3ly%2Bs5gpCwfpO3d8dnXwyfOheJhC9FgsovsanJ4MCuzw84sN%2BBb1Zh34ADfU7za6fq%2Fyl8Ib7K9E4Eo9HgpHLQu6aL45CB8ug6yqAbKIeyqMAhjjD1nM49596hbqQgHf2B%2Fm5xt3S8sqXlORFe%2FHuSvuD3vesUQ4eVxjgEfm08PWK5%2FoF14lBjDAJvXI0xMRS0%2BMVjbGLIbzV%2BP2y5aOC46IfAb7TzT5cFbSJwEKCc9eXifGgqtOBahN3vWy7aOS76f1zkrVNiaNw1NIpfhyBg8X%2FN428t3v2KJl6KtVqxWpXpCD2Bq5XZW3XPrWv1dMVHEmZy9pr8dhsGdQuhKt%2FTh9Mz6nTCE34Yeyy56byfUHMzqaWrEpRpHldmrpqJrosXPyV0N%2BzAsMJYKzwMwjacTmtXGe9%2B7InkrtPz3aRoaIWPSUEtGjL1wUcYFnoJXeChG7qwpmfUHkI30XsvRdMsmKZMs9TwEsjR67ik6%2Fk14hk4jVcGe4k9yMMojGDNyKiqRy1opi5phUrG7HLDnkfdxOHktZIu072wB9jFhpHReoj3UXNF3lmReb%2FC0eaMx%2BESO1NY1w2myfuMuXW7VKvJ9CQ9im9Wy3XmllpLVX0kWUzNpmW6E%2FrY8ePkjLaV%2FPCMWVTeTJidTYtyuJpuWhSOMYsuwBhMgNK0dCtxS3O7%2Fmtvy7YL9lKn7RfvbODaEerw%2BXfuPfT92WDkiopLpaJZ9pQNUy9JAlNdyjVVH6PDTDV7saB2TadSCVWQYIQeZ2F8QgTVM30zdZtFlcOVSmU1WYFXolFFeRB9Kgt8PJmMx2vJu7IwvZoOS9XRFwsLsXCylKjMyGxXrV5kXxb%2BBxsddR0AAAEAAAAAAAAAAAAA)
						format("woff");
					}

					.container,
					.container-fluid,
					,
					.container-wide,
					.container-xl {
					  margin: 0 auto;
					  padding: 0 20px;
					  width: 100%;
					}

					.container,
					 {
					  max-width: 1040px;
					}

					.container-wide,
					.container-xl {
					  max-width: 1160px;
					}

					.common-SuperTitle {
					  font-weight: 300;
					  font-size: 45px;
					  line-height: 60px;
					  color: #32325d;
					  letter-spacing: -.01em;
					}

					@media (min-width: 670px) {
					  .common-SuperTitle {
						font-size: 50px;
						line-height: 70px;
					  }
					}

					.common-IntroText {
					  font-weight: 400;
					  font-size: 21px;
					  line-height: 31px;
					  color: #525f7f;
					}

					@media (min-width: 670px) {
					  .common-IntroText {
						font-size: 24px;
						line-height: 36px;
					  }
					}

					.common-BodyText {
					  font-weight: 400;
					  font-size: 17px;
					  line-height: 26px;
					  color: #6b7c93;
					}

					.common-Link {
					  color: #6772e5;
					  font-weight: 500;
					  transition: color 0.1s ease;
					  cursor: pointer;
					}

					.common-Link:hover {
					  color: #32325d;
					}

					.common-Link:active {
					  color: #000;
					}

					.common-Link--arrow:after {
					  font: normal 16px StripeIcons;
					  content: "\2192";
					  padding-left: 5px;
					}

					.common-Button {
					  white-space: nowrap;
					  display: inline-block;
					  height: 40px;
					  line-height: 40px;
					  padding: 0 14px;
					  box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
					  background: #fff;
					  border-radius: 4px;
					  font-size: 15px;
					  font-weight: 600;
					  text-transform: uppercase;
					  letter-spacing: 0.025em;
					  color: #6772e5;
					  text-decoration: none;
					  transition: all 0.15s ease;
					}

					.common-Button:hover {
					  color: #7795f8;
					  transform: translateY(-1px);
					  box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
					}

					.common-Button:active {
					  color: #555abf;
					  background-color: #f6f9fc;
					  transform: translateY(1px);
					  box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
					}

					.common-Button--default {
					  color: #fff;
					  background: #6772e5;
					}

					.common-Button--default:hover {
					  color: #fff;
					  background-color: #7795f8;
					}

					.common-Button--default:active {
					  color: #e6ebf1;
					  background-color: #555abf;
					}

					.common-Button--dark {
					  color: #fff;
					  background: #32325d;
					}

					.common-Button--dark:hover {
					  color: #fff;
					  background-color: #43458b;
					}

					.common-Button--dark:active {
					  color: #e6ebf1;
					  background-color: #32325d;
					}

					.common-Button--disabled {
					  color: #fff;
					  background: #aab7c4;
					  pointer-events: none;
					}

					.common-ButtonIcon {
					  display: inline;
					  margin: 0 5px 0 0;
					  position: relative;
					}

					.common-ButtonGroup {
					  display: -ms-flexbox;
					  display: flex;
					  -ms-flex-wrap: wrap;
					  flex-wrap: wrap;
					  margin: -10px;
					}

					.common-ButtonGroup .common-Button {
					  -ms-flex-negative: 0;
					  flex-shrink: 0;
					  margin: 10px;
					}

					/** Page-specific styles */
					@keyframes spin {
					  0% {
						transform: rotate(0deg);
					  }

					  to {
						transform: rotate(1turn);
					  }
					}

					@keyframes void-animation-out {
					  0%,
					  to {
						opacity: 1;
					  }
					}

					

					.stripes {
					  position: absolute;
					  width: 100%;
					  transform: skewY(-12deg);
					  height: 950px;
					  top: -350px;
					  background: linear-gradient(180deg, #e6ebf1 350px, rgba(230, 235, 241, 0));
					}

					.stripes .stripe {
					  position: absolute;
					  height: 190px;
					}

					.stripes .s1 {
					  height: 380px;
					  top: 0;
					  left: 0;
					  width: 24%;
					  background: linear-gradient(90deg, #e6ebf1, rgba(230, 235, 241, 0));
					}

					.stripes .s2 {
					  top: 380px;
					  left: 4%;
					  width: 35%;
					  background: linear-gradient(
						90deg,
						hsla(0, 0%, 100%, 0.65),
						hsla(0, 0%, 100%, 0)
					  );
					}

					.stripes .s3 {
					  top: 380px;
					  right: 0;
					  width: 38%;
					  background: linear-gradient(90deg, #e4e9f0, rgba(228, 233, 240, 0));
					}

					 {
					  display: -ms-flexbox;
					  display: flex;
					  -ms-flex-wrap: wrap;
					  flex-wrap: wrap;
					  position: relative;
					  max-width: 750px;
					  padding: 110px 20px 110px;
					}

					 .cell {
					  display: -ms-flexbox;
					  display: flex;
					  -ms-flex-direction: column;
					  flex-direction: column;
					  -ms-flex-pack: center;
					  justify-content: center;
					  position: relative;
					  -ms-flex: auto;
					  flex: auto;
					  min-width: 100%;
					  min-height: 500px;
					  padding: 0 40px;
					}

					 .cell + .cell {
					  margin-top: 70px;
					}

					 .cell.intro {
					  padding: 0;
					}

					@media (min-width: 670px) {
					 .cell.intro {
						-ms-flex-align: center;
						align-items: center;
						text-align: center;
					  }

					  .optionList {
						margin-left: 13px;
					  }
					}

					 .cell.intro > * {
					  width: 100%;
					  max-width: 700px;
					}

					 .cell.intro .common-IntroText {
					  margin-top: 10px;
					}

					 .cell.intro .common-BodyText {
					  margin-top: 15px;
					}

					 .cell.intro .common-ButtonGroup {
					  width: auto;
					  margin-top: 20px;
					}

					  .stripe-form {
					  -ms-flex-align: center;
					  align-items: center;
					  border-radius: 4px;
					  box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
					  padding: 80px 0px;
					  margin-left: -20px;
					  margin-right: -20px;
					}

					@media (min-width: 670px) {
					    .stripe-form {
						padding: 40px;
					  }
					}

					 .stripe-form.submitted form,
					 .stripe-form.submitting form {
					  opacity: 0;
					  transform: scale(0.9);
					  pointer-events: none;
					}

					 .stripe-form.submitted .success,
					 .stripe-form.submitting .success {
					  pointer-events: all;
					}

					 .stripe-form.submitting .success .icon {
					  opacity: 1;
					}

					 .stripe-form.submitted .success > * {
					  opacity: 1;
					  transform: none !important;
					}

					  .stripe-form.submitted .success > :nth-child(2) {
					  transition-delay: 0.1s;
					}

					  .stripe-form.submitted .success > :nth-child(3) {
					  transition-delay: 0.2s;
					}

					  .stripe-form.submitted .success > :nth-child(4) {
					  transition-delay: 0.3s;
					}

					  .stripe-form.submitted .success .icon .border,
					  .stripe-form.submitted .success .icon .checkmark {
					  opacity: 1;
					  stroke-dashoffset: 0 !important;
					}

					  .stripe-form * {
					  margin: 0;
					  padding: 0;
					}

					  .stripe-form .caption {
					  display: flex;
					  justify-content: space-between;
					  position: absolute;
					  width: 100%;
					  top: 100%;
					  left: 0;
					  padding: 15px 10px 0;
					  color: #aab7c4;
					  font-family: Roboto, "Open Sans", "Segoe UI", sans-serif;
					  font-size: 15px;
					  font-weight: 500;
					}

					  .stripe-form .caption * {
					  font-family: inherit;
					  font-size: inherit;
					  font-weight: inherit;
					}

					  .stripe-form .caption .no-charge {
					  color: #cfd7df;
					  margin-right: 10px;
					}

					  .stripe-form .caption a.source {
					  text-align: right;
					  color: inherit;
					  transition: color 0.1s ease-in-out;
					  margin-left: 10px;
					}

					  .stripe-form .caption a.source:hover {
					  color: #6772e5;
					}

					  .stripe-form .caption a.source:active {
					  color: #43458b;
					}

					  .stripe-form .caption a.source  svg {
					  margin-right: 10px;
					}

					  .stripe-form .caption a.source svg path {
					  fill: currentColor;
					}

					  .stripe-form form {
					  position: relative;
					  width: 100%;
					  max-width: 500px;
					  transition-property: opacity, transform;
					  transition-duration: 0.35s;
					  transition-timing-function: cubic-bezier(0.165, 0.84, 0.44, 1);
					}

					  .stripe-form form input::-webkit-input-placeholder {
					  opacity: 1;
					}

					  .stripe-form form input::-moz-placeholder {
					  opacity: 1;
					}

					  .stripe-form form input:-ms-input-placeholder {
					  opacity: 1;
					}

					  .stripe-form .error {
					  display: -ms-flexbox;
					  display: flex;
					  -ms-flex-pack: center;
					  justify-content: center;
					  position: absolute;
					  width: 100%;
					  top: 100%;
					  margin-top: 20px;
					  left: 0;
					  padding: 0 15px;
					  font-size: 13px !important;
					  opacity: 0;
					  transform: translateY(10px);
					  transition-property: opacity, transform;
					  transition-duration: 0.35s;
					  transition-timing-function: cubic-bezier(0.165, 0.84, 0.44, 1);
					}

					  .stripe-form .error.visible {
					  opacity: 1;
					  transform: none;
					}

					  .stripe-form .error .message {
					  font-size: inherit;
					}

					  .stripe-form .error svg {
					  -ms-flex-negative: 0;
					  flex-shrink: 0;
					  margin-top: -1px;
					  margin-right: 10px;
					}

					  .stripe-form .success {
					  display: -ms-flexbox;
					  display: flex;
					  -ms-flex-direction: column;
					  flex-direction: column;
					  -ms-flex-align: center;
					  align-items: center;
					  -ms-flex-pack: center;
					  justify-content: center;
					  position: absolute;
					  width: 100%;
					  height: 100%;
					  top: 0;
					  left: 0;
					  padding: 10px;
					  text-align: center;
					  pointer-events: none;
					  overflow: hidden;
					}

					@media (min-width: 670px) {
					    .stripe-form .success {
						padding: 40px;
					  }
					}

					  .stripe-form .success > * {
					  transition-property: opacity, transform;
					  transition-duration: 0.35s;
					  transition-timing-function: cubic-bezier(0.165, 0.84, 0.44, 1);
					  opacity: 0;
					  transform: translateY(50px);
					}

					  .stripe-form .success .icon {
					  margin: 15px 0 30px;
					  transform: translateY(70px) scale(0.75);
					  width: 100px;
height: 70px;
					}

					  .stripe-form .success .icon svg {
					  will-change: transform;
					}

					  .stripe-form .success .icon .border {
					  stroke-dasharray: 251;
					  stroke-dashoffset: 62.75;
					  transform-origin: 50% 50%;
					  transition: stroke-dashoffset 0.35s cubic-bezier(0.165, 0.84, 0.44, 1);
					  animation: spin 1s linear infinite;
					}

					  .stripe-form .success .icon .checkmark {
					  stroke-dasharray: 60;
					  stroke-dashoffset: 60;
					  transition: stroke-dashoffset 0.35s cubic-bezier(0.165, 0.84, 0.44, 1) 0.35s;
					}

					  .stripe-form .success .title {
					  font-size: 17px;
					  font-weight: 500;
					  margin-bottom: 8px;
					}

					  .stripe-form .success .message {
					  font-size: 14px;
					  font-weight: 400;
					  margin-bottom: 25px;
					  line-height: 1.6em;
					}

					  .stripe-form .success .message span {
					  font-size: inherit;
					}

					  .stripe-form .success .reset:active {
					  transition-duration: 0.15s;
					  transition-delay: 0s;
					  opacity: 0.65;
					}

					  .stripe-form .success .reset svg {
					  will-change: transform;
					}

					footer {
					  position: relative;
					  max-width: 750px;
					  padding: 50px 20px;
					  margin: 0 auto;
					}

					.optionList {
					  margin: 6px 0;
					}

					.optionList li {
					  display: inline-block;
					  margin-right: 13px;
					}

					.optionList a {
					  color: #aab7c4;
					  transition: color 0.1s ease-in-out;
					  cursor: pointer;
					  font-size: 15px;
					  line-height: 26px;
					}

					.optionList a.selected {
					  color: #6772e5;
					  font-weight: 600;
					}

					.optionList a:hover {
					  color: #32325d;
					}

					.optionList a.selected:hover {
					  cursor: default;
					  color: #6772e5;
					}
				</style>
				<style type="text/css">
				.StripeElement {
				  background-color: white;
				  height: 40px;
				  padding: 10px 12px;
				  border-radius: 4px;
				  border: 1px solid transparent;
				  box-shadow: 0 1px 3px 0 #e6ebf1;
				  -webkit-transition: box-shadow 150ms ease;
				  transition: box-shadow 150ms ease;
				}

				.StripeElement--focus {
				  box-shadow: 0 1px 3px 0 #cfd7df;
				}

				.StripeElement--invalid {
				  border-color: #fa755a;
				}

				.StripeElement--webkit-autofill {
				  background-color: #fefde5 !important;
				}
				
				.stripe-form.stripe-final-form {
				  background-color: #fff;
				}

				.stripe-form.stripe-final-form * {
				  font-family: Source Code Pro, Consolas, Menlo, monospace;
				  font-size: 16px;
				  font-weight: 500;
				}

				.stripe-form.stripe-final-form .row {
				  display: -ms-flexbox;
				  display: flex;
				  margin: 0 5px 10px;
				}

				.stripe-form.stripe-final-form .field {
				  position: relative;
				  width: 100%;
				  height: 50px;
				  margin: 0 10px;
				}

				.stripe-form.stripe-final-form .field.half-width {
				  width: 50%;
				}

				.stripe-form.stripe-final-form .field.quarter-width {
				  width: calc(25% - 10px);
				}

				.stripe-form.stripe-final-form .baseline {
				  position: absolute;
				  width: 100%;
				  height: 1px;
				  left: 0;
				  bottom: 0;
				  background-color: #cfd7df;
				  transition: background-color 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
				}

				.stripe-form.stripe-final-form label {
				  position: absolute;
				  width: 100%;
				  left: 0;
				  bottom: 8px;
				  color: #cfd7df;
				  overflow: hidden;
				  text-overflow: ellipsis;
				  white-space: nowrap;
				  transform-origin: 0 50%;
				  cursor: text;
				  transition-property: color, transform;
				  transition-duration: 0.3s;
				  transition-timing-function: cubic-bezier(0.165, 0.84, 0.44, 1);
				}

				.stripe-form.stripe-final-form .input {
				  position: absolute;
				  width: 100%;
				  left: 0;
				  bottom: 0;
				  padding-bottom: 7px;
				  color: #32325d;
				  background-color: transparent;
				}

				.stripe-form.stripe-final-form .input::-webkit-input-placeholder {
				  color: transparent;
				  transition: color 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
				}

				.stripe-form.stripe-final-form .input::-moz-placeholder {
				  color: transparent;
				  transition: color 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
				}

				.stripe-form.stripe-final-form .input:-ms-input-placeholder {
				  color: transparent;
				  transition: color 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
				}

				.stripe-form.stripe-final-form .input.StripeElement {
				  opacity: 0;
				  transition: opacity 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
				  will-change: opacity;
				}

				.stripe-form.stripe-final-form .input.focused,
				.stripe-form.stripe-final-form .input:not(.empty) {
				  opacity: 1;
				}

				.stripe-form.stripe-final-form .input.focused::-webkit-input-placeholder,
				.stripe-form.stripe-final-form .input:not(.empty)::-webkit-input-placeholder {
				  color: #cfd7df;
				}

				.stripe-form.stripe-final-form .input.focused::-moz-placeholder,
				.stripe-form.stripe-final-form .input:not(.empty)::-moz-placeholder {
				  color: #cfd7df;
				}

				.stripe-form.stripe-final-form .input.focused:-ms-input-placeholder,
				.stripe-form.stripe-final-form .input:not(.empty):-ms-input-placeholder {
				  color: #cfd7df;
				}

				.stripe-form.stripe-final-form .input.focused + label,
				.stripe-form.stripe-final-form .input:not(.empty) + label {
				  color: #aab7c4;
				  transform: scale(0.85) translateY(-25px);
				  cursor: default;
				}

				.stripe-form.stripe-final-form .input.focused + label {
				  color: #24b47e;
				}

				.stripe-form.stripe-final-form .input.invalid + label {
				  color: #ffa27b;
				}

				.stripe-form.stripe-final-form .input.focused + label + .baseline {
				  background-color: #24b47e;
				}

				.stripe-form.stripe-final-form .input.focused.invalid + label + .baseline {
				  background-color: #e25950;
				}

				.stripe-form.stripe-final-form input, .stripe-form.stripe-final-form button {
				  -webkit-appearance: none;
				  -moz-appearance: none;
				  appearance: none;
				  outline: none;
				  border-style: none;
				}

				.stripe-form.stripe-final-form input:-webkit-autofill {
				  -webkit-text-fill-color: #e39f48;
				  transition: background-color 100000000s;
				  -webkit-animation: 1ms void-animation-out;
				}

				.stripe-form.stripe-final-form .StripeElement--webkit-autofill {
				  background: transparent !important;
				}

				.stripe-form.stripe-final-form input, .stripe-form.stripe-final-form button {
				  -webkit-animation: 1ms void-animation-out;
				}

				.stripe-form.stripe-final-form button {
				  display: block;
				  width: calc(100% - 30px);
				  height: 40px;
				  margin: 40px 15px 0;
				  background-color: #24b47e;
				  border-radius: 4px;
				  color: #fff;
				  text-transform: uppercase;
				  font-weight: 600;
				  cursor: pointer;
				}

				.stripe-form.stripe-final-form input:active {
				  background-color: #159570;
				}

				.stripe-form.stripe-final-form .error svg {
				  margin-top: 0 !important;
				}

				.stripe-form.stripe-final-form .error svg .base {
				  fill: #e25950;
				}

				.stripe-form.stripe-final-form .error svg .glyph {
				  fill: #fff;
				}

				.stripe-form.stripe-final-form .error .message {
				  color: #e25950;
				}

				.stripe-form.stripe-final-form .success .icon .border {
				  stroke: #abe9d2;
				}

				.stripe-form.stripe-final-form .success .icon .checkmark {
				  stroke: #24b47e;
				}

				.stripe-form.stripe-final-form .success .title {
				  color: #32325d;
				  font-size: 16px !important;
				}

				.stripe-form.stripe-final-form .success .message {
				  color: #8898aa;
				  font-size: 13px !important;
				}

				.stripe-form.stripe-final-form .success .reset path {
				  fill: #24b47e;
				}
				#payment-form>button{
				display:none !important;
			}
			</style>
			
			
			<script src="https://js.stripe.com/v3/"></script>
			
			
			<script type="text/javascript">
			jQuery(document).ready(function($){

			

			\'use strict\';

			var stripe = Stripe(\''.$this->publicKey.'\');

			function registerElements(elements, stripeFormName) {
			  var formClass = \'.\' + stripeFormName;
			  var stripeForm = document.querySelector(formClass);

			  var form = stripeForm.querySelector(\'form\');
			  var resetButton = stripeForm.querySelector(\'a.reset\');
			  var error = form.querySelector(\'.error\');
			  var errorMessage = error.querySelector(\'.message\');

			  function enableInputs() {
				Array.prototype.forEach.call(
				  form.querySelectorAll(
					"input[type=\'text\'], input[type=\'email\'], input[type=\'tel\']"
				  ),
				  function(input) {
					input.removeAttribute(\'disabled\');
				  }
				);
			  }

			  function disableInputs() {
				Array.prototype.forEach.call(
				  form.querySelectorAll(
					"input[type=\'text\'], input[type=\'email\'], input[type=\'tel\']"
				  ),
				  function(input) {
					input.setAttribute(\'disabled\', \'true\');
				  }
				);
			  }

			  function triggerBrowserValidation() {
				// The only way to trigger HTML5 form validation UI is to fake a user submit
				// event.
				var submit = document.createElement(\'input\');
				submit.type = \'submit\';
				submit.style.display = \'none\';
				form.appendChild(submit);
				submit.click();
				submit.remove();
			  }

			  // Listen for errors from each Element, and show error messages in the UI.
			  var savedErrors = {};
			  elements.forEach(function(element, idx) {
				element.on(\'change\', function(event) {
				  if (event.error) {
					error.classList.add(\'visible\');
					savedErrors[idx] = event.error.message;
					errorMessage.innerText = event.error.message;
				  } else {
					savedErrors[idx] = null;

					// Loop over the saved errors and find the first one, if any.
					var nextError = Object.keys(savedErrors)
					  .sort()
					  .reduce(function(maybeFoundError, key) {
						return maybeFoundError || savedErrors[key];
					  }, null);

					if (nextError) {
					 
					  errorMessage.innerText = nextError;
					} else {
					  // The user fixed the last error; no more errors.
					  error.classList.remove(\'visible\');
					}
				  }
				});
			  });

			 
			  form.addEventListener(\'submit\', function(e) {
				e.preventDefault();

				// Trigger HTML5 validation UI on the form if any of the inputs fail
				// validation.
				var plainInputsValid = true;
				Array.prototype.forEach.call(form.querySelectorAll(\'input\'), function(
				  input
				) {
				  if (input.checkValidity && !input.checkValidity()) {
					plainInputsValid = false;
					return;
				  }
				});
				if (!plainInputsValid) {
				  triggerBrowserValidation();
				  return;
				}

				// Show a loading screen...
				stripeForm.classList.add(\'submitting\');

				// Disable all inputs.
				disableInputs();

				
				var name = form.querySelector(\'#\' + stripeFormName + \'-name\');
				var address1 = form.querySelector(\'#\' + stripeFormName + \'-address\');
				var city = form.querySelector(\'#\' + stripeFormName + \'-city\');
				var state = form.querySelector(\'#\' + stripeFormName + \'-state\');
				var zip = form.querySelector(\'#\' + stripeFormName + \'-zip\');
				var additionalData = {
				  name: name ? name.value : undefined,
				  address_line1: address1 ? address1.value : undefined,
				  address_city: city ? city.value : undefined,
				  address_state: state ? state.value : undefined,
				  address_zip: zip ? zip.value : undefined,
				};

				// Use Stripe.js to create a token. We only need to pass in one Element
				// from the Element group in order to create a token. We can also pass
				// in the additional customer data we collected in our form.
				stripe.createToken(elements[0], additionalData).then(function(result) {
				  // Stop loading!
				  stripeForm.classList.remove(\'submitting\');

				  if (result.token) {
					// If we received a token, show the token ID.
					stripeForm.classList.add(\'submitted\');
					stripeTokenHandler(result.token);
					stripeForm.querySelector(\'.token\').innerText = result.token.id;
					
					
					
					
				  } else {
					// Otherwise, un-disable inputs.
					enableInputs();
				  }
				});
			  });

			  resetButton.addEventListener(\'click\', function(e) {
				e.preventDefault();
				
				form.reset();

				// Clear each Element.
				elements.forEach(function(element) {
				  element.clear();
				});

				// Reset error state as well.
				error.classList.remove(\'visible\');

				// Resetting the form does not un-disable inputs, so we need to do it separately:
				enableInputs();
				stripeForm.classList.remove(\'submitted\');
			  });
			}
			
			function stripeTokenHandler(token) {
			  // Insert the token ID into the form so it gets submitted to the server
			  var tokenDiv = document.getElementById(\'customer-buttons-div\');
			  var hiddenInput = document.createElement(\'input\');
			  hiddenInput.setAttribute(\'type\', \'hidden\');
			  hiddenInput.setAttribute(\'name\', \'stripeToken\');
			  hiddenInput.setAttribute(\'value\', token.id);
			  tokenDiv.innerHTML=(\'<input id="stripeToken" type="hidden" name="stripeToken" value="\'+token.id+\'">\');
			  jQuery(\'.car-rental-customer-form\').submit();
			 
			  
				
			  // Submit the form

			}
						  \'use strict\';
			// Create a Stripe client.
			

			

			  var elements = stripe.elements({
				fonts: [
				  {
					cssSrc: \'https://fonts.googleapis.com/css?family=Source+Code+Pro\',
				  },
				],
				
				locale: window.__stripeFormLocale
			  });

			  // Floating labels
			  var inputs = document.querySelectorAll(\'.cell.stripeForm.stripe-final-form .input\');
			  Array.prototype.forEach.call(inputs, function(input) {
				input.addEventListener(\'focus\', function() {
				  input.classList.add(\'focused\');
				});
				input.addEventListener(\'blur\', function() {
				  input.classList.remove(\'focused\');
				});
				input.addEventListener(\'keyup\', function() {
				  if (input.value.length === 0) {
					input.classList.add(\'empty\');
				  } else {
					input.classList.remove(\'empty\');
				  }
				});
			  });

			  var elementStyles = {
				base: {
				  color: \'#32325D\',
				  fontWeight: 500,
				  fontFamily: \'Source Code Pro, Consolas, Menlo, monospace\',
				  fontSize: \'16px\',
				  fontSmoothing: \'antialiased\',

				 \'::placeholder\': {
					color: \'#CFD7DF\',
				  },
				  \':-webkit-autofill\': {
					color: \'#e39f48\',
				  },
				},
				invalid: {
				  color: \'#E25950\',

				  \'::placeholder\': {
					color: \'#FFCCA5\',
				  },
				},
			  };

			  var elementClasses = {
				focus: \'focused\',
				empty: \'empty\',
				invalid: \'invalid\',
			  };

			  var cardNumber = elements.create(\'cardNumber\', {
				style: elementStyles,
				classes: elementClasses,
			  });
			  cardNumber.mount(\'#stripe-final-form-card-number\');

			  var cardExpiry = elements.create(\'cardExpiry\', {
				style: elementStyles,
				classes: elementClasses,
			  });
			  cardExpiry.mount(\'#stripe-final-form-card-expiry\');

			  var cardCvc = elements.create(\'cardCvc\', {
				style: elementStyles,
				classes: elementClasses,
			  });
			  cardCvc.mount(\'#stripe-final-form-card-cvc\');

			  registerElements([cardNumber, cardExpiry, cardCvc], \'stripe-final-form\');
			});
			</script>

			<script type="text/javascript">
			jQuery(document).ready(function($){

				


			});
			</script>';
			
		/*	$objBookingsObserver = new \NativeRentalSystem\Models\Booking\BookingsObserver($this->conf, $this->lang, $this->settings);
        $objBooking = new \NativeRentalSystem\Models\Booking\Booking(
                $this->conf, $this->lang, $this->settings, $objBookingsObserver->getIdByCode($this->bookingCode)
            );

		$costumerId = $objBooking->getCustomerId();
		var_dump($costumerId);*/
		

        return $ret;
    }

    public function setProcessingPage($paramBookingCode, $paramTotalPayNow = '0.00')
    {
        $this->bookingCode = sanitize_text_field($paramBookingCode);
        $this->totalPayNow = floatval($paramTotalPayNow);
        // Stripe does not uses form feature
    }

    public function getProcessingPageContent()
    {
        $errorMessage = '';
        $debugLog = '';
		$isCostumerCardSaved;
        require_once ('NRSStripeProcessor.php');
        $objStripeAPI = new \NRSStripeProcessor($this->conf, $this->lang, $this->settings);
		
		$objBookingsObserver = new \NativeRentalSystem\Models\Booking\BookingsObserver($this->conf, $this->lang, $this->settings);
        $objBooking = new \NativeRentalSystem\Models\Booking\Booking(
                $this->conf, $this->lang, $this->settings, $objBookingsObserver->getIdByCode($this->bookingCode)
            );

		$costumerId = $objBooking->getCustomerId();
		
		$cotumerCredentials=$objBooking->getPaymentUser($costumerId);
		
		
		
		$succesSavingCard = $objStripeAPI->saveStripeCard($this->privateKey, $cotumerCredentials['email']);
		
		if($succesSavingCard['succes']===1){
			$isCostumerCardSaved=$objBooking->saveCardIdToCostumer($costumerId,$succesSavingCard['cardID']);
		}else{
			
			die($succesSavingCard);
		}
		
		if($isCostumerCardSaved === FALSE || $isCostumerCardSaved === 0)
        {
            $this->errorMessages[] = 'Stripe Costumer is not saved!';
        } else
        {
            $succeeded = $objStripeAPI->process($this->privateKey, $this->bookingCode, $this->totalPayNow,$succesSavingCard['cardID'],$cotumerCredentials['first_name'].' '.$cotumerCredentials['last_name'],$cotumerCredentials['email']);
        }
		
		
		
		
        // Process API
        
        // Stripe does not uses processing page content feature
        $ret = '';

        // TODO: GET REAL STRIPE CUSTOMER EMAIL AND TRANSACTION ID
        $paramPayerEmail = $cotumerCredentials['email'];
        $transactionId = $succeeded['chargeID'];
        $isFailed = $succeeded['succes'] == 0;
		
        if($succeeded['succes']===1)
        {
            $objEMailsObserver = new \NativeRentalSystem\Models\EMail\EMailsObserver($this->conf, $this->lang, $this->settings);
            
            $objInvoice = new \NativeRentalSystem\Models\Booking\Invoice($this->conf, $this->lang, $this->settings, $objBooking->getId());

            $printPayerEmail = esc_html(sanitize_text_field($paramPayerEmail));
            $printTransactionId = esc_html(sanitize_text_field($transactionId));

            $payPalHtmlToAppend = '<!-- PAYPAL PAYMENT DETAILS -->
<br /><br />
<table style="font-family:Verdana, Geneva, sans-serif; font-size: 12px; background-color:#eeeeee; width:840px; border:none;" cellpadding="4" cellspacing="1">
<tr>
<td align="left" width="30%" style="font-weight:bold; font-variant:small-caps; background-color:#ffffff; padding-left:5px;">'.$this->lang->getText('NRS_PAYER_EMAIL_TEXT').'</td>
<td align="left" style="background-color:#ffffff; padding-left:5px;">'.$printPayerEmail.'</td>
</tr>
<tr>
<td align="left" style="font-weight:bold; font-variant:small-caps; background-color:#ffffff; padding-left:5px;">'.$this->lang->getText('NRS_TRANSACTION_ID_TEXT').'</td>
<td align="left" style="background-color:#ffffff; padding-left:5px;">'.$printTransactionId.'</td>
</tr>
</table>';
            $appended = $objInvoice->append($payPalHtmlToAppend);
            $markedAsPaid = $objBooking->markPaid($transactionId, $paramPayerEmail);
            $emailProcessed = $objEMailsObserver->sendBookingConfirmationEmail($objBooking->getId(), TRUE);
            $errorMessage = '';
            $debugLog = '';
            if($markedAsPaid && $emailProcessed === FALSE)
            {
                $errorMessage .= 'Failed: Reservation was marked as paid, but system was unable to send the confirmation email!';
            } else if($markedAsPaid === FALSE)
            {
                $errorMessage .= 'Failed: Reservation was not marked as paid!';
            } else if($appended === FALSE)
            {
                $errorMessage .= 'Failed: Transaction data was not appended to invoice!';
            }
			
			$allBookingFields=$objBooking->getAllBukingFields($objBooking->getId());
			$timeAtTheMoment=time();
			$timeAtTheMomentPlusDay=$timeAtTheMoment+24*3600;
			
			$pickupTimestamp=$allBookingFields['pickup_timestamp'];
			$pickupTimestamp=(int)$pickupTimestamp;
			if($pickupTimestamp<$timeAtTheMomentPlusDay){
				
				$invoiceDetails = $objInvoice->getDetails();
				$price=$invoiceDetails['print_fixed_deposit_amount'];
				$price=str_ireplace('$','',$price);
				$price=str_ireplace(' ','',$price);
				$price=(int)$price;
				$isSucces=$objStripeAPI->depositPreAutorize($this->privateKey,$succesSavingCard['cardID'],$price);
				
				
				if($isSucces['succes']===1){
					$updateTransactionId=$objBooking->preAutorize($allBookingFields['booking_id'],$isSucces['depositPreAutorizeID']);
				}
			}
        }

        // Save log
        $objLog = new Log($this->conf, $this->lang, $this->settings, 0);
        $objLog->save('payment-callback', $paramPayerEmail, '', '', $isFailed, $errorMessage, $debugLog);

        return $ret;
    }

    public function processAPI()
    {
        // Stripe does not use API callback process
    }
	
	
	
	
    /******************************************************************************************/
}
 
