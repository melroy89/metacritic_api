<?php
require_once 'libs/Unirest.php';
require_once 'libs/simple_html_dom.php';

class MetacriticAPI
{
    public function get_metacritic_page($game_name)
    {
        $returnValue = "";
        # convert spaces to -
        $game_name = str_replace(' ', '-', $game_name);
        # remove :
        $game_name = str_replace(':', '', $game_name);
        # Remove '
        $game_name = str_replace('\'', '', $game_name);
        # Remove ,
        $game_name = str_replace(',', '', $game_name);
        # Remove .
        $game_name = str_replace('.', '', $game_name);        
        # Remove (r), trade mark & (c) symbols
        $game_name = str_replace(array('®', '™', '©'), array('', '', ''), $game_name);
        
        # lowercase
        $game_name = strtolower($game_name);
        
        # Get the webpage
        $response = Unirest::get("http://www.metacritic.com/game/pc/" . $game_name);                            
        if($response->code == 200)
        {
            $returnValue = $response->raw_body;
        }
        return $returnValue;    
    }
    
    public function get_metacritic_scores($page_response)
    {
        # Get DOM by string content
        $html = str_get_html($page_response);
        # Define json output array
        $json_output = array();
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
        # Return JSON format
        return json_encode($json_output);
    }
}
?>
