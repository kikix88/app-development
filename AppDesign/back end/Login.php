<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class Login extends CI_Controller {

	public function index() {
        if($this->session->userdata('meetings')) {
            header("Location: http://churchbuild.net/index.php/dashboard");
        }

        if($this->input->post('login')) {
            $email = trim($this->input->post('username'));
            $given_password = $this->input->post('password');

            require_once APPPATH . 'third_party/firebase/vendor/autoload.php';

            $factory = (new Factory)->withServiceAccount(APPPATH.'/church-en-firebase-adminsdk-pzrsd-7b7e16235d.json');
            $database = $factory->createDatabase();
            $given_email = str_replace(".","**",$email);

            if($database->getReference("user/$given_email/password")->getValue() === $given_password) {
                $user = $database->getReference("user/$given_email")->getValue();

                $sess_array = array(
                    "email" => $email,
                    "name"  => $user["name"],
                    "role"  => "" . $user["fix"],
                );

                //fix = 3 for super-admin (Mr.Lin and team)
                //fix = 2 for admin (Church admins)
                //fix = 0/1 for hosts (Meeting hosts)

                if($user["fix"] == 2) {
                    $sess_array["church"] = $user["church"];
                }

                $this->session->set_userdata('meetings', $sess_array);

                header("Location: http://churchbuild.net/index.php/dashboard");

            } else {
                $this->session->set_flashdata('error', 'Wrong Credentials');
            }
        }

        $this->load->view('login',@$data);
    }

    public function logout() {
        $this->session->sess_destroy();
        header("Location: http://churchbuild.net/index.php/login");
    }
}