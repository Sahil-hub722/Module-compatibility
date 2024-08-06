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
namespace Aheadworks\Rbslider\Setup\Patch\Data;

use Aheadworks\Rbslider\Api\BannerRepositoryInterface;
use Aheadworks\Rbslider\Api\Data\BannerInterface;
use Aheadworks\Rbslider\Model\Sample;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Aheadworks\Rbslider\Api\Data\BannerInterfaceFactory;

/**
 * Class InstallSampleData
 */
class InstallSampleData implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var Sample
     */
    private $sampleData;

    /**
     * @var BannerInterfaceFactory
     */
    private $bannerDataFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var BannerRepositoryInterface
     */
    private $bannerRepository;

    /**
     * InstallSampleData constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param Sample $sampleData
     * @param BannerInterfaceFactory $bannerDataFactory
     * @param BannerRepositoryInterface $bannerRepository
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        Sample $sampleData,
        BannerInterfaceFactory $bannerDataFactory,
        BannerRepositoryInterface $bannerRepository,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->sampleData = $sampleData;
        $this->bannerDataFactory = $bannerDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->bannerRepository = $bannerRepository;
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * Install sample data
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        foreach ($this->sampleData->get() as $data) {
            try {
                $bannerDataObject = $this->bannerDataFactory->create();
                $this->dataObjectHelper->populateWithArray(
                    $bannerDataObject,
                    $data,
                    BannerInterface::class
                );

                if (!$bannerDataObject->getId()) {
                    $bannerDataObject->setId(null);
                }

                $this->bannerRepository->save($bannerDataObject);
            } catch (\Exception $e) {
                throw $e;
            }
        }
        $this->moduleDataSetup->getConnection()->endSetup();
    }
}
