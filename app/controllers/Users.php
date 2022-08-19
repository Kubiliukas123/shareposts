<?php
    class Users extends Controller {
        public function __construct(){
            $this->userModel = $this->model('User');
        }
        
        public function register(){
            // tikrinti post
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                //proces form
                
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                $data = [
                    'name' => trim($_POST['name']),
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                    'confirm_password' => trim($_POST['confirm_password']),
                   'name_err ' => '',
                   'email_err ' => '',
                   'password_err ' => '',
                   'confirm_password_err ' => '' 
                   ];

                   // emailo validacija
                   if (empty($data['email'])) {
                        $data['email_err'] = 'Please enter email';
                   } else {
                    // patikrintio emaila
                    if ($this->userModel->findUserByEmail($data['email'])) {
                        $data['email_err'] = 'Email already exists';
                    }
                   }

                   // vardo validacija
                   if (empty($data['name'])) {
                    $data['name_err'] = 'Please enter name';
                   }

                   // pwd validacija
                   if (empty($data['password'])) {
                    $data['password_err'] = 'Please enter password';
                   } elseif(strlen($data['password']) < 6){
                    $data['password_err'] = 'Password must be atleast 6 characters';
                   }
                     //  confirm pwd validacija
                   if (empty($data['confirm_password'])) {
                    $data['confirm_password_err'] = 'Please confirm password';
                   }else{
                    if ($data['password'] != $data['confirm_password']) {
                        $data['confirm_password_err'] = 'Passwords do not match';
                    }
                   }

                   // isitikinam kad klaidu nera

                   if (empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
                    // validuota

                   //hash pwd
                   $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                   //registruoti useri

                   if($this->userModel->register($data)){
                    flash('register_success', 'You are registered and can log in');
                    redirect('users/login');
                   }else{
                    die('Something went wrong');
                   }

                   }else{
                    // uzkrauna view su klaidom
                    $this->view('users/register', $data);
                   }

            }else {
                //innit data
               $data = [
                'name' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
               'name_err ' => '',
               'email_err ' => '',
               'password_err ' => '',
               'confirm_password_err ' => '' 
               ];

               // uzkrauti view
               $this->view('users/register', $data);
            }
        }

        public function login(){
            // tikrinti post
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                //proces form
                
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                $data = [
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                   'email_err ' => '',
                   'password_err ' => '', 
                   ];
                
                     // emailo validacija
                     if (empty($data['email'])) {
                        $data['email_err'] = 'Please enter email';
                   }
                   
                   // pwd validacija
                   if (empty($data['password'])) {
                    $data['password_err'] = 'Please enter password';
                   } 

                    //tikrinam user/email
                    if ($this->userModel->findUserByEmail($data['email'])) {
                        //useris rastas
                        
                    }else{
                        //nerastas useris
                        $data['email_err'] = 'No user found';
                    }

                   // isitikinam kad klaidu nera

                   if (empty($data['email_err']) && empty($data['password_err'])) {
                    // validuota
                    //patikrinam ir paruosiam prisijungusi useri
                    $loggedInUser = $this->userModel->login($data['email'], $data['password']);
                    if ($loggedInUser) {
                        $this->createUserSession($loggedInUser);
                    }else {
                        $data['password_err'] = 'Password incorrect';

                        $this->view('users/login', $data);
                    }
                   }else{
                    // uzkrauna view su klaidom
                    $this->view('users/login', $data);
                   }
                    
            }else {
                //innit data
               $data = [
                'email' => '',
                'password' => '',
               'email_err ' => '',
               'password_err ' => '',
               ];

               // uzkrauti view
               $this->view('users/login', $data);
            }
        }

        public function createUserSession($user){
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_email'] = $user->email;
            $_SESSION['user_name'] = $user->name;
            redirect('posts');
        }

        public function logout(){
            unset($_SESSION['user_id']);
            unset($_SESSION['user_email']);
            unset($_SESSION['user_name']);
            session_destroy();
            redirect('users/login');
        }
}