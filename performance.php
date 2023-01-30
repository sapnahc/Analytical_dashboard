
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="style.css">
    <title>Chart</title>
</head>

<body >
    <div class="chrt">
        <div class="chrt_dt">
            <!-- changes - Added HC logo-->
            <div class="hc_logo">
                <img src="https://s.hcurvecdn.com/hc_logo.png" alt="logo" />
            </div>
            <div class="title_bar">
                <h4>PERFORMANCE TREND</h4>
                <div class="dt_box">
                    <form class="dt_sel" method="post">
                        <label>From</label>
                        <input type="date" name="fdate" />
                        <label>to</label>
                        <input type="date"  name="ldate"/>
                    <!--</div>-->
                    <button  class="submit_btn" name="submit">
                        <i  class="fa-solid fa-arrow-right"></i>
                    </button>    
                    </form>
                </div>
            </div>
     
            <div class="chrt_r_2">
         
            <div class="chrt_wk">
                <h4>Overall</h4>
                
                <div class="week_graph">
                    <canvas id="weekChart"></canvas>
                </div>
            </div>
            <div class="chrt_wk">
                <h4>Device</h4>
                <form method="post" name="devf">
                  <label for="device">Select Device:</label>
                  <select name="dev" id="dev">
                  <?php
                    for($i=0;$i<=count($dev)-1;$i++)
                    {
                        if($i==0)
                        {
                            echo '<option value="'.$dev[$i].'"  selected>'.$dev[$i].'</option>';
                        }
                        else{
                            echo '<option  value="'.$dev[$i].'">'.$dev[$i].'</option>';
                        }
                      
                   
                    } 
                   
                    
                  ?>      
                    
                    
                  </select>
                  <input type="submit" name="devs" id="devs" style="display:none">
                  <?php  
                    
                        echo $_POST['dev'];
                    
                    ?>
                </form>
                <div class="dev_graph">
                    <canvas id="devChart"></canvas>
                </div>
            </div>
        </div>
        <div class="chrt_r_3">
            <div class="chrt_wk">
                <h4>Dimension</h4>
                <div class="dim_graph">
                    <canvas id="dimChart"></canvas>
                </div>
            </div>
            
        </div>
        </div>
        
    </div>
   <script src="https://cdn.jsdelivr.net/npm/chart.js@3.8.2/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.1.0/chartjs-plugin-datalabels.min.js"
      integrity="sha512-Tfw6etYMUhL4RTki37niav99C6OHwMDB2iBT5S5piyHO+ltK2YX8Hjy9TXxhE1Gm/TmAV0uaykSpnHKFIAif/A=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    ></script>
    <script src="https://cdn.jsdelivr.net/gh/emn178/chartjs-plugin-labels/src/chartjs-plugin-labels.js"></script>


</body>

</html>
<script>
    var res = document.getElementById("dev").value;
    alert(res);
</script>
<?php
$servername = "localhost";
$username = "publishe_data";
$password = "analytichc1";
$dbname = "publishe_analytic";
$url = $_SERVER['REQUEST_URI'];
    $url_components = parse_url($url);
    parse_str($url_components['query'], $params);
    $client = $params['client'];

$conn = new mysqli($servername, $username, $password,$dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    echo "Fail";
}
else{
    date_default_timezone_set('Asia/Kolkata');
$date1 = date('Y-m-d',strtotime("-1 days"));
$date2 = date('Y-m-d',strtotime("-2 days"));
$week1 = date('Y-m-d',strtotime("-7 days"));
$week1 = date('Y-m-d',strtotime("-14 days"));
$lastmn = date("Y-m-d",strtotime("-1 month"));
$lastmnf = date('Y-m-d', strtotime(date('Y-m-01'). ' -1 MONTH'));
$currmn = date("Y-m-d",strtotime("1 month"));
$currmnf = date('Y-m-d', strtotime(date('Y-m-01'). ' 1 MONTH'));
echo '<script>document.writeln(res)</script>';


    $sql = "select day,date,sum(clicks) as clicks,sum(imp)as imp from analytics where client = '$client' and date = '$date1' group by date";
    $result = $conn->query($sql);
    // echo $sql;
    while ($row = $result->fetch_assoc())
    {
       
        $imp = $row['imp'];
        // $dist[] = round((($row['imp'])/(array_sum($imp)))*100,2);
         $ctr = number_format(($row['clicks']/$row['imp'])*100,2);
       
    }
    $sql1 = "select day,date,sum(clicks) as clicks,sum(imp)as imp from analytics where client = '$client' and date = '$date2' group by date";
    $result1 = $conn->query($sql1);
    while ($row = $result1->fetch_assoc())
    {
       
        $imp1 = $row['imp'];
        // $dist[] = round((($row['imp'])/(array_sum($imp)))*100,2);
         $ctr1 = number_format(($row['clicks']/$row['imp'])*100,2);
       
    }
   
    $week = "select sum(clicks) as clicks,sum(imp)as imp from analytics where client = '$client' and date between '$week1' and '$date1' group by date ";
    $resultw = $conn->query($week);
    while ($row = $resultw->fetch_assoc())
    {
       
        $impw = $row['imp'];
        // $dist[] = round((($row['imp'])/(array_sum($imp)))*100,2);
         $ctrw = number_format(($row['clicks']/$row['imp'])*100,2);
        //  echo  $row['imp'];
       
    }
    $wek2 = "select sum(clicks) as clicks,sum(imp)as imp from analytics where client = '$client' and date between '$week2' and '$date2' group by date";
    $resultw2 = $conn->query($wek2);
    while ($row = $resultw2->fetch_assoc())
    {
       
        $impw1 = $row['imp'];
        // $dist[] = round((($row['imp'])/(array_sum($imp)))*100,2);
         $ctrw1 = number_format(($row['clicks']/$row['imp'])*100,2);
       
    }
    
    // month
    $lmonth = "select sum(clicks) as clicks,sum(imp)as imp from analytics where client = '$client' and date between '$lastmnf' and '$lastmn' group by date ";
    $resultlm = $conn->query($lmonth);
    while ($row = $resultlm->fetch_assoc())
    {
       
        $implm = $row['imp'];
         $ctrlm = number_format(($row['clicks']/$row['imp'])*100,2);
       
    }
    $cmonth = "select sum(clicks) as clicks,sum(imp)as imp from analytics where client = '$client' and date between '$currmnf' and '$currmn' group by date ";
    $resultcm = $conn->query($cmonth);
    while ($row = $resultlm->fetch_assoc())
    {
       
        $impcm = $row['imp'];
         $ctrcm = number_format(($row['clicks']/$row['imp'])*100,2);
       
    }
    // day
    $impp = number_format((($imp-$imp1)/$imp)*100,2);
    $ctrr = number_format((($ctr-$ctr1)/$ctr)*100,2);

    
    // week
    $impp1 = number_format((($impw-$impw1)/$impw)*100,2);
    $ctrr1 = number_format((($ctrw-$ctrw1)/$ctrw)*100,2);
    
    // month
    $imppm = number_format((($implm-$impcm)/$implm)*100,2);
    $ctrrm = number_format((($ctrlm-$ctrcm)/$ctrlm)*100,2);
    
    $impper = [$impp,$impp1,$imppm];
    $ctrper = [$ctrr,$ctrr1,$ctrrm];
    
    
    // Device
    $dsql = "select day,date,sum(clicks) as clicks,sum(imp)as imp from analytics where client = '$client' and date = '$date1' and device='mobile' group by device";
    $dresult = $conn->query($dsql);
    // echo $sql;
    while ($row = $dresult->fetch_assoc())
    {
       
        $dimp = $row['imp'];
        // $dist[] = round((($row['imp'])/(array_sum($imp)))*100,2);
         $dctr = number_format(($row['clicks']/$row['imp'])*100,2);
       
    }
    $dsql1 = "select day,date,sum(clicks) as clicks,sum(imp)as imp from analytics where client = '$client' and date = '$date2' and device='mobile'  group by device";
    $dresult1 = $conn->query($dsql1);
    while ($row = $dresult1->fetch_assoc())
    {
       
        $dimp1 = $row['imp'];
         $dctr1 = number_format(($row['clicks']/$row['imp'])*100,2);
       
    }
   
    $dweek = "select device,sum(clicks) as clicks,sum(imp)as imp from analytics where client = '$client' and date between '$week1' and '$date1' group by device";
    $dresultw = $conn->query($dweek);
    while ($row = $dresultw->fetch_assoc())
    {
       
        $dimpw = $row['imp'];
        $dev[] = $row['device'];
        $dctrw = number_format(($row['clicks']/$row['imp'])*100,2);
        //  echo  $row['imp'];
       
    }
    $dweek2 = "select sum(clicks) as clicks,sum(imp)as imp from analytics where client = '$client' and date between '$week2' and '$date2' group by device ";
    $dresultw2 = $conn->query($dweek2);
    while ($row = $dresultw2->fetch_assoc())
    {
       
        $dimpw1 = $row['imp'];
        // $dist[] = round((($row['imp'])/(array_sum($imp)))*100,2);
         $dctrw1 = number_format(($row['clicks']/$row['imp'])*100,2);
       
    }
    
    // month
    $dlmonth = "select sum(clicks) as clicks,sum(imp)as imp from analytics where client = '$client' and date between '$lastmnf' and '$lastmn'  group by device";
    $dresultlm = $conn->query($dlmonth);
    while ($row = $dresultlm->fetch_assoc())
    {
       
        $dimplm = $row['imp'];
         $dctrlm = number_format(($row['clicks']/$row['imp'])*100,2);
       
    }
    $dcmonth = "select sum(clicks) as clicks,sum(imp)as imp from analytics where client = '$client' and date between '$currmnf' and '$currmn' and device='mobile'  group by device ";
    $dresultcm = $conn->query($dcmonth);
    while ($row = $dresultlm->fetch_assoc())
    {
       
        $dimpcm = $row['imp'];
         $dctrcm = number_format(($row['clicks']/$row['imp'])*100,2);
       
    }
    // day
    $dimpp = number_format((($dimp-$dimp1)/$dimp)*100,2);
    $dctrr = number_format((($dctr-$dctr1)/$dctr)*100,2);

    
    // week
    $dimpp1 = number_format((($dimpw-$dimpw1)/$dimpw)*100,2);
    $dctrr1 = number_format((($dctrw-$dctrw1)/$dctrw)*100,2);
    
    // month
    $dimppm = number_format((($dimplm-$dimpcm)/$dimplm)*100,2);
    $dctrrm = number_format((($dctrlm-$dctrcm)/$dctrlm)*100,2);
    
    $dimpper = [$dimpp,$dimpp1,$dimppm];
    $dctrper = [$dctrr,$dctrr1,$dctrrm];
    
   // Dim
    $disql = "select day,dimension,date,sum(clicks) as clicks,sum(imp)as imp from analytics where client = '$client' and date = '$date1'  group by dimension";
    $diresult = $conn->query($disql);
    while ($row = $diresult->fetch_assoc())
    {
        $diimp = $row['imp'];
         $dictr = number_format(($row['clicks']/$row['imp'])*100,2);
    }
   
     $disql1 = "select day,dimension,date,sum(clicks) as clicks,sum(imp)as imp from analytics where client = '$client' and date = '$date2' group by dimension";
    $diresult1 = $conn->query($disql1);
    while ($row = $diresult1->fetch_assoc())
    {
       
        $diimp1 = $row['imp'];
         $dictr1 = number_format(($row['clicks']/$row['imp'])*100,2);
       
    } 
    
    $diweek = "select dimension,sum(clicks) as clicks,sum(imp)as imp from analytics where client = '$client' and date between '$week1' and '$date1'  group by dimension";
    $diresultw = $conn->query($diweek);
    while ($row = $diresultw->fetch_assoc())
    {
       
        $diimpw = $row['imp'];
        // $dist[] = round((($row['imp'])/(array_sum($imp)))*100,2);
         $dictrw = number_format(($row['clicks']/$row['imp'])*100,2);
        //  echo  $row['imp'];
       
    }
    $dimw2 = "select dimension,sum(clicks) as clicks,sum(imp)as imp from analytics where client = '$client' and date between '$week2' and '$date2' group by dimension ";
    $res2 = $conn->query($dimw2);
    while ($row = $res2->fetch_assoc())
    {
       
        $diimpw1 = $row['imp'];
      
         $dictrw1 = number_format(($row['clicks']/$row['imp'])*100,2);
       
    }
    // day
    $diimpp = number_format((($diimp-$diimp1)/$diimp)*100,2);
    $dictrr = number_format((($dictr-$dictr1)/$dictr)*100,2);

    
    // week
    $diimpp1 = number_format((($diimpw-$diimpw1)/$diimpw)*100,2);
    $dictrr1 = number_format((($dictrw-$dictrw1)/$dictrw)*100,2);
    
    // // month
    // $dimppm = number_format((($dimplm-$dimpcm)/$dimplm)*100,2);
    // $dctrrm = number_format((($dctrlm-$dctrcm)/$dctrlm)*100,2);
    
    $diimpper = [$diimpp,$diimpp1];
    $dictrper = [$dictrr,$dictrr1];
   
}    


?>
<script>
// window.onload=function(){
//   document.getElementById("devs").click();
// };    
// function check(){
//   window.removeEventListener("onload", check);
//   document.forms['devf'].submit();
// } 
// window.onload = (event) => {
//     console.log('The page has fully loaded');
//     document.getElementById("devs").click();
//     event.preventdefault();
// };
// window.onload = check;
//  window.onload = function(){
//     if (!sessionStorage.getItem("submitted")) {
//         console.log("submitting");
//         document.getElementById("devs").click();
//         sessionStorage.setItem("submitted", "true");
        
//     } else {
//         console.log("already submitted, not repeating");
//         sessionStorage.removeItem("submitted");
//     }
// }


const weekctx = document.getElementById("weekChart").getContext("2d");
const devctx = document.getElementById("devChart").getContext("2d");
const dimctx = document.getElementById("dimChart").getContext("2d");

// Alignment of x-axis label in bar
const labelDataAlign = {
  id: "labelDataAlign",
  afterDatasetsDraw(chart, args, options) {
    const { ctx } = chart;

    for (let i = 0; i < chart.config.data.labels.length; i++) {
      const yPosition = 10;
      const xPosition = chart.getDatasetMeta(0).data[i].y + 3;
      ctx.save();
      ctx.font = "bold 12px Roboto";
      ctx.fillText(chart.config.data.labels[i], yPosition, xPosition);
    }
  },
};

// scroll chart
const scrollchart1 = {
  id: "scrollchart",
  afterDatasetsDraw(chart, args, pluginOptions) {
    if (chart.config._config.data.labels.length > 8) {
      cre_gh.style.height = chart.config._config.data.labels.length * 45 + "px";
      chart.update();
    }else{
      cre_gh.style.height = "100%";
      chart.update();
    }
    if(window.matchMedia("(max-width: 600px)").matches){
      cre_gh.style.width = 680 + "px";
      chart.update();
    }else{
      cre_gh.style.width = "100%";
      chart.update();
    }
  },
}
var data = {
  labels: ["DayOfDay", "WeekOfWeek", "MonthOfMonth"],
  datasets: [{
    label: "IMP",
    backgroundColor: "blue",
    data: <?php echo json_encode($impper); ?>
  }, {
    label: "CTR",
    backgroundColor: "red",
    data: <?php echo json_encode($ctrper); ?>
  }]
};

var myBarChart = new Chart( weekctx, {
  type: 'bar',
  data: data,
  options: {
    barValueSpacing: 20,
    scales: {
      yAxes: [{
        ticks: {
          min: 0,
        }
      }]
    }
  }
});


var data1 = {
  labels: ["DayOfDay", "WeekOfWeek", "MonthOfMonth"],
  datasets: [{
    label: "IMP",
    backgroundColor: "blue",
    data: <?php echo json_encode($dimpper); ?>
  }, {
    label: "CTR",
    backgroundColor: "red",
    data: <?php echo json_encode($dctrper); ?>
  }]
};

var myBarChart1 = new Chart( devctx, {
  type: 'bar',
  data: data1,
  options: {
    barValueSpacing: 20,
    scales: {
      yAxes: [{
        ticks: {
          min: 0,
        }
      }]
    }
  }
});

var datadim = {
  labels: ["DayOfDay", "WeekOfWeek", "MonthOfMonth"],
  datasets: [{
    label: "IMP",
    backgroundColor: "blue",
    data: <?php echo json_encode($diimpper); ?>
  }, {
    label: "CTR",
    backgroundColor: "red",
    data: <?php echo json_encode($dictrper); ?>
  }]
};

var dimChart = new Chart( dimctx, {
  type: 'bar',
  data: datadim,
  options: {
    barValueSpacing: 20,
    scales: {
      yAxes: [{
        ticks: {
          min: 0,
        }
      }]
    }
  }
});

</script>