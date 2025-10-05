<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_joomet
 *
 * @copyright   Copyright (C) 2025 NXD nx-designs, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

use NXD\Component\Joomet\Administrator\View\Source\HtmlView;

/** @var HtmlView $this */
?>


<div class="row gy-4 gx-4 f-flex justify-content-center">
	<?php foreach ($this->sources as $source) : ?>
		<div class="col-md-3">
			<div>
				<div class="card text-center">
					<div class="card-header mx-auto">
						<i class="<?php echo $source->icon; ?> fa-5x"></i>
					</div>
					<div class="card-body">
						<h3 class="card-title"><?php echo $source->title; ?></h3>
						<a href="<?php echo $source->link; ?>" class="stretched-link"></a>
					</div>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>