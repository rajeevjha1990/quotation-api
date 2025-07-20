<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseAuthController extends Controller
{
    protected $request;
    protected $userId;
    protected $userData;
    protected $encrypter;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->encrypter = \Config\Services::encrypter();
        $this->request = $request;

        $authKey = $this->request->getHeaderLine('QTNAuthkey');
        if (!$authKey) {
            $this->unauthorized("Authorization header missing.");
        }

        try {
            $binaryKey = hex2bin($authKey);
            $decrypted = $this->encrypter->decrypt($binaryKey);
            $data = json_decode($decrypted, true);

            if (!isset($data['id'])) {
                throw new \Exception("Invalid token structure.");
            }

            $this->userId = $data['id'];

            $userModel = new \App\Models\M_user();
            $this->userData = $userModel->get_user($this->userId);

            if (!$this->userData) {
                $this->unauthorized("User not found.");
            }

        } catch (\Throwable $e) {
            $this->unauthorized("Invalid token: " . $e->getMessage());
        }
    }

    protected function unauthorized($message = "Unauthorized")
    {
        echo json_encode(['err' => $message]);
        http_response_code(401);
        exit;
    }
}
