<?php
/**
 * Copyright © ismail cakir All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kodhub\Reporter\Helper;

use Kodhub\Reporter\Model\ReportFactory;
use Kodhub\Reporter\Model\ReportRepository;
use Magento\Framework\App\Helper\AbstractHelper;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Export extends AbstractHelper
{
    const EXPORT_CSV = 0;
    const EXPORT_EXCEL = 1;
    const EXPORT_JSON = 2;
    const EXPORT_HTML = 3;
    const MODULE_MEDIA_FOLDER = "kodhub/reporter";

    /**
     * @var ReportRepository
     */
    protected $reportRepository;

    /**
     * @var \Kodhub\Reporter\Api\Data\ReportInterface
     */
    protected $reportEntity;

    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    protected $_connection;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $_fileSystem;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $_directoryList;

    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    protected $_fileFolder;

    /**
     * @var \Magento\Framework\File\Csv
     */
    protected $_csvProcessor;

    /**
     * @var array
     */
    protected $queryParameters;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        ReportRepository $reportRepository,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Framework\Filesystem $fileSystem,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\File\Csv $csvProcessor
    )
    {
        parent::__construct($context);

        $this->reportRepository = $reportRepository;
        $this->_connection = $resource->getConnection();
        $this->_fileSystem = $fileSystem;
        $this->_directoryList = $directoryList;
        $this->_csvProcessor = $csvProcessor;

        $this->_fileFolder = $this->_fileSystem->getDirectoryWrite($this->_directoryList::MEDIA);
    }

    /**
     * @param int $reportId
     * @param int $exportType
     * @param array $parameters
     */
    public function export(int $reportId, int $exportType, array $parameters = [])
    {
        $exportFile = NULL;

        $this->queryParameters = $parameters;

        $this->reportEntity = $this->reportRepository->get($reportId);

        $this->_eventManager->dispatch(
            'kodhub_reporter_export_report_start',
            [
                'report' => $this->reportEntity,
                'exportType' => $exportType,
                'parameters' => $this->queryParameters
            ]
        );

        if ($this->reportEntity->getQueryParameters()) {
            $requiredParameters = [];
            foreach (json_decode($this->reportEntity->getQueryParameters(), true) as $queryParameter) {
                if (
                    !isset($this->queryParameters[$queryParameter['key']]) ||
                    $this->queryParameters[$queryParameter['key']] === '' ||
                    $this->queryParameters[$queryParameter['key']] == NULL
                ) {
                    $requiredParameters[] = $queryParameter["label"] . ' --parameters[' . $queryParameter["key"] . '] is required.';
                }
            }

            if (count($requiredParameters) > 0) {
                $requiredParameters[] = __('This report cannot be exported from the command line and via cron. Because it is a parametric report.');
                throw new \Exception(implode(PHP_EOL, $requiredParameters));
            }
        }

        switch ($exportType) {
            case self::EXPORT_CSV:
                $exportFile = $this->exportCsv();
                break;
            case self::EXPORT_EXCEL:
                $exportFile = $this->exportExcel();
                break;
            case self::EXPORT_JSON:
                $exportFile = $this->exportJson();
                break;
            case self::EXPORT_HTML:
                $exportFile = $this->exportHtml();
                break;
            default:
                $exportFile = $this->exportJson();
                break;
        }

        $this->_eventManager->dispatch(
            'kodhub_reporter_export_report_end',
            [
                'report' => $this->reportEntity,
                'exportType' => $exportType,
                'parameters' => $parameters,
                'exportFile' => $exportFile
            ]
        );

        $this->reportEntity->setLastRunDate(date('Y-m-d H:i:s'));
        $this->reportRepository->save($this->reportEntity);

        return str_replace($this->_directoryList->getPath($this->_directoryList::PUB) . DS, $this->_urlBuilder->getBaseUrl(), $exportFile);
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Zend_Db_Statement_Exception
     */
    private function exportCsv()
    {
        $filePath = $this->fileNameGenerate('csv');

        $this->_fileFolder->touch($filePath);

        $this->_csvProcessor
            ->setEnclosure('"')
            ->setDelimiter(',')
            ->saveData($filePath, $this->getResult());

        return $filePath;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Zend_Db_Statement_Exception
     */
    private function exportJson()
    {
        $filePath = $this->fileNameGenerate('json');

        $this->_fileFolder->touch($filePath);

        $this->_fileFolder->writeFile($filePath, json_encode($this->getResult()));

        return $filePath;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Zend_Db_Statement_Exception
     */
    private function exportHtml()
    {
        $filePath = $this->fileNameGenerate('html');

        $this->_fileFolder->touch($filePath);

        $html = " < table><caption > " . __('Report') . " " . $this->reportEntity->getName() . " </caption > " . PHP_EOL;

        $html .= "<thead > " . PHP_EOL;

        foreach ($this->getResult() as $key => $result) {
            if ($key == 0) {
                foreach ($result as $colKey => $col) {
                    $html .= "<th style = 'border: 1px solid #000;min-width: 50px' > " . $colKey . "</th > " . PHP_EOL;
                }
                break;
            }
        }

        $html .= "</thead > " . PHP_EOL;

        $html .= "<tbody > " . PHP_EOL;

        foreach ($this->getResult() as $key => $result) {
            $html .= "<tr > " . PHP_EOL;

            foreach ($result as $col) {
                $html .= "<td style = 'border: 1px solid #000;min-width: 50px' > " . $col . "</td > " . PHP_EOL;
            }

            $html .= "</tr > " . PHP_EOL;
        }

        $html .= "<tbody > " . PHP_EOL;

        $html .= "</table > ";

        $this->_fileFolder->writeFile($filePath, $html);

        return $filePath;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws \Zend_Db_Statement_Exception
     */
    private function exportExcel()
    {
        $filePath = $this->fileNameGenerate('xlsx');

        $this->_fileFolder->touch($filePath);

        $sheet = new Spreadsheet();

        $page = $sheet->getActiveSheet();

        $page->fromArray($this->getResult());

        (new Xlsx($sheet))->save($filePath);

        return $filePath;
    }

    /**
     * @return array
     * @throws \Zend_Db_Statement_Exception
     */
    private function getResult()
    {
        $query = $this->reportEntity->getQuery();

        if (
            isset($this->queryParameters) &&
            is_array($this->queryParameters) &&
            count($this->queryParameters) > 0
        ) {
            foreach ($this->queryParameters as $key => $queryParameter) {
                $query = str_replace('{{' . $key . '}}', $queryParameter, $query);
            }
        }

        $result = $this->_connection->query($query)->fetchAll();

        if (count($result) < 1) {
            throw new \Exception('No records were found for this query.');
        }

        return $result;
    }

    /**
     * @param string $format
     * @return string
     */
    private function fileNameGenerate(string $format)
    {
        return sprintf(
            '%s/%s/%s/%s/%s.%s',
            $this->_directoryList->getPath($this->_directoryList::MEDIA),
            self::MODULE_MEDIA_FOLDER,
            date('Ymd'),
            $format,
            'report_' . $this->reportEntity->getReportId() . '_' . date('Ymdhis'),
            $format
        );
    }

    /**
     * @param string $url
     */
    public function getFilenameFromUrl(string $url)
    {
        $parse = explode('/', $url);
        return $parse[count($parse) - 1];
    }
}

