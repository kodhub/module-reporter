<?php
/**
 * Copyright © ismail cakir All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kodhub\Reporter\Model;

class ExportManagement implements \Kodhub\Reporter\Api\ExportManagementInterface
{

    /**
     * {@inheritdoc}
     */
    public function getExport($param)
    {
        return 'hello api GET return the $param ' . $param;
    }
}

