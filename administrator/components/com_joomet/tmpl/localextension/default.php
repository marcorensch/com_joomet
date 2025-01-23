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
?>

<h1>Extension</h1>