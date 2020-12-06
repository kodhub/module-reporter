<?php

namespace Kodhub\Reporter\Controller\Adminhtml\Report;

use Kodhub\Reporter\Block\Adminhtml\Report\GenerateView;
use Magento\Backend\App\Action\Context;
use Magento\Backend\App\Action;

class Generate extends Action
{
    protected $resultRawFactory;
    protected $layoutFactory;

    public function __construct(
        Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\View\LayoutFactory $layoutFactory
    ) {
        $this->resultRawFactory = $resultRawFactory;
        $this->layoutFactory = $layoutFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $content = $this->layoutFactory->create()
            ->createBlock(
                GenerateView::class
            );

        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->resultRawFactory->create();
        return $resultRaw->setContents($content->toHtml());
    }
}
