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
namespace Aheadworks\Rbslider\Controller\Adminhtml\Slide;

use Aheadworks\Rbslider\Model\Slide\ImageFileUploader;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;

/**
 * Class UploadImage
 * @package Aheadworks\Rbslider\Controller\Adminhtml\Slide
 */
class UploadImage extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Aheadworks_Rbslider::slides';

    /**
     * @var string
     */
    const IMG_FILE = 'img_file';

    /**
     * @var ImageFileUploader
     */
    protected $imageFileUploader;

    /**
     * @param Context $context
     * @param ImageFileUploader $imageFileUploader
     */
    public function __construct(
        Context $context,
        ImageFileUploader $imageFileUploader
    ) {
        parent::__construct($context);
        $this->imageFileUploader = $imageFileUploader;
    }

    /**
     * Image upload action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $result = $this->imageFileUploader->saveImageToMediaFolder(static::IMG_FILE);
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
