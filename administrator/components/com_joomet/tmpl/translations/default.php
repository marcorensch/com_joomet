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

<table class="table">
    <thead>
    <tr>
        <th><?php echo Text::_('COM_JOOMET_TABLE_HEADER_LINE');?></th>
        <th><?php echo Text::_('COM_JOOMET_TABLE_HEADER_CONSTANT');?></th>
        <th><?php echo Text::_('COM_JOOMET_TABLE_HEADER_ORIGINAL_VALUE');?></th>
        <th><?php echo Text::_('COM_JOOMET_TABLE_HEADER_TRANSLATION');?></th>
    </tr>
    </thead>
    <tbody>
	<?php foreach ($this->fileData['data'] as $row) : ?>
        <tr>
            <td><?php echo $row->rowNum; ?></td>
            <td><?php echo $row->constant; ?></td>
            <td><?php echo htmlspecialchars($row->content); ?></td>
            <td></td>
        </tr>
	<?php endforeach; ?>
    </tbody>
</table>
