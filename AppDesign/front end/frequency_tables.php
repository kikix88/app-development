<!DOCTYPE html>
<html lang="en">
<head>
    <title>Frequency Tables</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
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
<br><br><br>
        <?php foreach ($tables as $tkey => $table) { ?>
        <div class="my-table text-center mt-5 mb-5">
            <table id="table_<?php echo $tkey?>" class="display">
                <thead>
                <?php foreach ($table['info'] as $ti_key => $ti_row) { ?>
                    <tr <?php if($ti_key === 5) { ?> style="background-color: #b3f5c4;" <?php } else { ?> style="background-color: #ffe8ba;" <?php } ?>>
                        <?php foreach ($ti_row as $ti_row_key => $ti_row_value) { ?>
                        <th><?php echo $ti_row_value;?></th>
                        <?php } if($ti_key !== 6) { ?>
                            <th></th>
                        <?php } ?>
                    </tr>
                <?php } ?>
                </thead>
                <tbody>
                <?php foreach ($table['data'] as $td_key => $td_row) { ?>
                    <tr>
                        <?php foreach ($td_row as $td_row_key => $td_row_value) { ?>
                        <td <?php if($td_row_key === (count($td_row)-1)) { ?> style="background-color: #b3f5c4;" <?php }?>><?php echo $td_row_value;?></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            </div>
            <br><br><br>
        <?php }
            if(empty($tables)) {
                echo "No meetings with the selected options, so no tables.";
            }
        ?>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        <?php foreach ($tables as $tkey => $table) { ?>
        $("#table_<?php echo $tkey?>").DataTable({
            "paging":   false,
        });
        <?php } ?>
    } );
</script>
</body>
</html>