<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomet
 *
 * @copyright   Copyright (C) 2025 NXD nx-designs, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 *
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use NXD\Component\Joomet\Administrator\View\LocalExtension\HtmlView;

/** @var HtmlView $this */


$app = Factory::getApplication();
$doc = $app->getDocument();
$wa  = $doc->getWebAssetManager();

$description_key = strtoupper($this->extension->ini_name) . '_XML_DESCRIPTION';
$hideDescription = $description_key === Text::_($description_key);

$targetView = $app->input->getCmd('target');

if($this->extension->locked){
	$l_cls = "bg-danger";
	$l_txt = Text::_('JYES');
	$l_icon = "fa-lock";
}else{
	$l_cls = "bg-success";
	$l_txt = Text::_('JNO');
	$l_icon = "fa-unlock";
}

if($this->extension->protected){
	$p_cls = "bg-danger";
	$p_txt = Text::_('JYES');
	$p_icon = "fa-lock";
}else{
	$p_cls = "bg-success";
	$p_txt = Text::_('JNO');
	$p_icon = "fa-unlock";
}

?>

    <div class="row mb-4">
        <div class="col-12 col-md-6">
            <div class="card card-default mb-3 mb-md-0">
                <div class="card-body">
                    <h3 class="card-title"><i
                                class="fas fa-info-circle"></i> <?php echo Text::_('COM_JOOMET_TITLE_EXTENSION_DETAILS'); ?>
                    </h3>
					<?php if (!$hideDescription): ?>
                        <p class="card-text"><?php echo Text::_($description_key); ?></p>
					<?php endif; ?>
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <th><?php echo Text::_('COM_JOOMET_LOCAL_TABLE_ID');?></th>
                                <td><?php echo $this->extension->extension_id;?></td>
                            </tr>
                            <tr>
                                <th><?php echo Text::_('COM_JOOMET_LOCAL_TABLE_NAME');?></th>
                                <td><?php echo $this->extension->name;?></td>
                            </tr>
                            <tr>
                                <th><?php echo Text::_('COM_JOOMET_LOCAL_TABLE_TYPE');?></th>
                                <td><?php echo $this->extension->type;?></td>
                            </tr>
                            <tr>
                                <th><?php echo Text::_('COM_JOOMET_LOCAL_TABLE_LOCKED');?></th>
                                <td><?php echo '<span class="badge '.$l_cls.'"><i class="fas '.$l_icon.'"></i> '.Text::_($l_txt).'</span>'?></td>
                            </tr>
                            <tr>
                                <th><?php echo Text::_('COM_JOOMET_LOCAL_TABLE_PROTECTED');?></th>
                                <td><?php echo '<span class="badge '.$p_cls.'"><i class="fas '.$p_icon.'"></i> '.Text::_($p_txt).'</span>'?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <h1><?php echo Text::_('COM_JOOMET_TITLE_LANGUAGE_FILES'); ?></h1>

    <div class="row">
        <div class="col-md-12">
            <div id="j-main-container" class="j-main-container">
                <table class="table align-middle mb-5" id="siteFiles">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Path</th>
                        <th class="text-center">Location</th>
                        <th class="text-center" style="min-width: 1%">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
					<?php
					foreach ($this->languageFiles['site'] as $file)
					{
						$rowTemplate = new FileLayout('row', __DIR__ . '/table');
						echo $rowTemplate->render(['file' => $file, 'extension' => $this->extension, 'location' => "JSITE", 'targetView' => $targetView]);

					}
					foreach ($this->languageFiles['administration'] as $file)
					{
						$rowTemplate = new FileLayout('row', __DIR__ . '/table');
						echo $rowTemplate->render(['file' => $file, 'extension' => $this->extension, 'location' => "JADMINISTRATION", 'targetView' => $targetView]);

					}
					?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php if ($this->extension->locked || $this->extension->protected)
{
	$wa->addInlineScript('    
	function handleTranslateCoreFileClicked(path, name)
	{
		document.getElementById("file-to-translate").value = path;
        document.getElementById("label-to-translate").textContent = name;
	}
');
	$modalLayout = new FileLayout('modal', __DIR__ . '/modal');
	echo $modalLayout->render();
}
