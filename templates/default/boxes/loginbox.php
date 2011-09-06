<?php
/*
  $Id: loginbox.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
require(DIR_WS_LANGUAGES . $language . '/'.FILENAME_LOGINBOX);
if ( ( ! strstr($PHP_SELF,'login.php')) && ( ! strstr($PHP_SELF,'create_account.php')) && ! isset($_SESSION['customer_id']) )  {
  if ( !isset($_SESSION['customer_id']) ) {
    ?>
    <!-- loginbox //--> 
    <tr>
      <td>
        <?php
        $info_box_contents = array();
        $info_box_contents[] = array('align' => 'left',
                                     'text'  => '<font color="' . $font_color . '">' . BOX_HEADING_LOGIN . '</font>');
        new $infobox_template_heading($info_box_contents, '', ((isset($column_location) && $column_location !='') ? $column_location : '') );
        $loginboxcontent = "
        <form name=\"login\" method=\"post\" action=\"" . tep_href_link(FILENAME_LOGIN, 'action=process', 'SSL') . "\">
          <table border=\"0\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">
            <tr>
              <td align=\"left\" class=\"infoboxContents\">
                " . BOX_LOGINBOX_EMAIL . "
              </td>
            </tr>
            <tr>
              <td align=\"left\" class=\"infoboxContents\">
                <input type=\"text\" name=\"email_address\" maxlength=\"96\" size=\"20\" value=\"\">
              </td>
            </tr>
            <tr>
              <td align=\"left\" class=\"infoboxContents\">
                " . BOX_LOGINBOX_PASSWORD . "
              </td>
            </tr>
            <tr>
              <td align=\"left\" class=\"infoboxContents\">
                <input type=\"password\" name=\"password\" maxlength=\"40\" size=\"20\" value=\"\">
              </td>
            </tr>
            <tr>
              <td align=\"center\">
                " . tep_draw_separator('pixel_trans.gif', '100%', '5') . "
              </td>
            </tr>
            <tr>
              <td class=\"infoboxContents\" align=\"center\">
                " . tep_template_image_submit('button_login.gif', IMAGE_BUTTON_LOGIN) . "
              </td>
            </tr>
          </table>
        </form>";
        $info_box_contents = array();
        $info_box_contents[] = array('align' => 'center',
                                     'text'  => $loginboxcontent
                                    );
        new $infobox_template($info_box_contents, true, true, ((isset($column_location) && $column_location !='') ? $column_location : '') );
        if (TEMPLATE_INCLUDE_FOOTER =='true'){
          $info_box_contents = array();
          $info_box_contents[] = array('align' => 'left',
                                       'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                      );
          new $infobox_template_footer($info_box_contents, ((isset($column_location) && $column_location !='') ? $column_location : '') );
        } 
        ?>
      </td>
    </tr>
    <!-- loginbox eof//-->
    <?php
  } else {
    // If you want to display anything when the user IS logged in, put it
    // in here...  Possibly a "You are logged in as :" box or something.
  }
} else {
  if ( isset($_SESSION['customer_id']) ) {
    $pwa_query = tep_db_query("select purchased_without_account from " . TABLE_CUSTOMERS . " where customers_id = '" . $_SESSION['customer_id'] . "'");
    $pwa = tep_db_fetch_array($pwa_query);
    if ($pwa['purchased_without_account'] == '0') {
      ?>
      <!-- loginbox //-->
      <tr>
        <td>
          <?php
          $info_box_contents = array();
          $info_box_contents[] = array('align' => 'left',
                                       'text'  => '<font color="' . $font_color . '">' . BOX_HEADING_LOGIN_BOX_MY_ACCOUNT . '</font>');
          new $infobox_template_heading($info_box_contents, '', ((isset($column_location) && $column_location !='') ? $column_location : '') );
          $info_box_contents = array();
          $info_box_contents[] = array('align' => 'left',
                                       'text'  => '<a href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">' . LOGIN_BOX_MY_ACCOUNT . '</a><br>' .
                                                  '<a href="' . tep_href_link(FILENAME_ACCOUNT_EDIT, '', 'SSL') . '">' . LOGIN_BOX_ACCOUNT_EDIT . '</a><br>' .
                                                  '<a href="' . tep_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL') . '">' . LOGIN_BOX_ACCOUNT_HISTORY . '</a><br>' .
                                                  '<a href="' . tep_href_link(FILENAME_ADDRESS_BOOK, '', 'SSL') . '">' . LOGIN_BOX_ADDRESS_BOOK . '</a><br>' .
                                                  '<a href="' . tep_href_link(FILENAME_ACCOUNT_NOTIFICATIONS, '', 'NONSSL') . '">' . LOGIN_BOX_PRODUCT_NOTIFICATIONS . '</a><br>' .
                                                  '<a href="' . tep_href_link(FILENAME_LOGOFF, '', 'NONSSL') . '">' . LOGIN_BOX_LOGOFF . '</a>'
                                      );
          new $infobox_template($info_box_contents, true, true, ((isset($column_location) && $column_location !='') ? $column_location : '') );
          if (TEMPLATE_INCLUDE_FOOTER =='true'){
            $info_box_contents = array();
            $info_box_contents[] = array('align' => 'left',
                                         'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                        );
            new $infobox_template_footer($info_box_contents, ((isset($column_location) && $column_location !='') ? $column_location : '') );
          }  
          ?>
        </td>
      </tr>
      <!-- loginbox eof//-->
      <?php
    }
  }
}
?>