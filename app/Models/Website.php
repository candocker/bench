<?php

declare(strict_types = 1);

namespace ModuleBench\Models;

class Website extends AbstractModel
{
    protected $table = 'website';
    protected $guarded = ['id'];
    public $timestamps = false;

}
