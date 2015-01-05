<?php
require_once 'DRequest.php';
/**
 * Easy website crawler.
 *
 * @copyright (c) 2014, Amir Duran
 * @
 * @author Amir Duran
 */
class DCrawler {
    
    /**
     *
     * @var DRequest Userd to send cURL requests to the URL-s 
     */
    protected $request;
    /**
     *
     * @var DPages Used to save results from the cURL requests 
     */
    protected $pages;
    
    public function __construct() {
        $this->request=new DRequest();
        $this->pages=new DPages();
    }

    /**
     * 
     * @param string $url
     * @return DPage Returns the website object which contains page headers and body.
     */
    public function crawlWebsite($url){
        $this->pages->addPage($this->request->sendRequestToURL($url));
        return $this->pages->getElementWithIndex(0);
    }
    /**
     * 
     * @param array $websites An array of websites which should be crawled
     * @return DPages An object of type DPages will be returned
     */
    public function crawlWebsites($websites){
        foreach($websites as $url){
            $this->crawlWebsite($url);
        }
        return $this->pages;
    }
    /**
     * 
     * @return DPages An object of type DPages will be returned
     */
    public function getResults(){
        return $this->pages;
    }
}
