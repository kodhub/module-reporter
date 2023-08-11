<?php
/**
 * Copyright © ismail cakir All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kodhub\Reporter\Console\Command;

use Kodhub\Reporter\Model\ReportRepository;
use Magento\Framework\App\Area;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\Store;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Export extends Command
{
    const REPORTID_OPTION = "report_id";
    const EXPORTTYPE_OPTION = "export_type";

    const EMAIL_OPTION = "email";

    const PARAMTERS_OPTION = "parameters";

    /**
     * {@inheritdoc}
     */
    protected function execute(
        InputInterface  $input,
        OutputInterface $output
    )
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
        $objectManager->get(\Magento\Framework\App\State::class)->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);
        $objectManager->configure(
            $objectManager->get(\Magento\Framework\ObjectManager\ConfigLoaderInterface::class)->load('adminhtml')
        );

        /**
         * @var $exportHelper \Kodhub\Reporter\Helper\Export
         */
        $exportHelper = $objectManager->get(\Kodhub\Reporter\Helper\Export::class);

        /**
         * @var $exportTypeSource \Kodhub\Reporter\Model\Config\Source\ExportType
         */
        $exportTypeSource = $objectManager->get(\Kodhub\Reporter\Model\Config\Source\ExportType::class);

        $exportTypes = $exportTypeSource->toArray();

        $reportId = $input->getOption(self::REPORTID_OPTION);

        $exportType = $input->getOption(self::EXPORTTYPE_OPTION);

        if (empty($reportId)) {
            throw new \Exception('--report_id is required');
        }

        if (empty($exportType)) {
            throw new \Exception('--export_type is required');
        }

        if (!in_array($exportType, $exportTypes)) {
            throw new \Exception('Invalid export type ( Available formats: ' . implode(',', $exportTypeSource->toArray()) . ' )');
        }

        $exportType = array_search($exportType, $exportTypes);

        $exportFile = $exportHelper->export((int)$reportId, (int)$exportType);

        if (true) {
            /**
             * @var $logger \Psr\Log\LoggerInterface
             */
            $logger = $objectManager->get(\Psr\Log\LoggerInterface::class);

            /**
             * @var $reportRepository \Kodhub\Reporter\Model\ReportRepository
             */
            $reportRepository = $objectManager->get(\Kodhub\Reporter\Model\ReportRepository::class);

            /**
             * @var $report \Kodhub\Reporter\Model\Report
             */
            $report = $reportRepository->get((int)$reportId);

            $sendEmailList = $input->getOption(self::EMAIL_OPTION);

            if (count($sendEmailList) > 0) {
                try {
                    if (isset($exportFile) && $exportFile != "") {
                        foreach ($sendEmailList as $email) {
                            $this->sendEmail($email, $exportFile, $report->getName(), $report->getDescription());
                            $output->writeln("EMAIL SENT  " . $email);
                        }
                    } else {
                        $logger->critical("Export file is empty");
                    }
                } catch (\Exception|\Throwable $exception) {
                    $logger->critical($exception->getMessage());
                }
            } else {
                $logger->critical('The email address to send to was not found.');
            }
        }

        $output->writeln("Generate report file ->  " . $exportFile);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager

        /**
         * @var $exportTypeSource \Kodhub\Reporter\Model\Config\Source\ExportType
         */
        $exportTypeSource = $objectManager->get(\Kodhub\Reporter\Model\Config\Source\ExportType::class);
        $availableFormats = implode(',', $exportTypeSource->toArray());
        $this->setName("kodhub:reporter:export")
            ->setDescription("Export your Report")
            ->addOption(
                self::REPORTID_OPTION,
                "-i",
                InputOption::VALUE_REQUIRED,
                "Report ID"
            )
            ->addOption(
                self::EXPORTTYPE_OPTION,
                "-e",
                InputOption::VALUE_REQUIRED,
                "Export Type (available: " . $availableFormats . ")"
            )
            ->addOption(
                self::EMAIL_OPTION,
                "-s",
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
                "Email addresses"
            );
        parent::configure();
    }

    /**
     * @param $to
     * @param $exportFile
     * @param $reportName
     * @param $reportDescription
     */
    private function sendEmail($to, $exportFile, $reportName, $reportDescription)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager

        $objectManager->configure($objectManager->get(\Magento\Framework\ObjectManager\ConfigLoaderInterface::class)->load('adminhtml'));

        /**
         * @var $_transportBuilder  \Magento\Framework\Mail\Template\TransportBuilder

         */
        $_transportBuilder = $objectManager->get(\Magento\Framework\Mail\Template\TransportBuilder::class);

        /**
         * @var $senderResolver \Magento\Email\Model\Template\SenderResolver
         */
        $_senderResolver = $objectManager->get(\Magento\Email\Model\Template\SenderResolver::class);

        /**
         * @var $_logger \Psr\Log\LoggerInterface
         */
        $_logger = $objectManager->get(\Psr\Log\LoggerInterface::class);

        $_transportBuilder
            ->setTemplateIdentifier('kodhub_reporter_send_report_template')
            ->setTemplateOptions(['area' => Area::AREA_FRONTEND, 'store' => Store::DEFAULT_STORE_ID])
            ->setTemplateVars(
                [
                    'export_file' => $exportFile,
                    'report_name' => $reportName,
                    'report_description' => $reportDescription,
                    'generated_date' => date('Y-m-d H:i:s')
                ]
            )
            ->setFrom($_senderResolver->resolve('general'))
            ->addTo($to);

        try {
            $_transportBuilder->getTransport()->sendMessage();
        } catch (\Exception $exception) {
            $_logger->critical($exception->getMessage());
        }
    }
}
