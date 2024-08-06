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
namespace Aheadworks\Rbslider\Controller\Adminhtml\Banner;

use Aheadworks\Rbslider\Api\BannerRepositoryInterface;
use Aheadworks\Rbslider\Api\Data\BannerInterface;
use Aheadworks\Rbslider\Api\Data\BannerInterfaceFactory;
use Aheadworks\Rbslider\Model\Converter\Condition as ConditionConverter;
use Aheadworks\Rbslider\Model\Source\PageType;
use Aheadworks\Rbslider\Model\Source\Position;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Save
 * @package Aheadworks\Rbslider\Controller\Adminhtml\Banner
 */
class Save extends Action implements HttpPostActionInterface
{
    /**
     * {@inheritdoc}
     */
    const ADMIN_RESOURCE = 'Aheadworks_Rbslider::banners';

    /**
     * @var BannerRepositoryInterface
     */
    private $bannerRepository;

    /**
     * @var BannerInterfaceFactory
     */
    private $bannerDataFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var ConditionConverter
     */
    private $conditionConverter;

    /**
     * @param Context $context
     * @param BannerRepositoryInterface $bannerRepository
     * @param BannerInterfaceFactory $bannerDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataPersistorInterface $dataPersistor
     * @param ConditionConverter $conditionConverter
     */
    public function __construct(
        Context $context,
        BannerRepositoryInterface $bannerRepository,
        BannerInterfaceFactory $bannerDataFactory,
        DataObjectHelper $dataObjectHelper,
        DataPersistorInterface $dataPersistor,
        ConditionConverter $conditionConverter
    ) {
        parent::__construct($context);
        $this->bannerRepository = $bannerRepository;
        $this->bannerDataFactory = $bannerDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataPersistor = $dataPersistor;
        $this->conditionConverter = $conditionConverter;
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data = $this->getRequest()->getPostValue()) {
            try {
                $data = $this->prepareData($data);
                $banner = $this->performSave($data);

                $this->dataPersistor->clear('aw_rbslider_banner');
                $this->messageManager->addSuccessMessage(__('Banner was successfully saved'));
                if ($this->getRequest()->getParam('back') == 'edit') {
                    return $resultRedirect->setPath('*/banner/edit', ['id' => $banner->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the banner'));
            }
            $this->dataPersistor->set('aw_rbslider_banner', $data);
            $id = isset($data['id']) ? $data['id'] : false;
            if ($id) {
                return $resultRedirect->setPath('*/*/edit', ['id' => $id, '_current' => true]);
            }
            return $resultRedirect->setPath('*/*/new', ['_current' => true]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Perform save
     *
     * @param array$data
     * @return BannerInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function performSave($data)
    {
        $id = isset($data['id']) ? $data['id'] : false;
        $bannerDataObject = $id
            ? $this->bannerRepository->get($id)
            : $this->bannerDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $bannerDataObject,
            $data,
            BannerInterface::class
        );

        return $this->bannerRepository->save($bannerDataObject);
    }

    /**
     * Prepare data after save
     *
     * @param array $data
     * @return array
     */
    private function prepareData(array $data)
    {
        if (isset($data['slide_position'])) {
            $data['slide_ids'] = (array)array_keys(json_decode($data['slide_position'], true));
        }
        if ($data['page_type'] == PageType::PRODUCT_PAGE) {
            if (isset($data['rule']['rbslider'])) {
                $conditionArray = $this->convertFlatToRecursive($data['rule'], ['rbslider']);
                if (is_array($conditionArray['rbslider']['1'])) {
                    $data['product_condition'] = $this->conditionConverter
                        ->arrayToDataModel($conditionArray['rbslider']['1']);
                    $data['product_condition'] = $this->conditionConverter
                        ->dataModelToArray($data['product_condition']);
                } else {
                    $data['product_condition'] = '';
                }
            }
        } else {
            $data['product_condition'] = '';
        }
        if ($data['page_type'] != PageType::CATEGORY_PAGE) {
            $data['category_ids'] = '';
        }
        if ($data['page_type'] == PageType::CUSTOM_WIDGET) {
            $data['position'] = Position::CONTENT_TOP;
        }
        unset($data['rule']);
        return $data;
    }

    /**
     * Get conditions data recursively
     *
     * @param array $data
     * @param array $allowedKeys
     * @return array
     */
    private function convertFlatToRecursive(array $data, $allowedKeys = [])
    {
        $result = [];
        foreach ($data as $key => $value) {
            if (in_array($key, $allowedKeys) && is_array($value)) {
                foreach ($value as $id => $data) {
                    $path = explode('--', (string)$id);
                    $node = & $result;

                    for ($i = 0, $l = sizeof($path); $i < $l; $i++) {
                        if (!isset($node[$key][$path[$i]])) {
                            $node[$key][$path[$i]] = [];
                        }
                        $node = & $node[$key][$path[$i]];
                    }
                    foreach ($data as $k => $v) {
                        $node[$k] = $v;
                    }
                }
            }
        }
        return $result;
    }
}
