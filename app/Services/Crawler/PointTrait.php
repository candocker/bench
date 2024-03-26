<?php

namespace ModuleBench\Services\Crawler;

use Swoolecan\Foundation\Helpers\CommonTool;

trait PointTrait
{
	public function spiderPoint($file)
	{
        $file = "/point/{$file}.html";
        $crawler = $this->getCrawlerObj($file);
        $datas = [];
        $sql = 'INSERT INTO `wp_material_source` (`category_code`, `code`, `name`, `domain`, `url`, `description`, `file_path`, `status`, `created_at`, `updated_at`) VALUES ';
        $crawler->filter('tr')->each(function ($node) use (& $datas, & $sql) {
            $elems = $node->filter('td');
            if (count($elems) == 0) {
                return ;
            }
            for ($i = 0; $i < 2; $i++) {
                $elem = $elems->eq($i);
                $elem->filter('a')->each(function ($subNode) use (& $datas, & $sql) {
                    $href = $subNode->attr('href');
                    $href = strpos($href, '?') !== false ? substr($href, 0, strpos($href, '?')) : $href;
                    $href = "https://baike.baidu.com{$href}";
                    $name = $subNode->text();
                    $code = CommonTool::getSpellStr($name, '');
                    if (in_array($href, $datas)) {
                    echo $href . '-' . $name . '-' . $code . '<br />';
                    }
                    $sql .= "('waiguolishi', '{$code}', '{$name}', 'baike.baidu.com', '{$href}', '{$name}', '/source/history/{$code}/{$code}', '0', '2023-08-15 20:44:07', '2023-08-15 20:44:07'),\n";
                    $datas[] = $href;//['code' => $code, 'name' => $name, 'url' => $href];
                    $datas[] = ['code' => $code, 'name' => $name, 'url' => $href];
                });
            }
		});
        echo $sql;exit();
        var_export($datas);
	}
}
