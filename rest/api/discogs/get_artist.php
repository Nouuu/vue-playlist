<?php

require_once __DIR__ . '/../header_get.php';
require_once __DIR__ . '/../../middleware/user.php';

function stripBBCode($text_to_search) {
    $pattern = '|[[\\/\\!]*?[^\\[\\]]*?]|si';
    $replace = '';
    return preg_replace($pattern, $replace, $text_to_search);
}

$curl = curl_init();
$token = 'iYVWgqSIDEjmWvgMKZoMfrpESWtiAYJtXghMkhMM';
$api_url = 'https://api.discogs.com';
$artist_id = isset($_GET['id']) ? $_GET['id'] : die();;
$artist_id = trim(strtolower($artist_id));


curl_setopt($curl, CURLOPT_URL, $api_url . '/artists/' . $artist_id);

curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'Authorization: Discogs token=' . $token,
    'Accept: application/json'));

curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');
curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

$output = json_decode(curl_exec($curl));


curl_setopt($curl, CURLOPT_URL, $api_url . '/artists/' . $artist_id . '/releases?&sort=year&sort_order=desc&per_page=100&page=1');

$page = 1;
$releases_api = json_decode(curl_exec($curl));
$releases = [];

while (!empty($releases_api->pagination->pages) && $page <= $releases_api->pagination->pages) {
    foreach ($releases_api->releases as $release) {
        if ($release->type === "master" && $release->artist === $output->name) {
            $releases[] = array(
                'id' => empty($release->id) ? '' : $release->id,
                'title' => empty($release->title) ? '' : $release->title,
                'year'=>empty($release->year) ? '' : $release->year,
                'thumb'=>empty($release->thumb) ? '' : $release->thumb,
            );
        }
    }

    $page++;
    curl_setopt($curl, CURLOPT_URL, $api_url . '/artists/' . $artist_id . '/releases?&sort=year&sort_order=desc&per_page=100&page=' . $page);
    $releases_api = json_decode(curl_exec($curl));
}


curl_close($curl);

//echo json_encode($releases);
//die;

$final_json = [];

$final_json['name'] = empty($output->name) ? '' : $output->name;
$final_json['id'] = empty($output->id) ? '' : $output->id;

$final_json['image'] = empty($output->images[0]) ? '' : $output->images[0]->uri;
$final_json['profile'] = empty($output->profile) ? '' : stripBBCode($output->profile);

$final_json['releases'] = $releases;

echo json_encode($final_json);


