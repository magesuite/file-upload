<?php

namespace MageSuite\FileUpload\Plugin;

class ParseFileUrlInWidget
{
    const FILE_URL_WIDGET_CLASS = 'MageSuite\FileUpload\Widget\FileLink';
    const MEDIA_DIRECTIVE = '{{media url="%s"}}';

    public function beforeGetWidgetDeclaration(\Magento\Widget\Model\Widget $subject, $type, $params = [], $asIs = true)
    {
        if($type != self::FILE_URL_WIDGET_CLASS) {
            return [$type, $params, $asIs];
        }

        if(array_key_exists("file_url", $params)) {
            $params['file_url'] = $this->parseFilePathFromMediaDirectoryUrl($params["file_url"]);
        }

        return [$type, $params, $asIs];
    }

    public function afterGetWidgetDeclaration(\Magento\Widget\Model\Widget $subject, $result, $type, $params = [], $asIs = true)
    {
        if($type != self::FILE_URL_WIDGET_CLASS) {
            return $result;
        }

        if(array_key_exists("file_url", $params)) {
            return sprintf(self::MEDIA_DIRECTIVE, $params['file_url']);
        }

        return $result;
    }

    /**
     * Prases url returned from media gallery in admin panel in this format:
     * http://localhost/admin/cms/wysiwyg/directive/___directive/e3ttZWRpYSB1cmw9Ind5c2l3eWcvTmV3X2luZnJhc3RydWN0dXJlX0xFRFNfMS5wZGYifX0,/
     * into correct file media directory relative file path:
     * wysiwyg/file.pdf
     * @param $params
     * @param $url
     * @return mixed
     */
    protected function parseFilePathFromMediaDirectoryUrl($url)
    {
        if (strpos($url, '/directive/___directive/') == false) {
            return '';
        }

        $urlParts = explode('/', $url);
        $key = array_search("___directive", $urlParts);

        if ($key == false) {
            return '';
        }

        $url = $urlParts[$key + 1];
        $url = base64_decode(strtr($url, '-_,', '+/='));

        $urlParts = explode('"', $url);
        $key = array_search("{{media url=", $urlParts);
        $url = $urlParts[$key + 1];

        return $url;
    }
}
