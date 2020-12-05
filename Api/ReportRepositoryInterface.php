<?php
/**
 * Copyright © ismail cakir All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kodhub\Reporter\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ReportRepositoryInterface
{

    /**
     * Save Report
     * @param \Kodhub\Reporter\Api\Data\ReportInterface $report
     * @return \Kodhub\Reporter\Api\Data\ReportInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Kodhub\Reporter\Api\Data\ReportInterface $report
    );

    /**
     * Retrieve Report
     * @param string $reportId
     * @return \Kodhub\Reporter\Api\Data\ReportInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($reportId);

    /**
     * Retrieve Report matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Kodhub\Reporter\Api\Data\ReportSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Report
     * @param \Kodhub\Reporter\Api\Data\ReportInterface $report
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Kodhub\Reporter\Api\Data\ReportInterface $report
    );

    /**
     * Delete Report by ID
     * @param string $reportId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($reportId);
}

