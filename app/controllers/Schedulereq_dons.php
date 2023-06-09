<?php
class Schedulereq_dons extends Controller
{
    public function __construct()
    {

        /*if(!isLoggedIn()){
            redirect('users/login');
        }*/
        $this->userModel = $this->model('User');
        $this->requestModel = $this->model('Schedulereq_don');
        $this->requestbenModel = $this->model('Request_ben');
        $this->benModel = $this->model('BenSearch');

    }

    public function index()
    {
        //get requests
        if (isset($_POST['input'])) {
            $search = $this->benModel->getBen($_POST['input']);
            $data = [
                'search' => $search
            ];

            //header('Content-Type: application/json');
//            echo json_encode($data);
            $this->view('schedulereq_dons/index', $data);
        } else {
            $search = $this->requestModel->getNames();


            $data = [
                'search' => $search
            ];

            $this->view('schedulereq_dons/index', $data);
        }
    }

    public function searchBen()
    {

        if(isset($_POST['input'])){
            $search = $_POST['input'];
            // Define the regex pattern to match unwanted characters
            $pattern = '/[^a-zA-Z0-9\s]/';
            // Remove any unwanted characters from the search string
            $search = preg_replace($pattern, '', $search);
            $search_res = $this->benModel->getBen($search);
        }
        else{
            $search_res = $this->requestModel->getNames();
        }

        if ($search_res) {
            $output = '<table class="tableBen" id="tableBen">
                <thead>
                <tr>
              
                    <th>Name</th>
                    <th>Address</th>
                    <th>Type</th>
                    <th>Telephone Number</th>
                    <th>E-Mail</th>
                    <th>Members</th>


                    
                </tr>
                </thead>
                <tbody>';

            foreach ($search_res as $res) {

                $output .= '<tr>
                    
                        <td data-label="Name">' . $res->B_Name . '</td>
                        <td data-label="Address">' . $res->B_Address . '</td>
                        <td data-label="Address">' . $res->B_Type . '</td>
                        <td data-label="Telephone">' . $res->B_Tpno . '</td>
                        <td data-label="E-Mail">'.$res->B_Email . '</td>
                        <td data-label="members">'.$res->B_Members.'</td>
                        <td ><a href="<?php echo URLROOT; ?>/schedulereq_dons/add/<?php echo $search->B_Id; ?>"
>Select</a></td>
                        <td><a href="<?php echo URLROOT; ?>/profilebens/index/<?php echo $search->B_Id; ?>" >View
                        Profile</a></td>

                        
                       
                        
                    </tr>';
            };
            $output .= '</tbody>';
        } else {
            $output = '<h3>No Beneficiary  Found</h3>';
        }
        echo $output;

    }
    public function fetch(){
        $data=$this->benModel->getBen($_POST['input']);
        $this->view('schedulereq_dons/index', $data);

    }

    public function add($ben_id){


        if($_SERVER['REQUEST_METHOD'] == 'POST'){

           /* $requests = $this->requestModel->getDRequestById($ben_id);
            /*        $user = $this->userModel->getDUserById($requests->D_Id);*/

            /*$data = [
                'requests' => $requests,
                /*            'user' => $user*/


            /*if($data['requests']!=null){
                $this->view('schedulereq_dons/add', $data);
            } else {
                redirect('schedulereq_dons/index');
            }*/


            $c= $_SESSION['user_id'];
            $d =$this->requestModel-> getdonId($c);

            //Sanitize request data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $data = [

            'Food_Type' => trim($_POST['Food_Type']),
            'Donation_Quantity' => trim($_POST['Donation_Quantity']),
            'D_Date' => trim($_POST['D_Date']),
            'Time' => trim($_POST['Time']),
            'user_id' => $d->D_Id,            
            'B_id' => $ben_id,

            'Food_Type_err' => '',
            'Donation_Quantity_err' => '',
            'D_Date_err' => '',
            'Time_err' => ''
        ];


        //Validate food type
        if(empty($data['Food_Type'])){
            $data['Food_Type_err'] = 'Please enter food type';
        }

        //Validate food type
        if(empty($data['Donation_Quantity'])){
            $data['Donation_Quantity_err'] = 'Please enter donation quantity';
        }

        //Validate date
        if(empty($data['D_Date'])){
            $data['D_Date_err'] = 'Please enter date';
        }
        //Validate time
        if(empty($data['Time'])){
            $data['Time_err'] = 'Please enter time';
        }

        //Make sure errors are empty
        if( empty($data['Food_Type_err']) && empty($data['Donation_Quantity_err']) && empty($data['D_Date_err']) && empty($data['Time_err'])){
            // Validated
            $time=$data['Time'];
            $date=$data['D_Date'];
            $quantity = $data['Donation_Quantity'];


            $existing_request = $this->requestModel->checkExRequests($ben_id, $time, $date);
            if($existing_request){

                // Update comschedule table
                $b_members = $this->requestModel->getBMembers($ben_id)->B_Members; /*- ($total_quantity)->Total_Quantity*/;
                $total_quantity = $this->requestModel->getTotalQuantity($ben_id, $time, $date)->Total_Quantity;
                $remaining_quantity =  $b_members - $total_quantity ;
                var_dump($remaining_quantity,$total_quantity);
                if ( $remaining_quantity<$quantity) {
                    $data['Donation_Quantity_err'] = 'Donation quantity cannot be greater than remaining quantity';
                } else {
                    $this->requestModel->updateComSchedule($existing_request->S_ID, $data['Donation_Quantity']);
                    $this->requestModel->checkExRequests($ben_id,$date,$time);
                    $data["S_Id"]=$existing_request->S_ID;
/*                    var_dump($existing_request->S_ID);*/
                }
            } else {
                // Add new row to comschedule table
                $b_members = $this->requestModel->getBMembers($ben_id)->B_Members;
                if ($data['Donation_Quantity'] > $b_members) {
                    $data['Donation_Quantity_err'] = 'Donation quantity cannot be greater than no of members';
/*                    die("die amasha123");*/

                } else {

                $com_data = [
                    'B_id' => $ben_id,
                    'D_Date' => $data['D_Date'],
                    'Time' => $data['Time'],
                    'Donation_Quantity' => $data['Donation_Quantity']
                ];
                $this->requestModel->addcomschedule($com_data);
               $sid=$this->requestModel->checkExRequests($ben_id,$time,$date);
               var_dump($sid->S_ID);

                    $data["S_Id"]=$sid->S_ID;
                }
            }

            if($this->requestModel->addRequests($data)){

                flash('request_message', 'Request Added');
                redirect('schedulereq_dons/reviewreq');
            } else {
                die('Something went wrong');
            }
        }else{
            //Load view with errors
            $this->view('schedulereq_dons/add', $data);
        }
    }else{
        $data = [

            'Food_Type' => '',
            'Donation_Quantity' => '',
            'D_Date' => '',
            'Time' => '',
            'B_id' => $ben_id,

            'Food_Type_err' => '',
            'Donation_Quantity_err' => '',
            'D_Date_err' => '',
            'Time_err' => ''
        ];

        $this->view('schedulereq_dons/add', $data);

    }

    echo json_encode($data);
}

public function edit($id){
    
    $c= $_SESSION['user_id'];
    $d =$this->requestModel-> getdonId($c);
    if($_SERVER['REQUEST_METHOD'] == 'GET'){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    $data = [
        'B_Req_ID'=> $id,
        'Food_Type' => trim($_GET['Food_Type']),
        'Donation_Quantity' => trim($_GET['Donation_Quantity']),
        'D_Date' => trim($_GET['D_Date']),
        'Time' => trim($_GET['Time']),
        'user_id' => $d->D_Id,

        'Food_Type_err' => '',
        'Donation_Quantity_err' => '',
        'D_Date_err' => '',
        'Time_err' => ''
    ];


    //Validate food type
    if(empty($data['Food_Type'])){
        $data['Food_Type_err'] = 'Please enter food type';
    }

    //Validate food type
    if(empty($data['Donation_Quantity'])){
        $data['Donation_Quantity_err'] = 'Please enter donation quantity';
    }

    //Validate date
    if(empty($data['D_Date'])){
        $data['D_Date_err'] = 'Please enter date';
    }
    //Validate time
    if(empty($data['Time'])){
        $data['Time_err'] = 'Please enter time';
    }

    //Make sure errors are empty
    if(empty($data['Food_Type_err']) && empty($data['Donation_Quantity_err']) && empty($data['D_Date_err']) && empty($data['Time_err'])){

        // Validated

        $time=$data['Time'];
        $date=$data['D_Date'];
        $requestDetails = $this->requestModel->getDRequestByID($id);
        $reqDetails = $this->requestModel->getReqDetails($id);
        $S_Id = $reqDetails->S_Id;
        $ben_id = $requestDetails->B_Id;
        $ExDonation_Quantity = $reqDetails->Donation_Quantity;

        $comDetails = $this->requestModel->getComDetails($S_Id);
        $total_quantity = $comDetails -> Total_Quantity;
        $existing_req = $this->requestModel->checkSchReqDandT($ben_id, $time, $date, $id);
        $existing_request = $this->requestModel->checkExRequests($ben_id, $time, $date);
/*var_dump($ExDonation_Quantity, $S_Id, $total_quantity, $existing_req, $existing_request);*/
        if($existing_req){

             if($this->requestModel->updateRequests($data/*$existing_req->S_Id, $data['Donation_Quantity']*/)){
/*                 $data["S_Id"]=$S_Id;*/

            flash('request_message', 'Request Updated');
                 redirect('schedulereq_dons/reviewreq');
             } else {
                 die('Something went wrong');
             }
            if($total_quantity>$ExDonation_Quantity){
                 $this->requestModel->upDelComSchedule($S_Id, $ExDonation_Quantity);

                 $this->requestModel->updateComSchedule($S_Id, $data['Donation_Quantity']);
             }else{

                 $this->requestModel->upDelComSchtable($existing_req->S_Id, $data['Donation_Quantity']);
             }
         }else{
            var_dump($ExDonation_Quantity->S_Id,$existing_request->S_Id, $existing_req->S_ID, $data);

            if($total_quantity>$ExDonation_Quantity){
                 $this->requestModel->upDelComSchedule($S_Id, $ExDonation_Quantity);
                 if($existing_request){
                     $this->requestModel->updateComSchedule($existing_request->S_ID, $data['Donation_Quantity']);
                 }else{
                     $com_data = [
                         'B_id' => $ben_id,
                         'D_Date' => $data['D_Date'],
                         'Time' => $data['Time'],
                         'Donation_Quantity' => $data['Donation_Quantity']
                     ];
                     $this->requestModel->addcomschedule($com_data);
                 }
                 //S_Id eka update wenne na
                 if($this->requestModel->updateRequests($data)){
                     flash('request_message', 'Request Updated');
                     redirect('schedulereq_dons/reviewreq');
                 } else {
                     die('Something went wrong');
                 }
             }else{
/*                 var_dump($ExDonation_Quantity->S_Id,$existing_request->S_Id, $existing_req->S_ID, $data);*/
                 $this->requestModel->updateDelComSch( $data,$ExDonation_Quantity->S_Id, $data['Donation_Quantity']);
                 if($this->requestModel->updateNewRequests($data)){
                     $existing = $this->requestModel->checkExRequests($ben_id, $time, $date);
                     var_dump($existing);
                     $data[$S_Id] = $existing->S_ID;
                     flash('request_message', 'Request Updated');
/*                     redirect('schedulereq_dons/reviewreq');*/
                 } else {
                     die('Something went wrong');
                 }
             }

         }

    }else{
        //Load view with errors
        $this->view('schedulereq_dons/edit', $data);
    }

}else{
    //Get existing post from model
    $requests = $this->requestModel->getDRequestById($id);
    // Check for owner
    if($requests->D_Id != $d->D_Id){
/*        redirect('schedulereq_dons/reviewreq');*/
    }

    $data = [
        'S_Id'=>$requests->S_Id,
        'B_Req_ID'=>$id,

        'Food_Type' => $requests->Food_Type,
        'Donation_Quantity' =>$requests->Donation_Quantity ,
        'D_Date' => $requests->D_Date,
        'Time' => $requests->Time,
    ];

    $this->view('schedulereq_dons/edit', $data);

}

echo json_encode($data);
}

public function reviewreq(){
    
    //get requests
    $requests = $this->requestModel->getRequests();
    //$user = $this->userModel->getUserById($id);
    $data = [
        'requests' => $requests,
        //'user' => $user
    ];

    $this->view('schedulereq_dons/reviewreq', $data);
}

    public function ongoingschreq(){

        //get requests
        $requests = $this->requestModel->getScheduleReq();
        $requestsben = $this->requestModel->getRecentBeneficiaryReq();

        //$user = $this->userModel->getUserById($id);
        $data = [
            'requests' => $requests,
            'requestsben' => $requestsben,

            //'user' => $user
        ];

        $this->view('schedulereq_dons/ongoingschreq', $data);
    }

    public function comschreq(){

        //get requests
        $requestscom = $this->requestModel->getCompletedScheduleReq();

        //$user = $this->userModel->getUserById($id);
        $data = [
            'requestscom' => $requestscom,

            //'user' => $user
        ];

        $this->view('schedulereq_dons/comschreq', $data);
    }

    public function combenreq(){

        //get requests
        $requestscomben = $this->requestModel->getCompletedBeneficiaryReq();

        //$user = $this->userModel->getUserById($id);
        $data = [
            'requestscomben' => $requestscomben,

            //'user' => $user
        ];

        $this->view('schedulereq_dons/combenreq', $data);
    }

public function show($id){
    $requests = $this->requestModel->getDRequestById($id);
    $user = $this->userModel->getDUserById($requests->D_Id);

$data = [
    'requests' => $requests,
    'user' => $user
];

if($data['requests']!=null){
    $this->view('schedulereq_dons/show', $data);
    } else {
        redirect('schedulereq_dons/reviewreq');
    }
}

    public function showname($id){
        $requests = $this->requestModel->getDRequestById($id);
        $user = $this->userModel->getDUserById($requests->D_Id);

        $data = [
            'requests' => $requests,
            'user' => $user
        ];

        if($data['requests']!=null){
            $this->view('schedulereq_dons/add', $data);
        } else {
            redirect('schedulereq_dons/index');
        }
    }

    public function showbenreq($id){
        $requests = $this->requestbenModel->getRequestById($id);
        $user = $this->userModel->getUserById($requests->B_Id);

        $data = [
            'requests' => $requests,
            'user' => $user
        ];

        if($data['requests']!=null){
            $this->view('schedulereq_dons/showbenreq', $data);
        } else {
            redirect('dashboard_dons/index');
        }
    }

public function delete($Id)
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get existing post from model
        $request = $this->requestModel->getRequests();
        // Check for owner
        if ($request->D_Id != $_SESSION['user_id']) {
            redirect('schedulereq_dons/reviewreq');
        }

        $reqDetails = $this->requestModel->getReqDetails($Id);
        $S_Id = $reqDetails->S_Id;
        $Donation_Quantity = $reqDetails->Donation_Quantity;

        $comDetails = $this->requestModel->getComDetails($S_Id);
        $total_quantity = $comDetails->Total_Quantity;

        if ($this->requestModel->deleteRequest($Id)) {
            if ($total_quantity > $Donation_Quantity) {
                if ($this->requestModel->upDelComSchedule($S_Id, $Donation_Quantity)) {
                    flash('request_message', 'Request Removed');
                    redirect('schedulereq_dons/reviewreq');
                } else {
                    die('Something went wrong');
                }
            } else {
                if ($this->requestModel->deleteReqCom($S_Id)) {
                    flash('request_message', 'Request Removed');
                    redirect('schedulereq_dons/reviewreq');
                } else {
                    die('Something went wrong');

                }
            }

        } else {
            redirect('schedulereq_dons/reviewreq');
        }
    }
}

public function get_meals(){
    $requests = $this->requestModel->getAllRequests();
    $data = [
        'requests' => $requests,
    ];
    echo json_encode($data);
    }
}

