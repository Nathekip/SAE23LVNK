<!DOCTYPE html>
<html>
  <body>
    <?php
    include('fonctions.php');
    setup();
    
    if(isset($_SESSION['utilisateur'])){
        echo $_SESSION['utilisateur'];
        $btndeco = '<form action="deconnexion.php" method="post">
        <button type="submit" name="page" value=NUMERODEPAGE class="btn btn-warning btn-sm">Se déconnecter</button>
        </form>';
        $btndeco = str_replace('NUMERODEPAGE', basename($_SERVER["SCRIPT_NAME"], ".php"), $btndeco);
        echo $btndeco;
    }
    
    #si l'utilisateur n'existe pas, ça veut dire qu'il n'est pas identifié
    
    else { 
    
    $boutons = 'Vous n\'êtes pas connectés
          <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#myModal">
  Connexion
</button>

<!-- The Modal -->
<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content bg-light">

      <!-- Modal Header -->
      <div class="modal-header bg-secondary text-center">
        <h4 class="modal-title text-center">Connexion</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <div class="modal-body text-center">
        <div class="container-fluid text-center py-3 d-flex justify-content-between align-items-center bg-white">
          <div class="d-flex align-items-center mx-auto">
            <div class="login-form">
              <form action="NUMERODEPAGE.php" id="login-form" method="post">
                <div class="form-group">
                  <label>Utilisateur</label>
                  <input type="text" class="form-control" name="utilisateur" placeholder="Utilisateur">
                </div>
                <div class="form-group">
                  <label>Mot de passe</label>
                  <input type="password" class="form-control" name="motdepasse" placeholder="Mot de passe">
                </div>
                <div>
                    <br>
                  <button type="submit" name="page" value=NUMERODEPAGE class="btn btn-success data-bs-toggle="modal" data-bs-target="#myModal">Se connecter</button>
                </div>
              </form>
              <div class="d-flex justify-content-between w-100 mt-3">
                <div><a href="creerprofil5.php">Pas de profil ?</a></div>
                <div><a href="creerprofil.php">Mot de passe oublié ?</a></div>
              </div>
            </div>
          </div>
        </div>
      </div>
              
      <!-- Modal footer -->
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Fermer</button>
      </div>

    </div>
  </div>
</div>
';
        
        $boutons = str_replace('NUMERODEPAGE', basename($_SERVER["SCRIPT_NAME"], ".php"), $boutons);
        echo $boutons;
    }
                
   
    if (isset($_POST['page'])){
        /*echo "<script>
                $(document).ready(function() {
                    $('#myModal').modal('show');
                    });
                    </script>";*/
      echo "<div class='container'>
                  <div class='alert alert-danger'>
                      <strong>Erreur</strong> Le mot de passe ou l'identifiant sont invalides.
                  </div>
              <div>
        ";    
    }
    
    echo '</div></div></div></div>
    </header>';
    # echo '<script> $("login-form").submit(function(e) { e.preventDefault(); }); </script>';    # cette ligne est censée empecher le modal de se fermer mais elle ne fonctionne pas
    
    $json = file_get_contents('data/users.json');
    $user = json_decode($json, true);
    $page = "Location: ".$_POST['page'].".php";

    foreach($user as $u){       
      if ((password_verify($_POST['motdepasse'],$u['mdp'])==1) && ($_POST['utilisateur']==$u['user']))
      {
          $_SESSION['utilisateur']=$_POST['utilisateur'];
          $_SESSION['role']=$u['role'];
          $_SESSION['msg'] = "vrai";
          header($page);
     }
    }
   
      
    pr();
    ?>
  </body>
</html> 
