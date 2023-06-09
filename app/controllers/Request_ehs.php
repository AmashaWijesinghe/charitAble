<?php
class Request_ehs extends Controller {
    public function __construct(){

        /*if(!isLoggedIn()){
            redirect('users/login');
        }*/
        $this->userModel = $this->model('User');
        $this->ehRequestModel = $this->model('Request_eh');
    }
    public function index(){
        //get requests
        $beneficiaries = $this->ehRequestModel->getBeneficiaries();
        $data =[
            'beneficiaries' =>$beneficiaries
        ];


        $this->view('request_ehs/index', $data);
    }

    public function filter_by_type() {
        $B_Type = $_GET['B_Type'];
        if (empty($B_Type)) {
            $filtered_beneficiaries = $this->ehRequestModel->getBeneficiaries();
        } else {
            $filtered_beneficiaries = $this->ehRequestModel->getBeneficiaryDetailsByType($B_Type);
        }
        $data = [
            'beneficiaries' => $filtered_beneficiaries
        ];
        $this->view('request_ehs/index', $data);
    }

    public function requestEvents($ben_id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $c= $_SESSION['user_id'];
            $d =$this->ehRequestModel-> getEhId($c);
            //Sanitize request data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'document' => $_FILES['document'],
                'document_name' => time().'_'.$_FILES['document']['name'],
                'event_name' => trim($_POST['event_name']),
                'event_date' => trim($_POST['event_date']),
                'event_time' => trim($_POST['event_time']),
                'event_description' => trim($_POST['event_description']),
                'user_id' => $d->E_ID,
                'B_id' => $ben_id,
                'document_err' => '',
                'event_name_err' =>'',
                'event_date_err' => '',
                'event_time_err' => '',
                'event_description_err' => '',
            ];

            if(uploadDocument($data['document']['tmp_name'], $data['document_name'], '/img/documents/')) {
                //done
            }
            else {
                $data['document_err'] = 'Error uploading image';
            }

            //Validate event name
            if(empty($data['event_name'])){
                $data['event_name_err'] = 'Please enter event name';
            }

            //Validate event date
            if(empty($data['event_date'])){
                $data['event_date_err'] = 'Please enter event date';
            }

            //Validate event time
            if(empty($data['event_time'])){
                $data['event_time_err'] = 'Please enter event time';
            }

            //Validate event description
            if(empty($data['event_description'])){
                $data['event_description_err'] = 'Please enter event description';
            }

            //Make sure errors are empty
            if(empty($data['document_err']) && empty($data['event_name_err']) && empty($data['event_date_err']) && empty($data['event_time_err']) && empty($data['event_description_err'])){
                // Validated
                if($this->ehRequestModel->eventRequests($data)){
                    flash('request_message', 'Event request sent successfully');
                    print_r($data);
                   redirect('request_ehs/reviewreq');

                } else {
                    die('Something went wrong');
                }
            }else{
                //Load view with errors
                $this->view('request_ehs/requestEvents', $data);
            }

        }else{
            $data = [
                'document' => '',
                'event_name' => '',
                'event_date' => '',
                'event_time' => '',
                'event_description' => '',
                'B_id' => $ben_id,
                'document_err' => '',
                'event_name_err' => '',
                'event_date_err' => '',
                'event_time_err' => '',
                'event_description_err' => '',
            ];

            $this->view('request_ehs/requestEvents', $data);

        }

        echo json_encode($data);
    }

    public function edit($id){

        $c= $_SESSION['user_id'];
        $d =$this->ehRequestModel-> getEhId($c);
        if($_SERVER['REQUEST_METHOD'] == 'GET'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);


            $data = [
               'document' => $_FILES['document'],
                'document_name' => time().'_'.$_FILES['document']['name'],
                'Event_ID'=> $id,
                'Event_Name' => trim($_GET['Event_Name']),
                'Event_Date' => trim($_GET['Event_Date']),
                'Event_Time' => trim($_GET['Event_Time']),
                'Event_Description' => trim($_GET['Event_Description']),
                'user_id' => $d->E_ID,
               'document_err' => '',
                'Event_Name_err' =>'',
                'Event_Date_err' => '',
                'Event_Time_err' => '',
                'Event_Description_err' => ''
            ];

            //validate req data
           $requests = $this->ehRequestModel->getEventRequestById($id);
            $oldDocument = PUBROOT.'/img/documents/'.$requests->document;


            //post updated
            //user haven't changed the existing image
            if($_POST['intentially_removed'] == 'removed') {
                deleteDocument($oldDocument);
                $data['document_name'] = '';
            }
            else {
                if ($_FILES['document']['name'] == '') {
                    $data['document_name'] = $requests->document;
                } else {
                    updateDocument($oldDocument, $data['document']['tmp_name'], $data['document_name'], '/img/documents/');
                }
            }


            //photo remove intentionally

            //Validate Name
            if(empty($data['Event_Name'])){
                $data['Event_Name_err'] = 'Please enter event name';
            }

            //Validate Telephone Number
            if(empty($data['Event_Date'])){
                $data['Event_Date_err'] = 'Please enter event date';
            }

            //Validate food type
            if(empty($data['Event_Time'])){
                $data['Event_Time_err'] = 'Please enter event time';
            }

            //Validate food type
            if(empty($data['Event_Description'])){
                $data['Event_Description_err'] = 'Please enter event description';
            }

            //Make sure errors are empty
            if(empty($data['Event_Name_err']) && empty($data['Event_Date_err']) && empty($data['Event_Time_err']) && empty($data['Event_Description_err'])){
                // Validated
                if($this->ehRequestModel->updateEventRequests($data)){
                    flash('request_message', 'Request Updated');
                    redirect('request_ehs/reviewreq');
                } else {
                    die('Something went wrong');
                }
            }else{
                //Load view with errors
                $this->view('request_ehs/edit', $data);
            }

        }else{
            //Get existing post from model
            $requests = $this->ehRequestModel->getEventRequestById($id);
            // Check for owner
            if($requests->E_ID != $d->E_ID){
                redirect('request_ehs/reviewreq');
            }

            $data = [
               'document' => '',
                'document_name' => $requests->document,
                'Event_ID'=>$id,
                'Event_Name' => $requests->Event_Name,
                'Event_Date' => $requests->Event_Date,
                'Event_Time' => $requests->Event_Time,
                'Event_Description' =>$requests->Event_Description,
             'document_err' => '',
                'Event_Name_err' => '',
                'Event_Date_err' => '',
                'Event_Time_err' => '',
                'Event_Description_err' => ''
            ];

            $this->view('request_ehs/edit', $data);

        }

        echo json_encode($data);
    }

    public function reviewreq(){

        //get requests
        $requests = $this->ehRequestModel->getEventRequests();
        //$user = $this->userModel->getUserById($id);
        $data = [
            'requests' => $requests,
            //'user' => $user
        ];

        $this->view('request_ehs/reviewreq', $data);
    }

    public function show($id){
        $requests = $this->ehRequestModel->getEventRequestById($id);
        $user = $this->userModel->getEhUserById($requests->E_ID);

        $data = [
            'requests' => $requests,
            'user' => $user
        ];

        if($data['requests']!=null){
            $this->view('request_ehs/show', $data);
        } else {
            redirect('request_ehs/reviewreq');
        }
    }





    public function delete($id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Get existing post from model
            $request = $this->ehRequestModel->getEventRequests();
            // Check for owner
            if($request->E_ID != $_SESSION['user_id']){
                redirect('request_ehs/reviewreq');
            }
            if($this->ehRequestModel->deleteRequest($id)){
                flash('request_message', 'Request Removed');
                redirect('request_ehs/reviewreq');
            } else {
                die('Something went wrong');
            }
        } else {
            redirect('request_ehs/reviewreq');
        }
    }

    public function get_events(){
        $requests = $this->ehRequestModel->getAllRequests();
        $data = [
            'requests' => $requests,
        ];
        echo json_encode($data);
    }
}