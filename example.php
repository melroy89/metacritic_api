<?php
# Ignore Unirest warning if any (eg. safe mode related)
#error_reporting(E_ERROR | E_PARSE);
include 'metacritic.php';

$metacritic_api = new MetacriticAPI();
$response = $metacritic_api->get_metacritic_page("The Elder Scrolls V: Skyrim");
$json_reponse = $metacritic_api->get_metacritic_scores($response);

echo "Json Output:\n<br/><br/> ". $json_reponse;
?>
