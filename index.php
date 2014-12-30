<?php
require_once './DCrawler.php';

$crawler=new DCrawler();
$crawler->crawlWebsites(array("http://www.klix.ba/vijesti/crna-hronika/ilijas-jaguar-se-zapalio-na-autoputu/141224135#5","www.avaz.ba","www.mojkonjic.com"));

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


