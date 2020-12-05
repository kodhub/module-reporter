<?php
/**
 * Copyright © ismail cakir All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kodhub\Reporter\Api;

interface ReportsManagementInterface
{

    /**
     * GET for Reports api
     * @param string $param
     * @return string
     */
    public function getReports($param);
}

