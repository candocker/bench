<?php

declare(strict_types = 1);

namespace ModuleBench\Observers;

use ModuleBench\Models\Demo;

class DemoObserver
{
    public function deleting(Demo $model)
    {
        //return $model->canDelete();
    }
}
