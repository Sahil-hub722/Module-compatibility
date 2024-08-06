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

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\ReadInterface;
use Magento\Framework\Filesystem\Driver\File\Mime as FileMime;
use Magento\Framework\UrlInterface;

class ImageFileInfo
{
    /**
     * Path relatively by pub/media
     */
    private const MEDIA_PATH = 'aw_rbslider/slides';

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var FileMime
     */
    private $fileMime;

    /**
     * @var string|null
     */
    private $fileName;

    /**
     * @param UrlInterface $urlBuilder
     * @param Filesystem $filesystem
     * @param FileMime $fileMime
     */
    public function __construct(
        UrlInterface $urlBuilder,
        Filesystem $filesystem,
        FileMime $fileMime
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->filesystem = $filesystem;
        $this->fileMime = $fileMime;
    }

    /**
     * Retrieve stored filename
     *
     * @return string|null
     */
    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * Set filename for further processing
     *
     * @param string $fileName
     * @return $this
     */
    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * Collect file information
     *
     * @return array
     * @throws FileSystemException
     */
    public function getInfo(): array
    {
        return [
            'name' => $this->getFileName(),
            'url'  => $this->getStoreUrl(),
            'size' => $this->getStat()['size'] ?? 0,
            'type' => $this->getMimeType()
        ];
    }

    /**
     * Build path to the file relatively by media directory
     *
     * @return string
     */
    public function getRelativePath(): string
    {
        return self::MEDIA_PATH . '/' . $this->getFileName();
    }

    /**
     * Build absolute path to the file
     *
     * @return string
     */
    public function getAbsolutePath(): string
    {
        return $this->getMediaDirectory()->getAbsolutePath(
            $this->getRelativePath()
        );
    }

    /**
     * Retrieve storefront URL to the file
     *
     * @return string
     */
    public function getStoreUrl(): string
    {
        $mediaUrl = $this->urlBuilder->getBaseUrl(
            ['_type' => UrlInterface::URL_TYPE_MEDIA]
        );

        return $mediaUrl . $this->getRelativePath();
    }

    /**
     * Retrieve media read instance
     *
     * @return ReadInterface
     */
    private function getMediaDirectory(): ReadInterface
    {
        return $this->filesystem->getDirectoryRead(
            DirectoryList::MEDIA
        );
    }

    /**
     * Retrieve statistics for the file
     *
     * @return array
     */
    private function getStat(): array
    {
        return $this->getMediaDirectory()->stat(
            $this->getRelativePath()
        );
    }

    /**
     * Retrieve MIME type of the file
     *
     * @return string
     * @throws FileSystemException
     */
    private function getMimeType(): string
    {
        return $this->fileMime->getMimeType(
            $this->getAbsolutePath()
        );
    }
}
