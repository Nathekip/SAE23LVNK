<!DOCTYPE html>
<html>
<body>

<!-- <input id="numb">

<button type="button" onclick="myFunction()">Submit</button>

<p id="demo"></p> -->

<script>
function myFunction() {
  // Get the value of the input field with id="numb"
  let x = document.getElementById("user").value;
  let y = document.getElementById("pwd").value;
  // If x is Not a Number or less than one or greater than 10
  let text;
  if (x == "" || y == "") {
    text = "<div class='alert alert-danger'><strong>Erreur</strong>Veuillez remplir les champs</div>";
  } else {
    text = "Input OK";
    
  }
  document.getElementById("demo").innerHTML = text;
}
</script>
  
  
                          <div class="container-fluid text-center py-3 d-flex justify-content-between align-items-center bg-white">
                            <div class="d-flex align-items-center mx-auto">
                              <div class="login-form">
                                <form action="test2.php" id="login-form" method="post">
                                  <div class="form-group">
                                    <label>Utilisateur</label>
                                    <input id="user" type="text" class="form-control" name="utilisateur" placeholder="Utilisateur">
                                  </div>
                                  <div class="form-group">
                                    <label>Mot de passe</label>
                                    <input id="pwd" type="password" class="form-control" name="motdepasse" placeholder="Mot de passe">
                                  </div>
                                  <button type="button" onclick="myFunction()" name="page" value=NUMERODEPAGE class="btn btn-success data-bs-toggle='modal' data-bs-target='#myModal'">Se connecter</button>
                                </form>
                                <div>
                                  <a href="creerprofil.php">Pas de profil ? (décaler à gauche)</a>
                                  <a href="creerprofil.php">Mot de passe oublié ? (décaler à droite)</a>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div id="demo"></div>
  

</body>
</html> 
