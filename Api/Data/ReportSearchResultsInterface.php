<?php
/**
 * Copyright © ismail cakir All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kodhub\Reporter\Api\Data;

interface ReportSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Report list.
     * @return \Kodhub\Reporter\Api\Data\ReportInterface[]
     */
    public function getItems();

    /**
     * Set name list.
     * @param \Kodhub\Reporter\Api\Data\ReportInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

