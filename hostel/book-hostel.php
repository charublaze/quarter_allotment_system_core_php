<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();
//code for registration
if (isset($_POST['submit'])) {
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
    echo"<script>alert('Application Submitted Successfully');</script>";
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
        <title>Student Hostel Registration</title>
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
        <script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>
        <script>
            function getSeater(val) {
                $.ajax({
                    type: "POST",
                    url: "get_seater.php",
                    data: 'roomid=' + val,
                    success: function (data) {
                        //alert(data);
                        $('#seater').val(data);
                    }
                });

                $.ajax({
                    type: "POST",
                    url: "get_seater.php",
                    data: 'rid=' + val,
                    success: function (data) {
                        //alert(data);
                        $('#fpm').val(data);
                    }
                });
            }
        </script>

    </head>
    <body>
        <?php include('includes/header.php'); ?>
        <div class="ts-main-content">
            <?php include('includes/sidebar.php'); ?>
            <div class="content-wrapper">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-md-12">

                            <h2 class="page-title">Registration </h2>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">Fill all Info</div>
                                        <div class="panel-body">
                                            <form method="post" action="" class="form-horizontal">
                                                <?php
                                                $uid = $_SESSION['id'];
                                                $stmt = $mysqli->prepare("SELECT * FROM allotment WHERE eid=? AND status IN (0,1)");
                                                $stmt->bind_param('i', $uid);
                                                $stmt->execute();
//$stmt->bind_result($email);
                                                $rs = $stmt->fetch();
                                                $stmt->close();
                                                if ($rs) {
                                                    ?>
                                                    <h3 style="color: red" align="center">You have already applied for Quarter</h3>
                                                    <div align="center">
                                                        <div class="col-md-4">&nbsp;</div>
                                                        <div class="col-md-4">
                                                            <div class="panel panel-default">
                                                                <div class="panel-body bk-success text-light">
                                                                    <div class="stat-panel text-center">

                                                                        <div class="stat-panel-number h1 ">Application Status</div>

                                                                    </div>
                                                                </div>
                                                                <a href="room-details.php" class="block-anchor panel-footer text-center">See All &nbsp; <i class="fa fa-arrow-right"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                } else {
                                                    ?>			
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label"><h4 style="color: green" align="left">Room Related info </h4> </label>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">Total Available Quarters </label>
                                                        <div class="col-sm-1 control-label">
                                                            <!--<select name="room" id="room"class="form-control"  onChange="getSeater(this.value);" onBlur="checkAvailability()" required>--> 
    <!--                                                            <select name="qid" id="qid" class="form-control" required> 
                                                                <option value="">Select Quarter</option>-->
                                                            <?php
                                                            $query = "SELECT * FROM rooms R where R.qid<>0 AND R.qid NOT IN (SELECT A.qid FROM allotment A where R.qid=A.qid AND A.status=1)";
                                                            $stmt2 = $mysqli->prepare($query);
                                                            $stmt2->execute();
                                                            $res = $stmt2->get_result();
                                                            echo $res->num_rows;
//                                                                while ($row = $res->fetch_object()) {
                                                            ?>
                                                        </div>
                                                        <div class="col-sm-2 col-sm-offset-3">
                                                            <button class="btn btn-primary" onClick="apply()">Apply</button>
                                                            <!--<input type="submit" name="submit" value="Apply" class="btn btn-primary">-->
                                                        </div>
                                                    </div>


                                                </form>
                                            <?php } ?>

                                        </div>
                                        <?php print_r($_SESSION) ?>
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
</body>
<script type="text/javascript">
            $(document).ready(function () {
                $('input[type="checkbox"]').click(function () {
                    if ($(this).prop("checked") == true) {
                        $('#paddress').val($('#address').val());
                        $('#pcity').val($('#city').val());
                        $('#pstate').val($('#state').val());
                        $('#ppincode').val($('#pincode').val());
                    }

                });
            });
</script>
<script>
    function checkAvailability() {
        $("#loaderIcon").show();
        jQuery.ajax({
            url: "check_availability.php",
            data: 'roomno=' + $("#room").val(),
            type: "POST",
            success: function (data) {
                $("#room-availability-status").html(data);
                $("#loaderIcon").hide();
            },
            error: function () {}
        });
    }

    function apply() {
        jQuery.ajax({
            url: "admin/operation.php",
            data: { "apply": "1", "allot": "1"},
            type: "POST",
            success: function (data) {
                alert("Application Submitted Successfully");
            },
            error: function () {}
        });
    }
</script>


<script type="text/javascript">

    $(document).ready(function () {
        $('#duration').keyup(function () {
            var fetch_dbid = $(this).val();
            $.ajax({
                type: 'POST',
                url: "ins-amt.php?action=userid",
                data: {userinfo: fetch_dbid},
                success: function (data) {
                    $('.result').val(data);
                }
            });


        })
    });
</script>

</html>