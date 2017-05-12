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

namespace Eadesigndev\Opicmsppdfgenerator\Helper\Variable\Processors;

use Eadesigndev\Opicmsppdfgenerator\Helper\Variable\Formated;
use Eadesigndev\Opicmsppdfgenerator\Model\Source\TemplateType;
use Eadesigndev\Pdfgenerator\Model\Template\Processor;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Helper\Context;
use Eadesigndev\Opicmsppdfgenerator\Helper\AbstractPDF;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Payment\Helper\Data;
use Magento\Sales\Model\Order\Address\Renderer;
use Magento\Sales\Model\Order\Email\Container\InvoiceIdentity;

/**
 * Class Pdf
 * Process the variable so they are configured for pdf output
 *
 * @package Eadesigndev\Opicmsppdfgenerator\Helper
 * @SuppressWarnings(CouplingBetweenObjects)
 */
class Pdf extends AbstractPDF
{

    /**
     * @var File
     */
    public $file;

    /**
     * @var DirectoryList
     */
    public $directoryList;

    /**
     * @var Formated
     */
    private $formated;

    /**
     * @var Items
     */
    private $items;

    /**
     * Pdf constructor.
     * @param Context $context
     * @param File $file
     * @param DirectoryList $directoryList
     * @param Processor $processor
     * @param Data $paymentHelper
     * @param InvoiceIdentity $identityContainer
     * @param Renderer $addressRenderer
     * @param Formated $formated
     * @param Items $items
     */
    public function __construct(
        Context $context,
        File $file,
        DirectoryList $directoryList,
        Processor $processor,
        Data $paymentHelper,
        InvoiceIdentity $identityContainer,
        Renderer $addressRenderer,
        Formated $formated,
        Items $items
    ) {
        $this->file = $file;
        $this->directoryList = $directoryList;
        $this->formated = $formated;
        $this->items = $items;
        parent::__construct($context, $processor, $paymentHelper, $identityContainer, $addressRenderer);
    }

    /**
     * Filename of the pdf and the stream to sent to the download
     *
     * @return array
     */
    public function template2Pdf()
    {
        $source = $this->source;
        $templateModel = $this->template;

        $this->formated->applySourceOrder($source);

        $itemHtml = $this->items->processItems($source, $templateModel);

        $templateModel->setData('template_body', $itemHtml);

        /**transport use to get the variables $order object, $source object and the template model object*/
        $parts = $this->_transport();

        /** instantiate the mPDF class and add the processed html to get the pdf*/

        /** @var Output $applySettings */
        $applySettings = $this->_eapdfSettings($parts);

        $fileParts = [
            'filestream' => $applySettings,
            'filename' => filter_var($parts['filename'], FILTER_SANITIZE_URL)
        ];

        return $fileParts;
    }

    /**
     * This will process the template and the variables from the entity's
     *
     * @return string
     */
    private function _transport()
    {
        $order = $this->order;
        $source = $this->source;

        $templateModel = $this->template;
        $templateType = $templateModel->getData('template_type');

        $templateTypeName = TemplateType::TYPES[$templateType];

        $transport = [
            'order' => $order,
            $templateTypeName => $source,
            'customer' => $this->customer,
            'comment' => $source->getCustomerNoteNotify() ? $source->getCustomerNote() : '',
            'billing' => $order->getBillingAddress(),
            'payment_html' => $this->getPaymentHtml($order),
            'store' => $order->getStore(),
            'formattedShippingAddress' => $this->getFormattedShippingAddress($order),
            'formattedBillingAddress' => $this->getFormattedBillingAddress($order),

            'ea_' . $templateTypeName => $this->formated->getFormated($source),
            'ea_order' => $this->formated->getFormated($order),
            $templateTypeName . '_if' => $this->formated->getZeroFormated($source),
            'order_if' => $this->formated->getZeroFormated($order),

            'customer_if' => $this->formated->getZeroFormated($this->customer),

        ];

        foreach (AbstractPDF::CODE_BAR as $code) {
            $transport['ea_barcode_' . $code . '_' . $templateTypeName] = $this->formated->getBarcodeFormated(
                $source,
                $code
            );
            $transport['ea_barcode_' . $code . '_order'] = $this->formated->getBarcodeFormated($order, $code);
            $transport['ea_barcode_' . $code . '_customer'] = $this->formated->getBarcodeFormated(
                $this->customer,
                $code
            );
        }

        /** @var Processor $processor */
        $processor = $this->processor;

        $processor->setVariables($transport);
        $processor->setTemplate($this->template);

        $parts = $processor->processTemplate();

        return $parts;
    }
}
