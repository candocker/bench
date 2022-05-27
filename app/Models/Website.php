<?php

declare(strict_types = 1);

namespace ModuleBench\Models;

class Website extends AbstractModel
{
    protected $table = 'website';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function getNameFullAttribute()
    {
        return "<a href='{$this->url}' target='_blank'>{$this->name}</a>";
    }
}
