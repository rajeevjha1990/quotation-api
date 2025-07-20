<?php

namespace App\Models;

use CodeIgniter\Model;

class M_quotation_items extends Model
{
    protected $table = 'quotation_items';
    protected $allowedFields = [
        'qtnitem_id',
        'qtnitem_qtnid',
        'qtnitem_itemid',
        'qtnitem_quantity',
        'qtnitem_rate',
        'qtnitem_amount',
        'qtnitem_status	',
        'qtnitem_created',
    ];
  public function insert_item($data)
    {
      return  $this->insertBatch($data);
    }
  public function get_quotation_items($quotationId)
    {
      $this->where('qtnitem_qtnid',$quotationId);
      $this->select('quotation_items.*,items.item_name,items.item_unit');
      $this->join('items items','items.item_id=quotation_items.qtnitem_itemid','left');
      return $this->get()->getResult();
    }
}
?>
