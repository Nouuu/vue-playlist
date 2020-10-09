<?php
require_once __DIR__ . '/DiscogsArtist.php';

class DiscogsAlbum
{
    protected string $token = 'iYVWgqSIDEjmWvgMKZoMfrpESWtiAYJtXghMkhMM';
    protected string $api_url = 'https://api.discogs.com';

    public DiscogsArtist $artist;
    public string $title;
    public $release_date;
    public int $tracks;
    public string $image;

    public function __construct()
    {
    }

    public static function getAlbum(int $id): DiscogsAlbum
    {
        $album = new DiscogsAlbum();
        $curl = curl_init();
        $release_id = trim(strtolower($id));

        curl_setopt($curl, CURLOPT_URL, $album->api_url . '/masters/' . $release_id);

        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: Discogs token=' . $album->token,
            'Accept: application/json'));

        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');
        curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $output = json_decode(curl_exec($curl));

        curl_close($curl);

        $album->artist = new DiscogsArtist();
        $album->artist->name = empty($output->artists[0]->name) ? '' : $output->artists[0]->name;
        $album->artist->id = empty($output->artists[0]->id) ? '' : $output->artists[0]->id;

        $album->title = empty($output->title) ? '' : $output->title;
        $album->release_date = empty($output->year) ? '' : $output->year;
        $album->tracks = sizeof($output->tracklist);
        $album->image = empty($output->images) ? '' : $output->images[0];

        return $album;
    }
}