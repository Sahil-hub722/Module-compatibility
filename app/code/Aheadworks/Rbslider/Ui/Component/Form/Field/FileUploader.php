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

namespace Aheadworks\Rbslider\Ui\Component\Form\Field;

use Aheadworks\Rbslider\Model\Slide\ImageFileInfo;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Form\Element\DataType\Media as UiFileUploader;

class FileUploader extends UiFileUploader
{
    /**
     * @var ImageFileInfo
     */
    private $imageFileInfo;

    /**
     * @param ContextInterface $context
     * @param ImageFileInfo $imageFileInfo
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        ImageFileInfo $imageFileInfo,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $components, $data);
        $this->imageFileInfo = $imageFileInfo;
    }

    /**
     * Prepare data for Ui uploader
     *
     * @param array $dataSource
     * @return array
     * @throws FileSystemException
     */
    public function prepareDataSource(array $dataSource)
    {
        $file = $dataSource['data'][$this->getName()] ?? '';
        if ($file) {
            $dataSource['data'][$this->getName()] = [
                $this->imageFileInfo->setFileName($file)->getInfo()
            ];
        }

        return parent::prepareDataSource($dataSource);
    }
}
