<?php

declare(strict_types = 1);

namespace ModuleBench\Models;

use Framework\Baseapp\Models\AbstractModel as AbstractModelBase;

class AbstractModel extends AbstractModelBase
{
    protected $connection = 'bench';

    protected function getAppcode()
    {
        return 'bench';
    }
}
