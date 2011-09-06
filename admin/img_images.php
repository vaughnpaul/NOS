<?php
/**
 * Show a list of images in a long horizontal table.
 * @author $Author: Wei Zhuo $
 * @version $Id: images.php 27 2004-04-01 08:31:57Z Wei Zhuo $
 * @package ImageManager
 */

require('includes/application_top.php');
require_once('includes/javascript/image_manager/config.inc.php');
require_once('includes/javascript/image_manager/Classes/ImageManager.php');
require(DIR_WS_LANGUAGES . $language . '/imagemanager_manger.php');

//default path is /
$relative = '/';
$manager = new ImageManager($IMConfig);

//process any file uploads
$manager->processUploads();

$manager->deleteFiles();

$refreshDir = false;
//process any directory functions
if($manager->deleteDirs() || $manager->processNewDir())
    $refreshDir = true;

//check for any sub-directory request
//check that the requested sub-directory exists
//and valid
if(isset($_REQUEST['dir']))
{
    $path = rawurldecode($_REQUEST['dir']);
    if($manager->validRelativePath($path))
        $relative = $path;
}


$manager = new ImageManager($IMConfig);


//get the list of files and directories
$list = $manager->getFiles($relative);


/* ================= OUTPUT/DRAW FUNCTIONS ======================= */

/**
 * Draw the files in an table.
 */
function drawFiles($list, &$manager)
{
    global $relative;

    foreach($list as $entry => $file)
    { 
    ;?>
        <td><table width="100" cellpadding="0" cellspacing="0"><tr><td class="block">
        <a href="javascript:;" onclick="selectImage('<?php echo $file['relative'];;?>', '<?php echo $entry; ;?>', <?php echo $file['image'][0];;?>, <?php echo $file['image'][1]; ;?>);"title="<?php echo $entry; ;?> - <?php echo Files::formatSize($file['stat']['size']); ;?>"><img src="<?php echo $manager->getThumbnail($file['relative']); ;?>" border="0" alt="<?php echo $entry; ;?> - <?php echo Files::formatSize($file['stat']['size']); ;?>"/></a>
        </td></tr><tr><td class="edit">
            <?php echo $entry; ;?><br><a href="<?php echo tep_href_link('img_images.php', 'dir=' . $relative . '&delf=' . rawurlencode($file['relative']), 'SSL');?>" title="Trash" onclick="return confirmDeleteFile('<?php echo $entry; ;?>');"><img src="includes/javascript/image_manager/img/edit_trash.gif" border="0" height="15" width="15" alt="Trash"/></a><a href="javascript:;" title="Edit" onclick="editImage('<?php echo rawurlencode($file['relative']);;?>');"><img src="includes/javascript/image_manager/img/edit_pencil.gif" height="15" border="0" width="15" alt="<?php echo TEXT_IMANAGE_EDIT  ;?>"/></a>
        <?php if($file['image']){ echo $file['image'][0].'x'.$file['image'][1]; } else echo $entry;;?>
        </td></tr></table></td>
      <?php
    }//foreach
}//function drawFiles


/**
 * Draw the directory.
 */
function drawDirs($list, &$manager)
{
    global $relative;

    foreach($list as $path => $dir)
    { ;?>
        <td><table width="100" cellpadding="0" cellspacing="0"><tr><td class="block">
        <a href="<?php echo tep_href_link('img_images.php', 'dir=' . rawurlencode($path), 'SSL'); ?>" onclick="updateDir('<?php echo $path ;?>')" title="<?php echo $dir['entry']; ;?>"><img src="includes/javascript/image_manager/img/folder.gif" height="80" border="0" width="80" alt="<?php echo $dir['entry']; ;?>" /></a>
        </td></tr><tr>
        <td class="edit">
            <a href="<?php echo tep_href_link('img_images.php', 'dir=' . $relative . '&deld=' . rawurlencode($path), 'SSL'); ?>" title="Trash" onclick="return confirmDeleteDir('<?php echo $dir['entry']; ;?>', <?php echo $dir['count']; ;?>);"><img src="includes/javascript/image_manager/img/edit_trash.gif" border="0" height="15" width="15" alt="<?php echo TEXT_IMANAGE_TRASH ;?>"/></a>
            <?php echo $dir['entry']; ;?>
        </td>
        </tr></table></td>
      <?php
    } //foreach
}//function drawDirs


/**
 * No directories and no files.
 */
function drawNoResults()
{
;?>
<table width="100%">
  <tr>
    <td class="noResult"><?php echo TEXT_IMANAGE_NO_FOUND ;?></td>
  </tr>
</table>
<?php
}

/**
 * No directories and no files.
 */
function drawErrorBase(&$manager)
{
;?>
<table width="100%">
  <tr>
    <td class="error"><?php echo TEXT_IMANAGE_INVALID_BASE . $manager->config['base_dir']; ;?></td>
  </tr>
</table>
<?php
}

;?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
 <title>Image List</title>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <link href="includes/javascript/image_manager/assets/imagelist.css" rel="stylesheet" type="text/css" />
 <script type="text/javascript" src="includes/javascript/image_manager/assets/dialog.js"></script>
<script type="text/javascript">
/*<![CDATA[*/

    if(window.top)
      var I18N = window.top.I18N;

    function hideMessage()
    {
        var topDoc = window.top.document;
        var messages = topDoc.getElementById('messages');
        if(messages)
            messages.style.display = "none";
    }

    init = function()
    {
        hideMessage();
        var topDoc = window.top.document;

<?php
    //we need to refesh the drop directory list
    //save the current dir, delete all select options
    //add the new list, re-select the saved dir.
    if($refreshDir)
    {
        $dirs = $manager->getDirs();
;?>
        var selection = topDoc.getElementById('dirPath');
        var currentDir = selection.options[selection.selectedIndex].text;

        while(selection.length > 0)
        {    selection.remove(0); }

        selection.options[selection.length] = new Option("/","<?php echo rawurlencode('/'); ;?>");
        <?php foreach($dirs as $relative=>$fullpath) { ;?>
        selection.options[selection.length] = new Option("<?php echo $relative; ;?>","<?php echo rawurlencode($relative); ;?>");
        <?php } ;?>

        for(var i = 0; i < selection.length; i++)
        {
            var thisDir = selection.options[i].text;
            if(thisDir == currentDir)
            {
                selection.selectedIndex = i;
                break;
            }
        }
<?php } ;?>
    }

    function editImage(image)
    {
        var url = "img_editor.php?img="+image+getSessionID();
        Dialog(url, function(param)
        {
            if (!param) // user must have pressed Cancel
                return false;
            else
            {
                return true;
            }
        }, null);
    }

/*]]>*/
</script>
<script type="text/javascript" src="includes/javascript/image_manager/assets/images.js"></script>
</head>

<body>
<?php if ($manager->isValidBase() == false) { drawErrorBase($manager); }
    elseif(count($list[0]) > 0 || count($list[1]) > 0) { ;?>
<table>
    <tr>
    <?php drawDirs($list[0], $manager); ;?>
    <?php drawFiles($list[1], $manager); ;?>
    </tr>
</table>
<?php } else { drawNoResults(); } ;?>
</body>
</html>
