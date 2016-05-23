<!DOCTYPE html>
<?php
$session = session_start();

$root = "../";
require "../includes.php";

use Aws\DynamoDb\Exception\DynamoDbException;
?>

<head>
    <title>Accueil</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <!-- Bootstrap CSS -->    
    <link href="../bootstrap/css/bootstrap.css" rel="stylesheet">



    <!-- bootstrap theme -->
    <link href="../bootstrap/Nice-admin/css/bootstrap-theme.css" rel="stylesheet">

    <!-- font icon -->
    <link href="../bootstrap/Nice-admin/css/elegant-icons-style.css" rel="stylesheet" />
    <link href="../bootstrap/Nice-admin/css/font-awesome.min.css" rel="stylesheet" />   


    <link href="../bootstrap/Nice-admin/css/widgets.css" rel="stylesheet">
    <link href="../bootstrap/Nice-admin/css/style.css" rel="stylesheet">
    <link href="../bootstrap/Nice-admin/css/style-responsive.css" rel="stylesheet" />
    <link href="../bootstrap/Nice-admin/css/xcharts.min.css" rel=" stylesheet">	
    <link href="../bootstrap/Nice-admin/css/jquery-ui.css" rel="stylesheet">


    <!-- fullcalendar css -->
    <link href='../fullcalendar/fullcalendar.css' rel='stylesheet' />
    <link href='../fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />

    <!-- fullcalendar css -->
    <style>

        body {
            margin: 40px 10px;
            padding: 0;
            font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
            font-size: 14px;
        }

        #calendar {
            max-width: 900px;
            margin: 0 auto;
        }

    </style>

    <!-- datepicker css -->
    <link rel="stylesheet" type="text/css" href="../datepicker/jquery.datetimepicker.css"/>

    <!-- datepicker css -->
    <style type="text/css">

        .custom-date-style {
            background-color: red !important;
        }

        .input{	
        }
        .input-wide{
            width: 500px;
        }

    </style>

    <!-- Inbox CSS -->
    <style>
        body{ margin-top:50px;}
        .nav-tabs .glyphicon:not(.no-margin) { margin-right:10px; }
        .tab-pane .list-group-item:first-child {border-top-right-radius: 0px;border-top-left-radius: 0px;}
        .tab-pane .list-group-item:last-child {border-bottom-right-radius: 0px;border-bottom-left-radius: 0px;}
        .tab-pane .list-group .checkbox { display: inline-block;margin: 0px; }
        .tab-pane .list-group input[type="checkbox"]{ margin-top: 2px; }
        .tab-pane .list-group .glyphicon { margin-right:5px; }
        .tab-pane .list-group .glyphicon:hover { color:#FFBC00; }
        a.list-group-item.read { color: #222;background-color: #F3F3F3; }
        hr { margin-top: 5px;margin-bottom: 10px; }
        .nav-pills>li>a {padding: 5px 10px;}

        .ad { padding: 5px;background: #F5F5F5;color: #222;font-size: 80%;border: 1px solid #E5E5E5; }
        .ad a.title {color: #15C;text-decoration: none;font-weight: bold;font-size: 110%;}
        .ad a.url {color: #093;text-decoration: none;}
    </style>

    <!-- Add Form CSS -->
    <style>

        #drop-zone {
            width: 100%;
            min-height: 150px;
            border: 3px dashed rgba(0, 0, 0, .3);
            border-radius: 5px;
            font-family: Arial;
            text-align: center;
            position: relative;
            font-size: 20px;
            color: #7E7E7E;
        }
        #drop-zone input {
            position: absolute;
            cursor: pointer;
            left: 0px;
            top: 0px;
            opacity: 0;
        }
        /*Important*/

        #drop-zone.mouse-over {
            border: 3px dashed rgba(0, 0, 0, .3);
            color: #7E7E7E;
        }
        /*If you dont want the button*/

        #clickHere {
            display: inline-block;
            cursor: pointer;
            color: white;
            font-size: 17px;
            width: 150px;
            border-radius: 4px;
            background-color: #4679BD;
            padding: 10px;
        }
        #clickHere:hover {
            background-color: #376199;
        }
        #filename {
            margin-top: 10px;
            margin-bottom: 10px;
            font-size: 14px;
            line-height: 1.5em;
        }
        .file-preview {
            background: #ccc;
            border: 5px solid #fff;
            box-shadow: 0 0 4px rgba(0, 0, 0, 0.5);
            display: inline-block;
            width: 60px;
            height: 60px;
            text-align: center;
            font-size: 14px;
            margin-top: 5px;
        }
        .closeBtn:hover {
            color: red;
            display:inline-block;
        }

        .bs-example{
            margin: 20px;
            background: #fff;
        }
        /* Fix alignment issue of label on extra small devices in Bootstrap 3.2 */
        .form-horizontal .control-label{
            padding-top: 7px;
        }


    </style>

    <!-- Previous/next page Inbox -->
    <style>
        .form-panel:not(.active) {
            display:none;
        }
    </style>
</head>

<body>
    <?php
// if the member is connected
    if (isset($_SESSION['email'])) {
        ?>

        <section id="container" class="">   
            <header class="header dark-bg">
                <div class="toggle-nav">
                    <div class="icon reorder tooltips" data-original-title="Toggle Navigation" data-placement="bottom"></div>
                </div>
                <a href="accueil_adultes.php?page=welcome_adult" class="logo">Accueil</a>

                <!--logo end-->
                <div class="nav search-row" id="top_menu">


                    <!--search form end -->
                </div>


                <div class="top-nav notification-row">
                    <ul class="nav pull-right top-menu">
                        <li id="alert_notificatoin_bar" class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <?php
                                try {
                                    //$client = DynamoDbClientBuilder::get();
                                    $client = LocalDBClientBuilder::get();
                                    $tableName = 'Contents';
                                    $iterator = $client->getIterator('Scan', array(
                                        'TableName' => $tableName,
                                        'ScanFilter' => array(
                                            'owner' => array(
                                                'AttributeValueList' => array(
                                                    array('S' => $_SESSION['email'])
                                                ),
                                                'ComparisonOperator' => 'CONTAINS'
                                            ),
                                        )
                                    ));

                                    $i = 0;
                                    foreach ($iterator as $item) {
                                        if (!empty($item['start']['S']) && !empty($item['end']['S'])) {
                                            $startTmp = explode(" ", $item['start']['S']);
                                            $start = $startTmp[0];

                                            $endTmp = explode(" ", $item['end']['S']);
                                            $end = $endTmp[0];

                                            $now = date('Y-m-d');
                                            if ((strtotime($start) < strtotime($now) || strtotime($start) == strtotime($now)) && (strtotime($end) > strtotime($now) || strtotime($end) == strtotime($now))) {
                                                $i++;
                                            }
                                        }
                                    }
                                    ?>
                                    <i class="icon-bell-l"></i>
                                    <span class="badge bg-important"><?php echo $i ?></span>
                                </a>
                                <ul class="dropdown-menu extended notification">
                                    <div class="notify-arrow notify-arrow-blue"></div>
                                    <li>
                                        <p class="blue"> <?php echo $i ?> évènements en cours</p>
                                    </li>
                                    <?php
                                    foreach ($iterator as $item) {
                                        if (!empty($item['start']['S']) && !empty($item['end']['S'])) {
                                            $startTmp = explode(" ", $item['start']['S']);
                                            $start = $startTmp[0];

                                            $endTmp = explode(" ", $item['end']['S']);
                                            $end = $endTmp[0];

                                            $now = date('Y-m-d');
                                            if ((strtotime($start) < strtotime($now) || strtotime($start) == strtotime($now)) && (strtotime($end) > strtotime($now) || strtotime($end) == strtotime($now))) {
                                                echo '<li>';
                                                echo '<a>';
                                                echo $item['title']['S'];
                                                echo '<span class="small italic pull-left">' . $item['target']['S'] . '</span>';
                                                echo '</a>';
                                                echo '</li>';
                                            }
                                        }
                                    }
                                } catch (DynamoDbException $e) {
                                    echo "Unable to query:\n";
                                    echo $e->getMessage() . "\n";
                                }
                                ?>
                                <li>
                                    <a href="accueil_adultes.php?page=./calendar/calendar">Voir tous les évènements</a>
                                </li>
                            </ul>
                        <li id="mail_notificatoin_bar" class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <i class="icon-envelope-l"></i>
                                <?php
                                try {
                                    //$client = DynamoDbClientBuilder::get();
                                    // $client = LocalDBClientBuilder::get();
                                    $tableName = 'Messages';
                                    $email = $_SESSION['email'];
                                    $iterator = $client->getIterator('Scan', array(
                                        'TableName' => $tableName,
                                        'ScanFilter' => array(
                                            'target' => array(
                                                'AttributeValueList' => array(
                                                    array('S' => $email)
                                                ),
                                                'ComparisonOperator' => 'CONTAINS'
                                            ),
                                        )
                                    ));

                                    $i = 0;
                                    $child = array();
                                    foreach ($iterator as $item) {
                                        // Grab the time number value
                                        if (strcmp($item['new']['S'], "true") == 0) {
                                            $i++;
                                        }
                                    }
                                    ?>
                                    <span class="badge bg-important"><?php echo $i ?></span>
                                </a>
                                <ul class="dropdown-menu extended inbox">
                                    <div class="notify-arrow notify-arrow-blue"></div>
                                    <li>
                                        <p class="blue"><?php echo $i ?> nouveau(x) message(s)</p>
                                    </li>
                                    <?php
                                    // Get new messages identification

                                    foreach ($iterator as $item) {
                                        if (strcmp($item['new']['S'], "true") == 0) {
                                            echo '<li>';
                                            echo '<a href="./accueil_adultes.php?page=./Chat/read&id=', $item['id']['S'], '"">';
                                            echo '<span class="subject">';
                                            echo '<span class="from">' . stripslashes(htmlentities(trim($item['email']['S']))) . '</span>';
                                            echo '<span class="time">' . $item['date']['S'] . '</span>';
                                            echo '</span>';
                                            echo '<span >';
                                            echo $item['title']['S'];
                                            echo '</span>';
                                            echo '</a>';
                                            echo '</li>';
                                        }
                                    }
                                } catch (DynamoDbException $e) {
                                    echo "Unable to query:\n";
                                    echo $e->getMessage() . "\n";
                                }
                                ?>
                                <li>
                                    <a href="accueil_adultes.php?page=./Chat/message">Voir tous les messages</a>
                                </li>

                            </ul>
                        </li>

                        <!-- task notificatoin start -->
                        <li id="task_notificatoin_bar" class="dropdown">
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="username"><b><?php echo $_SESSION['firstname'] . " " . $_SESSION['lastname']; ?></b></span>
                                <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu extended logout">
                                <div class="log-arrow-up"></div>
                                <li class="eborder-top">
                                    <a href="accueil_adultes.php?page=profil"><i class="icon_profile"></i> Mon profil </a>
                                </li>
                                <li>
                                    <a href="accueil_adultes.php?page=file-manage"><i class="icon_chat_alt"></i> Mes fichiers </a>
                                </li>
                                <li>
                                    <a href="accueil_adultes.php?page=../handlers/logoutHandler"><i class="icon_key_alt"></i> Déconnexion </a>
                                </li>
                            </ul>
                        </li>
                        <!-- user login dropdown end -->
                        </li>
                        <!-- notificatoin dropdown end-->
                        <?php // }    ?>
                    </ul>    
                </div> 
            </header>


            <!--sidebar start -->
            <aside>
                <div id="sidebar" class="nav-collapse">
                    <!--sidebar menu start-->
                    <ul class="sidebar-menu">
                        <li class="">   
                            <a class="" href="accueil_adultes.php?page=welcome_adult">
                                <i class="icon_house_alt"></i>
                                <span>Accueil</span>
                            </a>
                        </li>

                        <li class="sub-menu">
                            <a class="" href="#">
                                <i class="icon_table"></i>
                                <span>Que faire ?</span>
                                <span class="menu-arrow arrow_carrot-right"></span>
                            </a>
                            <ul class="sub">  
                                <li role="presentation"><a href="accueil_adultes.php?page=./calendar/calendar">Voir le calendrier</a></li> 
                                <li role="presentation"><a href="accueil_adultes.php?page=add">Ajouter un fichier</a></li>
                            </ul>
                        </li>

                        <li class="sub-menu">
                            <a class="" href="#">
                                <i class="icon_genius"></i>
                                <span>Membres</span>
                                <span class="menu-arrow arrow_carrot-right"></span>
                            </a>
                            <ul class="sub">  
                                <li role="presentation"><a href="accueil_adultes.php?page=./Chat/skype_contact">Contacts Skype</a></li> 
                                <li role="presentation"><a href="accueil_adultes.php?page=./Chat/message">Messagerie</a></li>
                            </ul>
                        </li>



                    </ul>
                </div>
            </aside>

            <!-- sidebar end -->

            <!-- main content start -->    
            <section id="main-content">
                <section class="wrapper">
                    <?php
                    $page = @$_GET["page"];
                    if ($page == "") {
                        $page = "accueil_adultes";
                    }
                    require_once ($page . ".php");
                    ?> 
                </section> 
            </section>


            <?php
//if the member is not logged in
        } else {
            echo "Vous devez vous connecter pour pouvoir accéder au contenu";
        }
        ?>

        <!-- datepicker -->
        <script src="../datepicker/jquery.js"></script>
        <script language="JavaScript" type="text/javascript" src="../datepicker/jquery.datetimepicker.full.js"></script>
        <script>/*
         window.onerror = function(errorMsg) {
         $('#console').html($('#console').html()+'<br>'+errorMsg)
         }*/
            $.datetimepicker.setLocale('fr');
            $('.some_class').datetimepicker({minDate: 0});

            $('.datetimepicker2').datetimepicker({
                lang: 'fr',
                minDate: 0,
                timepicker: false,
                format: 'd/m/Y',
                formatDate: 'Y/m/d'
            });
        </script>

        <!-- fullcalendar -->
        <script language="JavaScript" type="text/javascript" src='../fullcalendar/lib/moment.min.js'></script>
        <script language="JavaScript" type="text/javascript" src='../fullcalendar/lib/jquery.min.js'></script>
        <script language="JavaScript" type="text/javascript" src='../fullcalendar/fullcalendar.min.js'></script>
        <script language="JavaScript" type="text/javascript" src='../fullcalendar/lang/fr.js'></script>
        <script language="JavaScript" type="text/javascript" src="../fullcalendar/lib/jquery-ui.js"></script><!-- WARNING jquery-ui has to be after jquery.min.js-->

        <!-- nice scroll -->
        <script language="JavaScript" type="text/javascript" src="../bootstrap/Nice-admin/js/jquery.scrollTo.min.js"></script>
        <script language="JavaScript" type="text/javascript" src="../bootstrap/Nice-admin/js/jquery.nicescroll.js"></script>

        <!-- charts scripts -->
        <script language="JavaScript" type="text/javascript" src="../bootstrap/Nice-admin/assets/jquery-knob/js/jquery.knob.js"></script>
        <script language="JavaScript" type="text/javascript" src="../bootstrap/Nice-admin/js/jquery.sparkline.js"></script>
        <script language="JavaScript" type="text/javascript" src="../bootstrap/Nice-admin/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js"></script>
        <script language="JavaScript" type="text/javascript" src="../bootstrap/Nice-admin/js/owl.carousel.js" ></script>




        <!--script for this page only-->
        <script language="JavaScript" type="text/javascript" src="../bootstrap/Nice-admin/js/jquery.rateit.min.js"></script>

        <!-- custom select -->
        <script language="JavaScript" type="text/javascript" src="../bootstrap/Nice-admin/js/jquery.customSelect.min.js" ></script>
        <script language="JavaScript" type="text/javascript" src="../bootstrap/Nice-admin/assets/chart-master/Chart.js"></script>

        <!--custome script for all page-->
        <script language="JavaScript" type="text/javascript" src="../bootstrap/Nice-admin/js/scripts.js"></script>

        <!-- custom script for this page-->
        <script language="JavaScript" type="text/javascript" src="../bootstrap/Nice-admin/js/sparkline-chart.js"></script>
        <script language="JavaScript" type="text/javascript" src="../bootstrap/Nice-admin/js/easy-pie-chart.js"></script>
        <script language="JavaScript" type="text/javascript" src="../bootstrap/Nice-admin/js/jquery-jvectormap-1.2.2.min.js"></script>
        <script language="JavaScript" type="text/javascript" src="../bootstrap/Nice-admin/js/jquery-jvectormap-world-mill-en.js"></script>
        <script language="JavaScript" type="text/javascript" src="../bootstrap/Nice-admin/js/xcharts.min.js"></script>
        <script language="JavaScript" type="text/javascript" src="../bootstrap/Nice-admin/js/jquery.autosize.min.js"></script>
        <script language="JavaScript" type="text/javascript" src="../bootstrap/Nice-admin/js/jquery.placeholder.min.js"></script>
        <script language="JavaScript" type="text/javascript" src="../bootstrap/Nice-admin/js/gdp-data.js"></script>	
        <script language="JavaScript" type="text/javascript" src="../bootstrap/Nice-admin/js/morris.min.js"></script>
        <script language="JavaScript" type="text/javascript" src="../bootstrap/Nice-admin/js/sparklines.js"></script>	
        <script language="JavaScript" type="text/javascript" src="../bootstrap/Nice-admin/js/charts.js"></script>
        <script language="JavaScript" type="text/javascript" src="../bootstrap/Nice-admin/js/jquery.slimscroll.min.js"></script>

        <!-- Bootstrap JS --> 
        <script type="text/javascript" src="../bootstrap/js/bootstrap.js"></script> <!-- WARNING : jquery.min.js has to be before bootstrap.js-->




        <!-- calendar -->
        <script>
            $(document).ready(function () {

                $('#calendar').fullCalendar({
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay'
                    },
                    editable: false,
                    eventLimit: true, // allow "more" link when too many events
                    events: [
<?php
try {
    $client = LocalDBClientBuilder::get();

    $tableName = 'Users';
    $response = $client->getItem(array(
                'TableName' => $tableName,
                'Key' => array(
                    'email' => array('S' => $_SESSION['email'])
                )
            ));
    $itemChild = $response['Item'];

    
            if (!empty($itemChild['children']['SS'])) {
                $total = count($itemChild['children']['SS']);
                for ($k = 0; $k < $total; $k++) {
                    $oneChild = $itemChild['children']['SS'][$k];

                    $tableName = 'Contents';
                    $iterator = $client->getIterator('Scan', array(
                        'TableName' => $tableName,
                        'ScanFilter' => array(
                            'target' => array(
                                'AttributeValueList' => array(
                                    array('S' => $oneChild)
                                ),
                                'ComparisonOperator' => 'CONTAINS'
                            ),
                        )
                    ));


        
   
    foreach ($iterator as $item) {
        ?>
                                {
                                    resources: ['<?php echo $item['target']['S'] ?>'],
                                    title: '<?php echo htmlspecialchars(html_entity_decode($item['title']['S'])) . " - " . $item['target']['S']; ?>',
                                    start: '<?php echo $item['start']['S']; ?>',
                                    end: '<?php echo $item['end']['S']; ?>',
                                    description: '<?php
        // Each item will contain the attributes we added
        // Prettifies File Types, add more to suit your needs.


        $extn = strtolower(pathinfo($item['name']['S'], PATHINFO_EXTENSION));

        switch ($extn) :

            case "png":
                echo '<p align="center">';
                echo '<figure><img width="550px" height="auto" src="../Uploads/';
                echo $item['name']['S'];
                echo '" alt="" /><figcaption><a href="../Uploads/' . $item['name']['S'] . ' " download>Télécharger</a><br />';
                echo html_entity_decode($item['description']['S']);
                echo '</figcaption></figure></p>';
                break;
            case "jpg":
                echo '<p align="center">';
                echo '<figure><img width="550px" height="auto" src="../Uploads/';
                echo $item['name']['S'];
                echo '" alt="" /><figcaption><a href="../Uploads/' . $item['name']['S'] . ' " download>Télécharger</a><br />';
                echo html_entity_decode($item['description']['S']);
                echo '</figcaption></figure></p>';
                break;
            case "jpeg":
                echo '<p align="center">';
                echo ' <figure><img width="550px" height="auto" src="../Uploads/';
                echo $item['name']['S'];
                echo '" alt="" /><figcaption><a href="../Uploads/' . $item['name']['S'] . ' " download>Télécharger</a><br />';
                echo html_entity_decode($item['description']['S']);
                echo '</figcaption></figure></p>';
                break;
            case "gif":
                echo '<p align="center">';
                echo '<figure><img width="550px" height="auto" src="../Uploads/';
                echo $item['name']['S'];
                echo '" alt="" /><figcaption><a href="../Uploads/' . $item['name']['S'] . ' " download>Télécharger</a><br />';
                echo html_entity_decode($item['description']['S']);
                echo '</figcaption></figure></p>';
                break;
            case "ico":
                echo '<p align="center">';
                echo '<figure><img width="550px" height="auto" src="../Uploads/';
                echo $item['name']['S'];
                echo '" alt="" /><figcaption><a href="../Uploads/' . $item['name']['S'] . ' " download>Télécharger</a><br />';
                echo html_entity_decode($item['description']['S']);
                echo '</figcaption></figure></p>';
                break;
            case "txt":
                echo '<p align="center"><iframe src="../Uploads/' . $item['name']['S'] . ' " width="550px" height="auto">';
                echo '</iframe><br /><a href="../Uploads/' . $item['name']['S'] . ' " download>Télécharger</a><br>';
                echo html_entity_decode($item['description']['S']);
                echo '</p>';
                break;
            case "pdf":
                echo ' <iframe src="../Uploads/';
                echo $item['name']['S'];
                echo'" width="550" height="480"></iframe><a href="../Uploads/' . $item['name']['S'] . ' " download>Télécharger</a><br />';
                echo html_entity_decode($item['description']['S']);
                break;
            case "mp3":
                echo '<p align="center">';
                echo '<audio id="musik" controls="controls"><source src="../Uploads/';
                echo $item['name']['S'];
                echo '" type="audio/mp3"></audio>';
                echo '</p>';
                echo '<figcaption><a href="../Uploads/' . $item['name']['S'] . ' " download>Télécharger</a><br />';
                echo html_entity_decode($item['description']['S']);
                echo '</figcaption></figure></p>';
                break;
            case "mp4":
                echo '<video width="550"  height="auto" src="../Uploads/';
                echo $item['name']['S'];
                echo '"  controls autobuffer>';
                echo '</video>';
                echo '<figcaption><a href="../Uploads/' . $item['name']['S'] . ' " download>Télécharger</a><br />';
                echo html_entity_decode($item['description']['S']);
                echo '</figcaption></figure></p>';
                break;
            default: $extn = strtoupper($extn) . " File";
                echo '<p align="center">';
                echo '<figure><figcaption><a href="../Uploads/' . $item['name']['S'] . ' " download>Télécharger</a><br />';
                echo html_entity_decode($item['description']['S']);
                echo '</figcaption></figure>';
                echo '</p>';
                break;
        endswitch;
        ?>',
                                    color: '<?php
        $extn = strtolower(pathinfo($item['name']['S'], PATHINFO_EXTENSION));

        switch ($extn) :

            case "png":
                echo '#ff9f89';
                break;
            case "jpg":
                echo '#ff9f89';
                break;
            case "jpeg":
                echo '#ff9f89';
                break;
            case "svg":
                break;
            case "gif":
                echo '#ff9f89';
                break;
            case "ico":
                echo '#ff9f89';
                break;
            case "txt":
                break;
            case "pdf":
                break;
            case "mp3":
                echo '#808000';
                break;
            case "mp4":
                echo '#DAA520';
                break;
            default:
                echo '#ff9f89';
                break;
        endswitch;
        ?>'

                                },
        <?php
    }
}
}
} catch (Exception $ex) {
    
}
?>

                    ],
                    eventClick: function (event, jsEvent, view) {
                        $('#modalTitle').html(event.title);
                        $('#modalBody').html(event.description);
                        $('#eventUrl').attr('href', event.url);
                        $('#fullCalModal').modal();
                    }
                });
            });


        </script>

        <!--Checkbox Select All for Add.php-->
        <script language="JavaScript" type="text/javascript">
            function selectAll(source) {
                checkboxes = document.getElementsByName('target[]');
                for (var i = 0, n = checkboxes.length; i < n; i++) {
                    checkboxes[i].checked = source.checked;
                }
            }
        </script>

        <script language="JavaScript">
            //Show/Hide calendar
            function showCalendar()
            {
<?php
for ($i = 1; $i < $totalCheckbox; $i++) {
    echo 'if($("#checkbox' . $i . '").is(":checked") && !$("#isStartChecked' . $i . '").is(":checked")) {
                $("#calendar' . $i . '").show();
                        $("#startSpan' . $i . '").hide().find("input").prop("disabled", true);
                $("#startSpanTime' . $i . '").show().find("input").prop("disabled", false);
            } else if (!$("#checkbox' . $i . '").is(":checked")) {
                $("#calendar' . $i . '").hide();
                     $("#startSpan' . $i . '").hide().find("input").prop("disabled", true);
                $("#startSpanTime' . $i . '").show().find("input").prop("disabled", true);
            }';
}
?>

            }

            // Show/Hide date
            function showDate()
            {
<?php
for ($i = 1; $i < $totalCheckbox; $i++) {
    echo 'if($("#checkbox' . $i . '").is(":checked") && $("#isStartChecked' . $i . '").is(":checked")){
               $("#startSpanTime' . $i . '").hide().find("input").prop("disabled", true);
                $("#startSpan' . $i . '").show().find("input").prop("disabled", false);
            }else if ($("#checkbox' . $i . '").is(":checked") && !$("#isStartChecked' . $i . '").is(":checked")){
                    $("#startSpan' . $i . '").hide().find("input").prop("disabled", true);
                $("#startSpanTime' . $i . '").show().find("input").prop("disabled", false);
    }';
}
?>

            }

        </script>

        <script>
            //dropzone
            $(function () {
                var dropZoneId = "drop-zone";
                var buttonId = "clickHere";
                var mouseOverClass = "mouse-over";

                var dropZone = $("#" + dropZoneId);
                var ooleft = dropZone.offset().left;
                var ooright = dropZone.outerWidth() + ooleft;
                var ootop = dropZone.offset().top;
                var oobottom = dropZone.outerHeight() + ootop;
                var inputFile = dropZone.find("input");
                document.getElementById(dropZoneId).addEventListener("dragover", function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropZone.addClass(mouseOverClass);
                    var x = e.pageX;
                    var y = e.pageY;

                    if (!(x < ooleft || x > ooright || y < ootop || y > oobottom)) {
                        inputFile.offset({
                            top: y - 15,
                            left: x - 100
                        });
                    } else {
                        inputFile.offset({
                            top: -400,
                            left: -400
                        });
                    }

                }, true);

                if (buttonId != "") {
                    var clickZone = $("#" + buttonId);

                    var oleft = clickZone.offset().left;
                    var oright = clickZone.outerWidth() + oleft;
                    var otop = clickZone.offset().top;
                    var obottom = clickZone.outerHeight() + otop;

                    $("#" + buttonId).mousemove(function (e) {
                        var x = e.pageX;
                        var y = e.pageY;
                        if (!(x < oleft || x > oright || y < otop || y > obottom)) {
                            inputFile.offset({
                                top: y - 15,
                                left: x - 160
                            });
                        } else {
                            inputFile.offset({
                                top: -400,
                                left: -400
                            });
                        }
                    });
                }

                document.getElementById(dropZoneId).addEventListener("drop", function (e) {
                    $("#" + dropZoneId).removeClass(mouseOverClass);
                }, true);

                inputFile.on('change', function (e) {
                    $('#filename').html("");
                    var fileNum = this.files.length,
                            initial = 0,
                            counter = 0,
                            fileNames = "";

                    for (initial; initial < fileNum; initial++) {
                        counter = counter + 1;
                        fileNames += this.files[initial].name + '&nbsp;';
                    }
                    if (fileNum > 1)
                        fileNames = 'Files selected...';
                    else
                        fileNames = this.files[0].name + '&nbsp;';

                    $('#filename').append('<span class="fa-stack fa-lg"><i class="fa fa-file fa-stack-1x "></i><strong class="fa-stack-1x" style="color:#FFF; font-size:12px; margin-top:2px;">' + fileNum + '</strong></span><span">' + fileNames + '</span>&nbsp;<span class="fa fa-times-circle fa-lg closeBtn" title="remove"></span><br>');

                    // add remove event
                    $('#filename').find('.closeBtn').click(function () {
                        $('#filename').empty();
                        inputFile.val('');
                    });
                    ///End change 
                });

            })

        </script>

        <!--Previous/Next button for Inbox-->
        <script>
            $('.previous').click(function () {
                var cur = $('.form-panel').index($('.form-panel.active'));
                if (cur != 0) {
                    $('.form-panel').removeClass('active');
                    $('.form-panel').eq(cur - 1).addClass('active');
                }
            });
            $('.next').click(function () {
                var cur = $('.form-panel').index($('.form-panel.active'));
                if (cur != $('.form-panel').length - 1) {
                    $('.form-panel').removeClass('active');
                    $('.form-panel').eq(cur + 1).addClass('active');
                }
            });
        </script>
        <!--Previous/Next button for Inbox: Show the page number-->
        <script type="text/javascript">
            var clicks = 1;
            var clicksEnd = numberPerPage;


            $(".next").click(function () {

                if (clicksEnd + numberPerPage < totalMessage) {
                    clicks = clicks + numberPerPage;
                    clicksEnd = clicksEnd + numberPerPage;
                    $('#pageNumber').html(clicks);
                    $('#pageNumberEnd').html(clicksEnd);
                } else if ((clicksEnd + numberPerPage >= totalMessage) && (clicksEnd + numberPerPage < totalMessage + numberPerPage)) {
                    clicks = clicks + numberPerPage;
                    clicksEnd = clicksEnd + numberPerPage;
                    $('#pageNumber').html(numberPerPage * (pageNumber - 1) + 1);
                    $('#pageNumberEnd').html(totalMessage);
                } else {
                    $('#pageNumber').html(numberPerPage * (pageNumber - 1) + 1);
                    $('#pageNumberEnd').html(totalMessage);
                }


            });


            $(".previous").click(function () {
                if (clicks - numberPerPage > 1) {
                    clicks = clicks - numberPerPage;
                    clicksEnd = clicksEnd - numberPerPage;
                    $('#pageNumber').html(clicks);
                    $('#pageNumberEnd').html(clicksEnd);
                } else if ((clicks - numberPerPage <= 1) && (clicks - numberPerPage > 1 - numberPerPage)) {
                    clicks = clicks - numberPerPage;
                    clicksEnd = clicksEnd - numberPerPage;
                    $('#pageNumber').html(1);
                    $('#pageNumberEnd').html(numberPerPage);
                } else {
                    $('#pageNumber').html(1);
                    $('#pageNumberEnd').html(numberPerPage);
                }

            });



        </script>
        <!--Checkbox Select All in one page for message.php WARNING has to be after the "Previous/Next button" script-->
        <script language="JavaScript" type="text/javascript">
            function selectAll(source) {
                checkboxes = document.getElementsByName('checkDelete[]');
                for (var i = clicks - 1, n = clicksEnd; i < n; i++) {
                    checkboxes[i].checked = source.checked;
                }
            }
        </script>
</body>

</html>