<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://aheadworks.com/end-user-license-agreement/
 *
 * @package    Rbslider
 * @version    1.4.5
 * @copyright  Copyright (c) 2022 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
declare(strict_types=1);

namespace Aheadworks\Rbslider\Model\Slide;

use Exception;
use Magento\MediaStorage\Model\File\UploaderFactory;

/**
 * Class ImageFileUploader
 * @package Aheadworks\Rbslider\Model\Slide
 */
class ImageFileUploader
{
    /**
     * @var UploaderFactory
     */
    private $uploaderFactory;

    /**
     * @var ImageFileInfo
     */
    private $imageFileInfo;

    /**
     * @param UploaderFactory $uploaderFactory
     * @param ImageFileInfo $imageFileInfo
     */
    public function __construct(
        UploaderFactory $uploaderFactory,
        ImageFileInfo $imageFileInfo
    ) {
        $this->uploaderFactory = $uploaderFactory;
        $this->imageFileInfo = $imageFileInfo;
    }

    /**
     * Save file to temp media directory
     *
     * @param string $fileId
     * @return array
     */
    public function saveImageToMediaFolder(string $fileId): array
    {
        try {
            $result = $this->uploaderFactory->create(['fileId' => $fileId])
                ->setAllowRenameFiles(true)
                ->setFilesDispersion(false)
                ->setAllowedExtensions($this->getAllowedExtensions())
                ->save($this->imageFileInfo->getAbsolutePath());

            $result = $this->imageFileInfo->setFileName($result['file'])->getInfo();
        } catch (Exception $e) {
            $result = [
                'error' => $e->getMessage(),
                'errorcode' => $e->getCode()
            ];
        }

        return $result;
    }

    /**
     * Retrieve storefront URL to the file
     *
     * @param string $filename
     * @return string
     */
    public function getMediaUrl(string $filename): string
    {
        return $this->imageFileInfo->setFileName($filename)->getStoreUrl();
    }

    /**
     * Get allowed file extensions
     *
     * @return string[]
     */
    private function getAllowedExtensions(): array
    {
        return ['jpg', 'jpeg', 'gif', 'png'];
    }
}
