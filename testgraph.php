<?php
$servername = "localhost";
$username = "publishe_data";
$password = "analytichc1";
$dbname = "publishe_analytic";

// Create connection
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
    //  echo "success";
    $replace ="SELECT REPLACE(GROUP_CONCAT(COLUMN_NAME), 'mainclient,imp,clicks,ctr,date,','')
                FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'analytics'
                AND TABLE_SCHEMA = 'publishe_analytic'";
    $replaced = $conn->query($replace);
    $row = $replaced->fetch_assoc();
    // print_r($row);
    foreach($row as $x => $x_value)
    {
     
      $name = $x_value;  
      $eventc = explode (",", $name);
      
    } 
    if(isset($_POST["submit"]) ||isset($_POST["submit1"]))
    {
        if(!empty($_POST["fdate"]))
        {
            $fromdate = $_POST["fdate"];
        }
        if(!empty($_POST["ldate"]))
        {
            $todate = $_POST["ldate"];
        }
      
  
      
      $column = $_POST["fcol"];
        // echo $column;
   
   if(empty($fromdate)&&empty($todate)&&!empty($column))
   {
       
       $sql = "select sum(clicks) as clicks,sum(imp)as imp,$column from $client group by $column  ";
       
            // echo $sql;

   }
   else if(!empty($fromdate)&&!empty($todate)&&!empty($column))
   {
       $sql = "select sum(clicks) as clicks,sum(imp)as imp,$column from $client where date between '$fromdate' and '$todate' group by $column";
    //   echo $sql;
   }
   else if(!empty($fromdate)&&!empty($todate)&&empty($column))
   {
       $sql = "select date,sum(clicks) as clicks,sum(imp)as imp,dimension from $client where date between '$fromdate' and '$todate' group by date  ";
    //   echo $sql;
   }
   else
   {
       $sql = "select date,sum(clicks) as clicks,sum(imp)as imp,dimension from $client where date between '$fromdate' and '$todate' group by date  ";
    //   echo $sql;
   }
   
//   date
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc())
    {
       
        $imp[] = $row['imp'];
        // $dist[] = round((($row['imp'])/(array_sum($imp)))*100,2);
         $ctr[] = number_format(($row['clicks']/$row['imp'])*100,2);
        
        $clicks[] = $row['clicks'];
        if(!empty($column))
        {
            if(strlen($row[$column]) > 20)
            {
                 $dyn[] = substr($row[$column],0,20)."...";
            }
            else
            {
                $dyn[] = $row[$column];
            }
            
        }
        else
        {
            $datee[] = date("M d", strtotime($row['date']));
            
        }
           
        
        // echo $row['date'];
    }
    // print_r($datee);
    $impcheck = array_sum($imp);
    //   dim

     
     
      $perct = (int)(array_sum($imp)*0.10);
      $fifty = (int)(array_sum($imp)*0.50);
     foreach ($imp as $value)
    {
        $dist[] = number_format(($value/$impcheck)*100,2);
    }
    }
else{
     
     
    //   date chart

    $sql = "select date,sum(clicks) as clicks,sum(imp)as imp from $client group by date order by date desc limit 10  ";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc())
    {
        
        $day[] = substr($row['day'],0,3);
        $imp[] = $row['imp'];
        //  $sum = array_sum($imp);
       
        // echo ($row['imp']/array_sum($imp)*100)."<br>";
        $ctr[] = number_format(($row['clicks']/$row['imp'])*100,2);
       $datee[] = date("M d", strtotime($row['date']));
        $clicks[] = $row['clicks'];
    }

    $fromdate = $datee[0];
        $todate = $datee[9];
    // print_r($dist);    
    $impcheck = array_sum($imp);
    
  
     $perct = (int)(array_sum($imp)*0.10);
     $fifty = (int)(array_sum($imp)*0.50);
    foreach ($imp as $value)
    {
        $dist[] = number_format(($value/$impcheck)*100,2);
    }
   
   
   
    }   
   

}

$original = array("voot", "nbavoot");
$replacee   = array("Voot AVOD - Road Safety World Series Season 2 - Moment Marketing - English", " Voot AVOD - Road Safety World Series Season 2 - Moment Marketing - Hindi");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.8.2/dist/chart.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" >
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    <script  src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.1.0/chartjs-plugin-datalabels.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/emn178/chartjs-plugin-labels/src/chartjs-plugin-labels.js"></script>
    <link rel="stylesheet" href="stylep.css">
    <title>Chart</title>
</head>

<body>
    <div class="chrt">
        <div class="chrt_dt" id="divToExport">
            <div class="hc_logo">
                <img src="https://s.hcurvecdn.com/hc_logo.png" alt="logo" />
            </div>
            <?php
                if($client == "voot")
                {
                    if(!empty($fromdate)&&!empty($todate))
                    {
                        echo '<div class="client_name" style="text-transform:capitalize">Voot AVOD - Road Safety World Series Season 2 - Moment Marketing - English <br>'.date("M j, Y", strtotime($fromdate)).' to '.date("M j, Y", strtotime($todate)).'<br>'.$campaign.'</div><br>';
                    }
                    else
                    {
                        echo '<div class="client_name" style="text-transform:capitalize">Voot AVOD - Road Safety World Series Season 2 - Moment Marketing - English'.'<br>'.$campaign.'</div><br>';
                        
                    }
                    
                }
                elseif($client == "nbavoot")
                {
                    if(!empty($fromdate)&&!empty($todate))
                    {
                        echo '<div class="client_name" style="text-transform:capitalize">Voot AVOD - Road Safety World Series Season 2 - Moment Marketing - Hindi <br>'.date("M j, Y", strtotime($fromdate)).' to '.date("M j, Y", strtotime($todate)).'<br>'.$campaign.'</div><br>';
                    }
                    else
                    {
                        echo '<div class="client_name" style="text-transform:capitalize">Voot AVOD - Road Safety World Series Season 2 - Moment Marketing - Hindi'.'<br>'.date("M j, Y", strtotime($fromdate)).' to '.date("M j, Y", strtotime($todate)).'</div><br>';
                    }
                    
                }
                else{
                    if(!empty($fromdate)&&!empty($todate))
                    {
                        echo '<div class="client_name" style="text-transform:capitalize">'.$client.'<br>'.date("M j, Y", strtotime($fromdate)).' to '.date("M j, Y", strtotime($todate)).'<br>'.$campaign.'</div><br>';
                    }
                    else
                    {
                        if(!empty($fromdate))
                        {
                        echo '<div class="client_name" style="text-transform:capitalize">'.$client.'<br>'.date("M j, Y", strtotime($fromdate)).' to '.date("M j, Y", strtotime($todate)).'</div><br>';
                        }
                        else{
                            echo '<div class="client_name" style="text-transform:capitalize">'.$client.'<br></div><br>';
                        }
                        
                    }
                    
                }
            ?>
            <div class="title_bar">
                <h4>PERFORMANCE TREND</h4>
                
                <div class="dt_box">
                    <form class="dt_sel" method="post">

                        <label>From</label>
                        <input type="date" id="fdate" name="fdate" />
                        <label><div id="to">to</div></label>
                        <input type="date" id="ldate"  name="ldate"/>
                    
                        <button  class="submit_btn" name="submit">
                        <!-- <i  class="fa-solid fa-arrow-right"></i> -->
                        Go
                    </button>
           
                </div>
            </div>
             <div class="chrt_scale">
        <div class="chrt_score_details">
          <div class="imp_score">
            <h4>IMPRESSION</h4>
            <span><?php echo number_format((array_sum($imp)))?></span>
          </div>
          <span class="vl"></span>
          <div class="ctr_score">
            <h4>CTR</h4>
            
            <span><?php echo number_format((array_sum($clicks)/$impcheck)*100,2)."%" ?></span>
          </div>
          <span class="vl"></span>
          <div class="click_score">
            <h4>CLICKS</h4>
            
            <span><?php echo number_format((array_sum($clicks))) ?></span>
          </div>
        </div>
        <div class="chrt_scale_clr">
          <div class="imp_clr">
            <h4>IMPRESSION</h4>
          </div>
          <div class="ctr_clr">
            <h4>CTR</h4>
          </div>
          <div class="dist_clr">
            <h4>DISTRIBUTION</h4>
          </div>
        </div>
         
      </div>
      <div class="swiper_container">
        <div class="swiper mySwiper">
          <div class="swiper-wrapper" id="wrapper">
     
          </div>
          <div class="swiper-pagination"></div>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
      </div>
        <div class="chrt_r_2" >
        <div class="chrt_d">
            <h4 style="text-align:center"><?php echo ucfirst($column)?> Wise Performance</h4>
                <div class="dt_sel" style="justify-content: flex-start;">
      <label>Select Cohort : </label>
        <select name="fcol" id="fcol" style="border:1px solid black" class="fcol">
                        <option value="">Select Column</option>
                    <?php

                        foreach($eventc as $colname)
                        {
                                echo '<option value="'.$colname.'">'.$colname.'</option>';
                            }    
                    ?>
        </select>
        <button  class="submit_btn"  name="submit1">Submit</button>
        
        </div>
        
            
            <div class="scroll_chart">
            <div class="dte_graph cre_graph dy_graph">
              <canvas id="dteChart"></canvas>
            </div>
            </div>
        </div>
       
        </div>
         </form>
        </div>
    </div>
        </div>    
    </div>
</body>
</html>
<script>
 var swiper = new Swiper(".mySwiper", {
      spaceBetween: 20, pagination: {
        el: ".swiper-pagination",
        type: "fraction",
      },
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
    });
  
//     fetch('https://opensheet.elk.sh/1sXU3XkmEHV99v91s2n1SRxsIn97_HANjurUMJAVJxBQ/1')
//     .then(function(response) {
//         return response.json();
//       })
//       .then(function(myJson) {
//           console.log(myJson.length);
//         for(var i = 0;i<=myJson.length;i++)
//         {
//             if(myJson[i]['client'] == "amazonp" && myJson[i]['status'] !="disable")
//             {
//             var el = document.createElement('div');
//             el.className = "swiper-slide";
//             el.id = "swiper-slide"+i;
//             if(myJson[i]['important'] == "highlight")
//             {
//                 el.style = "border:4px solid red";
//             }
            
//             document.getElementById("wrapper").appendChild(el)
//             var e2 = document.createElement('h3');
//             e2.innerHTML = "HC Recommendation"
//             document.getElementById("swiper-slide"+i).appendChild(e2)
//             var e3 = document.createElement('div');
//             e3.className = "swip_card";
//             e3.innerHTML = myJson[i]['recommendation'];
//             document.getElementById("swiper-slide"+i).appendChild(e3)
            
//             }
//         }
   
// });
</script> 
<?php 
if(!empty($dyn))
{
?>
<script>
  var dyn = <?php echo json_encode($dyn) ?>;
  var imp = <?php echo json_encode($imp) ?>;
  var checkv ="";
   fetch('https://opensheet.elk.sh/11ZzYWfudJ2jgECrPbUDwht183PE3VMR3_Uvf5XG7rzQ/benchmarkdim')
    .then(function(response) {
        return response.json();
      })
      .then(function(myJson) {
        for(var i = 0;i<dyn.length;i++)
        {
            var el = document.createElement('div');
            el.className = "swiper-slide";
            el.id = "swiper-slide"+i;
            document.getElementById("wrapper").appendChild(el)
            var e2 = document.createElement('h3');
            // e2.innerHTML = "HC Recommendation";
            document.getElementById("swiper-slide"+i).appendChild(e2)
            var e3 = document.createElement('div');
            e3.className = "swip_card";
            // e3.innerHTML = myJson[i]['recommendation'];
            document.getElementById("swiper-slide"+i).appendChild(e3)
            checkv = myJson.findIndex(obj => obj.dim==dyn[i]);
            if(imp[i] < myJson[checkv]['imp'])
            {
                console.log("Increase distribution on size "+dyn[i]);
                e3.innerHTML = "Increase distribution on size "+dyn[i];
            }
            else if(imp[i] > myJson[checkv]['imp'])
            {
                console.log("Size "+dyn[i]+" is performing well");
                e3.innerHTML = "Size "+dyn[i]+" is performing well";
            }
            // console.log(myJson[checkv]['imp']);
            
        }    
          
        for(var i = 0;i<=myJson.length;i++)
        {
            // console.log(myJson[i]['dim'])
            // if(myJson[i]['client'] == "amazonp" && myJson[i]['status'] !="disable")
            // {
            // var el = document.createElement('div');
            // el.className = "swiper-slide";
            // el.id = "swiper-slide"+i;
            // if(myJson[i]['important'] == "highlight")
            // {
            //     el.style = "border:4px solid red";
            // // }
            
            // document.getElementById("wrapper").appendChild(el)
            // var e2 = document.createElement('h3');
            // e2.innerHTML = "HC Recommendation"
            // document.getElementById("swiper-slide"+i).appendChild(e2)
            // var e3 = document.createElement('div');
            // e3.className = "swip_card";
            // e3.innerHTML = myJson[i]['recommendation'];
            // document.getElementById("swiper-slide"+i).appendChild(e3)
            
            // }
        }
   
});
</script>
<?php 
}
?>
<script>
    const datectx = document.getElementById("dteChart").getContext("2d");


const cre_gh = document.querySelector(".cre_graph");



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
var scrollchart = {
  id: scrollchart,
  afterDatasetsDraw(chart, args, pluginOptions) {
const cre_gh = document.querySelector(".cre_graph");
    if (chart.config._config.data.labels.length > 6) {
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

// barlength
const barLength = {
    id: "barLength",
    afterDatasetsDraw(chart, args, pluginOptions) {
    
    if(chart.config._config.data.datasets[2].label === "Impression"){
        if(chart.config._config.data.datasets[2].data.length < 2){
            chart.config._config.data.datasets[2].minBarLength = "100";
            chart.update();
        }else{
            chart.config._config.data.datasets[2].data.forEach(el => {
                if(el < 20){
                    chart.config._config.data.datasets[2].minBarLength = "100";
                    chart.update();
                }else if(el < 2000){
                    // console.log(chart.config._config.data.datasets[2].minBarLength)
                    chart.config._config.data.datasets[2].minBarLength = "200";
                    chart.update();
                }else{
                    // console.log(chart.config._config.data.datasets[2].minBarLength)
                    chart.config._config.data.datasets[2].minBarLength = "250";
                    chart.update();
                }
            });
        }
    }       
    }
}





<?php
    if(!empty($column))
    {
?>

const dateChart = new Chart(datectx, {
  type: "bar",
  data: {
    labels: <?php echo json_encode($dyn); ?>,
    datasets: [
      {
        label: "CTR",
        data: <?php echo json_encode($ctr); ?>,
        backgroundColor: [
          "#FBCA27",
          "#FBCA27",
          "#FBCA27",
          "#FBCA27",
          "#FBCA27",
          "#FBCA27",
        ],
        minBarLength: "250",
        barPercentage: 0.7,
        borderSkipped: false,
        hoverOffset: 4,
      },
      {
        label: "Distribution",
        data: <?php echo json_encode($dist); ?>,
        backgroundColor: [
          "#F47958",
          "#F47958",
          "#F47958",
          "#F47958",
          "#F47958",
          "#F47958",
        ],
        minBarLength: "320",
        barPercentage: 0.7,
        borderSkipped: false,
        hoverOffset: 4,
      },
      {
        label: "Impression",
        data: <?php echo json_encode($imp); ?>,
        backgroundColor: [
          "#29AFBA",
          "#29AFBA",
          "#29AFBA",
          "#29AFBA",
          "#29AFBA",
          "#29AFBA",
        ],
        minBarLength: "500",
        barPercentage: 0.7,
        borderSkipped: false,
        hoverOffset: 4,
      },
    ],
  },
  plugins: [ChartDataLabels, labelDataAlign,scrollchart],
  options: {
    responsive: true,
    maintainAspectRatio: false,
    indexAxis: "y",
    interaction: {
      mode: "index",
    },
    scales: {
      x: {
        display: false,
        stacked: true,
      },
      y: {
        display: false,
        stacked: true,
      },
    },
    plugins: {
      tooltip: {
        backgroundColor: "rgb(255,255,255)",
        titleColor: "rgb(0,0,0)",
        bodyColor: "rgb(0,0,0)",
        bodyFont: {
          weight: "bold",
        },
        borderWidth: 0.4,
        borderColor: "rgb(0,0,0)",
        callbacks: {
          label: (context) => {
            if(context.dataset.label === "Impression"){
              return context.parsed.x.toLocaleString('en-US');
            }
            if (context.dataset.label === "Distribution" || context.dataset.label === "CTR") {
              return context.dataset.label + ": " + context.parsed.x + "%";
            } else {
              return context.dataset.label + ": " + context.parsed.x;
            }
             
          },
        },
      },
      datalabels: {
        formatter: (value, context) => {
            if (context.dataset.label === "Impression") {
              return value.toLocaleString("en-US");
            }
            if (context.dataset.label === "Distribution"  || context.dataset.label === "CTR") {
              return value + "%";
            }
          },
        color: "#000",
        anchor: "end",
        align: "start",
        labels: {
          title: {
            font: {
              weight: "bold",
            },
          },
        },
      },
      legend: {
        display: false,
      },
    },
  },
});
<?php
}
else
{
?>
const dateChart = new Chart(datectx, {
  type: "line",
  data: {
    labels: <?php echo json_encode($datee); ?>,
    datasets: [
      {
        label: "Impression",
        data: <?php echo json_encode($imp); ?>,
        borderColor: "#29AFBA",
        pointColor: "#29AFBA",
        yAxisID: "yimp",
      },
      {
        label: "CTR",
        data: <?php echo json_encode($ctr); ?>,
        borderColor: "#FBCA27",
        pointColor: "#FBCA27",
        yAxisID: "yclk",
      },
      {
        label: "Distribution",
        data: <?php echo json_encode($dist); ?>,
        borderColor: "#F47958",
        pointColor: "#F47958",
        yAxisID: "ydist",
      },
    ],
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    tension: 0.4,
    interaction: {
      intersect: false,
      mode: "index",
    },
    scales: {
      x: {
        ticks: {
          font: {
            color: "#000",
            family: "Roboto",
            size: 14,
            weight: 500,
          },
        },
      },
      yimp: {
        display: false,
        position: "left",
        grid: {
          display: false,
        },
      },
      yclk: {
        display: false,
        position: "right",
        grid: {
          display: false,
        },
      },
      ydist: {
        display: false,
        max: 100,
        min: 0,
        ticks: {
          stepSize: 10,
        },
        position: "right",
        grid: {
          display: false,
        },
      },
    },
    plugins: {
      tooltip: {
        backgroundColor: "rgb(255,255,255)",
        titleColor: "rgb(0,0,0)",
        bodyColor: "rgb(0,0,0)",
        bodyFont: {
          weight: "bold",
        },
        borderWidth: 0.4,
        borderColor: "rgb(0,0,0)",
        callbacks: {
          label: (context) => {
            if(context.dataset.label === "Impression"){
              return context.parsed.y.toLocaleString('en-IN')
            }
            if (context.dataset.label === "Distribution") {
              return context.dataset.label + ": " + context.parsed.y + "%";
            } else {
              return context.dataset.label + ": " + context.parsed.y;
            }
          },
        },
      },
      legend: {
        display: false,
      },
    },
  },
});
<?php
}
?>


</script>
<script>
    var fd = <?php echo json_encode($fromdate) ?>;
    document.getElementById('fdate').value = fd;
    console.log(fd);
    var sd = <?php echo json_encode($todate) ?>;
    document.getElementById('ldate').value = sd;
    console.log(sd);
    var col = <?php echo json_encode($column) ?>;
    console.log(col);
    if(col != null )
    {
        document.getElementById('fcol').value = col;
    }
   
    
</script>

