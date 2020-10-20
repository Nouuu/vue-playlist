<?php
require_once __DIR__ . '/../header_post.php';
include_once __DIR__ . '/../../config/Database.php';
include_once __DIR__ . '/../../class/AlbumInList.php';
include_once __DIR__ . '/../../class/DiscogsAlbum.php';
include_once __DIR__ . '/../../class/Album.php';
include_once __DIR__ . '/../../class/Artist.php';
include_once __DIR__ . '/../../class/User.php';

//require_once __DIR__ . '/../../middleware/user.php';

$database = new Database();
$db = $database->getConnection();

$item = new AlbumInList($db);

$data = json_decode(file_get_contents('php://input'));

$item->album_id = empty($data->album_id) ? die : $data->album_id;
$item->list_id = empty($data->list_id) ? die : $data->list_id;
$item->grade = empty($data->grade) ? null : $data->grade;
$item->note = empty($data->note) ? null : $data->note;

$connectedUser = new User($db);
$connectedUser->getConnectedUser();

//if ($item->isOwner($connectedUser->email_user)) {

$discogsAlbum = DiscogsAlbum::getAlbum($item->album_id);
if ($discogsAlbum == null) {
    http_response_code(404);
    echo json_encode('Album ' . $item->album_id . ' not found');
    die;
}

$artist = new Artist($db);
$artist->name = $discogsAlbum->artist->name;
$artist->id = $discogsAlbum->artist->id;
if (!$artist->createArtist()) {
    http_response_code(500);
    echo json_encode('Artist ' . $artist->name . ' could not be created');
    die;
}

$album = new Album($db);
$album->id = $discogsAlbum->id;
$album->artist_id = $discogsAlbum->artist->id;
$album->title = $discogsAlbum->title;
$album->year = $discogsAlbum->release_date;
$album->image = $discogsAlbum->image;
$album->tracks = $discogsAlbum->tracks;
if (!$album->createAlbum()) {
    http_response_code(500);
    echo json_encode('Album ' . $album->title . ' could not be created');
    die;
}


if ($item->addAlbumInList()) {
    echo json_encode('Album added to list successfully.');
} else {
    http_response_code(500);
    echo json_encode('Album could not be added to list.');
}
//} else {
//    http_response_code(403);
//    echo json_encode('You are not the owner');
//}
