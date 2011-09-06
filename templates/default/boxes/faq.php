<?php
/*
  $Id: faq.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
$faqdata = new box_faq();
if (count($faqdata->category_rows) > 0 || count($faqdata->faq_rows) > 0) {
  ?>
  <tr>
    <td>
      <?php
      $info_box_contents = array();
      $info_box_contents[] = array('text'  => '<font color="' . $font_color . '">' . BOX_HEADING_FAQ . '</font>');
      new $infobox_template_heading($info_box_contents, '', ((isset($column_location) && $column_location !='') ? $column_location : '') ); 
      $faq_string = '';
      foreach ($faqdata->category_rows as $faq_categories) {
        $id_string = 'cID=' . $faq_categories['categories_id'];
        $faq_string .= '<a href="' . tep_href_link(FILENAME_FAQ, $id_string) . '">' . $faq_categories['categories_name'] . '</a><br>';
      }
      foreach ($faqdata->faq_rows as $faq) {
        $id_string = 'fID=' . $faq['faq_id'];
        $faq_string .= '<a href="' . tep_href_link(FILENAME_FAQ, $id_string) . '">' . $faq['question'] . '</a><br>';
      }
      $info_box_contents = array();
      $info_box_contents[] = array('text'  => $faq_string);
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
  <?php
}
?>