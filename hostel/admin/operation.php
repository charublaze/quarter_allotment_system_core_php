<?php

session_start();
$aid = $_SESSION['id'];
require_once("includes/config.php");

if (isset($_POST['apply'])) {
//$qid = $_POST['qid'];
    $eid = $_SESSION['id'];
    $status = 0;
    $j1 = json_encode($_SESSION);
    $j2 = json_encode($_POST);
    echo '<script>console.log(' . $j1 . ',' . $j2 . ')</script>';

    $query = "INSERT INTO allotment(eid,status) VALUES(?,?)";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('ii', $eid, $status);
    $stmt->execute();
    //echo"<script>alert('Application Submitted Successfully');</script>";
}

if (isset($_POST['allot'])) {
    print_r('allot');
    $ret1 = "SELECT * FROM rooms WHERE status=0 AND qid<>0";
    $stmt1 = $mysqli->prepare($ret1);
    $stmt1->execute(); //ok
    $res1 = $stmt1->get_result();
    $count1 = $res1->num_rows;
    if ($count1 > 0) {
        //echo json_encode($res1);
        //echo '<script>console.log(' . json_encode($res1) . ')</script>';
        $ret2 = "SELECT * FROM allotment WHERE status=0";
        $stmt2 = $mysqli->prepare($ret2);
        $stmt2->execute(); //ok
        $res2 = $stmt2->get_result();
        $count2 = $res2->num_rows;
        $mysqli->begin_transaction();
        while ($count1 > 0 && $count2 > 0) {
            $row1 = $res1->fetch_object();
            $row2 = $res2->fetch_object();
            //echo '<script>alert("' . $row1->qid . '->' . $row2->eid . '")</script>';
            $query = "UPDATE allotment SET qid=?, status = 1 WHERE id=?";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('ii', $row1->qid, $row2->id);
            $stmt->execute();

            $query = "UPDATE rooms SET status = 1 WHERE qid=?";
            $stmt = $mysqli->prepare($query);
            $rc = $stmt->bind_param('i', $row1->qid);
            $stmt->execute();

            $count1 = $count1 - 1;
            $count2 = $count2 - 1;
        }
        $mysqli->commit();
        echo json_encode(array('value'=>'msg'));
    } else {
        return 0;
        //echo '<script>alert("NO VAACANCY")</script>';
    }
}

if (isset($_POST['vacate'])) {
    $status = 0;
    $j1 = json_encode($_SESSION);
    $j2 = json_encode($_POST);
    $rid = $_POST['rid'];
    $qid = $_POST['qid'];
    print_r($rid);
    echo '<script>console.log(' . $j1 . ',' . $j2 . ')</script>';
    
    $mysqli->begin_transaction();
    $query = "UPDATE allotment SET status = 3 WHERE id=?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('i', $rid);
    $stmt->execute();
    
    $query = "UPDATE rooms SET status = 0 WHERE qid=?";
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('i', $qid);
    $stmt->execute();
    $mysqli->commit();
    //echo"<script>alert('Application Submitted Successfully');</script>";
}
?>
