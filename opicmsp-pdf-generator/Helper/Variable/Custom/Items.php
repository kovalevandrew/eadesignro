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

namespace Eadesigndev\Opicmsppdfgenerator\Helper\Variable\Custom;

use Eadesigndev\Opicmsppdfgenerator\Helper\Variable\Custom\AbstractCustomHelper;
use Eadesigndev\Opicmsppdfgenerator\Helper\Variable\Custom\CustomInterface;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Sales\Model\Order\Item;

class Items implements CustomInterface
{

    /**
     * @var Object
     */
    private $source;

    /**
     * @param $source
     * @return $this
     */
    public function entity($source)
    {
        if (is_object($source)) {
            $this->source = $source;
            return $this;
        }

        $this->addTaxPercent();
    }

    /**
     * @return Object
     */
    public function processAndReadVariables()
    {
        $this->addTaxPercent();
        $this->addItemOptions();
        return $this->source;
    }

    /**
     * @return Item|Object
     */
    private function addTaxPercent()
    {
        if (!$this->source instanceof Item) {
            $orderItem = $this->source->getOrderItem();
        } else {
            $orderItem = $this->source;
        }

        $taxPercent = number_format($orderItem->getTaxPercent(), 2);

        $this->source->setData(
            OrderItemInterface::TAX_PERCENT,
            $taxPercent
        );

        return $this->source;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    private function addItemOptions()
    {
        if (!$this->source instanceof Item) {
            $orderItem = $this->source->getOrderItem();
        } else {
            $orderItem = $this->source;
        }

        $result = [];
        if ($options = $orderItem->getProductOptions()) {
            if (isset($options['options'])) {
                $result = array_merge($result, $options['options']);
            }
            if (isset($options['additional_options'])) {
                $result = array_merge($result, $options['additional_options']);
            }
            if (isset($options['attributes_info'])) {
                $result = array_merge($result, $options['attributes_info']);
            }
        }

        $data = '';

        if (!empty($result)) {
            foreach ($result as $option => $value) {
                $data .= $value['label'] . ' - ' . $value['value'] . '<br>';
            }

            $this->source->setData(
                'item_options',
                $data
            );
        }

        $this->source->setData(
            'item_options',
            $data
        );
    }
}
