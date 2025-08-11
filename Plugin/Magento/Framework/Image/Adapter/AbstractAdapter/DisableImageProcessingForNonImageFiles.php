<?php

declare(strict_types=1);

namespace MageSuite\FileUpload\Plugin\Magento\Framework\Image\Adapter\AbstractAdapter;

class DisableImageProcessingForNonImageFiles
{
    public function __construct(
        protected array $nonImageFilesExtensions
    ) {}

    public function aroundOpen(\Magento\Framework\Image\Adapter\AbstractAdapter $subject, callable $proceed, $filename): void // phpcs:ignore
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION); // phpcs:ignore

        if (in_array($extension, $this->nonImageFilesExtensions)) {
            return;
        }

        $proceed($filename);
    }

    public function aroundSave(\Magento\Framework\Image\Adapter\AbstractAdapter $subject, callable $proceed, $destination = null, $newName = null): void // phpcs:ignore
    {
        $extension = pathinfo($destination, PATHINFO_EXTENSION); // phpcs:ignore

        if (in_array($extension, $this->nonImageFilesExtensions)) {
            return;
        }

        $proceed($destination, $newName);
    }

    public function aroundResize(\Magento\Framework\Image\Adapter\AbstractAdapter $subject, callable $proceed, $width = null, $height = null): void // phpcs:ignore
    {
        if (empty($subject->getFileSrcName())) {
            return;
        }

        $proceed($width, $height);
    }
}
