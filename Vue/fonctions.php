<?php
function addUser($usr, $mdp, $mail, $departement, $role="user",$question=0,$reponse=NULL){
    $user = array();
    $json = file_get_contents('data/users.json');
    $user = json_decode($json, true);

    $add = array();
    $add['user']=$usr;
    $add['mdp']=password_hash($mdp,PASSWORD_DEFAULT);
    $add['role']=$role;
    $add['mail']=$mail;
    $add['departement']=$departement;
    $add['question']=$question;
    $add['pp']=False;
    $add['reponse']=$reponse;
    $user[$add['user']]=$add;

    $fp = fopen("data/users.json", 'w');
    fwrite($fp, "");
    fclose($fp);

    $jsonString = json_encode($user, JSON_PRETTY_PRINT);
    $fp = fopen("data/users.json", 'a');
    fwrite($fp, $jsonString);
    fclose($fp);
}
function showusers($users) {
  $rep = <<<EOT
  <div class="container">
      <table class="table table-bordered table-hover">
          <thead class="thead-dark">
              <tr>
                  <th scope="col">User</th>
                  <th scope="col">Role</th>
                  <th scope="col">Nouveau mdp</th>
                  <th scope="col">Confirmation</th>
                  <th scope="col">Valider</th>
                  <th scope="col">Supprimer</th>
              </tr>
          </thead>
          <tbody>
  EOT;

  foreach ($users as $user) {
      $selectedVisiteur = ($user['role'] == 'visiteur') ? 'selected' : '';
      $selectedCommunication = ($user['role'] == 'communication') ? 'selected' : '';
      $selectedAdmin = ($user['role'] == 'admin') ? 'selected' : '';
      $selectedManager = ($user['role'] == 'manager') ? 'selected' : '';

      $rep .= <<<EOT
      <tr>
          <th scope="row">{$user['user']}</th>
          <td>
              <form action="pwdbtn.php" method="post">
                  <input type="hidden" name="user" value="{$user['user']}">
                  <select class="form-control" name="role">
                      <option value="visiteur" $selectedVisiteur>Visiteur</option>
                      <option value="communication" $selectedCommunication>Communication</option>
                      <option value="admin" $selectedAdmin>Admin</option>
                      <option value="manager" $selectedManager>Manager</option>
                  </select>
          </td>
          <td>
                  <div class="form-group">
                      <input type="password" class="form-control rounded-pill" id="mdp" placeholder="Mot de passe" name="mdp">
                  </div>
          </td>
          <td>
                  <div class="form-group">
                      <input type="password" class="form-control rounded-pill" id="cmdp" placeholder="Confirmation" name="cmdp">
                  </div>
          </td>
          <td>
                  <button type="submit" class="btn btn-success rounded-pill" name="submit">
                      <img class="img-fluid" src="images/check.png" alt="logo">
                  </button>
              </form>
          </td>
          <td>
              <form action="delbtn.php" method="post">
                  <button type="submit" class="btn btn-danger rounded-pill" name="user">
                      <img class="img-fluid" src="images/cancel.png" alt="logo">
                  </button>
              </form>
          </td>
      </tr>
      EOT;
  }

  $rep .= <<<EOT
      </tbody>
  </table>
  </div>
  EOT;

  return $rep;
}

function setup() {
    session_start();
    echo '<meta charset="utf-8">
          <meta name="viewport" content="width=device-width, initial-scale=1">
          <link rel="shortcut icon" href="../images/icone.png">
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
          <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
          <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	        <script src="../Vue/fonction.js"></script>
          <body class="bg-secondary bg-opacity-25"></body>
        ';
    
    $listetitre = ["Page d'accueil","Formulaire","Informations","Panier","Création de Profil","Mot de passe oublié","Mon Profil","Partage de Fichiers","Gestion de Partenaires"];
    $rep = $listetitre[intval(substr(basename($_SERVER["SCRIPT_NAME"], ".php"), -1))-1];
    if ($rep == NULL){
    $rep = "Car Fusion";}
    echo "<title>$rep</title>";
    if ( isset($_SESSION['utilisateur']) ){
        $json = file_get_contents('data/users.json');
        $user = json_decode($json, true);
	if ( isset($user[$_SESSION['utilisateur']]['pp'])){
	  if ( $user[$_SESSION['utilisateur']]['pp'] ){
	        $_SESSION['pp'] = True;
	  }  
	}
    }
}

function pr() {
    echo '<pre> Session :<br>';
    print_r($_SESSION);
    echo '<br> Post :<br>';
    print_r($_POST);
    echo '<br> Files :<br>';
    print_r($_FILES);
    echo '</pre>';
}

function pagenavbar($page=""){
	
  $json = file_get_contents('../data/users.json');
  $user = json_decode($json, true);
  if ( isset($_POST['page']) ){ $pagehead = "Location: ".$_POST['page'].".php"; }
  foreach($user as $u){
    #print_r($u);
    if ( isset($_POST['motdepasse']) && isset($_POST['utilisateur']) ){
      if ( (password_verify($_POST['motdepasse'],$u['mdp'])==1) && ( ($_POST['utilisateur']==$u['user']) || ($_POST['utilisateur']==$u['mail'])) ){
        $_SESSION['utilisateur']=$u['user'];
        $_SESSION['role']=$u['role'];
        $_SESSION['msg'] = "vrai";
        $_SESSION['pp']=$u['pp'];
        #echo $pagehead;
        header($pagehead);
      }
    }
  }
	
  #fixed-top
  $navbar = '<nav class="navbar navbar-expand-lg bg-black navbar-dark">
	       <div class="container">
	         <a class="navbar-brand" href="accueil01.php"><img src="../images/Logo.png" alt="Logo CarFusion"></a>
	         <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
	           <span class="navbar-toggler-icon"></span>
	         </button>
	         <div class="collapse navbar-collapse" id="navbarNav">
	       	   <ul class="navbar-nav ms-auto">
	       	     <li class="nav-item">
	       	       <a class="d-flex flex-row-reverse nav-link p02" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Trouver une voiture" href="page02.php">
		         <i class="fa fa-car fa-2x"></i>
		         <div class="pt-1 pe-3">Trouver une voiture</div>
		       </a>
	       	     </li>';
if ( in_array( $_SESSION['role'],['employe','admin','communication','manager'] ) ){
	$navbar .= '
	       	     <li class="nav-item">
	       	       <a class="nav-link p03" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Ajout Voiture" href="ajoutvoiture03.php"><i class="fa-solid fa-car-on fa-2x"></i></a>
	       	     </li>
	      	<li class="nav-item">
	       	       <a class="nav-link p08" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Partage de Fichier" href="fichier8.php"><i class="fa-solid fa-file fa-2x"></i></a>
	       	     </li>';
} 
  if (isset($_SESSION['role'])){
  if ( $_SESSION['role'] == 'admin' ){
          $navbar .= '<li class="nav-item">
	       	       <a class="nav-link p04" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Gestion Admin" href="page06.php"><i class="fa-solid fa-wrench fa-2x"></i></a>
	       	     </li>';
  }}
  if ( in_array( $_SESSION['role'],['admin','communication'] ) ){
	$navbar .= '
	       	     <li class="nav-item">
	       	       <a class="nav-link p09" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Gestion de partenaires" href="partenaire.php"><i class="fa-solid fa-handshake fa-2x"></i></a>
	       	     </li>';
  }
	
	
  if ( $_SESSION['pp'] ){
	  $navbar .= '<li class="nav-item">
	       	       <a class="nav-link" data-bs-toggle="tooltip" data-bs-placement="bottom" title="User" href="Monprofil7.php"><img class="border border-2 border-white rounded-circle circle border" width="36" height="36" src="pp/User.jpeg" alt="PP Kono"></i></a>
		     </li>';
	  $navbar = str_replace("User", $_SESSION['utilisateur'], $navbar);
  }
  else if ( isset($_SESSION['utilisateur']) ){
	  $navbar .= '<li class="nav-item">
	       	       <a class="nav-link p07" data-bs-toggle="tooltip" data-bs-placement="bottom" title="User" href="Monprofil7.php"><i class="fa-solid fa-circle-user fa-2x"></i></a>
		     </li>';
	  $navbar = str_replace("User", $_SESSION['utilisateur'], $navbar);
  }
  else {
	  $navbar .= '<li class="nav-item">
	       	       <a class="nav-link disabled" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Vous n\'avez pas de compte" href="Monprofil7.php"><i class="fa-solid fa-circle-user fa-2x"></i></a>
		     </li>';
  }
  $navbar = str_replace($page, 'active', $navbar);
  echo $navbar;
  if( isset($_SESSION['utilisateur']) ){
        $btndeco = '<li class="nav-item">
                      <form action="../Vue/deconnexion.php" method="post">
	                <button type="submit" name="page" value=NUMERODEPAGE class="btn btn1 btn-outline-custom">Se déconnecter</button>
	              </form>
	       	    </li>';
        $btndeco = str_replace('NUMERODEPAGE', basename($_SERVER["SCRIPT_NAME"], ".php"), $btndeco);
        echo $btndeco;
    }
  else {
	echo '<li class="nav-item">
                <button type="button" class="btn btn1 btn-outline-custom" data-bs-toggle="modal" data-bs-target="#myModal">
                  Connexion
                </button>
                </li>
                <!-- The Modal -->
                <div class="modal fade" id="myModal">
                  <div class="modal-dialog">
                    <div class="modal-content bg-light">
                      <!-- Modal Header -->
                      <div class="modal-header bg-secondary text-center">
                        <h4 class="modal-title text-white mx-auto">Connexion</h4>
                        <button type="button" class="btn-close bg-danger btn-outline-dark btn-close-modal" data-bs-dismiss="modal"></button>
                      </div>
                      <!-- Modal body -->
                      <div class="modal-body text-center">
                        <div class="container-fluid text-center py-3 d-flex justify-content-between align-items-center bg-white">
                          <div class="d-flex align-items-center mx-auto">
                            <div class="login-form">
                              <form id="login-form" method="post">
                                <div class="pt-3 form-group">
                                  <label>Utilisateur</label>
                                  <input type="text" class="form-control" name="utilisateur" placeholder="Utilisateur">
                                </div>
                                <div class="pt-3 form-group">
                                  <label>Mot de passe</label>
                                  <input type="password" class="form-control" name="motdepasse" placeholder="Mot de passe">
                                  <input type="hidden" name="page" value="accueil01">
                                </div>
                                <div class="pt-4">
                                <button type="submit" class="btn text-white btn-dark btn-outline-success btn-login" id="submitBtn">Se connecter</button>
                                </div>
                              </form>
                              <div class="pt-2 d-flex text-primary justify-content-between w-100 m-2 mt-3">
                                <div><a href="creerprofil5.php">Pas de profil</a> ?</div>
                                <div><a href="oublimdp6.php">Mot de passe oublié</a> ?</div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- Modal footer -->
                      <div class="modal-footer">
                        <button type="button" class="btn btn-dark text-white btn-outline-danger btn-close-modal" data-bs-dismiss="modal">Fermer</button>
                      </div>
                    </div>      
                    <div class="container" id="alerte">
                    </div>
                  </div>
                </div>
                <script>
                  document.addEventListener("DOMContentLoaded", function() {
                    var form = document.getElementById("login-form");
                    var modal = document.getElementById("myModal");
                    var loginButton = document.querySelector("#login-form button.btn-login");
                    form.addEventListener("submit", function(e) {
                      e.preventDefault(); // Empêcher le rechargement de la page
                      var formData = new FormData(form);
                      var xhr = new XMLHttpRequest();
                      xhr.open("POST", "../Vue/process.php", true);
                      xhr.onload = function() {
                        if (xhr.status === 200) {
                          var alerteDiv = document.getElementById("alerte");
                          alerteDiv.innerHTML = xhr.responseText;
                          alerteDiv.style.display = "block";
                          $(modal).modal("handleUpdate"); // Actualiser le modal après la soumission du formulaire          
                          if (xhr.responseText.indexOf("Erreur") === -1) {
                            window.location.assign(';
                            echo '"'.basename($_SERVER['PHP_SELF']).'"';
                          echo ');            
                          }
                        }
                      };
                      xhr.send(formData);
                    });
                    modal.addEventListener("hidden.bs.modal", function() {
                      var form = document.getElementById("login-form");
                      form.reset(); // Effacer les champs du formulaire
                      var alerteDiv = document.getElementById("alerte");
                      alerteDiv.innerHTML = ""; // Supprimer le contenu du message derreur
                      alerteDiv.style.display = "none"; // Masquer le message derreur
                    });
                    
                  });
                </script>';
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
              <div>";
    } 
    echo "</div>";
	
    # echo '<script> $("login-form").submit(function(e) { e.preventDefault(); }); </script>';    # cette ligne est censée empecher le modal de se fermer mais elle ne fonctionne pas
    
    
    echo            '</div>
                   </ul>
	       	 </div>
	       </div>
    	     </nav>';
  
  /* echo '<script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl)
        })
        </script>'; */
        
  echo '<style>
          .btn1:hover, .btn:focus {
              color: #c1c5c7;
              background-color: transparent;
              border-color: #c1c5c7;
          } 
          /* Choix couleur du bouton connexion */
          .btn-outline-custom {
              color: #889496; /* Couleur du texte */
              border-color: #889496; /* Couleur de la bordure */
          }
        </style>';
}
    

  

function deleteUser($usr){
    $json = file_get_contents('../data/users.json');
    $user = json_decode($json, true);

    unset($user[$usr]);

    $jsonString = json_encode($user, JSON_PRETTY_PRINT);
    $fp = fopen("../data/users.json", 'w');
    fwrite($fp, $jsonString);
    fclose($fp);
}

function ppTrue($usr){
    $json = file_get_contents('../data/users.json');
    $user = json_decode($json, true);
	
    echo "<pre><br>";
    print_r($user[$usr]);
    echo "<br>";
    echo $user[$usr]['pp'];
    $user[$usr]['pp']=True;
    echo "<br>";
    echo $user[$usr]['pp'];
    echo "<br>";
    print_r($user);		
    echo "</pre>";

    $fp = fopen("../data/users.json", 'w');
    fwrite($fp, "");
    fclose($fp);

    $jsonString = json_encode($user, JSON_PRETTY_PRINT);
    $fp = fopen("../data/users.json", 'a');
    fwrite($fp, $jsonString);
    fclose($fp);
}

function pagefooter(){
    echo '<footer>
            <div>
                <div class="jumbotron jumbotron-sm bg-dark small text-white text-opacity-50 w-100">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="widget"><br>
                                    <h5 class="widget-title fw-bold">Contact</h5><p></p>
                                    <p>+33 233 455 251<br>
                                        contact-us@carfusion.com<br><p></p>
                                        40 Rue de la Croix Desilles, 35400 Saint-Malo
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="widget offset-md-3"><br>
                                    <h5 class="widget-title fw-bold">Follow us</h5><p></p>
                                    <p class="follow-me-icons">
                                        <i class="fa-brands fa-instagram"></i>
                                        <i class="fa-brands fa-twitter"></i>
                                        <i class="fa-brands fa-facebook"></i>
                                        <i class="fa-brands fa-github"></i>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-black text-center small text-white text-opacity-50">
                <a>Copyright &copy; Politique de Confidentialité, Terme et Conditions, 2023 CarFusion.</a> 
                </div>
            </div>
        </footer>';
}
function trierParPrixCroissant($a, $b) {
  return $a['prix'] - $b['prix'];
}      
function trierParPrixDecroissant($a, $b) {
  return $b['prix'] - $a['prix'];
}
function afficherVoitures($voitures, $etat, $couleur, $prix_min, $prix_max, $modele1)
{
  foreach($voitures as $index => $voiture)
  {
    if(($etat == "tous" || $voiture['etat'] == $etat) && ($couleur == "toutes" || $voiture['couleur'] == $couleur))
    {
      $prix = $voiture['prix'];
      if(($prix_min == "" || $prix >= $prix_min) && ($prix_max == "" || $prix <= $prix_max) && (empty($modele1) || stristr($voiture['modele'], $modele1)))
      {
        $modele = $voiture['modele'];
        $marque = $voiture['marque'];
        $prix = $voiture['prix'];
        $couleur= $voiture['couleur'];
        $description = $voiture['description'];
        $image = $voiture['image'];
        $annee = $voiture['annee'];
        $kilometrage = $voiture['kilometrage'];
        $couleur = $voiture['couleur'];
        if ($kilometrage === null) {
          $newkilometre = "0 km";
        } else {
          $newkilometre = number_format(round($kilometrage), 0, '.', ' ') . " km";
        }
        $etat_voiture = $voiture['etat'];
        $puissance = $voiture['puissance'];
        $carburant = $voiture['carburant'];
        $boite = $voiture['boite'];
        $mani = isset($voiture['maniabilite']) ? $voiture['maniabilite'] : null;
        $fiab = isset($voiture['fiabilite']) ? $voiture['fiabilite'] : null;
        $conf = isset($voiture['confort']) ? $voiture['confort'] : null;
        $i = 0;
        $stars1 = '';
        $stars2 = '';
        $stars3 = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $mani) {
              $stars1 .= '<i class="fas fa-star"></i>';
            } 
            else {
              $stars1 .= '<i class="far fa-star"></i>';
            }
        }
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $conf) {
              $stars2 .= '<i class="fas fa-star"></i>';
            }
            else {
              $stars2 .= '<i class="far fa-star"></i>';
            }
        }
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $fiab) {
              $stars3 .= '<i class="fas fa-star"></i>';
            }
            else {
              $stars3 .= '<i class="far fa-star"></i>';
            }
        }
        echo <<<EOD
                   <div class="col-md-3 mb-3">
                     <img src="$image" class="card-img-top" alt="Photo de la voiture">
                     <div class="card border rounded-3">
                       <div class="card-body">
                         <center><h5 class="card-title"> $marque $modele</h5></center> 
                         <p class="card-text">$description</p>
                         <div class="d-flex justify-content-between align-items-center">
                           <p class="card-text mb-0">Prix : $prix €</p>
                           <span class="badge bg-secondary rounded-3">$etat_voiture</span>
                         </div>
                         <hr>
                         <form method="post" action="reservation.php">
                           <input type="hidden" name="marque" value="$marque">
                           <input type="hidden" name="modele" value="$modele">
                           <input type="hidden" name="annee" value="$annee">
                           <input type="hidden" name="prix" value="$prix">
                           <input type="hidden" name="etat" value="$etat_voiture">
                           <input type="hidden" name="kilometrage" value="$kilometrage">
                           <input type="hidden" name="couleur" value="$couleur">
                           <input type="hidden" name="carburant" value="$carburant">
                           <input type="hidden" name="boite" value="$boite">
                           <input type="hidden" name="puissance" value="$puissance">
                           <input type="hidden" name="description" value="$description">
                           <input type="hidden" name="image" value="$image">
                           <button type="submit" name="submit" value="Acheter" class="btn btn-success rounded-3 ms-2">Acheter</button>
                         </form>
                         <button type="button" class="btn btn-secondary rounded-3" data-bs-toggle="modal" data-bs-target="#myModal$index">Voir plus</button>
                       </div>
                     </div>
                   </div>

                   <div class="modal fade" id="myModal{$index}" >
                    <div class="modal-dialog modal-dialog-centered" >
                     <div class="modal-body">
                       <div class="card">
                         <div class="card-body">
                           <div class="row">
                             <div class="modal-body">
                               <div class="row">
                                 <div class="col-md-12">
                                   <img src="$image" class="img-fluid rounded" alt="Photo de la voiture">
                                 </div>
                               </div>
                               <br>
                               <div class="row">
                                 <div class="col-md-6">
                                   <h4 class="text-center">Général</h4>
                                   <div class="category">
                                     <div class="bg-dark bg-opacity-75 text-white fw-bold rounded p-2 text-center">
                                       État :
                                     </div>
                                     <p class="text-center fw-bold">
                                       $etat_voiture
                                     </p>
                                     <div class="bg-dark bg-opacity-75 text-white fw-bold rounded p-2 text-center">
                                       Couleur :
                                     </div>
                                     <p class="text-center fw-bold">
                                       $couleur
                                     </p>
                                     <div class="bg-dark bg-opacity-75 text-white fw-bold rounded p-2 text-center">
                                       Kilométrage :
                                     </div>
                                     <p class="text-center fw-bold">
                                       $newkilometrage
                                     </p>
                                   </div>
                                 </div>
                                 <div class="col-md-6">
                                   <h4 class="text-center">Mécanique</h4>
                                   <div class="category">
                                     <div class="bg-dark bg-opacity-75 text-white fw-bold rounded p-2 text-center">
                                       Puissance :
                                     </div>
                                     <p class="text-center fw-bold">
                                       $puissance CV
                                     </p>
                                     <div class="bg-dark bg-opacity-75 text-white fw-bold rounded p-2 text-center">
                                       Boîte de vitesse :
                                     </div>
                                     <p class="text-center fw-bold">
                                       $boite
                                     </p>
                                     <div class="bg-dark bg-opacity-75 text-white fw-bold rounded p-2 text-center">
                                       Carburant :
                                     </div>
                                     <p class="text-center fw-bold">
                                       $carburant
                                     </p>
                                   </div>
                                 </div>
                               </div>
                               <div class="row">
                                 <h4 class="text-center">Avis</h4>
                                 <div class="col-md-4">
                                   <div class="category">
                                     <div class="bg-dark bg-opacity-75 text-white fw-bold rounded p-2 text-center">
                                       Maniabilité :
                                     </div>
                                     <p class="text-center fw-bold">
                                       $stars1
                                     </p>
                                   </div>
                                 </div>
                                 <div class="col-md-4">
                                   <div class="category">
                                     <div class="bg-dark bg-opacity-75 text-white fw-bold rounded p-2 text-center">
                                       Fiabilité :
                                     </div>
                                     <p class="text-center fw-bold">
                                       $stars2
                                     </p>
                                   </div>
                                 </div>
                                 <div class="col-md-4">
                                   <div class="category">
                                     <div class="bg-dark bg-opacity-75 text-white fw-bold rounded p-2 text-center">
                                       Confort :
                                     </div>
                                     <p class="text-center fw-bold">
                                       $stars3
                                     </p>
                                   </div>
                                 </div>
                               </div>
                             </div>
                           </div>
                         </div>
                       </div>
                     </div>
                   </div>
                 </div>
                EOD;
      }
    }
  }
}

function addChat($u_emmetteur, $u_receveur, $message){
    $path1 = 'chat/'.$u_emmetteur.'_'.$u_receveur.'.json';
    $path2 = 'chat/'.$u_receveur.'_'.$u_emmetteur.'.json';
    if ( file_exists($path1) and file_exists($path2) ){ 
      $json1 = file_get_contents($path1);
      $chat1 = json_decode($json1, true);
      $json2 = file_get_contents($path2);
      $chat2 = json_decode($json2, true);
      $len = count($chat1)+1;
      for ($i = 1; $i <= $len; $i++){
        $key = $len-$i-1;
        if ( $key >= -1 ){
          echo $i;
          $chat1[$key+1]=$chat1[$key];
          $chat2[$key+1]=$chat2[$key];
        }
	    }
    }
    else {
      $chat1 = array();
      $chat2 = array();
    }
    $chat1[0] = ['turn' => TRUE, 'message' => $message, 'temps' => time()];
    $chat2[0] = ['turn' => FALSE, 'message' => $message, 'temps' => time()];        
    $jsonString1 = json_encode($chat1, JSON_PRETTY_PRINT);       
    $jsonString2 = json_encode($chat2, JSON_PRETTY_PRINT);
    $fp = fopen($path1, 'w');
    fwrite($fp, $jsonString1);
    fclose($fp);	
    $fp = fopen($path2, 'w');
    fwrite($fp, $jsonString2);
    fclose($fp);
}

function showFiles($root){
  $tab = array();
  echo '
              <div class="container">
               <form method="post" action="../fichier8.php">
                <table class="table table-dark table-striped">
                  <thead>
                    <tr>
                      <!-- <input type="checkbox" class="form-check-input" name="option1" value="something" checked> -->
                      <th scope="col"><input type="checkbox" class="form-check-input" name="option1" value="something"></th>
                      <th scope="col">Nom</th>
                      <th scope="col">Type</th>
                      <th scope="col">Auteur</th>
                      <th scope="col">Taille</th>
                      <th scope="col">Date</th>
		      <th scope="col"><a class="btn btn-danger">Supprimer tout ('.count($deletefile).')</a></th>
                    </tr>
                  </thead>
                  <tbody>
              ';
  
  $n=0;
  $json = file_get_contents('../data/files.json');
  $files = json_decode($json, true);
  foreach($files as $file){
      #print_r($file);
      #echo "<br>";
        
      $tab[$n] = '<tr>
                    <th scope="row"><input type="checkbox" class="form-check-input" name="option" value="something"></th>
                    <td>nom</td>
                    <td>typefichier</td>
                    <td>auteur</td>
                    <td>taille</td>
                    <td>date</td>
                      <td><button class="btn btn-danger" data-parametre="filepath"><i class="fa-solid fa-trash-can"></i></button></td>
                  </tr>';
      $tab[$n]= str_replace("nom",$file['name'],$tab[$n]);
      $tab[$n]= str_replace("typefichier",$file['type'],$tab[$n]);
      $tab[$n]= str_replace("auteur",$file['author'],$tab[$n]);
      $tab[$n]= str_replace("taille",$file['size'],$tab[$n]);
      $tab[$n]= str_replace("date",$file['date'],$tab[$n]);
      $tab[$n]= str_replace("filepath",$file['path'],$tab[$n]);
      echo $tab[$n];
      $n++;
  echo '
                </tbody>
              </table>
             </form>
            </div>
          ';

  }
}

function delfiltest($filepath){
  unlink($filepath);
  $json = file_get_contents('data/files.json');
  $files = json_decode($json, true);

  for($i=0; $i < count($files); $i++){
    if ($files[$i]['path'] == $filepath){
      unset($files[$i]);
    }
  }
  
  $jsonString = json_encode($files, JSON_PRETTY_PRINT);
  $fp = fopen("data/files.json", 'w');
  fwrite($fp, $jsonString);
  fclose($fp);
  echo "</pre>".$filepath .'removed';
}

function deleteFile($deletefile){
  foreach($deletefile as $file){
    unlink($file);
    $json = file_get_contents('data/files.json');
    $files = json_decode($json, true);

    foreach ($files as $key => $value){
      if ($files[$key]['path'] == $file){
        unset($files[$key]);
      }
    }
  }
  $jsonString = json_encode($files, JSON_PRETTY_PRINT);
  $fp = fopen("data/files.json", 'w');
  fwrite($fp, $jsonString);
  fclose($fp);


}

function formatBytes($octets) { 
  $unites = array('o', 'Ko', 'Mo', 'Go', 'To'); 

  $octets = max($octets, 0); 
  $puissance = floor(($octets ? log($octets) : 0) / log(1024)); 
  $puissance = min($puissance, count($unites) - 1); 

  $octets /= (1 << (10 * $puissance)); 

  return round($octets, 2) . ' ' . $unites[$puissance]; 
} 

?>