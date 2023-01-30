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

    $replace ="SELECT REPLACE(GROUP_CONCAT(COLUMN_NAME), 'client,imp,clicks,ctr,date,','')
                FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$client'
                AND TABLE_SCHEMA = 'publishe_analytic'";
    $replaced = $conn->query($replace);
    $row = $replaced->fetch_assoc();
    // print_r($row);
    foreach($row as $x => $x_value)
    {
     
      $name = $x_value;  
      $eventc = explode (",", $name);
      
    } 
    if(isset($_POST["submit"]) ||isset($_POST["submit1"]) || isset($_POST["filter"]))
    {
        if(!empty($_POST["fdate"]))
        {
            $fromdate = $_POST["fdate"];
   
        }
        if(!empty($_POST["ldate"]))
        {
            $todate = $_POST["ldate"];
        }
      if(!empty($_POST["fcamp"]))
        {
             $campaign = $_POST["fcamp"];
        }
       
        $column = $_POST["fcol"];
        
        // $subcampaign = $_POST["fscamp"];
   
  if(empty($fromdate)&&empty($todate)&&!empty($column)&&empty($campaign))
  {
      $sql = "select sum(clicks) as clicks,sum(imp)as imp,$column from $client where $column <> '0' and $column <> '' group by $column  ";
            // echo $sql;
  }
  else if(empty($fromdate)&&empty($todate)&&empty($column)&&!empty($campaign))
  {
      $sql = "select sum(clicks) as clicks,sum(imp)as imp,campaign from $client where campaign = '$campaign' group by campaign ";
    //   echo $sql;
  }
  else if(empty($fromdate)&&empty($todate)&&!empty($column)&&!empty($campaign))
  {
      $sql = "select sum(clicks) as clicks,sum(imp)as imp,$column  from $client where campaign = '$campaign' and $column <> '0' and $column <> '' group by $column,campaign  ";
    //   echo $sql;
  }
  else if(!empty($fromdate)&&!empty($todate)&&!empty($column)&&!empty($campaign))
  {
      $sql = "select sum(clicks) as clicks,sum(imp)as imp,$column from $client where date between '$fromdate' and '$todate' and campaign = 
     '$campaign' and $column <> '0' and $column <> '' group by $column,campaign";
    //   echo $sql;
  }
  else if(!empty($fromdate)&&!empty($todate)&&!empty($column)&&empty($campaign))
  {
      $sql = "select sum(clicks) as clicks,sum(imp)as imp,$column from $client where date between '$fromdate' and '$todate' and $column <> '0' and $column <> '' group by $column";
    //   echo $sql;
  }
  else if(!empty($fromdate)&&!empty($todate)&&empty($column)&&empty($campaign))
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
        if(!empty($column) )
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
        else if (!empty($campaign))
        {
            if(strlen($row['campaign']) > 20)
            {
                 $dyn[] = substr($row['campaign'],0,20)."...";
            }
            else
            {
                $dyn[] = $row['campaign'];
            }
        }
        else if (!empty($subcampaign))
        {
            if(strlen($row['subcampaign']) > 20)
            {
                 $dyn[] = substr($row['subcampaign'],0,20)."...";
            }
            else
            {
                $dyn[] = $row['subcampaign'];
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
else if($column != "")
{

    $sql = "select $column,sum(clicks) as clicks,sum(imp)as imp from $client where $column <> '0' and $column <> '' group by $column";
    // echo $sql;
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc())
    {
        
        // $day[] = substr($row['day'],0,3);
        $imp[] = $row['imp'];
        $clicks[] = $row['clicks'];
        //  $sum = array_sum($imp);
       
        // echo ($row['imp']/array_sum($imp)*100)."<br>";
        $ctr[] = number_format(($row['clicks']/$row['imp'])*100,2);
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
else{
     
   
    //   date chart

    $sql = "select campaign,sum(clicks) as clicks,sum(imp)as imp from $client where campaign <> '0' and campaign <> '' group by campaign";
    // echo $sql;
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc())
    {
        
        // $day[] = substr($row['day'],0,3);
        $imp[] = $row['imp'];
        $clicks[] = $row['clicks'];
        //  $sum = array_sum($imp);
       
        // echo ($row['imp']/array_sum($imp)*100)."<br>";
        $ctr[] = number_format(($row['clicks']/$row['imp'])*100,2);
        $dyn[] = $row['campaign'];
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

if(isset($_POST['back']))
 {
     header("Location: index1.php?client=".$client.",".$value[0]);
            die();
 }
 else if(isset($_POST['csv']))
 {
     if(!empty($_POST['fcol']))
     {
         header("Location: raw.php?client=".$client.",".$value[0].",".$_POST['fcol']);
            die();
     }
     else{
         header("Location: raw.php?client=".$client.",".$value[0].",".$column);
            die();
     }
     
 }

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
    <title>Analytic</title>
</head>

<body>
    <!--<div class="container maindiv">-->
   
        
   
    
    <div class="chrt" >
       <div class="chrt_dt" id="divToExport">
        <form method="post" class="container lg">
          <button name="back" class="btn">Back</button>
          <div class="hc_logo">
            <img src="https://s.hcurvecdn.com/hc_logo.png" alt="logo" />
        </div>
     
         </form>
          
                <?php
               
                $sql="SELECT cname FROM `clientname` WHERE dbname = '$client' LIMIT 1";
                $row = mysqli_fetch_assoc($conn->query($sql) );
                    
                    if(!empty($fromdate)&&!empty($todate))
                    {
                        echo '<div class="client_name" style="text-transform:capitalize">'.$row['cname'].'<br>'.date("M j, Y", strtotime($fromdate)).' to '.date("M j, Y", strtotime($todate)).'<br>'.$campaign.'</div><br>';
                    }
                    else
                    {
                        if(!empty($fromdate))
                        {
                        echo '<div class="client_name" style="text-transform:capitalize">'.$row['cname'].'<br>'.date("M j, Y", strtotime($fromdate)).' to '.date("M j, Y", strtotime($todate)).'</div><br>';
                        }
                        else{
                            echo '<div class="client_name" style="text-transform:capitalize">'.$row['cname'].'<br></div><br>';
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
              <div class="xyz" style="display:flex;flex-direction: row-reverse;margin-right:30px">
              <button class="submit_btn" name="filter" style="    margin-top: 10px">Submit</button>
        <select name="fcamp" id="fcamp" style="border:1px solid black;margin-left:6px;" class="fcamp">
                        <option value="">Select Campaign</option>
                    <?php
                        $sql = "select distinct campaign from $client";
                            $result = $conn->query($sql);
                            while ($row = $result->fetch_assoc())
                            {
                                echo '<option value="'.$row["campaign"].'">'.$row["campaign"].'</option>';
                            }    
                    ?>
                    </select>
                    <select name="fscamp" id="fscamp" style="border:1px solid black;display:none " class="fcamp">
                        <option value="">Select Sub-Campaign</option>
                    <?php
                        $sql1 = "select distinct subcampaign from $client where campaign='$campaign' ";
                            $result1 = $conn->query($sql1);
                            while ($row = $result1->fetch_assoc())
                            {
                                echo '<option value="'.$row["subcampaign"].'">'.$row["subcampaign"].'</option>';
                            }    
                    ?>
                    </select>
                  
                    </div>
             <div class="chrt_scale">
        <div class="chrt_score_details">
          <div class="imp_score">
            <h4 >IMPRESSION</h4>
            <span class="uppervalue" ><?php echo number_format((array_sum($imp)))?></span>
          </div>
          <span class="vl"></span>
          <div class="ctr_score">
            <h4>CTR</h4>
            
            <span class="uppervalue" ><?php echo number_format((array_sum($clicks)/$impcheck)*100,2)."%" ?></span>
          </div>
          <span class="vl"></span>
          <div class="click_score">
            <h4>CLICKS</h4>
            
            <span class="uppervalue" ><?php echo number_format((array_sum($clicks))) ?></span>
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
         
      </div >
      <div id="swiper" style="display:none">
      <div class="swiper_container" >
        <div class="swiper mySwiper">
          <div class="swiper-wrapper" id="wrapper">
     
          </div>
          
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        
      </div>

      </div>   
        <div class="chrt_r_2" >
        <div class="chrt_d">
            <h4 style="text-align:center"><?php echo ucfirst($column)?> Wise Performance</h4>
                <div class="dt_sel" style="justify-content: flex-start;">
      <label>Select Cohort : </label>
        <select name="fcol" id="fcol" style="border:1px solid black" class="fcol">
                        <!--<option value="">Select Column</option>-->
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
            <div class="dte_graph cre_graph dy_graph" id="dte">
              <canvas id="dteChart"></canvas>
            </div>
            </div>
        </div>
       
        </div>
        <button name="csv" class="btn2">Export Csv</button>
         </form>
         <div style="text-align:center"><a href="support.php?" >Need Help?</a></div>
        </div>
                   
    </div>
      
        </div>   
       
    </div>
    <!--</div>-->
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

</script> 
<script>
     var dyn = <?php echo json_encode($dyn) ?>; 
     var column = <?php echo json_encode($column) ?>; 
     console.log(column);    
     
     function impp()
     {
      var dist = <?php echo json_encode($dist) ?>;
      
    
      for(var i=0;i<dist.length;i++)
      {
          document.getElementById("swiper").style.display = "block";
          var el = document.createElement('div');
                el.className = "swiper-slide";
                el.id = "swiper-slide"+i;
                
          if(parseInt(dist[i]) < 5)
          {
                
                el.style = "outline:4px solid red"
                document.getElementById("wrapper").appendChild(el)
                var e3 = document.createElement('div');
                e3.className = "swip_card";
                e3.style="padding-top:-20px";
                e3.innerHTML = "<span style='color:red;font-size:60px'>&#9888</span><br><span style='font-size:30px'> Distribution of impression in "+dyn[i] +" is very low</span>";
              document.getElementById("swiper-slide"+i).appendChild(e3);
           
    
          }
          else if(parseInt(dist[i]) >= 5 && parseInt(dist[i]) <= 10)
          {
               
                el.style = "outline:4px solid orange"
                document.getElementById("wrapper").appendChild(el)
                var e3 = document.createElement('div');
                e3.className = "swip_card";
                e3.innerHTML = "<span style='font-size:30px'>Distribution of impression in "+dyn[i] +" is low</span>";
                document.getElementById("swiper-slide"+i).appendChild(e3);
          }
          else
          {
             
                document.getElementById("swiper").style.display = "none";
               
          }
         
      }
      
     }
    impp();
    function ctrr()
    {
        var ctr = <?php echo json_encode($ctr) ?>;
      
        for(var i=0;i<ctr.length;i++)
      {
          document.getElementById("swiper").style.display = "block";
          var el = document.createElement('div');
                el.className = "swiper-slide";
                el.id = "swiper-slide_c"+i;
                
          if(parseInt(ctr[i]) < 0.1)
          {
                
                el.style = "outline:4px solid red"
                document.getElementById("wrapper").appendChild(el)
                var e3 = document.createElement('div');
                e3.className = "swip_card";
                e3.style="padding-top:-20px";
                e3.innerHTML = "<span style='color:red;font-size:60px'>&#9888</span><br><span style='font-size:30px'> Viewability for "+dyn[i] +" is very low</span>";
              document.getElementById("swiper-slide_c"+i).appendChild(e3);
           
    
          }
       
          else
          {
             
                document.getElementById("swiper").style.display = "none";
               
          }
         
      }
      ctrr()
    }
</script>
<script>
    

var dyn = <?php echo json_encode($dyn) ?>; 
if(dyn != "0")
{
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
    //   if(window.matchMedia("(max-width: 600px)").matches){
    // {
    //     ctx.font = "bold 16px Roboto";
    // }      
    //  else{
         ctx.font = "bold 18px Roboto";
    //  } 
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
        minBarLength: "300",
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
        minBarLength: "400",
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
        minBarLength: "550",
        barPercentage: 0.7,
        borderSkipped: false,
        hoverOffset: 4,
      },
      {
        label: "Click",
        data: <?php echo json_encode($clicks); ?>,
        backgroundColor: [
          "white",
          "white",
          "white",
          "white",
          "white",
          "white",
          
        ],
        // color:"white",
        minBarLength: "3000",
        barPercentage: 0,
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
            if(context.dataset.label === "Impression" || context.dataset.label === "Click"){
              return context.dataset.label + ": " + context.parsed.x.toLocaleString('en-US');
            }
            // if(context.dataset.label === "Click"){
            //   return context.dataset.label + ": " + context.parsed.x.toLocaleString('en-US');
            // }
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
               return Number(value).toLocaleString("en-US");
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
              size:20,
    
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
else
{
    document.getElementById("dte").style.cssText = "width:100%;height:100%;font-size:24px;font-weight:bold;color:black;text-align:center;padding-top:10%"
    document.getElementById("dte").innerHTML = "Not Applicable"
}
</script>
<script>
    var fd = <?php echo json_encode($fromdate) ?>;
    if(fd != null)
    {
    document.getElementById('fdate').value = fd;
    }
    
    console.log(fd);
    var sd = <?php echo json_encode($todate) ?>;
    if(sd != null)
    {
    document.getElementById('ldate').value = sd;
    }
    
    console.log(sd);
    var campg = <?php echo json_encode($campaign) ?>;
    console.log(campg)
    if(campg != null)
    {
    document.getElementById('fcamp').value = campg;
    }
    // else{
    //      document.getElementById('fcamp').value = "select campaign";
    //      console.log("Ddd")
    // }
    var col = <?php echo json_encode($column) ?>;
    console.log(col);
    if(col != null )
    {
        document.getElementById('fcol').value = col;
    }
   
    
</script>

