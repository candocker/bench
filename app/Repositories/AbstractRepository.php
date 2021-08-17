<?php
declare(strict_types = 1);

namespace ModuleBench\Repositories;

use Framework\Baseapp\Repositories\AbstractRepository as AbstractRepositoryBase;

class AbstractRepository extends AbstractRepositoryBase
{
    protected function getAppcode()
    {
        return 'bench';
    }
}
