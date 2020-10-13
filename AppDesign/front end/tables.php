<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
</head>
<body>
    <div class="container">
    <br><br>
    <blockquote class="blockquote text-right">
    <footer class="blockquote-footer">Logged In, as <cite title="Source Title"><?php echo $this->session->userdata['meetings']['name'];?></cite></footer>
    <?php $role = $this->session->userdata['meetings']['role'];?>
    </blockquote>
    <br>
    <br>
    <br>
    <?php if(count($imp_details)) { ?>
        <table id="example" class="display">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Meet Name</th>
                    <th>Participants</th>
                    <th>Host</th>
                    <th>Start time</th>
                    <th>End time</th>
                </tr>
            </thead>
            <?php $i=1;
                foreach ($imp_details as $mkey => $meeting) { ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $meeting["name"];?></td>
                    <td><?php echo count($meeting["participants"]);?></td>
                    <td><?php echo $meeting["host"];?></td>
                    <td><?php echo $meeting["time_start"];?></td>
                    <td><?php echo $meeting["time_end"];?></td>
                </tr>
            <?php } ?>
            <tfoot>
                <tr>
                    <th>#</th>
                    <th>Meet Name</th>
                    <th>Participants</th>
                    <th>Host</th>
                    <th>Start time</th>
                    <th>End time</th>
                </tr>
            </tfoot>
        </table>
        <? } else {
            echo "No meetings to show.";
        }
    ?>
    <br>
    <br>
    <br>
    <?php
        foreach ($imp_details as $mkey => $meeting) { ?>
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title"><?php echo "Meeting: " . $meeting['name'];?></h5>
                    <h6 class="card-subtitle mb-2 text-muted">
                        <span class="badge badge-light"><?php if($meeting['timestamp_start'] === 0) { echo "Start time N/S"; } else { echo date('F j, h:i A', $meeting['timestamp_start']); } ?></span>
                        <span class="badge badge-light"><?php if($meeting['timestamp_end'] === 0) { echo "End time N/S"; } else { echo date('F j, h:i A', $meeting['timestamp_end']); } ?></span>
                        <?php $count = count($meeting['participants']);?>
                        <span class="badge badge-light"><?php if($count === 1) { echo "Participant - "; } else { echo "Participants - ";} echo $count;?></span>
                    </h6>
                    <?php if($role != 2) { ?>
                        <footer class="blockquote-footer">Host: <cite title="Source Title"><?php echo $meeting['host'];?></cite></footer>
                    <?php } ?>
                    <br>
                    <?php if(count($meeting['participants'])) {
                            $i = 1;
                    ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($meeting['participants'] as $pkey => $pvalue) { ?>
                                    <tr>
                                        <th scope="row"><?php echo $i++; ?></th>
                                        <td><?php if(isset($pvalue[0])) { echo $pvalue[0]; } ?></td>
                                        <td><?php if(isset($pvalue[1])) { echo $pvalue[1]; } ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } else { ?>
                        (No participants)
                    <?php } ?>
                    <a href="http://churchbuild.net/index.php/dashboard/getcsv/<?php echo $mkey; ?>" class="badge badge-secondary">Download CSV</a>
                </div>
            </div>
            <br><br>
        <? }
    ?>

    <br><br><br><br>
    <a class="btn btn-outline-success btn-lg btn-block" href="http://churchbuild.net/index.php/login/logout" role="button">Logout</a>
    <br><br>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        } );

    </script>
</body>
</html>