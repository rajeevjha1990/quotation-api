<?php

namespace App\Models;

use CodeIgniter\Model;

class M_client extends Model
{
    protected $table = 'client';

    protected $allowedFields = [
        'client_id',
        'client_user',
        'client_name',
        'client_email',
        'client_contact',
        'client_address',
        'client_status',
        'client_created'
    ];

public function client_exits($client, $clientId = null)
{
    $this->where('client_name', $client);
    if (!empty($clientId)) {
        $this->where('client_id !=', $clientId);
    }
    $query = $this->get(1);
    return $query->getRow();
}
public function insert_client($data)
  {
  return  $this->insert($data);
  }
public function allClients()
  {
    $this->where('client_status',1);
    return $this->get()->getResult();
  }
public function get_clientbyId($clientId)
  {
    $this->where('client_id',$clientId);
    return $this->get()->getRow();
  }
  public function edit_client($clientId,$data)
    {
      $this->where('client_id',$clientId);
      $this->set($data);
      return $this->update();
    }
}
?>
