<?php


include "navbar.php";

if (isset($_GET['loginFailed'])) {
    $message = "Invalid Credentials ! Please try again.";
    echo "<script type='text/javascript'>alert('$message');</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        .container8 {
            display: flex;
            justify-content: space-around;
            align-items: center;
            color: #fff;
            position: fixed;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            width: 95%;
            margin-top: 23%;
            height: 5px;


        }

        .container_content {
            width: 50%;
        }

        .container_content_inner {
            width: 100%;
            margin-left: 120px;
            margin-top:20px;
        }

        .container_outer_img {
            margin: 50px;
            margin-top: 10%;
            width: 80%;
            overflow: hidden;
        }

        .container_img {
            width: 40%;
            position: absolute;
            margin-left: 200px;
            margin-top: -270px;
        }

        .par {
            height: auto;
            overflow: hidden;
        }

        p
        { color: black;
            line-height: 28px;
        }
        .title2 {
            background: #2c3034;

            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-left: 1%;

        }
        h1 {
            font-size: 40px;
            color: black;
            margin-bottom: 20px;
        }

    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="home_style.css">

    <!-- bootstrap-css -->
    <link href="bootstrap.css" rel="stylesheet" type="text/css" media="all" />
    <!--// bootstrap-css -->


    <link type="fonts/css" rel="stylesheet" href="cm-overlay.css" />
    <!-- font-awesome icons -->
    <link href="fonts/font-awesome.css" rel="stylesheet">
    <!-- //font-awesome icons -->
    <!-- font -->

    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
</head>

<body>

<div class= 'container8'>
    <div class="container_content">
        <div class="container_content_inner">
            <div class="title2">
                <h1 >WELCOME <br> TO Dyna-Projects</h1>
            </div>

                <div class="btns">
                    <p>Dynamix Services est une entreprise spécialisée dans l'intégration des systèmes d'information,
                        en particulier dans les solutions ERP de Microsoft (Microsoft Dynamics NAV, Microsoft
                        Dynamics 365, INFOR SYTELINE), GMAO (INFOR EAM), Microsoft CRM, ainsi que dans
                        la solution de Business Intelligence QlikView. En tant que partenaire Microsoft, Dynamix
                        Services est certifié en tant qu'intégrateur de la solution Navision en Tunisie (Microsoft
                        Certified Business Solutions Partner). </p>
                </div>
            </a>
        </div>
    </div>
    <div class="container_outer_img">
        <div class="img-inner">
            <img src='ges.png' alt="" class="container_img"/>
        </div>
    </div>
</div>


</div>



<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
        class="bi bi-arrow-up-short"></i></a>
<div id="preloader"></div>
</body>
</html>

