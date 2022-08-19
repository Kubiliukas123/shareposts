<?php

class User {
    private $db;

    public function __construct(){
      $this->db = new Database;  
    }

    // registruoti useri
    public function register($data){
        $this->db->query('INSERT INTO users (name, email, password) VALUES(:name, :email, :password)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);

        if ($this->db->execute()) {
            return true;
        }else{
            return false;
        }
    }

    //login user
    public function login($email, $password){
        $this->db->query(' SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        $hased_password = $row->password;
        if (password_verify($password, $hased_password)) {
            return $row;
        }else {
            return false;
        }
    }

    //rasti useri pagal emaila
    public function findUserByEmail($email){
        $this->db->query(' SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();


        if ($this->db->rowCount() > 0) {
           return true;
        }else {
            return false;
        }
    }
    //rasti useri pagal id
    public function getUserById($id){
        $this->db->query(' SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);

        $row = $this->db->single();

        return $row;
    }
}