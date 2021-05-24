<?php
/**
 * Copyright © ismail cakir All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kodhub\Reporter\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Functions extends AbstractHelper
{

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * @param string $frequency
     * @param bool $time
     * @return mixed
     */
    function shouldCronWork($frequency = '* * * * *', $time = false)
    {
        $time = is_string($time) ? strtotime($time) : time();
        $time = explode(' ', date('i G j n w', $time));
        $time[0] = $time[0] + 0;
        $crontab = explode(' ', $frequency);
        foreach ($crontab as $k => &$v) {
            $v = explode(',', $v);
            $regexps = array(
                '/^\*$/', # every
                '/^\d+$/', # digit
                '/^(\d+)\-(\d+)$/', # range
                '/^\*\/(\d+)$/' # every digit
            );
            $time[$k] = $time[$k] + 0;
            $content = array(
                "true", # every
                "{$time[$k]} === $0", # digit
                "($1 <= {$time[$k]} && {$time[$k]} <= $2)", # range
                "{$time[$k]} % $1 === 0" # every digit
            );
            foreach ($v as &$v1)
                $v1 = preg_replace($regexps, $content, $v1);
            $v = '('.implode(' || ', $v).')';
        }
        $crontab = implode(' && ', $crontab);
        return eval("return {$crontab};");
    }

    /**
     * @param $days
     * @param string $format
     * @return array
     */
    public function getLastNDays($days, $format = "Ymd")
    {
        $m = date("m");
        $de = date("d");
        $y = date("Y");

        $dateArray = array();
        for ($i = 0; $i <= $days - 1; $i++) {
            $dateArray[] = '"' . date($format, mktime(0, 0, 0, (int)$m, ($de - $i), (int)$y)) . '"';
        }
        return array_reverse($dateArray);
    }
}

