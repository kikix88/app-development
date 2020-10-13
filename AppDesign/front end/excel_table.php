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
    <form action="http://saran.btechprime.com/index.php/excel/meeting_data" method="post">
        <div class="form-group text-center">
        <label for="exampleFormControlSelect1">Example select</label>
            <select class="form-control" name="meeting_type" required>
                <option value="">Select Meeting Type</option>
                <option value="Lord's Day Meeting">Lord's Day Meeting</option>
                <option value="Prayer Meeting">Prayer Meeting</option>
                <option value="Home Meeting">Home Meeting</option>
                <option value="Morning Revival">Morning Revival</option>
                <option value="Gospel Visits">Gospel Visits</option>
                <option value="Truth Pursuit">Truth Pursuit</option>
                <option value="Service Meeting">Service Meeting</option>
                <option value="Fellowship">Fellowship</option>
                <option value="Others">Others</option>
            </select>
            <input type="hidden" name="for" value="excel_download">
        </div>
        <button type="submit" class="btn btn-outline-dark btn-lg btn-block">Download XLSX</button>
    </form>
    <br>
    <br>
    <br>
    <br>


    <br><br><br><br>
    <a class="btn btn-outline-success btn-lg btn-block" href="http://saran.btechprime.com/index.php/login/logout" role="button">Logout</a>
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