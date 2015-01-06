<?php
require_once '../library/DCrawler.php';

$crawler=new DCrawler();
$crawler->crawlWebsites(array("http://klix.ba"));

$pages=$crawler->getResults();

foreach($pages as $p){
    //var_dump($p->htmlDom);exit;
    $rezultatPretrage=$p->getImageURLs();
	foreach($rezultatPretrage as $key=>$values){
    ?> 
	<img src="<?php echo $values;?>" >
	<?php 
	}
}


