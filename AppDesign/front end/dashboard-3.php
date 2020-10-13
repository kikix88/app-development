<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
    .btn-block+.btn-block {
        margin-top: 0;
    }
    </style>
</head>
<body>
    <div class="container">
    <br><br>
    <div class="d-flex justify-content-between">
        <cite><?php echo $this->session->userdata['meetings']['name'];?></cite>
        <a  class="text-secondary" href="http://churchbuild.net/index.php/login/logout"><i class="fa fa-power-off"></i> Logout</a>
        <!-- <a class="badge badge-danger" href="http://churchbuild.net/index.php/login/logout" role="button"><i class="fa fa-power-off"></i></a> -->
    </div>
    <div class="d-flex justify-content-between">
        <cite><?php echo $this->session->userdata['meetings']['email'];?></cite>
        <cite>
            <?php
                switch ($this->session->userdata['meetings']['role']) {
                    case "0":
                    case "1":
                        echo "Host";
                        break;
                    case "2":
                        echo "Admin (" . $this->session->userdata['meetings']['church'] . ")";
                        break;
                    case "3":
                        echo "Super Admin";
                        break;
                    default:
                        die("here");
                        session_start();
                        session_destroy();
                        header("Location: http://churchbuild.net/index.php/login");
                }
            ?>
        </cite>
    </div>
<br><br>
    <div class="card">
    <div class="card-body">
        <div class="form-group text-center">
            <div class="alert alert-success" role="alert">
                Find the meetings using the <strong>Filter</strong><sup><span class="badge badge-danger">New</span></sup>
            </div>
            <br>
            <form action="http://churchbuild.net/index.php/dashboard/filter" name="filterForm" id="filterForm" method="post">
            <?php
            if(isset($churches)){ ?>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" style="width: 126px;">Church</span>
                </div>
                    <select class="form-control" name="church" id="church">
                        <option value="">All Churches</option>
                        <? foreach($churches as $church) { ?>
                        <option value="<?php echo $church[1];?>"><?php echo $church[1]; ?></option>
                        <? } ?>
                    </select>
            </div>
            <br>
            <? } ?>

            <?php
                if(isset($meeting_types)){ ?>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text">Meeting Type</span>
                </div>
                    <select class="form-control" name="meeting_type" id="meeting_type">
                        <option value="">All Meeting Types</option>
                        <? foreach($meeting_types as $meeting_type) { ?>
                        <option value="<?php echo $meeting_type[1];?>"><?php echo $meeting_type[1]; ?></option>
                        <? } ?>
                    </select>
            </div>
            <br>
            <? } ?>

            <?php
                if(isset($hosts)){ ?>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" style="width: 126px;">Host</span>
                </div>
                    <select class="form-control" name="host" id="host">
                        <option value="">All Hosts</option>
                        <? foreach($hosts as $host) { ?>
                        <option value="<?php echo $host[1];?>"><?php echo $host[1]; ?></option>
                        <? } ?>
                    </select>
            </div>
            <br>
            <? } ?>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                <span class="input-group-text" style="width: 126px;">From date</span>
                </div>
                <input type="date" name="from_date" id="from_date">
            </div>
            <br>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                <span class="input-group-text" style="width: 126px;">To date</span>
                </div>
                <input type="date" name="to_date" id="to_date">
            </div>
        </div>
        <br>

        <div class="d-flex justify-content-around">
            <button id="tables" name="button" value="tables" class="btn btn-secondary btn-sm btn-block data-button"><i class="fa fa-list"></i> Tables</button>&nbsp&nbsp
            <button id="graphs" name="button" value="graphs" class="btn btn-secondary btn-block data-button"><i class="fa fa-bar-chart"></i> Graphs</button> &nbsp&nbsp
            <button id="excel" name="button" value="excel" class="btn btn-secondary btn-block data-button"><i class="fa fa-download"></i> Excel</button>
        </div>
        </form>
    </div>
    </div>
    <br>
    <br>
    <!-- <br><br><a class="btn btn-outline-success btn-lg btn-block" href="http://churchbuild.net/index.php/dashboard/getcsv" role="button">Download CSV</a> -->
    <!-- <br><br><a class="btn btn-outline-danger btn-lg btn-block" href="http://churchbuild.net/index.php/dashboard/tables" role="button">Show tables</a> -->
    <!-- <br><br><a class="btn btn-outline-success btn-lg btn-block" href="http://churchbuild.net/index.php/login/logout" role="button">Logout</a> -->
    <!-- <br><br> -->
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script>
    // $(document).ready(function(){
    //     $(".data-button").click(function(event){
    //         event.preventDefault();
    //         host = $("#host").val();
    //         meetingType = $("#meeting_type").val();
    //         church = $("#church").val();
    //         if((host == '') && (meetingType == '') && (church == '')) {
    //             $("#errorMessage").hide();
    //             $("#errorMessage").show();
    //         } else {
    //             $("#errorMessage").hide();
    //             btnId = $(this).attr('id');

    //             if(btnId == "excel") {
    //                 $("#filterForm").submit();
    //             } else {
    //                 $.post("http://churchbuild.net/index.php/dashboard/filter", {
    //                     host: host,
    //                     church: church,
    //                     meetingType: meetingType,
    //                     for: btnId,
    //                     started_at: started_at,
    //                     ended_at: ended_at,
    //                 }, function(data, status){
    //                     console.log(data)
    //                 });
    //             }
    //         }
    //     });
    // });
    </script>
</body>
</html>