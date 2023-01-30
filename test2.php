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

$sql = "select sum(clicks) as clicks,sum(imp)as imp,campaign from $client where campaign = '$campaign' group by campaign order by imp desc";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc())
    {
        $imp[] = $row['imp'];
        $clicks[] = $row['clicks'];
    }
    
    foreach($imp as $im_vl){
        echo "<span>Imps</span>: ".$im_vl;
    }
    
    foreach($clicks as $ck_vl){
        echo "<span>Clicks</span>: ".$ck_vl;
    }

// else{
//     // removing column from table
//     $replace ="SELECT REPLACE(GROUP_CONCAT(COLUMN_NAME), 'client,imp,clicks,ctr,date,','')
//                 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$client'
//                 AND TABLE_SCHEMA = 'publishe_analytic'";
//     $replaced = $conn->query($replace);
//     $row = $replaced->fetch_assoc();
//     $value = $_POST['fcamp']; 
//     // echo $value;
    
//      foreach($row as $x => $x_value)
//     {
//       $name = $x_value;  
//       $eventc = explode (",", $name);
      
//     } 
//     $len = count($eventc);
//     for($adi=0;$adi<$len;$adi++){
          
//           $sqladi = "select $eventc[$adi] FROM $client WHERE campaign='$value' ORDER BY $eventc[$adi] desc";
//           $replaced3=mysqli_query($conn,$sqladi);
//           $row3 = $replaced3->fetch_assoc();
          
//           $hello= $row3[$eventc[$adi]];
//           if($hello=="0"){
//              $yenahihai.=$adi.",";
//           }
//       }
     
//      $hogaya = explode(",",$yenahihai);
//      for($js=0;$js<count($hogaya);$js++){
//          unset($eventc[$hogaya[$js]]);
//      }
    
    
    
//     // condition will work if button gets clicked
//     if(isset($_POST["submit"]) ||isset($_POST["submit1"]) || isset($_POST["filter"]))
//     {
//         if(!empty($_POST["fdate"]))
//         {
//             $fromdate = $_POST["fdate"];
   
//         }
//         if(!empty($_POST["ldate"]))
//         {
//             $todate = $_POST["ldate"];
//         }
//       if(!empty($_POST["fcamp"]))
//         {
//              $campaign = $_POST["fcamp"];
//         }
       
//         $column = $_POST["fcol"];
        
        
//         // $subcampaign = $_POST["fscamp"];
   
//   if(empty($fromdate)&&empty($todate)&&!empty($column)&&empty($campaign))
//   {
//       $sql = "select sum(clicks) as clicks,sum(imp)as imp,$column from $client where $column <> '0' and $column <> '' group by $column order by imp desc";
        
//   }
//   else if(empty($fromdate)&&empty($todate)&&empty($column)&&!empty($campaign))
//   {
//       $sql = "select sum(clicks) as clicks,sum(imp)as imp,campaign from $client where campaign = '$campaign' group by campaign order by  desc";
     
//     //   echo $sql;
//   }
//   else if(empty($fromdate)&&empty($todate)&&!empty($column)&&!empty($campaign))
//   {
//       $sql = "select sum(clicks) as clicks,sum(imp)as imp,$column  from $client where campaign = '$campaign' and $column <> '0' and $column <> '' group by $column,campaign order by imp desc ";

//   }
//   else if(!empty($fromdate)&&!empty($todate)&&!empty($column)&&!empty($campaign))
//   {
//       $sql = "select sum(clicks) as clicks,sum(imp)as imp,$column from $client where date between '$fromdate' and '$todate' and campaign = 
//      '$campaign' and $column <> '0' and $column <> '' group by $column,campaign order by imp desc";
    
//     //   echo $sql;
//   }
//   else if(!empty($fromdate)&&!empty($todate)&&!empty($column)&&empty($campaign))
//   {
//       $sql = "select sum(clicks) as clicks,sum(imp)as imp,$column from $client where date between '$fromdate' and '$todate' and $column <> '0' and $column <> '' group by $column order by imp desc ";
    
//     //   echo $sql;
//   }
//   else if(!empty($fromdate)&&!empty($todate)&&empty($column)&&empty($campaign))
//   {
//       $sql = "select date,sum(clicks) as clicks,sum(imp)as imp,dimension from $client where date between '$fromdate' and '$todate' group by date  order by imp desc";
    
//     //   echo $sql;
//   }
//   else
//   {
//       $sql = "select date,sum(clicks) as clicks,sum(imp)as imp,dimension from $client where date between '$fromdate' and '$todate' group by date order by imp desc";
//     //   echo $sql;
//   }
   
// //   date
//     $result = $conn->query($sql);
//     while ($row = $result->fetch_assoc())
//     {
       
//         $imp[] = $row['imp'];
//         // $dist[] = round((($row['imp'])/(array_sum($imp)))*100,2);
//          $ctr[] = number_format(($row['clicks']/$row['imp'])*100,2);
        
//         $clicks[] = $row['clicks'];
//         if(!empty($column) )
//         {
//             if(strlen($row[$column]) > 20)
//             {
//                  $dyn[] = substr($row[$column],0,20)."...";
//             }
//             else
//             {
//                 $dyn[] = $row[$column];
//             }
            
//         }
//         else if (!empty($campaign))
//         {
//             if(strlen($row['campaign']) > 20)
//             {
//                  $dyn[] = substr($row['campaign'],0,20)."...";
//             }
//             else
//             {
//                 $dyn[] = $row['campaign'];
//             }
//         }
//         else if (!empty($subcampaign))
//         {
//             if(strlen($row['subcampaign']) > 20)
//             {
//                  $dyn[] = substr($row['subcampaign'],0,20)."...";
//             }
//             else
//             {
//                 $dyn[] = $row['subcampaign'];
//             }
//         }
//         else
//         {
//             $datee[] = date("M d", strtotime($row['date']));
            
//         }
           
        
//         // echo $row['date'];
//     }
//     // print_r($datee);
//     $impcheck = array_sum($imp);
//     //   dim

     
     
//       $perct = (int)(array_sum($imp)*0.10);
//       $fifty = (int)(array_sum($imp)*0.50);
      
//      foreach ($imp as $value)
//     {
//         $dist[] = number_format(($value/$impcheck)*100,2);
//     }
//     }
// else if($column != "")
// {
//     $sql = "select sum(clicks) as clicks,sum(imp)as imp,campaign from $client where campaign = '$campaign' group by campaign order by imp desc";
//     //   echo $sql;
//     // echo $sql;
//     $result = $conn->query($sql);
//     while ($row = $result->fetch_assoc())
//     {
        
//         // $day[] = substr($row['day'],0,3);
//         $imp[] = $row['imp'];
//         $clicks[] = $row['clicks'];
//         //  $sum = array_sum($imp);
       
//         // echo ($row['imp']/array_sum($imp)*100)."<br>";
//         $ctr[] = number_format(($row['clicks']/$row['imp'])*100,2);
//         if(!empty($column))
//         {
//             if(strlen($row[$column]) > 20)
//             {
//                  $dyn[] = substr($row[$column],0,20)."...";
//             }
//             else
//             {
//                 $dyn[] = $row[$column];
//             }
            
//         }
//         else
//         {
//             $datee[] = date("M d", strtotime($row['date']));
            
//         }
//     }

//     $fromdate = $datee[0];
//         $todate = $datee[9];
//     // print_r($dist);    
//     $impcheck = array_sum($imp);
    
  
//      $perct = (int)(array_sum($imp)*0.10);
//      $fifty = (int)(array_sum($imp)*0.50);
     
//     foreach ($imp as $value)
//     {
//         $dist[] = number_format(($value/$impcheck)*100,2);
//     }
   
// }    
// else{
     
   
//     //   date chart

     
//     $len = count($eventc);
//     for($adi=0;$adi<$len;$adi++){
          
//           $sqladi = "select $eventc[$adi] FROM $client WHERE campaign='$value' ORDER BY $eventc[$adi] desc";
//           $replaced3=mysqli_query($conn,$sqladi);
//           $row3 = $replaced3->fetch_assoc();
          
//           $hello= $row3[$eventc[$adi]];
//           if($hello=="0"){
//              $yenahihai.=$adi.",";
//           }
//       }
     
//      $hogaya = explode(",",$yenahihai);
//      for($js=0;$js<count($hogaya);$js++){
//          unset($eventc[$hogaya[$js]]);
//      }
    
//     // echo $sql;
//     $result = $conn->query($sql);
//     while ($row = $result->fetch_assoc())
//     {
        
//         // $day[] = substr($row['day'],0,3);
//         $imp[] = $row['imp'];
//         $clicks[] = $row['clicks'];
//         //  $sum = array_sum($imp);
       
//         // echo ($row['imp']/array_sum($imp)*100)."<br>";
//         $ctr[] = number_format(($row['clicks']/$row['imp'])*100,2);
//         $dyn[] = $row['campaign'];
//     }

//     $fromdate = $datee[0];
//         $todate = $datee[9];
//     // print_r($dist);    
//     $impcheck = array_sum($imp);
    
  
//      $perct = (int)(array_sum($imp)*0.10);
//      $fifty = (int)(array_sum($imp)*0.50);
     
//     foreach ($imp as $value)
//     {
//         $dist[] = number_format(($value/$impcheck)*100,2);
//     }
   
   
   
//     }   
   

// }

// if(isset($_POST['back']))
//  {
//      header("Location: index2.php?client=".$client);
//             die();
//  }
//  else if(isset($_POST['csv']))
// {
//      if(!empty($_POST['fcol']) && empty($_POST['fcamp']))
//      {
//          header("Location: raw.php?client=".$client.",".$_POST['fcol']);
//             die();
    
//      }
//      else if(!empty($_POST['fcamp']) && !empty($_POST['fcol']))
//      {
//       header("Location: raw.php?client=".$client.",".$_POST['fcol'].",".$_POST['fcamp']);
//             die();   
//      }
//      else if(!empty($_POST['fcamp']) && empty($_POST['fcol']))
//      {
//       header("Location: raw.php?client=".$client.",".$column.",".$_POST['fcamp']);
//             die();   
//      }
//      else{
//          header("Location: raw.php?client=".$client.",".$column);
//             die();
//      }
     
//  }


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
    <div class="chrt" >
       
    </div>
    <!--</div>-->
</body>
</html

