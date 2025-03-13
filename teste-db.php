<?php
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    // Respondendo à requisição OPTIONS
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    exit();
}

header("Access-Control-Allow-Origin: *");       
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    
echo "Backend conectado!";
