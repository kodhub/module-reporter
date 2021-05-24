<?php
/**
 * Copyright © ismail cakir All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kodhub\Reporter\Cron;

use Kodhub\Reporter\Helper\Config;
use Kodhub\Reporter\Helper\Functions;

class FileClear
{
    const MODULE_MEDIA_FOLDER = "kodhub/reporter";

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var Config
     */
    protected $moduleConfig;

    /**
     * @var Functions
     */
    protected $helperFunctions;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $directoryList;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    protected $driverFile;

    /**
     * Constructor
     *
     * @param \Psr\Log\LoggerInterface $logger
     * @param Config $moduleConfig
     * @param Functions $helperFunctions
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     * @param \Magento\Framework\Filesystem $filesystem
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        Config $moduleConfig,
        Functions $helperFunctions,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Filesystem\Driver\File $driverFile
    )
    {
        $this->logger = $logger;
        $this->moduleConfig = $moduleConfig;
        $this->helperFunctions = $helperFunctions;
        $this->directoryList = $directoryList;
        $this->filesystem = $filesystem;
        $this->driverFile = $driverFile;
    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute()
    {
        $excludeFolder = $this->helperFunctions->getLastNDays(
            (int)$this->moduleConfig->getFileClearPeriod()
        );

        try {
            $path = $this->directoryList->getPath($this->directoryList::MEDIA) . DIRECTORY_SEPARATOR . self::MODULE_MEDIA_FOLDER;
            $paths = $this->driverFile->readDirectory($path);

            foreach ($excludeFolder as &$item) {
                $item = $path . DIRECTORY_SEPARATOR . str_replace('"', '', $item);
            }

            foreach ($paths as $folder) {
                if (in_array($folder, $excludeFolder)) {
                    break;
                }
                $this->driverFile->deleteDirectory($folder);
            }
        } catch (\Exception $exception) {
            $this->logger->error("Kodhub File Clear Error", ["context" => $exception->getTrace()]);
            die;
        }
    }
}

