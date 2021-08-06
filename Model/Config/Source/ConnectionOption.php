<?php

namespace Kodhub\Reporter\Model\Config\Source;

class ConnectionOption implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var \Magento\Framework\App\DeploymentConfig
     */
    private $deploymentConfig;

    public function __construct(
        \Magento\Framework\App\DeploymentConfig $deploymentConfig
    )
    {
        $this->deploymentConfig = $deploymentConfig;
    }

    /**
     * Options getter
     *
     * @return array
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\RuntimeException
     */
    public function toOptionArray()
    {
        $values = [];
        foreach ($this->deploymentConfig->get('db/connection') as $key => $item) {
            $values[] = ['value' => $key, 'label' => $key];
        }
        return $values;
    }
}
