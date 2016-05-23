<!doctype html>
<html>
    
<head>
    <?php include 'head.php' ?>
    <title>Mot de passe oublié </title>
</head>

<body>
    <?php include 'nav.php' ?>

    <div class="container">
        <div class="row">
            <div class="col-md-3 col-md-offset-3">
                <form role="form" id="userForm" method="post" action="handlers/forgotPasswordHandler.php" >
                    <div class="form-group">
                        <label class="control-label" for="email">Email</label>
                        <input id="email" name="email" class="form-control" placeholder="Entrez votre email" type="email">
                    </div>
                    <button type="submit" class="btn btn-default">Recevoir un nouveau mot de passe à cette adresse</button>
                </form>
            </div>
        </div>
    </div>

    <?php include 'footer.php' ?>
    
</body>
</html>





