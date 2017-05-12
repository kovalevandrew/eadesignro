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
namespace Eadesigndev\Opicmsppdfgenerator\UI\Component\Buttons;

use Magento\Backend\Block\Widget\Button;
use Magento\Backend\Helper\Data as DataHelper;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Form\Element\Wysiwyg;
use Magento\Ui\Component\Wysiwyg\ConfigInterface;
use Magento\Framework\View\LayoutInterface;
use Magento\Backend\Model\UrlInterface;

/**
 * Class Variable
 *
 * @package Eadesigndev\Opicmsppdfgenerator\UI\Component\Buttons
 * @SuppressWarnings(CouplingBetweenObjects)
 */
class Variable extends Wysiwyg
{

    /**
     * @var RequestInterface
     */
    private $requestInterface;

    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * @var ContextInterface
     */
    public $context;

    /**
     * @var DataHelper
     */
    private $backendHelper;

    /**
     * @var LayoutInterface
     */
    private $layout;

    /**
     * Variable constructor.
     * @param ContextInterface $context
     * @param FormFactory $formFactory
     * @param ConfigInterface $wysiwygConfig
     * @param LayoutInterface $layout
     * @param DataHelper $backendHelper
     * @param UrlInterface $url
     * @param RequestInterface $requestInterface
     * @param array $components
     * @param array $data
     * @param array $config
     * @SuppressWarnings(ExcessiveParameterList)
     */
    public function __construct(
        ContextInterface $context,
        FormFactory $formFactory,
        ConfigInterface $wysiwygConfig,
        LayoutInterface $layout,
        DataHelper $backendHelper,
        UrlInterface $url,
        RequestInterface $requestInterface,
        array $components = [],
        array $data = [],
        array $config = []
    ) {
        $this->layout = $layout;
        $this->backendHelper = $backendHelper;
        $this->url = $url;
        $this->context = $context;
        $this->requestInterface = $requestInterface;
        parent::__construct($context, $formFactory, $wysiwygConfig, $components, $data, $config);

        $this->setData($this->prepareData($this->getData()));
    }

    /**
     * Prepare wysiwyg content
     *
     * @param array $data
     * @return array
     */
    private function prepareData($data)
    {
        if ($this->editor->isEnabled()) {
            $config = $data['config']['content'];

            $data['config']['content'] =
                $this->getSourceButtonHtml() .
                $this->getVariableButtonHtml() .
                $this->getVariableBarcodesButtonHtml() .
                $this->getVariableDependButtonHtml() .
                $this->getVariableCurrencyButtonHtml() .
                $this->getVariableItemsButtonHtml() .
                $this->getVariableOrderItemsButtonHtml().
                $this->getVariableItemsProductButtonHtml().
                $this->getVariableCustomerButtonHtml() .
                $this->getVariableOrderButtonHtml() .
                $config;
        }
        return $data;
    }

    /**
     * Return wysiwyg button html
     *
     * @return string
     */
    private function getVariableButtonHtml()
    {

        $html_id = $this->context->getNamespace() . '_' . $this->getData('name');

        $button = $this->layout->createBlock(
            Button::class,
            '',
            [
                'data' => [
                    'name' => 'variable_button1',
                    'label' => __('Standard Variables'),
                    'type' => 'button',
                    'style' => ' margin-top:10px; margin-bottom:10px',
                    'class' => 'action-wysiwyg',
                    'onclick' => 'EadesignVariablePlugin.loadChooser(\'' .
                        $this->url->getUrl(
                            'opicmsppdfgenerator/variable/standard',
                            ['template_id' => $this->requestInterface->getParam('template_id')]
                        ) .
                        '\', \'' . $html_id . '\');',
                ]
            ]
        )->toHtml();

        return $button;
    }

    /**
     * Return wysiwyg button html
     *
     * @return string
     */
    private function getSourceButtonHtml()
    {

        $html_id = $this->context->getNamespace() . '_' . $this->getData('name');

        $button = $this->layout->createBlock(
            Button::class,
            '',
            [
                'data' => [
                    'name' => 'barcode_button2',
                    'label' => __('Source Variables'),
                    'type' => 'button',
                    'style' => ' margin-top:10px; margin-bottom:10px',
                    'class' => 'action-wysiwyg',
                    'onclick' => 'EadesignVariablePlugin.loadChooser(\'' .
                        $this->url->getUrl(
                            'opicmsppdfgenerator/variable/source',
                            ['template_id' => $this->requestInterface->getParam('template_id')]
                        ) .
                        '\', \'' . $html_id . '\');',
                ]
            ]
        )->toHtml();

        return $button;
    }

    /**
     * Return wysiwyg button html for the barcodes
     *
     * @return string
     */
    private function getVariableBarcodesButtonHtml()
    {

        $html_id = $this->context->getNamespace() . '_' . $this->getData('name');

        $button = $this->layout->createBlock(
            Button::class,
            '',
            [
                'data' => [
                    'name' => 'barcode_button',
                    'label' => __('Source Barcode Variables'),
                    'type' => 'button',
                    'style' => ' margin-top:10px; margin-bottom:10px',
                    'class' => 'action-wysiwyg',
                    'onclick' => 'EadesignVariablePlugin.loadChooser(\'' .
                        $this->url->getUrl(
                            'opicmsppdfgenerator/variable/barcodes',
                            ['template_id' => $this->requestInterface->getParam('template_id')]
                        ) .
                        '\', \'' . $html_id . '\');',
                ]
            ]
        )->toHtml();

        return $button;
    }

    /**
     * Return wysiwyg button html for the depend evaluates if 0 values
     *
     * @return string
     */
    private function getVariableDependButtonHtml()
    {

        $html_id = $this->context->getNamespace() . '_' . $this->getData('name');

        $button = $this->layout->createBlock(
            Button::class,
            '',
            [
                'data' => [
                    'name' => 'variable_button2',
                    'label' => __('Source Depend Variables'),
                    'type' => 'button',
                    'style' => ' margin-top:10px; margin-bottom:10px',
                    'class' => 'action-wysiwyg',
                    'onclick' => 'EadesignVariablePlugin.loadChooser(\'' .
                        $this->url->getUrl(
                            'opicmsppdfgenerator/variable/depend',
                            ['template_id' => $this->requestInterface->getParam('template_id')]
                        ) .
                        '\', \'' . $html_id . '\');',
                ]
            ]
        )->toHtml();

        return $button;
    }

    /**
     * Return wysiwyg button html for the depend evaluates if 0 values
     *
     * @return string
     */
    private function getVariableCurrencyButtonHtml()
    {

        $html_id = $this->context->getNamespace() . '_' . $this->getData('name');

        $button = $this->layout->createBlock(
            Button::class,
            '',
            [
                'data' => [
                    'name' => 'variable_button3',
                    'label' => __('Source Currency Variables'),
                    'type' => 'button',
                    'style' => ' margin-top:10px; margin-bottom:10px',
                    'class' => 'action-wysiwyg',
                    'onclick' => 'EadesignVariablePlugin.loadChooser(\'' .
                        $this->url->getUrl(
                            'opicmsppdfgenerator/variable/currency',
                            ['template_id' => $this->requestInterface->getParam('template_id')]
                        ) .
                        '\', \'' . $html_id . '\');',
                ]
            ]
        )->toHtml();

        return $button;
    }

    /**
     * Return wysiwyg button html for the depend evaluates if 0 values
     *
     * @return string
     */
    private function getVariableItemsButtonHtml()
    {

        $html_id = $this->context->getNamespace() . '_' . $this->getData('name');

        $button = $this->layout->createBlock(
            Button::class,
            '',
            [
                'data' => [
                    'name' => 'variable_button3',
                    'label' => __('Source Items Variables'),
                    'type' => 'button',
                    'style' => ' margin-top:10px; margin-bottom:10px',
                    'class' => 'action-wysiwyg',
                    'onclick' => 'EadesignVariablePlugin.loadChooser(\'' .
                        $this->url->getUrl(
                            'opicmsppdfgenerator/variable/items',
                            ['template_id' => $this->requestInterface->getParam('template_id')]
                        ) .
                        '\', \'' . $html_id . '\');',
                ]
            ]
        )->toHtml();

        return $button;
    }

    /**
     * Return wysiwyg button html for the depend evaluates if 0 values
     *
     * @return string
     */
    private function getVariableItemsProductButtonHtml()
    {

        $html_id = $this->context->getNamespace() . '_' . $this->getData('name');

        $button = $this->layout->createBlock(
            Button::class,
            '',
            [
                'data' => [
                    'name' => 'variable_button3',
                    'label' => __('Source Order Items Product Variables'),
                    'type' => 'button',
                    'style' => ' margin-top:10px; margin-bottom:10px',
                    'class' => 'action-wysiwyg',
                    'onclick' => 'EadesignVariablePlugin.loadChooser(\'' .
                        $this->url->getUrl(
                            'opicmsppdfgenerator/variable/product',
                            ['template_id' => $this->requestInterface->getParam('template_id')]
                        ) .
                        '\', \'' . $html_id . '\');',
                ]
            ]
        )->toHtml();

        return $button;
    }

    /**
     * Return wysiwyg button html for the depend evaluates if 0 values
     *
     * @return string
     */
    private function getVariableOrderItemsButtonHtml()
    {

        $html_id = $this->context->getNamespace() . '_' . $this->getData('name');

        $button = $this->layout->createBlock(
            Button::class,
            '',
            [
                'data' => [
                    'name' => 'variable_button3',
                    'label' => __('Order Items Variables'),
                    'type' => 'button',
                    'style' => ' margin-top:10px; margin-bottom:10px',
                    'class' => 'action-wysiwyg',
                    'onclick' => 'EadesignVariablePlugin.loadChooser(\'' .
                        $this->url->getUrl(
                            'opicmsppdfgenerator/variable/orderitem',
                            ['template_id' => $this->requestInterface->getParam('template_id')]
                        ) .
                        '\', \'' . $html_id . '\');',
                ]
            ]
        )->toHtml();

        return $button;
    }

    /**
     * Return wysiwyg button html for the depend evaluates if 0 values
     *
     * @return string
     */
    private function getVariableCustomerButtonHtml()
    {

        $html_id = $this->context->getNamespace() . '_' . $this->getData('name');

        $button = $this->layout->createBlock(
            Button::class,
            '',
            [
                'data' => [
                    'name' => 'variable_button4',
                    'label' => __('Customer Variables'),
                    'type' => 'button',
                    'style' => ' margin-top:10px; margin-bottom:10px',
                    'class' => 'action-wysiwyg',
                    'onclick' => 'EadesignVariablePlugin.loadChooser(\'' .
                        $this->url->getUrl(
                            'opicmsppdfgenerator/variable/customer',
                            ['template_id' => $this->requestInterface->getParam('template_id')]
                        ) .
                        '\', \'' . $html_id . '\');',
                ]
            ]
        )->toHtml();

        return $button;
    }

    /**
     * Return wysiwyg button html for the depend evaluates if 0 values
     *
     * @return string
     */
    private function getVariableOrderButtonHtml()
    {

        $html_id = $this->context->getNamespace() . '_' . $this->getData('name');

        $button = $this->layout->createBlock(
            Button::class,
            '',
            [
                'data' => [
                    'name' => 'variable_button5',
                    'label' => __('Order Variables'),
                    'type' => 'button',
                    'style' => ' margin-top:10px; margin-bottom:10px',
                    'class' => 'action-wysiwyg',
                    'onclick' => 'EadesignVariablePlugin.loadChooser(\'' .
                        $this->url->getUrl(
                            'opicmsppdfgenerator/variable/order',
                            ['template_id' => $this->requestInterface->getParam('template_id')]
                        ) .
                        '\', \'' . $html_id . '\');',
                ]
            ]
        )->toHtml();

        return $button;
    }
}
