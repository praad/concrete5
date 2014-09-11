<? defined('C5_EXECUTE') or die("Access Denied."); ?>

<div class="ccm-block-rss-displayer-wrapper">

<? if( strlen($title)>0 ){ ?>
    <div class="ccm-block-rss-displayer-header">
    	<h5><?=$title?></h5>
    </div>
<? } ?>

<? 
$rssObj=$controller;
$textHelper = Loader::helper("text");

if( strlen($errorMsg)>0 ){
	echo $errorMsg;
}else{

	foreach($posts as $itemNumber=>$item) { 
	
		if( intval($itemNumber) >= intval($rssObj->itemsToDisplay) ) break;
		?>
		
		<div class="ccm-block-rss-displayer-item">
			<div class="ccm-block-rss-displayer-item-title">
				<a href="<?= $item->getLink(); ?>" <? if($rssObj->launchInNewWindow) echo 'target="_blank"' ?> >
					<?= $item->getTitle(); ?>
				</a>
			</div>
			<div class="ccm-block-rss-displayer-item-date"><?= h($this->controller->formatDateTime($item->getDateCreated())); ?></div>
			<div class="ccm-block-rss-displayer-item-summary">
				<?
				if( $rssObj->showSummary ){
					echo $textHelper->shortText( strip_tags($item->getDescription()) );
				}
				?>
			</div>
		</div>
	
<?  }  
}
?>
</div>