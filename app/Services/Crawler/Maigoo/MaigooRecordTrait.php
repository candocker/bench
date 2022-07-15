<?php

declare(strict_types = 1);

namespace ModuleBench\Services\Crawler\Maigoo;

trait MaigooRecordTrait
{
    protected function _recordMaigooSubject()
    {
        $datas = [
            ['page' => 1, 'name' => '毛笔', 'code' => 'mb', 'url' => 'https://www.maigoo.com/maigoo/7377mb_index.html'],
            ['page' => 1, 'name' => '墨汁', 'code' => 'mz', 'url' => 'https://www.maigoo.com/maigoo/8301mz_index.html'],
            ['page' => 1, 'name' => '宣纸', 'code' => 'xz', 'url' => 'https://www.maigoo.com/maigoo/7376xz_index.html'],
            ['page' => 1, 'name' => '钢笔', 'code' => 'gb', 'url' => 'https://www.maigoo.com/maigoo/152gb_index.html'],
            ['page' => 1, 'name' => '办公用品', 'code' => 'bgyp', 'url' => 'https://www.maigoo.com/maigoo/588bgls_index.html'],
            ['page' => 1, 'name' => '文具用品', 'code' => 'wjyp', 'url' => 'https://www.maigoo.com/maigoo/151wj_index.html'],
            ['page' => 1, 'name' => '圆珠笔', 'code' => 'yzb', 'url' => 'https://www.maigoo.com/maigoo/7209yzb_index.html'],
            ['page' => 1, 'name' => '字帖', 'code' => 'zt', 'url' => 'https://www.maigoo.com/maigoo/8165zt_index.html'],
            ['page' => 1, 'name' => '中性笔', 'code' => 'zxb', 'url' => 'https://www.maigoo.com/maigoo/785bi_index.html'],
            //['page' => 1, 'name' => '', 'code' => '', 'url' => ''],
        ];

        foreach ($datas as $data) {
            $this->_writeList($data['url'], $data['page'], $data);
        }
    }

    /*protected function _recordMaigooSubject()
    {
        $datas = [
            ['page' => 1, 'name' => '宠物食品', 'code' => 'cwsp', 'url' => 'https://www.maigoo.com/brand/list_1290.html'],
            ['page' => 1, 'name' => '猫砂', 'code' => 'ms', 'url' => 'https://www.maigoo.com/brand/search/?catid=7336&dynamic=1'],
            ['page' => 1, 'name' => '鱼缸', 'code' => 'yg', 'url' => 'https://www.maigoo.com/brand/search/?catid=2275'],
            ['page' => 1, 'name' => '猫粮', 'code' => 'ml', 'url' => 'https://www.maigoo.com/brand/search/?catid=1262'],
            ['page' => 1, 'name' => '狗粮', 'code' => 'gl', 'url' => 'https://www.maigoo.com/brand/search/?catid=1261'],
            //['page' => 1, 'name' => '', 'code' => '', 'url' => ''],
        ];

        foreach ($datas as $data) {
            $this->_writeList($data['url'], $data['page'], $data);
        }
    }*/

    /*protected function _recordMaigooBrand()
    {
        $datas = [
            ['page' => 1, 'name' => '宠物食品', 'code' => 'cwsp', 'url' => 'https://www.maigoo.com/brand/list_1290.html'],
            ['page' => 1, 'name' => '猫砂', 'code' => 'ms', 'url' => 'https://www.maigoo.com/brand/search/?catid=7336&dynamic=1'],
            ['page' => 1, 'name' => '鱼缸', 'code' => 'yg', 'url' => 'https://www.maigoo.com/brand/search/?catid=2275'],
            ['page' => 1, 'name' => '猫粮', 'code' => 'ml', 'url' => 'https://www.maigoo.com/brand/search/?catid=1262'],
            ['page' => 1, 'name' => '狗粮', 'code' => 'gl', 'url' => 'https://www.maigoo.com/brand/search/?catid=1261'],
            //['page' => 1, 'name' => '', 'code' => '', 'url' => ''],
        ];

        foreach ($datas as $data) {
            $this->_writeList($data['url'], $data['page'], $data);
        }
    }*/

    /*protected function _recordMaigooBrandtop()
    {
        $datas = [
            ['page' => 1, 'name' => '宠物店', 'code' => 'cwd', 'url' => 'https://www.maigoo.com/maigoo/5089cwd_index.html'],
            ['page' => 1, 'name' => '鱼缸', 'code' => 'yg', 'url' => 'https://www.maigoo.com/maigoo/987xg_index.html'],
            ['page' => 1, 'name' => '猫粮', 'code' => 'ml', 'url' => 'https://www.maigoo.com/maigoo/1262MYL_index.html'],
            ['page' => 1, 'name' => '狗粮', 'code' => 'gl', 'url' => 'https://www.maigoo.com/maigoo/1261gl_index.html'],
            ['page' => 1, 'name' => '宠物医院', 'code' => 'cwyy', 'url' => 'https://www.maigoo.com/maigoo/4989cwyy_index.html'],
            ['page' => 1, 'name' => '营养品', 'code' => 'yyp', 'url' => 'https://www.maigoo.com/maigoo/1159cw_index.html'],
            ['page' => 1, 'name' => '宠物食品', 'code' => 'cwsp', 'url' => 'https://www.maigoo.com/maigoo/947cwsp_index.html'],
            //['page' => 1, 'name' => '', 'code' => '', 'url' => ''],
        ];

        foreach ($datas as $data) {
            $this->_writeList($data['url'], $data['page'], $data);
        }
    }*/
}
