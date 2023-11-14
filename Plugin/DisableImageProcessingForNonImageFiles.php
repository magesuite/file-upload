<?php

namespace MageSuite\FileUpload\Plugin;

/**
 * Image manipulation operations must be disabled for non image files
 *
 * @package MageSuite\FileUpload\Plugin
 */
class DisableImageProcessingForNonImageFiles
{
    protected $nonImageFilesExtensions = [
        'pdf',
        'doc',
        'docm',
        'docx',
        'csv',
        'xml',
        'xls',
        'xlsx',
        'zip',
        'tar',
        'rar',
    ];

    public function aroundOpen(\Magento\Framework\Image\Adapter\Gd2 $subject, callable $proceed, $filename)
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        if(in_array($extension, $this->nonImageFilesExtensions)) {
            return;
        }

        $proceed($filename);
    }

    public function aroundSave(\Magento\Framework\Image\Adapter\Gd2 $subject, callable $proceed, $destination = null, $newName = null)
    {
        $extension = pathinfo($destination, PATHINFO_EXTENSION);

        if(in_array($extension, $this->nonImageFilesExtensions)) {
            return;
        }

        $proceed($destination, $newName);
    }
}
