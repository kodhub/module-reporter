<?php
/**
 * Copyright © ismail cakir All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kodhub\Reporter\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Config extends AbstractHelper
{
    const BASE_PATH = "reporter";
    const GENERAL = "general";

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return true;
    }

    /**
     * @param string $sectionPath
     * @param string $fieldPath
     * @return string
     */
    private function getConfigPath(string $sectionPath, string $fieldPath)
    {
        return sprintf('%s/%s/%s', self::BASE_PATH, $sectionPath, $fieldPath);
    }

    /**
     * @return string
     */
    public function getFileClearPeriod()
    {
        return $this->scopeConfig->getValue($this->getConfigPath(self::GENERAL, 'file_clear_period'));
    }
}

