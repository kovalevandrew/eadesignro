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

use Eadesigndev\Opicmsppdfgenerator\Helper\AbstractPDF;
use Eadesigndev\Opicmsppdfgenerator\Helper\Variable\Custom\Items as CustomItems;
use Eadesigndev\Opicmsppdfgenerator\Helper\Variable\Custom\Product as CustomProduct;
use Eadesigndev\Opicmsppdfgenerator\Helper\Variable\Formated;
use Eadesigndev\Pdfgenerator\Model\Template\Processor;
use Magento\Catalog\Model\Product\Type;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\DataObject\Factory as DataObject;
use Magento\Sales\Model\Order\Item;

/**
 * Class Items
 * @package Eadesigndev\Opicmsppdfgenerator\Helper\Variable\Processors
 * @SuppressWarnings(CouplingBetweenObjects)
 */
class Items extends AbstractHelper
{
    /**
     * @var Formated
     */
    private $formated;

    /**
     * @var CustomItems
     */
    private $customData;

    /**
     * @var Processor
     */
    public $processor;

    /**
     * @var DataObject
     */
    private $dataObject;

    /**
     * @var CustomProduct
     */
    private $customProduct;

    /**
     * Items constructor.
     * @param Context $context
     * @param Processor $processor
     * @param Formated $formated
     * @param CustomItems $customData
     * @param DataObject $dataObject
     * @param CustomProduct $customProduct
     */
    public function __construct(
        Context $context,
        Processor $processor,
        Formated $formated,
        CustomItems $customData,
        DataObject $dataObject,
        CustomProduct $customProduct
    ) {
        $this->formated = $formated;
        $this->customData = $customData;
        $this->processor = $processor;
        $this->dataObject = $dataObject;
        $this->customProduct = $customProduct;
        parent::__construct($context);
    }

    /**
     * @param $standardItem
     * @param $template
     * @return string
     */
    public function variableItemProcessor($standardItem, $template)
    {

        $item = $this->customData->entity($standardItem)->processAndReadVariables();

        /** @var Item $orderItem */
        $orderItem = $this->orderItem($item);
        $orderItemProduct = $orderItem->getProduct();

        $orderItemProduct = $this->customProduct->entity($orderItemProduct)->processAndReadVariables();

        $transport = [
            'item' => $item,
            'ea_item' => $this->formated->getFormated($item),
            'ea_item_if' => $this->formated->getZeroFormated($item),
            'order.item' => $orderItem,
            'order.ea_item' => $this->formated->getFormated($orderItem),
            'order.ea_item_if' => $this->formated->getZeroFormated($orderItem),
            'order_item_product' => $orderItemProduct,
            'order_ea_item_product' => $this->formated->getFormated($orderItemProduct),
            'order_ea_item_product_if' => $this->formated->getZeroFormated($orderItemProduct),
        ];

        foreach (AbstractPDF::CODE_BAR as $code) {
            $transport['ea_barcode_' . $code . '_item'] = $this->formated->getBarcodeFormated(
                $item,
                $code
            );
            $transport['ea_barcode_' . $code . '_order.item'] = $this->formated->getBarcodeFormated(
                $orderItem,
                $code
            );
            $transport['ea_barcode_' . $code . '_order_item_product'] = $this->formated->getBarcodeFormated(
                $orderItem,
                $code
            );
        }

        $processor = $this->processor;

        $processor->setVariables($transport);
        $processor->setTemplate($template);

        $parts = $processor->processTemplate();

        return $parts;
    }

    /**
     * @param $source
     * @param $templateModel
     * @return string
     */
    public function processItems($source, $templateModel)
    {

        $items = $source->getItems();

        $this->formated->applySourceOrder($source);

        $templateBodyParts = $this->formated->getItemsArea(
            $templateModel->getData('template_body'),
            '##productlist_start##',
            '##productlist_end##'
        );
        $itemHtml = '';

        $i = 1;
        foreach ($items as $item) {
            $item->setData('position', $i++);

            if ($item instanceof Item) {
                if ($parentItem = $item->getParentItem()) {
                    if ($parentItem->getData('product_type') != Type::TYPE_BUNDLE) {
                        continue;
                    } else {
                        $item->setData('position', '');
                    }
                }
            } else {
                if ($parentItem = $item->getOrderItem()->getParentItem()) {
                    if ($parentItem->getData('product_type')  != Type::TYPE_BUNDLE) {
                        continue;
                    }
                    $item->setData('position', '');
                }
            }

            $itemBodyParts = $this->dataObject->create(['template_body' => $templateBodyParts[1]]);

            $processedItem = $this->variableItemProcessor($item, $itemBodyParts);
            $itemHtml .= $processedItem['body'];
        }

        $template = $templateBodyParts[0] . $itemHtml . $templateBodyParts[2];

        return $template;
    }

    /**
     * @param $item
     * @return mixed
     */
    private function orderItem($item)
    {
        if (!$item instanceof Item) {
            $orderItem = $item->getOrderItem();
            $item = $this->customData->entity($orderItem)->processAndReadVariables();
            return $item;
        }

        return $item;
    }
}
