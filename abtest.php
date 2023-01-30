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
// echo $client;
$conn = new mysqli($servername, $username, $password,$dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    echo "Fail";
}
else{
    //   echo "sucess";
       $sql = "select distinct moment,count(DISTINCT template) as template from abtest where client='$client' group by template";
                                        $result = $conn->query($sql);
                                        while ($row = $result->fetch_assoc())
                                            {
                                                $temp[] = $row['template'];
                                                $moment = $row['moment'];
                                            }
    }

?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/simplePagination.js/1.6/jquery.simplePagination.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />

    <title>Template</title>
     <style>
        #livee {
            background-color: red;
        }

        .fnt1 {
            font-size: 14px;
        }

        #con {
            box-shadow: 4px 4px 6px rgba(0, 0, 0, .5);
            background-color: #f7f7f7e0;
            border: none;
            border-radius: 15px;
        }

        #con1 {
            box-shadow: 4px 4px 6px rgba(0, 0, 0, .5);
            width: 95%;
            border: none;
            border-radius: 15px;
        }

        thead {
            background-color: #4792D3;
        }

        .btns {
            margin-top: 5px;
            background-color: rgb(12, 12, 177);
            border: none rgb(12, 12, 177);
            border-radius: 15px;
            height: 40px;
            width: 160px;
            padding: 5px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }



        .gauge-wrapper {
            display: inline-block;
            width: auto;
            margin: 0 auto;
            padding: 20px 15px 15px;
        }

        .gauge {
            background: #e7e7e7;
            box-shadow: 0 -3px 6px 2px rgba(0, 0, 0, 0.50);
            width: 200px;
            height: 100px;
            border-radius: 100px 100px 0 0 !important;
            position: relative;
            overflow: hidden;
        }

        .gauge.min-scaled {
            transform: scale(0.5);
        }

        .gauge-center {
            content: '';
            color: #fff;
            width: 60%;
            height: 60%;
            background: #15222E;
            border-radius: 100px 100px 0 0 !important;
            position: absolute;
            box-shadow: 0 -13px 15px -10px rgba(0, 0, 0, 0.28);
            right: 21%;
            bottom: 0;
            color: #fff;
            z-index: 10;
        }

        .gauge-center .label,
        .gauge-center .number {
            display: block;
            width: 100%;
            text-align: center;
            border: 0 !important;
        }

        .gauge-center .label {
            font-size: 0.75em;
            opacity: 0.6;
            margin: 1.1em 0 0.3em 0;
        }

        .gauge-center .number {
            font-size: 1.2em;
        }

        .needle {
            width: 80px;
            height: 7px;
            background: #15222E;
            border-bottom-left-radius: 100% !important;
            border-bottom-right-radius: 5px !important;
            border-top-left-radius: 100% !important;
            border-top-right-radius: 5px !important;
            position: absolute;
            bottom: -2px;
            left: 20px;
            transform-origin: 100% 4px;
            transform: rotate(0deg);
            box-shadow: 0 2px 2px 1px rgba(0, 0, 0, 0.38);
            display: none;
            z-index: 9;
        }

        .four.rischio1 .needle {
            animation: fourspeed1 2s 1 both;
            animation-delay: 1s;
            display: block;
        }

        .four.rischio2 .needle {
            animation: fourspeed2 2s 1 both;
            animation-delay: 1s;
            display: block;
        }

        .four.rischio3 .needle {
            animation: fourspeed3 2s 1 both;
            animation-delay: 1s;
            display: block;
        }

        .four.rischio4 .needle {
            animation: fourspeed4 2s 1 both;
            animation-delay: 1s;
            display: block;
        }

        .slice-colors {
            height: 100%;
        }

        .slice-colors .st {
            position: absolute;
            bottom: 0;
            width: 0;
            height: 0;
            border: 50px solid transparent;
        }


        .four .slice-colors .st.slice-item:nth-child(2) {
            border-top: 50px #f1c40f solid;
            border-right: 50px #f1c40f solid;
            background-color: #1eaa59;
        }

        .four .slice-colors .st.slice-item:nth-child(4) {
            left: 50%;
            border-bottom: 50px #E84C3D solid;
            border-right: 50px #E84C3D solid;
            background-color: #e67e22;
        }


        @-webkit-keyframes fourspeed1 {
            0% {
                transform: rotate(0);
            }

            100% {
                transform: rotate(16deg);
            }
        }

        @-webkit-keyframes fourspeed2 {
            0% {
                transform: rotate(0);
            }

            100% {
                transform: rotate(65deg);
            }
        }

        @-webkit-keyframes fourspeed3 {
            0% {
                transform: rotate(0);
            }

            100% {
                transform: rotate(115deg);
            }
        }

        @-webkit-keyframes fourspeed4 {
            0% {
                transform: rotate(0);
            }

            100% {
                transform: rotate(164deg);
            }
        }

        @media screen and (max-width:600px) {

            #contt {
                margin: 3px 0 0 -3px;
                height: 200px;
                font-size: 12px;
            }
        }

        @media screen and (max-width:400px) {

            #contt {
                margin: 3px 0 0 -3px;
                height: 191px;

                font-size: 10px;
            }
        }

        @media screen and (max-width:600px) {

            .last {
                display: flex;
                /* flex-direction: column;*/
                justify-content: space-between;
                margin: 0 auto;
                flex: 0 0 0%;
            }

        }
        @media screen and (max-width:570px) {

.btns {
    width: 75px;
    height: 40px;
    font-size: 12px;
    margin-top: 298px;
}

}
        /* @media screen and (max-width:400px) {

            .btns {
                width: 75px;
                height: 40px;
                font-size: 12px;
                margin-top: 268px;
            }

        } */


        @media screen and (max-width:700px) {

            #con {

                font-size: 14px;
            }
        }

        @media screen and (max-width:400px) {
            .gauge {
                height: 80px;
                width: 150px;
            }

            .needle {
                width: 60px;
                height: 7px;
                background: #15222E;
                border-bottom-left-radius: 100% !important;
                border-bottom-right-radius: 5px !important;
                border-top-left-radius: 100% !important;
                border-top-right-radius: 5px !important;
                position: absolute;
                bottom: 2px;
                left: 8px;
                transform-origin: 100% 4px center;
                transform: rotate(0deg);
                box-shadow: 0 2px 2px 1px rgba(0, 0, 0, 0.38);
                display: none;
                z-index: 9;
            }

        }


        /* For mobile phones:
[class*="col-"] {
  width: 100%;
}*/


        .page1 {

            top: 50px;
        }

        .light-theme ul {
            display: flex;
            top: 10px;
            flex-direction: row;
            align-items: center;
            justify-content: center;
        }

        #num li {
            list-style-type: none;
            margin: 10px 0px;
            padding: 6px 10px;
            /* border: 1px solid black; */
        }

        #num2 li {
            list-style-type: none;
            margin: 10px 0px;
            padding: 6px 10px;
            /* border: 1px solid black; */
        }

        #num3 li {
            list-style-type: none;
            margin: 10px 0px;
            padding: 6px 10px;
            /* border: 1px solid black; */
        }
    </style>
</head>


<body>


    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>



    <!-- NAVBAR -->
    <nav class="navbar navbar-dark navbar-expand-lg" style="background-color: rgb(4, 4, 50);">
        <a class="navbar-brand" href="#"><img src="https://s.hcurvecdn.com/aprimeautotest/logohc/logo.png" alt="logo"
                style="height: 30px; width:140px;"></a>
        <div class="text-white mx-auto" style="font-size: 18px;">
            A/B Testing Dashboard
        </div>
        <div class="navi" style="margin-right: 10px;">
            <span class="navbar-toggler-icon">
                <!--<i class="fas fa-bars" style="color:white; font-size:24px"></i>-->
            </span>

        </div>

    </nav>


    <!-- 1st Sec -->
    <div class="container mt-0" id="con">
        <div class="row mx-auto">

            <div class="col  text-center"> <span style="color: rgb(11, 11, 120);"><b> Hello Team Rapido</b></span>
            </div>
        </div>
        <div class="row mt-2 mx-auto">
            <div class="col  text-center "><span
                    style="color: white; background-color: rgb(11, 11, 120); border-radius: 15px; font-size: 13px;">&nbsp;&nbsp;&nbsp;
                    Rapido GEO Location Campaign&nbsp;&nbsp;&nbsp;</span></div>
        </div>
        <div class="row mt-3 mx-auto">
            <div class="col col-lg-3 col-md-6 text-center "><b> A/B Test Version</b>
                <div class="ver">
                    <h1 style="color: red; border-right: 1px solid grey ;" ><?php echo array_sum($temp) ?></h1>
                </div>
                <div class="fnt1">
                    Creative Design
                </div>
            </div>
            <div class="col col-lg-3 col-md-6 text-center "><b>Moments</b>
                <div class="mmnt">
                    <h1 style="color: rgb(4, 4, 50); border-right: 1px solid grey ;"><?php echo $moment ?></h1>
                </div>
                <div class="fnt1">
                    API Based
                </div>
            </div>
            <div class="col col-lg-3 col-md-6 text-center "><b> HC Recommends</b>
                <div class="rec">
                    <h1 style="color: green; border-right: 1px solid grey ;">4+</h1>
                </div>
                <div class="fnt1">
                    Creative template
                </div>
            </div>
            <div class="col col-lg-3 col-md-6 text-center"><b> Performance Risk Meter</b>
                <div class="meter">
                    <div class="gauge-wrapper">
                        <div class="gauge four rischio3">
                            <div class="slice-colors">
                                <div class="st slice-item"></div>
                                <div class="st slice-item"></div>
                                <div class="st slice-item"></div>
                                <div class="st slice-item"></div>
                            </div>
                            <div class="needle"></div>
                            <div class="gauge-center">

                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p> low</b>
                        <p>high</p>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <!-- 2nd Sec -->
    <div class="container">
        <div class="row">

            <div class="col-8 mx-auto mt-3 text-center" id="con1">
                <!--<div style="background-color: rgb(247, 246, 246);">
                    <img src="live.png" class="rounded float-right" alt="live-logo"></div>-->
                <div style="background-color: rgb(247, 246, 246);">
                    <div class="text-white text-end"><strong
                            style="background-color: red; border-top-right-radius: 5px;">Live</strong></div>
                    In <b> Rapido Geo Based Campaign </b>there are <b> 2
                        options and 5 moments </b>live <br><br>
                    <h3 style="font-weight:700;"> We Recommended You to add </h3>
                    <h2 style="color: rgb(12, 12, 177); font-weight:600;"> 4 more options!!</h2>
                </div>
                <div style="color: red; font-size: 12px;">
                    Running one option may result in below average campaign performance
                </div>
                <hr style="width: 350px; margin:5px auto; color: black;">
                <div class="d-flex justify-content-evenly last">
                    <h5>recommended Templates <b style="color: rgb(12, 12, 177);"> FOR YOU!</b>
                    </h5>
                    <button
                        style="font-size: 10px; border: 1px solid grey; border-radius: 15px; width: 146px; height: 20px;">Coming
                        Soon</button>
                </div>
            </div>
        </div>
    </div>


    <!-- final Sec -->
    <div class="container" id="contt">
        <div class="row">
            <div class="col-8 mx-auto mt-3 text-center" id="con1">
                <div id="carouselExampleInterval" class="carousel slide" data-ride="carousel" data-bs-interval="500" data-bs-pause="false">
                    <!--data-bs-ride="carousel"(to run c auto)-->
                    <div class="carousel-inner">
                        <div class="carousel-item active" data-bs-interval="flase" ><!--data-bs-interval="10000"-->

                            <table class="table">
                                <div class="d-flex justify-content-between"> <b>Carousel</b> <b> Performance </b></div>
                                <thead class="thead-dark text-white">
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">Impressions</th>
                                        <th scope="col">Clicks</th>
                                        <th scope="col">CTR</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                         $sql = "select sum(imp) as imp,sum(clicks) as click,sum(ctr) as ctr, filter from abtest where client='$client' group by filter";
                                        $result = $conn->query($sql);
                                        while ($row = $result->fetch_assoc())
                                            {
                                               
                                              echo '<tr class="post">
                                            <th scope="row" style="text-align:left;">'.$row['filter'].'</th>
    
                                            <td
                                                style="border-top-left-radius:10px; border-bottom-left-radius: 10px; background-color: #FFFBF1;">
                                                '.number_format($row['imp']).'
                                            </td>
                                            </td>
                                            <td style="background-color:#FFFBF1;">'.$row['click'].'</td>
                                            <td style="background-color:#FFFBF1;">'.number_format(($row['click']/$row['imp'])*100,2).'%</td>
    
                                        </tr>';
                                                
                                            }
                                        
                                    ?>
                                   
                                </tbody>
                            </table>
                            <div  id="num"></div>
                        </div>

                        <!--2nd-->

                        <div class="carousel-item" data-bs-interval="flase" ><!--data-bs-interval="2000"-->
                            <table class="table2 table">
                                <div class="d-flex justify-content-between"> <b>Carousel</b> <b> Performance </b></div>
                                <thead class="thead-dark text-white">
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">Impressions</th>
                                        <th scope="col">Clicks</th>
                                        <th scope="col">CTR</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sql = "select sum(imp) as imp,sum(clicks) as click,sum(ctr) as ctr, event from abtest where client='$client' group by event";
                                        $result = $conn->query($sql);
                                        while ($row = $result->fetch_assoc())
                                            {
                                               
                                              echo '<tr class="post2">
                                            <th scope="row" style="text-align:left;">'.$row['events'].'</th>
    
                                            <td
                                                style="border-top-left-radius:10px; border-bottom-left-radius: 10px; background-color: #FFFBF1;">
                                                '.number_format($row['imp']).'
                                            </td>
                                            </td>
                                            <td style="background-color:#FFFBF1;">'.$row['click'].'</td>
                                            <td style="background-color:#FFFBF1;">'.number_format(($row['click']/$row['imp'])*100,2).'%</td>
    
                                        </tr>';
                                                
                                            }
                                        
                                    ?>
                                </tbody>
                            </table>
                            <div  id="num2"></div>
                        </div>
                        <div class="carousel-item" data-bs-interval="flase" ><!--data-bs-interval="2000"-->
                            <table class="table3 table">
                                <div class="d-flex justify-content-between"> <b>Carousel</b> <b> Performance </b></div>
                                <thead class="thead-dark text-white">
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">Impressions</th>
                                        <th scope="col">Clicks</th>
                                        <th scope="col">CTR</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="post3">
                                        <th scope="row" style="text-align:left;">Rapido1</th>

                                        <td
                                            style="border-top-left-radius:10px; border-bottom-left-radius: 10px; background-color: #FFFBF1;">
                                            13.5K
                                        </td>
                                        <td style="background-color:#FFFBF1;">2.06K</td>
                                        <td style="background-color:#FFFBF1;">0.28%</td>

                                    </tr>
                                    <tr class="post3">
                                        <th scope="row" style="text-align:left;">Rapido2</th>
                                        <td
                                            style="border-top-left-radius:10px; border-bottom-left-radius: 10px; background-color: #FFFBF1;">
                                            13.5K
                                        </td>
                                        <td style="background-color:#FFFBF1;">2.06K</td>
                                        <td style="background-color:#FFFBF1;">0.28%</td>
                                    </tr>
                                    <tr class="post3">
                                        <th scope="row" style="text-align:left;">Rapido3</th>
                                        <td
                                            style="border-top-left-radius:10px; border-bottom-left-radius: 10px; background-color: #FFFBF1;">
                                            13.5K
                                        </td>
                                        <td style="background-color:#FFFBF1;">2.06K</td>
                                        <td style="background-color:#FFFBF1;">0.28%</td>
                                    </tr>
                                    <tr class="post3">
                                        <th scope="row" style="text-align:left;">Rapido4</th>
                                        <td
                                            style="border-top-left-radius:10px; border-bottom-left-radius: 10px; background-color: #FFFBF1;">
                                            13.5K
                                        </td>
                                        <td style="background-color:#FFFBF1;">2.06K</td>
                                        <td style="background-color:#FFFBF1;">0.28%</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div  id="num3"></div>
                        </div>


                    </div>

                </div>
            </div>
        </div>
    </div>

    <br>
    <div class="container mt-0">
        <div class="row mx-auto">
            <div class="col text-center d-flex justify-content-evenly last" id="final">
                <button class="btns" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="false"></span>
                    <strong> Back</strong>
                </button>
                <button class="btns" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
                    <b>Next</b> <span class="carousel-control-next-icon" aria-hidden="false"></span>

                </button>
            </div>
        </div>
    </div>
    <br><br>

<script>
        // alert("hello");
        var items = $(".table .post");
        var numItems = items.length;
        var perPage = 10;

        items.slice(perPage).hide();

        $('#num').pagination({
            items: numItems,
            itemsOnPage: perPage,
            prevText: "",
            nextText: "",
            onPageClick: function (pageNumber) {
                var showFrom = perPage * (pageNumber - 1);
                var showTo = showFrom + perPage;
                items.hide().slice(showFrom, showTo).show();
            }
        });
    </script>
    <script>

        var items1 = $(".table2 .post2");
        var numItems1 = items1.length;
        var perPage1 = 10;

        items1.slice(perPage1).hide();

        $('#num2').pagination({
            items: numItems1,
            itemsOnPage: perPage1,
            prevText: "",
            nextText: "",
            onPageClick: function (pageNumber) {
                var showFrom1 = perPage1 * (pageNumber - 1);
                var showTo1 = showFrom1 + perPage1;
                items1.hide().slice(showFrom1, showTo1).show();
            }
        });
    </script>
    <script>

        var items2 = $(".table3 .post3");
        var numItems2 = items2.length;
        var perPage2 = 10;

        items2.slice(perPage2).hide();

        $('#num3').pagination({
            items: numItems2,
            itemsOnPage: perPage2,
            prevText: "",
            nextText: "",
            onPageClick: function (pageNumber) {
                var showFrom2 = perPage2 * (pageNumber - 1);
                var showTo2 = showFrom2 + perPage2;
                items2.hide().slice(showFrom2, showTo2).show();
            }
        });

//         $('.carousel').carousel({
//   interval: false,
// });

    </script>

</body>

</html>