<?php
/*
  $Id: create_account.php,v 1.2 2004/03/05 00:36:42 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/


// MAIL VALIDATION START //
define('VALIDATE_YOUR_MAILADRESS', 'Click here to Validate/Activate Your account');
define('SECOND_LINK', '<B>Or you can manually copy and paste in the following link into your browsers window:</B><br> ');
define('OR_VALIDATION_CODE', '<B>Your Validation Code is:</B> ');
define('MAIL_VALIDATION', '<FONT COLOR="#FF0000"><B>You have to validate/activate your account before you can login.</B></FONT><P><B>Please click on the link below to finish the account creation process:</B> ');
// MAIL VALIDATION END //

define('NAVBAR_TITLE', 'Create an Account');

define('HEADING_TITLE', 'My Account Information');

define('TEXT_ORIGIN_LOGIN', '<font color="#FF0000"><small><b>NOTE:</b></font></small> If you already have an account with us, please login at the <a href="%s"><u>login page</u></a>.');

define('EMAIL_SUBJECT', 'Welcome to ' . STORE_NAME);
define('EMAIL_GREET_MR', 'Dear Mr. %s,' . "\n\n");
define('EMAIL_GREET_MS', 'Dear Ms. %s,' . "\n\n");
define('EMAIL_GREET_NONE', 'Dear %s' . "\n\n");
define('EMAIL_WELCOME', 'We welcome you to <b>' . STORE_NAME . '</b>.' . "\n\n");

define('EMAIL_TEXT', 'You can now take part in the <b>various services</b> we have to offer you. Some of these services include:' . "\n\n" . '<li><b>Permanent Cart</b> - Any products added to your online cart remain there until you remove them, or check them out.' . "\n" . '<li><b>Address Book</b> - We can now deliver your products to another address other than yours! This is perfect to send birthday gifts direct to the birthday-person themselves.' . "\n" . '<li><b>Order History</b> - View your history of purchases that you have made with us.' . "\n" . '<li><b>Products Reviews</b> - Share your opinions on products with our other customers.' . "\n\n");
define('ADMIN_EMAIL_WELCOME', 'Application to become a wholesale customer of <b>' . STORE_NAME . '</b>.' . "\n\n");
define('ADMIN_EMAIL_TEXT', 'You have received an application to become a wholesale customer from your website.  Information regarding this application can be found at your online administration panel.' . "\n\n");

define('EMAIL_CONFIRMATION', 'Thank you for submitting your account information to our ' . STORE_NAME . "\n\n" . 'To finish your account setup please verify your e-mail address by clicking the link below: ' . "\n\n");
define('EMAIL_CONTACT', 'For help with any of our online services, please email the store-owner: ');
define('EMAIL_CONTACT_TEXT', '<a href=\"mailto:' . STORE_OWNER_EMAIL_ADDRESS . '\">' . STORE_OWNER_EMAIL_ADDRESS . '</a>' . "\n\n");
define('EMAIL_WARNING', '<b>Note:</b> This Email address was given to us by one of our customers. If you did not signup to be a member, please send an email to ');
define('EMAIL_WARNING_TEXT', '<a href=\"mailto:' . STORE_OWNER_EMAIL_ADDRESS . '\">' . STORE_OWNER_EMAIL_ADDRESS . '</a>' . "\n");

/* ICW Credit class gift voucher begin */
define('EMAIL_GV_INCENTIVE_HEADER', "\n\n" .'As part of our welcome to new customers, we have sent you a Gift Voucher worth %s');
define('EMAIL_GV_REDEEM', 'The redeem code for your Gift Voucher is %s. You can enter the redeem code when checking out while making a purchase');
define('EMAIL_GV_LINK', 'or by following this link ');
define('EMAIL_COUPON_INCENTIVE_HEADER', 'Congratulations! to make your first visit to our online shop a more rewarding experience, we are sending you an e-Discount Coupon.' . "\n" .
                                        ' Below are details of the Discount Coupon created just for you' . "\n");
define('EMAIL_COUPON_REDEEM', 'To use the coupon enter the redeem code which is %s during checkout while making a purchase');
/* ICW Credit class gift voucher end */
define('HEADING_TITLE_CHECKOUT','Checkout Personal Info');// Added by sheetal for PWA form Title

define('MAIL_VALIDATION_B2B','Thank you for creating an account in our store. We validate account access. We will review your information and contact you if necessary. If we approve your account, you will receive a notification via email."');
?>