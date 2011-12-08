<div class="grid_9">
<div class="box">
<? echo heading("Administration",2); if($this->session->userdata('level') == 'club') { ?>
<div class="block">
Users: You can update the information for your club and its users. You can also
use the competition entry link to enter your club members into upcoming competitions.
<? } else {?>
<div class="block">
Update user info for all users using the link at the right. You can enter competition results or enter users into 
competitions using the other links.

The pages links will allow you to update the information on each page.
<? } ?>
</div>
</div>
</div>
