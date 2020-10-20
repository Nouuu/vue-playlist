<?php

require_once __DIR__ . '../env.php';

class DiscogsArtist
{
    protected string $token = discogs_api_token;
    protected string $api_url = discogs_api_url;

    public $id;
    public $name;

    public function __construct()
    {
    }

    public static function getArtist(int $id): ?DiscogsArtist
    {
        $artist = new DiscogsArtist();
        $curl = curl_init();
        $artist_id = trim(strtolower($id));

        curl_setopt($curl, CURLOPT_URL, $artist->api_url . '/artists/' . $artist_id);

        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: Discogs token=' . discogs_api_token,
            'Accept: application/json'));

        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');
        curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $output = json_decode(curl_exec($curl));

        if (curl_errno($curl)) {
            return null;
        }

        $artist->name = empty($output->name) ? '' : $output->name;
        $artist->id = empty($output->id) ? '' : $output->id;

        return $artist;
    }
}