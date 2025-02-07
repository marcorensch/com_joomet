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
use Joomla\CMS\Form\Field\EditorField;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Language\Text;
use NXD\Component\Joomet\Administrator\View\Translations\HtmlView;

/** @var HtmlView $this */

$rows     = $this->fileData['data'];
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

<table class="table align-middle">
    <thead>
    <tr class="align-middle">
        <th style="min-width:1%;"><?php echo Text::_('COM_JOOMET_TABLE_HEADER_LINE'); ?></th>
        <th><?php echo Text::_('COM_JOOMET_TABLE_HEADER_CONSTANT'); ?></th>
        <th><?php echo Text::_('COM_JOOMET_TABLE_HEADER_ORIGINAL_VALUE'); ?></th>
        <th id="skip-header" class="d-flex flex-row align-items-center" style="min-width:10%;">

            <span><?php echo Text::_('COM_JOOMET_TABLE_HEADER_SKIP'); ?></span>

	        <?php
	        $skipField = $this->getForm()->getField('skip_element');
	        $skipField->name =  'skip_all_toggler';
	        $skipField->id =  'skip_all_toggler';
            $skipField->class = 'nxd-skip-all-toggler';
	        echo $skipField->renderField();
	        ?>


        </th>
        <th class="w-40"><?php echo Text::_('COM_JOOMET_TABLE_HEADER_TRANSLATION'); ?></th>
    </tr>
    </thead>
    <tbody id="joomet-translation-table-body">
	<?php foreach ($this->fileData['data'] as $row) : ?>
        <tr>
            <td class="text-center"><?php echo $row->rowNum; ?></td>
            <td><code class="constant"><?php echo $row->constant; ?></code></td>
            <td><?php
				echo '<span data-row-num="' . $row->rowNum . '" id="row-' . $row->rowNum . '-source" class="joomet-translation-source-value">';
				echo htmlspecialchars($row->content);
				echo '</span>';
				?>
            </td>
            <td>
	            <?php
	            $skipField = $this->getForm()->getField('skip_element');
                $skipField->name =  'skip_element[' . $row->rowNum . ']';
                $skipField->id =  'skip_element_' . $row->rowNum . '_';
                $skipField->class = 'nxd-skip-element-checkbox';
                echo $skipField->renderField();
                ?>
            </td>
            <td>
                <label class="hidden row-translation-label"
                       for="row-<?php echo $row->rowNum; ?>-translation-textarea"
                >
                    Translation
                </label>

	            <?php
	            /** @var EditorField $translationField */
	            $translationField = $this->getForm()->getField('translation_editor');
	            $translationField->name =  'translation_editor[' . $row->rowNum . ']';
	            $translationField->id =  'translation_editor_' . $row->rowNum;
	            $translationField->class = 'nxd-translation-editor-field';
	            $translationField->__set('data-row-num',$row->rowNum);

	            echo $translationField->renderField();
	            ?>
            </td>
        </tr>
	<?php endforeach; ?>
    </tbody>
</table>
