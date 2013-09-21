<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tienn2t
 * Date: 9/20/13
 * Time: 5:52 PM
 * To change this template use File | Settings | File Templates.
 */
// Tạo liên kết đến các trang đã đánh số thứ tự
$total_pages = ceil($total/$limit);
$page = $start/$limit+1;
echo "<div align='center' id='paginator_id'>Chọn một trang<br />";

// Tạo liên kết đến trang trước trang đang xem
if($page > 1){
    $prev = ($page - 1);
    echo "<a num='".($prev*$limit)."' class='btn'><<Trang trước</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
    } else {
        echo "<a numb='".($i*$limit)."' class='btn'>$i</a>&nbsp;";
    }
}

// Tạo liên kết đến trang tiếp theo
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a num='".($next*$limit)."' class='btn'>Trang tiếp theo>></a>";
}
echo "</div>";
?>