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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use NXD\Component\Joomet\Administrator\View\Source\HtmlView;

/** @var HtmlView $this */


$app = Factory::getApplication();
$doc = $app->getDocument();
$wa  = $doc->getWebAssetManager();

$this->useCoreUI = true;

$input  = $app->input;
$target = $input->get('target', '', 'string');
$layout = 'default';
$tmpl   = $input->get('tmpl', '', 'CMD') === 'component' ? '&tmpl=component' : '';
$view   = $input->get('view', '', 'CMD');
$action = Route::_('index.php?option=com_joomet&view='.$view.'&layout=' . $layout);

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));

?>



<form action="<?php echo Route::_('index.php?option=com_joomet&view=localextensions'); ?>" method="post" name="adminForm" id="adminForm" class="form-vertical">

    <div class="row">
        <div class="col-md-12">
            <div id="j-main-container" class="j-main-container">
	            <?php echo LayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>

                <table class="table" id="extensionsList">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Element</th>
                        <th>Type</th>
                        <th>Folder</th>
                        <th>Client</th>
                    </tr>
                    </thead>
                    <tbody>
					<?php foreach ($this->extensions as $ext) : ?>
                        <tr>
                            <td><?php echo $ext->extension_id; ?></td>
                            <td><?php
                                echo '<a href="' . Route::_('index.php?option=com_joomet&view=localextension&ext=' . $ext->element) . '&target="'.$target.'">' . $ext->name . '</a>'
                                ?>
                            </td>
                            <td><?php echo $ext->element; ?></td>
                            <td><?php echo $ext->type; ?></td>
                            <td><?php echo $ext->folder; ?></td>
                            <td><?php
	                            $string = $ext->client_id ? "COM_JOOMET_CLIENT_LABEL_BACKEND" : "COM_JOOMET_CLIENT_LABEL_FRONTEND";
                                $cls = $ext->client_id ? "bg-primary" : "bg-info";
                                echo "<div class=\"badge $cls\">" . Text::_($string) . "</div>"
                                ?>
                            </td>
                        </tr>
					<?php endforeach; ?>
                    </tbody>
                </table>
				<?php echo $this->pagination->getListFooter(); ?>
                <input type="hidden" name="task" value="">
                <input type="hidden" name="boxchecked" value="">
				<?php echo HTMLHelper::_('form.token'); ?>
            </div>
        </div>
    </div>
</form>