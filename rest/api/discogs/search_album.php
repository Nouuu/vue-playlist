<?php

require_once __DIR__ . '/../header_get.php';
//require_once __DIR__ . '/../../middleware/user.php';

$curl = curl_init();
$token = 'iYVWgqSIDEjmWvgMKZoMfrpESWtiAYJtXghMkhMM';
$api_url = 'https://api.discogs.com';
$search_entry = isset($_GET['search']) ? $_GET['search'] : die();;
$search_entry = trim(strtolower($search_entry));


curl_setopt($curl, CURLOPT_URL, $api_url . '/database/search?q=' . $search_entry . '&type=master&per_page=20');

curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'Authorization: Discogs token=' . $token,
    'Accept: application/json'));

curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');
curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

$output = json_decode(curl_exec($curl));

curl_close($curl);

//var_dump($output);
//die;

$final_json = [];

$final_json['total_result'] = empty($output->pagination->items) ? 0 : $output->pagination->items;
$final_json['showed_result'] = empty($output->pagination->per_page) ? 0 : $output->pagination->per_page;
$final_json['results'] = [];

if (!empty($output->results)) {
    foreach ($output->results as $item) {
        $final_json['results'][] = array(
            'id' => empty($item->id) ? '' : $item->id,
            'title' => empty($item->title) ? '' : $item->title,
            'year' => empty($item->year) ? '' : $item->year,
            'thumb' => empty($item->thumb) ? '' : $item->thumb,
            'cover' => empty($item->cover_image) ? '' : $item->cover_image
        );
    }
}

echo json_encode($final_json);

