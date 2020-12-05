<?php
/**
 * Copyright © ismail cakir All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kodhub\Reporter\Api\Data;

interface LogInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const TITLE = 'title';
    const CRON_IS_RUN = 'cron_is_run';
    const LAST_RUN_DATE = 'last_run_date';
    const ERROR = 'error';
    const SENT_LIST = 'sent_list';
    const TRACE = 'trace';
    const LOG_ID = 'log_id';
    const WORK_TIME = 'work_time';
    const ENDED_AT = 'ended_at';
    const STARTED_AT = 'started_at';
    const MESSAGE = 'message';
    const QUERY = 'query';

    /**
     * Get log_id
     * @return string|null
     */
    public function getLogId();

    /**
     * Set log_id
     * @param string $logId
     * @return \Kodhub\Reporter\Api\Data\LogInterface
     */
    public function setLogId($logId);

    /**
     * Get title
     * @return string|null
     */
    public function getTitle();

    /**
     * Set title
     * @param string $title
     * @return \Kodhub\Reporter\Api\Data\LogInterface
     */
    public function setTitle($title);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Kodhub\Reporter\Api\Data\LogExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Kodhub\Reporter\Api\Data\LogExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Kodhub\Reporter\Api\Data\LogExtensionInterface $extensionAttributes
    );

    /**
     * Get message
     * @return string|null
     */
    public function getMessage();

    /**
     * Set message
     * @param string $message
     * @return \Kodhub\Reporter\Api\Data\LogInterface
     */
    public function setMessage($message);

    /**
     * Get error
     * @return string|null
     */
    public function getError();

    /**
     * Set error
     * @param string $error
     * @return \Kodhub\Reporter\Api\Data\LogInterface
     */
    public function setError($error);

    /**
     * Get trace
     * @return string|null
     */
    public function getTrace();

    /**
     * Set trace
     * @param string $trace
     * @return \Kodhub\Reporter\Api\Data\LogInterface
     */
    public function setTrace($trace);

    /**
     * Get query
     * @return string|null
     */
    public function getQuery();

    /**
     * Set query
     * @param string $query
     * @return \Kodhub\Reporter\Api\Data\LogInterface
     */
    public function setQuery($query);

    /**
     * Get sent_list
     * @return string|null
     */
    public function getSentList();

    /**
     * Set sent_list
     * @param string $sentList
     * @return \Kodhub\Reporter\Api\Data\LogInterface
     */
    public function setSentList($sentList);

    /**
     * Get cron_is_run
     * @return string|null
     */
    public function getCronIsRun();

    /**
     * Set cron_is_run
     * @param string $cronIsRun
     * @return \Kodhub\Reporter\Api\Data\LogInterface
     */
    public function setCronIsRun($cronIsRun);

    /**
     * Get last_run_date
     * @return string|null
     */
    public function getLastRunDate();

    /**
     * Set last_run_date
     * @param string $lastRunDate
     * @return \Kodhub\Reporter\Api\Data\LogInterface
     */
    public function setLastRunDate($lastRunDate);

    /**
     * Get work_time
     * @return string|null
     */
    public function getWorkTime();

    /**
     * Set work_time
     * @param string $workTime
     * @return \Kodhub\Reporter\Api\Data\LogInterface
     */
    public function setWorkTime($workTime);

    /**
     * Get started_at
     * @return string|null
     */
    public function getStartedAt();

    /**
     * Set started_at
     * @param string $startedAt
     * @return \Kodhub\Reporter\Api\Data\LogInterface
     */
    public function setStartedAt($startedAt);

    /**
     * Get ended_at
     * @return string|null
     */
    public function getEndedAt();

    /**
     * Set ended_at
     * @param string $endedAt
     * @return \Kodhub\Reporter\Api\Data\LogInterface
     */
    public function setEndedAt($endedAt);
}

