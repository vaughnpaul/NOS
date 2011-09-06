    <?php
    // RCI code start
    echo $cre_RCI->get('global', 'top');
    echo $cre_RCI->get('indexproducts', 'top');
    // RCI code eof
    // Get the category information
    $category_query = tep_db_query("select cd.categories_name, cd.categories_heading_title, cd.categories_description, c.categories_image from " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd where c.categories_id = '" . (int)$current_category_id . "' and cd.categories_id = '" . (int)$current_category_id . "' and cd.language_id = '" . $languages_id . "'");
    $category = tep_db_fetch_array($category_query);

    if(tep_not_null($category['categories_heading_title'])){
        $heading_text = $category['categories_heading_title'];
    } else if(tep_not_null($category['categories_name'])){
        $heading_text = $category['categories_name'];
    } else {
        $heading_text = HEADING_TITLE;
    }
    ?>
    <!-- bof content.index_products.tpl.php-->
    <table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB;?>">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td><h1 class="pageHeading"><?php echo $heading_text; ?></h1>
              <?php if ( (ALLOW_CATEGORY_DESCRIPTIONS == 'true') && (isset($category) && tep_not_null($category['categories_description'])) ) {  echo '<span class="category_desc">' . $category['categories_description'] . '</span>'; } ?>
            </td>
<?php
// optional Product List Filter
    if (PRODUCT_LIST_FILTER > 0) {
      if (isset($_GET['manufacturers_id'])) {
        $filterlist_sql = "select distinct c.categories_id as id, cd.categories_name as name
                           from " . TABLE_PRODUCTS . " p,
                                " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c,
                                " . TABLE_CATEGORIES . " c,
                                " . TABLE_CATEGORIES_DESCRIPTION . " cd
                           where p2c.categories_id = c.categories_id
                             and p.products_id = p2c.products_id
                             and cd.categories_id = c.categories_id
                             and cd.language_id = '" . (int)$languages_id . "'
                             and p.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "'
                             and p.products_status = '1'
                           order by cd.categories_name";
      } else {
        $filterlist_sql= "select distinct m.manufacturers_id as id, m.manufacturers_name as name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c, " . TABLE_MANUFACTURERS . " m where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' order by m.manufacturers_name";
      }
      $filterlist_query = tep_db_query($filterlist_sql);
      if (tep_db_num_rows($filterlist_query) > 1) {
        echo '            <td align="center" class="main">' . tep_draw_form('filter', FILENAME_DEFAULT, 'get') . TEXT_SHOW . '&nbsp;';
        if (isset($_GET['manufacturers_id'])) {
          echo tep_draw_hidden_field('manufacturers_id', $_GET['manufacturers_id']);
          $options = array(array('id' => '', 'text' => TEXT_ALL_CATEGORIES));
        } else {
          echo tep_draw_hidden_field('cPath', $cPath);
          $options = array(array('id' => '', 'text' => TEXT_ALL_MANUFACTURERS));
        }
        echo tep_draw_hidden_field('sort', (isset($_GET['sort']) ? $_GET['sort'] : ''));
        while ($filterlist = tep_db_fetch_array($filterlist_query)) {
          $options[] = array('id' => $filterlist['id'], 'text' => $filterlist['name']);
        }
        echo tep_draw_pull_down_menu('filter_id', $options, (isset($_GET['filter_id']) ? $_GET['filter_id'] : ''), 'onchange="this.form.submit()"');
        echo '</form></td>' . "\n";
      }
    }

// Get the right image for the top-right
    $image = DIR_WS_IMAGES . 'table_background_list.gif';
    if (isset($_GET['manufacturers_id'])) {
       $manufactures_query = tep_db_query("select manufacturers_name from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "'");
       $manufactures = tep_db_fetch_array($manufactures_query);
       $heading_text_box =  $manufactures['manufacturers_name'];
      $image = tep_db_query("select manufacturers_image from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "'");
      $image = tep_db_fetch_array($image);
      $image = $image['manufacturers_image'];
    } elseif ($current_category_id) {
      $image = tep_db_query("select categories_image from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$current_category_id . "'");
      $image = tep_db_fetch_array($image);
      $image = $image['categories_image'];
    }
?>
            <td align="right"><?php echo tep_image(DIR_WS_IMAGES . $image, HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <!--manufacture list in index product.php-->
<?php
// BOF: Lango Added for template index_products .tpl MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $heading_text);
}
// EOF: Lango Added for template MOD
?>
      <tr>
        <td>
        <?php 
  //Product Listing Fix - Begin
  //decide which product listing to use
   if (PRODUCT_LIST_CONTENT_LISTING == 'column'){
  $listing_method = FILENAME_PRODUCT_LISTING_COL;
  } else {
  $listing_method = FILENAME_PRODUCT_LISTING;
  }
  //Then show product listing   
  //include(DIR_WS_MODULES . $listing_method); 
         if ( file_exists(TEMPLATE_FS_CUSTOM_MODULES . $listing_method)) {
            require(TEMPLATE_FS_CUSTOM_MODULES . $listing_method);
        } else {
            require(DIR_WS_MODULES . $listing_method);
        }
  //Product Listing Fix - End
?>
        </td>
      </tr>
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template content indexproducts.tlpMOD
?>
    </table>
    <?php
    // RCI code start
    echo $cre_RCI->get('indexproducts', 'bottom');
    echo $cre_RCI->get('global', 'bottom');
    // RCI code eof
    ?>