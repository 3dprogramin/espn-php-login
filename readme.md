ESPN PHP
----

The `ESPN.php` class will help you in logging into ESPN, using cURL and will scrape (do a `/GET` request) a URL, as authenticated user

**Example**

``` php
require 'ESPN.php';

/** Save response to file
 * @param $response
 */
function save_response($response){
    $f = fopen('/home/icebox/Desktop/response.html', 'w');
    fwrite($f, $response);
    fclose($f);
}

$username = 'mrockett@mail.com';
$password = 'ESPN456!';
$scrape_url = 'http://games.espn.com/ffl/clubhouse?leagueId=93772&teamId=1&seasonId=2018';

try {
    $espn = new ESPN($username, $password); // initialize class with username and password
    $espn->login();                         // do login (and set cookies right)
    $resp = $espn->scrape($scrape_url);     // scrape url
    save_response($resp);       // save to file to see it better in browser
} catch (Exception $ex) {
    die($ex->getMessage());
}
```

**Observation**

The class logs in, gets data such as `s2` and `swid` from response, and sets those as cookies, along with few others.
Once the cookies are set, you can make requests to whatever link, and it will show you just as you were logged in.

There is one variable which I gathered manually, and might change, once every week, month, etc, and that's the **APIKEY**,
which is sent with the 1st request, the login request.

As time passes, we'll know for sure if it's valid forever or not.

In the ESPN.php class it's defined like this:
```
define('API_KEY', '9/+y/6ojBxE/71zfuUxFdT0vtu4ULsxNPrF790O/9u/X+N6OCr0bThlcTBZUpODfDZU3wVP9mWj3psTKbdvzxQht0IbA');
```