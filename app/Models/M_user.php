<?php

namespace App\Models;

use CodeIgniter\Model;

class M_user extends Model
{
    protected $table = 'user';

    protected $allowedFields = [
        'user_id',
        'user_role',
        'user_name',
        'user_email',
        'user_contact',
        'user_psw',
        'user_branch',
        'user_college_roll_no',
        'user_registration_no',
        'user_status',
        'user_created'
    ];

  public function getPassword($mobile = false, $email = false)
  {
      if ($email) {
          return $this->where('user_email', $email)->first();
      } elseif ($mobile) {
          return $this->where('user_contact', $mobile)->first();
      }
      return false;
  }
  public function get_user($userId)
    {
      $this->where('user_id',$userId);
      return $this->get()->getRow();
    }
  public function isDuplicate($email, $mobile, $userId=null)
      {
        $this->groupStart()
                ->where('user_email', $email)
                ->orWhere('user_contact', $mobile)
                ->groupEnd();
        if (!empty($userId)) {
            $this->where('user_id !=', $userId);
        }
        $query = $this->get(1);
        return $query->getRow();
      }
public function insert_user($data)
  {
  return  $this->insert($data);
  }
public function allUsers()
  {
    $this->where('user_status',1);
    $this->select('user.*,branch.branch_shortname,branch.branch_name');
    $this->join('branch','branch.branch_id=user.user_branch','left');
    return $this->get()->getResult();
  }
public function get_userbyId($userId)
  {
    $this->select('user_id as user_id,user_role as usertype,user_name as name,
    user_email as email,user_contact as mobile,user_branch as branch,user_registration_no as registration_no,user_college_roll_no as roll_no');
    $this->where('user_id',$userId);
    return $this->get()->getRow();
  }
  public function update_user($data,$userId)
    {
      $this->where('user_id',$userId);
      $this->set($data);
      return $this->update();
    }
  
}
?>
