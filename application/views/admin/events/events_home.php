<div class="grid_9">
<div class="box">
<h2>Events</h2>
<div class="block">

<?
if($this->session->userdata('level') == 'executive'){
    echo anchor('admin/events/add','Add events');
echo "</br>";
    echo anchor('admin/events/cancel','Cancel Events');
}
echo "</br>";
    echo anchor('admin/events/entry','Enter fencers in events')
?>
</div>
</div>
</div>