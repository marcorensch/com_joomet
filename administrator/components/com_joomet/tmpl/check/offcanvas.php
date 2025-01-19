<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomet
 *
 * @copyright   Copyright (C) 2025 NXD nx-designs, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       1.0.0
 *
 * @var $displayData stdClass                   The display data
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

HTMLHelper::_('bootstrap.offcanvas', $displayData['offcanvas_id']);
$langPartConstant = $displayData['error']->type_id->value;

?>

<div class="joomet-offcanvas offcanvas offcanvas-end" tabindex="-1" id="<?php echo $displayData['offcanvas_id']; ?>"
     aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
        <h3 class="offcanvas-title" id="offcanvasExampleLabel">
			<?php echo Text::_("COM_JOOMET_ERROR_{$langPartConstant}_TITLE"); ?>
        </h3>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div>
            <div class="lead mb-2">
				<?php echo $displayData['error']->message ?>
            </div>
            <div class="mb-2">
				<?php echo Text::_("COM_JOOMET_ERROR_{$langPartConstant}_MSG"); ?>
            </div>
			<?php if ($displayData['error']->link) : ?>
            <div class="mt-5">
                <a target="_blank" href="<?php echo $displayData['error']->link ?>" class="btn btn-primary"><?php echo Text::_("COM_JOOMET_ERROR_LEARN_MORE_LINK_LABEL"); ?></a>
            </div>
			<?php endif; ?>
        </div>
    </div>
</div>
