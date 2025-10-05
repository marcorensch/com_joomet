<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomet
 *
 * @copyright   Copyright (C) 2025 NXD nx-designs, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Editor\Editor;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use NXD\Component\Joomet\Administrator\View\Edit\HtmlView;

/** @var HtmlView $this */

$user   = Factory::getApplication()->getIdentity();
$userId = $user->id;

$app = Factory::getApplication();
$fileContent = $this->fileContent;
$editorFieldName = 'file_content';

try {
	$editor = Editor::getInstance('codemirror');

	// Prüfen, ob der Editor erfolgreich geladen wurde
	if ($editor === null) {
		throw new RuntimeException('Editor nicht verfügbar.');
	}

	// Editor laden und anzeigen
	$editorHtml = $editor->display($editorFieldName, $fileContent, '100%', '400', '50', '15', false);
} catch (Throwable $e) {
	// Fallback to textarea if code mirror is not available
	$editorHtml = '<textarea name="' . htmlspecialchars($editorFieldName) . '" style="width:100%;height:400px;">' .
		htmlspecialchars($fileContent) . '</textarea>';

    Factory::getApplication()->enqueueMessage(Text::_("COM_JOOMET_CODE_MIRROR_NOT_AVAILABLE_MSG"), 'error');
}

?>

<h2><?php echo Text::sprintf("COM_JOOMET_EDIT_FILENAME", $this->file->label);?></h2>
<form action="<?php echo "" ?>" method="post" id="adminForm" name="adminForm">
    <div class="editor-container">
		<?php
            echo $editorHtml;
		?>
        <input type="hidden" name="file_path" value="<?php echo $this->file->path;?>" />
    </div>

    <!-- Optional: Submit-Button -->
    <div class="form-controls">
<!--        <button type="submit" class="btn btn-primary">Speichern</button>-->
    </div>

    <!-- Joomla Sicherheits-Token -->
    <input type="hidden" name="task" value="save" />
	<?php echo HTMLHelper::_('form.token'); ?>
</form>
