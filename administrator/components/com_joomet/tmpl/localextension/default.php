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
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use NXD\Component\Joomet\Administrator\View\Source\HtmlView;

/** @var HtmlView $this */


$app = Factory::getApplication();
$doc = $app->getDocument();
$wa  = $doc->getWebAssetManager();

?>

    <h1>Extension</h1>

<?php echo '<pre>' . var_export($this->languageFiles, 1) . '</pre>'; ?>

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
						echo $rowTemplate->render(['file' => $file, 'location' => "JSITE"]);

					}
					foreach ($this->languageFiles['administration'] as $file)
					{
						$rowTemplate = new FileLayout('row', __DIR__ . '/table');
						echo $rowTemplate->render(['file' => $file, 'extension' => $this->extension, 'location' => "JADMINISTRATION"]);

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
