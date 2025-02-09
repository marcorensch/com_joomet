<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomet
 *
 * @copyright   Copyright (C) 2025 NXD nx-designs, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use NXD\Component\Joomet\Administrator\View\Localextensions\HtmlView;

/** @var HtmlView $this */


$app = Factory::getApplication();
$doc = $app->getDocument();
$wa  = $doc->getWebAssetManager();

$this->useCoreUI = true;

$input  = $app->input;
$target = $input->get('target', '', 'string');
$targetQueryString = $this->escape($target) ? "&target=" . $this->escape($target) : '';

$layout = 'default';
$tmpl   = $input->get('tmpl', '', 'CMD') === 'component' ? '&tmpl=component' : '';
$view   = $input->get('view', '', 'CMD');
$action = Route::_('index.php?option=com_joomet&view=localextensions' . $targetQueryString);

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));


$params = ComponentHelper::getParams('com_joomet');
if($params->get('show_filters_by_default', 0)){
	$showFiltersScript= <<<JS
    document.addEventListener("DOMContentLoaded", ()=>{
        const filtersContainer = document.querySelector(".js-stools-container-filters");
        //add active Class
        filtersContainer.classList.add("js-stools-container-filters-visible");
    })
    JS;
	$wa->addInlineScript($showFiltersScript);
}

?>



<form action="<?php echo $action ?>" method="post" name="adminForm" id="adminForm" class="form-vertical">

    <div class="row">
        <div class="col-md-12">
            <div id="j-main-container" class="j-main-container">
	            <?php echo LayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>

                <table class="table" id="extensionsList">
                    <thead>
                    <tr>
                        <th style="min-width: 1%;">ID</th>
                        <th>Name</th>
                        <th>Element</th>
                        <th>Type</th>
                        <th>Folder</th>
                        <th class="text-center" style="min-width: 1%;">Client</th>
                        <th class="text-center" style="min-width: 1%;">Protected</th>
                        <th class="text-center" style="min-width: 1%;">Locked</th>
                    </tr>
                    </thead>
                    <tbody>
					<?php foreach ($this->extensions as $ext) : ?>
                        <tr>
                            <td><?php echo $ext->extension_id; ?></td>
                            <td><?php
                                echo '<a href="' . Route::_('index.php?option=com_joomet&view=localextension&element=' . $ext->element . $targetQueryString) . '">' . Text::_($ext->name) . '</a>'
                                ?>
                            </td>
                            <td><?php echo $ext->element; ?></td>
                            <td><?php
                                echo $ext->type;
                                ?></td>
                            <td><?php echo $ext->folder; ?></td>
                            <td class="text-center">
                                <?php
	                            $string = $ext->client_id ? "COM_JOOMET_CLIENT_LABEL_BACKEND" : "COM_JOOMET_CLIENT_LABEL_FRONTEND";
                                $cls = $ext->client_id ? "bg-primary" : "bg-info";
                                echo "<span class=\"fs-5 fw-light badge $cls\">" . Text::_($string) . "</span>"
                                ?>
                            </td>
                            <td class="text-center">
	                            <?php if($ext->protected === 1):?>
                                    <i class="fa fa-shield"></i>
	                            <?php endif;?>
                            </td>
                            <td class="text-center">
                                <?php if($ext->locked === 1):?>
                                <i class="fa fa-lock"></i>
                                <?php endif;?>
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