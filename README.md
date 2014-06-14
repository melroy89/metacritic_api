Metacritic API
==============
Using this Metacritic API, you are able to search games on [metacritic.com](http://www.metacritic.com).

It will give you back the metacritic score, users scores, genres, rating, developers and much more (see below for an example).
The output is in [JSON format](http://us.php.net/manual/en/function.json-decode.php), so it is easy to parse in both PHP or other languages.

By default it uses the URL prefix: "http://www.metacritic.com/game/pc/". If you want to search for Playstation 3 games, just change it to:
"http://www.metacritic.com/game/playstation-3" for example. See [metacritic.php](metacritic.php) file.


Example
-------
Searching on the PC game called "The Elder Scrolls V: Skyrim", 
will give the following JSON output:


```{
  "name": "The Elder Scrolls V: Skyrim",
  "metascritic_score": 94,
  "users_score": 8.3,
  "rating": "M",
  "genres": "Role-Playing",
  "developers": "Bethesda Game Studios",
  "publishers": "Bethesda Softworks",
  "release_date": "Nov 11, 2011",
  "thumbnail_url": "http://static.metacritic.com/images/products/games/7/5988ee04196a686e107b46174f94a3ae-98.jpg",
  "cheat_url": "http://www.gamefaqs.com/console/pc/code/615805.html"
}```

Code example
------------
Please, see [example.php](example.php)

Have fun!

