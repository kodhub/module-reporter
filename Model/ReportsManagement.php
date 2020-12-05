<?php
/**
 * Copyright © ismail cakir All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kodhub\Reporter\Model;

class ReportsManagement implements \Kodhub\Reporter\Api\ReportsManagementInterface
{

    /**
     * {@inheritdoc}
     */
    public function getReports($param)
    {
        return 'hello api GET return the $param ' . $param;
    }
}

