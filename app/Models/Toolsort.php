<?php

declare(strict_types = 1);

namespace ModuleBench\Models;

class Toolsort extends AbstractModel
{
    protected $table = 'toolsort';
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    protected $guarded = ['id'];

}
