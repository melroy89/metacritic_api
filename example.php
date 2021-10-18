<?php

# Ignore Unirest warning if any (eg. safe mode related)
#error_reporting(E_ERROR | E_PARSE);

namespace Metacritic;

include 'metacritic.php';

$metacritic_api = new API\MetacriticAPI();
$metacritic_api->getMetacriticPage("The Elder Scrolls V: Skyrim");
$json_reponse = $metacritic_api->getMetacriticScores();

echo "Json Output:\n<br/><br/> " . $json_reponse;
