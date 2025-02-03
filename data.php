<?php
include_once('db.php');
include_once('model.php');

$user_id = isset($_GET['user']) ? (int)$_GET['user'] : null;

if ($user_id) {
    // Get transactions balances
    $conn = get_connect();
    $resBalance = get_user_transactions_balances($user_id, $conn);
    // TODO: implement
    echo json_encode($resBalance);
}