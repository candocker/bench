<?php

declare(strict_types = 1);

namespace ModuleBench\Models;

class NavsortInfo extends AbstractModel
{
    protected $table = 'navsort_info';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function navsort()
    {
        return $this->hasOne(Navsort::class, 'code', 'navsort_code');
    }

    public function website()
    {
        return $this->hasOne(Website::class, 'id', 'info_id');
    }
}
