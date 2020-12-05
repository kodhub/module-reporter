<?php
/**
 * Copyright © ismail cakir All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kodhub\Reporter\Model\Report;

use Kodhub\Reporter\Model\ResourceModel\Report\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{

    protected $collection;

    protected $dataPersistor;

    protected $loadedData;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $model) {
            $this->loadedData[$model->getId()] = $this->dataSerialize($model->getData());
        }
        $data = $this->dataPersistor->get('kodhub_reporter_report');

        if (!empty($data)) {
            $model = $this->collection->getNewEmptyItem();
            $model->setData($data);
            $this->loadedData[$model->getId()] = $this->dataSerialize($model->getData());
            $this->dynamicRowsSerialize($this->loadedData[$model->getId()]);
            $this->dataPersistor->clear('kodhub_reporter_report');
        }

        return $this->loadedData;
    }

    /**
     * Serialize Data
     */
    private function dataSerialize($data)
    {
        if(isset($data['query_parameters'])) {
            $data['query_parameters_container'] = json_decode($data['query_parameters'], true);
        }

        if(isset($data['cron_email_list'])) {
            $data['cron_email_list_container'] = json_decode($data['cron_email_list'], true);
        }

        return $data;
    }
}

