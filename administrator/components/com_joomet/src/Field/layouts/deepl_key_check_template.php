<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomet
 *
 * @copyright   Copyright (C) 2025 NXD nx-designs, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

$usage        = $displayData['usage'];
$used         = $usage->character->count;
$limit        = $usage->character->limit;
$percentsUsed = ceil($used / $limit * 100);

?>

<div class="progress" role="progressbar" aria-label="Example with label" aria-valuenow="<?php echo $percentsUsed; ?>"
     aria-valuemin="0" aria-valuemax="100">
    <div class="progress-bar" style="width: <?php echo $percentsUsed; ?>%"><?php echo $percentsUsed; ?>%</div>
</div>
<div class="deepl-key-usage-text-container mt-2">
	<?php
	echo Text::sprintf(
		"COM_JOOMET_FIELD_DEEPL_KEY_CHECK_USAGE_MESSAGE",
		number_format((int)$used, 0, ".", "'"),
		number_format((int)$limit, 0, ".", "'"),
		$percentsUsed . "%"
	); ?>
</div>
