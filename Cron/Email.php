<?php
/**
 * Copyright © ismail cakir All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kodhub\Reporter\Cron;

use Kodhub\Reporter\Helper\Export;
use Kodhub\Reporter\Helper\Functions;
use Kodhub\Reporter\Model\ReportRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;

class Email
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var Export
     */
    protected $exportHelper;

    /**
     * @var Functions
     */
    protected $functionsHelper;

    /** @var SearchCriteriaBuilder */
    protected $searchCriteriaBuilder;

    /**
     * Email constructor.
     * @param \Psr\Log\LoggerInterface $logger
     * @param Functions $functionsHelper
     * @param Export $exportHelper
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        Functions $functionsHelper,
        Export $exportHelper,
        ReportRepository $reportRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    )
    {
        $this->logger = $logger;
        $this->functionsHelper = $functionsHelper;
        $this->exportHelper = $exportHelper;
        $this->reportRepository = $reportRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute()
    {
        //$this->logger->critical("Cronjob Email is executed.");

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('cron_status', '1', 'eq')
            ->addFilter('cron_email_list', '[]', 'neq')
            ->addFilter('cron_expression', null, 'notnull')
            ->addFilter('query_parameters', '[]', 'eq')
            ->addFilter('status', '1', 'eq')
            ->create();

        $reports = $this->reportRepository->getList($searchCriteria)->getItems();

        foreach ($reports as $report) {
            $this->logger->critical($report->getName());

            if ($this->functionsHelper->shouldCronWork($report->getCronExpression())) {

                $sendEmailList = [];
                foreach ($report->getCronEmailList() as $email) {
                    if ($email['status'] === "1") {
                        $sendEmailList[] = $email['email'];
                        $this->logger->critical($email['email'] . ' email adresine gönderilmek üzere eklendi.');
                    }
                }

                if (count($sendEmailList) > 0) {
                    $this->logger->critical('Emailler bulundu.');

                    try {
                        $exportFile = $this->exportHelper->export((int)$report->getReportId(), (int)$report->getCronExportType());

                        $this->logger->critical($exportFile . ' oluşturuldu.');
                    } catch (\Exception $exception) {
                        $this->logger->critical($exception->getMessage());
                    } catch (\Throwable $exception) {
                        $this->logger->critical($exception->getMessage());
                    }

                } else {
                    $this->logger->critical('Gönderilecek email bulunamadı.');
                }

                $this->logger->critical($report->getName() . ' cronu çalıştı. zamanı geldi.');
            } else {
                $this->logger->critical($report->getName() . ' cronu çalışmadı. zamanı gelmedi.');
            }
        }
    }
}

