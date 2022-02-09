<!-- fonctions php --> 
<?php

session_start();
require_once 'fonctions/bd.php';
require_once 'fonctions/utilisateur.php';
require_once 'fonctions/categories.php';

$stateMsg ="";
$control = "";

$link = getConnection($dbHost, $dbUser, $dbPwd, $dbName);

// si aucun utilisateur n'est connecté
if(!isset($_SESSION["logged"])){
	$_SESSION["logged"] = "";
}

// Verfie le formumlaire d'inscription et lance l'ajout d'utilisateur
if(isset($_POST["inscription"])){
	if(isset($_POST["pseudo"]) && $_POST["pseudo"]!=""){
		if(isset($_POST["mdp"]) && $_POST["mdp"] !=""){
			$pseudo = $_POST["pseudo"];
			$hashMdp = md5($_POST["mdp"]);
			$hashMdpc = md5($_POST["mdpc"]);
		
			$available = checkAvailability($pseudo, $link);
		
			if($hashMdp == $hashMdpc){
				if($available){
					register($pseudo, $hashMdp, $link);
					$_SESSION["logged"] = $pseudo;
					header('Location: index.php?suscribe=yes');
				}
				else{
					$stateMsg = "Ce pseudo est déjà pris";
				}
			} else{
				$stateMsg = "Le mot de passe ne correspond pas";
			}
		} 
		else{
			$stateMsg = "Veuiller saisir un mot de passe";
		}
	} else{
		$stateMsg = "Veuiller saisir un pseudo";
	}	
}

// affiche les onglet en fonction de l'état de l'utilisateur
// si il est connecter ou non 
// si il est utilisateur ou admin
$ongletAjout='';
$ongletConnexion='';
$ongletStatistique='';

if(isset($_SESSION["logged"]) && $_SESSION["logged"] !=""){
	$ongletAjout='<li><a href="ajout.php" id="tab-ajout">Ajouter une photo</a></li>';
	$ongletMesPhotos='<li><a href="mesphotos.php" id="tab-mesphotos">Mes photos</a></li>';
	$ongletConnexion='<li style="float:right"><a href="#id01" id="tab-deconnexion">Deconnexion</a></li>';
	
	$utilisateur = getUtilisateur($link, $_SESSION["logged"]);
	$statut = $utilisateur[2];
	if($statut == "admin"){
		$ongletStatistique='<li style="float:right"><a href="statistiques.php" id="tab-stat">Statistiques</a></li>';
		}
		else{
			$ongletStatistique='<li style="float:right"><a href="mesdonnees.php" id="tab-stat">Mes données</a></li>';
		}
}

else{
	$ongletAjout='<li><a href="ajout.php" id="tab-ajout" class="disabled" >Ajouter une photo</a></li>';
	$ongletMesPhotos='<li><a href="mesphotos.php" id="tab-mesphotos" class="disabled">Mes photos</a></li>';
	$ongletConnexion='<li style="float:right"><a href="connexion.php" id="tab-connexion">Connexion</a></li>';
}


?>

<!doctype html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>TP projet de BDW1</title>
	<link rel="stylesheet" type="text/css" href="css/styles.css">
</head>


<header> <!-- en-tête -->
	<h1 class="logo">Mini Pinterest</h1> <!--titre -->
	<p class="login_user"><?php echo $_SESSION["logged"]; ?></p>
</header>


<body> <!-- corps -->
	
	<div class="menu"> <!-- barre d'onglet -->
			 <ul>
  				<li><a href="index.php" class="active" id="tab-accueil">Accueil</a></li>
  				<?php echo $ongletAjout; ?>
  				<?php echo $ongletMesPhotos; ?>
  				<?php echo $ongletConnexion; ?>
  				<li style="float:right"><a href="inscription.php" id="tab-inscription">Inscription</a></li>
  				<?php echo $ongletStatistique; ?>
			</ul> 
	</div>
	
	<div class="background"> <!-- zone centrale -->
		<div class="container">

			<h2> Inscription </h2>
			<div class="errorMsg"><strong><?php echo $stateMsg; ?></strong></div>
			<div class="formulaire" style="margin:40px">  <!-- formulaire d'inscription -->
			<form name="insForm" method="POST">
				<p><label class="label">Pseudo</label></p>
            <div class="control">
            	<input id="pseudo" name="pseudo" class="input" type="text" placeholder="Gandalf le magnifique">
            </div>
            <p><label class="label">Mot de passe</label></p>
            <div class="control">
            	<input id="mdp" name="mdp" class="input" type="password" placeholder="*********">
            </div>
            <p><label class="label">Confirmer votre mot de passe</label></p>
            <div class="control">
            	<input id="mdpc" name ="mdpc" class="input" type="password" placeholder="*********">
            </div>
				<p><input id="inscription" type="submit" name="inscription" value="S'inscrire"></p>
			</form>
			</div>
			<a href="connexion.php" class="liencolo">déjà inscrit?</a>
		</div>
		<div class="controlemsg"><?php echo $control; ?></div>
	</div>
	<!-- boite modal connexion -->

<div id="id01" class="modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="header-modal"> 
        <a href="#" class="closebtn">×</a>
        <h2>Deconnexion</h2>
      </div>
      <div class="body-modal">
        <p>Souhaitez-vous vraiment vous deconnecter?</p>  
      </div>
      <div class="modal-footer">
      <form name="decForm" method="POST" action="index.php">
        <p><input name="btnDeco" type="submit" value="Se deconnecter" class="btn-modal"  ></p>
        </form>
      </div>
    </div>
  </div>
</div> 
		<script src="js/client.js"></script>
	</body>
	<footer> <!-- pied de page -->
	</footer>
	
</html>
