<?php
/**
 * EaDesgin
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@eadesign.ro so we can send you a copy immediately.
 *
 * @category    eadesigndev_pdfgenerator
 * @copyright   Copyright (c) 2008-2016 EaDesign by Eco Active S.R.L.
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

namespace Eadesigndev\Opicmsppdfgenerator\Setup;

use Eadesigndev\Pdfgenerator\Model\Source\TemplatePaperForm;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Eadesigndev\Pdfgenerator\Model\PdfgeneratorFactory;
use Eadesigndev\Pdfgenerator\Model\PdfgeneratorRepository as TemplateRepository;
//@codingStandardsIgnoreFile
/**
 * Class InstallData
 * @package Eadesigndev\Pdfgenerator\Setup
 * Adds the templates default on module install
 * @codeCoverageIgnore
 * @SuppressWarnings(PHPMD)
 */
class InstallData implements InstallDataInterface
{

    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var PdfgeneratorFactory
     */
    private $templateFactory;

    /**
     * @var TemplateRepository
     */
    private $templateRepository;

    /**
     * InstallData constructor.
     * @param StoreManagerInterface $storeManager
     * @param PdfgeneratorFactory $templateFactory
     * @param TemplateRepository $templateRepository
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        PdfgeneratorFactory $templateFactory,
        TemplateRepository $templateRepository
    )
    {
        $this->storeManager = $storeManager;
        $this->templateFactory = $templateFactory;
        $this->templateRepository = $templateRepository;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {

        $storeId = $this->storeManager->getStore()->getId();

        $templates = [
            [
                'store_id' => $storeId,
                'is_active' => 1,
                'template_name' => 'Invoice Template Portrait!',
                'template_description' => 'The template for invoice default',
                'template_default' => 0,
                'template_type' => 1,
                'template_body' => '<style>
    .clearfix:after {
        content: "";
        display: table;
        clear: both;
    }
    a {
        color: #000;
        text-decoration: none;
    }
    table thead .desc, table thead .qty, table thead .unit {
        color: #f26522;
        text-align: center;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px;
    }
    table thead th {
        font-weight: bold;
        text-transform: uppercase;
        padding-top: 5px;
        padding-bottom: 5px;
    }
    table tfoot {
        background: #f2f8ee;
        text-align: right;
    }
    table tfoot td, table tfoot th {
        padding: 10px 20px;
        background: #f2f8ee;
        border-bottom: none;
        font-size: 1em;
        white-space: nowrap;
        border-top: 1px solid #AAAAAA;
        text-align: right;
        text-transform: uppercase;
        font-weight: normal;
        font-family: \'Oswald\', sans-serif;
    }
    .no {
        text-align: left;
        background: #f26522;
        color: #f2f8ee;
        padding-top: 5px;
        padding-bottom: 5px;
        width: 5%;
        padding-left: 10px;
    }
    .qty{
        width: 10%;
        background: #f2f8ee;
        text-align: center;
    }
    .unit{
        width: 15%;
        background: #f2f8ee;
        text-align: center;

    }
    .desc{
        width: 40%;
        background: #f2f8ee;
        text-align: left;
        padding-left: 10px;

    }
    .total {
        text-align: right;
        background: #f26522;
        color: #f2f8ee;
        padding-right: 10px;
        border-top: 1px solid #AAAAAA;
        padding-top: 5px;
        padding-bottom: 5px;
        width: 30%;
    }
</style>

<body>
<div>
    <div class="body"
         style="position: relative; width: 21cm; height: 29.7cm; margin: 0 auto; color: #555555; background: #FFFFFF; font-size: 14px;">
        <div class="main">
            <div id="details" class="clearfix" style="margin-bottom: 20px; font-family: \'Libre Franklin\', sans-serif;">
                <table id="client" style="width: 100%;">
                    <tr>
                        <td style="width:25%; padding-left: 6px; border-left: 6px solid #000; color: #777777;" class="to">INVOICE TO:</td>
                        <td style="width:25%; padding-left: 6px; border-left: 6px solid #000; color: #777777;" class="to">BILLING TO:</td>
                        <td id="invoice" style="font-family: \'Oswald\', sans-serif; text-align: right; width: 50%; "><h1 style="color: #000; font-size: 2.4em; line-height: 1em; font-weight: normal; margin: 0  0 10px 0;">
                            INVOICE {{var invoice.increment_id}}</h1></td>
                    </tr>
                    <tr>
                        <td class="name" style="padding-left: 6px; border-left: 6px solid #000;">{{var formattedBillingAddress|raw}}</td>
                        <td class="name" style="padding-left: 6px; border-left: 6px solid #000;">{{var formattedBillingAddress|raw}}</td>
                        <td style="text-align: right;" >
                            <div>{{var ea_barcode_c39_invoice.increment_id}}</div>
                            <div class="date" style="font-size: 1.1em; color: #777777;">Date of Invoice:{{var
                                invoice.created_at}}
                            </div>
                            <div class="date" style="font-size: 1.1em; color: #777777;">Status:{{var order.status}}</div>
                        </td>
                    </tr>
                </table>
            </div>
            <table style="width: 100%; font-family: \'Frank Ruhl Libre\', sans-serif; border-collapse: collapse; border-spacing: 0; margin-bottom: 20px;" border="0">
                <thead>
                <tr>
                    <th class="no">#</th>
                    <th class="desc">DESCRIPTION</th>
                    <th class="unit">PRICE</th>
                    <th class="qty">QTY</th>
                    <th class="total">TOTAL</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="5">##productlist_start##</td>
                </tr>
                <tr>
                    <td class="no">
                        <p>{{var item.position}}</p>
                    </td>
                    <td class="desc">
                        <p>{{var item.name}}</p>
                        <p>{{var item.sku}}</p>
                        <p>{{var ea_barcode_c39_item.sku}}</p>
                        <p>{{var item.options}}</p>
                        <p>{{var item.additional_options}}</p>
                        <p>{{var item.attributes_info}}</p>
                    </td>
                    <td class="unit">
                        <p>{{var ea_item.price}}</p>
                    </td>
                    <td class="qty">
                        <p>{{var ea_item.qty}}</p>
                    </td>
                    <td class="total">{{var ea_item.row_total}}</td>
                </tr>
                <tr>
                    <td colspan="5">##productlist_end##</td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="3">SUBTOTAL</td>
                    <td>{{var ea_invoice.subtotal}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="3">SHIPPING TAX</td>
                    <td>{{var ea_invoice.base_shipping_amount}}{{depend invoice_if.base_shipping_amount}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="3">DEPEND SHIPPING TAX</td>
                    <td>{{var invoice.base_shipping_amount}}{{/depend}}</td>
                </tr>
                <tr>
                    <td style="border-top: 1px solid #f26522;" >&nbsp;</td>
                    <td class="grandtotal" style="color: #f26522; font-weight: bold; font-size: 1.6em; border-top: 1px solid #f26522;"
                        colspan="3">GRAND TOTAL
                    </td>
                    <td class="grandtotal" style="color: #f26522;  font-weight: bold; font-size: 1.6em; border-top: 1px solid #f26522;">
                        {{var ea_invoice.grand_total}}
                    </td>
                </tr>
                </tfoot>
            </table>
            <div id="thanks" style="font-size: 2em; margin-top: 100px; font-family: \'Libre Franklin\', sans-serif;">Thank you!</div>
        </div>
    </div>
</div>
</body>',
                'template_header' => '<div class="clearfix header" style="padding: 10px 0; margin-bottom: 15px; border-bottom: 1px solid #AAAAAA;">            <div id="logo" style="float: left; margin-top: 8px; width: 49%;"><img style="height: 70px;" alt=""                                                                                  src="https://www.eadesign.ro/skin/frontend/default/eadesignnew/images/logo-eadesign.png"/>            </div>            <div id="company" style="float: right; font-family: \'\'Libre Franklin\'\', sans-serif; text-align: right; width: 49%;">                <h2 class="name" style="font-size: 1.4em; font-weight: normal; margin: 0;">EaDesign Web Development</h2>                <div>Lascar Catargi nr.10, et.1,Iasi,Romania</div>                <div>0232 272221</div>                <div><a href="mailto:office@eadesign.ro">office@eadesign.ro</a></div>            </div>        </div>',
                'template_footer' => '
<h2>Dear {{var order.getCustomerName()}}</h2><h3>Thank you!</h3>', '<div style="text-align: center; color: #777777; border-top: 1px solid #AAAAAA;">Invoice was created on a computer and is valid without the signature and seal.</div><div style="text-align: center;">Page number {PAGENO}/{nbpg}. Call us at 0800 454 454 at eny time!</div>'
                ,
                'template_css' => '',
                'template_file_name' => 'invoice',
                'template_paper_form' => TemplatePaperForm::TEMAPLATE_PAPER_FORM_A4,
                'template_custom_form' => 0,
                'template_custom_h' => 25,
                'template_custom_w' => 25,
                'template_custom_t' => 42,
                'template_custom_b' => 0,
                'template_custom_l' => 5,
                'template_custom_r' => 10,
                'template_paper_ori' => 1,
                'barcode_types' => 'c39',
                'customer_group_id' => '0',
                'creation_time' => time(),
                'update_time' => time(),
            ],
            [
                'store_id' => $storeId,
                'is_active' => 1,
                'template_name' => 'Invoice Template Landscape!',
                'template_description' => 'The template for invoice default',
                'template_default' => 1,
                'template_type' => 1,
                'template_body' => '<style>

    .clearfix:after {
        content: "";
        display: table;
        clear: both;
    }
    a {
        color: #000;
        text-decoration: none;
    }
    table thead .desc, table thead .qty, table thead .unit {
        color: #f26522;
        text-align: center;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px;
    }
    table thead th {
        font-weight: bold;
        text-transform: uppercase;
        padding-top: 5px;
        padding-bottom: 5px;
    }
    table tfoot {
        background: #f2f8ee;
        text-align: right;
    }
    table tfoot td, table tfoot th {
        padding: 10px 20px;
        background: #f2f8ee;
        border-bottom: none;
        font-size: 1em;
        white-space: nowrap;
        border-top: 1px solid #AAAAAA;
        text-align: right;
        text-transform: uppercase;
        font-weight: normal;
        font-family: \'Oswald\', sans-serif;
    }
    .no {
        text-align: left;
        background: #f26522;
        color: #f2f8ee;
        padding-top: 5px;
        padding-bottom: 5px;
        width: 5%;
        padding-left: 10px;
    }
    .qty{
        width: 7%;
        background: #f2f8ee;
        text-align: center;
    }
    .unit{
        width: 10%;
        background: #f2f8ee;
        text-align: center;
    }
    .desc{
        width: 24%;
        background: #f2f8ee;
        text-align: left;
        padding-left: 10px;
    }
    .total {
        text-align: right;
        background: #f26522;
        color: #f2f8ee;
        padding-right: 10px;
        border-top: 1px solid #AAAAAA;
        padding-top: 5px;
        padding-bottom: 5px;
        width: 20%;
    }
</style>
<body>
<div>
    <div class="body"
         style="position: relative; margin: 0 auto; color: #555555; background: #FFFFFF; font-size: 14px;">

        <div class="main">
            <div id="details" class="clearfix" style="margin-bottom: 20px; font-family: \'Libre Franklin\', sans-serif;">
                <table id="client" style="width: 100%;">
                    <tr>
                        <td style="width:25%; padding-left: 6px; border-left: 6px solid #000; color: #777777;" class="to">INVOICE TO:</td>
                        <td style="width:25%; padding-left: 6px; border-left: 6px solid #000; color: #777777;" class="to">BILLING TO:</td>
                        <td id="invoice" style="font-family: \'Oswald\', sans-serif; text-align: right; width: 50%; "><h1 style="color: #000; font-size: 2.4em; line-height: 1em; font-weight: normal; margin: 0  0 10px 0;">
                            INVOICE {{var invoice.increment_id}}</h1></td>
                    </tr>
                    <tr>
                        <td class="name" style="padding-left: 6px; border-left: 6px solid #000;">{{var formattedBillingAddress|raw}}</td>
                        <td class="name" style="padding-left: 6px; border-left: 6px solid #000;">{{var formattedBillingAddress|raw}}</td>
                        <td style="text-align: right;" >
                            <div>{{var ea_barcode_c39_invoice.increment_id}}</div>
                            <div class="date" style="font-size: 1.1em; color: #777777;">Date of Invoice:{{var
                                invoice.created_at}}
                            </div>
                            <div class="date" style="font-size: 1.1em; color: #777777;">Status:{{var order.status}}</div>
                        </td>
                    </tr>
                </table>
            </div>
            <table class="table" style="width: 100%; font-family: \'Frank Ruhl Libre\', sans-serif; border-collapse: collapse; border-spacing: 0; margin-bottom: 20px;" border="0">
                <thead>
                <tr>
                    <th class="no">#</th>
                    <th class="desc">DESCRIPTION</th>
                    <th class="unit">PRICE WITH VAT</th>
                    <th class="unit">PRICE WITHOUT VAT</th>
                    <th class="qty">QTY</th>
                    <th class="qty">VAT</th>
                    <th class="qty">VAT%</th>
                    <th class="unit">TOTAL WITHOUT VAT</th>
                    <th class="total">TOTAL WITH VAT</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="9">##productlist_start##</td>
                </tr>
                <tr>
                    <td class="no">
                        <p>{{var item.position}}</p>
                    </td>
                    <td class="desc">
                        <p>{{var item.name}}</p>
                        <p>{{var item.sku}}</p>
                        <p>{{var ea_barcode_c39_item.sku}}</p>
                        <p>{{var item.options}}</p>
                        <p>{{var item.additional_options}}</p>
                        <p>{{var item.attributes_info}}</p>
                    </td>
                    <td class="unit">
                        <p>{{var ea_item.price_incl_tax}}</p>
                    </td>
                    <td class="unit">
                        <p>{{var ea_item.price}}</p>
                    </td>
                    <td class="qty">
                        <p>{{var ea_item.qty}}</p>
                    </td>
                    <td class="qty">
                        <p>{{var ea_item.tax_amount}}</p>
                    </td>
                    <td class="qty">
                        <p>{{var item.tax_percent}}</p>
                    </td>
                    <td class="unit">
                        <p>{{var ea_item.row_total}}</p>
                    </td>
                    <td class="total">{{var ea_item.row_total_incl_tax}}</td>
                </tr>
                <tr>
                    <td colspan="9">##productlist_end##</td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="7">SUBTOTAL WITHOUT VAT</td>
                    <td>{{var ea_invoice.subtotal}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="7">SUBTOTAL</td>
                    <td>{{var ea_invoice.subtotal_incl_tax}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="7">SHIPPING TAX WITHOUT VAT</td>
                    <td>{{var ea_invoice.shipping_tax_amount}}{{depend invoice_if.base_shipping_amount}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="7">DEPEND SHIPPING TAX</td>
                    <td>{{var invoice.base_shipping_amount}}{{/depend}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="7">SHIPPING TAX WITH VAT</td>
                    <td>{{var ea_invoice.shipping_incl_tax}}{{depend invoice_if.base_shipping_amount}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="7">DEPEND SHIPPING TAX</td>
                    <td>{{var invoice.base_shipping_amount}}{{/depend}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="7">GRAND TOTAL WITHOUT VAT</td>
                    <td>{{var ea_invoice.subtotal}}</td>
                </tr>
                <tr>
                    <td style="border-top: 1px solid #f26522;" >&nbsp;</td>
                    <td class="grandtotal" style="color: #f26522; font-weight: bold; font-size: 1.6em; border-top: 1px solid #f26522;"
                        colspan="7">GRAND TOTAL
                    </td>
                    <td class="grandtotal" style="color: #f26522;  font-weight: bold; font-size: 1.6em; border-top: 1px solid #f26522;">
                        {{var ea_invoice.grand_total}}
                    </td>
                </tr>
                </tfoot>
            </table>
            <div id="thanks" style="font-size: 2em;  font-family: \'Libre Franklin\', sans-serif;">Thank you!</div>
        </div>
    </div>
</div>
</body>'
                ,
                'template_header' => '<div class="clearfix header" style="padding: 10px 0; margin-bottom: 15px; border-bottom: 1px solid #AAAAAA;">            <div id="logo" style="float: left; margin-top: 8px; width: 49%;"><img style="height: 70px;" alt=""                                                                                  src="https://www.eadesign.ro/skin/frontend/default/eadesignnew/images/logo-eadesign.png"/>            </div>            <div id="company" style="float: right; font-family: \'\'Libre Franklin\'\', sans-serif; text-align: right; width: 49%;">                <h2 class="name" style="font-size: 1.4em; font-weight: normal; margin: 0;">EaDesign Web Development</h2>                <div>Lascar Catargi nr.10, et.1,Iasi,Romania</div>                <div>0232 272221</div>                <div><a href="mailto:office@eadesign.ro">office@eadesign.ro</a></div>            </div>        </div>',
                'template_footer' => '
<h2>Dear {{var order.getCustomerName()}}</h2><h3>Thank you!</h3>', '<div style="text-align: center; color: #777777; border-top: 1px solid #AAAAAA;">Invoice was created on a computer and is valid without the signature and seal.</div><div style="text-align: center;">Page number {PAGENO}/{nbpg}. Call us at 0800 454 454 at eny time!</div>'
                ,
                'template_css' => '',
                'template_file_name' => 'invoice',
                'template_paper_form' => 2,
                'template_custom_form' => 0,
                'template_custom_h' => 25,
                'template_custom_w' => 25,
                'template_custom_t' => 45,
                'template_custom_b' => 0,
                'template_custom_l' => 5,
                'template_custom_r' => 10,
                'template_paper_ori' => 2,
                'barcode_types' => 'c39',
                'customer_group_id' => '0',
                'creation_time' => time(),
                'update_time' => time(),
            ],
            [
                'store_id' => $storeId,
                'is_active' => 1,
                'template_name' => 'Credit memo template Portrait!',
                'template_description' => 'Credit memo template',
                'template_default' => 1,
                'template_type' => 4,
                'template_body' => '<style>
    .clearfix:after {
        content: "";
        display: table;
        clear: both;
    }
    a {
        color: #000;
        text-decoration: none;
    }
    table thead .desc, table thead .qty, table thead .unit {
        color: #f26522;
        text-align: center;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px;
    }
    table thead th {
        font-weight: bold;
        text-transform: uppercase;
        padding-top: 5px;
        padding-bottom: 5px;
    }
    table tfoot {
        background: #f2f8ee;
        text-align: right;
    }
    table tfoot td, table tfoot th {
        padding: 10px 20px;
        background: #f2f8ee;
        border-bottom: none;
        font-size: 1em;
        white-space: nowrap;
        border-top: 1px solid #AAAAAA;
        text-align: right;
        text-transform: uppercase;
        font-weight: normal;
        font-family: \'Oswald\', sans-serif;
    }
    .no {
        text-align: left;
        background: #f26522;
        color: #f2f8ee;
        padding-top: 5px;
        padding-bottom: 5px;
        width: 5%;
        padding-left: 10px;
    }
    .qty{
        width: 10%;
        background: #f2f8ee;
        text-align: center;
    }
    .unit{
        width: 15%;
        background: #f2f8ee;
        text-align: center;
    }
    .desc{
        width: 40%;
        background: #f2f8ee;
        text-align: left;
        padding-left: 10px;
    }
    .total {
        text-align: right;
        background: #f26522;
        color: #f2f8ee;
        padding-right: 10px;
        border-top: 1px solid #AAAAAA;
        padding-top: 5px;
        padding-bottom: 5px;
        width: 30%;
    }
</style>
<body>
<div>
    <div class="body"
         style="position: relative;  margin: 0 auto; color: #555555; background: #FFFFFF; font-size: 14px;">
        <div class="main">
            <div id="details" class="clearfix" style="margin-bottom: 20px; font-family: \'Libre Franklin\', sans-serif;">
                <table id="client" style="width: 100%;">
                    <tr>
                        <td style="width:25%; padding-left: 6px; border-left: 6px solid #000; color: #777777;" class="to">REFUND TO:</td>
                        <td style="width:25%; padding-left: 6px; border-left: 6px solid #000; color: #777777;" class="to">BILLING TO:</td>
                        <td id="invoice" style="font-family: \'Oswald\', sans-serif; text-align: right; width: 50%; "><h1 style="color: #000; font-size: 2.4em; line-height: 1em; font-weight: normal; margin: 0  0 10px 0;">
                            REFUND {{var creditmemo.increment_id}}</h1></td>
                    </tr>
                    <tr>
                        <td class="name" style="padding-left: 6px; border-left: 6px solid #000;">{{var formattedBillingAddress|raw}}</td>
                        <td class="name" style="padding-left: 6px; border-left: 6px solid #000;">{{var formattedBillingAddress|raw}}</td>
                        <td style="text-align: right;" >
                            <div>{{var ea_barcode_c39_creditmemo.increment_id}}</div>
                            <div class="date" style="font-size: 1.1em; color: #777777;">Date of Order:{{var
                                order.created_at}}
                            </div>
                            <div class="date" style="font-size: 1.1em; color: #777777;">Status:{{var order.status}}</div>
                        </td>
                    </tr>
                </table>
            </div>
            <table style="width: 100%; font-family: \'Frank Ruhl Libre\', sans-serif; border-collapse: collapse; border-spacing: 0; margin-bottom: 20px;" border="0">
                <thead>
                <tr>
                    <th class="no">#</th>
                    <th class="desc">DESCRIPTION</th>
                    <th class="unit">PRICE</th>
                    <th class="qty">QTY</th>
                    <th class="total">TOTAL</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="5">##productlist_start##</td>
                </tr>
                <tr>
                    <td class="no">
                        <p>{{var item.position}}</p>
                    </td>
                    <td class="desc">
                        <p>{{var item.name}}</p>
                        <p>{{var item.sku}}</p>
                        <p>{{var ea_barcode_c39_item.sku}}</p>
                        <p>{{var item.options}}</p>
                        <p>{{var item.additional_options}}</p>
                        <p>{{var item.attributes_info}}</p>
                    </td>
                    <td class="unit">
                        <p>{{var ea_item.price}}</p>
                    </td>
                    <td class="qty">
                        <p>{{var ea_item.qty}}</p>
                    </td>
                    <td class="total">{{var ea_item.row_total}}</td>
                </tr>
                <tr>
                    <td colspan="5">##productlist_end##</td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="3">SUBTOTAL</td>
                    <td>{{var ea_creditmemo.subtotal}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="3">SHIPPING TAX</td>
                    <td>{{var ea_creditmemo.shipping_amount}}{{depend ea_creditmemo_if.base_shipping_amount}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="3">DEPEND SHIPPING TAX</td>
                    <td>{{var ea_creditmemo.base_shipping_amount}}{{/depend}}</td>
                </tr>
                <tr>
                    <td style="border-top: 1px solid #f26522;" >&nbsp;</td>
                    <td class="grandtotal" style="color: #f26522; font-weight: bold; font-size: 1.6em; border-top: 1px solid #f26522;"
                        colspan="3">GRAND TOTAL
                    </td>
                    <td class="grandtotal" style="color: #f26522;  font-weight: bold; font-size: 1.6em; border-top: 1px solid #f26522;">
                        {{var ea_creditmemo.grand_total}}
                    </td>
                </tr>
                </tfoot>
            </table>
            <div id="thanks" style="font-size: 2em;  font-family: \'Libre Franklin\', sans-serif;">Thank you!</div>
        </div>
    </div>
</div>
</body>',
                'template_header' => '<div class="clearfix header" style="padding: 10px 0; margin-bottom: 15px; border-bottom: 1px solid #AAAAAA;">            <div id="logo" style="float: left; margin-top: 8px; width: 49%;"><img style="height: 70px;" alt=""                                                                                  src="https://www.eadesign.ro/skin/frontend/default/eadesignnew/images/logo-eadesign.png"/>            </div>            <div id="company" style="float: right; font-family: \'\'Libre Franklin\'\', sans-serif; text-align: right; width: 49%;">                <h2 class="name" style="font-size: 1.4em; font-weight: normal; margin: 0;">EaDesign Web Development</h2>                <div>Lascar Catargi nr.10, et.1,Iasi,Romania</div>                <div>0232 272221</div>                <div><a href="mailto:office@eadesign.ro">office@eadesign.ro</a></div>            </div>        </div>',
                'template_footer' => '
<h2>Dear {{var order.getCustomerName()}}</h2><h3>Thank you!</h3>', '<div style="text-align: center; color: #777777; border-top: 1px solid #AAAAAA;">Invoice was created on a computer and is valid without the signature and seal.</div><div style="text-align: center;">Page number {PAGENO}/{nbpg}. Call us at 0800 454 454 at eny time!</div>'
                ,
                'template_css' => '',
                'template_file_name' => 'creditmemo',
                'template_paper_form' => 1,
                'template_custom_form' => 0,
                'template_custom_h' => 0,
                'template_custom_w' => 0,
                'template_custom_t' => 45,
                'template_custom_b' => 0,
                'template_custom_l' => 5,
                'template_custom_r' => 10,
                'template_paper_ori' => 1,
                'barcode_types' => 'c39',
                'customer_group_id' => '0',
                'creation_time' => time(),
                'update_time' => time(),
            ],
            [
                'store_id' => $storeId,
                'is_active' => 1,
                'template_name' => 'Order Template Portrait!',
                'template_description' => 'Order Template',
                'template_default' => 0,
                'template_type' => 2,
                'template_body' => '<style>
    .clearfix:after {
        content: "";
        display: table;
        clear: both;
    }
    a {
        color: #000;
        text-decoration: none;
    }
    table thead .desc, table thead .qty, table thead .unit {
        color: #f26522;
        text-align: center;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px;
    }
    table thead th {
        font-weight: bold;
        text-transform: uppercase;
        padding-top: 5px;
        padding-bottom: 5px;
    }
    table tfoot {
        background: #f2f8ee;
        text-align: right;
    }
    table tfoot td, table tfoot th {
        padding: 10px 20px;
        background: #f2f8ee;
        border-bottom: none;
        font-size: 1em;
        white-space: nowrap;
        border-top: 1px solid #AAAAAA;
        text-align: right;
        text-transform: uppercase;
        font-weight: normal;
        font-family: \'Oswald\', sans-serif;
    }
    .no {
        text-align: left;
        background: #f26522;
        color: #f2f8ee;
        padding-top: 5px;
        padding-bottom: 5px;
        width: 5%;
        padding-left: 10px;
    }
    .qty{
        width: 10%;
        background: #f2f8ee;
        text-align: center;
    }
    .unit{
        width: 15%;
        background: #f2f8ee;
        text-align: center;
    }
    .desc{
        width: 40%;
        background: #f2f8ee;
        text-align: left;
        padding-left: 10px;
    }
    .total {
        text-align: right;
        background: #f26522;
        color: #f2f8ee;
        padding-right: 10px;
        border-top: 1px solid #AAAAAA;
        padding-top: 5px;
        padding-bottom: 5px;
        width: 30%;
    }
</style>
<body>
<div>
    <div class="body"
         style="position: relative; width: 21cm; height: 29.7cm; margin: 0 auto; color: #555555; background: #FFFFFF; font-size: 14px;">
        <div class="main">
            <div id="details" class="clearfix" style="margin-bottom: 20px; font-family: \'Libre Franklin\', sans-serif;">
                <table id="client" style="width: 100%;">
                    <tr>
                        <td style="width:25%; padding-left: 6px; border-left: 6px solid #000; color: #777777;" class="to">ORDER TO:</td>
                        <td style="width:25%; padding-left: 6px; border-left: 6px solid #000; color: #777777;" class="to">BILLING TO:</td>
                        <td id="invoice" style="font-family: \'Oswald\', sans-serif; text-align: right; width: 50%; "><h1 style="color: #000; font-size: 2.4em; line-height: 1em; font-weight: normal; margin: 0  0 10px 0;">
                            ORDER {{var order.increment_id}}</h1></td>
                    </tr>
                    <tr>
                        <td class="name" style="padding-left: 6px; border-left: 6px solid #000;">{{var formattedBillingAddress|raw}}</td>
                        <td class="name" style="padding-left: 6px; border-left: 6px solid #000;">{{var formattedBillingAddress|raw}}</td>
                        <td style="text-align: right;" >
                            <div>{{var ea_barcode_c39_order.increment_id}}</div>
                            <div class="date" style="font-size: 1.1em; color: #777777;">Date of Order:{{var
                                order.created_at}}
                            </div>
                            <div class="date" style="font-size: 1.1em; color: #777777;">Status:{{var order.status}}</div>
                        </td>
                    </tr>
                </table>
            </div>
            <table style="width: 100%; font-family: \'Frank Ruhl Libre\', sans-serif; border-collapse: collapse; border-spacing: 0; margin-bottom: 20px;" border="0">
                <thead>
                <tr>
                    <th class="no">#</th>
                    <th class="desc">DESCRIPTION</th>
                    <th class="unit">PRICE</th>
                    <th class="qty">QTY</th>
                    <th class="total">TOTAL</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="5">##productlist_start##</td>
                </tr>
                <tr>
                    <td class="no">
                        <p>{{var item.position}}</p>
                    </td>
                    <td class="desc">
                        <p>{{var item.name}}</p>
                        <p>{{var item.sku}}</p>
                        <p>{{var ea_barcode_c39_item.sku}}</p>
                        <p>{{var item.options}}</p>
                        <p>{{var item.additional_options}}</p>
                        <p>{{var item.attributes_info}}</p>
                    </td>
                    <td class="unit">
                        <p>{{var ea_item.price}}</p>
                    </td>
                    <td class="qty">
                        <p> Ordered:{{var ea_item.qty_ordered}}</p>
                        <p> Invoiced:{{var ea_item.qty_invoiced}}</p>
                        <p> Shipped:{{var ea_item.qty_shipped}}</p>
                        <p> Refunded:{{var ea_item.qty_refunded}}</p>
                    </td>
                    <td class="total">{{var item.row_total}}</td>
                </tr>
                <tr>
                    <td colspan="5">##productlist_end##</td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="3">SUBTOTAL</td>
                    <td>{{var ea_order.subtotal}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="3">SHIPPING TAX</td>
                    <td>{{var ea_order.base_shipping_amount}}{{depend ea_order_if.base_shipping_amount}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="3">DEPEND SHIPPING TAX</td>
                    <td>{{var ea_order.base_shipping_amount}}{{/depend}}</td>
                </tr>
                <tr>
                    <td style="border-top: 1px solid #f26522;" >&nbsp;</td>
                    <td class="grandtotal" style="color: #f26522; font-weight: bold; font-size: 1.6em; border-top: 1px solid #f26522;"
                        colspan="3">GRAND TOTAL
                    </td>
                    <td class="grandtotal" style="color: #f26522;  font-weight: bold; font-size: 1.6em; border-top: 1px solid #f26522;">
                        {{var ea_order.grand_total}}
                    </td>
                </tr>
                </tfoot>
            </table>
            <div id="thanks" style="font-size: 2em;  font-family: \'Libre Franklin\', sans-serif;">Thank you!</div>
        </div>
    </div>
</div>
</body>',
                'template_header' => '<div class="clearfix header" style="padding: 10px 0; margin-bottom: 15px; border-bottom: 1px solid #AAAAAA;">            <div id="logo" style="float: left; margin-top: 8px; width: 49%;"><img style="height: 70px;" alt=""                                                                                  src="https://www.eadesign.ro/skin/frontend/default/eadesignnew/images/logo-eadesign.png"/>            </div>            <div id="company" style="float: right; font-family: \'\'Libre Franklin\'\', sans-serif; text-align: right; width: 49%;">                <h2 class="name" style="font-size: 1.4em; font-weight: normal; margin: 0;">EaDesign Web Development</h2>                <div>Lascar Catargi nr.10, et.1,Iasi,Romania</div>                <div>0232 272221</div>                <div><a href="mailto:office@eadesign.ro">office@eadesign.ro</a></div>            </div>        </div>',
                'template_footer' => '
<h2>Dear {{var order.getCustomerName()}}</h2><h3>Thank you!</h3>', '<div style="text-align: center; color: #777777; border-top: 1px solid #AAAAAA;">Invoice was created on a computer and is valid without the signature and seal.</div><div style="text-align: center;">Page number {PAGENO}/{nbpg}. Call us at 0800 454 454 at eny time!</div>'
                ,
                'template_css' => '',
                'template_file_name' => 'order',
                'template_paper_form' => 1,
                'template_custom_form' => 0,
                'template_custom_h' => 0,
                'template_custom_w' => 0,
                'template_custom_t' => 45,
                'template_custom_b' => 0,
                'template_custom_l' => 5,
                'template_custom_r' => 10,
                'template_paper_ori' => 1,
                'barcode_types' => 'c39',
                'customer_group_id' => '0',
                'creation_time' => time(),
                'update_time' => time(),
            ],
            [
                'store_id' => $storeId,
                'is_active' => 1,
                'template_name' => 'Shipping template Portrait!',
                'template_description' => 'Shipping template',
                'template_default' => 0,
                'template_type' => 3,
                'template_body' => '<style>
    #track {
        text-align: center;
    }
    .clearfix:after {
        content: "";
        display: table;
        clear: both;
    }
    a {
        color: #000;
        text-decoration: none;
    }
    table thead .desc, table thead .qty, table thead .unit {
        color: #f26522;
        text-align: center;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px;
    }
    table thead th {
        font-weight: bold;
        text-transform: uppercase;
        padding-top: 5px;
        padding-bottom: 5px;
    }
    table tfoot {
        background: #f2f8ee;
        text-align: right;
    }
    table tfoot td, table tfoot th {
        padding: 10px 20px;
        background: #f2f8ee;
        border-bottom: none;
        font-size: 1em;
        white-space: nowrap;
        border-top: 1px solid #AAAAAA;
        text-align: right;
        text-transform: uppercase;
        font-weight: normal;
        font-family: \'Oswald\', sans-serif;
    }
    .no {
        text-align: left;
        background: #f26522;
        color: #f2f8ee;
        padding-top: 5px;
        padding-bottom: 5px;
        width: 5%;
        padding-left: 10px;
    }
    .qty{
        width: 10%;
        background: #f2f8ee;
        text-align: center;
    }
    .unit{
        width: 15%;
        background: #f2f8ee;
        text-align: center;
    }
    .desc{
        width: 65%;
        background: #f2f8ee;
        text-align: left;
        padding-left: 10px;
    }
    .total {
        text-align: right;
        background: #f26522;
        color: #f2f8ee;
        padding-right: 10px;
        border-top: 1px solid #AAAAAA;
        padding-top: 5px;
        padding-bottom: 5px;
        width: 30%;
    }
</style>
<body>
<div>
    <div class="body"
         style="position: relative; margin: 0 auto; color: #555555; background: #FFFFFF; font-size: 14px;">
        <div class="main">
            <div id="details" class="clearfix" style="margin-bottom: 20px; font-family: \'Libre Franklin\', sans-serif;">
                <table id="client" style="width: 100%;">
                    <tr>
                        <td style="width:25%; padding-left: 6px; border-left: 6px solid #000; color: #777777;" class="to">SHIPPING TO:</td>
                        <td style="width:25%; padding-left: 6px; border-left: 6px solid #000; color: #777777;" class="to">BILLING TO:</td>
                        <td id="invoice" style="font-family: \'Oswald\', sans-serif; text-align: right; width: 50%; "><h1 style="color: #000; font-size: 2.4em; line-height: 1em; font-weight: normal; margin: 0  0 10px 0;">
                            SHIPMENT {{var shipment.increment_id}}</h1></td>
                    </tr>
                    <tr>
                        <td class="name" style="padding-left: 6px; border-left: 6px solid #000;">{{var formattedBillingAddress|raw}}</td>
                        <td class="name" style="padding-left: 6px; border-left: 6px solid #000;">{{var formattedBillingAddress|raw}}</td>
                        <td style="text-align: right;" >
                            <div>{{var ea_barcode_c39_shipment.increment_id}}</div>
                            <div class="date" style="font-size: 1.1em; color: #777777;">Date of Order:{{var
                                order.created_at}}
                            </div>
                            <div class="date" style="font-size: 1.1em; color: #777777;">Status:{{var order.status}}</div>
                        </td>
                    </tr>
                </table>
            </div>
            <table style="width: 100%; font-family: \'Frank Ruhl Libre\', sans-serif; border-collapse: collapse; border-spacing: 0; margin-bottom: 20px;" border="0">
                <thead>
                <tr>
                    <th class="no">#</th>
                    <th class="desc">DESCRIPTION</th>
                    <th class="total">QTY</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="3">##productlist_start##</td>
                </tr>
                <tr>
                    <td class="no">
                        <p>{{var item.position}}</p>
                    </td>
                    <td class="desc">
                        <p>{{var item.name}}</p>
                        <p>{{var item.sku}}</p>
                        <p>{{var ea_barcode_c39_item.sku}}</p>
                        <p>{{var item.options}}</p>
                        <p>{{var item.additional_options}}</p>
                        <p>{{var item.attributes_info}}</p>
                    </td>
                    <td class="total">
                        <p>{{var ea_item.qty}}</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">##productlist_end##</td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td>&nbsp;</td>
                    <td>SHIPPING METHOD:</td>
                    <td>{{var order.getShippingDescription()}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>TOTAL QTY</td>
                    <td>{{var ea_shipment.total_qty}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>TOTAL WEIGHT</td>
                    <td>{{var shipment.total_weight}}</td>
                </tr>
                <tr>
                    <td style="border-top: 1px solid #f26522;">&nbsp;</td>
                    <td colspan="2" id="track" style="color: #f26522; font-weight: bold; font-size: 1.6em; border-top: 1px solid #f26522;">SHIPMENT TRACK{{block class=\'Magento\Framework\View\Element\Template\' area=\'frontend\' template=\'Magento_Sales::email/shipment/track.phtml\' shipment=$shipment order=$order}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>SHIPPING TAX</td>
                    <td>{{var ea_order.base_shipping_amount}}{{depend order_if.base_shipping_amount}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>DEPEND SHIPPING TAX</td>
                    <td>{{var ea_order.base_shipping_amount}}{{/depend}}</td>
                </tr>
                </tfoot>
            </table>
            <div id="thanks" style="font-size: 2em;  font-family: \'Libre Franklin\', sans-serif;">Thank you!</div>
        </div>
    </div>
</div>
</body>',
                'template_header' => '<div class="clearfix header" style="padding: 10px 0; margin-bottom: 15px; border-bottom: 1px solid #AAAAAA;">            <div id="logo" style="float: left; margin-top: 8px; width: 49%;"><img style="height: 70px;" alt=""                                                                                  src="https://www.eadesign.ro/skin/frontend/default/eadesignnew/images/logo-eadesign.png"/>            </div>            <div id="company" style="float: right; font-family: \'\'Libre Franklin\'\', sans-serif; text-align: right; width: 49%;">                <h2 class="name" style="font-size: 1.4em; font-weight: normal; margin: 0;">EaDesign Web Development</h2>                <div>Lascar Catargi nr.10, et.1,Iasi,Romania</div>                <div>0232 272221</div>                <div><a href="mailto:office@eadesign.ro">office@eadesign.ro</a></div>            </div>        </div>',
                'template_footer' => '
<h2>Dear {{var order.getCustomerName()}}</h2><h3>Thank you!</h3>', '<div style="text-align: center; color: #777777; border-top: 1px solid #AAAAAA;">Invoice was created on a computer and is valid without the signature and seal.</div><div style="text-align: center;">Page number {PAGENO}/{nbpg}. Call us at 0800 454 454 at eny time!</div>'
                ,
                'template_css' => '',
                'template_file_name' => 'shipping',
                'template_paper_form' => 1,
                'template_custom_form' => 0,
                'template_custom_h' => 0,
                'template_custom_w' => 0,
                'template_custom_t' => 45,
                'template_custom_b' => 0,
                'template_custom_l' => 5,
                'template_custom_r' => 10,
                'template_paper_ori' => 1,
                'barcode_types' => 'c39',
                'customer_group_id' => '0',
                'creation_time' => time(),
                'update_time' => time(),
            ],
            [
                'store_id' => $storeId,
                'is_active' => 1,
                'template_name' => 'Credit memo landscape!',
                'template_description' => 'Credit memo template',
                'template_default' => 1,
                'template_type' => 4,
                'template_body' => '<style>

    .clearfix:after {
        content: "";
        display: table;
        clear: both;
    }
    a {
        color: #000;
        text-decoration: none;
    }
    table thead .desc, table thead .qty, table thead .unit {
        color: #f26522;
        text-align: center;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px;
    }
    table thead th {
        font-weight: bold;
        text-transform: uppercase;
        padding-top: 5px;
        padding-bottom: 5px;
    }
    table tfoot {
        background: #f2f8ee;
        text-align: right;
    }
    table tfoot td, table tfoot th {
        padding: 10px 20px;
        background: #f2f8ee;
        border-bottom: none;
        font-size: 1em;
        white-space: nowrap;
        border-top: 1px solid #AAAAAA;
        text-align: right;
        text-transform: uppercase;
        font-weight: normal;
        font-family: \'Oswald\', sans-serif;
    }
    .no {
        text-align: left;
        background: #f26522;
        color: #f2f8ee;
        padding-top: 5px;
        padding-bottom: 5px;
        width: 5%;
        padding-left: 10px;
    }
    .qty{
        width: 7%;
        background: #f2f8ee;
        text-align: center;
    }
    .unit{
        width: 10%;
        background: #f2f8ee;
        text-align: center;
    }
    .desc{
        width: 24%;
        background: #f2f8ee;
        text-align: left;
        padding-left: 10px;
    }
    .total {
        text-align: right;
        background: #f26522;
        color: #f2f8ee;
        padding-right: 10px;
        border-top: 1px solid #AAAAAA;
        padding-top: 5px;
        padding-bottom: 5px;
        width: 20%;
    }
</style>
<body>
<div>
    <div class="body"
         style="position: relative; margin: 0 auto; color: #555555; background: #FFFFFF; font-size: 14px;">
        <div class="main">
            <div id="details" class="clearfix" style="margin-bottom: 20px; font-family: \'Libre Franklin\', sans-serif;">
                <table id="client" style="width: 100%;">
                    <tr>
                        <td style="width:25%; padding-left: 6px; border-left: 6px solid #000; color: #777777;" class="to">REFUND TO:</td>
                        <td style="width:25%; padding-left: 6px; border-left: 6px solid #000; color: #777777;" class="to">BILLING TO:</td>
                        <td id="invoice" style="font-family: \'Oswald\', sans-serif; text-align: right; width: 50%; "><h1 style="color: #000; font-size: 2.4em; line-height: 1em; font-weight: normal; margin: 0  0 10px 0;">
                            REFUND {{var creditmemo.increment_id}}</h1></td>
                    </tr>
                    <tr>
                        <td class="name" style="padding-left: 6px; border-left: 6px solid #000;">{{var formattedBillingAddress|raw}}</td>
                        <td class="name" style="padding-left: 6px; border-left: 6px solid #000;">{{var formattedBillingAddress|raw}}</td>
                        <td style="text-align: right;" >
                            <div>{{var ea_barcode_c39_creditmemo.increment_id}}</div>
                            <div class="date" style="font-size: 1.1em; color: #777777;">Date of Order: {{var order.created_at}}
                            </div>
                            <div class="date" style="font-size: 1.1em; color: #777777;">Status:{{var order.status}}</div>
                        </td>
                    </tr>
                </table>
            </div>
            <table class="table" style="width: 100%; font-family: \'Frank Ruhl Libre\', sans-serif; border-collapse: collapse; border-spacing: 0; margin-bottom: 20px;" border="0">
                <thead>
                <tr>
                    <th class="no">#</th>
                    <th class="desc">DESCRIPTION</th>
                    <th class="unit">PRICE WITH VAT</th>
                    <th class="unit">PRICE WITHOUT VAT</th>
                    <th class="qty">QTY</th>
                    <th class="qty">VAT</th>
                    <th class="qty">VAT%</th>
                    <th class="unit">TOTAL WITHOUT VAT</th>
                    <th class="total">TOTAL WITH VAT</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="9">##productlist_start##</td>
                </tr>
                <tr>
                    <td class="no">
                        <p>{{var item.position}}</p>
                    </td>
                    <td class="desc">
                        <p>{{var item.name}}</p>
                        <p>{{var item.sku}}</p>
                        <p>{{var ea_barcode_c39_item.sku}}</p>
                        <p>{{var item.options}}</p>
                        <p>{{var item.additional_options}}</p>
                        <p>{{var item.attributes_info}}</p>
                    </td>
                    <td class="unit">
                        <p>{{var ea_item.price_incl_tax}}</p>
                    </td>
                    <td class="unit">
                        <p>{{var ea_item.price}}</p>
                    </td>
                    <td class="qty">
                        <p>{{var ea_item.qty}}</p>
                    </td>
                    <td class="qty">
                        <p>{{var ea_item.tax_amount}}</p>
                    </td>
                    <td class="qty">
                        <p>{{var item.tax_percent}}</p>
                    </td>
                    <td class="unit">
                        <p>{{var ea_item.row_total}}</p>
                    </td>
                    <td class="total">{{var ea_item.row_total_incl_tax}}</td>
                </tr>
                <tr>
                    <td colspan="9">##productlist_end##</td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="7">SUBTOTAL WITHOUT VAT</td>
                    <td>{{var ea_creditmemo.subtotal}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="7">SUBTOTAL</td>
                    <td>{{var ea_creditmemo.subtotal_incl_tax}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="7">SHIPPING TAX WITHOUT VAT</td>
                    <td>{{var ea_creditmemo.shipping_tax_amount}}{{depend creditmemo_if.base_shipping_amount}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="7">DEPEND SHIPPING TAX</td>
                    <td>{{var creditmemo.base_shipping_amount}}{{/depend}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="7">SHIPPING TAX WITH VAT</td>
                    <td>{{var ea_creditmemo.shipping_incl_tax}}{{depend creditmemo_if.base_shipping_amount}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="7">DEPEND SHIPPING TAX</td>
                    <td>{{var creditmemo.base_shipping_amount}}{{/depend}}</td>
                </tr>
                <tr>
                    <td style="border-top: 1px solid #f26522;" >&nbsp;</td>
                    <td class="grandtotal" style="color: #f26522; font-weight: bold; font-size: 1.6em; border-top: 1px solid #f26522;"
                        colspan="7">GRAND TOTAL
                    </td>
                    <td class="grandtotal" style="color: #f26522;  font-weight: bold; font-size: 1.6em; border-top: 1px solid #f26522;">
                        {{var ea_creditmemo.grand_total}}
                    </td>
                </tr>
                </tfoot>
            </table>
            <div id="thanks" style="font-size: 2em;  font-family: \'Libre Franklin\', sans-serif;">Thank you!</div>
        </div>
    </div>
</div>
</body>',
                'template_header' => '<div class="clearfix header" style="padding: 10px 0; margin-bottom: 15px; border-bottom: 1px solid #AAAAAA;">            <div id="logo" style="float: left; margin-top: 8px; width: 49%;"><img style="height: 70px;" alt=""                                                                                  src="https://www.eadesign.ro/skin/frontend/default/eadesignnew/images/logo-eadesign.png"/>            </div>            <div id="company" style="float: right; font-family: \'\'Libre Franklin\'\', sans-serif; text-align: right; width: 49%;">                <h2 class="name" style="font-size: 1.4em; font-weight: normal; margin: 0;">EaDesign Web Development</h2>                <div>Lascar Catargi nr.10, et.1,Iasi,Romania</div>                <div>0232 272221</div>                <div><a href="mailto:office@eadesign.ro">office@eadesign.ro</a></div>            </div>        </div>',
                'template_footer' => '
<h2>Dear {{var order.getCustomerName()}}</h2><h3>Thank you!</h3>', '<div style="text-align: center; color: #777777; border-top: 1px solid #AAAAAA;">Invoice was created on a computer and is valid without the signature and seal.</div><div style="text-align: center;">Page number {PAGENO}/{nbpg}. Call us at 0800 454 454 at eny time!</div>'
                ,
                'template_css' => '',
                'template_file_name' => 'creditmemo',
                'template_paper_form' => 1,
                'template_custom_form' => 0,
                'template_custom_h' => 25,
                'template_custom_w' => 25,
                'template_custom_t' => 45,
                'template_custom_b' => 0,
                'template_custom_l' => 5,
                'template_custom_r' => 10,
                'template_paper_ori' => 2,
                'barcode_types' => 'c39',
                'customer_group_id' => '0',
                'creation_time' => time(),
                'update_time' => time(),
            ],
            [
                'store_id' => $storeId,
                'is_active' => 1,
                'template_name' => 'Order Landscape!',
                'template_description' => 'Order template',
                'template_default' => 1,
                'template_type' => 2,
                'template_body' => '<style>
    .clearfix:after {
        content: "";
        display: table;
        clear: both;
    }
    a {
        color: #000;
        text-decoration: none;
    }
    table thead .desc, table thead .qty, table thead .unit {
        color: #f26522;
        text-align: center;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px;
    }
    table thead th {
        font-weight: bold;
        text-transform: uppercase;
        padding-top: 5px;
        padding-bottom: 5px;
    }
    table tfoot {
        background: #f2f8ee;
        text-align: right;
    }
    table tfoot td, table tfoot th {
        padding: 10px 20px;
        background: #f2f8ee;
        border-bottom: none;
        font-size: 1em;
        white-space: nowrap;
        border-top: 1px solid #AAAAAA;
        text-align: right;
        text-transform: uppercase;
        font-weight: normal;
        font-family: \'Oswald\', sans-serif;
    }
    .no {
        text-align: left;
        background: #f26522;
        color: #f2f8ee;
        padding-top: 5px;
        padding-bottom: 5px;
        width: 5%;
        padding-left: 10px;
    }
    .qty{
        width: 7%;
        background: #f2f8ee;
        text-align: center;
    }
    .unit{
        width: 10%;
        background: #f2f8ee;
        text-align: center;
    }
    .desc{
        width: 24%;
        background: #f2f8ee;
        text-align: left;
        padding-left: 10px;
    }
    .total {
        text-align: right;
        background: #f26522;
        color: #f2f8ee;
        padding-right: 10px;
        border-top: 1px solid #AAAAAA;
        padding-top: 5px;
        padding-bottom: 5px;
        width: 20%;
    }
</style>
<body>
<div>
    <div class="body"
         style="position: relative; margin: 0 auto; color: #555555; background: #FFFFFF; font-size: 14px;">
        <div class="main">
            <div id="details" class="clearfix" style="margin-bottom: 20px; font-family: \'Libre Franklin\', sans-serif;">
                <table id="client" style="width: 100%;">
                    <tr>
                        <td style="width:25%; padding-left: 6px; border-left: 6px solid #000; color: #777777;" class="to">ORDER TO:</td>
                        <td style="width:25%; padding-left: 6px; border-left: 6px solid #000; color: #777777;" class="to">BILLING TO:</td>
                        <td id="invoice" style="font-family: \'Oswald\', sans-serif; text-align: right; width: 50%; "><h1 style="color: #000; font-size: 2.4em; line-height: 1em; font-weight: normal; margin: 0  0 10px 0;">
                            ORDER {{var order.increment_id}}</h1></td>
                    </tr>
                    <tr>
                        <td class="name" style="padding-left: 6px; border-left: 6px solid #000;">{{var formattedBillingAddress|raw}}</td>
                        <td class="name" style="padding-left: 6px; border-left: 6px solid #000;">{{var formattedBillingAddress|raw}}</td>
                        <td style="text-align: right;" >
                            <div>{{var ea_barcode_c39_order.increment_id}}</div>
                            <div class="date" style="font-size: 1.1em; color: #777777;">Date of Order:{{var
                                order.created_at}}
                            </div>
                            <div class="date" style="font-size: 1.1em; color: #777777;">Status:{{var order.status}}</div>
                        </td>
                    </tr>
                </table>
            </div>
            <table class="table" style="width: 100%; font-family: \'Frank Ruhl Libre\', sans-serif; border-collapse: collapse; border-spacing: 0; margin-bottom: 20px;" border="0">
                <thead>
                <tr>
                    <th class="no">#</th>
                    <th class="desc">DESCRIPTION</th>
                    <th class="unit">PRICE WITH VAT</th>
                    <th class="unit">PRICE WITHOUT VAT</th>
                    <th class="qty">QTY</th>
                    <th class="qty">VAT</th>
                    <th class="qty">VAT%</th>
                    <th class="unit">TOTAL WITHOUT VAT</th>
                    <th class="total">TOTAL WITH VAT</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="9">##productlist_start##</td>
                </tr>
                <tr>
                    <td class="no">
                        <p>{{var item.position}}</p>
                    </td>
                    <td class="desc">
                        <p>{{var item.name}}</p>
                        <p>{{var item.sku}}</p>
                        <p>{{var ea_barcode_c39_item.sku}}</p>
                        <p>{{var item.options}}</p>
                        <p>{{var item.additional_options}}</p>
                        <p>{{var item.attributes_info}}</p>
                    </td>
                    <td class="unit">
                        <p>{{var ea_item.price_incl_tax}}</p>
                    </td>
                    <td class="unit">
                        <p>{{var ea_item.price}}</p>
                    </td>
                    <td class="qty">
                        <p> Ordered:{{var ea_item.qty_ordered}}</p>
                        <p> Invoiced:{{var ea_item.qty_invoiced}}</p>
                        <p> Shipped:{{var ea_item.qty_shipped}}</p>
                        <p> Refunded:{{var ea_item.qty_refunded}}</p>
                    </td>
                    <td class="qty">
                        <p>{{var ea_item.tax_amount}}</p>
                    </td>
                    <td class="qty">
                        <p>{{var item.tax_percent}}</p>
                    </td>
                    <td class="unit">
                        <p>{{var ea_item.base_row_total}}</p>
                    </td>
                    <td class="total">{{var ea_item.base_row_total_incl_tax}}</td>
                </tr>
                <tr>
                    <td colspan="9">##productlist_end##</td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="7">SUBTOTAL WITHOUT VAT</td>
                    <td>{{var ea_order.subtotal}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="7">SUBTOTAL</td>
                    <td>{{var ea_order.subtotal_incl_tax}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="7">SHIPPING TAX WITHOUT VAT</td>
                    <td>{{var ea_order.shipping_tax_amount}}{{depend order_if.base_shipping_amount}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="7">DEPEND SHIPPING TAX</td>
                    <td>{{var order.base_shipping_amount}}{{/depend}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="7">SHIPPING TAX WITH VAT</td>
                    <td>{{var ea_order.shipping_incl_tax}}{{depend order_if.base_shipping_amount}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="7">DEPEND SHIPPING TAX</td>
                    <td>{{var order.base_shipping_amount}}{{/depend}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td colspan="7">GRAND TOTAL WITHOUT VAT</td>
                    <td>{{var ea_order.subtotal}}</td>
                </tr>
                <tr>
                    <td style="border-top: 1px solid #f26522;" >&nbsp;</td>
                    <td class="grandtotal" style="color: #f26522; font-weight: bold; font-size: 1.6em; border-top: 1px solid #f26522;"
                        colspan="7">GRAND TOTAL
                    </td>
                    <td class="grandtotal" style="color: #f26522;  font-weight: bold; font-size: 1.6em; border-top: 1px solid #f26522;">
                        {{var ea_order.grand_total}}
                    </td>
                </tr>
                </tfoot>
            </table>
            <div id="thanks" style="font-size: 2em;  font-family: \'Libre Franklin\', sans-serif;">Thank you!</div>
        </div>
    </div>
</div>
</body>',
                'template_header' => '<div class="clearfix header" style="padding: 10px 0; margin-bottom: 15px; border-bottom: 1px solid #AAAAAA;">            <div id="logo" style="float: left; margin-top: 8px; width: 49%;"><img style="height: 70px;" alt=""                                                                                  src="https://www.eadesign.ro/skin/frontend/default/eadesignnew/images/logo-eadesign.png"/>            </div>            <div id="company" style="float: right; font-family: \'\'Libre Franklin\'\', sans-serif; text-align: right; width: 49%;">                <h2 class="name" style="font-size: 1.4em; font-weight: normal; margin: 0;">EaDesign Web Development</h2>                <div>Lascar Catargi nr.10, et.1,Iasi,Romania</div>                <div>0232 272221</div>                <div><a href="mailto:office@eadesign.ro">office@eadesign.ro</a></div>            </div>        </div>',
                'template_footer' => '
<h2>Dear {{var order.getCustomerName()}}</h2><h3>Thank you!</h3>', '<div style="text-align: center; color: #777777; border-top: 1px solid #AAAAAA;">Invoice was created on a computer and is valid without the signature and seal.</div><div style="text-align: center;">Page number {PAGENO}/{nbpg}. Call us at 0800 454 454 at eny time!</div>'
                ,
                'template_css' => '',
                'template_file_name' => 'order',
                'template_paper_form' => 1,
                'template_custom_form' => 0,
                'template_custom_h' => 25,
                'template_custom_w' => 25,
                'template_custom_t' => 45,
                'template_custom_b' => 0,
                'template_custom_l' => 5,
                'template_custom_r' => 10,
                'template_paper_ori' => 2,
                'barcode_types' => 'c39',
                'customer_group_id' => '0',
                'creation_time' => time(),
                'update_time' => time(),
            ],
            [
                'store_id' => $storeId,
                'is_active' => 1,
                'template_name' => 'Shippment landscape!',
                'template_description' => 'Shippment template',
                'template_default' => 1,
                'template_type' => 3,
                'template_body' => '<style>
    #track {
        text-align: center;
    }
    .clearfix:after {
        content: "";
        display: table;
        clear: both;
    }
    a {
        color: #000;
        text-decoration: none;
    }
    table thead .desc, table thead .qty, table thead .unit {
        color: #f26522;
        text-align: center;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px;
    }
    table thead th {
        font-weight: bold;
        text-transform: uppercase;
        padding-top: 5px;
        padding-bottom: 5px;
    }
    table tfoot {
        background: #f2f8ee;
        text-align: right;
    }
    table tfoot td, table tfoot th {
        padding: 10px 20px;
        background: #f2f8ee;
        border-bottom: none;
        font-size: 1em;
        white-space: nowrap;
        border-top: 1px solid #AAAAAA;
        text-align: right;
        text-transform: uppercase;
        font-weight: normal;
        font-family: \'Oswald\', sans-serif;
    }
    .no {
        text-align: left;
        background: #f26522;
        color: #f2f8ee;
        padding-top: 5px;
        padding-bottom: 5px;
        width: 5%;
        padding-left: 10px;
    }
    .qty{
        width: 10%;
        background: #f2f8ee;
        text-align: center;
    }
    .unit{
        width: 15%;
        background: #f2f8ee;
        text-align: center;
    }
    .desc{
        width: 65%;
        background: #f2f8ee;
        text-align: left;
        padding-left: 10px;
    }
    .total {
        text-align: right;
        background: #f26522;
        color: #f2f8ee;
        padding-right: 10px;
        border-top: 1px solid #AAAAAA;
        padding-top: 5px;
        padding-bottom: 5px;
        width: 30%;
    }
</style>
<body>
<div>
    <div class="body"
         style="position: relative; margin: 0 auto; color: #555555; background: #FFFFFF; font-size: 14px;">
        <div class="main">
            <div id="details" class="clearfix" style="margin-bottom: 20px; font-family: \'Libre Franklin\', sans-serif;">
                <table id="client" style="width: 100%;">
                    <tr>
                        <td style="width:25%; padding-left: 6px; border-left: 6px solid #000; color: #777777;" class="to">SHIPPING TO:</td>
                        <td style="width:25%; padding-left: 6px; border-left: 6px solid #000; color: #777777;" class="to">BILLING TO:</td>
                        <td id="invoice" style="font-family: \'Oswald\', sans-serif; text-align: right; width: 50%; "><h1 style="color: #000; font-size: 2.4em; line-height: 1em; font-weight: normal; margin: 0  0 10px 0;">
                            SHIPMENT {{var shipment.increment_id}}</h1></td>
                    </tr>
                    <tr>
                        <td class="name" style="padding-left: 6px; border-left: 6px solid #000;">{{var formattedBillingAddress|raw}}</td>
                        <td class="name" style="padding-left: 6px; border-left: 6px solid #000;">{{var formattedBillingAddress|raw}}</td>
                        <td style="text-align: right;" >
                            <div>{{var ea_barcode_c39_shipment.increment_id}}</div>
                            <div class="date" style="font-size: 1.1em; color: #777777;">Date of Order:{{var
                                order.created_at}}
                            </div>
                            <div class="date" style="font-size: 1.1em; color: #777777;">Status:{{var order.status}}</div>
                        </td>
                    </tr>
                </table>
            </div>
            <table style="width: 100%; font-family: \'Frank Ruhl Libre\', sans-serif; border-collapse: collapse; border-spacing: 0; margin-bottom: 20px;" border="0">
                <thead>
                <tr>
                    <th class="no">#</th>
                    <th class="desc">DESCRIPTION</th>
                    <th class="total">QTY</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="3">##productlist_start##</td>
                </tr>
                <tr>
                    <td class="no">
                        <p>{{var item.position}}</p>
                    </td>
                    <td class="desc">
                        <p>{{var item.name}}</p>
                        <p>{{var item.sku}}</p>
                        <p>{{var ea_barcode_c39_item.sku}}</p>
                        <p>{{var item.options}}</p>
                        <p>{{var item.additional_options}}</p>
                        <p>{{var item.attributes_info}}</p>
                    </td>
                    <td class="total">
                        <p>{{var ea_item.qty}}</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">##productlist_end##</td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td>&nbsp;</td>
                    <td>SHIPPING METHOD:</td>
                    <td>{{var order.getShippingDescription()}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>TOTAL QTY</td>
                    <td>{{var ea_shipment.total_qty}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>TOTAL WEIGHT</td>
                    <td>{{var shipment.total_weight}}</td>
                </tr>
                <tr>
                    <td style="border-top: 1px solid #f26522;">&nbsp;</td>
                    <td colspan="2" id="track" style="color: #f26522; font-weight: bold; font-size: 1.6em; border-top: 1px solid #f26522;">SHIPMENT TRACK{{block class=\'Magento\Framework\View\Element\Template\' area=\'frontend\' template=\'Magento_Sales::email/shipment/track.phtml\' shipment=$shipment order=$order}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>SHIPPING TAX</td>
                    <td>{{var ea_order.base_shipping_amount}}{{depend order_if.base_shipping_amount}}</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>DEPEND SHIPPING TAX</td>
                    <td>{{var ea_order.base_shipping_amount}}{{/depend}}</td>
                </tr>
                </tfoot>
            </table>
            <div id="thanks" style="font-size: 2em;  font-family: \'Libre Franklin\', sans-serif;">Thank you!</div>
        </div>
    </div>
</div>
</body>',
                'template_header' => '<div class="clearfix header" style="padding: 10px 0; margin-bottom: 15px; border-bottom: 1px solid #AAAAAA;">            <div id="logo" style="float: left; margin-top: 8px; width: 49%;"><img style="height: 70px;" alt=""                                                                                  src="https://www.eadesign.ro/skin/frontend/default/eadesignnew/images/logo-eadesign.png"/>            </div>            <div id="company" style="float: right; font-family: \'\'Libre Franklin\'\', sans-serif; text-align: right; width: 49%;">                <h2 class="name" style="font-size: 1.4em; font-weight: normal; margin: 0;">EaDesign Web Development</h2>                <div>Lascar Catargi nr.10, et.1,Iasi,Romania</div>                <div>0232 272221</div>                <div><a href="mailto:office@eadesign.ro">office@eadesign.ro</a></div>            </div>        </div>',
                'template_footer' => '
<h2>Dear {{var order.getCustomerName()}}</h2><h3>Thank you!</h3>', '<div style="text-align: center; color: #777777; border-top: 1px solid #AAAAAA;">Invoice was created on a computer and is valid without the signature and seal.</div><div style="text-align: center;">Page number {PAGENO}/{nbpg}. Call us at 0800 454 454 at eny time!</div>'
                ,
                'template_css' => '',
                'template_file_name' => 'shipping',
                'template_paper_form' => 1,
                'template_custom_form' => 0,
                'template_custom_h' => 25,
                'template_custom_w' => 25,
                'template_custom_t' => 45,
                'template_custom_b' => 0,
                'template_custom_l' => 5,
                'template_custom_r' => 10,
                'template_paper_ori' => 2,
                'barcode_types' => 'c39',
                'customer_group_id' => '0',
                'creation_time' => time(),
                'update_time' => time(),
            ]
        ];

        foreach ($templates as $template) {
            $tmpl = $this->templateFactory->create();
            $tmpl->setData($template);
            $this->templateRepository->save($tmpl);
        }
    }

}