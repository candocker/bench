<?php

declare(strict_types = 1);

namespace ModuleBench\Models;

class PositionInfo extends AbstractModel
{
    protected $table = 'position_info';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function formatInfo()
    {
        switch ($this->info_type) {
        case 'website':
            $info = $this->getModelObj('website')->where(['id' => $this->info_id])->first();
            break;
        case 'sort':
            $info = $this->getModelObj('navsort')->where(['id' => $this->info_id])->first();
            break;
        }
        $data = $this->toArray();
        return array_merge($info->toArray(), $data);
    }

    public function position()
    {
        return $this->hasOne(Position::class, 'code', 'position_code');
    }
}
