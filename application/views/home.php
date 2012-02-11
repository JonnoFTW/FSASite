<? /*
<style type="text/css">
.kwicks {  
    list-style: none;  
    position: relative;  
    margin: 0;  
    padding: 0; 
    width: 100%;
} 
.kwicks li{  
    float: left;  
    width: 125px;  
    height: 100%;  
    margin-right: 5px;  
    
}
</style>
<script type="text/javascript" src="<?php echo base_url();?>scripts/jquery.kwicks-1.5.1.pack.js" ></script>
<script type="text/javascript">
    $().ready(function() {
        $('.kwicks').kwicks({
            max: 205,
            spacing:  5
         });
    });
</script>
<div class="grid_12">
<div class="box">
<? echo heading('Gallery',2) ?>
<div class="block">
<?
    // Images should probably be drawn randomly from the gallery
    $imgs = array(
        img('assets/images/sponsor1.png'),
    );
    
    echo ul($imgs,array('class'=>'kwicks'));
?>
</div>
</div>
</div>
*/
?>
<style type="text/css">
td:first-child {font-weight: bold;}
.table-head a {
    color: white;
    font-weight:normal;
}
</style>
<div class="grid_3">
<!-- begin results block -->
				<div class="box">
                    <?
                        echo heading(anchor('results','Recent Results'),2);
                    ?>
					<div class="block" id="tables">
                    <?
                        foreach($results as $v) {
                            echo $v;
                        }
                    ?>
                    </div>
				</div>
<!-- end results block -->
<!-- begin sponsor block -->
				<div class="box">
					<h2>
						<a href="#" >Sponsors</a>
					</h2>
					<div class="block" >
						<p>FencingSA is grateful to the following sponsors:</p>
                        <?
                           // Should probably store in a db table so that they can be readily updated
                            $sponsors = array(
                                'Scotch'=>array('link'=>'http://www.scotch.sa.edu.au/',
                                                'src'=>'assets/images/sponsor1.png'),
                                'GSAORS'=>array('link'=>'http://www.recsport.sa.gov.au/',
                                                'src'=>'assets/images/sponsor2.png'),
                                'Be Active'=>array('link'=>'http://www.beactive.com.au/',
                                                'src'=>'assets/images/sponsor3.png')
                            );
                            foreach($sponsors as $key => $i){
                                echo anchor($i['link'],img(array(
                                    'src'=>$i['src'],
                                    'title'=>$key,
                                    'style'=>'display:block;margin:auto;',
                                    'alt'=>$key)))."\n";
                            }
                        ?>
					</div>
				</div>
<!-- end sponsor block -->
            </div>
			<div class="grid_5">
				<div class="box">
					<h2>
						<a href="#" id="toggle-blockquote">New to fencing?</a>
					</h2>
					<div class="block" id="blockquote">
						<blockquote>
							<p>Welcome to FencingSA, the website for all fencing in South Australia. If you are curious about about fencing then find some information <a href="documents/info/starting.php">here</a> or read the news below to find out what is happening with your local fencing sports scene.</p>
							<p class="cite">
								<cite>FencingSA Executive</cite>
							</p>
						</blockquote>
					</div>
				</div>				<div class="box">
					<h2>
						<a href="#">General Information</a>
					</h2>
					<div class="block">
						<div>
							<div class="element atStart">
                                    <? echo $message['message']; ?>
							</div>
						</div>
                        <p class="cite">
                        <? echo "{$message['first_name']} {$message['last_name']}, updated {$message['updated']}" ;?>
                        </p>
					</div>
				</div>
				<div class="box menu">
					<?
                    echo heading(anchor('calendar','Upcoming Events'),2);
                    ?>
                <div class="block" id="section-menu">
						<ul class="section menu">
							<li>
                                <?  
                                    $upcoming = array();
                                    foreach($events as $i) {
                                        $d = explode(" ",$i['date']);
                                        $upcoming[$d[0]][] = anchor('results/event/'.$i['event_id'],$i['name']);  
                                    }
                                    foreach($upcoming as $k=>$v) {
                                        echo "<a class=\"menuitem\">{$k}</a>";
                                        echo ul($v,array("class"=>'submenu'));
                                    }
                                ?>
							</li>
                        </ul>
					</div>
                </div>
			</div>
			<div class="grid_4">
				<!--<div class="box">
					<h2>
						<a href="#" id="toggle-search">Search</a>
					</h2>
					<div class="block" id="search">
						<form method="get" action="" class="search">
							<p>
								<input class="search text" name="value" type="text" />
								<input class="search button" value="Search" type="submit" />
							</p>
						</form>
					</div> 
				</div>-->
				<div class="box articles">
					<?
						echo heading(anchor('news','Recent News'),2);
					?>
					<div class="block" id="articles">
                        <? 
                            foreach($news as $k=>$i){
                                switch($k) {
                                    case 0:
                                        echo "<div class=\"first article\">"; break;
                                    case 1:
                                        echo "<div class=\"article\">"; break;
                                    case 2:
                                        echo "<div class=\"last article\">"; break;
                                }
                                echo heading(anchor('news/item/'.$i['newsid'],$i['title']),3);
                                echo heading($i['posted'],4);
                                echo "<p class=\"meta\">Reported by {$i['first_name']} {$i['last_name']}</p>";
                                echo "<p>";
                              //  echo ellipsize(auto_link($i['message']),200,1);
                             //   $i['message'] = auto_link($i['message']);
                                echo auto_link(ellipsize($i['message'],200,1));
                                echo "</p>";
                                echo "</div>";
                            }
                        ?>
					</div>
				</div>
			</div>
