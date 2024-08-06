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

namespace Aheadworks\Rbslider\Ui\DataProvider;

use Aheadworks\Rbslider\Api\Data\SlideInterface;
use Aheadworks\Rbslider\Model\ResourceModel\Slide\Grid\CollectionFactory;
use Aheadworks\Rbslider\Model\ThirdPartyModule\Manager as ModuleManager;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProviderInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

/**
 * Class SlideDataProvider
 *
 * @package Aheadworks\Rbslider\Ui\DataProvider
 */
class SlideDataProvider extends AbstractDataProvider implements DataProviderInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var ModuleManager
     */
    private $moduleManager;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param RequestInterface $request
     * @param DataPersistorInterface $dataPersistor
     * @param ModuleManager $moduleManager
     * @param array $meta
     * @param array $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        RequestInterface $request,
        DataPersistorInterface $dataPersistor,
        ModuleManager $moduleManager,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);

        $this->collection = $collectionFactory->create();
        $this->request = $request;
        $this->dataPersistor = $dataPersistor;
        $this->moduleManager = $moduleManager;
    }

    /**
     * @inheritdoc
     */
    public function getData()
    {
        $data = [];
        $dataFromForm = $this->dataPersistor->get('aw_rbslider_slide');
        if (!empty($dataFromForm)) {
            $data[$dataFromForm['id']] = $dataFromForm;
            $this->dataPersistor->clear('aw_rbslider_slide');
        } else {
            $id = $this->request->getParam($this->getRequestFieldName());
            /** @var SlideInterface $slide */
            $slide = $this->getCollection()
                ->addFieldToFilter(SlideInterface::ID, $id)
                ->getFirstItem();

            $slideData = $slide->getData();
            $data[$id] = $this->prepareFormData($slideData);
        }
        return $data;
    }

    /**
     * Prepare form data
     *
     * @param array $itemData
     * @return array
     */
    private function prepareFormData(array $itemData): array
    {
        $itemData['isSegmentsAvailable'] = $this->moduleManager->isCustomerSegmentationModuleEnabled();

        return $itemData;
    }
}
