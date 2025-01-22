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
use NXD\Component\Joomet\Administrator\View\Dashboard\HtmlView;

/** @var HtmlView $this */

$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->addInlineStyle('
.pp-QDZ3XA9V2SDHW{text-align:center;border:none;border-radius:0.25rem;min-width:11.625rem;padding:0 2rem;height:2.625rem;font-weight:bold;background-color:#FFD140;color:#000000;font-family:"Helvetica Neue",Arial,sans-serif;font-size:1rem;line-height:1.25rem;cursor:pointer;}
')

?>

<div class="row g-4">
    <div class="col-lg-6 d-flex">
        <div class="card card-default w-100">
            <div class="card-header">
                <h3 class="card-title">PayPal</h3>
                <hr>
            </div>
            <div class="card-body">
                <form action="https://www.paypal.com/ncp/payment/QDZ3XA9V2SDHW" method="post" target="_blank"
                      style="display:inline-grid;justify-items:center;align-content:start;gap:0.5rem;">
                    <input class="pp-QDZ3XA9V2SDHW" type="submit" value="Sponsor Joomet"/>
                    <img src=https://www.paypalobjects.com/images/Debit_Credit_APM.svg alt="cards"/>
                    <section> Abgewickelt durch <img
                                src="https://www.paypalobjects.com/paypal-ui/logos/svg/paypal-wordmark-color.svg"
                                alt="paypal" style="height:0.875rem;vertical-align:middle;"/></section>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6 d-flex">
        <div class="card card-default w-100">
            <div class="card-header">
                <h3 class="card-title">Buy me a Coffee</h3>
                <hr>
            </div>
            <div class="card-body">
                <script type="text/javascript" src="https://cdnjs.buymeacoffee.com/1.0.0/button.prod.min.js"
                        data-name="bmc-button" data-slug="nxdesigns" data-color="#FFDD00" data-emoji=""
                        data-font="Cookie" data-text="Buy me a coffee" data-outline-color="#000000"
                        data-font-color="#000000" data-coffee-color="#ffffff"></script>
            </div>
        </div>
    </div>
</div>
