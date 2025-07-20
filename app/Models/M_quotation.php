<?php

namespace App\Models;

use CodeIgniter\Model;

class M_quotation extends Model
{
    protected $table = 'quotations';
    protected $allowedFields = [
        'quotation_id',
        'quotation_client',
        'quotation_date',
        'quotation_total_amount',
        'quotation_status	',
        'quotation_created',
    ];
 public function insert_quotation($data)
  {
    $this->insert($data);
     return $this->insertID();
  }
public function client_current_quotation($clientId)
  {
    $this->where('quotation_client',$clientId);
    $this->where('quotation_status',1);
    $this->orderBy('quotation_id', 'DESC');
    return $this->get()->getResult();
  }
public function client_quotation($clientId,$quotationId)
  {
    $this->where('quotation_client',$clientId);
    $this->where('quotation_id',$quotationId);
    $this->where('quotation_status',1);
    return $this->get()->getRow();
  }
}
?>
