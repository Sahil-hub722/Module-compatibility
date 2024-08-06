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
namespace Aheadworks\Rbslider\Ui\DataProvider;

use Magento\Framework\View\Element\UiComponent\DataProvider\DataProviderInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\App\RequestInterface;
use Aheadworks\Rbslider\Model\ResourceModel\Banner\Grid\CollectionFactory;

/**
 * Class BannerDataProvider
 * @package Aheadworks\Rbslider\Model\Banner
 */
class BannerDataProvider extends AbstractDataProvider implements DataProviderInterface
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
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param RequestInterface $request
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        RequestInterface $request,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->request = $request;
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $data = [];
        $dataFromForm = $this->dataPersistor->get('aw_rbslider_banner');
        if (!empty($dataFromForm)) {
            $data[$dataFromForm['id']] = $dataFromForm;
            $this->dataPersistor->clear('aw_rbslider_banner');
        } else {
            $id = $this->request->getParam($this->getRequestFieldName());
            /** @var \Aheadworks\Rbslider\Model\Banner $banner */
            $banner = $this->getCollection()
                ->addFieldToFilter('id', $id)
                ->getFirstItem();

            $bannerData = $banner->getData();
            if (!empty($bannerData)) {
                $data[$id] = $bannerData;
            }
        }
        return $data;
    }
}
