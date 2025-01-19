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

?>

<div class="row gx-4">
	<div class="col-lg-8">
		<div class="row gy-4 gx-4">
			<?php foreach ($this->items as $item) : ?>
				<div class="col-md-6">
					<div>
						<div class="card text-center">
							<div class="card-header mx-auto">
								<i class="<?php echo $item->icon; ?> fa-5x"></i>
							</div>
							<div class="card-body">
								<h3 class="card-title"><?php echo $item->title; ?></h3>
								<a href="<?php echo $item->link; ?>" class="stretched-link"></a>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<div class="col-lg-4">
		<div>
			<div class="card">
				<div class="card-header">
					<h3 class="card-title"><?php echo Text::_('COM_JOOMET')?></h3>
					<hr>
				</div>
				<div class="card-body">
					<h4><?php echo Text::_('COM_JOOMET_DASHBOARD_VERSION_TITLE')?></h4>
					<div class="component-version"><?php echo $this->componentVersion; ?></div>
					<hr>
					<h4><?php echo Text::_('COM_JOOMET_DASHBOARD_ABOUT_TITLE')?></h4>
					<p><?php echo Text::_('COM_JOOMET_DASHBOARD_ABOUT_TEXT')?></p>
					<p>
						<?php echo Text::_('COM_JOOMET_DASHBOARD_ABOUT_TEXT_2')?><br>
						<?php echo Text::_('COM_JOOMET_DASHBOARD_ABOUT_TEXT_3')?>
					</p>
				</div>
			</div>
		</div>
	</div>
</div>
