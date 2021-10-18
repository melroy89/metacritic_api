<?php

namespace Metacritic\API;

use Unirest;

require_once 'libs/Unirest.php';

require_once 'libs/simple_html_dom.php';

class MetacriticAPI
{
    private $response_body = "";
    private $baseUrl = "http://www.metacritic.com/game/";
    private $arrSystems = array();

    public function __construct($system = "pc")
    {
        $this->arrSystems[] = $system;
    }

    public function getMetacriticPage($game_name)
    {
        $returnValue = "";
        # convert spaces to -
        $game_name = str_replace(' ', '-', $game_name);
        # Remove &<space>
        $game_name = str_replace('& ', '', $game_name);
        # lowercase
        $game_name = strtolower($game_name);
        # Remove all special chars execept a-z, digits, --sign, ?-sign, !-sign
        $game_name = preg_replace('/[^a-z\d\?!\-]/', '', $game_name);

        # Get the webpage
        $i = 0;
        do {
            $system = $this->arrSystems[$i++];
            $url = $this->baseUrl . $system . "/" . $game_name;
            $response = Unirest\Request::get($url, $headers = array(), $parameters = null);
        } while ($response->code <> 200 and $i < count($this->arrSystems));

        if ($response->code == 200) {
            $returnValue = $response->raw_body;
        }
        $this->response_body = $returnValue;
    }

    public function getMetacriticScores()
    {
        # Get DOM by string content
        $html = str_get_html($this->response_body);
        # Define json output array
        $json_output = array();
        $error = false;
        # init all vars
        $name = "";
        $metascritic_score = 0;
        $user_score = 0.0;
        $rating = "";
        $developer = "";
        $publisher = "";
        $genres = "";
        $release_date = "";
        $image_url = "";
        $cheat_url = "";

        if (!$html) {
            $json_output['error'] = "Page could not be loaded!";
            $error = true;
        }

        if (!$error) {
            foreach ($html->find('div[class=product_title] h1') as $element) {
                $name = trim($element->plaintext);
            }

            foreach ($html->find('div[class=metascore_w xlarge game positive] span') as $element) {
                $metascritic_score = intval($element->plaintext);
            }

            foreach ($html->find("div[class=metascore_w user large game positive]") as $element) {
                $user_score = floatval($element->plaintext);
            }

            foreach ($html->find('li[class=summary_detail product_rating] span[class=data]') as $element) {
                $rating = trim($element->plaintext);
            }

            $genres = array();
            foreach ($html->find('li[class=summary_detail product_genre] span[class=data]') as $element) {
                array_push($genres, trim($element->plaintext));
            }

            foreach ($html->find('li[class=summary_detail developer] span[class=data]') as $element) {
                $developer = trim($element->plaintext);
            }
            $developers = explode(", ", $developer);

            foreach ($html->find('li[class=summary_detail publisher] span[class=data] a') as $element) {
                $publisher = trim($element->plaintext);
            }

            foreach ($html->find('li[class=summary_detail release_data] span[class=data]') as $element) {
                $release_date = trim($element->plaintext);
            }

            $also_on = array();
            $also_on_url = array();
            foreach ($html->find('li[class=summary_detail product_platforms] span[class=data] a') as $element) {
                array_push($also_on, trim($element->plaintext));
                array_push($also_on_url, $element->href);
            }

            foreach ($html->find('img[class=product_image large_image]') as $element) {
                $image_url = $element->src;
            }

            foreach ($html->find('li[class=summary_detail product_cheats] span[class=data] a') as $element) {
                $cheat_url = $element->href;
            }

            # Prevent memory leak
            $html->clear();
            unset($html);

            # Fill-in the array
            $json_output['name'] = $name;
            $json_output['metascritic_score'] = $metascritic_score;
            $json_output['users_score'] = $user_score;
            $json_output['rating'] = $rating;
            $json_output['genres'] = $genres;
            $json_output['developers'] = $developers;
            $json_output['publishers'] = $publisher;
            $json_output['release_date'] = $release_date;
            $json_output['also_on'] = $also_on;
            $json_output['also_on_url'] = $also_on_url;
            $json_output['thumbnail_url'] = $image_url;
            $json_output['cheat_url'] = $cheat_url;
        }

        # Return JSON format
        return json_encode($json_output);
    }
}

if ($_SERVER['SCRIPT_FILENAME'] == __FILE__) {
    if (isset($_GET['game_title'])) {
        $metacritic_api = new MetacriticAPI();
        $metacritic_api->getMetacriticPage($_GET['game_title']);
        echo $metacritic_api->getMetacriticScores();
    } else {
        echo json_encode(array("error" => "Game title is empty"));
    }
}
