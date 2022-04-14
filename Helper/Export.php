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
        $this->_connection = $resource;
        $this->_fileSystem = $fileSystem;
        $this->_directoryList = $directoryList;
        $this->_csvProcessor = $csvProcessor;

        $this->_fileFolder = $this->_fileSystem->getDirectoryWrite($this->_directoryList::MEDIA);
    }

    /**
     * @param int $reportId
     * @param int $exportType
     * @param array $parameters
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Zend_Db_Statement_Exception
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

        try {
            switch ($exportType) {
                case self::EXPORT_CSV:
                    $exportFile = $this->exportCsv();
                    break;
                case self::EXPORT_EXCEL:
                    $exportFile = $this->exportExcel();
                    break;
                case self::EXPORT_HTML:
                    $exportFile = $this->exportHtml();
                    break;
                case self::EXPORT_JSON:
                default:
                    $exportFile = $this->exportJson();
                    break;
            }
        } catch (\Exception | \Throwable $exception) {
            $this->_logger->critical($exception->getMessage());
        }

        $this->_eventManager->dispatch(
            'kodhub_reporter_export_report_end',
            [
                'report' => $this->reportEntity,
                'exportType' => $exportType,
                'parameters' => $parameters,
                'exportFile' => $exportFile ?: ''
            ]
        );

        $this->reportEntity->setLastRunDate(date('Y-m-d H:i:s'));
        $this->reportRepository->save($this->reportEntity);

        return str_replace($this->_directoryList->getPath($this->_directoryList::PUB) . DS, $this->scopeConfig->getValue('web/secure/base_url'), $exportFile);
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Zend_Db_Statement_Exception
     */
    private function exportCsv()
    {
        $results =  $this->getResult();

        if (count($results) < 1) {
            return false;
        }

        $filePath = $this->fileNameGenerate('csv');

        $this->_fileFolder->touch($filePath);

        $this->_csvProcessor
            ->setEnclosure('"')
            ->setDelimiter(',')
            ->saveData($filePath, $results);

        return $filePath;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Zend_Db_Statement_Exception
     */
    private function exportJson()
    {
        $results = $this->getResult();

        if (count($results) < 1) {
            return false;
        }

        $filePath = $this->fileNameGenerate('json');

        $this->_fileFolder->touch($filePath);

        $this->_fileFolder->writeFile($filePath, json_encode($results));

        return $filePath;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Zend_Db_Statement_Exception
     */
    private function exportHtml()
    {
        $results = $this->getResult();

        if (count($results) < 1) {
            return false;
        }

        $filePath = $this->fileNameGenerate('html');

        $this->_fileFolder->touch($filePath);
        
        $html ='<!doctype html>
            <html lang="en" >
            <head>
                <meta charset="UTF-8">
                <meta name="viewport"
                      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
                <meta http-equiv="X-UA-Compatible" content="ie=edge">
                <title>'. $this->reportEntity->getName() .'</title>
            </head>
            <body>
        '; 

        $html .= $this->getCssStyleForHtmlTemplate();

        $html .= "<table>";
        $html .= "<caption> " . __('Report') . " " . $this->reportEntity->getName() . " </caption> " . PHP_EOL;

        $html .= "<thead> " . PHP_EOL;
        $html .= "<tr> " . PHP_EOL;

        foreach ($results as $key => $result) {
            if ($key == 0) {
                foreach ($result as $colKey => $col) {
                    $html .= "<th scope='col'> " . $colKey . "</th> " . PHP_EOL;
                }
                break;
            }
        }

        $html .= "</tr> " . PHP_EOL;
        $html .= "</thead> " . PHP_EOL;

        $html .= "<tbody> " . PHP_EOL;

        foreach ($results as $key => $result) {
            $html .= "<tr> " . PHP_EOL;

            foreach ($result as $colKey => $col) {
                $html .= "<td data-label='" . $colKey . "'> " . $col . "</td> " . PHP_EOL;
            }

            $html .= "</tr> " . PHP_EOL;
        }

        $html .= "<tbody> " . PHP_EOL;

        $html .= "</table> ";
        $html .= "</body> ";
        $html .= "</html> ";

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
        $results = $this->getResult();

        if (count($results) < 1) {
            return false;
        }

        $filePath = $this->fileNameGenerate('xlsx');

        $this->_fileFolder->touch($filePath);

        $sheet = new Spreadsheet();

        $page = $sheet->getActiveSheet();

        $page->fromArray($results);

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


        return $this->_connection
            ->getConnectionByName($this->reportEntity->getConnectionName())
            ->query($query)
            ->fetchAll();
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

    public function getCssStyleForHtmlTemplate()
    {
        return '<style>table{border:1px solid #ccc;border-collapse:collapse;margin:0;padding:0;width:100%;table-layout:fixed}table caption{font-size:1.5em;margin:.5em 0 .75em}table tr{background-color:#f8f8f8;border:1px solid #ddd;padding:.35em}table td,table th{word-wrap: break-word;border: 1px solid #ccc;padding:.625em;}table th{font-size:.85em;letter-spacing:.1em;text-transform:uppercase}@media screen and (max-width:600px){table{border:0}table caption{font-size:1.3em}table thead{border:none;clip:rect(0 0 0 0);height:1px;margin:-1px;overflow:hidden;padding:0;position:absolute;width:1px}table tr{border-bottom:5px solid #7d3434;display:block;margin-bottom:.625em}table td{border-bottom:1px solid #ddd;display:block;font-size:.8em;text-align:right}table td::before{content:attr(data-label);float:left;font-weight:700;text-transform:uppercase}table td:last-child{border-bottom:0}}body{font-family:"Open Sans",sans-serif;line-height:1.25}</style>';
    }
}

