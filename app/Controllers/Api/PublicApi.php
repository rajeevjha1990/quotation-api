<?php
namespace App\Controllers\Api;

use App\Controllers\BaseController;

class PublicApi extends BaseController
{
    public function ping()
    {
        return $this->response->setJSON([
            'status' => 'ok',
            'message' => 'API working without login'
        ]);
    }
}
