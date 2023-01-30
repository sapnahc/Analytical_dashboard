<?php
$servername = "localhost";
$username = "publishe_data";
$password = "analytichc1";
$dbname = "publishe_analytic";

// Create connection
$url = $_SERVER['REQUEST_URI'];
    $url_components = parse_url($url);
    parse_str($url_components['query'], $params);
    if (strpos($params['client'],","))
    {
    $value = explode(",",$params['client']);
    $client = $value[0];
    $column = $value[1];
    }
    else
    {
        $client = $params['client'];
    }

$conn = new mysqli($servername, $username, $password,$dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    echo "Fail";
}
else{
//  $replace ="SELECT REPLACE(GROUP_CONCAT(COLUMN_NAME), 'client,imp,clicks,ctr,date,','')
//                 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$client'
//                 AND TABLE_SCHEMA = 'publishe_analytic'";
// $replace = "select distinct campaign from $client";
//     $replaced = $conn->query($replace);
//     $row = $replaced->fetch_assoc();
//     print_r($row);
//     foreach($row as $x => $x_value)
//     {
     
//       $name = $x_value;  
//       $eventc = $name;
      
//     } 


$sql = "select distinct campaign from $client";
$result = $conn->query($sql);

    if(isset($_POST["submit"]))
    {
        if(!empty($_POST["fcol"]))
        {
            echo $_POST["fcol"];
             header("Location: testp.php?client=".$client.",".$_POST["fcol"]);
            die();
        }
        else
        {
            echo "Please select any value";
        }
    }
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Analytic</title>

<style>
  #loading {
top:0px;
left:0px;
  position: fixed;
  z-index: 100;
  width: 100%;
  height: 100%;
  background-color: transparent;
  background-image: url("https://i.stack.imgur.com/MnyxU.gif");
  background-repeat: no-repeat;
  background-position: center;
  display:none;
} 
@font-face {
        font-family: 'Roboto';
        src: url('https://hcurvecdn.com/fonts/Roboto-Bold.woff2') format("truetype");
    
    }

 img {
  width: 10%;
  float:right;
  
}
/*.client_name {*/
/*  text-align: center;*/
/*  font-size: 16px;*/
/*}*/
h2{
    width:100%;
    text-align:center;
    margin-top: -11px;
    margin-left: 130px;
}
.submit_btn {
  width: 67px;
  height: 31px;
  padding: 2px;
  border: none;
  background-color:#2098f5;
  color:white;
  border-radius: 5px;
  box-shadow: 1px 1px 4px rgba(0, 0, 0, 0.4);
  cursor: pointer;
  /* display: flex; */
font-size: 15px;
  justify-content: center;
  align-items: center;
  margin-left: 10px;
}


.dt_sel label {
  color: rgba(0, 0, 0, 0.5);
  font-size: 0.86rem;
}

.dt_sel select {
  border: none;
}
.dt_sel {
    width: 300px;
  display: flex;
  justify-content: space-evenly;
  align-items: center;
}

.chrt_dt {
  width: 95%;
  height: 100%;
}

.chrt_dt {
  background-color: #fff;
  border: none;
  -webkit-box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5);
  box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5);
  padding: 20px;
  margin: 10px 0;
  border-radius: 10px;
}
.title_bar{
    font-size:20px;
}




@media only screen and (max-width: 750px){
    .chrt_dt {
    width: 88%;
    height: auto;
  }
   
/*.hc_logo{*/
/*      display: flex;*/
      /*justify-content: center;*/
/*      height:12%;*/
/*  }*/
  
  .hc_logo img {
    width: 14%;
    /*margin: 0 0 10px;*/
    /*height:16%;*/
  }
    
  .dt_sel {
     width: 100%;
     align-items: center;
     justify-content: space-evenly;
}
.dt_sel label {
    color: rgba(0, 0, 0, 0.5);
    font-size: 0.7rem;
  }
h2{
  
    margin-top: 0px;
    font-size: 14px;
    width: 100%;
    margin-left: 61px;
    text-align: center;
}
.title_bar{
font-size: 14px;
    margin-top: 0px;
    width: 100%;
    margin-left: 37px;
    text-align: center;
}
}
</style>
    </head>
    <body >
            <div id="loading" ><span style="width:100%;left:0px;margin-top:180px;text-align:center;font-size:25px;">Please wait! While Your data is loading</span></div>
    <div class="chrt_dt" id="divToExport">
        <div class="hc_logo" style="height:10% float:right">
                <img src="https://s.hcurvecdn.com/hc_logo.png" alt="logo">
                </div>
            
   <div class="client_name"> <h2><?php 
        $sql="SELECT cname FROM `clientname` WHERE dbname = '$client' LIMIT 1";
                $row = mysqli_fetch_assoc($conn->query($sql) );
                echo $row['cname']?> Campaign Reports</h2>
        <div style=" display: flex;justify-content: center;">
         <div class="title_bar">Select the Report you want to see </div></div>
                </div>
                
         <div style=" display: flex;justify-content: center;margin-top:4px;"><form method="post" >
           <!--<label>Select The Report You Want To See : </label> -->
            <select name="fcol" id="fcol" style="border:2px solid #24b2be;width:120px;height:30px; border-radius:5px" class="fcol">
                        <option value="">Select Report</option>
                    <?php

                        while ($row = $result->fetch_assoc())
                            {
                                echo '<option value="'.$row["campaign"].'">'.$row["campaign"].'</option>';
                            }     
                    ?>
        </select>
        <button  class="submit_btn"  name="submit" onclick="loader()">Submit</button>
    
        </form></div>
    </div>
    </body>
</html>
<script>
    function loader(){
        document.getElementById("loading").style.display="flex";
    }
</script>