<?php
/*
  CRE Loaded , Open Source E-Commerce Solutions
  http://www.creloaded.com

     Chain Reaction Works, Inc
     Copyright &copy; 2005 - 2007 Chain Reaction Works, Inc.

   Released under the GNU General Public License
*/

define('TEXT_INSTALL_1', VERSION . ' Admin Password Configuration');
define('TEXT_INSTALL_CREM', 'CRE Merchant Account Application');
define('TEXT_INSTALL_3', 'The configuration was successful!');
define('TEXT_INSTALL_4', 'The admin user is ');
define('TEXT_INSTALL_4a', ' and the admin password is ' );
define('TEXT_INSTALL_5', 'To go to the Catalog, Click here');
define('TEXT_INSTALL_6', 'To go to the Admin, Click here');
define('TEXT_INSTALL_7', 'Once you have book marked the catalog and admin please delete the install directory. Failure to do so allows anyone to change your username and password.  This might be bad.');
define('TEXT_INSTALL_8', 'ERROR: ');
define('TEXT_INSTALL_9', 'Possible solution: ');

define('TEXT_CRE_MERCHANT_SKIP', 'Skip This Step');
define('TEXT_CRE_MERCHANT_SKIP_CONFIRM_YES', 'No, I\'ll continue without credit card processing');
define('TEXT_CRE_MERCHANT_SKIP_CONFIRM_NO', 'Wait, I want to apply for CRE Merchant!');

//Errors
define('TEXT_ERROR_11', 'The Admin user is not a valid email');
define('TEXT_ERROR_12', 'The Admin user admin@localhost.com should not be used');
define('TEXT_ERROR_13', 'The Admin user or Admin password is empty');
define('TEXT_ERROR_14', 'There was a MySQL database error while trying to add admin info');
define('TEXT_ERROR_15', 'There was an error writing to the catalog or admin configure files');
define('TEXT_ERROR_16', 'There was an error connecting to the database during test connection');

//Suggestion for fixing the error
define('TEXT_ERROR_1_S', 'Please click the back button below and make sure the Admin email is valid');
define('TEXT_ERROR_2_S', 'You have tried to use admin@localhost.com as the admin email, This is well known by hackers. Please click the back button and use a different email');
define('TEXT_ERROR_3_S', 'Click the back button, and make sure the Admn user and Admin password are not empty');
define('TEXT_ERROR_4_S', 'There was a MySQL database error while trying to add admin info');
define('TEXT_ERROR_5_S', 'The following error has occurred: <b>The configuration files do not exist, or permission levels are not set.</b><br><br>Please perform the following actions:
                          <ul class="boxMe"><li>cd %s includes/</li><li>touch configure.php</li><li>chmod 706 configure.php</li></ul>
                          <ul class="boxMe"><li>cd %s admin/includes/</li><li>touch configure.php</li><li>chmod 706 configure.php</li></ul></p>
                          <p class="noteBox">If <i>chmod 706</i> does not work, please try <i>chmod 777</i>.</p>
                          <p class="noteBox">If you are running this installation procedure under a Microsoft Windows environment, try renaming the existing configuration file so a new file can be created.</p>');
define('TEXT_ERROR_6_S', '<p>A test connection made to the database was <b>NOT</b> successful.</p>
                          <p>The error message returned is:</p>
                          <p class="boxme">' . $db_error . '</p>
                          <p>Please click on the <i>Back</i> button below to review your database server settings.</p>
                          <p>If you require help with your database server settings, please consult your hosting company.</p>');
define('TEXT_ERROR_7', 'This Email Address has already been added to the database');
define('TEXT_ERROR_7_S', 'This Email Address has already been added to the database');

//Regualar text in form
define('TEXT_COMPANY_NAME','Company Name:');
define('TEXT_FULL_NAME','Full Name:');
define('TEXT_TELEPHONE','Day Time Phone:');
define('TEXT_NIGHT_PHONE','Night Time Phone:');
define('TEXT_COUNTRY','Country:');
define('TEXT_EMAIL_ADDRESS','Email:');
define('TEXT_YEARS_IN_BUSINESS','Years in Business:');
define('TEXT_WEBSITE','Web Site Address:');
define('TEXT_PROCESSING','Processing:');
define('TEXT_START_PROCESSING','Start Processing In:');
define('TEXT_COMMENTS','Comments:');
?>