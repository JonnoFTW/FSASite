<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="grid_9">
<div class="box">
<? echo heading("Results summary for: {$fencer['first_name']} {$fencer['last_name']}",2); ?>
<div class="block">
<? 
echo $events;
?>
</div>
</div>
</div>