<?php
/*
bazinis kontroleris
užkrauna modelius ir vaizdus
*/
class Controller{
    //užkrauti modeli
    public function model($model){
        // pareikalauti modelio failo
        require_once '../app/models/' . $model . '.php';
        //isnr model

        return new $model();
    }

    //load vaizda
    public function view($view, $data = []){
        // timkrinti vaizdu faila
        if (file_exists('../app/views/' . $view . '.php')) {
            require_once '../app/views/' . $view . '.php';
        }else{
            //vaizdas neegzistuoja
            die('View does not exist');
        }
    }
}
