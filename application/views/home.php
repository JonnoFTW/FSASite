<div class="grid_3">
<!-- begin results block -->
				<div class="box">
					<h2>
						<a href="#" id="toggle-tables">Recent Results</a>
					</h2>
					<div class="block" id="tables">
						<table>
							<colgroup>
								<col class="colA" />
								<col class="colB" />
							</colgroup>
							<thead>
								<tr>
									<th colspan="3" class="table-head">3/07/2011 Open Mens Foil</th>
								</tr>
								<tr>
									<th>Position</th>
									<th>Name</th>
								</tr>
							</thead>
							<tbody>
								<tr class="odd">
									<th>1st</th>
									<td>Jahan Penny-Dimri</td>
								</tr>
								<tr>
									<th>2nd</th>
									<td>Michael Dzodzos</td>
								</tr>
								<tr class="odd">
									<th>3rd</th>
									<td>Louis Ritchie</td>
								</tr>
								<tr>
									<th>3rd</th>
									<td>Matteo Barchiesi</td>
								</tr>
							</tbody>
						</table>
						<table>
							<colgroup>
								<col class="colA" />
								<col class="colB" />
							</colgroup>
							<thead>
								<tr>
									<th colspan="3" class="table-head">3/07/2011 Open Mens Foil</th>
								</tr>
								<tr>
									<th>Position</th>
									<th>Name</th>
								</tr>
							</thead>
							<tbody>
								<tr class="odd">
									<th>1st</th>
									<td>Jahan Penny-Dimri</td>
								</tr>
								<tr>
									<th>2nd</th>
									<td>Michael Dzodzos</td>
								</tr>
								<tr class="odd">
									<th>3rd</th>
									<td>Louis Ritchie</td>
								</tr>
								<tr>
									<th>3rd</th>
									<td>Matteo Barchiesi</td>
								</tr>
							</tbody>
						</table>
						<table>
							<colgroup>
								<col class="colA" />
								<col class="colB" />
							</colgroup>
							<thead>
								<tr>
									<th colspan="3" class="table-head">3/07/2011 Open Mens Foil</th>
								</tr>
								<tr>
									<th>Position</th>
									<th>Name</th>
								</tr>
							</thead>
							<tbody>
								<tr class="odd">
									<th>1st</th>
									<td>Jahan Penny-Dimri</td>
								</tr>
								<tr>
									<th>2nd</th>
									<td>Michael Dzodzos</td>
								</tr>
								<tr class="odd">
									<th>3rd</th>
									<td>Louis Ritchie</td>
								</tr>
								<tr>
									<th>3rd</th>
									<td>Matteo Barchiesi</td>
								</tr>
							</tbody>
						</table>
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
                            
                            foreach(array('Scotch','GSAORS','Be Active') as $key => $i){
                                echo img(array(
                                    'src'=>'assets/images/sponsor'.($key+1).".png",
                                    'title'=>$i,
                                    'style'=>'margin-left:auto;margin-right:auto;',
                                    'alt'=>$i))."\n";
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
								<cite>FencingSA Committee</cite>
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
								<h4>AFF Development Guidelines for Cadet &amp; Junior Fencers</h4>
								<p>The AFF have published guidelines for cadet &amp; junior fencers to assist with development 
of their training programs. The document is recommended reading for cadet &amp; junior fencers who are aiming for national representation and is available 
<a href="http://ausfencing.org/files/%5B221%5D%20AFF%20Cadet%20and%20Junior%20Development%20Guidelines%20(Dec08).pdf">here</a>.
</p>
							</div>
							<div class="element atStart">
								<h4>AFF Member Protection Policy</h4>
								<p>The AFF have published a member protection policy which applies to all associated individuals 
(coaches, athletes, referees, registered fencers, parents, spectators, etc) and is available
<a href="http://ausfencing.org/home/attachments/027_AFF%20Member%20Protection%20Policy%20(May08).pdf">here</a>
</p>
							</div>
						</div>
					</div>
				</div>
				<div class="box menu">
					<h2>
						<a href="#" id="toggle-section-menu">Upcoming Events</a>
					</h2>
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
				<div class="box">
					<h2>
						<a href="#" id="toggle-search">Search</a>
					</h2>
				<!--	<div class="block" id="search">
						<form method="get" action="" class="search">
							<p>
								<input class="search text" name="value" type="text" />
								<input class="search button" value="Search" type="submit" />
							</p>
						</form>
					</div> -->
				</div>
				<div class="box articles">
					<h2>
						<a href="#" id="toggle-articles">Latest News</a>
					</h2>
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