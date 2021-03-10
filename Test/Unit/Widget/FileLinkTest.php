<?php

namespace MageSuite\FileUpload\Test\Unit\Widget;

class FileLinkTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \MageSuite\FileUpload\Widget\FileLink
     */
    protected $widget;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->widget = $this->objectManager->create(\MageSuite\FileUpload\Widget\FileLink::class);
    }

    public function testItReturnsFileUrlWithFullDomain()
    {
        $this->widget->setData('file_url', 'wysiwyg/file.pdf');

        $result = $this->widget->toHtml();

        $result = str_replace('pub/', '', $result);
        $this->assertEquals('http://localhost/media/wysiwyg/file.pdf', $result);
    }

    public function testItReturnsEmptyStringWhenUrlIsNotPassed()
    {
        $result = $this->widget->toHtml();

        $this->assertEquals('', $result);
    }
}
