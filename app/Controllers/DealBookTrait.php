<?php

declare(strict_types = 1);

namespace ModuleBench\Controllers;

trait DealBookTrait
{
    public function dealBookByInfo()
    {
        $commonlistId = 65;
        $commonlist = $this->getModelObj('commonlist')->where('id', $commonlistId)->first();
        $bCode = $commonlist['code'];
        $infos = $this->getModelObj('commoninfo')->where(['relate_id' => $commonlistId])->orderBy('id', 'asc')->get();
        //$infos = $this->getModelObj('commoninfo')->where(['relate_id' => $commonlistId, 'status' => 2])->where('code_ext', '<>', '[]')->orderBy('id', 'asc')->get();
        $filePre = config('culture.material_path') . "/books/{$bCode}/";
        $chapterInfos = [];
        foreach ($infos as $info) {
            $chapterInfos[$info['extfield']][] = ['code' => $info['code'], 'name' => $info['name']];
            if (in_array($info['code'], ['0', '00'])) {
                continue;
            }
            $file = $filePre . $info['code'] . '.php';
            //$subInfos = $this->getModelObj('commoninfo')->where(['extfield' => $commonlistId, 'status' => 2, 'target_id' => $info['id']])->get();
            $subInfos = [$info];
            $str = $this->formatContent($subInfos);
            //echo $str;exit();
            if (!is_dir(dirname($file))) {
                mkdir(dirname($file));
            }
            file_put_contents($file, $str);
        }
        //print_r($chapterInfos);

        $this->createChapterFile($chapterInfos, $bCode);
        exit();
        //echo $str;exit();
    }

    public function createChapterFile($chapters, $code)
    {
        $ccFile = config('culture.material_path') . "/booklist/{$code}.php";
        $cFile = config('culture.material_path') . "/booklist/{$code}_catalogue.php";
        //print_r($chapters);exit();
        $cStr = $ccStr = "<?php\nreturn [\n";
        $ccStr .= "'chapters' => [\n";
        foreach ($chapters as $code => $elems) {
            $ccStr .= "[\n";
            $ccStr .= "    'name' => '{$code}',\n";
            $ccStr .= "    'brief' => '',\n";
            $ccStr .= "    'infos' => [\n";
            $infoStr = '';
            foreach ($elems as $elem) {
                $infoStr .= "'{$elem['code']}', ";
                $cStr .= "    '{$elem['code']}' => ['code' => '{$elem['code']}', 'name' => '{$elem['name']}', 'brief' => '',],\n";
            }
            $infoStr = trim($infoStr, ', ');
            $ccStr .= "        {$infoStr}\n";
            $ccStr .= "    ],\n";
            $ccStr .= "],\n";
        }
        $ccStr .= "],\n";

        $ccStr .= "];";
        $cStr .= "];";
        echo $cStr;
        echo $ccStr;
        file_put_contents($ccFile, $ccStr);
        file_put_contents($cFile, $cStr);
        exit();
    }

    public function dealBookByList($commonlist)
    {
        $code = $commonlist->code;
        $file = config('culture.material_path') . '/books/shangshu/' . $code . '.php';
        if (empty($commonlist->description)) {
            return true;
        }

        $ext = "'" . implode("','", json_decode($commonlist->description, true));
        //echo $ext;exit();
        $contents = file_get_contents($file);
        $contents .= $ext;
        file_put_contents($file, $contents);
        return true;
        if (file_exists($file)) {
            return true;
        }
        $infos = $this->getModelObj('commoninfo')->where(['target_id' => $commonlist->id])->orderBy('id', 'asc')->get();

        /*$descs = explode("\n", $commonlist->description);
        $str .= "'unscramble' => [\n";
        foreach ($descs as $desc) {
            $desc = trim($desc);
            if (empty($desc)) {
                continue;
            }
            $str .= "    '{$desc}',\n";
        }
        $str .= "],\n";*/

        $str = $this->formatContent($infos);
        file_put_contents($file, $str);
    }

    public function formatContent($infos, $extInfo = null)
    {
        $str = "<?php\nreturn [\n";
        $str .= "'chapters' => [\n";

        foreach ($infos as $info) {
            $codeExt = $info->code_ext;
            $datas = json_decode($codeExt, true);

            //print_r($datas);exit();
            /*$newDatas = [];
            foreach ($datas['content'] as $i => $elem) {
                $newDatas[$i]['content'][] = $elem;
                if (isset($datas['vernacular'][$i])) {
                    $newDatas[$i]['vernacular'][] = $datas['vernacular'][$i];
                }
            }
            //print_r($newDatas);exit();
            foreach ($newDatas as $datas) {*/

            $str .= "[\n";
            $space = '    ';
            $str .= $this->getPointStr($datas, $space);
            $str .= "],\n";
            //}
        }
        $str .= "],\n";

        if (!empty($extInfo)) {
            $str .= $getPointStr($extInfo, '');
        }

        $str .= "];";
        return $str;
    }

    protected function getPointStr($datas, $space)
    {
        $str = '';
        foreach ($datas as $key => $subValue) {
            $str .= "{$space}'{$key}' => [\n";
            foreach ($subValue as $value) {
                $str .= "{$space}    '{$value}',\n";
            }
            $str .= "{$space}],\n";
        }
        return $str;
    }
}
