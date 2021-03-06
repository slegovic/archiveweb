<!-- Audit_log - controller - 16.03.2017. -->
<?php defined("BASEPATH") OR exit("No direct script access allowed");
class Audit_log extends CI_Controller {
  function __construct() {
    parent::__construct();
    $this->load->model("Audit_log_model");
    if(true==1){
      is_login();
      $this->user_id=isset($this->session->get_userdata()['user_details'][0]->users_id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }else{
      $this->user_id=1;
    }
  }

  public function index() {
    if(CheckPermission("audit_log", "all_read,own_read")){
      $data["view_data"]=$this->Audit_log_model->get_data();
      $this->load->view("include/header");
      $this->load->view("index",$data);
      $this->load->view("include/footer");
    } else {
      $this->session->set_flashdata('messagePr', 'Nemate ovlasti za izvršenje tražene radnje!');
      redirect( base_url().'opci_inventar', 'refresh');
    }
  }

  public function add_edit() {
    $data=$this->input->post();
    foreach ($_FILES as $fkey => $fvalue) {
      foreach($fvalue['name'] as $key => $fileInfo) {
        if(!empty($_FILES[$fkey]['name'][$key])){
          $filename=$_FILES[$fkey]['name'][$key];
          $tmpname=$_FILES[$fkey]['tmp_name'][$key];
          $exp=explode('.', $filename);
          $ext=end($exp);
          $newname=  $exp[0].'_'.time().".".$ext;
          $config['upload_path']='assets/images/';
          $config['upload_url']=base_url().'assets/images/';
          $config['allowed_types']="gif|jpg|jpeg|png|iso|dmg|zip|rar|doc|docx|xls|xlsx|ppt|pptx|csv|ods|odt|odp|pdf|rtf|sxc|sxi|txt|exe|avi|mpeg|mp3|mp4|3gp";
          $config['max_size']='2000000';
          $config['file_name']=$newname;
          $this->load->library('upload', $config);
          move_uploaded_file($tmpname,"assets/images/".$newname);
          $data[$fkey][]=$newname;
        } else {
          if(!empty($_POST['fileOld'])) {
            $newname=$this->input->post('fileOld');
            $newname=explode(',', $newname);
            $data[$fkey]=$newname;
          } else {
            $data[$fkey]='';
          }
        }
      }
      $data[$fkey]=implode(',', $data[$fkey]);
    }
    if($this->input->post('id')) {
      unset($data['submit']);
      unset($data['fileOld']);
      unset($data['save']);
      unset($data['id']);
      foreach ($data as $dkey => $dvalue) {
        if(is_array($dvalue)) {
          $data[$dkey]=implode(',', $dvalue);
        }
      }
      $this->Audit_log_model->updateRow('audit_log', 'audit_log_id', $this->input->post('id'), $data);
      $afftectedRows=$this->db->affected_rows();
      if ($afftectedRows > 0 ) {
        $this->session->set_flashdata('message', 'Zapis je ažuriran!');
        redirect('audit_log');
      } else {
        $this->session->set_flashdata('message', 'Zapis nije ažuriran!');
        redirect('audit_log');
      }
		} else {
			unset($data['submit']);
			unset($data['fileOld']);
			unset($data['save']);
			$data['user_id']=$this->user_id;
			foreach ($data as $dkey => $dvalue) {
				if(is_array($dvalue)) {
					$data[$dkey]=implode(',', $dvalue);
				}
			}
			$this->Audit_log_model->insertRow('audit_log', $data);
      $afftectedRows=$this->db->affected_rows();
      if ($afftectedRows > 0 ) {
        $this->session->set_flashdata('message', 'Zapis je unesen!');
        redirect('audit_log');
      } else {
        $this->session->set_flashdata('message', 'Zapis nije unesen!');
        redirect('audit_log');
      }
    }
  }

  public function get_modal() {
    if($this->input->post('id')){
      $data['data']=$this->Audit_log_model->Get_data_id($this->input->post('id'));
      echo $this->load->view('add_update', $data, true);
    } else {
      echo $this->load->view('add_update', '', true);
    }
    exit;
  }

  public function get_modal2() {
    if($this->input->post('id')){
      $data['data']= $this->Audit_log_model->Get_data_id($this->input->post('id'));
      echo $this->load->view('view_record', $data, true);
    } else {
      echo $this->load->view('view_record', '', true);
    }
    exit;
  }

  public function delete($ids) {
    $idsArr = explode('-', $ids);
    foreach ($idsArr as $key => $value) {
      $this->Audit_log_model->delete_data($value);
    }
    $afftectedRows=$this->db->affected_rows();
    if ($afftectedRows > 0 ) {
      $this->session->set_flashdata('message', 'Zapis je izbrisan!');
      redirect(base_url().'audit_log', 'refresh');
    } else {
      $this->session->set_flashdata('message', 'Zapis nije moguće izbrisati!');
      redirect(base_url().'audit_log', 'refresh');
    }
  }

  public function delete_data($id) {
    $this->Audit_log_model->delete_data($id);
    $afftectedRows=$this->db->affected_rows();
    if ($afftectedRows > 0 ) {
      $this->session->set_flashdata('message', 'Zapis je izbrisan!');
      redirect('audit_log');
    } else {
      $this->session->set_flashdata('message', 'Zapis nije moguće izbrisan!');
      redirect('audit_log');
    }
  }

  public function ajx_data(){
    $primaryKey='audit_log_id';
    $table='audit_log';
    $columns=array(
      array('db' => 'audit_log_id', 'dt' => 0),
      array('db' => 'data_id', 'dt' => 1),
      array('db' => 'action', 'dt' => 2),
      array('db' => 'table', 'dt' => 3),
      array('db' => 'field', 'dt' => 4),
      array('db' => 'old_value', 'dt' => 5),
      array('db' => 'new_value', 'dt' => 6),
      array('db' => 'modified', 'dt' => 7),
      array('db' => 'executed', 'dt' => 8),
      array('db' => 'audit_log_id', 'dt' => 9)
    );
    $joinQuery='';
    $aminkhan='@banarsiamin@';
    $where='';
    if($this->input->get('dateRange')) {
      $date=explode(' - ', $this->input->get('dateRange'));
      $where="DATE_FORMAT(`$table`.`".$this->input->get('columnName')."`, '%Y/%m/%d')>='".date('Y/m/d', strtotime($date[0]))."' AND DATE_FORMAT(`$table`.`".$this->input->get('columnName')."`, '%Y/%m/%d') <= '".date('Y/m/d', strtotime($date[1]))."' ";
		}
    $sql_details=array(
      'user' => $this->db->username,
      'pass' => $this->db->password,
      'db' => $this->db->database,
      'host' => $this->db->hostname
    );
    if(CheckPermission($table, "all_read")){}
      else if(CheckPermission($table, "own_read") && CheckPermission($table, "all_read")!=true){$where.="`$table`.`user_id`=".$this->user_id;}
      $output_arr=SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $where);
      foreach ($output_arr['data'] as $key => $value)
      {
        $output_arr['data'][$key][0]='<input type="checkbox" name="selData" value="'.$output_arr['data'][$key][0].'">';
        $id=$output_arr['data'][$key][count($output_arr['data'][$key]) - 1];
        $output_arr['data'][$key][count($output_arr['data'][$key]) - 1]='';
        }
        echo json_encode($output_arr);
      }
    }
?>
