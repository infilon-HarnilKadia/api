<?php
if (count($data)) {
    foreach ($data as $r) {
        ?><div class="dropdown-list"><div class="content-wrapper"><small class="name"><?php echo $r['msg']; ?></small><small class="content-text"><i><?php echo timeago($r['l_date']); ?></i></small></div><a href="#."><i class="mdi mdi-close"></i></a></div>
    <?php }
}
?>