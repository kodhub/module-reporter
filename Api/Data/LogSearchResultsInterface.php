<?php
/**
 * Copyright © ismail cakir All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kodhub\Reporter\Api\Data;

interface LogSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Log list.
     * @return \Kodhub\Reporter\Api\Data\LogInterface[]
     */
    public function getItems();

    /**
     * Set title list.
     * @param \Kodhub\Reporter\Api\Data\LogInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}

