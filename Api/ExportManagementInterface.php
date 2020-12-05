<?php
/**
 * Copyright © ismail cakir All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Kodhub\Reporter\Api;

interface ExportManagementInterface
{

    /**
     * GET for Export api
     * @param string $param
     * @return string
     */
    public function getExport($param);
}

