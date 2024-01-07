<?php

$files = scandir("./json/");
$files = array_slice($files, 2);

$pdo = new PDO('sqlite:bill.db');

foreach ($files as $file) {
    $bills = get_bills($file);
    foreach ($bills as $bill) {
        if (!array_key_exists("議案流程", $bill) or count($bill["議案流程"]) == 0) { continue; }
        $progress_array = $bill["議案流程"];
        $bill_data = get_bill_data($bill);
        $bill_id = insert_bill_date($pdo, $bill_data);
        foreach ($progress_array as $progress) {
            list($host, $state, $sessionPeriod, $date) = get_progress_data($progress);
        }
    }
}

function get_bills($filename) {
    $content = file_get_contents("./json/" . $filename);
    $json = json_decode($content, true);
    $bills = $json["bills"];
    return $bills;
}

function get_bill_data($bill) {
    $term = (array_key_exists("屆期", $bill)) ? $bill["屆期"] : "none";
    $sessionPeriod = (array_key_exists("會期", $bill)) ? $bill["會期"] : "none";
    $bill_type = (array_key_exists("議案類別", $bill)) ? $bill["議案類別"] : "none";
    $proposal_source = (array_key_exists("提案來源", $bill)) ? $bill["提案來源"] : "none";
    $billNo = (array_key_exists("billNo", $bill)) ? $bill["billNo"] : "none";
    $serial_number = (array_key_exists("字號", $bill)) ? $bill["字號"] : "none";
    return array($term, $sessionPeriod, $bill_type, $proposal_source, $billNo, $serial_number);
}

function insert_bill_date($pdo, $bill_data) {
    $sql = "INSERT INTO bill " .
           "(term, session_period, bill_type, proposal_source, ppg_bill_number, serial_number) " .
           "VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($bill_data);
    return $pdo->lastInsertId();
}

function get_progress_data($progress) {
    $host = (array_key_exists("院會/委員會", $progress)) ? $progress["院會/委員會"] : "none";
    $state = (array_key_exists("狀態", $progress)) ? $progress["狀態"] : "none";
    $sessionPeriod = (array_key_exists("會期", $progress)) ? $progress["會期"] : "none";
    $date = "none";
    if (array_key_exists("日期", $progress) and count($progress["日期"]) > 0) {
        $date = $progress["日期"][0];
    }
    return array($host, $state, $sessionPeriod, $date);
}
