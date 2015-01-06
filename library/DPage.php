<?php
require_once 'simple_html_dom.php';
/**
 * Crawled page data is saved in this class
 *
 * @author Amir Duran <amir.duran@gmail.com>
 * @version 1.0
 */
class DPage {
    /**
     *
     * @var string Page headers 
     */
    protected $headers;
    /**
     *
     * @var String Page content 
     */
    protected $content;
    /**
     *
     * @var String Page URL
     */
    public $url;
    /**
     *
     * @var mixed Errors during crawl 
     */
    public $errors;
    /**
     *
     * @var simple_html_dom
     */
    public $htmlDom;
    
    /**
     * Class constructor is setting headers, content, url and htmlDom attributes to null
     */
    public function __construct() {
        $this->headers=null;
        $this->content=null;
        $this->url=null;
        $this->htmlDom=new simple_html_dom();
    }
    /**
     * Call this function if you want to set page headers
     * @param string $headers
     */
    public function setHeaders($headers){
        $this->headers=$headers;
    }
     /**
     * Call this function if you want to set page content
     * @param string $content
     */
    public function setContent($content){
        $this->content=$content;
        if(isset($content))
            $this->htmlDom->load($content);
        else
            $this->htmlDom->load("");
    }
    /**
     * 
     * @param string $selector You can pass here any valid jQuery selector. For example a[title], #id, .class 
     * @return mixed 
     */
    public function getElementsWithSelector($selector){
        if($this->checkForErrors())
            return false;
        return $this->htmlDom->find($selector);
    }
    
    /**
     * Returns page title
     * @return string Returns page title
     */
    public function getTitle(){
       if($this->checkForErrors())
            return false;
        $title=$this->htmlDom->find("title");
        if(isset($title))
            return $title[0]->innertext;
    }
    /**
     * Returns all page anchors
     * @return mixed
     */
    public function getAnchors(){
       if($this->checkForErrors())
            return false;
        return $this->htmlDom->find("a[href]");
    }
    /**
     * Returns an array of urls in all anchors. For example (a href="http://google.com" ) will return array(0=>"http://google.com")
     * @return array
     */
    public function getLinkURLs(){
        if($this->checkForErrors())
            return false;
        $anchors=$this->getAnchors();
        $results=array();
        foreach($anchors as $a){
            array_push($results, $a->attr["href"]);
        }
        return $results;
    }
    /**
     * Returns an object containing all images in the page
     * @return Pages
     */
    public function getImages(){
        if($this->checkForErrors())
            return false;
        return $this->htmlDom->find("img");
    }
    /**
     * Returns an array of image URLs on the current page
     * @return array
     */
    public function getImageURLs(){
        if($this->checkForErrors())
            return false;
        $images=$this->htmlDom->find("img[src]");
        $result=array();
        foreach($images as $image){
            array_push($result,$image->src);
        }
        return $result;
    }
    
    /**
     * Returns an element with id=$id
     * @param string $id
     * @return mixed
     */
    public function getElementWithId($id){
        if($this->checkForErrors())
            return false;
        return $this->htmlDom->find("#".$id);
    }
    /**
     * Returns an elements with a class=$class
     * @param string $class
     * @return mixed
     */
    public function getElementsWithClass($class){
        if($this->checkForErrors())
            return false;
        return $this->htmlDom->find(".".$class);
    }
    /**
     * Returns an elements with jQuery tag=$tag
     * @param string $tag
     * @return mixed
     */
    public function getElementsWithTag($tag){
        if($this->checkForErrors())
            return false;
        return $this->htmlDom->find($tag);
    }
    /**
     * Checks if there are errors
     * @return boolean
     */
    private function errorsOccured(){
        if(!isset($this->errors))
            return false;
        else
            return true;
    }
    /**
     * Returns all errors during page crawl
     * @return mixed
     */
    public function getErrors(){
        return $this->errors;
    }
    /**
     * Prints all errors during page crawl
     * @return void
     */
    public function printErrors(){
        var_dump($this->errors);
        return;
    }
    /**
     * Sets errors occured during page crawl
     * @param mixed $errors
     */
    public function setErrors($errors){
        $this->errors=$errors;
    }
    private function checkForErrors(){
        if($this->errorsOccured()){
            $this->printErrors();
             return TRUE;
        }
        return FALSE;
    }
}


class DPages implements Iterator {
    
    protected $pages;
    protected $index;
    
    public function __construct() {
        $this->pages=array();
        $index=0;
    }
    public function addPage(DPage $p){
        array_push($this->pages, $p);
    }
    public function removePage($p){
        $key = array_search($p, $this->pages);
        $this->removeElementWithIndex($key);
    }
    public function removeElementWithIndex($index){
        if($index < 0 || $index> count($this->pages)) throw new InvalidArgumentException("Can't find element on the requested index");
        unset($this->pages[$index]);
    }
    public function current() {
        return $this->pages[$this->index];
    }
    public function key() {
        return $this->index;
    }

    public function next() {
        $this->pages[$this->index];
        $this->index++;
    }
    public function rewind() {
        $this->index=0;
    }
    public function valid() {
        return isset($this->pages[$this->index]);
    }
    
    public function getElementWithIndex($index){
        if(isset($this->pages[$index]))
            return $this->pages[$index];
    }

}