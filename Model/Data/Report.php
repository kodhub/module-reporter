<?php
/**
 * Copyright © ismail cakir All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kodhub\Reporter\Model\Data;

use Kodhub\Reporter\Api\Data\ReportInterface;

class Report extends \Magento\Framework\Api\AbstractExtensibleObject implements ReportInterface
{

    /**
     * Get report_id
     * @return string|null
     */
    public function getReportId()
    {
        return $this->_get(self::REPORT_ID);
    }

    /**
     * Set report_id
     * @param string $reportId
     * @return \Kodhub\Reporter\Api\Data\ReportInterface
     */
    public function setReportId($reportId)
    {
        return $this->setData(self::REPORT_ID, $reportId);
    }

    /**
     * Get name
     * @return string|null
     */
    public function getName()
    {
        return $this->_get(self::NAME);
    }

    /**
     * Set name
     * @param string $name
     * @return \Kodhub\Reporter\Api\Data\ReportInterface
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Kodhub\Reporter\Api\Data\ReportExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Kodhub\Reporter\Api\Data\ReportExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Kodhub\Reporter\Api\Data\ReportExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get query
     * @return string|null
     */
    public function getQuery()
    {
        return $this->_get(self::QUERY);
    }

    /**
     * Set query
     * @param string $query
     * @return \Kodhub\Reporter\Api\Data\ReportInterface
     */
    public function setQuery($query)
    {
        return $this->setData(self::QUERY, $query);
    }

    /**
     * Get cron_status
     * @return string|null
     */
    public function getCronStatus()
    {
        return $this->_get(self::CRON_STATUS);
    }

    /**
     * Set cron_status
     * @param string $cronStatus
     * @return \Kodhub\Reporter\Api\Data\ReportInterface
     */
    public function setCronStatus($cronStatus)
    {
        return $this->setData(self::CRON_STATUS, $cronStatus);
    }

    /**
     * Get cron_email_list
     * @return string|null
     */
    public function getCronEmailList()
    {
        return json_decode($this->_get(self::CRON_EMAIL_LIST), true);
    }

    /**
     * Set cron_email_list
     * @param string $cronEmailList
     * @return \Kodhub\Reporter\Api\Data\ReportInterface
     */
    public function setCronEmailList($cronEmailList)
    {
        if (is_array($cronEmailList)) {
            $cronEmailList = json_encode($cronEmailList);
        }

        return $this->setData(self::CRON_EMAIL_LIST, $cronEmailList);
    }

    /**
     * Get cron_expression
     * @return string|null
     */
    public function getCronExpression()
    {
        return $this->_get(self::CRON_EXPRESSION);
    }

    /**
     * Set cron_expression
     * @param string $cronExpression
     * @return \Kodhub\Reporter\Api\Data\ReportInterface
     */
    public function setCronExpression($cronExpression)
    {
        return $this->setData(self::CRON_EXPRESSION, $cronExpression);
    }

    /**
     * Get description
     * @return string|null
     */
    public function getDescription()
    {
        return $this->_get(self::DESCRIPTION);
    }

    /**
     * Set description
     * @param string $description
     * @return \Kodhub\Reporter\Api\Data\ReportInterface
     */
    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * Get status
     * @return string|null
     */
    public function getStatus()
    {
        return $this->_get(self::STATUS);
    }

    /**
     * Set status
     * @param string $status
     * @return \Kodhub\Reporter\Api\Data\ReportInterface
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt()
    {
        return $this->_get(self::CREATED_AT);
    }

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Kodhub\Reporter\Api\Data\ReportInterface
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Get updated_at
     * @return string|null
     */
    public function getUpdatedAt()
    {
        return $this->_get(self::UPDATED_AT);
    }

    /**
     * Set updated_at
     * @param string $updatedAt
     * @return \Kodhub\Reporter\Api\Data\ReportInterface
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * Get last_run_date
     * @return string|null
     */
    public function getLastRunDate()
    {
        return $this->_get(self::LAST_RUN_DATE);
    }

    /**
     * Set last_run_date
     * @param string $lastRunDate
     * @return \Kodhub\Reporter\Api\Data\ReportInterface
     */
    public function setLastRunDate($lastRunDate)
    {
        return $this->setData(self::LAST_RUN_DATE, $lastRunDate);
    }

    /**
     * Get cron_export_type
     * @return string|null
     */
    public function getCronExportType()
    {
        return $this->_get(self::CRON_EXPORT_TYPE);
    }

    /**
     * Set cron_export_type
     * @param string $cronExportType
     * @return \Kodhub\Reporter\Api\Data\ReportInterface
     */
    public function setCronExportType($cronExportType)
    {
        return $this->setData(self::CRON_EXPORT_TYPE, $cronExportType);
    }

    /**
     * Get query_parameters
     * @return string|null
     */
    public function getQueryParameters()
    {
        return json_decode($this->_get(self::QUERY_PARAMETERS), true);
    }

    /**
     * Set query_parameters
     * @param string $queryParameters
     * @return \Kodhub\Reporter\Api\Data\ReportInterface
     */
    public function setQueryParameters($queryParameters)
    {
        if (is_array($queryParameters)) {
            $queryParameters = json_encode($queryParameters);
        }

        return $this->setData(self::QUERY_PARAMETERS, $queryParameters);
    }
}

