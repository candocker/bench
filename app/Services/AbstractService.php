<?php
declare(strict_types = 1);

namespace ModuleBench\Services;

use Framework\Baseapp\Services\AbstractService as AbstractServiceBase;

abstract class AbstractService extends AbstractServiceBase
{
    use CurlTrait;

    protected function getAppcode()
    {
        return 'bench';
    }

    public function getConfig($code, $path = 'bench')
    {
        $param = $path ? "app.{$path}.{$code}" : "app.{$code}";
        return config($param);
    }

    public function getPointFile($file)
    {
        return $this->getConfig('spiderPath', 'bench') . $file;
    }

    public function createFilePath($file)
    {
        $path = dirname($file);
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        return true;
    }
}
