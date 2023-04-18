<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();
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
        <title>Room Details</title>
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
        <link rel="stylesheet" href="css/bootstrap-social.css">
        <link rel="stylesheet" href="css/bootstrap-select.css">
        <link rel="stylesheet" href="css/fileinput.min.css">
        <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">

        <script language="javascript" type="text/javascript">
            var popUpWin = 0;
            function popUpWindow(URLStr, left, top, width, height)
            {
                if (popUpWin)
                {
                    if (!popUpWin.closed)
                        popUpWin.close();
                }
                popUpWin = open(URLStr, 'popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=yes,width=' + 510 + ',height=' + 430 + ',left=' + left + ', top=' + top + ',screenX=' + left + ',screenY=' + top + '');
            }



        </script>

    </head>

    <body>
        <?php include('includes/header.php'); ?>

        <div class="ts-main-content">
            <?php include('includes/sidebar.php'); ?>
            <div class="content-wrapper">
                <div class="container-fluid">
                    <div class="row" id="print">


                        <div class="col-md-12">
                            <h2 class="page-title" style="margin-top:3%">Rooms Details</h2>
                            <div class="panel panel-default">
                                <div class="panel-heading">All Room Details</div>
                                <div class="panel-body">
                                    <table id="zctb" class="table table-bordered " cellspacing="0" width="100%" border="1">

                                        <span style="float:left" ><i class="fa fa-print fa-2x" aria-hidden="true" OnClick="CallPrint(this.value)" style="cursor:pointer" title="Print the Report"></i></span>			
                                        <tr>
                                            <td colspan="6" style="text-align:center; color:blue"><h3>Application Status</h3></td>
                                        </tr>
                                        <tbody>
                                            <?php
                                            $rid = 0;
                                            $eid = $_SESSION['id'];
                                            $ret = "SELECT * FROM rooms R, allotment A WHERE A.qid=R.qid AND A.eid=? ORDER BY A.updationDate DESC, A.creationDate DESC";
                                            $stmt = $mysqli->prepare($ret);
                                            $stmt->bind_param('i', $eid);
                                            $stmt->execute();
                                            $res = $stmt->get_result();
                                            echo "<pre>";
                                            print_r($res);
                                            echo "</pre>";
                                            $cnt = 1;
                                            while ($row = $res->fetch_object()) {
                                                echo "<pre>";
                                                print_r($row);
                                                echo "</pre>";
                                                $rid = $row->id;
                                                $qid = $row->qid;
                                                ?>
                                                <tr>
                                                    <th colspan="2" style="color:blueviolet">Request # <?php echo $cnt ?></th>
                                                    <!--<td></td>-->
                                                </tr>
                                                <tr>
                                                    <th>Apply Date :</th>
                                                    <td><?php echo $row->creationDate; ?></td>
                                                </tr>

                                                <tr>
                                                    <td><b>Quarter no :</b></td>
                                                    <td><?php echo $row->q_no; ?></td>
                                                </tr>

                                                <tr>
                                                    <td><b>Status</b></td>
                                                    <td>
                                                        <?php
                                                        switch ($row->status) {
                                                            case 0: echo "Pending";
                                                                break;
                                                            case 1: echo "Approved and Alloted";
                                                                break;
                                                            case 2: echo "Reject";
                                                                break;
                                                            case 3: echo "Vacated";
                                                                break;
                                                        }
                                                        ?>

                                                        <?php
                                                        if ($row->status == 1) {
                                                            ?>
                                                        &emsp;&emsp;
                                                            <button class="btn btn-primary btn-sm" onClick="vacate(<?php echo $rid . "," . $qid ?>)">Vacate Quarter</button>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                $cnt = $cnt + 1;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <?php
                                    if (0) {//($row->status == 1) {
                                        ?>
                                        <div class="col-sm-8 col-sm-offset-5">
                                            <button class="btn btn-primary" onClick="vacate(<?php echo $rid . "," . $qid ?>)">Vacate Quarter</button>
                                            <!--<input class="btn btn-primary" type="submit" name="submit" value="Vacate Quarter">-->
                                        </div>
                                        <?php
                                    }
//}
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading Scripts -->
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap-select.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.dataTables.min.js"></script>
        <script src="js/dataTables.bootstrap.min.js"></script>
        <script src="js/Chart.min.js"></script>
        <script src="js/fileinput.js"></script>
        <script src="js/chartData.js"></script>
        <script src="js/main.js"></script>
        <script>
                                            $(function () {
                                                $("[data-toggle=tooltip]").tooltip();
                                            });

                                            function CallPrint(strid) {
                                                var prtContent = document.getElementById("print");
                                                var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
                                                WinPrint.document.write(prtContent.innerHTML);
                                                WinPrint.document.close();
                                                WinPrint.focus();
                                                WinPrint.print();
                                                WinPrint.close();
                                            }

                                            function vacate(rid, qid) {
                                                alert("Vacate");
                                                jQuery.ajax({
                                                    url: "admin/operation.php",
                                                    data: {"rid": rid, "qid": qid, "vacate": "1"},
                                                    type: "POST",
                                                    success: function (data) {
                                                        alert("Quarter Vacated Successfully");
                                                    },
                                                    error: function () {
                                                        alert("Error");
                                                    }
                                                });
                                            }
        </script>
    </body>

</html>
