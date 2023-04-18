<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();
print_r($_POST);
//code for add courses
if (isset($_POST['action'])) {
    print_r("action");
    $rid = $_POST['rid'];
    $query = "UPDATE allotment SET updationDate=?, ";
    $timestamp = date("Y-m-d H:i:s");
    if ($_POST['action'] == "Approve") {
        print_r("if");
        $query .= "status=1 WHERE id=?";
    } else {
        print_r("else");
        $query .= "status=2 WHERE id=?";
    }
    $stmt = $mysqli->prepare($query);
    $rc = $stmt->bind_param('si', $timestamp, $rid);
    $stmt->execute();

//    $coursecode = $_POST['cc'];
//    $coursesn = $_POST['cns'];
//    $coursefn = $_POST['cnf'];
//    $id = $_GET['id'];
//    $query = "update courses set course_code=?,course_sn=?,course_fn=? where id=?";
//    $stmt = $mysqli->prepare($query);
//    $rc = $stmt->bind_param('sssi', $coursecode, $coursesn, $coursefn, $id);
//    $stmt->execute();
    echo"<script>alert('Course has been Updated successfully');</script>";
}
?>
<!doctype html>
<html lang="en" class="no-js">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="theme-color" content="#3e454c">
        <title>Request Action</title>
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">>
        <link rel="stylesheet" href="css/bootstrap-social.css">
        <link rel="stylesheet" href="css/bootstrap-select.css">
        <link rel="stylesheet" href="css/fileinput.min.css">
        <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
        <link rel="stylesheet" href="css/style.css">
        <script type="text/javascript" src="js/jquery-1.11.3-jquery.min.js"></script>
        <script type="text/javascript" src="js/validation.min.js"></script>
    </head>
    <body>
        <?php include('includes/header.php'); ?>
        <div class="ts-main-content">
            <?php include('includes/sidebar.php'); ?>
            <div class="content-wrapper">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-md-12">

                            <h2 class="page-title">Request Action </h2>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">Request Action</div>
                                        <div class="panel-body">
                                            <form method="post" class="form-horizontal" action="">
                                                <?php
                                                $id = $_GET['id'];
                                                $ret = "SELECT * FROM allotment A, rooms R, userregistration U WHERE A.qid=R.qid AND A.eid=U.eid AND A.id=?";
                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->bind_param('i', $id);
                                                $stmt->execute(); //ok
                                                $res = $stmt->get_result();
//$cnt=1;
                                                while ($row = $res->fetch_object()) {
                                                    ?>
                                                    <div class="hr-dashed"></div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">Request ID </label>
                                                        <div class="col-sm-8">
                                                            <input type="text"  name="rid" value="<?php echo $id; ?>"  class="form-control" readonly> </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">Quarter No. </label>
                                                        <div class="col-sm-8">
                                                            <input type="text"  name="cc" value="<?php echo $row->q_no; ?>"  class="form-control" readonly> </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">PIS</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control" name="cns" id="cns" value="<?php echo $row->regNo; ?>" required="required" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">Name</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control" name="cnf" value="<?php echo $row->firstName . " " . $row->middleName . " " . $row->lastName; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">Applied Date</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control" name="ad" value="<?php echo $row->creationDate; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">Status</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" class="form-control" name="status" value="<?php
                                                            switch ($row->status) {
                                                                case 0: echo "Pending";
                                                                    break;
                                                                case 1: echo "Approved and Alloted";
                                                                    break;
                                                                case 2: echo "Reject";
                                                            }
                                                            ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <!--                                                    <div class="form-group">
                                                                                                            <label class="col-sm-2 control-label">Action</label>
                                                                                                            <div class="col-sm-8">
                                                                                                                <select name="aid" id="aid" class="form-control" required>
                                                                                                                    <option value="1">Approve and Allot</option>
                                                                                                                    <option value="2">Reject</option>
                                                                                                                </select>
                                                                                                            </div>
                                                                                                        </div>-->

                                                <?php } ?>
                                                <div class="col-sm-8 col-sm-offset-2">
                                                    <input class="btn btn-success" type="submit" name="action" value="Approve">
                                                    <input class="btn btn-danger" type="submit" name="action" value="Reject">
                                                </div>
                                            </form>
                                        </div>


                                    </div>
                                </div>


                            </div>




                        </div>
                    </div>

                </div>
            </div> 	


        </div>
    </div>
</div>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap-select.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>
<script src="js/Chart.min.js"></script>
<script src="js/fileinput.js"></script>
<script src="js/chartData.js"></script>
<script src="js/main.js"></script>

</script>
</body>

</html>