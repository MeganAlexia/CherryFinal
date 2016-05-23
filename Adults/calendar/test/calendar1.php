<?php 
session_start();
?>
<!doctype html>
<html>
    
    <head>
        <title>Calendar</title>
        
        <?php
        $root = '../handlers/';
        include $root.'head.php';
        include $root.'includes.php';
        ?>
        
        <link href= "<?php echo $root."../css/style_calendar.css" ?>" rel="stylesheet" type="text/css">
        <link href= "<?php echo $root."../css/files_container_style.css" ?>" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> 
        <script src="../js/bootstrap.min.js"></script>
        <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        <script type='text/javascript'>
            jQuery(function($) {
                $(".datepicker" ).datepicker();
                $('.month').hide();
                $('.month:first').show();
                $('.months a:first').addClass('active');
                var current = 1;
                $('.months a').click(function() {
                    var month = $(this).attr('id').replace('linkMonth', '');
                    if (month !== current) {
                        $('#month'+current).slideUp();
                        $('#month'+month).slideDown();
                        $('.months a').removeClass('active');
                        $('.months a#linkMonth'+month).addClass('active');
                        current = month;
                    }
                    return false;
                });
            });
        </script>
    </head>

    <body>

        <?php //include $root.'nav.php' ?>
        
        <?php
        
        $doy = new DaysOfYear();
        $year = date('Y');
        $calendar = $doy->getAll($year);
        
        $access_rights = Rights::$NONE;
        
        $child_email = $_GET['email'];
        $adult_email = $_SESSION['email'];
        if (!empty($child_email) && !empty($adult_email)) {
            $childDao = new ChildDAO(DynamoDbClientBuilder::get());
            $child = $childDao->get($child_email);
            $usrDao = new UserDAO(DynamoDbClientBuilder::get());
            $user = $usrDao->get($adult_email);
            if ($child->isFather($user->getEmail())) {
                $access_rights = Rights::$FULL_ACCESS;  // pour l'instant en dur, plus tard dans la base
            }
             echo '<span class="email" email="'.$child_email. '"></span>';
         echo '<span class="adultEmail" email="'.$adult_email. '"   type="'.$user->getType().'"></span>';        
        }
        ?>

        <div class ='periods'>
            <div class='year'><?php echo $year; ?></div>
            <div class ='months'>
                <ul>
                    <!-- print all months, three letters per month -->
                    <?php foreach ($doy->months as $id=>$m): ?>
                    <li> <a href='#' id='linkMonth<?php echo $id+1; ?>'><?php echo utf8_encode(substr(utf8_decode($m), 0, 3)); ?></a> </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="clear"></div>
            <?php $dates = $calendar[$year] ?>
            <!-- Do that for each month -->
            <?php foreach ($dates as $m=>$days): ?>
                <div class='month relative' id='month<?php echo $m; ?>'>
                    <div class="row">
            <div class="col-md-6">
        
                    <table class="calendar_style">
                        <thead>
                            <tr>
                                <!-- print all days of a week (monday, tuesday..) -->
                                <?php foreach ($doy->days as $day): ?>
                                    <th>
                                        <?php echo substr($day, 0, 3); ?>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <!-- Do that for each day of a month -->
                                <?php $end = end($days); foreach ($days as $d=>$w): ?>
                                    <?php if ($d == 1 && $w != 1): ?>
                                        <td colspan="<?php echo $w-1 ?>" class="padding"></td>
                                    <?php endif; ?>
                                    
                                    <td>
                                        <div class="relative">
                                            <div class="day">
                                                <?php echo $d; ?>
                                            </div>
                                        </div>
                                        <div class="daytitle">
                                            <?php echo $doy->days[$w-1].' '.$d.' '.$doy->months[$m-1]; ?>
                                        </div>
                                        
                                        
                                        <?php
                                        $date_obj = new Date($year, $m, $d);
                                        $in = $date_obj->toString("in");
                                        echo "<div class=\"hiddenDate\">". $in ."</div>";
                                        if ($access_rights == Rights::$LIMITED_ACCESS) {
                                            $my_contents = $child->getContentsByStartingDate($in, $user->getType());
                                        } else if ($access_rights == Rights::$FULL_ACCESS) {
                                            $medical = $child->getContentsByStartingDate($in, "doctor");
                                            $teaching = $child->getContentsByStartingDate($in, "teacher");
                                            $family = $child->getContentsByStartingDate($in, "family");
                                        }
                                        ?>
                                        
                                        <?php if ($access_rights == Rights::$LIMITED_ACCESS): ?>
                                        <ul class="events_bullets">
                                            <?php foreach ($my_contents as $content): ?>
                                            <li></li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <ul class="events">
                                            <?php foreach ($my_contents as $content): ?>
                                            <li> <?php echo $content['M']['name']['S'] ?> </li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <?php endif; ?>
                                        
                                         <?php if ($access_rights == Rights::$FULL_ACCESS): ?>
                                        <ul class="events_bullets">
                                            <?php foreach ($medical as $content): ?>
                                            <li class="item_medical"></li>
                                            <?php endforeach; ?>
                                            
                                            <?php foreach ($family as $content): ?>
                                            <li class="item_family"></li>
                                            <?php endforeach; ?>
                                            
                                            <?php foreach ($teaching as $content): ?>
                                            <li class="item_teaching"></li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <ul class="events">
                                            <?php foreach ($medical as $content): ?>
                                            <li type="doctor" class="item_medical" <?php echo "owner=\"".$content['M']['owner']['S']."\""; ?> > <?php echo $content['M']['name']['S'] ?> </li>
                                            <?php endforeach; ?>
                                            
                                            <?php foreach ($family as $content): ?>
                                            <li type="family" class="item_family" <?php echo "owner=\"".$content['M']['owner']['S']."\""; ?> > <?php echo $content['M']['name']['S'] ?> </li>
                                            <?php endforeach; ?>
                                            
                                            <?php foreach ($teaching as $content): ?>
                                            <li type="teacher" class="item_teaching" <?php echo "owner=\"".$content['M']['owner']['S']."\""; ?> > <?php echo $content['M']['name']['S'] ?> </li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <?php endif; ?>
                                        
                                    </td>
                                        
                                    <?php if ($w == 7): ?>
                                        </tr><tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                            
                                <?php if ($end != 7): ?>
                                            <td colspan="<?php echo 7-$end ?>" class="padding"></td>
                                <?php endif; ?>
                            </tr>
                        </tbody>
                    </table>
                    </div>
               
                <div class="col-md-offset-2  col-md-4">
                    <div class="containerFiles">
                          
                       <!-- mouton.txt <button class="btn btn-danger"> <span class="glyphicon glyphicon-remove"></span> </button> -->
                    </div>
               
                    <div class="filesToAdd none">
                        <?php 
                        $contentDao = new ContentDAO(DynamoDbClientBuilder::get());
                        $contents = $contentDao->getContentsOfUser($_SESSION['email']);
                        $files = array();
                        foreach ($contents as $content) {
                            array_push($files, $content->getName());
                        }
                        //$files =['insecte.txt','f2.txt','f3.txt']; //fichiers en dur pour l'instant
                        foreach ($files as $file) {
                            echo '<div class="newFileRow row"><div class="col-md-8" > <div class="input-group">  <input type="text" class="datepicker form-control" placeholder="Choisir une date" aria-describedby="basic-addon2"><span class="input-group-addon" id="basic-addon2">Date de fin</span></div> </div>'
                                    . '<div class="col-md-4" > <input type="checkbox" name="file" value="' . $file.  '">'. 
                        $file  .'</div></div> <br/>' ;
                        }
                        
                        //<input type="checkbox" name="file" value="' . $file.  '">'. 
                       //$file  .
                        ?>
                       <br/>
                        
                        <button id="addFilesButton" class="btn btn-default">Ajouter les fichiers à l'enfant</button>
                        <span class="ajout"></span>
                    </div>
                    
                </div>
              
               
           </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!--
        <pre><?php print_r($dates) ?></pre>
        -->
        
    </body>
        
    <?php include $root.'footer.php' ?>
    <script src=calendarFileManagement.js></script>
    
</html>
