<?php
/*
  $Id: customers.php,v 2.0 2008/05/05 00:36:41 datazen Exp $

  CRE Loaded, Commerical Open Source eCommerce
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  
  // RCI for global and individual top
  echo $cre_RCI->get('global', 'top', false);
  echo $cre_RCI->get('customers', 'top', false); 

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  $error = false;
  $processed = false;

  // RCI for adding processes after $_GET['action']
  echo $cre_RCI->get('customers', 'process', false); 

  if (tep_not_null($action)) {
    switch ($action) {
      case 'update':
        $customers_id = tep_db_prepare_input($_GET['cID']);
        $customers_firstname = tep_db_prepare_input($_POST['customers_firstname']);
        $customers_lastname = tep_db_prepare_input($_POST['customers_lastname']);
        $customers_email_address = strtolower(tep_db_prepare_input($_POST['customers_email_address']));
        $customers_telephone = tep_db_prepare_input($_POST['customers_telephone']);
        $customers_fax = tep_db_prepare_input($_POST['customers_fax']);
        $customers_newsletter = tep_db_prepare_input($_POST['customers_newsletter']);
        $customers_emailvalidated = tep_db_prepare_input($_POST['customers_emailvalidated']);
        $customers_gender = tep_db_prepare_input($_POST['customers_gender']);
        $customers_dob = tep_db_prepare_input($_POST['customers_dob']);
        $customers_voucher_amount = tep_db_prepare_input($_POST['customers_voucher_amount']);
        $customers_selected_template = tep_db_prepare_input($_POST['customers_selected_template']);
        $default_address_id = tep_db_prepare_input($_POST['default_address_id']);
        $entry_street_address = tep_db_prepare_input($_POST['entry_street_address']);
        $entry_suburb = tep_db_prepare_input($_POST['entry_suburb']);
        $entry_postcode = tep_db_prepare_input($_POST['entry_postcode']);
        $entry_city = tep_db_prepare_input($_POST['entry_city']);
        $entry_country_id = tep_db_prepare_input($_POST['entry_country_id']);
        $entry_company = tep_db_prepare_input($_POST['entry_company']);
        $entry_state = tep_db_prepare_input($_POST['entry_state']);
        if (isset($_POST['entry_zone_id'])) $entry_zone_id = tep_db_prepare_input($_POST['entry_zone_id']);
        if (strlen($customers_firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
          $error = true;
          $entry_firstname_error = true;
        } else {
          $entry_firstname_error = false;
        }
        if (strlen($customers_lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
          $error = true;
          $entry_lastname_error = true;
        } else {
          $entry_lastname_error = false;
        }
        if (ACCOUNT_DOB == 'true') {
          if (checkdate(substr(tep_date_raw($customers_dob), 4, 2), substr(tep_date_raw($customers_dob), 6, 2), substr(tep_date_raw($customers_dob), 0, 4))) {
            $entry_date_of_birth_error = false;
          } else {
            $error = true;
            $entry_date_of_birth_error = true;
          }
        }
        if (strlen($customers_email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
          $error = true;
          $entry_email_address_error = true;
        } else {
          $entry_email_address_error = false;
        }
        if (!tep_validate_email($customers_email_address)) {
          $error = true;
          $entry_email_address_check_error = true;
        } else {
          $entry_email_address_check_error = false;
        }
        if (strlen($entry_street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
          $error = true;
          $entry_street_address_error = true;
        } else {
          $entry_street_address_error = false;
        }
        if (strlen($entry_postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
          $error = true;
          $entry_post_code_error = true;
        } else {
          $entry_post_code_error = false;
        }
        if (strlen($entry_city) < ENTRY_CITY_MIN_LENGTH) {
          $error = true;
          $entry_city_error = true;
        } else {
          $entry_city_error = false;
        }
        if ($entry_country_id == false) {
          $error = true;
          $entry_country_error = true;
        } else {
          $entry_country_error = false;
        }
        if (ACCOUNT_STATE == 'true') {
          if ($entry_country_error == true) {
            $entry_state_error = true;
          } else {
            $zone_id = 0;
            $entry_state_error = false;
            $check_query = tep_db_query("SELECT count(*) as total 
                                           from " . TABLE_ZONES . " 
                                         WHERE zone_country_id = '" . (int)$entry_country_id . "'");
            $check_value = tep_db_fetch_array($check_query);
            $entry_state_has_zones = ($check_value['total'] > 0);
            if ($entry_state_has_zones == true) {
              $zone_query = tep_db_query("SELECT zone_id 
                                            from " . TABLE_ZONES . " 
                                          WHERE zone_country_id = '" . (int)$entry_country_id . "' 
                                            and zone_name = '" . tep_db_input($entry_state) . "'");
              if (tep_db_num_rows($zone_query) == 1) {
                $zone_values = tep_db_fetch_array($zone_query);
                $entry_zone_id = $zone_values['zone_id'];
              } else {
                $error = true;
                $entry_state_error = true;
              }
            } else {
              if ($entry_state == false) {
                $error = true;
                $entry_state_error = true;
              }
            }
          }
        }
        if (strlen($customers_telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
          $error = true;
          $entry_telephone_error = true;
        } else {
          $entry_telephone_error = false;
        }
        $check_email = tep_db_query("SELECT customers_email_address 
                                       from " . TABLE_CUSTOMERS . " 
                                     WHERE lower(customers_email_address) = '" . tep_db_input($customers_email_address) . "' 
                                       and customers_id != '" . (int)$customers_id . "'");
        if (tep_db_num_rows($check_email)) {
          $error = true;
          $entry_email_address_exists = true;
        } else {
          $entry_email_address_exists = false;
        }
        if ($error == false) {
          $sql_data_array = array('customers_firstname' => $customers_firstname,
                                  'customers_lastname' => $customers_lastname,
                                  'customers_email_address' => $customers_email_address,
                                  'customers_validation' => $customers_emailvalidated,
                                  'customers_selected_template' => $customers_selected_template,
                                  'customers_newsletter' => $customers_newsletter);
          if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $customers_gender;
          if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($customers_dob);

          tep_db_perform(TABLE_CUSTOMERS, $sql_data_array, 'update', "customers_id = '" . (int)$customers_id . "'");
          tep_db_query("update ".TABLE_COUPON_GV_CUSTOMER." set amount = " . $customers_voucher_amount . " where customer_id = " . (int)$customers_id . "");
          tep_db_query("update " . TABLE_CUSTOMERS_INFO . " set customers_info_date_account_last_modified = now() where customers_info_id = '" . (int)$customers_id . "'");

          if ($entry_zone_id > 0) $entry_state = '';
          $sql_data_array = array('entry_firstname' => $customers_firstname,
                                  'entry_lastname' => $customers_lastname,
                                  'entry_email_address' => $customers_email_address,
                                  'entry_telephone' => $customers_telephone,
                                  'entry_fax' => $customers_fax,
                                  'entry_street_address' => $entry_street_address,
                                  'entry_postcode' => $entry_postcode,
                                  'entry_city' => $entry_city,
                                  'entry_country_id' => $entry_country_id);

          if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $entry_suburb;
          if (ACCOUNT_STATE == 'true') {
            if ($entry_zone_id > 0) {
              $sql_data_array['entry_zone_id'] = $entry_zone_id;
              $sql_data_array['entry_state'] = '';
            } else {
              $sql_data_array['entry_zone_id'] = '0';
              $sql_data_array['entry_state'] = $entry_state;
            }
          }
          tep_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array, 'update', "customers_id = '" . (int)$customers_id . "' and address_book_id = '" . (int)$default_address_id . "'");
        } else if ($error == true) {
          $cInfo = new objectInfo($_POST);
          $processed = true;
        }

        // RCI for action update
        echo $cre_RCI->get('customers', 'action', false);
        
        if ($error !== true) {
            $messageStack->add_session('search', sprintf(NOTICE_CUSTOMER_UPDATED, $customers_id), 'success');
            tep_redirect(tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $customers_id));
        }
        break;
      
      case 'deleteconfirm':
        $customers_id = tep_db_prepare_input($_GET['cID']);
        if (isset($_POST['delete_reviews']) && ($_POST['delete_reviews'] == 'on')) {
          $reviews_query = tep_db_query("select reviews_id from " . TABLE_REVIEWS . " where customers_id = '" . (int)$customers_id . "'");
          while ($reviews = tep_db_fetch_array($reviews_query)) {
            tep_db_query("delete from " . TABLE_REVIEWS_DESCRIPTION . " where reviews_id = '" . (int)$reviews['reviews_id'] . "'");
          }

          tep_db_query("delete from " . TABLE_REVIEWS . " where customers_id = '" . (int)$customers_id . "'");
        } else {
          tep_db_query("update " . TABLE_REVIEWS . " set customers_id = null where customers_id = '" . (int)$customers_id . "'");
        }
        tep_db_query("delete from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customers_id . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS . " where customers_id = '" . (int)$customers_id . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . (int)$customers_id . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET . " where customers_id = '" . (int)$customers_id . "'");
        tep_db_query("delete from " . TABLE_CUSTOMERS_BASKET_ATTRIBUTES . " where customers_id = '" . (int)$customers_id . "'");
        tep_db_query("delete from " . TABLE_WHOS_ONLINE . " where customer_id = '" . (int)$customers_id . "'");

        // RCI for action deleteconfirm
        echo $cre_RCI->get('customers', 'action', false);
        $messageStack->add_session('search', sprintf(NOTICE_CUSTOMER_DELETE, $customers_id), 'warning');
        tep_redirect(tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action'))));
        break;

      default:
        $customers_query = tep_db_query("select c.customers_id, c.customers_gender, c.customers_firstname, c.customers_lastname, c.customers_dob, c.customers_email_address, a.entry_company, a.entry_street_address, a.entry_suburb, a.entry_postcode, a.entry_city, a.entry_state, a.entry_zone_id, a.entry_country_id, a.entry_telephone as customers_telephone , a.entry_fax as customers_fax, c.customers_newsletter, c.customers_default_address_id, c.customers_validation, c.customers_selected_template from " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_default_address_id = a.address_book_id where a.customers_id = c.customers_id and c.customers_id = '" . (int)$_GET['cID'] . "'");

        $module_directory = DIR_FS_CATALOG_MODULES . 'payment/';
        $ship_module_directory = DIR_FS_CATALOG_MODULES . 'shipping/';
        $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
        $directory_array = array();
        if ($dir = @dir($module_directory)) {
        while ($file = $dir->read()) {
          if (!is_dir($module_directory . $file)) {
            if (substr($file, strrpos($file, '.')) == $file_extension) {
              $directory_array[] = $file; // array of all the payment modules present in includes/modules/payment
            }
          }
        }
        sort($directory_array);
        $dir->close();
        }

        $ship_directory_array = array();
        if ($dir = @dir($ship_module_directory)) {
          while ($file = $dir->read()) {
            if (!is_dir($ship_module_directory . $file)) {
              if (substr($file, strrpos($file, '.')) == $file_extension) {
                $ship_directory_array[] = $file; // array of all shipping modules present in includes/modules/shipping
              }
            }
          }
          sort($ship_directory_array);
          $dir->close();
        }
        $customers = tep_db_fetch_array($customers_query);
        $cInfo = new objectInfo($customers);
        $customers_voucher_amount_query = tep_db_query("select * from  ".TABLE_COUPON_GV_CUSTOMER." where customer_id = ".(int)$_GET['cID']."");     
        $customers_voucher_amount_res =  tep_db_fetch_array($customers_voucher_amount_query);
        $i_customers_voucher_amount = $customers_voucher_amount_res['amount']; 
        $cInfo->customers_voucher_amount = number_format($i_customers_voucher_amount,2);
        // RCI call for action default
        echo $cre_RCI->get('customers', 'action', false);
    } // end switch
  }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<script type="text/javascript" src="includes/prototype.js"></script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<script language="javascript" src="includes/general.js"></script>
<?php
if ($action == 'edit' || $action == 'update') {
  ?>
  <script language="javascript"><!--
  function check_form() {
    var error = 0;
    var error_message = "<?php echo JS_ERROR; ?>";
    var customers_firstname = document.customers.customers_firstname.value;
    var customers_lastname = document.customers.customers_lastname.value;
    <?php if (ACCOUNT_COMPANY == 'true') echo 'var entry_company = document.customers.entry_company.value;' . "\n"; ?>
    <?php if (ACCOUNT_DOB == 'true') echo 'var customers_dob = document.customers.customers_dob.value;' . "\n"; ?>
    var customers_email_address = document.customers.customers_email_address.value;
    var entry_street_address = document.customers.entry_street_address.value;
    var entry_postcode = document.customers.entry_postcode.value;
    var entry_city = document.customers.entry_city.value;
    var customers_telephone = document.customers.customers_telephone.value;
    <?php 
    if (ACCOUNT_GENDER == 'true') { ?>
      if (document.customers.customers_gender[0].checked || document.customers.customers_gender[1].checked) {
      } else {
        error_message = error_message + "<?php echo JS_GENDER; ?>";
        error = 1;
      }
      <?php 
    }
    ?>
    if (customers_firstname == "" || customers_firstname.length < <?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo JS_FIRST_NAME; ?>";
      error = 1;
    }
    if (customers_lastname == "" || customers_lastname.length < <?php echo ENTRY_LAST_NAME_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo JS_LAST_NAME; ?>";
      error = 1;
    }
    <?php
    if (ACCOUNT_DOB == 'true') { 
      ?>
      if (customers_dob == "" || customers_dob.length < <?php echo ENTRY_DOB_MIN_LENGTH; ?>) {
        error_message = error_message + "<?php echo JS_DOB; ?>";
        error = 1;
      }
      <?php 
    } 
    ?>
    if (customers_email_address == "" || customers_email_address.length < <?php echo ENTRY_EMAIL_ADDRESS_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo JS_EMAIL_ADDRESS; ?>";
      error = 1;
    }
    if (entry_street_address == "" || entry_street_address.length < <?php echo ENTRY_STREET_ADDRESS_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo JS_ADDRESS; ?>";
      error = 1;
    }
    if (entry_postcode == "" || entry_postcode.length < <?php echo ENTRY_POSTCODE_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo JS_POST_CODE; ?>";
      error = 1;
    }
    if (entry_city == "" || entry_city.length < <?php echo ENTRY_CITY_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo JS_CITY; ?>";
      error = 1;
    }
    <?php
    if (ACCOUNT_STATE == 'true') {
      ?>
      if (document.customers.elements['entry_state'].type != "hidden") {
        if (document.customers.entry_state.value == '' || document.customers.entry_state.value.length < <?php echo ENTRY_STATE_MIN_LENGTH; ?> ) {
           error_message = error_message + "<?php echo JS_STATE; ?>";
           error = 1;
        }
      }
      <?php
    }
    ?>
    if (document.customers.elements['entry_country_id'].type != "hidden") {
      if (document.customers.entry_country_id.value == 0) {
        error_message = error_message + "<?php echo JS_COUNTRY; ?>";
        error = 1;
      }
    }
    if (customers_telephone == "" || customers_telephone.length < <?php echo ENTRY_TELEPHONE_MIN_LENGTH; ?>) {
      error_message = error_message + "<?php echo JS_TELEPHONE; ?>";
      error = 1;
    }
    if (error == 1) {
      alert(error_message);
      return false;
    } else {
      return true;
    }
  }
  //--></script>
  <?php
}
?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<div id="body">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body-table">
  <tr>
  <!-- left_navigation //-->
  <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
  <!-- left_navigation_eof //-->
        <!-- body_text //-->
    <td valign="top" class="page-container"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <?php
      if ($action == 'edit' || $action == 'update') {
        $newsletter_array = array(array('id' => '0', 'text' => ENTRY_NEWSLETTER_NO),
                                  array('id' => '1', 'text' => ENTRY_NEWSLETTER_YES));
  
        $emailvalidated_array = array(array('id' => '0', 'text' => ENTRY_EMAILVALIDATE_NO),
                                      array('id' => '1', 'text' => ENTRY_EMAILVALIDATE_YES));               
        ?>
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '2'); ?></td>
        </tr>
        <tr>
          <?php 
          echo tep_draw_form('customers', FILENAME_CUSTOMERS, tep_get_all_get_params(array('action')) . 'action=update', 'post', 'onSubmit="return check_form();"') . tep_draw_hidden_field('default_address_id', (isset($cInfo->customers_default_address_id) ? $cInfo->customers_default_address_id : '')); 
          ?>
          <td class="formAreaTitle"><?php echo CATEGORY_PERSONAL; ?></td>
        </tr>
        <tr>
          <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
            <?php
            if (ACCOUNT_GENDER == 'true') {
              ?>
              <tr>
                <td class="main"><?php echo ENTRY_GENDER; ?></td>
                <td class="main">
                  <?php
                  if ($error == true) {
                  if (isset($entry_gender_error) && $entry_gender_error == true) {
                    echo tep_draw_radio_field('customers_gender', 'm', false, $cInfo->customers_gender) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('customers_gender', 'f', false, $cInfo->customers_gender) . '&nbsp;&nbsp;' . FEMALE . '&nbsp;' . ENTRY_GENDER_ERROR;
                  } else {
                    echo ($cInfo->customers_gender == 'm') ? MALE : FEMALE;
                    echo tep_draw_hidden_field('customers_gender');
                  }
                } else {
                  echo tep_draw_radio_field('customers_gender', 'm', false, $cInfo->customers_gender) . '&nbsp;&nbsp;' . MALE . '&nbsp;&nbsp;' . tep_draw_radio_field('customers_gender', 'f', false, $cInfo->customers_gender) . '&nbsp;&nbsp;' . FEMALE;
                }
                ?>
                </td>
              </tr>
              <?php
            }
            ?>
            <tr>
              <td class="main"><?php echo ENTRY_FIRST_NAME; ?></td>
              <td class="main">
                <?php
                if ($error == true) {
                  if (isset($entry_firstname_error) && $entry_firstname_error == true) {
                    echo tep_draw_input_field('customers_firstname', $cInfo->customers_firstname, 'maxlength="32"') . '&nbsp;' . ENTRY_FIRST_NAME_ERROR;
                  } else {
                    echo $cInfo->customers_firstname . tep_draw_hidden_field('customers_firstname');
                  }
                } else {
                  echo tep_draw_input_field('customers_firstname', $cInfo->customers_firstname, 'maxlength="32"', true);
                }
                ?>
              </td>
            </tr>
            <tr>
              <td class="main"><?php echo ENTRY_LAST_NAME; ?></td>
              <td class="main">
                <?php
                if ($error == true) {
                  if (isset($entry_lastname_error) && $entry_lastname_error == true) {
                    echo tep_draw_input_field('customers_lastname', $cInfo->customers_lastname, 'maxlength="32"') . '&nbsp;' . ENTRY_LAST_NAME_ERROR;
                  } else {
                    echo $cInfo->customers_lastname . tep_draw_hidden_field('customers_lastname');
                  }
                } else {
                  echo tep_draw_input_field('customers_lastname', $cInfo->customers_lastname, 'maxlength="32"', true);
                }
                ?>
              </td>
            </tr>
            <?php
            if (ACCOUNT_DOB == 'true') {
              ?>
              <tr>
                <td class="main"><?php echo ENTRY_DATE_OF_BIRTH; ?></td>
                <td class="main">
                  <?php
                  if ($error == true) {
                    if (isset($entry_date_of_birth_error) && $entry_date_of_birth_error == true) {
                      echo tep_draw_input_field('customers_dob', tep_date_short($cInfo->customers_dob), 'maxlength="10"') . '&nbsp;' . ENTRY_DATE_OF_BIRTH_ERROR;
                    } else {
                      echo $cInfo->customers_dob . tep_draw_hidden_field('customers_dob');
                    }
                  } else {
                    echo tep_draw_input_field('customers_dob', tep_date_short($cInfo->customers_dob), 'maxlength="10"', true);
                  }
                  ?>
                </td>
              </tr>
              <?php
            }
            ?>
            <tr>
              <td class="main"><?php echo ENTRY_EMAIL_ADDRESS; ?></td>
              <td class="main">
                <?php
                if ($error == true) {
                  if (isset($entry_email_address_error) && $entry_email_address_error == true) {
                    echo tep_draw_input_field('customers_email_address', $cInfo->customers_email_address, 'maxlength="96"') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_ERROR;
                  } elseif ($entry_email_address_check_error == true) {
                    echo tep_draw_input_field('customers_email_address', $cInfo->customers_email_address, 'maxlength="96"') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_CHECK_ERROR;
                  } elseif ($entry_email_address_exists == true) {
                    echo tep_draw_input_field('customers_email_address', $cInfo->customers_email_address, 'maxlength="96"') . '&nbsp;' . ENTRY_EMAIL_ADDRESS_ERROR_EXISTS;
                  } else {
                    echo $customers_email_address . tep_draw_hidden_field('customers_email_address');
                  }
                } else {
                  echo tep_draw_input_field('customers_email_address', $cInfo->customers_email_address, 'maxlength="96"', true);
                }
                ?>
              </td>
            </tr>
          </table></td>
        </tr>
        <?php
        if (ACCOUNT_COMPANY == 'true') {
          ?>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="formAreaTitle"><?php echo CATEGORY_COMPANY; ?></td>
          </tr>
          <tr>
            <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
              <tr>
                <td class="main"><?php echo ENTRY_COMPANY; ?></td>
                <td class="main">
                  <?php
                  if ($error == true) {
                    if (isset($entry_company_error) && $entry_company_error == true) {
                      echo tep_draw_input_field('entry_company', $cInfo->entry_company, 'maxlength="32"') . '&nbsp;' . ENTRY_COMPANY_ERROR;
                    } else {
                      echo $cInfo->entry_company . tep_draw_hidden_field('entry_company');
                    }
                  } else {
                    echo tep_draw_input_field('entry_company', $cInfo->entry_company, 'maxlength="32"');
                  }
                  ?>
                </td>
              </tr>
            </table></td>
          </tr>
          <?php
        }
        ?>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
        </tr>
        <tr>
          <td class="formAreaTitle"><?php echo CATEGORY_ADDRESS; ?></td>
        </tr>
        <tr>
          <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
            <tr>
              <td class="main"><?php echo ENTRY_STREET_ADDRESS; ?></td>
              <td class="main">
                <?php
                if ($error == true) {
                  if (isset($entry_street_address_error) && $entry_street_address_error == true) {
                    echo tep_draw_input_field('entry_street_address', $cInfo->entry_street_address, 'maxlength="64"') . '&nbsp;' . ENTRY_STREET_ADDRESS_ERROR;
                  } else {
                    echo $cInfo->entry_street_address . tep_draw_hidden_field('entry_street_address');
                  }
                } else {
                  echo tep_draw_input_field('entry_street_address', $cInfo->entry_street_address, 'maxlength="64"', true);
                }
                ?>
              </td>
            </tr>
            <?php
            if (ACCOUNT_SUBURB == 'true') {
              ?>
              <tr>
                <td class="main"><?php echo ENTRY_SUBURB; ?></td>
                <td class="main">
                  <?php
                  if ($error == true) {
                    if (isset($entry_suburb_error) && $entry_suburb_error == true) {
                      echo tep_draw_input_field('suburb', $cInfo->entry_suburb, 'maxlength="32"') . '&nbsp;' . ENTRY_SUBURB_ERROR;
                    } else {
                      echo $cInfo->entry_suburb . tep_draw_hidden_field('entry_suburb');
                    }
                  } else {
                    echo tep_draw_input_field('entry_suburb', $cInfo->entry_suburb, 'maxlength="32"');
                  }
                  ?>
                </td>
              </tr>
              <?php
            }
            ?>
            <tr>
              <td class="main"><?php echo ENTRY_POST_CODE; ?></td>
              <td class="main">
                <?php
                if ($error == true) {
                  if (isset($entry_post_code_error) && $entry_post_code_error == true) {
                    echo tep_draw_input_field('entry_postcode', $cInfo->entry_postcode, 'maxlength="10"') . '&nbsp;' . ENTRY_POST_CODE_ERROR;
                  } else {
                    echo $cInfo->entry_postcode . tep_draw_hidden_field('entry_postcode');
                  }
                } else {
                  echo tep_draw_input_field('entry_postcode', $cInfo->entry_postcode, 'maxlength="10"', true);
                }
                ?>
              </td>
            </tr>
            <tr>
              <td class="main"><?php echo ENTRY_CITY; ?></td>
              <td class="main">
                <?php 
                if ($error == true) {
                  if (isset($entry_city_error) && $entry_city_error == true) {
                    echo tep_draw_input_field('entry_city', $cInfo->entry_city, 'maxlength="32"') . '&nbsp;' . ENTRY_CITY_ERROR;
                  } else {
                    echo $cInfo->entry_city . tep_draw_hidden_field('entry_city');
                  }
                } else {
                  echo tep_draw_input_field('entry_city', $cInfo->entry_city, 'maxlength="32"', true);
                }
                ?>
              </td>
            </tr>
            <?php
            if (ACCOUNT_STATE == 'true') {
              ?>
              <tr>
                <td class="main"><?php echo ENTRY_STATE; ?></td>
                <td class="main">
                  <?php
                  $entry_state = tep_get_zone_name($cInfo->entry_country_id, (isset($cInfo->entry_zone_id) ? $cInfo->entry_zone_id : 0), $cInfo->entry_state);
                  if ($error == true) {
                    if ($entry_state_error == true) {
                      if ($entry_state_has_zones == true) {
                        $zones_array = array();
                        $zones_query = tep_db_query("select zone_name from " . TABLE_ZONES . " where zone_country_id = '" . tep_db_input($cInfo->entry_country_id) . "' order by zone_name");
                        while ($zones_values = tep_db_fetch_array($zones_query)) {
                          $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
                        }
                        echo tep_draw_pull_down_menu('entry_state', $zones_array) . '&nbsp;' . ENTRY_STATE_ERROR;
                      } else {
                        echo tep_draw_input_field('entry_state', tep_get_zone_name($cInfo->entry_country_id, $cInfo->entry_zone_id, $cInfo->entry_state)) . '&nbsp;' . ENTRY_STATE_ERROR;
                      }
                    } else {
                      echo $entry_state . tep_draw_hidden_field('entry_zone_id') . tep_draw_hidden_field('entry_state');
                    }
                  } else {
                    echo tep_draw_input_field('entry_state', tep_get_zone_name($cInfo->entry_country_id, $cInfo->entry_zone_id, $cInfo->entry_state));
                  }
                  ?>
                </td>
              </tr>
              <?php
            }
            ?>
            <tr>
              <td class="main"><?php echo ENTRY_COUNTRY; ?></td>
              <td class="main">
                <?php
                if ($error == true) {
                  if ($entry_country_error == true) {
                    echo tep_draw_pull_down_menu('entry_country_id', tep_get_countries(), $cInfo->entry_country_id) . '&nbsp;' . ENTRY_COUNTRY_ERROR;
                  } else {
                    echo tep_get_country_name($cInfo->entry_country_id) . tep_draw_hidden_field('entry_country_id');
                  }
                } else {
                  echo tep_draw_pull_down_menu('entry_country_id', tep_get_countries(), $cInfo->entry_country_id);
                }
                ?>
              </td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
        </tr>
        <tr>
          <td class="formAreaTitle"><?php echo CATEGORY_CONTACT; ?></td>
        </tr>
        <tr>
          <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
            <tr>
              <td class="main"><?php echo ENTRY_TELEPHONE_NUMBER; ?></td>
              <td class="main">
                <?php
                if ($error == true) {
                  if ($entry_telephone_error == true) {
                    echo tep_draw_input_field('customers_telephone', $cInfo->customers_telephone, 'maxlength="32"') . '&nbsp;' . ENTRY_TELEPHONE_NUMBER_ERROR;
                  } else {
                    echo $cInfo->customers_telephone . tep_draw_hidden_field('customers_telephone');
                  }
                } else {
                  echo tep_draw_input_field('customers_telephone', $cInfo->customers_telephone, 'maxlength="32"', true);
                }
                ?>
              </td>
            </tr>
            <tr>
              <td class="main"><?php echo ENTRY_FAX_NUMBER; ?></td>
              <td class="main">
                <?php
                if ($processed == true) {
                  echo $cInfo->customers_fax . tep_draw_hidden_field('customers_fax');
                } else {
                  echo tep_draw_input_field('customers_fax', $cInfo->customers_fax, 'maxlength="32"');
                }
                ?>
              </td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
        </tr>
        <tr>
          <td class="formAreaTitle"><?php echo CATEGORY_OPTIONS; ?></td>
        </tr>
        <tr>
          <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
            <tr>
              <td class="main"><?php echo ENTRY_NEWSLETTER; ?></td>
              <td class="main">
                <?php
                if ($processed == true) {
                  if ($cInfo->customers_newsletter == '1') {
                    echo ENTRY_NEWSLETTER_YES;
                  } else {
                    echo ENTRY_NEWSLETTER_NO;
                  }
                  echo tep_draw_hidden_field('customers_newsletter');
                } else {
                  echo tep_draw_pull_down_menu('customers_newsletter', $newsletter_array, (($cInfo->customers_newsletter == '1') ? '1' : '0'));
                }
                ?>
              </td>
            </tr>
            <tr>
              <td class="main"><?php echo ENTRY_CUSTOMERS_EMAIL_VALIDATED;?>  </td>
                <td class="main">
                <?php
                  if(ACCOUNT_EMAIL_CONFIRMATION=='true')  { 
                      echo tep_draw_pull_down_menu('customers_emailvalidated', $emailvalidated_array, (isset($cInfo->customers_validation) ? $cInfo->customers_validation : ''));
                  } else {
                      echo  TEXT_EMAIL_VALIDATE_FEATURE . tep_draw_hidden_field('customers_emailvalidated', $cInfo->customers_validation);
                  }
                  ?>
              </td>
            </tr> 
          </table></td>
        </tr>
        <?php
        // RCI to allow for additional customer information to be presented 
        $returned_rci = $cre_RCI->get('customers', 'dataextension');
        echo $returned_rci;
        ?>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
        </tr>
        <tr>
          <td class="formAreaTitle"><?php echo SYSTEM_INFORMATION; ?></td>
        </tr>
        <tr>
          <td class="formArea"><table border="0" cellspacing="2" cellpadding="2">
            <tr>
              <td class="main"><?php echo ENTRY_CUSTOMERS_VOUCHER_AMOUNT; ?></td>
              <td class="main">
                <?php
                if ($processed == true) {
                  echo $cInfo->customers_voucher_amount . tep_draw_hidden_field('customers_voucher_amount');
                } else {
                  echo "<input type = 'text' name = 'customers_voucher_amount' value = ". $cInfo->customers_voucher_amount ." size='22'>";
                }
                ?>
              </td>
            </tr>
            <tr>
              <td class="main"><?php echo ENTRY_CUSTOMERS_TEMPLATE_NAME; ?></td>
              <?php
              if ($processed != true) {
                $existing_templates_query = tep_db_query("select * from ".TABLE_TEMPLATE." order by template_id");    
                $existing_templates_array[] = array("id" => '', "text" => "&#160; NONE &#160;");
                while ($existing_templates =  tep_db_fetch_array($existing_templates_query)) {
                  $existing_templates_array[] = array("id" => $existing_templates['template_name'], "text" => "&#160;".$existing_templates['template_name']."&#160;");      
                }
              } // end if ($processed != true )
              ?>
              <td class="main">
                <?php 
                if ($processed == true) {
                  echo $cInfo->customers_selected_template . tep_draw_hidden_field('customers_selected_template');
                } else {
                  if(tep_not_null($cInfo->customers_selected_template )) {
                    echo tep_draw_pull_down_menu('customers_selected_template', $existing_templates_array, $cInfo->customers_selected_template);
                  } else {
                    echo tep_draw_pull_down_menu('customers_selected_template', $existing_templates_array, '');
                  }
                }      
                ?>
              </td>
            </tr>
          </table></td>
        </tr>
        <?php
        // RCI for inserting info inside the form at bottom
        echo $cre_RCI->get('customers', 'bottominsideform');
        ?>
        <tr>
          <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
        </tr>
        <tr>
          <td align="right" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('action'))) .'">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_update.gif', IMAGE_UPDATE); ?></td>
        </tr></form>
        <?php
        // RCI for inserting info outside the form at bottom
        echo $cre_RCI->get('customers', 'bottomoutsideform');        
      } else {
        ?>
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr><?php 
              echo tep_draw_form('search', FILENAME_CUSTOMERS, tep_get_all_get_params(array('search')), 'post'); 
              if (isset($_GET[tep_session_name()])) {
                echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);
              }          
            ?>
              <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
              <td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ' ' . tep_draw_input_field('search'); ?></td>
            </form></tr>
          </table></td>
        </tr>
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="data-table">
                <tr class="dataTableHeadingRow">
                  <?php
                  $listing = isset($_GET['listing']) ? $_GET['listing'] : '';
                  switch ($listing) {
                    case "id-asc":
                      $order = "c.customers_id";
                      break;
                    case "firstname":
                      $order = "c.customers_firstname";
                      break;
                    case "firstname-desc":
                      $order = "c.customers_firstname DESC";
                      break;
                    case "company":
                      $order = "a.entry_company, c.customers_lastname";
                      break;
                    case "company-desc":
                      $order = "a.entry_company DESC,c .customers_lastname DESC";
                      break;
                    case "lastname":
                      $order = "c.customers_lastname, c.customers_firstname";
                      break;
                    case "lastname-desc":
                      $order = "c.customers_lastname DESC, c.customers_firstname";
                      break;
                    default:
                      $order = "c.customers_id DESC";
                  }
                  if (isset($_GET[tep_session_name()])) {
                    $oscid = '&' . tep_session_name() . '=' . $_GET[tep_session_name()];
                  } else {
                    $oscid = '';
                  }
                  ?>
                  <td class="dataTableHeadingContent" valign="top">
                    <?php echo ENTRY_COMPANY; ?>
                    <a href="<?php echo tep_href_link(FILENAME_CUSTOMERS,'listing=company'); ?>"><img src="images/arrow_up.gif" border="0"></a>&nbsp;
                    <a href="<?php echo tep_href_link(FILENAME_CUSTOMERS,'listing=company-desc'); ?>"><img src="images/arrow_down.gif" border="0"></a>
                  </td>
                  <td class="dataTableHeadingContent" valign="top">
                    <?php echo TABLE_HEADING_LASTNAME; ?>
                    <a href="<?php echo tep_href_link(FILENAME_CUSTOMERS,'listing=lastname'); ?>"><img src="images/arrow_up.gif" border="0"></a>&nbsp;
                    <a href="<?php echo tep_href_link(FILENAME_CUSTOMERS,'listing=lastname-desc'); ?>"><img src="images/arrow_down.gif" border="0"></a>
                  </td>
                  <td class="dataTableHeadingContent" valign="top">
                    <?php echo TABLE_HEADING_FIRSTNAME; ?>
                    <a href="<?php echo tep_href_link(FILENAME_CUSTOMERS,'listing=firstname'); ?>"><img src="images/arrow_up.gif" border="0"></a>&nbsp;
                    <a href="<?php echo tep_href_link(FILENAME_CUSTOMERS,'listing=firstname-desc'); ?>"><img src="images/arrow_down.gif" border="0"></a>
                  </td>
                  <td class="dataTableHeadingContent" align="right" valign="top">
                    <?php echo TABLE_HEADING_ACCOUNT_CREATED; ?>
                    <a href="<?php echo tep_href_link(FILENAME_CUSTOMERS,'listing=id-asc'); ?>"><img src="images/arrow_up.gif" border="0"></a>&nbsp;
                    <a href="<?php echo tep_href_link(FILENAME_CUSTOMERS,'listing=id-desc'); ?>"><img src="images/arrow_down.gif" border="0"></a>
                  </td>
                  <td class="dataTableHeadingContent" align="right" valign="top">&nbsp;</td>
                </tr>
                <?php
                $search = '';
                $keywords = isset($_GET['search']) ? tep_db_input(tep_db_prepare_input($_GET['search'])) : '';
                if (isset($_POST['search'])) {
                  $keywords =  tep_db_input(tep_db_prepare_input($_POST['search']));
                  // this is added to ba able to pass the value along to the next statement
                  $_GET['search'] = $keywords;
                }
                if ($keywords != '') {
                  // $search = "where c.customers_lastname like '%" . $keywords . "%' or c.customers_firstname like '%" . $keywords . "%' or c.customers_email_address like '%" . $keywords . "%'";
                  $search = "where c.customers_lastname like '%" . $keywords . "%' or c.customers_firstname like '%" . $keywords . "%' or lower(c.customers_email_address) like '%" . $keywords . "%'  or (a.entry_company) like '%" . $keywords . "%' ";
                }
                $customers_query_raw = "select c.customers_id, c.customers_lastname, c.customers_firstname, c.customers_email_address, a.entry_country_id, a.entry_company from " . TABLE_CUSTOMERS . " c left join " . TABLE_ADDRESS_BOOK . " a on c.customers_id = a.customers_id and c.customers_default_address_id = a.address_book_id " . $search . " order by $order";
                $info = array();
                $customers = array();
                $customers_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $customers_query_raw, $customers_query_numrows);
                $customers_query = tep_db_query($customers_query_raw);
                while ($customers = tep_db_fetch_array($customers_query)) {
                  $info_query = tep_db_query("select customers_info_date_account_created as date_account_created, customers_info_date_account_last_modified as date_account_last_modified, customers_info_date_of_last_logon as date_last_logon, customers_info_number_of_logons as number_of_logons from " . TABLE_CUSTOMERS_INFO . " where customers_info_id = '" . $customers['customers_id'] . "'");
                  $info = tep_db_fetch_array($info_query);
                  if ((!isset($_GET['cID']) || (isset($_GET['cID']) && ($_GET['cID'] == $customers['customers_id']))) && !isset($cInfo)) {
                    $country_query = tep_db_query("select countries_name from " . TABLE_COUNTRIES . " where countries_id = '" . (int)$customers['entry_country_id'] . "'");
                    if ( !$country = tep_db_fetch_array($country_query) ) {
                      $country = array();
                    }
                    $reviews_query = tep_db_query("select count(*) as number_of_reviews from " . TABLE_REVIEWS . " where customers_id = '" . (int)$customers['customers_id'] . "'");
                    if ( !$reviews = tep_db_fetch_array($reviews_query) ) {
                      $reviews = array();
                    }
                    $customer_info = array_merge((array)$country, (array)$info, (array)$reviews);
                    $cInfo_array = array_merge((array)$customers, (array)$customer_info);
                    $cInfo = new objectInfo($cInfo_array);
                  }
                  if (isset($cInfo) && is_object($cInfo) && ($customers['customers_id'] == $cInfo->customers_id)) {
                    echo '<tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=edit') . '\'">' . "\n";
                  } else {
                    echo '<tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID')) . 'cID=' . $customers['customers_id']) . '\'">' . "\n";
                  }
                  ?>
                  <td class="dataTableContent"><?php
                    if (strlen($customers['entry_company']) > 16 ) {
                      print ("<acronym title=\"".$customers['entry_company']."\">".substr($customers['entry_company'], 0, 16)."&#160;</acronym>");
                    } else {
                      echo $customers['entry_company']; 
                    } ?>
                  </td>
                  <td class="dataTableContent"><?php
                    if (strlen($customers['customers_lastname']) > 15 ) {
                      print ("<acronym title=\"".$customers['customers_lastname']."\">".substr($customers['customers_lastname'], 0, 15)."&#160;</acronym>");
                    } else {
                      echo $customers['customers_lastname']; 
                    } ?>
                  </td>
                  <td class="dataTableContent"><?php
                    if (strlen($customers['customers_firstname']) > 15 ) {
                      print ("<acronym title=\"".$customers['customers_firstname']."\">".substr($customers['customers_firstname'], 0, 15)."&#160;</acronym>");
                    } else {
                      echo $customers['customers_firstname']; 
                    } ?>
                  </td>
                  <td class="dataTableContent" align="right"><?php echo tep_date_short($info['date_account_created']); ?></td>
                  <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($customers['customers_id'] == $cInfo->customers_id)) { echo tep_image(DIR_WS_IMAGES . 'arrow_right_blue.png', ''); } else { echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID')) . 'cID=' . $customers['customers_id']) . '">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
                </tr>
                <?php
                }
                ?>
                </table>
                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="data-table-foot">
                <tr>
                  <td colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                      <td class="smallText" valign="top"><?php echo $customers_split->display_count($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></td>
                      <td class="smallText" align="right"><?php echo $customers_split->display_links($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))); ?></td>
                    </tr>
                    <tr>
                      <td colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                        <tr>              
                          <?php
                          // RCI for inserting code in the bottom of the listing
                          echo $cre_RCI->get('customers', 'listingbottom');
                          // RCI code eof
                          ?>
                        </tr>
                      </table></td>
                    </tr>                    
                    <?php
                    if ((isset($_POST['search']) && tep_not_null($_POST['search'])) || (isset($_GET['search']) && tep_not_null($_GET['search']))) {
                      ?>
                      <tr>
                        <td align="right" colspan="2"><?php echo '<a href="' . tep_href_link(FILENAME_CUSTOMERS) . '">' . tep_image_button('button_reset.gif', IMAGE_RESET) . '</a>'; ?></td>
                      </tr>
                      <?php
                    }
                    ?>
                  </table></td>
                </tr>              
              </table></td>
              <?php
              $heading = array();
              $contents = array();

              switch ($action) {
                case 'confirm':
                  $heading[] = array('text' => ''. tep_draw_separator('pixel_trans.gif', '11', '12') .'&nbsp;<br><b>' . TEXT_INFO_HEADING_DELETE_CUSTOMER . '</b>');
                  $contents = array('form' => tep_draw_form('customers', FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=deleteconfirm'));
                  $contents[] = array('text' => TEXT_DELETE_INTRO . '<br><br><b>' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname . '</b>');
                  if (isset($cInfo->number_of_reviews) && ($cInfo->number_of_reviews) > 0) $contents[] = array('text' => '<br>' . tep_draw_checkbox_field('delete_reviews', 'on', true) . ' ' . sprintf(TEXT_DELETE_REVIEWS, $cInfo->number_of_reviews));
                  $contents[] = array('align' => 'center', 'text' => '<br><a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>' . tep_image_submit('button_delete.gif', IMAGE_DELETE));
                  break;

                default:
                  if (isset($cInfo) && is_object($cInfo)) {
                    $heading[] = array('text' => '[' . $cInfo->customers_id . '] ' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname);
                    $contents[] = array('align' => 'center',
                                        'text' => '<br><a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=edit') . '">' . tep_image_button('button_page_edit.png', IMAGE_EDIT) . '</a>' .
                                                  '<a href="' . tep_href_link(FILENAME_CUSTOMERS, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=confirm') . '">' . tep_image_button('button_delete.gif', IMAGE_DELETE) . '</a><br>' .
                                                  '<a href="' . tep_href_link(FILENAME_ORDERS, 'cID=' . $cInfo->customers_id) . '">' . tep_image_button('button_orders.gif', IMAGE_ORDERS) . '</a>' . 
                                                  '<a href="' . tep_href_link(FILENAME_MAIL, 'selected_box=tools&customer=' . $cInfo->customers_email_address) . '">' . tep_image_button('button_email.gif', IMAGE_EMAIL) . '</a><br>'
                          );
                     $contents[] = array('align' => 'center',
                                         'text' => tep_draw_separator('pixel_trans.gif', '1', '15') . '<a href="' . tep_href_link(FILENAME_CREATE_ORDER, 'Customer=' . $cInfo->customers_id) . '">' . tep_image_button('button_create_order.gif', IMAGE_BUTTON_CREATE_ORDER) . '</a><br>'
                                        );

                    if (ACCOUNT_EMAIL_CONFIRMATION == 'true') {
                      $contents[] = array('align' => 'center',
                                          'text' => tep_draw_separator('pixel_trans.gif', '1', '15') . '<a href="' . tep_href_link(FILENAME_VALIDATE_NEW, tep_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->customers_id . '&action=confirm') . '">' . tep_image_button('button_resend_validation.gif', IMAGE_BUTTON_RESEND_VALIDATION) . '</a>');
                    }
                    //RCI customer sidebar buttons top
                    $returned_rci = $cre_RCI->get('customers', 'sidebarbuttons');
                    $contents[] = array('align' => 'center', 'text' => $returned_old_rci . $returned_rci);
                    $contents[] = array('text' => '<br>' . TEXT_DATE_ACCOUNT_CREATED . ' <b>' . tep_date_short($cInfo->date_account_created) . '</b>');
                    $contents[] = array('text' => '<br>' . TEXT_DATE_ACCOUNT_LAST_MODIFIED . ' <b>' . tep_date_short($cInfo->date_account_last_modified) . '</b>');
                    $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_LAST_LOGON . ' <b>'  . tep_date_short($cInfo->date_last_logon) . '</b>');
                    $contents[] = array('text' => '<br>' . TEXT_INFO_NUMBER_OF_LOGONS . ' <b>' . $cInfo->number_of_logons . '</b>');
                    $contents[] = array('text' => '<br>' . TEXT_INFO_COUNTRY . ' <b>' . (isset($cInfo->countries_name) ? $cInfo->countries_name : '') . '</b>');
                    //RCI customer sidebar buttons bottom
                    $returned_rci = $cre_RCI->get('customers', 'sidebarbottom');
                    $contents[] = array('text' => $returned_rci);
                  }
                  break;
              }
              if ( (tep_not_null($heading)) && (tep_not_null($contents)) ) {
                echo '<td width="25%" valign="top">' . "\n";
                $box = new box;
                echo $box->infoBox($heading, $contents);
                echo '</td>' . "\n";
              }
              ?>
            </tr>
          </table></td>
        </tr>
        <?php
      }
      // RCI for global and individual bottom
      echo $cre_RCI->get('customers', 'bottom'); 
      echo $cre_RCI->get('global', 'bottom');                                      
      ?>
    </table></td>
    <!-- body_text_eof //-->
  </tr>
</table>
</div>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>