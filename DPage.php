<?php
require_once './simple_html_dom.php';
/**
 * Description of DPage
 *
 * @author Amir Duran
 */
class DPage {
    protected $headers;
    protected $content;
    public $url;
    public $errors;
    public $htmlDom;
    
    public function __construct() {
        $this->headers=null;
        $this->content=null;
        $this->url=null;
        $this->htmlDom=new simple_html_dom();
    }
    public function setHeaders($headers){
        $this->headers=$headers;
    }
    public function setContent($content){
        $this->content=$content;
        if(isset($content))
            $this->htmlDom->load($content);
        else
            $this->htmlDom->load("");
    }
    public function getElementsWithSelector($selector){
        if($this->checkForErrors())
            return false;
        return $this->htmlDom->find($selector);
    }
    
    public function getTitle(){
       if($this->checkForErrors())
            return false;
        $title=$this->htmlDom->find("title");
        if(isset($title))
            return $title[0]->innertext;
    }
    public function getAnchors(){
       if($this->checkForErrors())
            return false;
        return $this->htmlDom->find("a[href]");
    }
    
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
    public function getImages(){
        if($this->checkForErrors())
            return false;
        return $this->htmlDom->find("img");
    }
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
    public function getElementWithId($id){
        if($this->checkForErrors())
            return false;
        return $this->htmlDom->find("#".$id);
    }
    public function getElementsWithClass($class){
        if($this->checkForErrors())
            return false;
        return $this->htmlDom->find(".".$class);
    }
    public function getElementsWithTag($tag){
        if($this->checkForErrors())
            return false;
        return $this->htmlDom->find($tag);
    }
    
    private function errorsOccured(){
        if(!isset($this->errors))
            return false;
        else
            return true;
    }
    public function getErrors(){
        return $this->errors;
    }
    public function printErrors(){
        var_dump($this->errors);
        return;
    }
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