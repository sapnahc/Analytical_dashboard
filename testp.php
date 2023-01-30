<?php
$servername = "localhost";
$username = "publishe_data";
$password = "analytichc1";
$dbname = "publishe_analytic";

// retriving url values
$url = $_SERVER['REQUEST_URI'];
    $url_components = parse_url($url);
    parse_str($url_components['query'], $params);
    if (strpos($params['client'],","))
    {
    $value = explode(",",$params['client']);
    
    $client = $value[0];
    $campaign = $value[1];
  
   
    }
    else
    {
        $client = $params['client'];
    }
 
 // Create connection
$conn = new mysqli($servername, $username, $password,$dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    echo "Fail";
}
else{
   
    // removing column from table
    $replace ="SELECT REPLACE(GROUP_CONCAT(COLUMN_NAME), 'client,imp,clicks,ctr,date,','')
                FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$client'
                AND TABLE_SCHEMA = 'publishe_analytic'";
    $replaced = $conn->query($replace);
    $row = $replaced->fetch_assoc();
    $value = $_POST['fcamp']; 
    // echo $value;
    
     foreach($row as $x => $x_value)
    {
      $name = $x_value;  
      $eventc = explode (",", $name);
      
    } 
    $len = count($eventc);
    for($adi=0;$adi<$len;$adi++){
          
          $sqladi = "select $eventc[$adi] FROM $client WHERE campaign='$value' ORDER BY $eventc[$adi] desc";
          $replaced3=mysqli_query($conn,$sqladi);
          $row3 = $replaced3->fetch_assoc();
          
          $hello= $row3[$eventc[$adi]];
          if($hello=="0"){
             $yenahihai.=$adi.",";
          }
      }
     
     $hogaya = explode(",",$yenahihai);
     for($js=0;$js<count($hogaya);$js++){
         unset($eventc[$hogaya[$js]]);
     }
    
    
    
    // condition will work if button gets clicked
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
      $sql = "select sum(clicks) as clicks,sum(imp)as imp,$column from $client where $column <> '0' and $column <> '' group by $column order by imp desc";
        
  }
  else if(empty($fromdate)&&empty($todate)&&empty($column)&&!empty($campaign))
  {
      $sql = "select sum(clicks) as clicks,sum(imp)as imp,campaign from $client where campaign = '$campaign' group by campaign order by  desc";
     
    //   echo $sql;
  }
  else if(empty($fromdate)&&empty($todate)&&!empty($column)&&!empty($campaign))
  {
      $sql = "select sum(clicks) as clicks,sum(imp)as imp,$column  from $client where campaign = '$campaign' and $column <> '0' and $column <> '' group by $column,campaign order by imp desc ";

  }
  else if(!empty($fromdate)&&!empty($todate)&&!empty($column)&&!empty($campaign))
  {
      $sql = "select sum(clicks) as clicks,sum(imp)as imp,$column from $client where date between '$fromdate' and '$todate' and campaign = 
     '$campaign' and $column <> '0' and $column <> '' group by $column,campaign order by imp desc";
    
    //   echo $sql;
  }
  else if(!empty($fromdate)&&!empty($todate)&&!empty($column)&&empty($campaign))
  {
      $sql = "select sum(clicks) as clicks,sum(imp)as imp,$column from $client where date between '$fromdate' and '$todate' and $column <> '0' and $column <> '' group by $column order by imp desc ";
    
    //   echo $sql;
  }
  else if(!empty($fromdate)&&!empty($todate)&&empty($column)&&empty($campaign))
  {
      $sql = "select date,sum(clicks) as clicks,sum(imp)as imp,dimension from $client where date between '$fromdate' and '$todate' group by date  order by imp desc";
    
    //   echo $sql;
  }
  else
  {
      $sql = "select date,sum(clicks) as clicks,sum(imp)as imp,dimension from $client where date between '$fromdate' and '$todate' group by date order by imp desc";
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
    $sql = "select sum(clicks) as clicks,sum(imp)as imp,campaign from $client where campaign = '$campaign' group by campaign order by imp desc";
    //   echo $sql;
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
    
    //Adding Campaign Start date of the campaign
// $conn = new mysqli($servername, $username, $password,$dbname);
// $check1="select date from $client order by date asc limit 1";
// $result221=mysqli_query($conn,$check1);
// $row221=mysqli_fetch_assoc($result221);
// $firstdate1= $row221['date'];
 
//Adding Campaign Last date of the campaign
// $check12="select date from $client order by date desc limit 1";
// $result231=mysqli_query($conn,$check12);
// $row231=mysqli_fetch_assoc($result231);
// $lastdate1= $row231['date'];
    
    //   echo '<div class="client_name" id="mmm" style="text-transform:capitalize;position:absolute;top:137px;border:1px solid black">'.$campaign.'<br>'.$firstdate1.' To '.$lastdate1.'</div><br>';


    //   echo "$firstdate1 to $lastdate1";
    //   date chart

    $sql = "select sum(clicks) as clicks,sum(imp)as imp,campaign from $client where campaign = '$campaign' group by campaign order by imp desc";
    $replace ="SELECT REPLACE(GROUP_CONCAT(COLUMN_NAME), 'client,imp,clicks,ctr,date,','')
                FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$client'
                AND TABLE_SCHEMA = 'publishe_analytic'";
    $replaced = $conn->query($replace);
    $row1 = $replaced->fetch_assoc();
    $value = $campaign;
    $column = 'Campaign';
    // echo $value;
    
     foreach($row1 as $x => $x_value)
    {
      $name = $x_value;  
      $eventc = explode (",", $name);
      
    } 
    $len = count($eventc);
    for($adi=0;$adi<$len;$adi++){
          
          $sqladi = "select $eventc[$adi] FROM $client WHERE campaign='$value' ORDER BY $eventc[$adi] desc";
          $replaced3=mysqli_query($conn,$sqladi);
          $row3 = $replaced3->fetch_assoc();
          
          $hello= $row3[$eventc[$adi]];
          if($hello=="0"){
             $yenahihai.=$adi.",";
          }
      }
     
     $hogaya = explode(",",$yenahihai);
     for($js=0;$js<count($hogaya);$js++){
         unset($eventc[$hogaya[$js]]);
     }
    
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
     header("Location: index2.php?client=".$client);
            die();
 }
 else if(isset($_POST['csv']))
{
     if(!empty($_POST['fcol']) && empty($_POST['fcamp']))
     {
         header("Location: raw2.php?client=".$client.",".$_POST['fcol']);
            die();
    
     }
     else if(!empty($_POST['fcamp']) && !empty($_POST['fcol']))
     {
       header("Location: raw2.php?client=".$client.",".$_POST['fcol'].",".$_POST['fcamp']);
            die();   
     }
     else if(!empty($_POST['fcamp']) && empty($_POST['fcol']))
     {
       header("Location: raw2.php?client=".$client.",".$column.",".$_POST['fcamp']);
            die();   
     }
     else{
         header("Location: raw2.php?client=".$client.",".$column);
            die();
     }
     
 }

//Adding Campaign Start date of the campaign
$conn = new mysqli($servername, $username, $password,$dbname);
$check="select date from $client order by date asc limit 1";
$result22=mysqli_query($conn,$check);
$row22=mysqli_fetch_assoc($result22);
$firstdate= $row22['date'];
 
//Adding Campaign Last date of the campaign
$check2="select date from $client order by date desc limit 1";
$result23=mysqli_query($conn,$check2);
$row23=mysqli_fetch_assoc($result23);
$lastdate= $row23['date'];



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
    <link rel="stylesheet" href="stylea.css">
    <title>Analytic</title>
</head>

<body>
    
    <div class="chrt" >
       <div class="chrt_dt" id="divToExport">
              <div class="hc_logo">
            <img src="https://s.hcurvecdn.com/hc_logo.png" alt="logo" />
        </div>
          
        <form method="post" class="container lg">
         <button name="back" class="btn">Back</button>
         </form>
      
                <?php
               
                $sql="SELECT cname FROM `clientname` WHERE dbname = '$client' LIMIT 1";
                $row = mysqli_fetch_assoc($conn->query($sql) );
                    // echo "$campaign <br>" ;
                    // echo "$firstdate to $lastdate";
                     if(!empty($fromdate)&&!empty($todate))
                    {
                        $conn = new mysqli($servername, $username, $password,$dbname);
$check1="select date from $client order by date asc limit 1";
$result221=mysqli_query($conn,$check1);
$row221=mysqli_fetch_assoc($result221);
$firstdate1= $row221['date'];
 
//Adding Campaign Last date of the campaign
$check12="select date from $client order by date desc limit 1";
$result231=mysqli_query($conn,$check12);
$row231=mysqli_fetch_assoc($result231);
$lastdate1= $row231['date'];
    
                        
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
                        <input type="date" id="fdate" name="fdate" value="<?php echo $firstdate ?>"/>
                        <label><div id="to">to</div></label>
                        <input type="date" id="ldate"  name="ldate" value="<?php echo $lastdate ?>"/>
                    
                        <button  class="mayu" name="submit">
                        <!-- <i  class="fa-solid fa-arrow-right"></i> -->
                        Go
                    </button>
           
                </div>
               
            </div>
              <div class="xyz" style="display:flex;flex-direction: row-reverse;margin-right:30px">
              <button class="submit_btn" name="filter" style="    margin-top: 10px">Submit</button>
        <select name="fcamp" id="fcamp" style="border:1px solid black;margin-left:6px;" class="fcamp">
                        <option name = "campvalue" value="">Select Campaign</option>
                    <?php
                        $sql = "select distinct campaign from $client";
                            $result = $conn->query($sql);
                            while ($row = $result->fetch_assoc())
                            {
                                echo '<option  name = "campvalue" value="'.$row["campaign"].'">'.$row["campaign"].'</option>';
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
            <h4>ID-Impression Distribution</h4>
          </div>
        </div>
         
      </div >
      <div id="swiper">
      <div class="swiper_container" >
        <div class="swiper mySwiper">
          <div class="swiper-wrapper" id="wrapper"></div>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        
      </div>

      </div>   
        <div class="chrt_r_2" >
        <div class="chrt_d">
            <h4 style="text-align:center;font-size:14px"><?php echo ucfirst($column)?> Wise Performance</h4>
                <div class="dt_sel" style="justify-content: flex-start;">
      <label><strong> Select Cohort - </strong> </label>
        <select name="fcol" id="fcol" style="border:2px solid #24b2be;border-radius:3px" class="fcol">
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
        <?php $url = "support.php?client=".$client.",".$_POST["fcamp"];?>
         <div style="text-align:center"><a href="<?php echo $url;?>" >Need Help?</a></div>
        </div>
                   
    </div>
      
        </div>   
       
    </div>
    <!--</div>-->
</body>
</html>
<script>
// Recommendation swiper
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
    var dyn = <?php echo json_encode($dyn) ?>; //dynamic item from the cohort
    var ctr = <?php echo json_encode($ctr) ?>;//sum of ctr 
    var mainctr = <?php echo json_encode(number_format((array_sum($clicks)/$impcheck)*100,2))?>;
    var imps = <?php echo json_encode($imp) ?>;//sum of the impressions 
    var dist = <?php echo json_encode($dist) ?>; // array of distribution

    var ctr_sum = 0;
    var imp_sum = 0;
    var dist_sum=0;
    
  

    
 function recom(){
     ctr.forEach(ctr => {
            ctr_sum += Number(ctr)
        } )
        
        imps.forEach(imps => {
            imp_sum += Number(imps)
        } )
        
        dist.forEach(dist => {
            dist_sum += Number(dist)
        } )
        
        dyn.forEach((dyn,i) => {
            if(Number(dist[i]) < 5 && Number(ctr[i]) == Number(mainctr)){
            
                var el = document.createElement('div');
                el.className = "swiper-slide";
                el.id = "swiper-slide"+i;
                
                el.style = "outline:4px solid green"
                document.getElementById("wrapper").appendChild(el)
                var e3 = document.createElement('div');
                e3.className = "swip_card";
                e3.style="padding-top:-20px";
                e3.innerHTML = "<span style='font-size:15px'> ID is very low on <span style='color:#24b2be;font-family: 'RobotoB;'> " +dyn+ "</span> and CTR is equal to Avg CTR.<br> HC Recommends : <span style='color:#24b2be;font-family: 'RobotoB';>  Increase</span> the ID on <span style='color:#24b2be;font-family: 'RobotoB';>  " +dyn+ "</span>.</span>";
              document.getElementById("swiper-slide"+i).appendChild(e3);
            }
            else if(Number(dist[i]) >=15  &&( Number(ctr[i]) > Number(mainctr - 0.04) && (Number(ctr[i]< Number(mainctr))))){

                var el = document.createElement('div');
                el.className = "swiper-slide";
                el.id = "swiper-slide"+i;
                
                var percent =Math.abs(Math.floor((Number(mainctr)-Number(ctr[i]))/((Number(mainctr)+Number(ctr[i]))/2)*100));
               
                
                el.style = "outline:4px solid green"
                document.getElementById("wrapper").appendChild(el)
                var e3 = document.createElement('div');
                e3.className = "swip_card";
                e3.style="padding-top:-20px";
                e3.innerHTML = "<span style='font-size:15px'> ID is high on <span style='color:#24b2be;font-family: 'RobotoB;'> " +dyn+ "</span> and CTR is <span style='color:#24b2be;font-family: 'RobotoB';>  "+percent+"% </span> lower Which is close to Avg CTR.<br> HC Recommends : <span style='color:#24b2be;font-family: 'RobotoB';>  Increase</span> the ID on <span style='color:#24b2be;font-family: 'RobotoB';>  " +dyn+ "</span>.</span>";
              
              document.getElementById("swiper-slide"+i).appendChild(e3);
            }
             else if(Number(dist[i]) <= 0.09 && Number(ctr[i]) > Number(mainctr)){

                var el = document.createElement('div');
                el.className = "swiper-slide";
                el.id = "swiper-slide"+i;
                
                var percent =Math.abs(Math.floor((Number(mainctr)-Number(ctr[i]))/((Number(mainctr)+Number(ctr[i]))/2)*100));
               
                
                el.style = "outline:4px solid red"
                document.getElementById("wrapper").appendChild(el)
                var e3 = document.createElement('div');
                e3.className = "swip_card";
                e3.style="padding-top:-20px";
                e3.innerHTML = "<span style='font-size:15px'> ID is very low on <span style='color:red;font-family: 'RobotoB;'> " +dyn+ "</span> and CTR is higher than Avg CTR.<br> HC Recommends : Please check the set up on <span style='color:red;font-family: 'RobotoB';>  " +dyn+ "</span>.</span>";
              
              document.getElementById("swiper-slide"+i).appendChild(e3);
            }
             else if(Number(dist[i]) <= 10 && Number(ctr[i]) >= Number(mainctr)){

                var el = document.createElement('div');
                el.className = "swiper-slide";
                el.id = "swiper-slide"+i;
                
                var percent =Math.abs(Math.floor((Number(mainctr)-Number(ctr[i]))/((Number(mainctr)+Number(ctr[i]))/2)*100));
               
                
                el.style = "outline:4px solid green"
                document.getElementById("wrapper").appendChild(el)
                var e3 = document.createElement('div');
                e3.className = "swip_card";
                e3.style="padding-top:-20px";
                e3.innerHTML = "<span style='font-size:15px'> ID is very low on <span style='color:#24b2be;font-family: 'RobotoB';> " +dyn+ "</span> but CTR is <span style='color:#24b2be;font-family: 'RobotoB';>  "+percent+"% </span> high as compared to Avg CTR. <br> HC Recommends :<span style='color:#24b2be;font-family: 'RobotoB';>   Increase</span> the ID on <span style='color:#24b2be;font-family: 'RobotoB';> " +dyn+ "</span>.</span>";
              
              document.getElementById("swiper-slide"+i).appendChild(e3);
            }
            else if(Number(dist[i]) >= 13 && Number(ctr[i]) == Number(mainctr)){
            
                var el = document.createElement('div');
                el.className = "swiper-slide";
                el.id = "swiper-slide"+i;
                
                el.style = "outline:4px solid green"
                document.getElementById("wrapper").appendChild(el)
                var e3 = document.createElement('div');
                e3.className = "swip_card";
                e3.style="padding-top:-20px";
                e3.innerHTML = "<span style='font-size:15px'> ID is high and CTR is equal to Avg CTR.<br> HC Recommends:<span style='color:#24b2be;font-family: 'RobotoB';> SPEND MORE ON  " +dyn+ "</span>&#128077</span>";
              document.getElementById("swiper-slide"+i).appendChild(e3);
            }
            else if(Number(dist[i]) <= 15 && Number(dist[i]) >= 10 && Number(ctr[i]) >= Number(mainctr)){
            
                var el = document.createElement('div');
                el.className = "swiper-slide";
                el.id = "swiper-slide"+i;
                
                var percent =Math.abs(Math.floor((Number(mainctr)-Number(ctr[i]))/((Number(mainctr)+Number(ctr[i]))/2)*100));
                
                el.style = "outline:4px solid green"
                document.getElementById("wrapper").appendChild(el)
                var e3 = document.createElement('div');
                e3.className = "swip_card";
                e3.style="padding-top:-20px";
                
                e3.innerHTML = "<span style='font-size:15px'> ID is Avg on<span style='color:#24b2be;font-family: 'RobotoB;'> " +dyn+ "</span> and CTR is <span style='color:#24b2be;font-family: 'RobotoB';>  "+percent+"% </span> high as compared to Avg CTR.<br> HC Recommends : <span style='color:#24b2be;font-family: 'RobotoB';>  Increase</span> the ID on<span style='color:#24b2be;font-family: 'RobotoB';>  " +dyn+ "</span>.</span>";
              
              document.getElementById("swiper-slide"+i).appendChild(e3);
            }
            else if(Number(dist[i]) >= 15 && Number(ctr[i]) <= Number(mainctr)){
            
                var el = document.createElement('div');
                el.className = "swiper-slide";
                el.id = "swiper-slide"+i;
                var percent =Math.abs(Math.floor((Number(mainctr)-Number(ctr[i]))/((Number(mainctr)+Number(ctr[i]))/2)*100));
                
                
                el.style = "outline:4px solid red"
                document.getElementById("wrapper").appendChild(el)
                var e3 = document.createElement('div');
                e3.className = "swip_card";
                e3.style="padding-top:-20px";
                e3.innerHTML = "<span style='font-size:15px'> ID is high on <span style='color:red;font-family: 'RobotoB;'> " +dyn+ "</span> and CTR is <span style='color:red;font-family: 'RobotoB';>  "+percent+"% </span> lower as compared to Avg CTR.<br> HC Recommends : <span style='color:red;font-family: 'RobotoB';>  Decrease</span> the ID on <span style='color:red;font-family: 'RobotoB';>  " +dyn+ "</span>.</span>";
              
              document.getElementById("swiper-slide"+i).appendChild(e3);
            }
              else if(Number(dist[i]) >= 15 && Number(ctr[i]) > Number(mainctr)){
            
                var el = document.createElement('div');
                el.className = "swiper-slide";
                el.id = "swiper-slide"+i;
                var percent =Math.abs(Math.floor((Number(mainctr)-Number(ctr[i]))/((Number(mainctr)+Number(ctr[i]))/2)*100));
                
                
                el.style = "outline:4px solid green"
                document.getElementById("wrapper").appendChild(el)
                var e3 = document.createElement('div');
                e3.className = "swip_card";
                e3.style="padding-top:-20px";
                e3.innerHTML = "<span style='font-size:15px'> ID is high on <span style='color:#24b2be;font-family: 'RobotoB;'> " +dyn+ "</span> and CTR is <span style='color:#24b2be;font-family: 'RobotoB';>  "+percent+"% </span> higher as compared to Avg CTR.<br> HC Recommends : SPEND MORE ON <span style='color:#24b2be;font-family: 'RobotoB';>  " +dyn+ "</span>.</span>";
              
              document.getElementById("swiper-slide"+i).appendChild(e3);
            }
              else if(Number(dist[i]) < 10 && Number(ctr[i]) < Number(mainctr)){
            
                var el = document.createElement('div');
                el.className = "swiper-slide";
                el.id = "swiper-slide"+i;
                var percent =Math.abs(Math.floor((Number(mainctr)-Number(ctr[i]))/((Number(mainctr)+Number(ctr[i]))/2)*100));
                
                
                el.style = "outline:4px solid red"
                document.getElementById("wrapper").appendChild(el)
                var e3 = document.createElement('div');
                e3.className = "swip_card";
                e3.style="padding-top:-20px";
                e3.innerHTML = "<span style='font-size:15px'> ID is very low on <span style='color:red;font-family: 'RobotoB;'> " +dyn+ "</span> and CTR is <span style='color:red;font-family: 'RobotoB';>  "+percent+"% </span> low as compared to Avg CTR.<br> HC Recommends :<span style='color:red;font-family: 'RobotoB;'> Decrease</span> the ID on  <span style='color:red;font-family: 'RobotoB';>  " +dyn+ "</span>.</span>";
              
              document.getElementById("swiper-slide"+i).appendChild(e3);
            }
            else{
                  var el = document.createElement('div');
                el.className = "swiper-slide";
                el.id = "swiper-slide"+i;
                var percent =Math.abs(Math.floor((Number(mainctr)-Number(ctr[i]))/((Number(mainctr)+Number(ctr[i]))/2)*100));
                
                
                el.style = "outline:4px solid green"
                document.getElementById("wrapper").appendChild(el)
                var e3 = document.createElement('div');
                e3.className = "swip_card";
                e3.style="padding-top:-20px";
                e3.innerHTML = "<span style='font-size:15px'> Performance on <span style='color:#24b2be;font-family: 'RobotoB;'> " +dyn+ " </span>is <span style='color:#24b2be;font-family: 'RobotoB';>VERY GOOD </span><span style='font-size:50px;'>&#128077;</span></span>";
              
              document.getElementById("swiper-slide"+i).appendChild(e3);
            }
        })
        
        if(document.querySelector('.swiper-wrapper').innerHTML == ""){
            document.querySelector('#swiper').style.display = "none";
        }
    }
     recom();
   
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
      
         ctx.font = "bold 16px Roboto";
         
    //   } 
    
    var chrlabel = chart.config.data.labels[i].length > 15 ? chart.config.data.labels[i].slice(0,15)+"..." : chart.config.data.labels[i];
    
        ctx.fillText(chrlabel, yPosition, xPosition);
      
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
                    chart.config._config.data.datasets[2].minBarLength = "150";
                    chart.update();
                }else{
                    // console.log(chart.config._config.data.datasets[2].minBarLength)
                    chart.config._config.data.datasets[2].minBarLength = "200";
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
    labels: <?php echo json_encode($dyn); ?>.slice(0,50),
    datasets: [
      {
        label: "CTR",
        data: <?php echo json_encode($ctr); ?>.slice(0,50),
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
        data: <?php echo json_encode($dist); ?>.slice(0,50),
        backgroundColor: [
          "#F47958",
          "#F47958",
          "#F47958",
          "#F47958",
          "#F47958",
          "#F47958",
        ],
        minBarLength: "350",
        barPercentage: 0.7,
        borderSkipped: false,
        hoverOffset: 4,
        
      },
      {
        label: "Impression",
        data: <?php echo json_encode($imp); ?>.slice(0,50),
        backgroundColor: [
          "#29AFBA",
          "#29AFBA",
          "#29AFBA",
          "#29AFBA",
          "#29AFBA",
          "#29AFBA",
        ],
        minBarLength: "450",
        barPercentage: 0.7,
        borderSkipped: false,
        hoverOffset: 4,
      },
      {
        label: "Click",
        data: <?php echo json_encode($clicks); ?>.slice(0,50),
        backgroundColor: [
          "white",
          "white",
          "white",
          "white",
          "white",
          "white",
          
        ],
        // color:"white",
        minBarLength: "2900",
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
        position:"nearest",
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
              size:18,
    
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
    document.getElementById("dte").style.cssText = "width:100%;height:100%;font-size:20px;font-weight:bold;color:black;text-align:center;padding-top:10%"
    document.getElementById("dte").innerHTML = "Not Applicable"
}
</script>
<script>
// When form gets reload after applying filter the value will be displayed in input
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
    
    var col = <?php echo json_encode($column) ?>;
    console.log(col);
    if(col != null )
    {
        document.getElementById('fcol').value = col;
    }
</script>

