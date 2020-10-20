<?php

require_once __DIR__ . '/../header_get.php';
require_once __DIR__ . '/../../env.php';
//require_once __DIR__ . '/../../middleware/user.php';

$curl = curl_init();
$token = discogs_api_token;
$api_url = discogs_api_url;
$release_id = isset($_GET['id']) ? $_GET['id'] : die();;
$release_id = trim(strtolower($release_id));


curl_setopt($curl, CURLOPT_URL, $api_url . '/masters/' . $release_id);

curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'Authorization: Discogs token=' . $token,
    'Accept: application/json'));

curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');
curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

$output = json_decode(curl_exec($curl));

curl_close($curl);

//echo json_encode($output);
//die;

$final_json = [];

$final_json['artist'] = [];
$final_json['artist']['name'] = empty($output->artists[0]->name) ? '' : $output->artists[0]->name;
$final_json['artist']['id'] = empty($output->artists[0]->id) ? '' : $output->artists[0]->id;

$final_json['title'] = empty($output->title) ? '' : $output->title;
$final_json['release_date'] = empty($output->year) ? '' : $output->year;

if (!empty($output->genres)) {
    $final_json['genres'] = [];
    foreach ($output->genres as $genre) {
        $final_json['genres'][] = $genre;
    }
}

if (!empty($output->tracklist)) {
    $final_json['tracklist'] = [];
    foreach ($output->tracklist as $track) {
        $final_json['tracklist'][] = (array)$track;
    }
}

if (!empty($output->images)) {
    $final_json['images'] = [];
    foreach ($output->images as $image) {
        if (!empty($image->uri)) {
            $final_json['images'][] = $image->uri;
        }
    }
}


echo json_encode($final_json);


