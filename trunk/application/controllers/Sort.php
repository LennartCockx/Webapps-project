<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sort extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Sort_model','elderly');
	}

	public function index()
	{
		$this->load->helper('url');
		$this->load->view('caregiver_home');
	}

	public function ajax_list()
	{
		$list = $this->elderly->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $elderly) {
			$no++;
			$row = array();
			$row[] = $elderly->id;
			$row[] = $elderly->first_name;
                        $row[] = $elderly->last_name;
                        $row[] = $elderly->gender;
                        $row[] = $elderly->password;
                        $row[] = $elderly->date_of_birth;
                        $row[] = $elderly->language;
                        $row[] = $elderly->floor_number;
                        $row[] = $elderly->last_domicile;
                        $row[] = $elderly->last_activity;
                        $row[] = $elderly->last_completed;
                        $row[] = $elderly->completed_sessions;
                        $row[] = $elderly->session_in_progress;
                        $row[] = $elderly->account_created_by;
                        $row[] = $elderly->account_created_on;
                       
			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_person('."'".$elderly->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_person('."'".$elderly->id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->elderly->count_all(),
						"recordsFiltered" => $this->elderly->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->elderly->get_by_id($id);
		$data->date_of_birth = ($data->date_of_birth == '0000-00-00') ? '' : $data->date_of_birth; // if 0000-00-00 set to empty for datepicker compatibility
                $data->last_activity = ($data->last_activity == '0000-00-00') ? '' : $data->last_activity; // if 0000-00-00 set to empty for datepicker compatibility
                $data->last_completed = ($data->last_completed == '0000-00-00') ? '' : $data->last_completed; // if 0000-00-00 set to empty for datepicker compatibility
                $data->account_created_on = ($data->account_created_on == '0000-00-00') ? '' : $data->account_created_on; // if 0000-00-00 set to empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'id' => $this->input->post('id'),
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'gender' => $this->input->post('gender'),
                                'password' => $this->input->post('password'),
                                'date_of_birth' => $this->input->post('date_of_birth'),
                                'language' => $this->input->post('language'),
                                'floor_number' => $this->input->post('floor_number'),
                                'last_domicile' => $this->input->post('last_domicile'),
                                'last_activity' => $this->input->post('last_activity'),
                                'last_completed' => $this->input->post('last_completed'),
				'completed_sessions' => $this->input->post('completed_sessions'),
                                'session_in_progress' => $this->input->post('session_in_progress'),
                                'account_created_by' => $this->input->post('account_created_by'),
                                'account_created_on' => $this->input->post('account_created_on'),                     
                    
                    
                    
			);
		$insert = $this->elderly->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
                    'id' => $this->input->post('id'),
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'gender' => $this->input->post('gender'),
                                'password' => $this->input->post('password'),
                                'date_of_birth' => $this->input->post('date_of_birth'),
                                'language' => $this->input->post('language'),
                                'floor_number' => $this->input->post('floor_number'),
                                'last_domicile' => $this->input->post('last_domicile'),
                                'last_activity' => $this->input->post('last_activity'),
                                'last_completed' => $this->input->post('last_completed'),
				'completed_sessions' => $this->input->post('completed_sessions'),
                                'session_in_progress' => $this->input->post('session_in_progress'),
                                'account_created_by' => $this->input->post('account_created_by'),
                                'account_created_on' => $this->input->post('account_created_on'),
				
			);
		$this->elderly->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->elderly->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}


	private function _validate()
	{                            
            
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('firstName') == '')
		{
			$data['inputerror'][] = 'firstName';
			$data['error_string'][] = 'First name is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('lastName') == '')
		{
			$data['inputerror'][] = 'lastName';
			$data['error_string'][] = 'Last name is required';
			$data['status'] = FALSE;
		}		

		if($this->input->post('gender') == '')
		{
			$data['inputerror'][] = 'gender';
			$data['error_string'][] = 'Please select gender';
			$data['status'] = FALSE;
		}

		if($this->input->post('password') == '')
		{
			$data['inputerror'][] = 'password';
			$data['error_string'][] = 'password is required';
			$data['status'] = FALSE;
		}
                
                if($this->input->post('date_of_birth') == '')
		{
			$data['inputerror'][] = 'date_of_birth';
			$data['error_string'][] = 'date_of_birth is required';
			$data['status'] = FALSE;
		}
                if($this->input->post('language') == '')
		{
			$data['inputerror'][] = 'language';
			$data['error_string'][] = 'language is required';
			$data['status'] = FALSE;
		}
                if($this->input->post('floor_number') == '')
		{
			$data['inputerror'][] = 'floor_number';
			$data['error_string'][] = 'floor_number is required';
			$data['status'] = FALSE;
		}  
                if($this->input->post('last_domicile') == '')
		{
			$data['inputerror'][] = 'last_domicile';
			$data['error_string'][] = 'last_domicile is required';
			$data['status'] = FALSE;
		}
                if($this->input->post('last_activity') == '')
		{
			$data['inputerror'][] = 'last_activity';
			$data['error_string'][] = 'last_activity is required';
			$data['status'] = FALSE;
		}
                if($this->input->post('last_completed') == '')
		{
			$data['inputerror'][] = 'last_completed';
			$data['error_string'][] = 'last_completed is required';
			$data['status'] = FALSE;
		}
                if($this->input->post('completed_sessions') == '')
		{
			$data['inputerror'][] = 'completed_sessions';
			$data['error_string'][] = 'completed_sessions is required';
			$data['status'] = FALSE;
		}
                if($this->input->post('last_completed') == '')
		{
			$data['inputerror'][] = 'last_completed';
			$data['error_string'][] = 'last_completed is required';
			$data['status'] = FALSE;
		}
                if($this->input->post('session_in_progress') == '')
		{
			$data['inputerror'][] = 'session_in_progress';
			$data['error_string'][] = 'session_in_progress is required';
			$data['status'] = FALSE;
		}
                if($this->input->post('account_created_by') == '')
		{
			$data['inputerror'][] = 'account_created_by';
			$data['error_string'][] = 'account_created_by is required';
			$data['status'] = FALSE;
		}
                if($this->input->post('account_created_on') == '')
		{
			$data['inputerror'][] = 'account_created_on';
			$data['error_string'][] = 'account_created_on is required';
			$data['status'] = FALSE;
		}


		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

}