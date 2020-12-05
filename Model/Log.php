<?php
/**
 * Copyright © ismail cakir All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kodhub\Reporter\Model;

use Kodhub\Reporter\Api\Data\LogInterface;
use Kodhub\Reporter\Api\Data\LogInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class Log extends \Magento\Framework\Model\AbstractModel
{

    protected $dataObjectHelper;

    protected $_eventPrefix = 'kodhub_reporter_log';
    protected $logDataFactory;


    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param LogInterfaceFactory $logDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Kodhub\Reporter\Model\ResourceModel\Log $resource
     * @param \Kodhub\Reporter\Model\ResourceModel\Log\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        LogInterfaceFactory $logDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Kodhub\Reporter\Model\ResourceModel\Log $resource,
        \Kodhub\Reporter\Model\ResourceModel\Log\Collection $resourceCollection,
        array $data = []
    ) {
        $this->logDataFactory = $logDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve log model with log data
     * @return LogInterface
     */
    public function getDataModel()
    {
        $logData = $this->getData();
        
        $logDataObject = $this->logDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $logDataObject,
            $logData,
            LogInterface::class
        );
        
        return $logDataObject;
    }
}

