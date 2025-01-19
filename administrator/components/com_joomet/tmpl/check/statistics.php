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

use Joomla\CMS\Date\Date;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

$stats = $displayData['stats'];
$rowsCount = $displayData['rowsCount'];

?>
<div class="card card-default w-100">
	<div class="card-body">
        <h3><?php echo Text::_('COM_JOOMET_TITLE_STATISTICS');?></h3>
        <table class="table table-striped">
			<tbody>
			<tr>
				<th>
                    <?php echo Text::_('COM_JOOMET_FIELD_ROWS_COUNT');?>
				</th>
				<td>
					<?php echo $rowsCount; ?>
				</td>
			</tr>
			<tr>
				<th>
                    <?php echo Text::_('COM_JOOMET_FIELD_ROWS_EMPTY');?>
				</th>
				<td>
					<?php echo $stats['empty']; ?>
				</td>
			</tr>
			<tr>
				<th>
                    <?php echo Text::_('COM_JOOMET_FIELD_ROWS_COMMENT');?>
				</th>
				<td>
					<?php echo $stats['comment']; ?>
				</td>
			</tr>
			<tr>
				<th>
                    <?php echo Text::_('COM_JOOMET_FIELD_ROWS_TRANSLATION');?>
				</th>
				<td>
					<?php echo $stats['translation']; ?>
				</td>
			</tr>
			<tr>
				<th>
                    <?php echo Text::_('COM_JOOMET_FIELD_ROWS_INVALID');?>
				</th>
				<td>
					<?php echo count($stats['invalid_rows']); ?>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
</div>