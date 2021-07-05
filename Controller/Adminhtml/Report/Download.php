<?php

namespace Kodhub\Reporter\Controller\Adminhtml\Report;

use Kodhub\Reporter\Helper\Export;
use Magento\Backend\App\Action\Context;
use Magento\Backend\App\Action;

class Download extends Action
{

    /**
     * @var Export
     */
    protected $exportHelper;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $jsonFactory;

    /**
     * Download constructor.
     * @param Context $context
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \Magento\Framework\View\LayoutFactory $layoutFactory
     * @param Export $exportHelper
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        Export $exportHelper
    )
    {
        $this->exportHelper = $exportHelper;
        $this->jsonFactory = $jsonFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();

        if ($data) {
            try {
                $exportFile = $this->exportHelper->export(
                    $this->getRequest()->getParam('report_id'),
                    $this->getRequest()->getParam('export_type'),
                    is_array($this->getRequest()->getParam('parameters')) ? $this->getRequest()->getParam('parameters') : []
                );

                if ($exportFile) {
                    return $resultJson->setStatusHeader(200)->setData([
                        'exportFile' => $exportFile,
                        'filename' => $this->exportHelper->getFilenameFromUrl($exportFile)
                    ]);
                }

                return $resultJson->setStatusHeader(500)->setData([
                    'message' => 'No records were found for this query.'
                ]);

            } catch (\Exception $exception) {
                return $resultJson->setStatusHeader(500)->setData([
                    'message' => $exception->getMessage()
                ]);
            } catch (\Throwable $exception) {
                return $resultJson->setStatusHeader(500)->setData([
                    'message' => $exception->getMessage()
                ]);
            }
        }
    }
}
