<html>
  <head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart', 'bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        <?php
            $i=0;
            foreach ($name_version as $title => $versions) { $i++; ?>
                var data<?php echo $i;?> = google.visualization.arrayToDataTable([
                    ["<?php $title;?>", "Participants", { role: "style" } ],
                    <?php  ksort($versions);
                    foreach ($versions as $version => $count) { ?>
                        ["<?php echo $version;?>", <?php echo $count;?>, "#76A7FA"],
                    <?php } ?>
                    ]);
                    var view<?php echo $i;?> = new google.visualization.DataView(data<?php echo $i;?>);
                    view<?php echo $i;?>.setColumns([0, 1,
                        { calc: "stringify",
                            sourceColumn: 1,
                            type: "string",
                            role: "annotation" },
                        2]);

                var options<?php echo $i;?> = {
                    title: "<?php echo $title;?>",
                    width: 600,
                    height: 400,
                    bar: {groupWidth: "95%"},
                    legend: { position: "none" },
                };
                var chart<?php echo $i;?> = new google.visualization.ColumnChart(document.getElementById("div<?php echo $i;?>"));
                chart<?php echo $i;?>.draw(view<?php echo $i;?>, options<?php echo $i;?>);

            <?php } ?>

            <?php if($unique_name_time) { ?>
                var data_unique_name_time = google.visualization.arrayToDataTable([
                    ["Meeting Name", "Unique Participants", { role: "style" } ],
                    <?php  ksort($unique_name_time);
                    foreach ($unique_name_time as $name => $count) { ?>
                        ["<?php echo $name;?>", <?php echo $count;?>, "gold"],
                    <?php } ?>
                    ]);


                    var options_unique_name_time = {
                        chart: {
                            title: 'Unique Participants for Meetings (Name+Time)',
                            subtitle: 'Recurring meetings, unique visitors count for each recurring meeting',
                        },                //     width: 600,
                        width: 600,
                        height: data_unique_name_time.getNumberOfRows() * 41 + 150,
                        bars: 'horizontal' // Required for Material Bar Charts.
                    };

                    var chart_data_unique_name_time = new google.charts.Bar(document.getElementById('div_unique_name_time'));

                    chart_data_unique_name_time.draw(data_unique_name_time, google.charts.Bar.convertOptions(options_unique_name_time));
                    //}

                //     var view_unique_name_time = new google.visualization.DataView(data_unique_name_time);
                //     view_unique_name_time.setColumns([0, 1,
                //         { calc: "stringify",
                //             sourceColumn: 1,
                //             type: "string",
                //             role: "annotation" },
                //         2]);

                // var options_unique_name_time = {
                //     title: "Unique Participants for Meetings (Name+Time)",
                //     width: 600,
                //     height: 400,
                //     bar: {groupWidth: "95%"},
                //     legend: { position: "none" },
                // };
                // var chart_unique_name_time = new google.visualization.ColumnChart(document.getElementById("div_unique_name_time"));
                // chart_unique_name_time.draw(view_unique_name_time, options_unique_name_time);

            <?php } ?>



            <?php if($unique_name) { ?>
                var data_unique_name = google.visualization.arrayToDataTable([
                    ["Meeting Name", "Unique Participants", { role: "style" } ],
                    <?php  ksort($unique_name);
                    foreach ($unique_name as $name => $count) { ?>
                        ["<?php echo $name;?>", <?php echo $count;?>, "#C5A5CF"],
                    <?php } ?>
                    ]);
                    var view_unique_name = new google.visualization.DataView(data_unique_name);
                    view_unique_name.setColumns([0, 1,
                        { calc: "stringify",
                            sourceColumn: 1,
                            type: "string",
                            role: "annotation" },
                        2]);

                var options_unique_name = {
                    title: "Unique Participants for each Meeting (Grouped by Name)",
                    width: data_unique_name.getNumberOfRows() * 41 + 50,
                    height: 600,
                    bar: {groupWidth: "95%"},
                    legend: { position: "none" },
                };
                var chart_unique_name = new google.visualization.ColumnChart(document.getElementById("div_unique_name"));
                chart_unique_name.draw(view_unique_name, options_unique_name);

            <?php } ?>



            <?php if($unique_host) { ?>
                var data_unique_host = google.visualization.arrayToDataTable([
                    ["Host Name", "Unique Participants", { role: "style" } ],
                    <?php  ksort($unique_host);
                    foreach ($unique_host as $name => $count) { ?>
                        ["<?php echo $name;?>", <?php echo $count;?>, "red"],
                    <?php } ?>
                    ]);
                    var view_unique_host = new google.visualization.DataView(data_unique_host);
                    view_unique_host.setColumns([0, 1,
                        { calc: "stringify",
                            sourceColumn: 1,
                            type: "string",
                            role: "annotation" },
                        2]);

                var options_unique_host = {
                    title: "Unique participants for each Host",
                    width: 1000,
                    height: 1000,
                    bar: {groupWidth: "95%"},
                    legend: { position: "none" },
                };
                var chart_unique_host = new google.visualization.ColumnChart(document.getElementById("div_unique_host"));
                chart_unique_host.draw(view_unique_host, options_unique_host);

            <?php } ?>



            <?php if($unique_church) { ?>
                var data_unique_church = google.visualization.arrayToDataTable([
                    ["Church Name", "Unique Participants", { role: "style" } ],
                    <?php  ksort($unique_church);
                    foreach ($unique_church as $name => $count) { ?>
                        ["<?php echo $name;?>", <?php echo $count;?>, "gold"],
                    <?php } ?>
                    ]);
                    var view_unique_church = new google.visualization.DataView(data_unique_church);
                    view_unique_church.setColumns([0, 1,
                        { calc: "stringify",
                            sourceColumn: 1,
                            type: "string",
                            role: "annotation" },
                        2]);

                var options_unique_church = {
                    title: "Unique participants for each Church",
                    width: 600,
                    height: 400,
                    bar: {groupWidth: "95%"},
                    legend: { position: "none" },
                };
                var chart_unique_church = new google.visualization.ColumnChart(document.getElementById("div_unique_church"));
                chart_unique_church.draw(view_unique_church, options_unique_church);

            <?php } ?>
      }



</script>
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
        <div class="d-flex flex-column bd-highlight mb-3">
            <?php $displayed = FALSE; if($unique_name_time) { ?>
                <div id="div_unique_name_time" style="border: 1px solid #ccc" class="align-self-center"></div><br>
            <?php $displayed = TRUE; } ?>

            <?php if($unique_name) { ?>
                <div id="div_unique_name" style="border: 1px solid #ccc" class="align-self-center"></div><br>
            <?php $displayed = TRUE; } ?>

            <?php if($unique_host) { ?>
                <div id="div_unique_host" style="border: 1px solid #ccc" class="align-self-center"></div><br>
            <?php $displayed = TRUE; } ?>

            <?php if($unique_church) { ?>
                <div id="div_unique_church" style="border: 1px solid #ccc" class="align-self-center"></div><br>
            <?php $displayed = TRUE; } ?>

            <?php $i=0;
            foreach ($name_version as $title => $versions) { $i++; ?>
                <div id="div<?php echo $i;?>" style="border: 1px solid #ccc" class="align-self-center"></div><br>
            <?php $displayed = TRUE; } if(!$displayed) { echo "No meetings with the selected options, so no graphs.";}?>

        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>
