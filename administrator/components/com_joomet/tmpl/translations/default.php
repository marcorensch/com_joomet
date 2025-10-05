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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Router\Route;
use NXD\Component\Joomet\Administrator\View\Translations\HtmlView;

/** @var HtmlView $this */

$fileData    = $this->fileData['data'];
$rows        = $fileData['rowsToTranslate'] ?? [];
$skippedRows = $fileData['rowsToSkip'] ?? [];
$form        = $this->getForm();

$rowTemplate = new FileLayout('row', __DIR__ . '/table');

$jsonRows = json_encode($rows, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->addInlineScript('const rowsToTranslate = ' . $jsonRows . ';');

?>

<h1>Translations</h1>
<div class="sticky-top bg-default" style="top: 80px; backdrop-filter: blur(10px);">
    <div class="card card-default card-body mb-3">
        <form class="form-vertical">
            <div class="row align-items-center">
                <div class="col-12 col-sm-6 col-md-6 col-xl-5">
					<?php echo $this->getForm()->renderField('source_language'); ?>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-xl-5">
					<?php echo $this->getForm()->renderField('target_language'); ?>
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-xl-2">
					<?php echo $this->getForm()->renderField('use_formality'); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-8">
                    <div class="translation-progress-container w-100">
                        <div class="progress" role="progressbar" aria-label="Translation Progress"
                             aria-valuenow="0" aria-valuemin="0"
                             aria-valuemax="100"
                             style="height: 20px"
                        >
                            <div id="nxd-translation-progress-bar" class="progress-bar progress-bar-animated"
                                 style="width: 0; height: 20px">0%
                            </div>
                        </div>
                    </div>
                    <div>
                        <div id="nxd-translation-status-container" class="small mt-1">
                            <span>Now Translating:</span> <span id="nxd-current-string-preview" class=""></span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="btn-group w-100">
                        <button id="nxd-stop-translation-btn" class="btn btn-danger"><i class="fas fa-stop"></i>
                        </button>
                        <button id="nxd-start-translation-btn" class="w-100 btn btn-primary nxd-start-translation">
                            <i class="fas fa-play"></i> Translate
                        </button>
                    </div>

                </div>
            </div>

        </form>
    </div>

</div>

<form id="translation-form" action="<?php echo Route::_('index.php?option=com_joomet&view=translations'); ?>" enctype="multipart/form-data"
      method="post" class="form-vertical">
    <table class="table align-middle">
        <thead>
        <tr class="align-middle">
            <th style="min-width:1%;"><?php echo Text::_('COM_JOOMET_TABLE_HEADER_LINE'); ?></th>
            <th><?php echo Text::_('COM_JOOMET_TABLE_HEADER_CONSTANT'); ?></th>
            <th><?php echo Text::_('COM_JOOMET_TABLE_HEADER_ORIGINAL_VALUE'); ?></th>
            <th id="skip-header" class="d-flex flex-row align-items-center" style="min-width:10%;">

                <span><?php echo Text::_('COM_JOOMET_TABLE_HEADER_SKIP'); ?></span>

				<?php
				$skipField        = $this->getForm()->getField('skip_element');
				$skipField->name  = 'skip_all_toggler';
				$skipField->id    = 'skip_all_toggler';
				$skipField->class = 'nxd-skip-all-toggler';
				echo $skipField->renderField();
				?>

            </th>
            <th class="w-40"><?php echo Text::_('COM_JOOMET_TABLE_HEADER_TRANSLATION'); ?></th>
        </tr>
        </thead>
        <tbody id="joomet-translation-table-body">
		<?php
		foreach ($rows as $row)
		{
			echo $rowTemplate->render(["row" => $row, "form" => $form, "hidden" => false]);
		}
		foreach ($skippedRows as $row)
		{
			echo $rowTemplate->render(["row" => $row, "form" => $form, "hidden" => true]);
		}
		?>
        </tbody>
    </table>

    <input type="hidden" name="task" value="translations.generateTranslatedFile">
    <input type="hidden" name="filename" value="<?php echo $this->fileName ?? ""; ?>">
    <input type="hidden" id="selected-language-field" name="language" value="">
	<?php echo HTMLHelper::_('form.token'); ?>

    <div class="card card-default card-body mt-2 mb-2">
        <div class="d-flex flex-row align-items-center gap-2">
            <div id="nxd-check-translation-note" class="hidden">
                <div class="alert alert-warning mt-0 mb-0">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>
                    <?php echo Text::_('COM_JOOMET_TRANSLATIONS_WARNING_MESSAGE'); ?>
                </span>
                </div>
            </div>
            <div class="ms-auto col-12 col-sm-3 col-lg-2">
                <button type="submit" class="btn btn-success w-100"><i class="fas fa-download"></i> <?php echo Text::_("COM_JOOMET_DOWNLOAD_FILE_BTN_TXT");?></button>
            </div>
        </div>
    </div>

</form>

