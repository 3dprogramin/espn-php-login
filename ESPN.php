<?php

define('LOGIN_URL', 'https://ha.registerdisney.go.com/jgc/v6/client/ESPN-ONESITE.WEB-PROD/guest/login?langPref=en-US');
define('USER_AGENT', 'Mozilla/5.0 (X11; Linux x86_64; rv:61.0) Gecko/20100101 Firefox/61.0');

define('DEBUG', FALSE);

// not sure how this values are generated, but they seem to be working over HOURS now
define('API_KEY', '9/+y/6ojBxE/71zfuUxFdT0vtu4ULsxNPrF790O/9u/X+N6OCr0bThlcTBZUpODfDZU3wVP9mWj3psTKbdvzxQht0IbA');

if(DEBUG){
    // debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}


class ESPN
{
    private $_username;
    private $_password;

    private $_session;
    private $_auth_response;

    /**
     * ESPN constructor.
     * @param $username - ESPN username
     * @param $password - ESPN password
     */
    function __construct($username, $password)
    {
        $this->_username = $username;
        $this->_password = $password;
    }

    /**
     * Log into website and save response data to dict, which is then used to make scrape request
     */
    public function login()
    {
        $this->_session = curl_init();
        //Url to use for login
        curl_setopt($this->_session, CURLOPT_URL, LOGIN_URL);

        //Activate cookiesession
        curl_setopt($this->_session, CURLOPT_COOKIESESSION, 1);

        //No need for SSL
        curl_setopt($this->_session, CURLOPT_SSL_VERIFYPEER, FALSE);

        //User agent
        curl_setopt($this->_session, CURLOPT_USERAGENT, USER_AGENT);

        //Timeout
        curl_setopt($this->_session, CURLOPT_TIMEOUT, 60);

        //Follow redirection
        curl_setopt($this->_session, CURLOPT_FOLLOWLOCATION, true);

        //Return or echo
        curl_setopt($this->_session, CURLOPT_RETURNTRANSFER, 1);

        //Referer
        curl_setopt($this->_session, CURLOPT_REFERER, 'https://cdn.registerdisney.go.com/');

        // debugging
        if (DEBUG) {
            curl_setopt($this->_session, CURLOPT_PROXY, 'localhost:8080');
            curl_setopt($this->_session, CURLOPT_VERBOSE, true);
        }

        // body
        $postData = array();
        $postData['loginValue'] = $this->_username;
        $postData['password'] = $this->_password;
        $data_string = json_encode($postData);

        // POST
        curl_setopt($this->_session, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($this->_session, CURLOPT_POST, 1);


        // header
        $header = array();
        $header[] = 'Content-type: application/json';
        $header[] = 'Authorization: APIKEY ' . API_KEY;
        $header[] = 'Content-Length: ' . strlen($data_string);
        curl_setopt($this->_session, CURLOPT_HTTPHEADER, $header);

        // Save result to content
        $content = curl_exec($this->_session);

        // check if we're logged in
        if (strpos($content, '"email":"') !== false) {
            /**
             * Construct cookies here, so user doesn't have to access the method after login, and we do this only once
             */
            $this->_auth_response = json_decode($content, TRUE);
            $this->construct_cookies();
        } else throw new Exception('not logged in, response: ' . $content);      // throws Exception if not logged in

    }

    /**
     * @param $url - Scrape URL with previous session
     */
    public function scrape($url)
    {
        //Url to use for login
        curl_setopt($this->_session, CURLOPT_URL, $url);

        //Referer
        curl_setopt($this->_session, CURLOPT_REFERER, '');

        //POST - disable
        curl_setopt($this->_session, CURLOPT_POST, 0);

        //Save result to content
        $content = curl_exec($this->_session);
        return $content;
    }

    /**
     * Construct cookies and set them to session
     */
    private function construct_cookies()
    {
        $cookies = "SWID=" . $this->_auth_response['data']['token']['swid'] . "; espn_s2=" . $this->_auth_response['data']['s2'] . ";  ESPN-ONESITE.WEB-PROD.auth=disneyid; ESPN-ONESITE.WEB-PROD-ac=XUS; espnAuth={\"swid\":\"" . $this->_auth_response['data']['token']['swid'] . "\"};";
        curl_setopt($this->_session, CURLOPT_HTTPHEADER, array("Cookie: $cookies"));
    }
}

?>

