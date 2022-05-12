<?php

declare(strict_types = 1);

namespace ModuleBench\Models;

class Navsort extends AbstractModel
{
    protected $table = 'navsort';
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function getCategoryDatas($chunkNum = null)
    {
        $categorys = $this->where(['parent_code' => '', 'status' => 1])->get()->toArray();
        $mediaCategorys = $this->where(['parent_code' => '', 'status' => 10])->get()->toArray();
        return [
            'categorys' => is_null($chunkNum) ? $categorys : array_chunk($categorys, $chunkNum, true),
            'mediaCategorys' => is_null($chunkNum) ? $mediaCategorys : array_chunk($mediaCategorys, $chunkNum, true),
        ];
    }

    public function getWebsiteDatas()
    {
        $infos = $this->getModelObj('navsortInfo')->where(['navsort_code' => $this->code])->orderBy('orderlist', 'desc')->get();
        $results = [];
        foreach ($infos as $info) {
            $data = $info->toArray();
            $website = $this->getModelObj('website')->where('id', $info->info_id)->first();
            $data = array_merge($data, $website->toArray());
            $logoPath = $data['logo_path'];
            if (!empty($logoPath)) {
                $logoPath = strpos($logoPath, 'http') !== false ? $logoPath : config('app.domains.assetUrl') . $logoPath;
            }
            $data['logo_path'] = $logoPath;
            $results[] = $data;
        }
        return $results;
    }

    public function getElemNumAttribute()
    {
        if ($this->parent_code != '') {
            return $this->getModelObj('navsortInfo')->where(['navsort_code' => $this->code])->count();
        }
        $infos = $this->getSubElem();
        $infos = $infos->toArray();
        $codes = array_keys($infos);
        return $this->getModelObj('navsortInfo')->whereIn('navsort_code', $codes)->count();
    }

    public function getSubElem()
    {
        $infos = $this->where(['parent_code' => $this->code])->get()->keyBy('code');
        return $infos;
    }
}
