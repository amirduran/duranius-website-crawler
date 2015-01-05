duranius-website-crawler
========================

#How to install

If you want to install this library then, follow this procedure

##Step 1

Download the library and extract the files from the `library` folder

##Step 2

Move the following files into your project folder

```
DCrawler.php - Main file

DPage.php
DRequest.php
simple_html_dom.php
```

##Step 3

Import `DCrawler.php` into your project using `require_once` statement:

```
require_once("DCrawler.php");
```


#How to use library

##Example 1

To instatiate DCrowler object you should do this:

```
require_once("DCrawler.php");
$crawler=new DCrawler();
$crawler->crawlWebsites(array("http://www.bbc.com/"));//To crawl bbc.com only
$pages=$crawler->getResults();
```

Your results are available in the `$pages` object.


##Example 2

Let's try to crawl multiple websites, and extract images only:


```
$crawler=new DCrawler();
$crawler->crawlWebsites(array("http://www.bbc.com/", "http://www.yahoo.com","http://sports.yahoo.com/"));//To crawl multiple websites
```

Now lets get crawled pages to the $pages object

```
$pages=$crawler->getResults();//Return results to the $pages object
```

Now let's go through every crawled page, and extract images:

```
foreach($pages as $p){
    $imageURL=$p->getImageURLs();
	foreach($imageURL as $key=>$values){
    ?> 
	<img src="<?php echo $values;?>" >
	<?php 
	}
}
```


