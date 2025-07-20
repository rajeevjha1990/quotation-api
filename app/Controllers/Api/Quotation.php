<?php
namespace App\Controllers\Api;

use App\Controllers\BaseAuthController;
use Mpdf\Mpdf;

class Quotation extends BaseAuthController
{
  protected $validation;

    public function __construct()
    {
        $this->validation = \Config\Services::validation();
    }

  public function new_quotation()
    {
      $this->validation->setRule("quotation_date", "Date", "required");
      $this->validation->setRule("quotation_note", "Note", "required");

      $this->validation->withRequest($this->request);
      if (!$this->validation->run()) {
          $this->response->setStatusCode(400);
          return json_encode(['err' => $this->validation->getErrors()]);
      }

      $m_quoation = new \App\Models\M_quotation();
      $m_quoation_item = new \App\Models\M_quotation_items();
      $quotationdata = [
          "quotation_user" => $this->userId,
          "quotation_client" => $this->request->getVar('clientId'),
          "quotation_date" => $this->request->getVar("quotation_date"),
          "quotation_note" => $this->request->getVar("quotation_note"),
          "quotation_total_amount" => $this->request->getVar("quotation_total_amount"),
      ];
      $insertId = $m_quoation->insert_quotation($quotationdata);
        if($insertId){
          $items=json_decode($this->request->getVar('items'),true);
          $itemArray=[];
            foreach ($items as $item) {
                $itemArray[] = [
                    "qtnitem_qtnid" => $insertId,
                    "qtnitem_itemid"    => $item['item_id'] ?? '',
                    "qtnitem_name"    => $item['item_name'] ?? '',
                    "qtnitem_rate"    => $item['item_rate'] ?? 0,
                    "qtnitem_quantity"     => $item['quantity'] ?? 1,
                    "qtnitem_amount"       => ($item['item_rate'] ?? 0) * ($item['quantity'] ?? 1),
                ];
            }
          $res=$m_quoation_item->insert_item($itemArray);
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
    public function quotationPDF($clientid, $quotationid)
    {
        $m_client = new \App\Models\M_client();
        $m_quotation = new \App\Models\M_quotation();
        $m_quotation_item = new \App\Models\M_quotation_items();

        $data['client'] = $m_client->get_clientbyId($clientid);
        $data['quotation'] = $m_quotation->client_quotation($clientid, $quotationid);
        $data['quotationitems'] = $m_quotation_item->get_quotation_items($quotationid);

        $pdfConfig = [
        'mode' => 'utf-8',
        'format' => 'A4',
        'margin_left' => 5,
        'margin_right' => 5,
        'margin_top' => 57,
        'margin_bottom' => 14,
        'margin_header' => 0,
        'margin_footer' => 0,
        'tempDir' => WRITEPATH . 'mpdf' // This is writable folder in CodeIgniter (writable/mpdf)
    ];


        $mpdf = new Mpdf($pdfConfig);
        $html = view('quotation', $data);
        $mpdf->WriteHTML($html);

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setBody($mpdf->Output('', 'S')); // 'S' returns as string to set as body
    }
}
?>
