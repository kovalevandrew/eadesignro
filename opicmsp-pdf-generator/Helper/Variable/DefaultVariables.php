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

namespace Eadesigndev\Opicmsppdfgenerator\Helper\Variable;

use Magento\Catalog\Model\Product;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\DataObject;
use Magento\Sales\Model\Order\Item;

/**
 * Handles the default system data coming from the source and generates the variables
 *
 * Class Data
 * @package Eadesigndev\Pdfgenerator\Helper
 */
class DefaultVariables extends AbstractHelper
{
    /**
     * @var
     * the source for the variables
     */
    private $source;

    /**
     * @var
     * the type for the variables
     */
    private $type;

    /**
     * @param $source
     * @param $type
     * @return $this
     */
    public function setSourceType($source, $type)
    {
        $this->source = $source;
        $this->type = $type;

        return $this;
    }

    /**
     * @return array
     */
    public function getSourceDefault()
    {

        $data = $this->source->getData();
        $groupName = __('Source Default Variables');
        $sourceVariables = $this->getVariablesOptionArray($groupName, $data, $this->type . '.', true);

        return $sourceVariables;
    }

    /**
     * @param $barcodes
     * @return array
     */
    public function getBarcodeDefault($barcodes)
    {
        $data = $this->source->getData();
        $groupName = __('Source Barcode Variables');
        $sourceVariables = $this->getBarCodeVariables(
            $groupName,
            $data,
            'ea_barcode_',
            '_' . $this->type . '.',
            $barcodes,
            true
        );

        return $sourceVariables;
    }

    /**
     * @return array
     */
    public function getDependDefault()
    {
        $data = $this->source->getData();

        $variableData = [];

        foreach ($data as $dat => $val) {
            if (is_numeric($val)) {
                $variableData[$dat] = $val;
            } else {
                continue;
            }
        }

        $groupName = __('Source Depend Variables');
        $sourceVariables = $this->getDependCurrencyOptionArray($groupName, $data, $this->type . '_if.', true);

        return $sourceVariables;
    }

    /**
     * @return array
     */
    public function getCurrencyDefault()
    {
        $data = $this->source->getData();

        $variableData = [];

        foreach ($data as $dat => $val) {
            if (is_numeric($val)) {
                $variableData[$dat] = $val;
            } else {
                continue;
            }
        }

        $groupName = __('Source Currency Variables');
        $sourceVariables = $this->getDependCurrencyOptionArray($groupName, $data, 'ea_' . $this->type . '.', true);

        return $sourceVariables;
    }

    /**
     * @param Object $item
     * @param $barCodes
     * @return array|bool
     */
    public function getItemsDefault($item, $barCodes)
    {
        if (!$item) {
            return false;
        }

        $this->source = $item;
        $data = $this->source->getData();

        $variableData = [];

        $groupNameVariables = __('Item Variables');
        $sourceVariables = $this->getVariablesOptionArray($groupNameVariables, $data, 'item.', true);

        $groupNameBarcode = __('Item Barcode Variables');

        $sourceBarcodeVariables = [];
        if (!empty($barCodes)) {
            $sourceBarcodeVariables = $this->getBarCodeVariables(
                $groupNameBarcode,
                $data,
                'ea_barcode_',
                '_item.',
                $barCodes,
                true
            );
        }

        foreach ($data as $dat => $val) {
            if (is_numeric($val)) {
                $variableData[$dat] = $val;
            } else {
                continue;
            }
        }

        $groupNameCurrency = __('Item Currency Variables');
        $sourceCurrencyVariables = $this->getDependCurrencyOptionArray($groupNameCurrency, $data, 'ea_item.', true);

        $groupNameDepend = __('Item Depend Variables');
        $sourceDependVariables = $this->getDependCurrencyOptionArray($groupNameDepend, $data, 'ea_item_if.', true);

        $standardVariables = [$sourceVariables, $sourceCurrencyVariables, $sourceDependVariables];

        return array_merge($standardVariables, $sourceBarcodeVariables);
    }

    /**
     * @param Object $item
     * @param $barCodes
     * @return array|bool
     */
    public function getOrderItemsDefault($item, $barCodes)
    {
        if (!$item) {
            return false;
        }

        $this->source = $item;
        $data = $this->source->getData();

        $variableData = [];

        $groupNameVariables = __('Order Item Variables');
        $sourceVariables = $this->getVariablesOptionArray($groupNameVariables, $data, 'order.item.', true);

        $groupNameBarcode = __('Order Item Barcode Variables');

        $sourceBarcodeVariables = [];
        if (!empty($barCodes)) {
            $sourceBarcodeVariables = $this->getBarCodeVariables(
                $groupNameBarcode,
                $data,
                'ea_barcode_',
                '_order.item.',
                $barCodes,
                true
            );
        }

        foreach ($data as $dat => $val) {
            if (is_numeric($val)) {
                $variableData[$dat] = $val;
            } else {
                continue;
            }
        }

        $groupNameCurrency = __('Order Item Currency Variables');
        $sourceCurrencyVariables = $this->getDependCurrencyOptionArray(
            $groupNameCurrency,
            $data,
            'ea_order.item.',
            true
        );

        $groupNameDepend = __('Order Item Depend Variables');
        $sourceDependVariables = $this->getDependCurrencyOptionArray(
            $groupNameDepend,
            $data,
            'ea_order.item_if.',
            true
        );

        $standardVariables = [$sourceVariables, $sourceCurrencyVariables, $sourceDependVariables];

        return array_merge($standardVariables, $sourceBarcodeVariables);
    }

    /**
     * @param Object $product
     * @param $barCodes
     * @return array|bool
     */
    public function getOrderItemsProductDefault($product, $barCodes)
    {
        if (!$product) {
            return false;
        }

        $this->source = $product;
        /** @var Product $data */
        $data = $this->source->getData();

        $variableData = [];

        $groupNameVariables = __('Product Variables');
        $sourceVariables = $this->getVariablesOptionArray($groupNameVariables, $data, 'order_item_product.', true);

        $groupNameBarcode = __('Product Barcode Variables');

        $sourceBarcodeVariables = [];
        if (!empty($barCodes)) {
            $sourceBarcodeVariables = $this->getBarCodeVariables(
                $groupNameBarcode,
                $data,
                'ea_barcode_',
                '_order_item_product',
                $barCodes,
                true
            );
        }

        foreach ($data as $dat => $val) {
            if (is_numeric($val)) {
                $variableData[$dat] = $val;
            } else {
                continue;
            }
        }

        $groupNameCurrency = __('Product Currency Variables');
        $sourceCurrencyVariables = $this->getDependCurrencyOptionArray(
            $groupNameCurrency,
            $data,
            'order_ea_item_product.',
            true
        );

        $groupNameDepend = __('Product Depend Variables');
        $sourceDependVariables = $this->getDependCurrencyOptionArray(
            $groupNameDepend,
            $data,
            'order_ea_item_product_if.',
            true
        );

        $standardVariables = [$sourceVariables, $sourceCurrencyVariables, $sourceDependVariables];

        return array_merge($standardVariables, $sourceBarcodeVariables);
    }

    /**
     * @param DataObject $customer
     * @param $barCodes
     * @return array|bool
     */
    public function getCustomerDefault(DataObject $customer, $barCodes)
    {
        if (!$customer) {
            return false;
        }

        $this->source = $customer;

        $data = $this->source->getData();

        $variableData = [];

        $groupNameVariables = __('Customer Variables');
        $sourceVariables = $this->getVariablesOptionArray($groupNameVariables, $data, 'customer.', true);

        $groupNameBarcode = '__(Customer Barcode Variables)';
        $sourceBarcodeVariables = [];

        if (!empty($barCodes)) {
            $sourceBarcodeVariables = $this->getBarCodeVariables(
                $groupNameBarcode,
                $data,
                'ea_barcode_',
                '_customer.',
                $barCodes,
                true
            );
        }

        foreach ($data as $dat => $val) {
            if (is_numeric($val)) {
                $variableData[$dat] = $val;
            } else {
                continue;
            }
        }

        $groupNameDepend = __('Customer Depend Variables');
        $sourceDependVariables = $this->getDependCurrencyOptionArray($groupNameDepend, $data, 'customer_if.', true);

        $standardVariables = [$sourceVariables, $sourceDependVariables];

        return array_merge($standardVariables, $sourceBarcodeVariables);
    }

    /**
     * @param DataObject $customer
     * @param $barCodes
     * @return array|bool
     */
    public function getOrderDefault(DataObject $customer, $barCodes)
    {
        if (!$customer) {
            return false;
        }

        $this->source = $customer;

        $data = $this->source->getData();

        $variableData = [];

        $groupNameVariables = __('Order Variables');
        $sourceVariables = $this->getVariablesOptionArray($groupNameVariables, $data, 'order.', true);

        $groupNameBarcode = __('Order Barcode Variables');
        $sourceBarcodeVariables = [];

        if (!empty($barCodes)) {
            $sourceBarcodeVariables = $this->getBarCodeVariables(
                $groupNameBarcode,
                $data,
                'ea_barcode_',
                '_order.',
                $barCodes,
                true
            );
        }
        foreach ($data as $dat => $val) {
            if (is_numeric($val)) {
                $variableData[$dat] = $val;
            } else {
                continue;
            }
        }

        $groupNameCurrency = __('Order Currency Variables');
        $sourceCurrencyVariables = $this->getDependCurrencyOptionArray($groupNameCurrency, $data, 'ea_order.', true);

        $groupNameDepend = __('Order Depend Variables');
        $sourceDependVariables = $this->getDependCurrencyOptionArray($groupNameDepend, $data, 'order_if.', true);

        $standardVariables = [$sourceVariables, $sourceCurrencyVariables, $sourceDependVariables,];

        return array_merge($standardVariables, $sourceBarcodeVariables);
    }

    /**
     * Retrieve option array of variables
     *
     * @param boolean $withGroup if true wrap variable options in group
     * @param $variables , the passed variables for processing
     * @param $groupLabel , the label for the new variable group
     * @param $prefix , the prefix with dot to get the correct var name
     * @return array
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function getVariablesOptionArray(
        $groupLabel,
        $variables,
        $prefix,
        $withGroup = false
    ) {
        $optionArray = [];

        if ($variables) {
            foreach ($variables as $value => $label) {
                $optionArray[] = [
                    'value' => '{{' . 'var ' . $prefix . $value . '}}',
                    'label' => __('%1', $this->createNameFromValue($value)) .
                        ' - ({{' . 'var ' . $prefix . $value . '}})'
                ];
                sort($optionArray);
            }
            if ($withGroup) {
                $optionArray = [
                    'label' => __($groupLabel),
                    'value' => $optionArray
                ];
            }
        }
        return $optionArray;
    }

    /**
     * @param $objectValue
     * @return string
     */
    private function createNameFromValue($objectValue)
    {
        $label = ucfirst(str_replace('_', ' ', $objectValue));
        return $label;
    }

    /**
     * @param bool $withGroup
     * @param $groupLabel
     * @param $variables
     * @param $prefix
     * @param $suffix
     * @param $barcodes
     * @return array
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function getBarCodeVariables(
        $groupLabel,
        $variables,
        $prefix,
        $suffix,
        $barcodes,
        $withGroup = false
    ) {
        $variablesToOptionArray = [];
        $optionArray = [];
        foreach ($barcodes as $code) {
            if ($variables) {
                foreach ($variables as $value => $label) {
                    $variablesToOptionArray[] = [
                        'value' => '{{' . 'var ' . $prefix . $code . $suffix . $value . '}}',
                        'label' => __('%1', $this->createNameFromValue($value)) .
                            ' - ({{' . 'var ' . $prefix . $code . $suffix . $value . '}})'
                    ];
                    sort($variablesToOptionArray);
                }
            }

            if ($withGroup) {
                $optionArray[] = [
                    'label' => __($groupLabel) . ' ' . $code,
                    'value' => $variablesToOptionArray
                ];
            }

            $variablesToOptionArray = [];
        }

        return $optionArray;
    }

    /**
     * @param bool $withGroup
     * @param $groupLabel
     * @param $variables
     * @param $prefix
     * @return array
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function getDependCurrencyOptionArray(
        $groupLabel,
        $variables,
        $prefix,
        $withGroup = false
    ) {
        $optionArray = [];

        if ($variables) {
            foreach ($variables as $value => $label) {
                $optionArray[] = [
                    'value' => '{{' . 'var ' . $prefix . $value . '}}',
                    'label' => __('%1', $this->createNameFromValue($value)) .
                        ' - ({{' . 'var ' . $prefix . $value . '}})'
                ];
                sort($optionArray);
            }
            if ($withGroup) {
                $optionArray = [
                    'label' => __($groupLabel),
                    'value' => $optionArray
                ];
            }
        }

        return $optionArray;
    }
}
