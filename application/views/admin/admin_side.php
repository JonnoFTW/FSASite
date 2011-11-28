<div class="grid_3">
<div class="box">
   <h2>Manage</h2>
    <h3><? echo anchor('admin/user','Users');?></h3>
    <ul>
        <? /* foreach($users as $i){
            echo "<li>".anchor('admin/user/'.$i['uid'],$i['first_name']." ".$i['last_name'])."</li>";
        }*/
        ?>
    </ul>
    <h3><? echo anchor('admin/article','Pages'); ?></h3>
    <ul>
     <? foreach($pages as $i){echo "<li>".anchor('admin/article/'.$i['title'],$i['title'])."</li>";} ?>
     <li><? echo anchor('admin/resources',"Resources"); ?></li>
     </ul>
     
     <? /*
    <h3><? echo anchor('admin/gallery','Gallery'); ?></h3>
    <ul>
     <? foreach($galleries as $i){echo "<li>".anchor('admin/gallery/'.$i['gid'],$i['title'])."</li>";} ?>
     </ul>
     */ ?>
</div>
</div>