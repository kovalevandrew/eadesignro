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

use Eadesigndev\Opicmsppdfgenerator\Model\Source\TemplateType;

class Currency extends Template
{
    /**
     * @return $this|null
     */
    public function execute()
    {

        $this->_initTemplate();

        $id = $this->getRequest()->getParam('template_id');

        if (!$id) {
            return null;
        }

        $templateModel = $this->templateRepository->getById($id);
        $templateType = $templateModel->getData('template_type');

        $templateTypeName = TemplateType::TYPES[$templateType];

        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultJsonFactory->create();

        $collection = $this->collection($templateTypeName);

        if (empty($collection)) {
            return null;
        }

        $source = $collection->getLastItem();
        $invoiceVariables = $this->defaultVariablesHelper
            ->setSourceType($source, $templateTypeName)
            ->getCurrencyDefault();

        $result = $resultJson->setData([$invoiceVariables]);

        return $this->addResponse($result);
    }
}
