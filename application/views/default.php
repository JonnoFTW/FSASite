<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
echo doctype('xhtml11');
 ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
	<title>Fencing South Australia :: <? echo $title; ?> </title>
		<? 
			echo link_tag('favicon.ico', 'shortcut icon', 'image/ico');
			echo "\n";
			echo link_tag("assets/css/reset.css")."\n";
			echo link_tag("assets/css/text.css")."\n";
			echo link_tag("assets/css/960.css")."\n";
			echo link_tag("assets/css/layout.css")."\n";
			echo link_tag("assets/css/nav.css")."\n";
			echo link_tag("assets/css/fsa.css")."\n";	
			echo link_tag("assets/css/ui-lightness/jquery-ui-1.8.16.custom.css")."\n";	
			echo "<!--[if IE 6]>".link_tag("assets/css/ie6.css")."<![endif]-->\n";
			echo "<!--[if IE 7]>".link_tag("assets/css/ie.css")."<![endif]-->\n";
		?>
    <style type="text/css">
    html, body {
    background: url('<? echo base_url();?>assets/images/tile.png') repeat !important
    }
    .grid_12 h1 {
        background: url('<? echo base_url();?>assets/images/bd2.png') no-repeat !important
    }
    </style>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<meta name="keywords" content="fencing, south australia, adelaide, competition, sport"/>
	<meta name="description" content="Fencing SA is the governing body for the sport of fencing in South Australia, and manages the competitions, rules and organisation of fencing in South Australia"/>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script type="text/javascript" src="<? echo base_url();?>assets/scripts/jquery-ui-1.8.14.custom.min.js"></script>
  </head>
<body>
<div class="container_12">
	<div class="grid_12" >
		<h1 id="branding" >
			<a href="home">
				<? echo img("assets/images/fsa.png") ?>
			</a>
		</h1>
	</div>
	<div class="clear"></div>
	<div class="grid_12">
		<ul class="nav main">

<? 

echo $menu;

  ?>
		</ul>
	</div>
<div class="clear"></div>

<div id="content">
<div class="grid_12">
	<h2 id="page-heading"><? echo $title; ?></h2>
</div>
<? echo $main_content; ?>
</div>

<div class="clear"></div>
<div class="grid_12" id="site_info">
				<div class="box">
					<p>FencingSA website created by <a href="http://2dev.net.au">2DEV INC</a>, all rights reserved. All content Copyright&copy; <? echo date("Y") ?> <?
                        $attrs = array(
                           'src'=>  'assets/images/codeigniter.png',
                           'alt'=> 'This site is powered by CodeIgniter',
                           'style'=> 'float:right;top: 50%;',
                           'title'=>'This site is powered by CodeIgniter'
                        );
                        echo img($attrs);

                        ?></p> 
				</div>
</div>
<div class="clear"></div>
</div>
</body>
</html>