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

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use NXD\Component\Joomet\Administrator\View\Source\HtmlView;

$file       = $displayData['file'];
$location   = $displayData['location'];
$extension  = $displayData['extension'];
$targetView = $displayData['targetView'];


$user      = Factory::getApplication()->getIdentity();
$directUrl = false;

if ($targetView === 'check')
{
	$directUrl = Route::_('index.php?option=com_joomet&task=localextension.handleCheckFileClicked&file=' . base64_encode($file->path));
}
if ($targetView === 'translations')
{
	$directUrl = Route::_('index.php?option=com_joomet&task=localextension.handleTranslateFileClicked&file=' . base64_encode($file->path));
}

?>

<tr>
    <td><?php if ($directUrl): ?>
            <a href="<?php echo $directUrl; ?>" target="_self"><?php echo $file->name; ?></a>
		<?php else:
			echo $file->name;
		endif;
		?>
    </td>
    <td><?php echo $file->relative_path; ?></td>
    <td class="text-center"><?php echo Text::alt($location, ""); ?></td>
    <td class="text-center">
        <div class="hstack gap-3">
            <div class="ms-auto">
				<?php if ($user->authorise("com_joomet.check", "com_joomet") || $user->authorise("com_joomet.translate", "com_joomet")): ?>
                <div class="btn-group">
					<?php if ($user->authorise("com_joomet.check", "com_joomet")): ?>
                        <a href="<?php echo Route::_('index.php?option=com_joomet&task=localextension.handleCheckFileClicked&file=' . base64_encode($file->path)); ?>"
                           title="<?php echo Text::_("COM_JOOMET_CHECK_FILE_TXT"); ?>"
                           class="btn btn-light">
                            <i class="fa fa-file-circle-check"></i>
                        </a>
					<?php endif; ?>
					<?php if ($user->authorise("com_joomet.translate", "com_joomet")): ?>
						<?php if ($extension->locked || $extension->protected): ?>
                            <a href="#"
                               title="<?php echo Text::_("COM_JOOMET_TRANSLATE_FILE_TXT"); ?>"
                               data-bs-toggle="modal"
                               data-bs-target="#translateConfirmationModal"
                               onclick="handleTranslateCoreFileClicked('<?php echo base64_encode($file->path); ?>', '<?php echo $file->name; ?>')"
                               class="btn btn-warning">
                                <i class="fa fa-language"></i>
                            </a>
						<?php else: ?>
                            <a href="<?php echo Route::_('index.php?option=com_joomet&task=localextension.handleTranslateFileClicked&file=' . base64_encode($file->path)); ?>"
                               title="<?php echo Text::_("COM_JOOMET_TRANSLATE_FILE_TXT"); ?>"
                               class="btn btn-light">
                                <i class="fa fa-language"></i>
                            </a>
						<?php endif; ?>
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
