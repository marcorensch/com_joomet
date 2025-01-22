<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomet
 *
 * @copyright   Copyright (C) 2025 NXD nx-designs, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Date\Date;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use NXD\Component\Joomet\Administrator\View\Uploaded\HtmlView;

/** @var HtmlView $this */

$user   = Factory::getApplication()->getIdentity();
$userId = $user->id;

HTMLHelper::_('bootstrap.modal');       // Lädt das Bootstrap-CSS

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->addInlineScript('
    function handleEditFileClicked(event, url){
        event.preventDefault();
        let form = document.getElementById("adminForm");
        form.action = url;

        // Optional: Task-Wert setzen, falls notwendig
        let taskInput = form.querySelector(\'[name="task"]\');
        if (taskInput) {
            taskInput.value = "uploaded.handleEditFileClicked" // Beispiel für dynamischen Task
        }

        // Formular absenden
        form.submit();
    }
    
	function handleDeleteFileClicked(original_name, name)
	{
		document.getElementById("file-to-del").value = original_name;
        document.getElementById("label-to-del").textContent = name;
	}
');

?>

<form action="<?php echo Route::_('index.php?option=com_joomet'); ?>" enctype="multipart/form-data"
      class="form-vertical" method="post" name="adminForm" id="adminForm">
    <table class="table align-middle">
        <thead>
        <tr>
            <td style="width: 1%" class="text-center">
				<?php echo HTMLHelper::_('grid.checkall'); ?>
            </td>
            <th scope="col">Name</th>
            <th scope="col">Uploaded</th>
            <th scope="col" class="text-center" style="width:20%">Actions</th>
        </tr>
        </thead>
        <tbody>
		<?php foreach ($this->items as $i => $file) : ?>
            <tr>
                <td class="text-center">
					<?php echo HTMLHelper::_('grid.id', $i, $file['original_name']); ?>
                </td>
                <th><?php echo $file['name']; ?></th>
                <td>
					<?php
					if ($file['uploaded'])
					{
						echo HTMLHelper::date(new Date($file['uploaded']), "DATE_FORMAT_LC5");
					}
					?>
                </td>
                <td>
                    <div class="hstack gap-3">
						<?php if (!$this->targetView): ?>
                            <div class="btn-group">
                                <a href="<?php echo Route::_('index.php?option=com_joomet&task=uploaded.handleCheckFileClicked&file=' . $file['original_name']); ?>"
                                   title="<?php echo Text::_("COM_JOOMET_CHECK_FILE_TXT"); ?>"
                                   class="btn btn-light">
                                    <i class="fa fa-file-circle-check"></i>
                                </a>
                                <a href="<?php echo Route::_('index.php?option=com_joomet&task=uploaded.handleTranslateFileClicked&id=' . $file['original_name']); ?>"
                                   title="<?php echo Text::_("COM_JOOMET_TRANSLATE_FILE_TXT"); ?>"
                                   class="btn btn-light">
                                    <i class="fa fa-language"></i>
                                </a>
                            </div>

                            <div class="vr"></div>
						<?php endif; ?>
                        <div class="btn-group">
                            <a target="_blank" href="<?php echo $file['url']; ?>"
                               title="<?php echo Text::_("COM_JOOMET_VIEW_FILE_TXT"); ?>"
                               class="btn btn-primary nxd-ext-btn">
                                <i class="fa fa-eye"></i>
                            </a>
                            <button
                                    title="<?php echo Text::_("COM_JOOMET_EDIT_FILE_TXT"); ?>"
                                    class="btn btn-primary"
                                    onclick="handleEditFileClicked(event, '<?php echo Route::_('index.php?option=com_joomet&task=uploaded.handleEditFileClicked&file=' . $file['original_name']); ?>')"
                            >
                                <i class="fa fa-pencil"></i>
                            </button>
                            <a href="<?php echo Route::_('index.php?option=com_joomet&task=uploaded.delete&id=' . $file['original_name']); ?>"
                               title="<?php echo Text::_("COM_JOOMET_DELETE_FILE_TXT"); ?>"
                               data-bs-toggle="modal"
                               data-bs-target="#deleteConfirmationModal"
                               onclick="handleDeleteFileClicked('<?php echo $file['original_name']; ?>', '<?php echo $file['name']; ?>')"
                               class="btn btn-danger">
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>
                    </div>
                </td>
            </tr>
		<?php endforeach; ?>
        </tbody>
    </table>
    <input type="hidden" name="boxchecked" value="0">
    <input type="hidden" name="task" value="">
	<?php echo HTMLHelper::_('form.token'); ?>
</form>

<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"
                    id="deleteConfirmationModalLabel"><?php echo Text::_('COM_JOOMET_DELETE_CONFIRM_TITLE'); ?></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
				<?php echo Text::_('COM_JOOMET_ARE_YOU_SURE_DELETE'); ?>

                <form id="deleteConfirmForm"
                      action="<?php echo Route::_('index.php?option=com_joomet&task=uploaded.handleDeleteFileClicked'); ?>"
                      method="post">
                    <input type="hidden" id="file-to-del" name="file"
                           value="<?php echo htmlspecialchars($file['original_name'], ENT_QUOTES, 'UTF-8'); ?>">
					<?php echo HTMLHelper::_('form.token'); ?>
                </form>

            </div>
            <div class="modal-footer">
                <a href="#" id="deleteConfirmButton" class="btn btn-danger" role="button"
                   onclick="document.getElementById('deleteConfirmForm').submit();">
					<?php echo Text::_('COM_JOOMET_BTN_DELETE_TEXT'); ?>
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
					<?php echo Text::_('JNO'); ?>
                </button>
            </div>
        </div>
    </div>
</div>
