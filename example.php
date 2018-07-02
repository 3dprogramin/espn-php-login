#!/usr/bin/php

<?php
require 'ESPN.php';

/** Save response to file
 * @param $response
 */
function save_response($response){
    $f = fopen('/home/icebox/Desktop/response.html', 'w');
    fwrite($f, $response);
    fclose($f);
}

/**
 * Log into ESPN website and scrape/GET a page
 */
function test()
{
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
}

test();     // run it

?>