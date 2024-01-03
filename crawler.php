<?php

// Taking arguments
if ($argc < 4) {
    echo 'Usage: php crawler.php term(num) session_period(num) 提案來源("gov", "legis", "comt")\n';
    exit(1);
}
$term = $argv[1];;
$session_period = $argv[2];
$proposal_idx = $argv[3];

$base_url = "https://ly.govapi.tw/bill/";
$proposal_types = [
    "gov" => "政府提案",
    "legis" => "委員提案",
    "comt" => "審查報告",
];
$field = "議案流程";
$page = 1;
$total_page = 1;

$curl = curl_init();
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

while($page <= $total_page && $page <= 100 ) {
    $url = build_url($base_url, $page, $term, $session_period, $proposal_types[$proposal_idx], $field);
    $data = retrieve_data($curl, $url);
    if ($page == 1) {
        $total_page = intval($data[1]);
        echo "total_page: " . $total_page . "\n";
        if ($total_page == 0) { break; }
    }
    save_json($data[0], $term, $session_period, $proposal_idx, $page);
    $page = $page + 1;
}

curl_close($curl);

function save_json($data, $term, $session_period, $proposal_idx, $page) {
    $file_name = "{$term}-{$session_period}-{$proposal_idx}-";
    $file_name = $file_name . str_pad($page, 3, "0", STR_PAD_LEFT);
    echo "data saved to: " . $file_name . "\n";
    file_put_contents("./json/{$file_name}.json", $data);
}

function retrieve_data($curl, $url) {
    curl_setopt($curl, CURLOPT_URL, $url);
    $response = curl_exec($curl);
    $json = json_decode($response, true);
    $total_page = $json["total_page"];
    return [$response, $total_page];
}

function build_url($base_url, $page, $term, $session_period, $proposql_type, $field) {
    $url = $base_url . "?page=" . $page;
    $url = $url . "&bill_type=法律案&bill_type=修憲案";
    $url = $url . "&field=" . $field;
    $url = $url . "&term=" . $term;
    $url = $url . "&sessionPeriod=" . $session_period;
    $url = $url . "&proposal_type=" . $proposql_type;
    return $url;
}
