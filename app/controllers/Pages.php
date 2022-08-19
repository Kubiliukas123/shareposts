<?php

class Pages extends Controller{
    public function __construct(){

    }
    public function index(){
        if (isLoggedIn()) {
            redirect('posts');
        }

        $data = [
            'title' => 'SharePosts',
            'description' => 'Simple social network built on the MVC PHP framework'
        ];

        $this->view('pages/index', $data);
    }
    
    public function about(){
        $data = [
            'title' => 'About Us',
            'description' => 'App to share posts with other users'
        ];
        $this->view('pages/about', $data);
    }

    public function test(){
        $data = [
            'title' => 'Skill test',
            'description' => 'Just trying stuff out'
        ];
        $this->view('pages/test', $data);
    }
}