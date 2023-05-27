
<?php

ob_start();
require_once "functions/db.php";

// Initialize the session

session_start();

// If session variable is not set it will redirect to login page

if(!isset($_SESSION['email']) || empty($_SESSION['email'])){

header("location: login.php");

exit;
}

$email = $_SESSION['email'];

$sql_posts = "SELECT * FROM posts";
$query_posts = mysqli_query($connection, $sql_posts);

$sql_excel_data = "SELECT * FROM excel_data";
$query_sql_excel_data = mysqli_query($connection, $sql_excel_data);

$sql_contacts = "SELECT * FROM contacts";
$query_contacts = mysqli_query($connection, $sql_contacts);

$sql_subscribers = "SELECT * FROM subscribers";
$query_subscribers = mysqli_query($connection, $sql_subscribers);

$sql_comments = "SELECT * FROM comments";
$query_comments = mysqli_query($connection, $sql_comments);

$data1 = '';
$data2 = '';
$buildingName = '';

$sql_relevance = "SELECT id, relevance, region FROM excel_data WHERE region IS NOT NULL GROUP BY region ORDER BY id DESC LIMIT 20";
$datarelevance = array();
if($query_relevance = mysqli_query($connection, $sql_relevance)){
    while ($rowdata = $query_relevance->fetch_assoc()) {
        $data1 = $data1 . '"'. $rowdata['relevance'].'",';
        $data2 = $data2 . '"'. ucwords($rowdata['region']) .'",';
    }

    $data1 = trim($data1,",");
	$data2 = trim($data2,",");
}

$sql_likelihood = "SELECT DISTINCT likelihood  FROM excel_data";
$datalikelihood = array();
if($likelihood = mysqli_query($connection, $sql_likelihood)){
    while ($rowdata = $likelihood->fetch_assoc()) {
        $datalikelihood[] = $rowdata;
    }
}

$sql_relevance = "SELECT DISTINCT relevance  FROM excel_data";
$datarelevance = array();
if($likelihood = mysqli_query($connection, $sql_relevance)){
    while ($rowdata = $likelihood->fetch_assoc()) {
        $datarelevance[] = $rowdata;
    }
}

$query="SELECT country, intensity FROM excel_data WHERE country IS NOT NULL   AND end_year = 2023
GROUP BY country";
if($stmt = mysqli_query($connection, $query)){

$php_data_array = Array(); // create PHP array
while ($row = $stmt->fetch_row()) {
$php_data_array[] = $row; // Adding to array
}
}else{
echo $connection->error;
}

// echo '<pre>';
// print_r(json_encode($php_data_array) );
// exit;
// Transfor PHP array to JavaScript two dimensional array 
echo "<script>
var my_2d = ".json_encode($php_data_array)."
</script>";



?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<link rel="icon" type="image/png" sizes="16x16" href="../plugins/images/icon.png">
<title>Website Admin</title>
<!-- Bootstrap Core CSS -->
<link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="../plugins/bower_components/bootstrap-extension/css/bootstrap-extension.css" rel="stylesheet">
<!-- Menu CSS -->
<link href="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css" rel="stylesheet">
<!-- toast CSS -->
<link href="../plugins/bower_components/toast-master/css/jquery.toast.css" rel="stylesheet">
<!-- morris CSS -->
<link href="../plugins/bower_components/morrisjs/morris.css" rel="stylesheet">
<!-- animation CSS -->
<link href="css/animate.css" rel="stylesheet">
<!-- Custom CSS -->
<link href="css/style.css" rel="stylesheet">
<!-- color CSS -->
<link href="css/colors/blue.css" id="theme" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<style>
#chart-container {
width: 100%;
height: auto;
}

#graphCanvas{
height: 500px;
width: 100%;
}

#chart_div{
display: flex;
justify-content: center;
}

/* .select2-selection.select2-selection--multiple{
    padding: 0.3em;
} */
</style>
</head>

<body>
<!-- Preloader -->
<div class="preloader">
<div class="cssload-speeding-wheel"></div>
</div>
<div id="wrapper">
<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top m-b-0">
<div class="navbar-header"> <a class="navbar-toggle hidden-sm hidden-md hidden-lg " href="javascript:void(0)" data-toggle="collapse" data-target=".navbar-collapse"><i class="ti-menu"></i></a>
<div class="top-left-part"><a class="logo" href="index.php"><b><img src="../plugins/images/icon.png" style="width: 30px; height: 30px;" alt="home" /></b><span class="hidden-xs"><b>Company</b></span></a></div>
<ul class="nav navbar-top-links navbar-left hidden-xs">
<li><a href="javascript:void(0)" class="open-close hidden-xs waves-effect waves-light"><i class="icon-arrow-left-circle ti-menu"></i></a></li>
<li>
    <form role="search" class="app-search hidden-xs">
        <input type="text" placeholder="Search..." class="form-control"> <a href=""><i class="fa fa-search"></i></a> </form>
</li>
</ul>
<ul class="nav navbar-top-links navbar-right pull-right">

<!-- /.dropdown -->



<li class="right-side-toggle"> <a class="waves-effect waves-light" href="javascript:void(0)"><i class="ti-settings"></i></a></li>
<!-- /.dropdown -->
</ul>
</div>
<!-- /.navbar-header -->
<!-- /.navbar-top-links -->
<!-- /.navbar-static-side -->
</nav>
<!-- Left navbar-header -->
<div class="navbar-default sidebar" role="navigation">
<div class="sidebar-nav navbar-collapse slimscrollsidebar">
<ul class="nav" id="side-menu">
<li class="sidebar-search hidden-sm hidden-md hidden-lg">
    <!-- input-group -->
    <div class="input-group custom-search-form">
        <input type="text" class="form-control" placeholder="Search..."> <span class="input-group-btn">
        <button class="btn btn-default" type="button"> <i class="fa fa-search"></i> </button>
        </span> 
    </div>
    <!-- /input-group -->
</li>
<li class="user-pro">
    <a href="#" class="waves-effect"><img src="../plugins/images/user.jpg" alt="user-img" class="img-circle"> <span class="hide-menu"> Account<span class="fa arrow"></span></span>
    </a>
    <ul class="nav nav-second-level">
        <li><a href="settings.php"><i class="ti-settings"></i> Account Setting</a></li>
        <li><a href="functions/logout.php"><i class="fa fa-power-off"></i> Logout</a></li>
    </ul>
</li>
<li class="nav-small-cap m-t-10">--- Main Menu</li>
<li> <a href="index.php" class="waves-effect active"><i class="linea-icon linea-basic fa-fw" data-icon="v"></i> <span class="hide-menu"> Dashboard </a>
</li>


<!-- <li> <a href="#" class="waves-effect"><i data-icon="&#xe00b;" class="linea-icon linea-basic fa-fw"></i> <span class="hide-menu">Blog<span class="fa arrow"></span></span></a>
    <ul class="nav nav-second-level">
        <li><a href="posts.php">All Posts</a></li>
        <li><a href="new-post.php">Create Post</a></li>
        <li><a href="comments.php" class="waves-effect">Comments</a>
        </li>
    </ul>
</li> -->

<li><a href="inbox.php" class="waves-effect"><i data-icon=")" class="linea-icon linea-basic fa-fw"></i> <span class="hide-menu">Messages</span></a>
</li>

<li><a href="subscribers.php" class="waves-effect"><i data-icon="n" class="linea-icon linea-basic fa-fw"></i> <span class="hide-menu">Subscribers</span></a>
</li>

    <li class="nav-small-cap">--- Other</li>

<!-- <li> <a href="#" class="waves-effect"><i data-icon="H" class="linea-icon linea-basic fa-fw"></i> <span class="hide-menu">Access<span class="fa arrow"></span></span></a>
    <ul class="nav nav-second-level">
        <li><a href="users.php">Administrators</a></li>
        <li><a href="new-user.php">Create Admin</a></li>
        
    </ul>
</li> -->

<li><a href="functions/logout.php" class="waves-effect"><i class="icon-logout fa-fw"></i> <span class="hide-menu">Log out</span></a></li>

</ul>
</div>
</div>
<!-- Left navbar-header end -->
<!-- Page Content -->
<div id="page-wrapper">
<div class="container-fluid">
<div class="row bg-title">
<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
    <h4 class="page-title"><?php echo $email;?></h4> </div>
<div class="col-lg-9 col-sm-8 col-md-8 col-xs-12"> 
    <ol class="breadcrumb">
        <li><a href="#">Dashboard</a></li>
        <li class="active">Home</li>
    </ol>
</div>
<!-- /.col-lg-12 -->
</div>

<?php 

if (isset($_GET['set'])) {
echo'<div class="alert alert-success" >
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
<strong>DONE!! </strong><p> Your password has been successfully updated.</p>
    </div>';
    }


?>

<!-- /.row -->
<div class="row">
<div class="col-md-12 col-lg-12 col-sm-12">
    <div class="white-box">
        <div class="row row-in">
            <div class="col-lg-3 col-sm-6 row-in-br">
                <div class="col-in row">
                    <div class="col-md-6 col-sm-6 col-xs-6"> <i data-icon="E" class="linea-icon linea-basic"></i>
                        <h5 class="text-muted vb">Excel Data</h5> </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <h3 class="counter text-right m-t-15 text-danger"><?php echo mysqli_num_rows($query_sql_excel_data);?></h3> </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="progress">
                            <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"> <span class="sr-only">40% Complete (success)</span> </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 row-in-br  b-r-none">
                <div class="col-in row">
                    <div class="col-md-6 col-sm-6 col-xs-6"> <i class="linea-icon linea-basic" data-icon="&#xe01b;"></i>
                        <h5 class="text-muted vb">Blog Comments</h5> </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <h3 class="counter text-right m-t-15 text-megna"><?php echo mysqli_num_rows($query_comments);?></h3> </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="progress">
                            <div class="progress-bar progress-bar-megna" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"> <span class="sr-only">40% Complete (success)</span> </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 row-in-br">
                <div class="col-in row">
                    <div class="col-md-6 col-sm-6 col-xs-6"> <i class="linea-icon linea-basic" data-icon="&#xe00b;"></i>
                        <h5 class="text-muted vb">Contact Messages</h5> </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <h3 class="counter text-right m-t-15 text-primary"><?php echo mysqli_num_rows($query_contacts);?></h3> </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="progress">
                            <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"> <span class="sr-only">40% Complete (success)</span> </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6  b-0">
                <div class="col-in row">
                    <div class="col-md-6 col-sm-6 col-xs-6"> <i class="linea-icon linea-basic" data-icon="&#xe016;"></i>
                        <h5 class="text-muted vb">Company Subscribers</h5> </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <h3 class="counter text-right m-t-15 text-success"><?php echo mysqli_num_rows($query_subscribers);?></h3> </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="progress">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"> <span class="sr-only">40% Complete (success)</span> </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!--row -->

<div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="mail-contnet" id="chart-container">
            <div id="chart_div"></div>
            <h5 class="text-center">Sector</h5>
            </div>
            </div>
            <div class="col-md-12 col-lg-12 col-sm-12" style="display: flex; align-content: flex-start; justify-content: center; align-items: center; padding: 2em; flex-direction: column; ">
            <label>Select Likelihood</label>
            <select id="multiple" class="js-states form-control" multiple style="width:300px;">
            <?php foreach ($datalikelihood as $item): ?>
            <option value="<?php echo $item['likelihood']; ?>"><?php echo $item['likelihood']; ?></option>
            <?php endforeach; ?>
            </select>
            <button type="button" id="myButton" class="btn btn-primary mt-3">Filter</button>

            </div>
            <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="white-box">
                <h3 class="box-title">Likelihood vs Topics Bar Chart</h3>
                <div class="comment-center">
                    <div class="comment-body">
                        <div class="mail-contnet" id="chart-container2">
                        <canvas id="graphCanvas"></canvas>
                        <h5 class="text-center">Topics</h5>
                        </div>
                    </div>
                </div>
            </div>
            </div>

            <!-- chart 3 -->
            <div class="col-md-12 col-lg-12 col-sm-12" style="display: flex; align-content: flex-start; justify-content: center; align-items: center; padding: 2em; flex-direction: column; ">
            <label>Select Relevance</label>
            <select id="multiples" class="js-states form-control" multiple style="width:300px;">
            <?php foreach ($datarelevance as $item): ?>
            <option value="<?php echo $item['relevance']; ?>"><?php echo $item['relevance']; ?></option>
            <?php endforeach; ?>
            </select>
            <button type="button" id="myButton2" class="btn btn-primary mt-3">Filter</button>
            </div>
            <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="white-box">
                <h3 class="box-title">Relevance vs City  Graph</h3>
                <div class="comment-center">
                    <div class="comment-body">
                        <div class="mail-contnet" id="chart-container3">
                        <canvas id="chart" style="width: 100%; height: 65vh; background: #222; border: 1px solid #555652; margin-top: 10px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            </div>

<div class="col-md-12 col-lg-12 col-sm-12">
    <div class="white-box">
        <!-- <h3 class="box-title">Recent Blog Posts
            <div class="col-md-3 col-sm-4 col-xs-6 pull-right">
            <select class="form-control pull-right row b-none">
                <option>March 2018</option>
                <option>April 2018</option>
                <option>May 2018</option>
                <option>June 2018</option>
                <option>July 2018</option>
            </select>
            </div>
        </h3> -->
        <div class="row sales-report">
            <div class="col-md-6 col-sm-6 col-xs-6">
                <h2>Company 2018</h2>
                <p>Blog Posts</p>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6 ">
                <h1 class="text-right text-success m-t-20"><?php echo mysqli_num_rows($query_sql_excel_data);?></h1> </div>
        </div>
        <div class="table-responsive">
            <table class="table ">

                <?php
                            if (mysqli_num_rows($query_sql_excel_data)==0) {
                                echo "<i style='color:brown;'>No Posts Yet :( Upload Company's first blog post today! </i> ";
                                }
                                else
                                    
                                {
                                    echo '
                                            <thead>
                                        <tr>
                                            <th>TOPIC</th>
                                            <th>END DATE</th>
                                            <th>SOURCE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    ';
                                }
                                    $counter = 0;
                                    $max = 3;

                            while (($row = mysqli_fetch_array($query_sql_excel_data)) and ($counter < $max) )
                            {
    

                            echo '
                    <tr>
                        <td class="txt-oflo">'.$row["topic"].'</td>
                        <td class="txt-oflo">'.$row["end_year"].'</td>
                        <td><span class="text-success">'.$row["source"].'</span></td>
                    </tr>
                ';
                $counter++;
                    }
                ?>

                </tbody>

            </table> 
                    <a href="posts.php" class="btn btn-info btn-rounded btn-outline hidden-xs hidden-sm waves-effect waves-light">View All Posts</a>
                    </div>
    </div>
</div>
</div>
<!-- /.row -->

<!-- .right-sidebar -->
<div class="right-sidebar">
<div class="slimscrollright">
    <div class="rpanel-title"> Service Panel <span><i class="ti-close right-side-toggle"></i></span> </div>
    <div class="r-panel-body">
        <ul>
            <li><b>Layout Options</b></li>
            <li>
                <div class="checkbox checkbox-info">
                    <input id="checkbox1" type="checkbox" class="fxhdr">
                    <label for="checkbox1"> Fix Header </label>
                </div>
            </li>
            <li>
                <div class="checkbox checkbox-warning">
                    <input id="checkbox2" type="checkbox" class="fxsdr">
                    <label for="checkbox2"> Fix Sidebar </label>
                </div>
            </li>
            <li>
                <div class="checkbox checkbox-success">
                    <input id="checkbox4" type="checkbox" class="open-close">
                    <label for="checkbox4"> Toggle Sidebar </label>
                </div>
            </li>
        </ul>
        <ul id="themecolors" class="m-t-20">
            <li><b>With Light sidebar</b></li>
            <li><a href="javascript:void(0)" theme="default" class="default-theme">1</a></li>
            <li><a href="javascript:void(0)" theme="green" class="green-theme">2</a></li>
            <li><a href="javascript:void(0)" theme="gray" class="yellow-theme">3</a></li>
            <li><a href="javascript:void(0)" theme="blue" class="blue-theme working">4</a></li>
            <li><a href="javascript:void(0)" theme="purple" class="purple-theme">5</a></li>
            <li><a href="javascript:void(0)" theme="megna" class="megna-theme">6</a></li>
            <li><b>With Dark sidebar</b></li>
            <br/>
            <li><a href="javascript:void(0)" theme="default-dark" class="default-dark-theme">7</a></li>
            <li><a href="javascript:void(0)" theme="green-dark" class="green-dark-theme">8</a></li>
            <li><a href="javascript:void(0)" theme="gray-dark" class="yellow-dark-theme">9</a></li>
            <li><a href="javascript:void(0)" theme="blue-dark" class="blue-dark-theme">10</a></li>
            <li><a href="javascript:void(0)" theme="purple-dark" class="purple-dark-theme">11</a></li>
            <li><a href="javascript:void(0)" theme="megna-dark" class="megna-dark-theme">12</a></li>
        </ul>
        
    </div>
</div>
</div>
<!-- /.right-sidebar -->
</div>
<!-- /.container-fluid -->
<footer class="footer text-center"> 2018 &copy; Company Admin </footer>
</div>
<!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->
<!-- jQuery -->
<script src="../plugins/bower_components/jquery/dist/jquery.min.js"></script>
<script src="js/Chart.min.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="bootstrap/dist/js/tether.min.js"></script>
<script src="bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../plugins/bower_components/bootstrap-extension/js/bootstrap-extension.min.js"></script>
<!-- Menu Plugin JavaScript -->
<script src="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
<!--slimscroll JavaScript -->
<script src="js/jquery.slimscroll.js"></script>
<!--Wave Effects -->
<script src="js/waves.js"></script>
<!--Counter js -->
<script src="../plugins/bower_components/waypoints/lib/jquery.waypoints.js"></script>
<script src="../plugins/bower_components/counterup/jquery.counterup.min.js"></script>
<!--Morris JavaScript -->
<script src="../plugins/bower_components/raphael/raphael-min.js"></script>
<script src="../plugins/bower_components/morrisjs/morris.js"></script>
<!-- Custom Theme JavaScript -->
<script src="js/custom.min.js"></script>
<script src="js/dashboard1.js"></script>
<!-- Sparkline chart JavaScript -->
<script src="../plugins/bower_components/jquery-sparkline/jquery.sparkline.min.js"></script>
<script src="../plugins/bower_components/jquery-sparkline/jquery.charts-sparkline.js"></script>
<script src="../plugins/bower_components/toast-master/js/jquery.toast.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- Select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
$.toast({
heading: 'Welcome to Company admin',
text: 'view, edit and upload new posts to keep your users engaged.',
position: 'top-right',
loaderBg: '#ff6849',
icon: 'info',
hideAfter: 3700,
stack: 6
});
showGraph();
});


function showGraph()
{

{
$.post("data.php",
function (data)
{
console.log(data);
var sector = [];
var intensity = [];

for (var i in data) {
    sector.push(data[i].topic);
    intensity.push(data[i].likelihood);
}

var chartdata = {
    labels: sector,
    datasets: [
        {
            label: 'Likelihood',
            backgroundColor: '#49e2ff',
            borderColor: '#46d5f1',
            hoverBackgroundColor: '#CCCCCC',
            hoverBorderColor: '#666666',
            data: intensity
        }
    ]
};
var graphTarget = $("#graphCanvas");
graphTarget.innerHTML = "";
var barGraph = new Chart(graphTarget, {
    type: 'bar',
    data: chartdata
});

});
}
}




document.addEventListener('DOMContentLoaded', function() {
  var myButton = document.getElementById('myButton');
  
  myButton.addEventListener('click', function() {
    var mySelect = document.getElementById('multiple');

    // Get the selected options
    var selectedOptions = Array.from(mySelect.selectedOptions).map(function(option) {
      return option.value;
    });
    if (selectedOptions.length === 0) {
      alert('Please select Likelihood Before Filtering');
      return;
    }
    // Log the selected values
    console.log(selectedOptions);
    // Perform AJAX request here
        $.ajax({
        url:"data.php",    //the page containing php script
        type: "post",    //request type,
        dataType: 'json',
        data: {selectedOptions: selectedOptions},
        success:function(result){
                var sector = [];
                var intensity = [];

                for (var i in result) {
                sector.push(result[i].topic);
                intensity.push(result[i].likelihood);
                }

                var chartdata = {
                labels: sector,
                datasets: [
                {
                label: 'Likelihood',
                backgroundColor: '#49e2ff',
                borderColor: '#46d5f1',
                hoverBackgroundColor: '#CCCCCC',
                hoverBorderColor: '#666666',
                data: intensity
                }
                ]
                };
                var graphTarget = $("#graphCanvas");
                graphTarget.innerHTML = "";
                var barGraph = new Chart(graphTarget, {
                type: 'bar',
                data: chartdata
                });
        },
        error: function(xhr, status, error) {
    console.log("AJAX Error:", error);
  }

        });
  });


  var myButton2 = document.getElementById('myButton2');
  
  myButton2.addEventListener('click', function() {
    var mySelects = document.getElementById('multiples');
  
    // Get the selected options
    var selectedOptionse = Array.from(mySelects.selectedOptions).map(function(option) {
      return option.value;
    });

    if (selectedOptionse.length === 0) {
      alert('Please select Relevance Before Filtering');
      return;
    }
  
    
    // Log the selected values
    console.log(selectedOptionse);
    // Perform AJAX request here
        $.ajax({
        url:"data.php",    //the page containing php script
        type: "post",    //request type,
        dataType: 'json',
        data: {selectedOptionsnew: selectedOptionse},
        success:function(data){
            var relevanceArray = [];
            var regionArray = [];

            for (var key in data) {
            relevanceArray.push(data[key].relevance);
            regionArray.push(data[key].region);
            }


            var ctx = document.getElementById("chart").getContext('2d');
            ctx.innerHTML = "";
            var myChart = new Chart(ctx, {
            type: 'line',
            data: {
            labels: regionArray,
            datasets: 
            [{
            label: 'Relevance',
            data: relevanceArray,
            bbackgroundColor: 'transparent',
            borderColor:'rgba(255,99,132)',
            borderWidth: 3
            },

            {
            label: 'Region',
            data: regionArray,
            backgroundColor: 'transparent',
            borderColor:'rgba(0,255,255)',
            borderWidth: 3	
            }]
            },

            options: {
            scales: {scales:{yAxes: [{beginAtZero: false}], xAxes: [{autoskip: true, maxTicketsLimit: 20}]}},
            tooltips:{mode: 'index'},
            legend:{display: true, position: 'top', labels: {fontColor: 'rgb(255,255,255)', fontSize: 16}}
            }
            });



          
        },
        error: function(xhr, status, error) {
    console.log("AJAX Error:", error);
  }

        });
  });

});


// code for pie chart

google.charts.load('current', {'packages':['corechart']});
// Draw the pie chart when Charts is loaded.
google.charts.setOnLoadCallback(draw_my_chart);
// Callback that draws the pie chart
function draw_my_chart() {
// Create the data table .
var data = new google.visualization.DataTable();
data.addColumn('string', 'language');
data.addColumn('number', 'Nos');
for(i = 0; i < my_2d.length; i++)
data.addRow([my_2d[i][0], parseInt(my_2d[i][1])]);
// above row adds the JavaScript two dimensional array data into required chart format
var options = {
title: 'Year and Intensity Pie Chart',
width: '100%',
height: 500,
chartArea: {
width: '50%',
height: '80%'
},
titleTextStyle: {
textAlign: 'center'
},
backgroundColor: 'white'
};

// Instantiate and draw the chart
var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
chart.draw(data, options);
}

$("#multiple").select2({
          placeholder: " Select Likelihood",
          allowClear: true
      });

      $("#multiples").select2({
          placeholder: " Select Relevance",
          allowClear: true
      });


// jQuery for cart 3

var ctx = document.getElementById("chart").getContext('2d');
    			var myChart = new Chart(ctx, {
        		type: 'line',
		        data: {
		            labels: [<?php echo $data2; ?>],
		            datasets: 
		            [{
		                label: 'Relevance',
		                data: [<?php echo $data1; ?>],
		                bbackgroundColor: 'transparent',
		                borderColor:'rgba(255,99,132)',
		                borderWidth: 3
		            },

		            {
		            	label: 'Region',
		                data: [<?php echo $data2; ?>],
		                backgroundColor: 'transparent',
		                borderColor:'rgba(0,255,255)',
		                borderWidth: 3	
		            }]
		        },
		     
		        options: {
		            scales: {scales:{yAxes: [{beginAtZero: false}], xAxes: [{autoskip: true, maxTicketsLimit: 20}]}},
		            tooltips:{mode: 'index'},
		            legend:{display: true, position: 'top', labels: {fontColor: 'rgb(255,255,255)', fontSize: 16}}
		        }
		    });

</script>
<!--Style Switcher -->
<script src="../plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>


</body>

</html>
