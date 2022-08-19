<?php

// puslapio nukreipimas

function redirect($page){
    header('location: ' . URLROOT . '/' . $page);
}