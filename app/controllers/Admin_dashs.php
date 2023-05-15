<?php 
    class Admin_dashs extends Controller {
        public function __construct(){
            // if(!isLoggedIn()){
            //     redirect('users/login');
            // }
            $this->adminDashModel = $this->model('Admin_dash');
            $this->donationModel = $this->model('Donation');
            $this->eventModel = $this->model('Event');
        }

        public function dash_view(){
            // $this->view('admin/admin_dash');
            $ben_count = $this->adminDashModel->getBenCount();
            $don_count = $this->adminDashModel->getDonCount();
            $eh_count = $this->adminDashModel->getEhCount();
            $post_count = $this->adminDashModel->getPostCount();
            $donation_details = $this->donationModel->getDonationDetails();
            $event_details = $this->eventModel->getEventDetails();
            $data = [
                'ben_count' => $ben_count,
                'don_count' => $don_count,
                'eh_count' => $eh_count,
                'post_count' => $post_count,
                'donation_details' => $donation_details,
                'event_details' => $event_details
            ];
            $this->view('admin/admin_dash',$data);
        }

    }