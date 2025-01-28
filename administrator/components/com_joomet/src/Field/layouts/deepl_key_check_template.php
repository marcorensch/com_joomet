<?php
/**
 * @package     NXD\Module\NCE\Site\Field
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

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
