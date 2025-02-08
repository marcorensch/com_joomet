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

use Joomla\CMS\Form\Field\EditorField;
use Joomla\CMS\Language\Text;
use NXD\Component\Joomet\Administrator\View\Translations\HtmlView;

$row    = $displayData['row'];
$hidden = $displayData['hidden'];
$form   = $displayData['form'];

?>

<tr id="jform_row_<?php echo $row->rowNum; ?>" class="<?php echo $hidden ? "hidden" : ""; ?>">
    <td class="text-center"><?php echo $row->rowNum; ?></td>
    <td><label for="jform[translation_constant_<?php echo $row->rowNum; ?>]" class="hidden"><?php echo Text::_("COM_JOOMET_TABLE_HEADER_CONSTANT");?></label>
        <input readonly
               id="jform_translation_constant_<?php echo $row->rowNum; ?>"
               name="jform[translation_constant_<?php echo $row->rowNum; ?>]"
               class="constant form-control" value="<?php echo $row->constant; ?>"
        />
    </td>
    <td><?php
		echo '<span data-row-num="' . $row->rowNum . '" id="row-' . $row->rowNum . '-source" class="joomet-translation-source-value">';
		echo htmlspecialchars($row->content);
		echo '</span>';
		?>
    </td>
    <td>
		<?php
		$skipField        = $form->getField('skip_element');
		$skipField->name  = "skip_element_{$row->rowNum}";
		$skipField->id    = 'skip_element_' . $row->rowNum . '_';
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
		$translationField        = $form->getField('translation_editor');
		$translationField->name  = "translation_editor_{$row->rowNum}";
		$translationField->id    = 'translation_editor_' . $row->rowNum;
		$translationField->class = 'nxd-translation-editor-field';
		$translationField->__set('data-row-num', $row->rowNum);

		echo $translationField->renderField();
		?>
    </td>
</tr>

