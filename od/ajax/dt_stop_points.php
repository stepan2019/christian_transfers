<?php
  include("../dbconfig.php");
  header('Content-Type: application/json');
/*

*/

$per_page = (!empty($_GET['length'])) ? intval($_GET['length']) : 10;
$from     = (!empty($_GET['start']))  ? intval($_GET['start'])  : 0;
$draw     = (!empty($_GET['draw']))   ? intval($_GET['draw'])   : 1;
$id       = (!empty($_GET['id']))     ? intval($_GET['id'])     : 1;
if (!empty($_GET['filter'])) $filter = $_GET['filter']; else $filter = "";

$orderColumn = "tfl_stop_points.name"; $orderDir = "asc";  //default

if (!empty($_GET['order'])) {
    $getOrd = $_GET['order'][0];
    $getOrdColumn = $getOrd['column']; // 2
    $getOrdDir = $getOrd['dir']; // asc, desc
    if (!empty($getOrdDir))$orderDir = $getOrdDir;

    if (!empty($getOrdColumn)) {
        switch ($getOrdColumn) {
        case 0:
            $orderColumn = "tfl_stop_points.code";
            break;
        case 1:
            $orderColumn = "tfl_stop_points.name";
            break;
        }

    }

}

$where = " WHERE 1=1 ";

if (!empty($_GET['search'])) {
    $searchValue = $_GET['search']['value'];
    if (!empty($searchValue)) {
        $where .= " AND (tfl_stop_points.code = '$searchValue' OR tfl_stop_points.name like '%$searchValue%') " ;
    }
} // get[search]


    $q = "SELECT tfl_stop_points.* FROM tfl_stop_points $where order by $orderColumn $orderDir LIMIT $from,$per_page";
//echo $q; die();

    $rows=$db->get_results($q);
    if (!empty($rows)) {
        $output = array();
        foreach ($rows as $row) {

            $slug = slug($row->name);
            $map = "<a href=\"/od/stop-point/$row->code/$slug\" class=\"btn btn-primary btn-sm\"><i class=\"fas fa-bus-alt\"></i></a>" ;

            $lines = "";
            $rows4 = $db->get_results("select * from tfl_station_lines WHERE station_code = '$row->code' order by line_tid asc ");

            if (!empty($rows4)) {
                foreach ($rows4 as $row4) {
                    $lines .= "$row4->line_tid, ";
                }
            }

            $stopType = stopTypePrint($row->stopType);

            $output[] = array('code'=> $row->code,'name'=>$row->name, 'type' => $stopType, 'lines' => $lines , 'map' => $map);
        }
        $recordsTotal = $db->get_var("SELECT count(id) FROM tfl_stop_points $where");
        $recordsFiltered = $recordsTotal;
        echo json_encode(array('draw' => $draw,'recordsTotal' => $recordsTotal,'recordsFiltered' => $recordsFiltered, 'data' => $output));
    } else {
        echo json_encode(array('draw' => $draw,'recordsTotal' => 0,'recordsFiltered' => 0, 'data' => array()));
    }


?>