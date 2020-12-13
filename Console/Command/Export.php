<?php
/**
 * Copyright © ismail cakir All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kodhub\Reporter\Console\Command;

use Kodhub\Reporter\Model\ReportRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Export extends Command
{
    const REPORTID_OPTION = "report_id";
    const EXPORTTYPE_OPTION = "export_type";
    const PARAMTERS_OPTION = "parameters";

    /**
     * {@inheritdoc}
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    )
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager

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

        /**
         * TODO @ismailcaakir
         * İsteğe bağlı email gönderme özelliği eklenecek..
         */

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

        $this->setName("kodhub:reporter:export");
        $this->setDescription("Export your Report");
        $availableFormats = implode(',', $exportTypeSource->toArray());
        $this->setDefinition([
            new InputOption(self::REPORTID_OPTION, "-i", InputOption::VALUE_REQUIRED, "Report ID"),
            new InputOption(self::EXPORTTYPE_OPTION, "-e", InputOption::VALUE_REQUIRED, "Export Type (available: " . $availableFormats),
        ]);
        parent::configure();
    }
}

