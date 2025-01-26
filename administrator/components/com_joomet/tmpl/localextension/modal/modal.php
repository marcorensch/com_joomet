<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomet
 *
 * @copyright   Copyright (C) 2025 NXD nx-designs, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @var     array    $displayData the data to display
 *
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

HTMLHelper::_('bootstrap.modal');       // Lädt das Bootstrap-CSS

?>

<div class="modal fade" id="translateConfirmationModal" tabindex="-1" aria-labelledby="translateConfirmationModalLabel"
     aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title"
				    id="translateConfirmationModalLabel"><?php echo Text::_('COM_JOOMET_TRANSLATE_CONFIRM_TITLE'); ?></h3>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-3">
				<?php echo Text::_('COM_JOOMET_ARE_YOU_SURE_TRANSLATE'); ?>

				<form id="translateConfirmForm"
				      action="<?php echo Route::_('index.php?option=com_joomet&task=uploaded.handleDeleteFileClicked'); ?>"
				      method="post">
					<input type="hidden" id="file-to-translate" name="file"
					       value="<?php echo htmlspecialchars("", ENT_QUOTES, 'UTF-8'); ?>">
					<?php echo HTMLHelper::_('form.token'); ?>
				</form>

			</div>
			<div class="modal-footer">
				<a href="#" id="translateConfirmButton" class="btn btn-danger" role="button"
				   onclick="document.getElementById('translateConfirmForm').submit();">
					<?php echo Text::_('COM_JOOMET_BTN_TRANSLATE_TEXT'); ?>
				</a>
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
					<?php echo Text::_('JNO'); ?>
				</button>
			</div>
		</div>
	</div>
</div>