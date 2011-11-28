<? echo "<?xml version=\"1.0\"?>"; ?>
<rss version="2.0">
	<channel>
		<title>Fencing SA News and Results</title>
		<link>http://fencingsa.org.au/</link>
		<description>Newsfeed of the latest news and competition results</description>
		<language>en-us</language>
		<pubDate><? echo date() ?></pubDate>
		<generator>2DEV RSS Generator</generator>
		<webMaster>webmaster@fencingsa.org.au</webMaster>
	<?php //Foreach news item in the last 10, ordered by date
		// Competition results as well

		foreach($news as $i){
			echo "<item>
				<title>{$i['title']}</title>
				<link>http://fencingsa.org.au/news/item{$i['id']}</link>
				<description>{$i['description']}</description>
				<pubDate>{$i['date']}</pubDate>
				<guid>http://fencingsa.org.au/news/item{$i['id']}</guid>
			</item>	";
		}
	
	?>
    </channel>
</rss>