<!DOCTYPE html>
<?php
$session = session_start();
?>

<html >

    <!-- // From https://colorlib.com/wp/html5-and-css3-login-forms/ -->
    <head>
        <meta charset="UTF-8">
        <title>Inscription/Connexion</title>
        <link href='http://fonts.googleapis.com/css?family=Titillium+Web:400,300,600' rel='stylesheet' type='text/css'>

        <link rel="stylesheet" href="css/normalize.css">


        <link rel="stylesheet" href="css/style.css">

    </head>

    <body>
        <?php
        // if the member is connected
        if (isset($_SESSION['email'])) {
            echo "<div class=\"container\">" .
            "Redirection vers la <a href=\"../Adults/accueil_adultes.php?page=welcome_adult\"> Page d'accueil </a> ..." .
            "</div>";
            header('Refresh:1; url=../Adults/accueil_adultes.php?page=welcome_adult');
        } else {
            ?>
            <div class="form">

                <ul class="tab-group">
                    <li class="tab active"><a href="#login">Connexion</a></li>
                    <li class="tab"><a href="#signup">Inscription</a></li>
                </ul>

                <div class="tab-content">
                    <div id="login">   
                        <h1>Bienvenue !</h1>

                        <form action="../handlers/connectionHandler.php" method="post">

                            <div class="field-wrap">
                                <label>
                                    Adresse email<span class="req">*</span>
                                </label>
                                <input id="email" name="email" type="email" required autocomplete="off"/>
                            </div>

                            <div class="field-wrap">
                                <label>
                                    Mot de passe<span class="req">*</span>
                                </label>
                                <input id="password" name="password" type="password" required autocomplete="off"/>
                            </div>



                            <p class="forgot"><a href="../forgotPassword.php">Mot de passe oublié ?</a></p>
                            <p class="forgot"><a href="../index.html">Retour à l'accueil Cherry.</a></p>

                            <button class="button button-block"/>Connexion</button>

                        </form>

                    </div>  

                    <div id="signup">   
                        <h1>Inscrivez-vous</h1>

                        <form role="form" id="userForm" method="post" action="../handlers/authenticationHandler.php">

                            <div class="top-row">
                                <div class="field-wrap">
                                    <label>
                                        Prénom<span class="req">*</span>
                                    </label>
                                    <input id="firstname" name="firstname" type="text" required autocomplete="off" />
                                </div>

                                <div class="field-wrap">
                                    <label>
                                        Nom<span class="req">*</span>
                                    </label>
                                    <input id="lastname" name="lastname" type="text" required autocomplete="off"/>
                                </div>

                            </div>

                            <div class="field-wrap">
                                <label>
                                    Adresse email<span class="req">*</span>
                                </label>
                                <input id="email" name="email" type="email" required autocomplete="off"/>
                            </div>
                            
                            <div class="field-wrap">
                                <label>
                                    Pseudo Skype<span class="req">*</span>
                                </label>
                                <input id="email" name="skype" type="text" required autocomplete="off"/>
                            </div>

                            <div class="field-wrap">
                                <label>
                                    Entrez un mot de passe<span class="req">*</span>
                                </label>
                                <input id="password" name="password" type="password" required autocomplete="off"/>
                            </div>

                            <div class="field-wrap">
                                <label class="control-label" for="confirm_password">
                                    Confirmez le mot de passe<span class="req">*</span>
                                </label>
                                <input id="confirmPassword" name="confirmPassword" type="password" class="form-control" required autocomplete="off"/>
                            </div>    

                            <div class="field-wrap">  
                                <div class="form-group">
                                    <select id="type" name="type">
                                        <option value="child" >Enfant</option>
                                        <option value="teacher" selected>Enseignant</option>
                                        <option value="doctor">Médecin</option>
                                        <option value="family">Membre de la famille</option>
                                    </select>
                                </div>
                            </div>  

                            <p class="forgot"><a href="../index.html">Retour à l'accueil Cherry.</a></p>
                            <button type="submit" class="button button-block"/>C'est parti</button>

                        </form>

                    </div>        
                </div><!-- tab-content -->

            </div> <!-- /form -->
            <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

            <script src="js/index.js"></script>

            <script>
                var ddl = document.getElementById("type");
                var selectedValue = ddl.options[ddl.selectedIndex].value;
                if (selectedValue === "child") {
                    $("#adultsInput").show();
                } else {
                    $("#adultsInput").hide();
                }
                $('#type').on('change', function () {
                    if ($(this).val() === "child") {
                        $("#adultsInput").show();
                    } else {
                        $("#adultsInput").hide();
                    }
                });
            </script>

    <?php
} //Else
?>

    </body>
</html>
