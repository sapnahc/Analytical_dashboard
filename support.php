<?php
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
        // echo $client;
    }

if(isset($_POST['back']))
 {

//  header("Location: index2.php?client=".$client);
//  die();
//  echo $_POST["fcol"];
            //  header("Location: testp.php?client=".$client.",".$_POST["fcamp"]);
            die();
          
    
 }
 
  
 
 
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {font-family: Arial, Helvetica, sans-serif;}
* {box-sizing: border-box;}

/* Button used to open the contact form - fixed at the bottom of the page */
.open-button {
  background-color: white;
  color: rgb(35, 2, 90);
  padding: 16px 20px;
  cursor: pointer;
  opacity: 0.8;

  width: 200px;
  border: 2px solid orange;
  border-radius: 5px;
  font-size: 20px;
  justify-content: center;
  align-items: center;
  /* display: flex; */
}

/* The popup form - hidden by default */
.form-popup {
  display: none;
  position: absolute;
  bottom: 0;
  top: 0px;
  border: 3px solid #f1f1f1;
  z-index: 9;
}

/* Add styles to the form container */
.form-container {
  max-width: 300px;
  padding: 10px;
  background-color: white;
  justify-content: center;
  align-items: center;
  
}

/* Full-width input fields */
.form-container input[type=text], .form-container input[type=password] {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  border: none;
  background: #f1f1f1;
}

/* When the inputs get focus, do something */
.form-container input[type=text]:focus, .form-container input[type=password]:focus {
  background-color: #ddd;
  outline: none;
}

/* Set a style for the submit/login button */
.form-container .btn {
  background-color: white;
  border: 2px green solid;
  color: black;
  padding: 16px 20px;
  /* border: none; */
  border-radius: 5px;
  cursor: pointer;
  width: 100%;
  margin-bottom:10px;
  opacity: 0.8;
  font-size: 14px;
}

/* Add a red background color to the cancel button */
.form-container .cancel {
  color: black;
  border: 2px red solid;
}

/* Add some hover effects to buttons */
.form-container .btn:hover, .open-button:hover {
  opacity: 1;
}
.support {
 
  width: 100%;
  height: auto;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
 
}
textarea {
  width: 100%;
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
  margin-top: 6px;
  margin-bottom: 16px;
  resize: vertical;
}


h3{
    color: #03754b;
}

 .btn2{
  border: 2px solid black;
  border-radius: 5px;
  background-color: white;
  text-decoration: none;
width: 60px;
height: 30px;
font-size: 14px;
color: black;
}
a{
  color: black;
}
a:link{
	     text-decoration: none;
	     color:black;
	}
 
 
</style>
</head>
<body>
     <!--<form method="post">-->
    <button class="btn2" name="back" onclick="history.back()">Back</button>
    <!--</form>-->
<div class="support">
  
<h1>Support</h1>


<button class="open-button" onclick="openForm()">Write-Us</button>

<div class="form-popup" id="myForm">
  <form method="post" class="form-container">
    <h1>Contact us</h1>

    <!--<label for="email"><b>Email</b></label>-->
    <!--<input type="text" placeholder="Enter Email" name="email" required>-->
    <label for="subject"><b>Subject</b></label>
    <input type="text" placeholder="Subject" name="subject" required>

    <label for="write">Write here</label>
    <textarea id="text" name="mail" placeholder="Write something.." style="height:200px" required></textarea>


    <button type="submit" name="send" class="btn">Send Mail</button>
    <button type="button" class="btn cancel" onclick="closeForm()">Close</button>
  </form>

</div>
</div>
<script>
function openForm() {
  document.getElementById("myForm").style.display = "block";
}

function closeForm() {
  document.getElementById("myForm").style.display = "none";
}
</script>

</body>
</html>
<?php
if(isset($_POST['send']))
{
//  $email = $_POST['email'];
 $subject = $_POST['subject'];
 $mail = $_POST['mail'];
//  echo $email."<br>";
//  echo $subject."<br>";
//  echo $mail."<br>";
 
     $to = "adithi@hockeycurve.com";
     $subject = $subject;
     $message = $mail;
     
    $header = "From:bizops@hockeycurve.com \r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-type: text/html\r\n";

                     
                     $retval = mail ($to,$subject,$message,$header);
                    //  echo $header;
                     if( $retval == true ) {
                        echo "Email was send successfully please close the window";
                        exit;
                     }else {
                        echo "Message could not be sent...";
                        echo $eail;
                     }
}
?>
