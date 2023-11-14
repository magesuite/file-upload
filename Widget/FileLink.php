<?php

namespace MageSuite\FileUpload\Widget;

class FileLink extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    /**
     * @var \Magento\Store\Api\StoreManagementInterface
     */
    protected $storeManagement;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Store\Api\StoreManagementInterface $storeManagement,
        array $data = []
    )
    {
        \Magento\Framework\View\Element\Template::__construct($context, $data);
        $this->storeManagement = $storeManagement;
    }

    protected function _toHtml()
    {
        $fileUrl = $this->getData('file_url');

        if(empty($fileUrl)) {
            return '';
        }

        $baseUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        return $baseUrl . $fileUrl;
    }
}
