<?php
/**
 * User: asuslx
 * Date: 25.02.13
 * Time: 14:18
 */
 
class F_Tools_Pager {

    public static function format($count, $size, $page, $win = 5, $sizes = array(50, 100, 250, 500)) {
        if(!$count) return false;

        $total = ceil($count / $size);

        if($page > $total-1) $page = $total -1;
        if($page < 0) $page = 0;

        $windowStart = $page - ceil($win / 2) + 1;
	    if($windowStart < 0) $windowStart = 0;
	    $windowEnd = $windowStart + $win - 1;

	   	if($windowEnd > $total - 1) {
	   		$windowStart -= $windowEnd - $total + 1;
	   	 	$windowEnd = $total - 1;
	   	}
	    if($windowStart < 0) $windowStart = 0;
        if($windowEnd < 0) $windowEnd = 0;

	   	$pages = range($windowStart, $windowEnd);

        return array (
            'total_rows' => $count,
            'window' => $pages,
            'page' => $page,
            'total'=> $total,
            'size' => $size,
            'sizes' => $sizes
        );
    }

}