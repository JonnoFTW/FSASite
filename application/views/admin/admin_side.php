<div class="grid_3">
<div class="box">
<?
    echo heading('Manage',2);
    echo "<div id=\"list-items\" class=\"block\">";
    echo heading(anchor('admin/user','Users'),5);
    $items = array(
        anchor('admin/user/filter/all','All'),
        anchor('admin/user/filter/licensed','Licensed'),
        anchor('admin/user/filter/unlicensed','Unlicensed'),
        anchor('admin/user/filter/clubs','Clubs')
    );
    echo ul($items,array('class'=>'menu'));
    if($this->session->userdata('level') == 'executive') {
        echo anchor('admin/licenses','Manage licenses');
        echo heading(anchor('admin/article','Pages'),5);
        $pages = array(
            anchor('admin/page/home','Home'),
            anchor('admin/page/news','News'),
            "Calendar"=>array(
                anchor('admin/page/calendar','Add events'),
                anchor('calendar','Update events')
             ),
            anchor('admin/page/results','Results'),
            anchor('admin/page/forms','Forms and Resources'),
            anchor('admin/rules/','Rules')
        );
        echo ul($pages,array('class'=>'menu'));
        echo heading(anchor('admin/result_entry','Results Entry'),5);
    }
    echo heading(anchor('admin/comp_entry','Competition Entry'),5);
    echo "</div>";
     ?>
</div>
</div>