<?php

namespace MageSuite\FileUpload\Test\Unit\Plugin;

class ParseFileUrlInWidgetTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \MageSuite\FileUpload\Plugin\ParseFileUrlInWidget
     */
    protected $plugin;

    /**
     * @var \Magento\Widget\Model\Widget
     */
    protected $widgetDummy;

    const FILE_LINK_WIDGET_CLASS = 'MageSuite\FileUpload\Widget\FileLink';

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->plugin = $this->objectManager
            ->get(\MageSuite\FileUpload\Plugin\ParseFileUrlInWidget::class);

        $this->widgetDummy = $this->getMockBuilder(\Magento\Widget\Model\Widget::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testItParsesDataOnlyWhenCorrectWidgetClassIsPassed()
    {
        $otherWidgetType = 'Magento/Sales/Widget/Test';
        $originalParams = ['file_url' => 'test'];

        list($type, $params, $asIs) = $this->plugin->beforeGetWidgetDeclaration(
            $this->widgetDummy,
            $otherWidgetType,
            $originalParams
        );

        $this->assertEquals($otherWidgetType, $type);
        $this->assertEquals($originalParams, $params);
        $this->assertTrue($asIs);
    }

    public function testItParsesUrlForFileLinkWidget()
    {
        $originalParams = ['file_url' => 'http://localhost/admin/cms/wysiwyg/directive/___directive/e3ttZWRpYSB1cmw9Ind5c2l3eWcvTmV3X2luZnJhc3RydWN0dXJlX0xFRFNfMS5wZGYifX0,/key/6e1082646133d6a76f8df303be9056740be094b29e5836fdcbd7b676e4da2331/'];

        list($type, $params, $asIs) = $this->plugin
            ->beforeGetWidgetDeclaration($this->widgetDummy,self::FILE_LINK_WIDGET_CLASS,$originalParams);

        $this->assertEquals(self::FILE_LINK_WIDGET_CLASS, $type);
        $this->assertEquals(['file_url' => 'wysiwyg/New_infrastructure_LEDS_1.pdf'], $params);
        $this->assertTrue($asIs);
    }

    public function testItDoesNotReplaceResultWhenDifferentWidgetClassIsPassed()
    {
        $originalParams = ['file_url' => 'wysiwyg/New_infrastructure_LEDS_1.pdf'];
        $widget = '{{widget type="Magento/Sales/Widget/Test"}}';

        $result = $this->plugin->afterGetWidgetDeclaration($this->widgetDummy, $widget, 'Magento/Sales/Widget/Test', $originalParams);

        $this->assertEquals($widget, $result);
    }

    public function testItReplacesGeneratedWidgetWithMediaDirective()
    {
        $originalParams = ['file_url' => 'wysiwyg/New_infrastructure_LEDS_1.pdf'];
        $widget = '{{widget type="MageSuite\FileUpload\Widget\FileLink" file_url="wysiwyg/New_infrastructure_LEDS_1.pdf"}}';

        $result = $this->plugin->afterGetWidgetDeclaration($this->widgetDummy, $widget, self::FILE_LINK_WIDGET_CLASS, $originalParams);

        $this->assertEquals('{{media url="wysiwyg/New_infrastructure_LEDS_1.pdf"}}', $result);
    }

}
