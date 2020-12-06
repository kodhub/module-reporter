<?php
namespace Kodhub\Reporter\Block\Adminhtml\Report;

use Kodhub\Reporter\Model\ReportRepository;

class GenerateView extends \Magento\Backend\Block\Template
{
    /**
     * @var string
     */
    public $_template = 'Kodhub_Reporter::view/generate-view.phtml';

    /**
     * @var ReportRepository
     */
    public $reportRepository;

    /**
     * GenerateView constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param ReportRepository $reportRepository
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        ReportRepository $reportRepository
    ) {
        parent::__construct($context);
        $this->reportRepository = $reportRepository;
    }

    /**
     * @return \Kodhub\Reporter\Api\Data\ReportInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getReportEntity()
    {
        return $this->reportRepository->get($this->getRequest()->getParam('id'));
    }
}
