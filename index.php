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
     

    if(isset($_POST["submit"]))
    {
      $fromdate = $_POST["fdate"];

    //   date("d-m-Y", strtotime($_POST["fdate"]));
      $todate = $_POST["ldate"];
      $campaign = $_POST["fcamp"];
      $subcampaign = $_POST["fscamp"];
    //   echo $subcampaign;
   
    //   date chart
    if(!empty($fromdate)&&!empty($todate)&&!empty($campaign)&&!empty($subcampaign))
        {
            
            $sql = "select day,date,sum(clicks) as clicks,sum(imp)as imp,dimension from analytics where mainclient = '$client' and date between '$fromdate' and '$todate' and campaign = '$campaign' and subcampaign = '$subcampaign' group by date HAVING imp > 100";
        } 
        else if(empty($fromdate)&&empty($todate)&&!empty($campaign)&&!empty($subcampaign))
        {
            
            $sql = "select day,date,sum(clicks) as clicks,sum(imp)as imp,dimension from analytics where mainclient = '$client'  and campaign = '$campaign' and subcampaign = '$subcampaign' group by date HAVING imp > 100";
        } 
    else if(!empty($fromdate)&&!empty($todate)&&empty($campaign))
   {
       $sql = "select day,date,sum(clicks) as clicks,sum(imp)as imp,dimension from analytics where mainclient = '$client' and date between '$fromdate' and '$todate' group by date HAVING imp > 100";

   }
   else if(!empty($campaign)&&!empty($fromdate)&&!empty($todate))
   {
       $sql = "select day,date,sum(clicks) as clicks,sum(imp)as imp,dimension from analytics where mainclient = '$client' and date between '$fromdate' and '$todate' and campaign = '$campaign' group by date HAVING imp > 100";

   }
   else if(!empty($campaign)&&empty($fromdate)&&empty($todate))
   {
    $sql = "select date,sum(clicks) as clicks,sum(imp)as imp,dimension from analytics where mainclient = '$client' and campaign = '$campaign'  group by date HAVING imp > 100 ";
   }
   else
   {
       $sql = "select day,date,sum(clicks) as clicks,sum(imp)as imp,dimension from analytics where mainclient = '$client' and date between '$fromdate' and '$todate' group by date HAVING imp > 100";
   }
    // echo $sql;
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc())
    {
        $day[] = substr($row['day'],0,3);
        $imp[] = $row['imp'];
        // $dist[] = round((($row['imp'])/(array_sum($imp)))*100,2);
         $ctr[] = number_format(($row['clicks']/$row['imp'])*100,2);
        $date[] = date("M d", strtotime($row['date']));
        $dim[] = $row['dimension'];
        $clicks[] = $row['clicks'];
       
    }
     
     $impcheck = array_sum($imp);
      $perct = (int)(array_sum($imp)*0.10);
      $fifty = (int)(array_sum($imp)*0.50);
     foreach ($imp as $value)
    {
        $dist[] = number_format(($value/$impcheck)*100,2);
    }
    }
    else{
     
     
    //   date chart

    $sql = "select date,sum(clicks) as clicks,sum(imp)as imp from analytics where mainclient = '$client'  group by date  ";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc())
    {
        $day[] = substr($row['day'],0,3);
        $imp[] = $row['imp'];
        //  $sum = array_sum($imp);
       
        // echo ($row['imp']/array_sum($imp)*100)."<br>";
        $ctr[] = number_format(($row['clicks']/$row['imp'])*100,2);
       $date[] = date("M d", strtotime($row['date']));
        $dim[] = $row['dimension'];
        $clicks[] = $row['clicks'];
    }
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
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="style.css">
  <!--  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script> -->
  <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>-->
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
                        echo '<div class="client_name" style="text-transform:capitalize">Voot AVOD - Road Safety World Series Season 2 - Moment Marketing - Hindi'.'<br>'.$campaign.'</div><br>';
                    }
                    
                }
                else{
                    if(!empty($fromdate)&&!empty($todate))
                    {
                        echo '<div class="client_name" style="text-transform:capitalize">'.$client.'<br>'.date("M j, Y", strtotime($fromdate)).' to '.date("M j, Y", strtotime($todate)).'<br>'.$campaign.'</div><br>';
                    }
                    else
                    {
                        echo '<div class="client_name" style="text-transform:capitalize">'.$client.'<br>'.$campaign.'</div><br>';
                        
                    }
                    
                }
            ?>
            <div class="title_bar">
                <h4>PERFORMANCE TREND</h4>
                
                <div class="dt_box">
                    <form class="dt_sel" method="post">
                    <div> 
                        <label>From</label>
                        <input type="date" id="fdate" name="fdate" />
                        <label>to</label>
                        <input type="date" id="ldate"  name="ldate"/>
                    <!--</div>-->
                    </div>  
        <!--                  <div class="xyz" style="display:flex;flex-direction: row-reverse;margin-right:30px">-->
        <!--      <button class="submit_btn" name="filter" style="    margin-top: 10px">Submit</button>-->
        <!--<select name="fcamp" id="fcamp" style="border:1px solid black;margin-left:6px;" class="fcamp">-->
        <!--                <option value="">Select Campaign</option>-->
        <!--            <?php-->
        <!--                $sql = "select distinct campaign from $client";-->
        <!--                    $result = $conn->query($sql);-->
        <!--                    while ($row = $result->fetch_assoc())-->
        <!--                    {-->
        <!--                        echo '<option value="'.$row["campaign"].'">'.$row["campaign"].'</option>';-->
        <!--                    }    -->
        <!--            ?>-->
        <!--            </select>-->
        <!--            <select name="fscamp" id="fscamp" style="border:1px solid black; " class="fcamp">-->
        <!--                <option value="">Select Sub-Campaign</option>-->
        <!--            <?php-->
        <!--                $sql1 = "select distinct subcampaign from $client where campaign='$campaign' ";-->
        <!--                    $result1 = $conn->query($sql1);-->
        <!--                    while ($row = $result1->fetch_assoc())-->
        <!--                    {-->
        <!--                        echo '<option value="'.$row["subcampaign"].'">'.$row["subcampaign"].'</option>';-->
        <!--                    }    -->
        <!--            ?>-->
        <!--            </select>-->
                  
        <!--            </div>-->
                    
                    
                    <div>
                    <select name="fcamp" id="fcamp" style="border:1px solid black" class="fcamp">
                        <option value="">Select Campaign</option>
                    <?php
                        $sql = "select distinct campaign from analytics where mainclient = '$client'";
                            $result = $conn->query($sql);
                            while ($row = $result->fetch_assoc())
                            {
                                echo '<option value="'.$row["campaign"].'">'.$row["campaign"].'</option>';
                            }    
                    ?>
                    </select>
                    <select name="fscamp" id="fscamp" style="border:1px solid black" class="fcamp">
                        <option value="">Select Sub-Campaign</option>
                    <?php
                        $sql1 = "select distinct subcampaign from analytics where campaign='$campaign' and mainclient = '$client'";
                            $result1 = $conn->query($sql1);
                            while ($row = $result1->fetch_assoc())
                            {
                                echo '<option value="'.$row["subcampaign"].'">'.$row["subcampaign"].'</option>';
                            }    
                    ?>
                    </select>
                    
                    </div>
                    <button  class="submit_btn" name="submit">
                        <i  class="fa-solid fa-arrow-right"></i>
                    </button>
                    </form>
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
    
        <div class="chrt_r_2" style="display:none">
      
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

     var swiper = new Swiper(".mySwiper", {
        spaceBetween: 20,pagination: {
          el: ".swiper-pagination",
          type: "fraction",
        },
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
});
  


</script>
<?php
$replace ="SELECT REPLACE(GROUP_CONCAT(COLUMN_NAME), 'mainclient,imp,clicks,ctr,','')
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
    
  
foreach($eventc as $i) 
  {
      
    //  echo array_search($i,$eventc);
        $fromdate = $_POST["fdate"];
        $todate = $_POST["ldate"];
        // echo $campaign;
        if(!empty($fromdate)&&!empty($todate)&&!empty($campaign)&&!empty($subcampaign))
        {
            
            ${"event".$i} = "select sum(clicks) as clicks,sum(imp)as imp,$i from analytics where mainclient = '$client' and date between '$fromdate' and '$todate' and campaign = '$campaign' and subcampaign = '$subcampaign' group by $i HAVING imp > 100 order by imp desc ";
        } 
        else if(empty($fromdate)&&empty($todate)&&!empty($campaign)&&!empty($subcampaign))
        {
            
            ${"event".$i} = "select sum(clicks) as clicks,sum(imp)as imp,$i from analytics where mainclient = '$client'  and campaign = '$campaign' and subcampaign = '$subcampaign' group by $i HAVING imp > 100 order by imp desc ";
        } 
        else if(!empty($fromdate)&&!empty($todate)&&empty($campaign))
        {
            
            ${"event".$i} = "select sum(clicks) as clicks,sum(imp)as imp,$i from analytics where mainclient = '$client' and date between '$fromdate' and '$todate' group by $i HAVING imp > 100 order by imp desc ";
        } 
        else if(!empty($campaign)&&!empty($fromdate)&&!empty($todate))
        {
            
            ${"event".$i} = "select sum(clicks) as clicks,sum(imp)as imp,$i from analytics where mainclient = '$client' and date between '$fromdate' and '$todate' and campaign = '$campaign' group by $i HAVING imp > 100 order by imp desc ";
        } 
        else if(!empty($campaign)&&empty($fromdate)&&empty($todate))
        {
            ${"event".$i} = "select sum(clicks) as clicks,sum(imp)as imp,$i from analytics where mainclient = '$client' and campaign = '$campaign'  group by $i HAVING imp > 100 order by imp desc ";
        }
        else
        {
            ${"event".$i} = "select sum(clicks) as clicks,sum(imp)as imp,$i from analytics where mainclient = '$client' group by $i  ";
        }

  

         
        ${"resultd".$i} = $conn->query(${"event".$i});
        while (${"row".$i} = ${"resultd".$i}->fetch_assoc())
        {
            if(strlen(${"row".$i}[$i]) > 31)
            {
                ${"dyn".$i}[] = substr(${"row".$i}[$i],0,30)."...";
            }
            else{
                ${"dyn".$i}[] = ${"row".$i}[$i];
            }
            
            ${"imp".$i}[] = (int)${"row".$i}['imp'];
         
            ${"dist".$i}[] = number_format(((${"row".$i}['imp'])/($impcheck)*100),2);
            ${"ctr".$i}[] = number_format((${"row".$i}['clicks']/${"row".$i}['imp'])*100,2);
        }
if(in_array("0", ${"dyn".$i}) || empty(${"dyn".$i}))
{
    $key = array_search($i, $eventc); 
    array_splice($eventc, $key, 1);
    // unset($eventc[$i]);
    echo $eventc[$i];
    $eventc = array_values($eventc);
    // print_r($eventc);
}
else{        
if(array_search($i,$eventc) %2 == 0)
{        
?>

<script type="text/javascript">

var chrt_div = document.querySelector(".chrt_dt");
var ss = <?php echo json_encode(strtolower(substr($i,-3))); ?>;
var camp = <?php echo json_encode(${"dyn".$i})?>;
var col = <?php echo json_encode($i)?>;

    var cc=0;
    cc++;
var getClassname = chrt_div.lastElementChild.getAttribute("class").slice(-1);
      var element = document.createElement("div");
      element.setAttribute("class", `chrt_r chrt_r_${Number(getClassname) + 1}`);
      var dyTag = `<div class="chrt_d">
                      <h4><?php echo strtoupper($i); ?></h4>
                      <div class="scroll_chart">
                        <div class="`+ss+`cc_graph dy_graph">
                            <canvas id="`+ss+`ccChart"></canvas>
                        </div>
                      </div>
                    </div>`;
      element.innerHTML = dyTag;
      chrt_div.appendChild(element);
      var id = document.querySelector(`#`+ss+`ccChart`).getContext("2d");
      
          createChart(id,<?php echo json_encode(array_slice(${"dyn".$i},0,50)) ?>,<?php echo json_encode(array_slice(${"imp".$i},0,50)) ?>,<?php echo json_encode(array_slice(${"dist".$i},0,50)) ?>,<?php echo json_encode(array_slice(${"ctr".$i},0,50)) ?>)
      
      
      function createChart(id,campaign,impe,diste,ctre) {
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

if(col == 'date')
{
    new Chart(id, {
  type: "line",
  data: {
    labels: <?php echo json_encode($date); ?>,
    datasets: [
      {
        label: "imp",
        data: impe,
        borderColor: "#29AFBA",
        yAxisID: "yimp",
      },
      {
        label: "CTR",
        data: ctre,
        borderColor: "#FBCA27",
        yAxisID: "yclk",
      },
      {
        label: "Distribution",
        data: diste,
        borderColor: "#F47958",
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
          offset: true,
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
            if (context.dataset.label === "Distribution"  || context.dataset.label === "CTR") {
              return context.dataset.label + ": " + context.parsed.y + "%";
            }
            else if (context.dataset.label === "imp") {
              return context.dataset.label + ": " + context.parsed.y.toLocaleString("en-US");
            } 
            else {
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
}
else{
     new Chart(id, {
    type: "bar",
    data: {
      labels: campaign,
      datasets: [
        {
          label: "CTR",
          data: ctre,
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
          data: diste,
          backgroundColor: [
            "#F47958",
            "#F47958",
            "#F47958",
            "#F47958",
            "#F47958",
            "#F47958",
          ],
          minBarLength: "330",
        barPercentage: 0.7,
        borderSkipped: false,
        hoverOffset: 4,
        },
        {
          label: "Impression",
          data: impe,
          backgroundColor: [
            "#29AFBA",
            "#29AFBA",
            "#29AFBA",
            "#29AFBA",
            "#29AFBA",
            "#29AFBA",
          ],
         minBarLength: "550",
        barPercentage: 0.7,
        borderSkipped: false,
        hoverOffset: 4,
        },
      ],
    },
    plugins: [ChartDataLabels,labelDataAlign,scrollchart1],
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
          ticks: {
            mirror: true,
          },
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
              if (context.dataset.label === "Impression") {
                return context.parsed.x.toLocaleString("en-US");
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
var ss1 = <?php echo json_encode(strtolower(substr($i,-3))); ?>;
var getClassname1 = chrt_div1.lastElementChild.getAttribute("class").slice(-1);
var camp1 = <?php echo json_encode(${"dyn".$i})?>;

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
                      <h4><?php echo strtoupper($i); ?></h4>
                     <div class="scroll_chart">
                        <div class="`+ss1+`cc1_graph dy_graph">
                            <canvas id="`+ss1+`cc1Chart"></canvas>
                        </div>
                    </div>`;
      el.innerHTML = dyTag1;
      ch_gp.appendChild(el);
      var id1 = document.querySelector(`#`+ss1+`cc1Chart`).getContext("2d");
      createChart(id1,<?php echo json_encode(array_slice(${"dyn".$i},0,50)) ?>,<?php echo json_encode(array_slice(${"imp".$i},0,50)) ?>,<?php echo json_encode(array_slice(${"dist".$i},0,50)) ?>,<?php echo json_encode(array_slice(${"ctr".$i},0,50)) ?>)
      function createChart(id,campaign,impe,diste,ctre) {
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
    type: "bar",
    data: {
      labels: campaign,
      datasets: [
        {
          label: "CTR",
          data: ctre,
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
          data: diste,
          backgroundColor: [
            "#F47958",
            "#F47958",
            "#F47958",
            "#F47958",
            "#F47958",
            "#F47958",
          ],
           minBarLength: "330",
        barPercentage: 0.7,
        borderSkipped: false,
        hoverOffset: 4,
        },
        {
          label: "Impression",
          data: impe,
          backgroundColor: [
            "#29AFBA",
            "#29AFBA",
            "#29AFBA",
            "#29AFBA",
            "#29AFBA",
            "#29AFBA",
          ],
          minBarLength: "550",
        barPercentage: 0.7,
        borderSkipped: false,
        hoverOffset: 4,
        },
      ],
    },
    plugins: [ChartDataLabels,labelDataAlign,scrollchart1],
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
          ticks: {
            mirror: true,
          },
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
              if (context.dataset.label === "Impression") {
                return context.parsed.x.toLocaleString("en-US");
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
}

</script>
<?php
}
}
}
?>
<script>
    var fd = <?php echo json_encode($fromdate) ?>;
    document.getElementById('fdate').value = fd;
    var sd = <?php echo json_encode($todate) ?>;
    document.getElementById('ldate').value = sd;
    var campg = <?php echo json_encode($campaign) ?>;
    if(campg != null )
    {
        document.getElementById('fcamp').value = campg;
    }
    var scampg = <?php echo json_encode($subcampaign) ?>;
    if(scampg != null )
    {
        document.getElementById('fscamp').value = scampg;
    }
    
</script>

