<?php

declare(strict_types = 1);

namespace ModuleBench\Models;

class Navsort extends AbstractModel
{
    protected $table = 'navsort';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function getCategoryDatas($chunkNum = null)
    {
        $datas = $this->where(['parent_code' => '', 'status' => 1])->get()->toArray();
        return is_null($chunkNum) ? $datas : array_chunk($datas, $chunkNum, true);
    }

    public function getWebsiteDatas()
    {
        $infos = $this->getModelObj('navsortInfo')->where(['navsort_code' => $this->code])->orderBy('orderlist', 'desc')->get();
        $results = [];
        foreach ($infos as $info) {
            $data = $info->toArray();
            $website = $this->getModelObj('website')->where('id', $info->info_id)->first();
            $data = array_merge($data, $website->toArray());
            $results[] = $data;
        }
        return $results;
    }
}
