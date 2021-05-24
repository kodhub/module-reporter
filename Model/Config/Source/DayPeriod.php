<?php

namespace Kodhub\Reporter\Model\Config\Source;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Option\ArrayInterface;

class DayPeriod implements ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return $this->getDays();
    }

    private function getDays()
    {
        return [
            ['value' => "3", 'label' => "3 Day",],
            ['value' => "7", 'label' => "7 Day",],
            ['value' => "14", 'label' => "14 Day",],
            ['value' => "21", 'label' => "21 Day",],
            ['value' => "30", 'label' => "30 Day",],
            ['value' => "90", 'label' => "90 Day",],
        ];
    }
}
