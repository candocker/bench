<?php

declare(strict_types = 1);

namespace ModuleBench\Controllers;

class SpiderinfoController extends AbstractController
{
    public function operation()
    {
        $repository = $this->getRepositoryObj();
        $info = $this->getPointInfo($repository, $this->request, false);
        $action = $this->request->input('action');
        $service = $this->getServiceObj('spider');
        $service->spiderinfo = $info;
		return $this->success($service->spiderinfoOperation($action, $this->request->all()));
    }
}
