<?php
/*
  $Id: google_admin.php, $


  Copyright (c) 2007 Chainreactionworks.com
  Released under the GNU General Public License
*/

  require('includes/application_top.php');
include (DIR_WS_LANGUAGES . $language . '/' . FILENAME_DATA);
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
<script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=450,height=300%,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
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
    <td valign="top" class="page-container"><table border="0" width="100%" cellspacing="0" cellpadding="0" class="menuBoxHeading">
                         <tr>
                     <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                       <tr>
                         <td class="pageHeading"><?php echo HEADING_TITLE . TEXT_FEED_GOOGLE; ?></td>
                        </tr>
                        <tr>
                        <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                       </tr>
                     </table></td>
                   </tr>
<tr class="attributeBoxContent">

 <td>
 <?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;'  ; ?>
</td>
</tr>
    <tr>
        <td><?php
        // google
        $data_files ='';
             $data_query_check = tep_db_query("select data_files_id, data_name, data_files_service from " . TABLE_DATA_FILES . " where data_status = '1' and data_files_service = 'froogle' ");
        while ($data_check = tep_db_fetch_array($data_query_check)) {
               $data_files .= $data_check['data_files_service'];
     }


        $data_query = tep_db_query("select data_files_id, data_name, data_files_service from " . TABLE_DATA_FILES . " where data_status = '1' and data_files_service = 'google_bas' ");
        while ($data = tep_db_fetch_array($data_query)) {
              $file_type_array[] = array('id' => $data['data_files_id'], 'text' => $data['data_name']) ;
         }

    ?>

      <tr>
       <td>
        <?php
          //configure

          echo '&nbsp;' . TEXT_CONFIGURE . tep_draw_form('run', FILENAME_DATA_ADMIN, 'page=1', 'post', '');
          echo tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;' . TEXT_FEED_CONFIGURE_HELP1 ;
          echo tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;' . tep_image_submit('button_run.gif', TEXT_RUN_CONFIGURE) . '</form>';

          echo tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_DATA_HELP,'action=google_configure') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';

         //set category string
         echo '<br> &nbsp;' . TEXT_SET_CATEGORIES . tep_draw_form('run', FILENAME_GOOGLE_PRE1, 'action=run', 'post', '');
   echo tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;' . TEXT_SET_CATEGORIES_HELP1 ;
   echo tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;' . tep_image_submit('button_run.gif', IMAGE_SET_CATEGORIES) . '</form>';
         echo tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_DATA_HELP,'action=google_category') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';

         if (!isset($file_type_array)) {
           $file_type_array = array();
         }

         //run pre feed
         echo '<br> &nbsp;' . TEXT_FEED_PRE_FEED . tep_draw_form('run', FILENAME_GOOGLE_PRE, 'action=run', 'post', '');
         echo tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;' . TEXT_FEED_PRE_FEED_HELP1 ;
         echo tep_draw_separator('pixel_trans.gif', '5', '15') . tep_draw_pull_down_menu('feed_google', $file_type_array, $data['data_files_id']) ;
         echo tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;' . tep_image_submit('button_run.gif', TEXT_FEED_RUN_PRE_FEED) . '</form>';
         echo tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_DATA_HELP,'action=google_preprocess') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';

        // send to google
         echo '<br> &nbsp;' . TEXT_FEED_RUN . tep_draw_form('run_feed', FILENAME_GOOGLE, 'action=run', 'post', '');
         echo tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;' . TEXT_FEED_RUN_HELP1 ;
         echo tep_draw_separator('pixel_trans.gif', '5', '15') . tep_draw_pull_down_menu('feed_google', $file_type_array, $data['data_files_id']) ;
         echo tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;' . tep_image_submit('button_run.gif', IMAGE_FEED_RUN);
         echo tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_DATA_HELP,'action=google_send') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';

        ?>
        </td> </form>
      </tr>
      <tr>
              <td>
              <?php echo TEXT_FEED_HELP . '<br>';?>
              <?php echo TEXT_FEED_HELP_CONFIGURE . '<br>';?>
              <?php echo TEXT_FEED_HELP_SELECT . '<br>';?>
              <?php echo TEXT_FEED_HELP_PREFEED . '<br>';?>
              <?php echo TEXT_FEED_HELP_RUN . '<br>';?>
              <?php
        //      check to see if any feeds have froogle, if they do the echo message
               if (strstr($data_files, 'froogle')){
              echo '<b>' . TEXT_INFO_FEED_MISSING . '</b>';
              }
              ;?>
             </td>

      </tr>

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
