<?php
/**
 * Copyright © ismail cakir All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kodhub\Reporter\Model\Data;

use Kodhub\Reporter\Api\Data\LogInterface;
//@todo @emrah \Magento\Framework\Model\AbstractExtensibleModel
class Log extends \Magento\Framework\Api\AbstractExtensibleObject implements LogInterface
{
    /**
     * Get log_id
     * @return string|null
     */
    public function getLogId()
    {
        return $this->_get(self::LOG_ID);
    }

    /**
     * Set log_id
     * @param string $logId
     * @return \Kodhub\Reporter\Api\Data\LogInterface
     */
    public function setLogId($logId)
    {
        return $this->setData(self::LOG_ID, $logId);
    }

    /**
     * Get title
     * @return string|null
     */
    public function getTitle()
    {
        return $this->_get(self::TITLE);
    }

    /**
     * Set title
     * @param string $title
     * @return \Kodhub\Reporter\Api\Data\LogInterface
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Kodhub\Reporter\Api\Data\LogExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Kodhub\Reporter\Api\Data\LogExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Kodhub\Reporter\Api\Data\LogExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get message
     * @return string|null
     */
    public function getMessage()
    {
        return $this->_get(self::MESSAGE);
    }

    /**
     * Set message
     * @param string $message
     * @return \Kodhub\Reporter\Api\Data\LogInterface
     */
    public function setMessage($message)
    {
        return $this->setData(self::MESSAGE, $message);
    }

    /**
     * Get error
     * //@todo change field name hasError
     *
     * @return string|null
     */
    public function getError()
    {
        return $this->_get(self::ERROR);
    }

    /**
     * Set error
     * @param string $error
     * @return \Kodhub\Reporter\Api\Data\LogInterface
     */
    public function setError($error)
    {
        return $this->setData(self::ERROR, $error);
    }

    /**
     * Get trace
     * @return string|null
     */
    public function getTrace()
    {
        return $this->_get(self::TRACE);
    }

    /**
     * Set trace
     * @param string $trace
     * @return \Kodhub\Reporter\Api\Data\LogInterface
     */
    public function setTrace($trace)
    {
        return $this->setData(self::TRACE, $trace);
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
     * @return \Kodhub\Reporter\Api\Data\LogInterface
     */
    public function setQuery($query)
    {
        return $this->setData(self::QUERY, $query);
    }

    /**
     * Get sent_list
     * @return string|null
     */
    public function getSentList()
    {
        return $this->_get(self::SENT_LIST);
    }

    /**
     * Set sent_list
     * @param string $sentList
     * @return \Kodhub\Reporter\Api\Data\LogInterface
     */
    public function setSentList($sentList)
    {
        return $this->setData(self::SENT_LIST, $sentList);
    }

    /**
     * Get cron_is_run
     * @return string|null
     */
    public function getCronIsRun()
    {
        return $this->_get(self::CRON_IS_RUN);
    }

    /**
     * Set cron_is_run
     * //@todo changeField name $isCron
     * @param string $cronIsRun
     * @return \Kodhub\Reporter\Api\Data\LogInterface
     */
    public function setCronIsRun($cronIsRun)
    {
        return $this->setData(self::CRON_IS_RUN, $cronIsRun);
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
     * @return \Kodhub\Reporter\Api\Data\LogInterface
     */
    public function setLastRunDate($lastRunDate)
    {
        return $this->setData(self::LAST_RUN_DATE, $lastRunDate);
    }

    /**
     * Get work_time
     * @return string|null
     */
    public function getWorkTime()
    {
        return $this->_get(self::WORK_TIME);
    }

    /**
     * Set work_time
     * @param string $workTime
     * @return \Kodhub\Reporter\Api\Data\LogInterface
     */
    public function setWorkTime($workTime)
    {
        return $this->setData(self::WORK_TIME, $workTime);
    }

    /**
     * Get started_at
     * @return string|null
     */
    public function getStartedAt()
    {
        return $this->_get(self::STARTED_AT);
    }

    /**
     * Set started_at
     * @param string $startedAt
     * @return \Kodhub\Reporter\Api\Data\LogInterface
     */
    public function setStartedAt($startedAt)
    {
        return $this->setData(self::STARTED_AT, $startedAt);
    }

    /**
     * Get ended_at
     * @return string|null
     */
    public function getEndedAt()
    {
        return $this->_get(self::ENDED_AT);
    }

    /**
     * Set ended_at
     * @param string $endedAt
     * @return \Kodhub\Reporter\Api\Data\LogInterface
     */
    public function setEndedAt($endedAt)
    {
        return $this->setData(self::ENDED_AT, $endedAt);
    }
}

