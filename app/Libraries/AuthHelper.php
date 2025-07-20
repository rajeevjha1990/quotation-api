<?php
if (!function_exists('getLoggedInUserId')) {
    function getLoggedInUserId()
    {
        $request = \Config\Services::request();
        $encrypter = \Config\Services::encrypter();
        $authkey = $request->getHeaderLine('QTNAuthkey');

        if (!$authkey) {
            throw new \Exception('Authorization header missing.');
        }

        $binaryKey = hex2bin($authkey);
        if ($binaryKey === false) {
            throw new \Exception("Invalid hex key format.");
        }

        $decrypted = $encrypter->decrypt($binaryKey);
        if (!$decrypted) {
            throw new \Exception("Decryption failed.");
        }

        $data = json_decode($decrypted, true);
        if (!is_array($data) || !isset($data['id'])) {
            throw new \Exception("Invalid decrypted data.");
        }

        return $data['id'];
    }
}
?>
