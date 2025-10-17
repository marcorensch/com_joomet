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

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Date\Date;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

$name = $displayData['name'];
$uploaded = $displayData['uploaded'];

?>

<div class="card card-default w-100">
	<div class="card-body">
		<h3 class="mb-3"><?php echo Text::_('COM_JOOMET_TITLE_FILENAME_CHECKS');?></h3>

        <table class="table table-striped">
            <tbody>
            <tr>
                <th>
                    <?php echo Text::_('COM_JOOMET_FIELD_FILENAME'); ?>:
                </th>
                <td>
					<?php echo $name; ?>
                </td>
            </tr>
            <?php if($uploaded):?>
            <tr>
                <th>
                    <?php echo Text::_('COM_JOOMET_FIELD_UPLOADED'); ?>:
                </th>

                <td>
					<?php echo HTMLHelper::date(new Date($uploaded), "DATE_FORMAT_LC5"); ?>
                </td>
            </tr>
            <?php endif;?>
            <tr>
                <th>
                    <?php echo Text::_('COM_JOOMET_FIELD_PREFIXED'); ?>:
                </th>
                <td>
                    <div class="d-flex gap-3">
		            <?php
                        $cls = $displayData['checks']['prefixed'] ? 'success' : 'danger';
                        echo "<span class=\"text-{$cls}\">" . ($displayData['checks']['prefixed'] ? Text::_('JYES') : Text::_('JNO')) . "</span>";
                        if(!$displayData['checks']['prefixed']){
                            echo "<a target='_blank' href='https://manual.joomla.org/docs/next/general-concepts/multilingual/#implementing-multilingual-support-in-your-extension'>".Text::_('COM_JOOMET_ERROR_LEARN_MORE_LINK_LABEL')."</a>";
                        }
                    ?>
                    </div>
                </td>
            </tr>
            <tr>
                <th>
                    <?php echo Text::_('COM_JOOMET_FIELD_STRUCTURE'); ?>:
                </th>
                <td>
                    <div class="d-flex gap-3">
	                <?php
	                $cls = $displayData['checks']['structure'] ? 'success' : 'danger';
	                echo "<span class=\"text-{$cls}\">" . ($displayData['checks']['structure'] ? Text::_('JYES') : Text::_('JNO')) . "</span>";
	                if(!$displayData['checks']['structure']){
		                echo "<a target='_blank' href='https://manual.joomla.org/docs/next/general-concepts/multilingual/#implementing-multilingual-support-in-your-extension'>".Text::_('COM_JOOMET_ERROR_LEARN_MORE_LINK_LABEL')."</a>";
	                }
	                ?>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
	</div>
</div>
