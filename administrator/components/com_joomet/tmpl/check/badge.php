<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomet
 *
 * @copyright   Copyright (C) 2025 NXD nx-designs, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       1.0.0
 *
 * @var $displayData array                   The display data
 */

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use Joomla\CMS\Layout\FileLayout;

$offcanvas = new FileLayout('offcanvas', __DIR__);
$offcanvas_id = "offcanvas-{$displayData['rowNum']}-{$displayData['msgIndex']}";
$displayData['offcanvas_id'] = $offcanvas_id;
$msgType = $displayData['msgType'];
$cls = "text-bg-{$msgType}"
?>
<div>
    <a class="badge rounded-pill <?php echo $cls;?>"
       data-bs-toggle="offcanvas"
       href="#<?php echo $offcanvas_id;?>"
    >
		<?php echo $displayData['error']->message;?>
    </a>
</div>
<?php echo $offcanvas->render($displayData); ?>
