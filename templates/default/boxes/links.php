<?php
   /*
  $Id: links.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// check for link categoris to determine if there is anything to display
$links = new box_links();

if (count($links->rows) > 0) {
  ?>
  <tr>
    <td>
      <!-- links -->            
      <?php
      $info_box_contents = array();
      $info_box_contents[] = array('text'  => '<font color="' . $font_color . '">' . BOX_HEADING_LINKS . '</font>');
      new $infobox_template_heading($info_box_contents, tep_href_link(FILENAME_LINKS, '', 'NONSSL'), ((isset($column_location) && $column_location !='') ? $column_location : '') );
      $informationString = '';
      foreach ($links->rows as $row) {
        $lPath_new = 'lPath=' . $row['link_categories_id'];
        $informationString .= '<a href="' . tep_href_link(FILENAME_LINKS, $lPath_new) . '">' . $row['link_categories_name'] . '</a><br>';
      }
      $info_box_contents = array();
      $info_box_contents[] = array('align' => 'left',
                                   'text'  => $informationString
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
  <?php
  }
?>
<!-- links eof//-->