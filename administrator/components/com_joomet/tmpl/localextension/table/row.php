<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomet
 *
 * @copyright   Copyright (C) 2025 NXD nx-designs, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @var     array    $displayData the data to display
 * @var     HtmlView $this
 *
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use NXD\Component\Joomet\Administrator\View\Source\HtmlView;

$file     = $displayData['file'];
$location = $displayData['location'];
$extension = $displayData['extension'];


$user = Factory::getApplication()->getIdentity();

?>

<tr>
    <td><?php echo $file->name; ?></td>
    <td><?php echo $file->relative_path; ?></td>
    <td class="text-center"><?php echo Text::alt($location, ""); ?></td>
    <td class="text-center">
        <div class="hstack gap-3">
            <div class="ms-auto">
				<?php if ($user->authorise("com_joomet.check", "com_joomet") || $user->authorise("com_joomet.translate", "com_joomet")): ?>
                <div class="btn-group">
                        <?php if ($user->authorise("com_joomet.check", "com_joomet")): ?>
                            <a href="<?php echo Route::_('index.php?option=com_joomet&task=localextension.handleCheckFileClicked&data=' . base64_encode(serialize($file))); ?>"
                               title="<?php echo Text::_("COM_JOOMET_CHECK_FILE_TXT"); ?>"
                               class="btn btn-light">
                                <i class="fa fa-file-circle-check"></i>
                            </a>
                        <?php endif; ?>
                        <?php if ($user->authorise("com_joomet.translate", "com_joomet")): ?>
                            <?php if ($extension->locked || $extension->protected):?>
                                <a href="#"
                                   title="<?php echo Text::_("COM_JOOMET_TRANSLATE_FILE_TXT"); ?>"
                                   data-bs-toggle="modal"
                                   data-bs-target="#translateConfirmationModal"
                                   onclick="handleTranslateCoreFileClicked('<?php echo $file->path; ?>', '<?php echo $file->name; ?>')"
                                   class="btn btn-warning">
                                    <i class="fa fa-language"></i>
                                </a>
                            <?php else: ?>
                                <a href="<?php echo Route::_('index.php?option=com_joomet&task=localextension.handleTranslateFileClicked&data=' . base64_encode(serialize($file))); ?>"
                                   title="<?php echo Text::_("COM_JOOMET_TRANSLATE_FILE_TXT"); ?>"
                                   class="btn btn-light">
                                    <i class="fa fa-language"></i>
                                </a>
                            <?php endif;?>
                        <?php endif; ?>
					<?php endif; ?>
                    <a target="_blank" href="<?php echo $file->url; ?>"
                       title="<?php echo Text::_("COM_JOOMET_VIEW_FILE_TXT"); ?>"
                       class="btn btn-primary nxd-ext-btn">
                        <i class="fa fa-eye"></i>
                    </a>
                </div>
            </div>
        </div>
    </td>
</tr>
