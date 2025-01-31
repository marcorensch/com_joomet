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
use Joomla\CMS\Language\Text;
use NXD\Component\Joomet\Administrator\View\Translations\HtmlView;

/** @var HtmlView $this */

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();

?>

<h1>Translations</h1>

<?php echo '<pre>' . var_export($this->fileData['data'], 1) . '</pre>'; ?>

<table class="table align-middle">
    <thead>
    <tr>
        <th style="min-width:1%;"><?php echo Text::_('COM_JOOMET_TABLE_HEADER_LINE'); ?></th>
        <th><?php echo Text::_('COM_JOOMET_TABLE_HEADER_CONSTANT'); ?></th>
        <th><?php echo Text::_('COM_JOOMET_TABLE_HEADER_ORIGINAL_VALUE'); ?></th>
        <th class="w-40"><?php echo Text::_('COM_JOOMET_TABLE_HEADER_TRANSLATION'); ?></th>
    </tr>
    </thead>
    <tbody>
	<?php foreach ($this->fileData['data'] as $row) : ?>
        <tr>
            <td class="text-center"><?php echo $row->rowNum; ?></td>
            <td><code class="constant"><?php echo $row->constant; ?></code></td>
            <td><?php echo htmlspecialchars($row->content); ?></td>
            <td>
                <label class="hidden row-translation-label"
                       for="row-<?php echo $row->rowNum; ?>-translation-textarea"
                >
                    Translation
                </label>
                <textarea
                        name="row-<?php echo $row->rowNum; ?>-translation-textarea"
                        class="form-control w-100"
                        id="row_<?php echo $row->rowNum; ?>_translation" cols="30" rows="2">
                </textarea>
            </td>
        </tr>
	<?php endforeach; ?>
    </tbody>
</table>

<div class="translation-progress-container">
    <div class="progress" role="progressbar" aria-label="Translation Progress" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
        <div class="progress-bar" style="width: 25%">25%</div>
    </div>
</div>
