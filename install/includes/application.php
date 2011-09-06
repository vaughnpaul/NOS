<?php
/*

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Released under the GNU General Public License
*/
    
//catalog version
  define('VERSION', 'CRE Loaded PCI Pro v6.4');
//install logo
  define('INSTALL_LOGO', 'cre62_pro_title.png');
// install sql file
  define('SQL_SCHEMA', 'install/sql/creloaded_schema.sql');
  define('SQL_BASEDATA', 'install/sql/creloaded_basedata.sql');
  define('SQL_CONFIGDATA', 'install/sql/creloaded_configdata.sql');
  define('SQL_DEMO_DATA', 'install/sql/creloaded_demodata.sql');
  
// Set the level of error reporting
  error_reporting(E_ALL & ~E_NOTICE);

  require('includes/functions/general.php');
  require('includes/functions/database.php');
  require('includes/functions/html_output.php');
  require('includes/functions/validations.php');
  require('includes/languages/language_list.php');
  
//default language
  $language_d = 'english';
  $languages_code_d = 'en';

// set the language
  $language_code = (isset($_POST['language_code']) ? $_POST['language_code'] : $languages_code_d);
  $language = (isset($languages_list[$language_code]['directory']) ? $languages_list[$language_code]['directory'] : $language_d);

  if  (is_null($language)) {
    $language = $language_d;
  }

// include the language main translations, not each page calls it's own
  require('includes/languages/' . $language . '.php');
// initialize the message stack for output messages
  require('includes/classes/message_stack.php');
  $messageStack = new messageStack;

?>