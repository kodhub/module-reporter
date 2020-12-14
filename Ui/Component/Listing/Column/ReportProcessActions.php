<?php
/**
 * Copyright © ismail cakir All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kodhub\Reporter\Ui\Component\Listing\Column;

class ReportProcessActions extends \Magento\Ui\Component\Listing\Columns\Column
{
    public $urlBuilder;
    public $layout;

    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\View\LayoutInterface $layout,
        array $components = [],
        array $data = []
    )
    {
        $this->urlBuilder = $urlBuilder;
        $this->layout = $layout;

        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function getViewUrl()
    {
        return $this->urlBuilder->getUrl(
            $this->getData('config/viewUrlPath')
        );
    }

    public function prepareDataSource(array $dataSource)
    {
        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }
        foreach ($dataSource['data']['items'] as & $item) {
            if (isset($item['report_id'])) {
                $item[$this->getData('name')] = $this->layout->createBlock(
                    \Magento\Backend\Block\Widget\Button::class,
                    '',
                    [
                        'data' => [
                            'label' => __('Generate'),
                            'type' => 'button',
                            'disabled' => false,
                            'class' => 'reporter-generate-modal-button',
                            'onclick' => 'reporterGenerateModal.open(\'' . $this->getViewUrl() . '\', \'' . $item['report_id'] . '\')',
                        ]
                    ]
                )->toHtml();
            }
        }

        return $dataSource;
    }
}
