<?php

namespace App\Models;

use CodeIgniter\Model;

class M_item extends Model
{
    protected $table = 'items';

    protected $allowedFields = [
        'item_id',
        'item_user',
        'item_name',
        'item_unit',
        'item_rate',
        'item_status',
        'item_created'
    ];

public function item_exits($item, $itemId = null)
{
    $this->where('item_name', $item);
    if (!empty($itemId)) {
        $this->where('item_id !=', $itemId);
    }
    $query = $this->get(1);
    return $query->getRow();
}
public function insert_item($data)
  {
  return  $this->insert($data);
  }
public function allItems()
  {
    $this->where('item_status',1);
    return $this->get()->getResult();
  }
public function get_itembyId($itemId)
  {
    $this->select('item_id as item_id,item_name as item');
    $this->where('item_id',$itemId);
    return $this->get()->getRow();
  }
  public function edit_item($itemId,$data)
    {
      $this->where('item_id',$itemId);
      $this->set($data);
      return $this->update();
    }
}
?>
