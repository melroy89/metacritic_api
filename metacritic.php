<?php
require_once 'libs/Unirest.php';
require_once 'libs/simple_html_dom.php';

class MetacriticAPI
{
	private $response_body = "";
	private $baseUrl = "http://www.metacritic.com/game/";
	# private $baseUrl = "http://www.metacritic.com/game/playstation-4/";
	private $arrSystems = array();

	function __construct($system = "pc") {
		$this->arrSystems[] = $system;
	}

    public function get_metacritic_page($game_name)
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
		# print_r("Getting ".$url."\n");
	        $response = Unirest\Request::get($url, $headers = array(), $parameters = null);
	} while ($response->code <> 200 and $i<count($this->arrSystems));
       	if($response->code == 200)
        {
       	    $returnValue = $response->raw_body;
        }
	$this->response_body = $returnValue;
    }
    
    public function get_metacritic_scores()
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

		if(!$html) 
		{
			$json_output['error'] = "Page could not be loaded!";
			$error = true;
		}

		if(!$error)
		{
		    foreach($html->find('div[class=product_title] span[itemprop=name]') as $element) 
		    {
		        $name = trim($element->plaintext);
		    }
		    
		    foreach($html->find('span[itemprop=ratingValue]') as $element) 
		    {
		        $metascritic_score = intval($element->plaintext);
		    }
		    
		    foreach($html->find("div[class=userscore_wrap] a[class=metascore_anchor] div[class=metascore_w]") as $element) 
		    {
		        $user_score = floatval($element->plaintext);
		    }
		    
		    foreach($html->find('span[itemprop=contentRating]') as $element) 
		    {
		        $rating = trim($element->plaintext);
		    }
		    
		    foreach($html->find('span[itemprop=genre]') as $element) 
		    {
		        $genres = trim($element->plaintext);
		    }        
		    
		    foreach($html->find('li[class=summary_detail developer] span[class=data]') as $element) 
		    {
		        $developer = trim($element->plaintext);
		    }
		    
		    foreach($html->find('li[class=summary_detail publisher] span[itemprop=name]') as $element) 
		    {
		        $publisher = trim($element->plaintext);
		    }
		    
		    foreach($html->find('span[itemprop=datePublished]') as $element) 
		    {
		        $release_date = trim($element->plaintext);
		    }
		    
		    foreach($html->find('img[itemprop=image]') as $element) 
		    {
		        $image_url = $element->src;
		    }
		    
		    foreach($html->find('li[class=summary_detail product_cheats] span[class=data] a') as $element) 
		    {
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
		    $json_output['developers'] = $developer;
		    $json_output['publishers'] = $publisher;
		    $json_output['release_date'] = $release_date;
		    $json_output['thumbnail_url'] = $image_url;
		    $json_output['cheat_url'] = $cheat_url;
		}

        # Return JSON format
        return json_encode($json_output);
    }
}
?>
