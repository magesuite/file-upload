<?php

namespace MageSuite\FileUpload\Plugin;

class ParseFileUrlInWidget
{
    public const FILE_URL_WIDGET_CLASS = 'MageSuite\FileUpload\Widget\FileLink';
    public const MEDIA_DIRECTIVE = '{{media url="%s"}}';
    public const KEY_FILE_URL = 'file_url';

    protected \MageSuite\FileUpload\Model\ParseFilePathFromMediaDirectoryUrl $parseFilePathFromMediaDirectoryUrl;

    public function __construct(
        \MageSuite\FileUpload\Model\ParseFilePathFromMediaDirectoryUrl $parseFilePathFromMediaDirectoryUrl
    ) {
        $this->parseFilePathFromMediaDirectoryUrl = $parseFilePathFromMediaDirectoryUrl;
    }

    public function beforeGetWidgetDeclaration(\Magento\Widget\Model\Widget $subject, $type, $params = [], $asIs = true)
    {
        if ($type !== self::FILE_URL_WIDGET_CLASS) {
            return [$type, $params, $asIs];
        }

        $fileUrl = $params[self::KEY_FILE_URL] ?? '';

        if (!empty($fileUrl)) {
            $params[self::KEY_FILE_URL] = $this->parseFilePathFromMediaDirectoryUrl->execute($fileUrl);
        }

        return [$type, $params, $asIs];
    }

    public function afterGetWidgetDeclaration(\Magento\Widget\Model\Widget $subject, $result, $type, $params = [])
    {
        if ($type !== self::FILE_URL_WIDGET_CLASS) {
            return $result;
        }

        $fileUrl = $params[self::KEY_FILE_URL] ?? '';

        if (empty($fileUrl)) {
            return $result;
        }

        return sprintf(self::MEDIA_DIRECTIVE, $fileUrl);
    }
}
