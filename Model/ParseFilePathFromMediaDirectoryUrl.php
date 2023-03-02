<?php

declare(strict_types=1);

namespace MageSuite\FileUpload\Model;

class ParseFilePathFromMediaDirectoryUrl
{
    /**
     * Parses URL returned from media gallery in admin panel in this format:
     * http://localhost/admin/cms/wysiwyg/directive/___directive/e3ttZWRpYSB1cmw9Ind5c2l3eWcvTmV3X2luZnJhc3RydWN0dXJlX0xFRFNfMS5wZGYifX0,/
     * into correct file media directory relative file path:
     * wysiwyg/file.pdf
     */
    public function execute(?string $url): string
    {
        if (strpos((string)$url, '/directive/___directive/') === false) {
            return '';
        }

        $urlParts = explode('/', $url);
        $key = array_search("___directive", $urlParts);

        if ($key === false) {
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
