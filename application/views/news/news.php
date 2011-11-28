<div class='grid_7'><div class='box articles'>
<?
foreach($news_item as $i){
	echo "<h2>".anchor("news/item/{$i['newsid']}",$i['title'],"id=toggle-articles")."</h2>";
	echo "<div id='articles' href='' class='block'>";
	echo "<blockquote><p>{$i['message']}</p><p class='cite'><cite>Posted by {$i['first_name']} {$i['last_name']} on {$i['posted']}</cite></p></blockquote>";
	echo '</div>';
}
?>
</div>