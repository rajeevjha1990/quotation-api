<?php
namespace App\Controllers\Api;

use App\Controllers\BaseAuthController;

class Client extends BaseAuthController
{
  protected $validation;

    public function __construct()
    {
        $this->validation = \Config\Services::validation();
    }


  public function client_list()
  {
    $clientModel = new \App\Models\M_client();
    $data['clients'] = $clientModel->allClients();
    return json_encode($data);
  }
  public function new_client()
    {
    $this->validation->setRule("client_name", "Client Name", "required");
    $this->validation->setRule("client_contact", "Contact", "required|numeric|min_length[10]");
    $this->validation->setRule("client_email", "Email", "required|valid_email");
    $this->validation->setRule("client_address", "Address", "required");

      $this->validation->withRequest($this->request);
      if (!$this->validation->run()) {
          $this->response->setStatusCode(400);
          return json_encode(['err' => $this->validation->getErrors()]);
      }

    $m_client = new \App\Models\M_client();
    $clientid=$this->request->getVar('client_id');
    $client_name=$this->request->getVar('client_name');

    if ($m_client->client_exits($client_name, $clientid)) {
        $response = [
            'err' => ['title' => 'This client is already exists.']
        ];
        return $this->response->setJSON($response)->setStatusCode(400);
    }
      // User data
      $clientdata = [
          "client_user" => $this->userId,
          "client_name" => $this->request->getVar("client_name"),
          "client_email" => $this->request->getVar("client_email"),
          "client_contact" => $this->request->getVar("client_contact"),
          "client_address" => $this->request->getVar("client_address"),
      ];
      $m_client = new \App\Models\M_client();

      // Insert or Update Brand
      if ($this->request->getVar('client_id')) {
          $clientId = $this->request->getVar('client_id');
          $res = $m_client->edit_client($clientId, $clientdata);
          $msg = "Client Updated Successfully";
      } else {
          $res = $m_client->insert_client($clientdata);
          $msg = "Client Added Successfully";
      }

      if ($res) {
          $response = [
              'msg' => $msg,
          ];
          return json_encode($response);
      } else {
          $response = [
              'msg' => 'error, try again..'
          ];
          return json_encode($response);
      }
    }
public function client_current_quotation()
{
    $clientId = $this->request->getVar('clientId');
    $m_quotation = new \App\Models\M_quotation();
    $m_quotation_items = new \App\Models\M_quotation_items();
    $quotations = $m_quotation->client_current_quotation($clientId);
    $data = [];
    foreach ($quotations as $qtn) {
        $qtnitems = $m_quotation_items->get_quotation_items($qtn->quotation_id);
        $data[] = [
            'quotations' => $qtn,
            'items'     => $qtnitems
        ];
      }
      return $this->response->setJSON([
          'quotations' => $data
      ]);
    }

}
?>
