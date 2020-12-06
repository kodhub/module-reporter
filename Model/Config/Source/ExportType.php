<?php

namespace Kodhub\Reporter\Model\Config\Source;

class ExportType implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 0, 'label' => __('Csv')],
            ['value' => 1, 'label' => __('Excel')],
            ['value' => 2, 'label' => __('Json')],
            ['value' => 3, 'label' => __('Html')]
        ];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [
            0 => __('Csv'),
            1 => __('Excel'),
            2 => __('Json'),
            3 => __('Html'),
        ];
    }
}
