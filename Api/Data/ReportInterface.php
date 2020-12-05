<?php
/**
 * Copyright © ismail cakir All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kodhub\Reporter\Api\Data;

interface ReportInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const UPDATED_AT = 'updated_at';
    const DESCRIPTION = 'description';
    const CRON_EXPRESSION = 'cron_expression';
    const CREATED_AT = 'created_at';
    const CRON_STATUS = 'cron_status';
    const REPORT_ID = 'report_id';
    const NAME = 'name';
    const STATUS = 'status';
    const QUERY = 'query';
    const CRON_EMAIL_LIST = 'cron_email_list';

    /**
     * Get report_id
     * @return string|null
     */
    public function getReportId();

    /**
     * Set report_id
     * @param string $reportId
     * @return \Kodhub\Reporter\Api\Data\ReportInterface
     */
    public function setReportId($reportId);

    /**
     * Get name
     * @return string|null
     */
    public function getName();

    /**
     * Set name
     * @param string $name
     * @return \Kodhub\Reporter\Api\Data\ReportInterface
     */
    public function setName($name);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Kodhub\Reporter\Api\Data\ReportExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Kodhub\Reporter\Api\Data\ReportExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Kodhub\Reporter\Api\Data\ReportExtensionInterface $extensionAttributes
    );

    /**
     * Get query
     * @return string|null
     */
    public function getQuery();

    /**
     * Set query
     * @param string $query
     * @return \Kodhub\Reporter\Api\Data\ReportInterface
     */
    public function setQuery($query);

    /**
     * Get cron_status
     * @return string|null
     */
    public function getCronStatus();

    /**
     * Set cron_status
     * @param string $cronStatus
     * @return \Kodhub\Reporter\Api\Data\ReportInterface
     */
    public function setCronStatus($cronStatus);

    /**
     * Get cron_email_list
     * @return string|null
     */
    public function getCronEmailList();

    /**
     * Set cron_email_list
     * @param string $cronEmailList
     * @return \Kodhub\Reporter\Api\Data\ReportInterface
     */
    public function setCronEmailList($cronEmailList);

    /**
     * Get cron_expression
     * @return string|null
     */
    public function getCronExpression();

    /**
     * Set cron_expression
     * @param string $cronExpression
     * @return \Kodhub\Reporter\Api\Data\ReportInterface
     */
    public function setCronExpression($cronExpression);

    /**
     * Get description
     * @return string|null
     */
    public function getDescription();

    /**
     * Set description
     * @param string $description
     * @return \Kodhub\Reporter\Api\Data\ReportInterface
     */
    public function setDescription($description);

    /**
     * Get status
     * @return string|null
     */
    public function getStatus();

    /**
     * Set status
     * @param string $status
     * @return \Kodhub\Reporter\Api\Data\ReportInterface
     */
    public function setStatus($status);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Kodhub\Reporter\Api\Data\ReportInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get updated_at
     * @return string|null
     */
    public function getUpdatedAt();

    /**
     * Set updated_at
     * @param string $updatedAt
     * @return \Kodhub\Reporter\Api\Data\ReportInterface
     */
    public function setUpdatedAt($updatedAt);
}

