<?php
/*
  CRE Loaded , Open Source E-Commerce Solutions
  http://www.creloaded.com

     Chain Reaction Works, Inc
     Copyright &copy; 2005 - 2007 Chain Reaction Works, Inc.

   Released under the GNU General Public License

*/

define('TEXT_BUTTON_RECHECK', 'Recheck the settings');
define('TEXT_INSTALL_2', 'Successfully connected to the MYSQL server.<br>');
define('TEXT_INSTALL_2A', 'New Database Settings');
define('TEXT_INSTALL_2B', 'Test Data to be Imported: ');
define('TEXT_INSTALL_4', 'Please enter the settings of the new database. The new database must be empty. The installer will copy your existing database tables to this new database.');
define('TEXT_INSTALL_6', 'Database Server:');
define('TEXT_INSTALL_7', 'Hostname or IP-address of the database server');
define('TEXT_INSTALL_8', 'The database server can be in the form of a hostname, such as db1.myserver.com, or as an IP-address, such as 192.168.0.1');
define('TEXT_INSTALL_9', 'Database username');
define('TEXT_INSTALL_10', 'The username used to connect to the database server. An example username is \'mysql_10\'.<br><br>Note: Create and Drop permissions <b>are required</b> at this point of the installation procedure.');
define('TEXT_INSTALL_11', 'Password:');
define('TEXT_INSTALL_12', 'Database password');
define('TEXT_INSTALL_13', 'The password is used together with the username, which forms the database user account.');
define('TEXT_INSTALL_14', 'Database Name:');
define('TEXT_INSTALL_15', 'Database Name');
define('TEXT_INSTALL_16', 'The database used to hold the data. An example database name is \'creloaded\'.');
define('TEXT_INSTALL_22', 'Username:');

define('TEXT_INSTALL_PCI_T1', 'PCI Compliance – Essential Requirement'); 
define('TEXT_INSTALL_PCI_T2', '<p>As indicated in the introduction you must follow all of our instructions and recommendations completely, to achieve PCI Compliance. You must now make a decision about any credit card information you have currently stored in your old database. If you do not do this <u>YOU WILL NOT BE ABLE TO ACHIEVE PCI COMPLIANCE</u>.</p>
                               <p>There are three options below.</p>
                               <p><b>Remove all Credit Card data</b>: USE this option if you do not need to reference any card numbers in the future. All data will be deleted from your system. (Note this is the safest option).</p>
                               <p><b>Mask middle 6 of Credit Card data</b>: This will mask the middle six numbers of the credit card information permanently. You can still use the remaining numbers for reference but the whole number will be unusable for charging purposes.</p>
                               <p><b>Mask first 12 of Credit Card data</b>: This will permanently mask all but the last four numbers that can be used for reference.</p>
                               <p>Please select one of the three options:</p>
                              ');

define('TEXT_INSTALL_PCI1', 'PCI Compliant Options for Storing Credit Card Data'); 
define('TEXT_INSTALL_PCI2', 'Select an Option:');
define('TEXT_INSTALL_PCI3', 'Remove ALL Credit Card Data');
define('TEXT_INSTALL_PCI4', 'Mask Middle 6 of Credit Card Data');
define('TEXT_INSTALL_PCI5', 'Mask First 12 of Credit Card Data');   

//Error text
define('TEXT_ERROR_1', '<p>A test connection made to the database was <b>NOT</b> successful.</p>
                        <p>The error message returned is:</p>
                        <p class="boxme">Install Error: ');
define('TEXT_ERROR_1E', '<p>The database you identified already exists.</p>
                         <p>The error message returned is: ');
define('TEXT_ERROR_2', '</p><p class="boxme">Mysql error: ');
define('TEXT_ERROR_3', '</p>');
define('TEXT_ERROR_6', '<p class="error">Please go back and fill in the <i>database name</i>.</p>');
define('TEXT_ERROR_MISSING', '<p class="error">Required information is missing.  Please fill in all required fields.</p>');
?>