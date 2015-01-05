<?php

require_once 'DPage.php';

/**
 * Description of DRequest
 * @co
 * @author Amir Duran
 */
class DRequest {

    protected $postParameters;
    protected $url;
    protected $timeout;
    protected $maxRedirects;
    protected $followlocation;
    protected $userAgent = "DuraniusCrawler";
    protected $noBody;
    protected $curl;
    public $result;

    public function __construct($url = "") {
        $this->followlocation = true;
        $this->maxRedirects = 5;
        $this->timeout = 10;
        $this->url = $url;
        $this->noBody = false;
    }

    /**
     * Set request timeout in seconds
     * @param int $timeout Number of seconds before request is timed out
     * @return void 
     */
    public function setTimeout($timeout) {
        $this->timeout = $timeout;
    }

    /**
     * Set maximum number of redirects allowed
     * @return void 
     * @param int $maxRedirects
     */
    public function setMaxRedirects($maxRedirects) {
        $this->maxRedirects = $maxRedirects;
    }

    /**
     * Sets request URL
     * @return void 
     * @param string $url
     */
    public function setRequestUrl($url) {
        if (filter_var($url, FILTER_VALIDATE_URL) === TRUE)
            $this->url = $url;
        else
            throw new InvalidArgumentException("Provided argument is not valid URL!");
    }

    /**
     * Set to true if you want headers only
     * @param bool $value
     * @return void
     */
    public function headersOnly($value) {
        $this->noBody = $value;
    }

    /**
     * Used to send request to the specified URL
     * 
     * @param string $url Server URL
     * @return \DPage Returns an object of type DPage
     */
    public function sendRequestToURL($url) {
        $this->url = $url; //set url
        $this->curl = curl_init(); //Initialize cURL

        curl_setopt_array($this->curl, $this->getCURLOptions()); //Set cURL settings

        if ($this->noBody) {
            curl_setopt($this->curl, CURLOPT_NOBODY, TRUE);
        }

        $result = curl_exec($this->curl);
        if (curl_errno($this->curl)) {
            $this->result = null; //set result to null
            $page = new DPage();
            $page->setErrors(curl_error($this->curl)); //Get curl errors
            return $page;
        } else {
            //save request result
            $this->result = $result;

            //Create Page object
            $page = new DPage();
            //var_dump($result);exit;
            $page->setHeaders($this->get_headers_from_curl_response($result));
            //var_dump($page->headers);exit;

            if ($this->noBody) {
                $page->setContent(null);
            } else {
                $page->setContent($this->getBody($result));
            }
            $page->url = $url;
            $page->setErrors(null);

            //Close CURL
            curl_close($this->curl);
            return $page;
        }
    }

    /**
     * Take only body part of the cURL request
     * @param string $result Content received from the server
     * @return string Returns $page body without headers
     */
    private function getBody($result) {

        $header_size = curl_getinfo($this->curl, CURLINFO_HEADER_SIZE);
        $body = substr($result, $header_size);
        return $body;
    }

    /**
     * Takes only headers from the cURL request
     * @param string $response
     * @return array Returns all headers received
     */
    private function get_headers_from_curl_response($response) {
        $header_size = curl_getinfo($this->curl, CURLINFO_HEADER_SIZE); //Get headers only
        //var_dump($header_size);
        //Because of redirects, cURL can receive multiple headers
        $headers = array();

        //Double new line
        //Different response headers
        $arrRequests = explode("\r\n\r\n", $response);
        for ($index = 0; $index < count($arrRequests) - 1; $index++) {

            if (strpos($arrRequests[$index], 'HTTP/1.1') !== false || strpos($arrRequests[$index], 'HTTP/1.0') !== false) {
                foreach (explode("\r\n", $arrRequests[$index]) as $i => $line) {
                    if ($i === 0)
                        $headers[$index]['http_code'] = $line;
                    else {
                        list ($key, $value) = explode(': ', $line);
                        $headers[$index][$key] = $value;
                    }
                }
            }
        }
        return $headers;
    }

    public function sendRequest() {
        return $this->sendRequestToURL($this->url);
    }

    private function getCURLOptions() {
        return array(
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => $this->followlocation,
            CURLOPT_MAXREDIRS => $this->maxRedirects,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_HEADER => true,
            CURLOPT_USERAGENT, $this->userAgent,
            CURLOPT_SSL_VERIFYPEER,false
        );
    }

}
