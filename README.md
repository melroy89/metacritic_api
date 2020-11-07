# Metacritic API

Using this Metacritic API, you are able to search games on [metacritic.com](http://www.metacritic.com).

It will give you back the metacritic score, users score, genres, rating, developers and much more (see below for an example).
The output is in [JSON format](http://us.php.net/manual/en/function.json-decode.php), so it is easy to parse in both PHP or other languages.

By default it uses the URL prefix: "http://www.metacritic.com/game/pc/". If you want to search for Playstation 3 games, just change it to:
"http://www.metacritic.com/game/playstation-3". See [metacritic.php](metacritic.php) file.

## Example

Searching on the PC game called "The Elder Scrolls V: Skyrim", 
will give the following JSON output:

```json
{
  "name": "The Elder Scrolls V: Skyrim",
  "metascritic_score": 94,
  "users_score": 8.2,
  "rating": "M",
  "genres": [
    "Role-Playing",
    "First-Person",
    "First-Person",
    "Western-Style"
  ],
  "developers": [
    "Bethesda Game Studios"
  ],
  "publishers": "Bethesda Softworks",
  "release_date": "Nov 10, 2011",
  "also_on": [
    "PlayStation 3",
    "Xbox 360"
  ],
  "also_on_url": [
    "/game/playstation-3/the-elder-scrolls-v-skyrim",
    "/game/xbox-360/the-elder-scrolls-v-skyrim"
  ],
  "thumbnail_url": "http://static.metacritic.com/images/products/games/7/5988ee04196a686e107b46174f94a3ae-98.jpg",
  "cheat_url": "http://www.gamefaqs.com/console/pc/code/615805.html"
}
```

## On error

When for some reason the page couldn't be loaded / found or parsed, you will get the following JSON response:

```json
{"error":"Page could not be loaded!"}
```

Or when metacritic.php is directly called, without input you will get: `{"error": "Game title is empty"}`

## Code example

Please, see [example.php](example.php)

Another way to access the API is to directly call [metacritic.php](metacritic.php) via the website URL:
```sh
metacritic.php?game_title=Halo%202
```

Be-aware that the game title needs to be [URL encoded](https://www.w3schools.com/tags/ref_urlencode.asp) to work.

Have fun!

## CI/CD

Code quality is checked in GitLab CI/CD, to avoid regression.

Currently in the pipeline:

* [Psalm](https://psalm.dev/) - Static analysis tool for PHP
* [Phpcs](https://github.com/squizlabs/PHP_CodeSniffer) - PHP coding style standard (`phpcbf` command for auto-fix)
* [Phpmetrics](https://phpmetrics.github.io/PhpMetrics/) - PHP metrics for complexity, object oriented, maintainability and more.
  * [Latest Metrics Report](https://gitlab.melroy.org/melroy/metacritic_api/-/jobs/artifacts/master/file/report/index.html?job=phpmetrics)
