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

namespace Eadesigndev\Opicmsppdfgenerator\Controller\Adminhtml\Variable;

use Eadesigndev\Opicmsppdfgenerator\Controller\Adminhtml\Variable\Items as ItemsVariable;
use Eadesigndev\Opicmsppdfgenerator\Model\Source\TemplateType;
use Magento\Framework\Controller\Result\Json;
use Magento\Sales\Model\Order\Item;

class Orderitem extends ItemsVariable
{
    /**
     * @return $this|null
     */
    public function execute()
    {

        $collection = $this->addCollection();
        if (empty($collection)) {
            return null;
        }

        $orderItem = $this->dataItem($collection);

        if (!$orderItem instanceof Item) {
            $orderItem = $orderItem->getOrderItem();
        }

        $lastItem = $this->customData->entity($orderItem)->processAndReadVariables();

        $barCodes = [];
        $barCodesData = $this->pdfTemplateModel->getData('barcode_types');
        if (!empty($barCodesData)) {
            $barCodes = explode(',', $barCodesData);
        }

        $variables = $this->defaultVariablesHelper->getOrderItemsDefault($lastItem, $barCodes);

        /** @var Json $resultJson */
        return $this->response($variables);
    }
}
