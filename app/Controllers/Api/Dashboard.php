<?php
namespace App\Controllers\Api;

use App\Controllers\BaseAuthController;

class Dashboard extends BaseAuthController
{
    public function index()
    {
        return $this->response->setJSON([
            'status'    => 'success',
            'user_id'   => $this->userId,
            'user_name' => $this->userData->user_name ?? '',
        ]);
    }
}
