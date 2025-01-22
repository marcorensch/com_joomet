<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomet
 *
 * @copyright   Copyright (C) 2025 NXD nx-designs, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use NXD\Component\Joomet\Administrator\View\Upload\HtmlView;

/** @var HtmlView $this */

$user      = Factory::getApplication()->getIdentity();
$userId    = $user->id;

?>

<form action="<?php echo Route::_('index.php?option=com_joomet'); ?>" enctype="multipart/form-data" class="form-vertical" method="post" name="adminForm" id="adminForm">
	<div class="row">
		<div class="col-md-12">
			<div id="j-main-container" class="j-main-container">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">

								<?php echo $this->getForm()->renderFieldset('upload'); ?>

                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-primary" type="submit"><?php echo Text::_("COM_JOOMET_UPLOAD_FILE_BTN_TXT");?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="target_view" value="<?php echo $this->targetView;?>">
                <input type="hidden" name="task" value="upload.handleFileUpload">
				<input type="hidden" name="boxchecked" value="0">
				<?php echo HTMLHelper::_('form.token'); ?>
			</div>
		</div>
	</div>
</form>
