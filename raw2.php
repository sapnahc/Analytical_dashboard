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
    $subcamp = $value[2];
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
    //  echo "success";
    //  $replace ="SELECT REPLACE(GROUP_CONCAT(COLUMN_NAME), 'mainclient,imp,clicks,ctr,date,','')
    //             FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'analytics'
    //             AND TABLE_SCHEMA = 'publishe_analytic'";
    // $replaced = $conn->query($replace);
    // $row = $replaced->fetch_assoc();
    // foreach($row as $x => $x_value)
    // {
     
    //   $name = $x_value;  
    //   $eventc = explode (",", $name);
      
    // } 
   

      
   

}


?>
<!DOCTYPE html>
<html lang="en">

<head>
 
    
    <title>Analytic</title>
</head>

<body>
    <div class="container maindiv data" id="tab">
    <table>
        <tr>
                    <th>Date</th>
                    
                    <?php echo "<th>".$column."</th>"; ?>
                    <th>Impression</th>
                    <th>Clicks</th>
                    <th>Ctr</th> 
                    <!--<th>Hc Budget</th> -->
                    
                    
        </tr>
        <?php
        // if($column != "campaign")
        // {
        //  $sql = "select date,campaign,sum(clicks) as clicks,sum(imp)as imp,$column from $client group by date";
        // //  echo $sql;
        // }
        // else
        // {
        // }
        if(!empty($subcamp))
        {
             $sql = "select date,sum(clicks) as clicks,sum(imp)as imp,$column from $client where campaign = '$subcamp' group by $column order by $column asc" ;
        }
        else
        {
             $sql = "select date,sum(clicks) as clicks,sum(imp)as imp,$column from $client group by $column order by $column asc" ;
        }
           
    // echo $sql;
    $result = $conn->query($sql);
     while ($row = $result->fetch_assoc())
    {
           
        $imp[] = $row['imp'];
        $clicks[] = $row['clicks'];
       $colu = $row[$column] ;
        $ctr[] = number_format(($row['clicks']/$row['imp'])*100,2);
        // $clicks[] = $row['clicks'];
        echo "<tr>";
            echo "<td>" . $row['date'] . "</td>";
            // if($column != "campaign")
            // {   
            //     echo "<td>" . $row['campaign'] . "</td>";
            // } 
            echo "<td>" . $row[$column] . "</td>";
            echo "<td>" . number_format($row["imp"]) . "</td>";
            echo "<td>" . number_format($row['clicks']) . "</td>";
            echo "<td>" . number_format(($row['clicks']/$row['imp'])*100,2) . "% </td>";
         
        echo "</tr>";    
    }
    if($column != "campaign")
    {
        echo "<td></td>";
    }  
    else{
        echo "<td></td>";
    }
 
    echo " 
    <td><b>Sum</b></td>
    <td><b>".number_format(array_sum($imp))."</b></td>
    <td><b>".number_format(array_sum($clicks))."</b></td>
    <td><b>".number_format((array_sum($clicks)/array_sum($imp))*100,2)."% </b></td>";    
                                    
        ?>
    </table>
     <!--<button class="export">Export in CSV file</button>-->
    </div>
</body>
<script>
  var client = <?php echo json_encode($client);?>;
  var column = <?php echo json_encode($column);?>;
  var subcamp = <?php echo json_encode($subcamp);?>;

       function download_csv(csv, filename) {
    var csvFile;
    var downloadLink;

    // CSV FILE
    csvFile = new Blob([csv], {type: "text/csv"});

    // Download link
    downloadLink = document.createElement("a");

    // File name
    downloadLink.download = filename;

    // We have to create a link to the file
    downloadLink.href = window.URL.createObjectURL(csvFile);

    // Make sure that the link is not displayed
    downloadLink.style.display = "none";

    // Add the link to your DOM
    document.body.appendChild(downloadLink);

    // Lanzamos
    downloadLink.click();
    // alert("EE");
    window.location.replace("testp.php?client="+client+","+subcamp);
    }

   function export_table_to_csv(html, filename) {
	var csv = [];
	var rows = document.querySelectorAll(".data tr");
	
    for (var i = 0; i < rows.length; i++) {
		var row = []
        var cols = rows[i].querySelectorAll("td, th");
        // var cols = col.replace(",","");
        // cols = cols.replace(/,/g, "")
		
        for (var j = 0; j < cols.length; j++) 
            row.push(cols[j].innerText.replace(/,/g, ""));
        
		csv.push(row.join(","));		
	}

    // Download CSV
    download_csv(csv.join("\n"), filename);
 }

    function csv() {
    var html = document.querySelector("table").outerHTML;
	export_table_to_csv(html, "data.csv");
 }
 csv()
</script>
</html>

