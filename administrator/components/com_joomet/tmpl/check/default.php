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

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use NXD\Component\Joomet\Administrator\View\Check\HtmlView;

/** @var HtmlView $this */
$params = $this->params;
$user   = Factory::getApplication()->getIdentity();
$userId = $user->id;
$rows   = $this->rows;
$stats  = $this->statistics;
if (!$rows) return;
$badge = new FileLayout('badge', __DIR__);

?>
<div class="mb-4">
    <div class="row">
        <div class="col col-md-6 d-flex">
		    <?php
		    $filenameChecksView = new FileLayout('file', __DIR__);
		    echo $filenameChecksView->render([
			    'checks' => $this->filenameChecks,
			    'name' => $stats['original_name'],
			    'uploaded' => $stats['uploaded']
		    ]);
		    ?>
        </div>

        <div class="col col-md-6 d-flex">
			<?php
			$statisticsView = new FileLayout('statistics', __DIR__);
			echo $statisticsView->render([
				'stats'     => $stats,
				'rowsCount' => count($rows),
			]);
			?>
        </div>

    </div>
</div>
<div class="mb-4">
    <div class="card card-default">
        <div class="card-body">
            <div class="row-filters">
                <div class="row justify-content-end">
                    <div class="col-auto">
                        <div class="form-check form-switch">
                            <label class="form-check-label" for="show_empty_rows">
                                <?php echo Text::_('COM_JOOMLET_FIELD_SHOW_EMPTY_ROWS'); ?>
                            </label>
                            <input class="form-check-input"
                                   type="checkbox"
                                   id="show_empty_rows"
                                   <?php echo $params->get('show_empty_rows', 1) ? 'checked' : ''; ?>
                            >
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="form-check form-switch">
                            <label class="form-check-label" for="show_comment_rows">
                                <?php echo Text::_('COM_JOOMLET_FIELD_SHOW_COMMENT_ROWS'); ?>
                            </label>
                            <input class="form-check-input"
                                   type="checkbox"
                                   id="show_comment_rows"
                                   <?php echo $params->get('show_comment_rows', 1) ? 'checked' : ''; ?>
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="main-card">
    <table class="table table-hover" id="checks-table">
        <thead>
        <tr>
            <th class="status-col"></th>
            <th class="text-center" style="width: 2%"><?php echo Text::_('COM_JOOMET_TABLE_HEADER_LINE');?></th>
            <th style="width: 25%"><?php echo Text::_('COM_JOOMET_TABLE_HEADER_CONSTANT');?></th>
            <th style="width: 25%"><?php echo Text::_('COM_JOOMET_TABLE_HEADER_VALUE');?></th>
            <th><?php echo Text::_('COM_JOOMET_TABLE_HEADER_ERRORS_WARNINGS');?></th>
        </tr>
        </thead>
        <tbody>

		<?php foreach ($rows as $i => $row):
			$hasErrors = !empty($row->errors);
			$hasWarnings = !empty($row->warnings);
			$hasInfos = !empty($row->infos);
			$isComment = $row->constant === null && str_starts_with($row->content, ';');
			$isEmpty = $row->constant === null && $row->content === '';
			$rowClass = $hasErrors ? '' : ($isComment ? 'info' : 'ok');
            $rowClass = $isEmpty ? 'empty' : $rowClass;
			$statusColClass = $hasInfos ? 'nxd-marked-info' : '';
			$statusColClass = $hasWarnings ? 'nxd-marked-warning' : '';
			$statusColClass = $hasErrors ? 'nxd-marked-danger' : '';

			?>
            <tr class="<?php echo "row-" . ($i % 2) . " table-{$rowClass}"; ?>"
                data-empty-row="<?php echo $isEmpty ? 1 : 0; ?>" data-comment-row="<?php echo $isComment ? 1 : 0; ?>">
                <td class="status-col <?php echo $statusColClass; ?>"></td>
                <td class="text-center"><?php echo $row->rowNum; ?></td>
                <td><?php echo $row->constant; ?></td>
                <td><?php echo htmlspecialchars($row->content, false, 'UTF-8'); ?></td>
                <td>
					<?php $offcanvasIndex = 0; ?>
					<?php foreach ($row->errors as $error):
						$offcanvasIndex++;
						echo $badge->render([
							'rowNum'   => $row->rowNum,
							'msgIndex' => $offcanvasIndex,
							'error'    => $error,
							'msgType'  => "danger",
							'params'   => $this->params,
						]);
						?>
					<?php endforeach; ?>
					<?php foreach ($row->warnings as $warning):
						$offcanvasIndex++;
						echo $badge->render([
							'rowNum'   => $row->rowNum,
							'msgIndex' => $offcanvasIndex,
							'error'    => $warning,
							'msgType'  => "warning",
							'params'   => $this->params,
						]);
						?>
					<?php endforeach; ?>
					<?php foreach ($row->infos as $info):
						$offcanvasIndex++;
						echo $badge->render([
							'rowNum'   => $row->rowNum,
							'msgIndex' => $offcanvasIndex,
							'error'    => $info,
							'msgType'  => "info",
							'params'   => $this->params,
						]);
						?>
					<?php endforeach; ?>
                </td>
            </tr>
		<?php endforeach; ?>
        </tbody>
    </table>
</div>
