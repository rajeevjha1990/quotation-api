<?php
namespace App\Controllers\Api;

use App\Controllers\BaseController;

class Auth extends BaseController
{
    protected $session;
    protected $encrypter;
    protected $request;
    protected $validation;

    public function __construct()
    {
        $this->session    = \Config\Services::session();
        $this->encrypter  = \Config\Services::encrypter();
        $this->request    = \Config\Services::request();
        $this->validation = \Config\Services::validation();
    }

    public function login()
    {
        $emailOrMobile = $this->request->getVar("email");
        $password      = $this->request->getVar("password");

        if (is_numeric($emailOrMobile)) {
            $this->validation->setRule(
                "email",
                "Mobile",
                "required|regex_match[/^[1-9][0-9]{9}$/]",
                ['regex_match' => 'Please enter a valid mobile number']
            );
            $mobile = $emailOrMobile;
            $email = false;
        } else {
            $this->validation->setRule(
                "email",
                "Email",
                "required|valid_email",
                ['valid_email' => 'Please enter a valid email address']
            );
            $email = $emailOrMobile;
            $mobile = false;
        }

        $this->validation->setRule(
            "password",
            "Password",
            "required",
            ['required' => 'Password is required']
        );

        $this->validation->withRequest($this->request);
        if (!$this->validation->run()) {
            return $this->response->setStatusCode(451)->setJSON([
                "err" => $this->validation->getErrors()
            ]);
        }

        $userModel = new \App\Models\M_user();
        $user = $userModel->getPassword($mobile, $email);

        if ($user) {
            // $user is object → use ->user_psw
            if (password_verify($password, $user['user_psw'])) {
                $authData = json_encode(["id" => $user['user_id']]);
                $authKey  = bin2hex($this->encrypter->encrypt($authData));

                $this->session->set([
                    "logged_in" => true,
                    "user_id"   => $user['user_id'],
                    "user_name" => $user['user_name'] ?? '',
                    "user_role" => $user['user_role'] ?? 'user'
                ]);

                return $this->response->setJSON([
                    "authkey" => $authKey,
                    "userid"  => $user['user_id'],
                    "message" => 'Login successful'
                ]);
            } else {
                return $this->response->setStatusCode(405)->setJSON([
                    "err" => 'Invalid password'
                ]);
            }
        } else {
            return $this->response->setStatusCode(406)->setJSON([
                "err" => 'Mobile or email not registered'
            ]);
        }
    }

public function get_user()
{
    $authkey = $this->request->getHeaderLine('QTNAuthkey');
    if (!$authkey) {
        return $this->response->setStatusCode(401)->setJSON([
            'err' => 'Authorization header missing.'
        ]);
    }

    try {
        $binaryKey = hex2bin($authkey);
        if ($binaryKey === false) {
            throw new \Exception("Invalid hex key format.");
        }

        // Decrypt
        $decrypted = $this->encrypter->decrypt($binaryKey);
        if (!$decrypted) {
            throw new \Exception("Decryption failed.");
        }

        // Decode JSON
        $data = json_decode($decrypted, true);
        if (!is_array($data) || !isset($data['id'])) {
            throw new \Exception("Invalid decrypted data.");
        }

        $userId = $data['id'];

        // Fetch user
        $userModel = new \App\Models\M_user();
        $user = $userModel->get_user($userId);
        if ($user) {
            return $this->response->setJSON([
                "user_id"    => $user->user_id,
                "user_name"  => $user->user_name,
                "user_email" => $user->user_email,
                "user_mobile"=> $user->user_contact,
                "role"       => $user->user_role,
                "status"     => 200
            ]);
        } else {
            return $this->response->setStatusCode(404)->setJSON([
                'err' => 'User not found',
                'user_id_checked' => $userId
            ]);
        }
    } catch (\Exception $e) {
        return $this->response->setStatusCode(401)->setJSON([
            'err' => 'Invalid token',
            'exception' => $e->getMessage()
        ]);
    }
}

public function logout()
    {
        $this->session->destroy();
        return $this->response->setJSON([
            'status'  => 'success',
            'message' => 'Logged out successfully.'
        ]);
    }

}
?>
