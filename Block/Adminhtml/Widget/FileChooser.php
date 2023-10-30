<?php

namespace MageSuite\FileUpload\Block\Adminhtml\Widget;

use Magento\Ui\Component\Form\Element\DataType\Media\OpenDialogUrl;
use Magento\Cms\Model\Wysiwyg\Config;

class FileChooser extends \Magento\Backend\Block\Template
{
    /**
     * @var \Magento\Framework\Data\Form\Element\Factory
     */
    protected $_elementFactory;
    
    /**
     * @var OpednDialogUrl
     */
    protected $openDialogUrl;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Data\Form\Element\Factory $elementFactory
     * @param OpenDialogUrl $openDialogUrl
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Data\Form\Element\Factory $elementFactory,
        OpenDialogUrl $openDialogUrl,
        array $data = []
    )
    {
        $this->_elementFactory = $elementFactory;
        $this->openDialogUrl = $openDialogUrl;

        parent::__construct($context, $data);
    }

    /**
     * Prepare chooser element HTML
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element Form Element
     * @return \Magento\Framework\Data\Form\Element\AbstractElement
     */
    public function prepareElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $config = $this->_getData('config');
        
        $sourceUrl = $this->getUrl(
            $this->openDialogUrl->get(),
            ['target_element_id' => $element->getId(), 'type' => 'file']
        );

        $onClick = sprintf('MediabrowserUtility.openDialog("%s")', $sourceUrl);

        $chooser = $this->getLayout()->createBlock('Magento\Backend\Block\Widget\Button')
            ->setType('button')
            ->setClass('btn-chooser')
            ->setLabel($config['button']['open'])
            ->setOnClick($onClick)
            ->setDisabled($element->getReadonly());

        $input = $this->_elementFactory->create("text", ['data' => $element->getData()]);
        $input->setId($element->getId());
        $input->setForm($element->getForm());
        $input->setClass("widget-option input-text admin__control-text");

        if ($element->getRequired()) {
            $input->addClass('required-entry');
        }

        $element->setData('after_element_html', $input->getElementHtml() . $chooser->toHtml()
            . "<script>require(['mage/adminhtml/browser']);</script>");

        return $element;
    }
}
