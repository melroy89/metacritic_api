<?php

# Ignore Unirest warning if any (eg. safe mode related)
#error_reporting(E_ERROR | E_PARSE);

namespace Metacritic;

include 'metacritic.php';

// In this example we use the constructor with default parameters,
// meaning it will only search on PC games
$metacritic_api = new API\MetacriticAPI();
// Lets search on a game
$metacritic_api->getMetacriticPage("The Elder Scrolls V: Skyrim");
$json_reponse = $metacritic_api->getMetacriticScores();

echo "Json Output:\n<br/><br/> " . $json_reponse;
