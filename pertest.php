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
    $w1 = date('Y-m-d',strtotime("-8 days"));
    // echo $week1."-".$date1;
    
    $week2 = date('Y-m-d',strtotime("-14 days"));
 
    $lastmn = date("Y-m-d",strtotime("-1 month"));
    // $lastmnf = date('Y-m-d', strtotime($date1. ' -1 MONTH'));
    // echo $lastmn."-".$date1."<br>";
    $currmn = date("Y-m-d",strtotime("-2 month"));
    
    $currmnf = date('Y-m-d', strtotime($lastmn. '-1 days'));
// echo $currmn."-".$currmnf;
    $test1 = [$week1,$week2,$lastmn,$currmn];
    $test2 = [$date1,$w1,$date1,$currmnf];
   
    $sql = "select day,date,sum(clicks) as clicks,sum(imp)as imp from $client where date = '$date1' group by client";

    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc())
    {
        $imp = $row['imp'];
        $ctr = number_format(($row['clicks']/$row['imp'])*100,2);
    }
    $sql1 = "select day,date,sum(clicks) as clicks,sum(imp)as imp from $client where date = '$date2' group by client";

    $result1 = $conn->query($sql1);
    while ($row = $result1->fetch_assoc())
    {
        $imp1 = $row['imp'];
        $ctr1 = number_format(($row['clicks']/$row['imp'])*100,2);
    } 
    // echo "ctr ".$ctr."<br>";
    // echo "ctr1 ".$ctr1."<br>";
    for($i=0;$i<=count($test1)-1;$i++)
    {

        ${"$sqlo".$i} = "select sum(clicks) as clicks,sum(imp)as imp from $client date between '$test1[$i]' and '$test2[$i]' group by client";
        ${"result0".$i} = $conn->query(${"$sqlo".$i});
        while (${"row".$i} = ${"result0".$i}->fetch_assoc())
        {
            ${"impo".$i} = ${"row".$i}[imp];
            ${"ctro".$i} = number_format((${"row".$i}['clicks']/${"row".$i}['imp'])*100,2);
            
        }
       
    }
    
    $impp = number_format((($imp-$imp1)/$imp)*100,2);
    $ctrr = number_format((($ctr-$ctr1)/$ctr)*100,2);
    
    // week
    $oviw = number_format((($impo0-$impo1)/$impo0)*100,2);
    $ovcw = number_format((($ctro0-$ctro1)/$ctro0)*100,2);
    
    // month
    $ovim = number_format((($impo2-$impo3)/$impo2)*100,2);
    $ovcm = number_format((($ctro2-$ctro3)/$ctro2)*100,2);
    
    $overim = [$impp,$oviw,$ovim];
    $overct = [$ctrr,$ovcw,$ovcm];
    
    // print_r($overim);
    // print_r($overct);
    
}    
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="style2.css">
    <title>Chart</title>
</head>

<body >
    <div class="chrt">
        <div class="chrt_dt">
            <!-- changes - Added HC logo-->
            <div class="hc_logo">
                <img src="https://s.hcurvecdn.com/hc_logo.png" alt="logo" />
            </div>
            
            <div class="chrt_r_2">
         
            <div class="chrt_wk">
                <h4>Overall</h4>
                
                <div class="week_graph">
                    <canvas id="weekChart"></canvas>
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


const weekctx = document.getElementById("weekChart").getContext("2d");

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



var myBarChart = new Chart(weekctx, {
  type: 'bar',
  data: {
  labels: ["DayOfDay", "WeekOfWeek", "MonthOfMonth"],
  datasets: [{
    label: "IMP",
    backgroundColor: "#29AFBA",
    data: <?php echo json_encode($overim); ?>,
  }, {
    label: "CTR",
    backgroundColor: "#FBCA27",
    data: <?php echo json_encode($overct); ?>,
  }]
},
  options: {
    barValueSpacing: 20,
    scales: {
        y: {
          grid: {
              color: function(context) {
                  if(context.tick.value == 0){
                      return "rgb(0,0,0)"
                  }else {
                      return "rgba(0,0,0,0.1)"
                  }
          },
      },
  },
      yAxes: [{
        ticks: {
          min: 0,
        }
      }]
    }
  },
   plugins: {
        labels: {
          formatter: (value, context) => {
            if (context.data.label === "IMP") {
              return value + "%";
            }
            if (context.datas.label === "CTR") {
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
});





</script>
<?php
$replace ="SELECT REPLACE(GROUP_CONCAT(COLUMN_NAME), 'date,client,imp,clicks,ctr,','')
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
  array_shift($eventc)  ;
  
//   column data  query
 foreach($eventc as $y) 
  {
     ${"column".$y} = "select distinct $y from analytics where client = '$client' group by $y";

      ${"columnr".$y} = $conn->query(${"column".$y});
        while (${"row".$y} = ${"columnr".$y}->fetch_assoc())
        {
            ${"dyn".$y}[] = ${"row".$y}[$y];
            
        }
        

  } 
foreach($eventc as $value) 
  {
    //   echo $value."<br>";
    $ee = substr($value,-4);  
    if(isset($_POST[$ee]))
    {
        $tt = $_POST[$value];
    }
    else{
        $tt = ${"dyn".$value}[0];
    }
    // echo $tt;
        
      
      //   day
        ${"d".substr($value,2)} = "select sum(clicks) as clicks,sum(imp)as imp from analytics where client = '$client' and date = '$date1' and $value = '$tt'  group by $value";
        // echo ${"d".substr($value,2)}."<br>";
        ${"rd".substr($value,2)} = $conn->query( ${"d".substr($value,2)});
        while (${"rod".substr($value,2)} = ${"rd".substr($value,2)}->fetch_assoc())
        {
           
            ${"imd".substr($value,2)} = ${"rod".substr($value,2)}[imp];
            // echo "impression".${"imd".substr($value,2)}."<br>"; 
            ${"ctd".substr($value,2)} = number_format((${"rod".substr($value,2)}['clicks']/${"rod".substr($value,2)}['imp'])*100,2);
        //   echo "ctr".${"ctd".substr($value,2)}."<br>"; 
           
        }
       
        ${"d1".substr($value,2)} = "select sum(clicks) as clicks,sum(imp)as imp from analytics where client = '$client' and date = '$date2' and $value = '$tt' group by $value";
        // echo ${"d1".substr($value,2)}."<br>";
        ${"rd1".substr($value,2)} = $conn->query( ${"d1".substr($value,2)});
        while (${"rod1".substr($value,2)} = ${"rd1".substr($value,2)}->fetch_assoc())
        {
            
            ${"imd1".substr($value,2)} = ${"rod1".substr($value,2)}[imp];
            // echo "impression1 ".${"imd1".substr($value,2)}."<br>"; 
            ${"ctd1".substr($value,2)} = number_format((${"rod1".substr($value,2)}['clicks']/${"rod1".substr($value,2)}['imp'])*100,2);
        //   echo "ctr1 ".${"ctd1".substr($value,2)}."<br>"; 
        }
    //   echo $tt[0];
    for($i=0;$i<=count($test1)-1;$i++)
    {
//   echo $i."<br>";
    
        
         // cards after day query
        ${"s".substr($value,2).$i} = "select sum(clicks) as clicks,sum(imp)as imp from analytics where client = '$client' and $value = '$tt' and date between '$test1[$i]' and '$test2[$i]' group by $value";
        // echo "ee - ".${"s".substr($value,2).$i}."<br>";
        ${"r".substr($value,2).$i} = $conn->query( ${"s".substr($value,2).$i});
        while (${"ro".substr($value,2).$i} = ${"r".substr($value,2).$i}->fetch_assoc())
        {
            
            ${"im".substr($value,2).$i} = ${"ro".substr($value,2).$i}[imp];
            // echo "impression".${"im".substr($value,2).$i}."<br>"; 
            ${"ct".substr($value,2).$i} = number_format((${"ro".substr($value,2).$i}['clicks']/${"ro".substr($value,2).$i}['imp'])*100,2);
            // echo "ctr".${"ct".substr($value,2).$i}."<br>"; 
        }
        
    //   echo ${"im".substr($value,2)."3"};
    }
    
     ${"dim".substr($value,3)} = number_format(((${"imd".substr($value,2)}-${"imd1".substr($value,2)})/${"imd".substr($value,2)})*100,2);
     ${"dct".substr($value,3)} = number_format(((${"ctd".substr($value,2)}-${"ctd1".substr($value,2)})/${"ctd".substr($value,2)})*100,2);
     
     ${"day".substr($value,3)} = ${"dim".substr($value,3)};
     ${"dayc".substr($value,3)} = ${"dct".substr($value,3)};
     
     ${"imw".substr($value,3)} = number_format(((${"im".substr($value,2)."0"}-${"im".substr($value,2)."1"})/${"im".substr($value,2)."0"})*100,2);
     
     ${"ctw".substr($value,3)} = number_format(((${"ct".substr($value,2)."0"}- ${"ct".substr($value,2)."1"})/${"ct".substr($value,2)."0"})*100,2);
    //  echo "avg".${"ctw".substr($value,3)}."<br>";
     
     ${"imm".substr($value,3)} = number_format(((${"im".substr($value,2)."2"}-${"im".substr($value,2)."3"})/${"im".substr($value,2)."2"})*100,2);
     ${"ctm".substr($value,3)} = number_format(((${"ct".substr($value,2)."2"}-${"ct".substr($value,2)."3"})/${"ct".substr($value,2)."2"})*100,2);
     
         ${"impf".substr($value,3)} = [${"day".substr($value,3)},${"imw".substr($value,3)},${"imm".substr($value,3)}]; 
         ${"ctrf".substr($value,3)} = [${"dayc".substr($value,3)},${"ctw".substr($value,3)},${"ctm".substr($value,3)}]; 
          
    
    
if(array_search($value,$eventc) %2 == 0)
{        
?>

<script type="text/javascript">
var idd = <?php echo json_encode($value)?>;
var chrt_div = document.querySelector(".chrt_dt");
var ss = <?php echo json_encode(strtolower(substr($value,-3))); ?>;
var camp = <?php echo json_encode(${"dyn".$value})?>;
if(camp != '')
{
    console.log(camp)
var html = "<form method='post'><select id='"+<?php echo json_encode($value)?>+"' name='"+<?php echo json_encode($value)?>+"'>"+generateOption(camp)+"</select><input type='submit' name='"+<?php echo json_encode(substr($value,-4))?>+"' id='"+<?php echo json_encode(substr($value,-4))?>+"' ></form>";
function generateOption(camp)
{
  var returnHtml = "";
  for(var i=0;i<camp.length;i++)
    returnHtml += "<option>"+camp[i]+"</option>";
  return returnHtml;
}


    var cc=0;
    cc++;
var getClassname = chrt_div.lastElementChild.getAttribute("class").slice(-1);
      var element = document.createElement("div");
      element.setAttribute("class", `chrt_r chrt_r_${Number(getClassname) + 1}`);
      var dyTag = `<div class="chrt_d">
                      <h4><?php echo strtoupper($value)." - ".$tt; ?></h4>`+html+`
                      
                      <div class="scroll_chart">
                        <div class="`+ss+`cc_graph dy_graph">
                            <canvas id="`+ss+`ccChart"></canvas>
                        </div>
                      </div>
                    </div>`;
      element.innerHTML = dyTag;
      chrt_div.appendChild(element);
      var id = document.querySelector(`#`+ss+`ccChart`).getContext("2d");
      createChart(id,<?php echo json_encode(${"impf".substr($value,3)}) ?>,<?php echo json_encode(${"ctrf".substr($value,3)}) ?>)
      function createChart(id,impe,ctre) {
  // Alignment of x-axis label in bar
var labelDataAlign = {
  id: "labelDataAlign",
  afterDatasetsDraw(chart, args, options) {
    var { ctx } = chart;
    for (var i = 0; i < chart.config.data.labels.length; i++) {
      const yPosition = 10;
      const xPosition = chart.getDatasetMeta(0).data[i].y + 3;
      ctx.save();
      ctx.font = "bold 12px Roboto";
      ctx.fillText(chart.config.data.labels[i], yPosition, xPosition);
    }
  },
};

// changes 21-10
var scrollchart1 = {
  id: scrollchart1,
  afterDatasetsDraw(chart, args, pluginOptions) {
var dy_gph = document.querySelector(`.${chart.canvas.parentElement.className.split(" ")[0]}`)
    if (chart.config._config.data.labels.length > 6) {
      dy_gph.style.height = chart.config._config.data.labels.length * 45 + "px";
      chart.update();
    }else{
      dy_gph.style.height = "100%";
      chart.update();
    }
    if(window.matchMedia("(max-width: 600px)").matches){
      dy_gph.style.width = 680 + "px";
      chart.update();
    }else{
      dy_gph.style.width = "100%";
      chart.update();
    }
   
  },
}

  new Chart(id, {
  type: 'bar',
  data: {
  labels: ["DayOfDay", "WeekOfWeek", "MonthOfMonth"],
  datasets: [{
    label: "IMP",
    backgroundColor: "#29AFBA",
    data: impe,
  }, {
    label: "CTR",
    backgroundColor: "#FBCA27",
    data: ctre,
  }]
},
  options: {
    barValueSpacing: 20,
     scales: {
        y: {
          grid: {
              color: function(context) {
                  if(context.tick.value == 0){
                      return "rgb(0,0,0)"
                  }else {
                      return "rgba(0,0,0,0.1)"
                  }
          },
      },
  },
      yAxes: [{
        ticks: {
          min: 0,
        }
      }]
    }
  },
   plugins: {
        labels: {
          formatter: (value, context) => {
            if (context.data.label === "IMP") {
              return value + "%";
            }
            if (context.datas.label === "CTR") {
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
});
}
}
</script>
<?

}
else
{
?>
<script type="text/javascript">

var chrt_div1 = document.querySelector(".chrt_dt");
var ss1 = <?php echo json_encode(strtolower(substr($value,-3))); ?>;
var getClassname1 = chrt_div1.lastElementChild.getAttribute("class").slice(-1);
var camp1 = <?php echo json_encode(${"dyn".$value})?>;
if(camp1 != '')
{
    console.log(camp1)
var html1 = "<form method='post'><select id='"+<?php echo json_encode($value)?>+"' name='"+<?php echo json_encode($value)?>+"'>"+generateOption1(camp1)+"</select><input type='submit' name='"+<?php echo json_encode(substr($value,-4))?>+"' id='"+<?php echo json_encode(substr($value,-4))?>+"' ></form>";
function generateOption1(camp1)
{
  var returnHtml1 = "";
  for(var i=0;i<camp1.length;i++)
    returnHtml1 += "<option>"+camp1[i]+"</option>";
  return returnHtml1;
}
console.log(html1);

    var cc1 = 0;
    cc1++;
      var ch_gp = document.querySelector(`.chrt_r_${Number(getClassname1)}`);
      
      // changes 21-10
      if (window.matchMedia("(max-width: 600px)").matches){
        ch_gp.style.height = "840px";
      }
      
      var el = document.createElement("div");
      el.setAttribute("class", `chrt_d`);
      var dyTag1 = `
                      <h4><?php echo strtoupper($value)." - ".$tt; ?></h4>`+html1+`
                     <div class="scroll_chart">
                        <div class="`+ss1+`cc1_graph dy_graph">
                            <canvas id="`+ss1+`cc1Chart"></canvas>
                        </div>
                    </div>`;
      el.innerHTML = dyTag1;
      ch_gp.appendChild(el);
      var id1 = document.querySelector(`#`+ss1+`cc1Chart`).getContext("2d");
      createChart(id1,<?php echo json_encode(${"impf".substr($value,3)}) ?>,<?php echo json_encode(${"ctrf".substr($value,3)}) ?>)
      function createChart(id,impe,ctre) {
  // Alignment of x-axis label in bar
var labelDataAlign = {
  id: "labelDataAlign",
  afterDatasetsDraw(chart, args, options) {
    var { ctx } = chart;
    for (var i = 0; i < chart.config.data.labels.length; i++) {
      const yPosition = 10;
      const xPosition = chart.getDatasetMeta(0).data[i].y + 3;
      ctx.save();
      ctx.font = "bold 12px Roboto";
      ctx.fillText(chart.config.data.labels[i], yPosition, xPosition);
    }
  },
};

// changes 21-10
var scrollchart1 = {
  id: scrollchart1,
  afterDatasetsDraw(chart, args, pluginOptions) {
var dy_gph = document.querySelector(`.${chart.canvas.parentElement.className.split(" ")[0]}`)
    if (chart.config._config.data.labels.length > 6) {
      dy_gph.style.height = chart.config._config.data.labels.length * 45 + "px";
      chart.update();
    }else{
      dy_gph.style.height = "100%";
      chart.update();
    }
    if(window.matchMedia("(max-width: 600px)").matches){
      dy_gph.style.width = 680 + "px";
      chart.update();
    }else{
      dy_gph.style.width = "100%";
      chart.update();
    }
   
  },
}

   new Chart(id, {
  type: 'bar',
  data: {
  labels: ["DayOfDay", "WeekOfWeek", "MonthOfMonth"],
  datasets: [{
    label: "IMP",
    backgroundColor: "#29AFBA",
    data: impe,
  }, {
    label: "CTR",
    backgroundColor: "#FBCA27",
    data: ctre,
  }]
},
  options: {
    barValueSpacing: 20,
    scales: {
        y: {
          grid: {
              color: function(context) {
                  if(context.tick.value == 0){
                      return "rgb(0,0,0)"
                  }else {
                      return "rgba(0,0,0,0.1)"
                  }
          },
      },
  },
      yAxes: [{
        ticks: {
          min: 0,
        }
      }]
    }
  },
   plugins: {
        labels: {
          formatter: (value, context) => {
            if (context.data.label === "IMP") {
              return value + "%";
            }
            if (context.datas.label === "CTR") {
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
});
}
}
</script>
<?php
}

}
?>


