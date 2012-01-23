<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="grid_9">
<div class="box">
<h2>Search results</h2>
<div class="block">
<? 
echo $query;
echo "</br>";
if(isset($events)) {
    // List events
    foreach($events as $v) {
        echo anchor("results/event/{$v['event_id']}","{$v['name']}");
    }
} 
elseif(isset($fencers)) {
    // List fencers
    foreach($fencers as $v) {
        echo anchor("results/user/{$v['uid']}","{$v['first_name']} {$v['last_name']}");
    }
}
?>
</div>
</div>
</div>