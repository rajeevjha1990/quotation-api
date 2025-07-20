<?php
namespace App\Controllers\Api;

use App\Controllers\BaseAuthController;

class Item extends BaseAuthController
{
  protected $validation;

    public function __construct()
    {
        $this->validation = \Config\Services::validation();
    }


  public function item_list()
  {
    $itemModel = new \App\Models\M_item();
    $data['items'] = $itemModel->allItems();
    return json_encode($data);
  }
  public function new_item()
    {
      $this->validation->setRule("item_name", "Item", "required");
      $this->validation->setRule("item_unit", "Unit", "required");
      $this->validation->setRule(
          'item_rate',
          'Rate',
          'required|regex_match[/^(?!0*(\.0+)?$)\d+(\.\d+)?$/]',
          [
            'required'    => 'Please enter the item rate.',
            'regex_match' => 'Rate must be a positive number greater than zero (e.g. 0.5, 10, 99.99).',
           ]
      );
      $this->validation->withRequest($this->request);
      if (!$this->validation->run()) {
          $this->response->setStatusCode(400);
          return json_encode(['err' => $this->validation->getErrors()]);
      }

    $m_item = new \App\Models\M_item();
    $itemid=$this->request->getVar('item_id');
    $item_name=$this->request->getVar('item_name');

    if ($m_item->item_exits($item_name, $itemid)) {
        $response = [
            'err' => ['title' => 'This item is already exists.']
        ];
        return $this->response->setJSON($response)->setStatusCode(400);
    }
      // User data
      $itemdata = [
          "item_user" => $this->userId,
          "item_name" => $this->request->getVar("item_name"),
          "item_unit" => $this->request->getVar("item_unit"),
          "item_rate" => $this->request->getVar("item_rate"),
      ];
      $m_item = new \App\Models\M_item();

      // Insert or Update Brand
      if ($this->request->getVar('item_id')) {
          $itemId = $this->request->getVar('item_id');
          $res = $m_item->edit_item($itemId, $itemdata);
          $msg = "Item Updated Successfully";
      } else {
          $res = $m_item->insert_item($itemdata);
          $msg = "Item Added Successfully";
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
}
?>
