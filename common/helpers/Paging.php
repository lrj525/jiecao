<?php 
namespace common\helpers;

class Paging{
	
/*
     * 
     * 对于翻页代码的处理
     * 
     * @param int    $page 		   当前页面
     * @param int    $page_total 总页数
     * @param string $url 	              链接
     * @param bool   $isIndex    当值为true时，对首页页码采用$cIndex的值
     * @param string $cIndex	  首页特殊处理的值
     * @param string $prefix 	   页码的前缀
     * @param string $suffix	   页码的后缀
     * @author xi
     * 
     */

    public static function make($page, $page_total, $url, $isIndex = false, $cIndex = '', $prefix = '', $suffix = '',$jump=true) 
    {
        if ($page_total == 1)
            return "";
        //间距
        $page_space = 2;

        $p_page = "";
        $n_page = "";

        //生成上一页下一页的页码
        $p_page = $page - 1;
        $n_page = $page + 1;
        if ($page == 1) {
            $p_page = "";
        }
        if ($page == $page_total) {
            $n_page = "";
        }

        //生成页码
        $center_array = array();
        $start = 0;
        $end = 0;
        if ($page > $page_space) {
            $start = $page - $page_space;
        } else {
            $start = 1;
        }
        if (($page + 2) >= $page_total) {
            $end = $page_total;
        } else {
            $end = $page + 2;
        }

        if (($end - $start) != 4)
            $end = $start + 4;
        if ($end > $page_total) {
            $end = $page_total;
            $start = $end - 4;
        }
        for ($r = $start; $r <= $end; $r++) {
            if ($r > 0 && $r <= $page_total)
                $center_array[] = $r;
        }

        if ($center_array[0] > 2)
            array_unshift($center_array, 1, "");
        else if ($center_array[0] == 2)
            array_unshift($center_array, 1);

        $last = end($center_array);
        if (($page_total - $last) > 1)
            array_push($center_array, "", $page_total);
        else if (($page_total - $last) == 1)
            array_push($center_array, $page_total);

        //生成页面翻页代码
        $p_page_html = empty($p_page) ? '' : '<a class="btn btn-default btn-sm" href="' . $url . self::groupPagename($p_page, $prefix, $suffix, $isIndex, $cIndex) . '">上一页</a> ';
        $n_page_html = empty($n_page) ? '' : '<a class="btn btn-default btn-sm" href="' . $url . self::groupPagename($n_page, $prefix, $suffix, $isIndex, $cIndex) . '">下一页</a> ';

        $page_html = $p_page_html;
        foreach ($center_array as $val) {
            if ($val == $page) {

                $page_html .= '<a class="btn btn-success btn-sm">' . $val . '</a> ';
            } else if (empty($val)) {
                $page_html .= "<span>...</span> ";
            } else {
                $page_html .= '<a class="btn btn-sm btn-default" href="' . $url . self::groupPagename($val, $prefix, $suffix, $isIndex, $cIndex) . '">' . $val . '</a> ';
            }
        }
        $page_html .= $n_page_html;
        $jumpPage= '';
        if($jump){
            $jumpPage = '<form action="?" style="display:inline;">第 ';
            foreach ($_GET as $key=>$val)
            {
                if($key!='p'){
                    $jumpPage.= '<input type="hidden" name="'.$key.'" value="'.$val.'"/>';
                }
            }
            $jumpPage.= '<input type="text" style="text-align:center;" class="go-page" name="p" value="'.$page.'"> 页 <button class="circle btn-success">GO</button></form>';
        }
        
        $str = "当前第<strong>$page</strong>/$page_total 页 ";
        return $str.$page_html.$jumpPage;
    }
	/*
     * 生成对应的链接参数
     * @param bool   $isIndex    当值为true时，对首页页码采用$cIndex的值
     * @param string $cIndex	  首页特殊处理的值
     * @param string $prefix 	   页码的前缀
     * @param string $suffix	   页码的后缀
     * @author xi
     */

    public static function groupPagename($page, $prefix, $suffix, $isIndex, $cIndex) 
    {
        if ($page == 1 && $isIndex == true)
            return $cIndex;
        return $prefix . $page . $suffix;
    }
    
}
?>