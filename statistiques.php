<!-- fonctions php --> 
<?php

session_start();
require_once 'fonctions/bd.php';
require_once 'fonctions/categories.php';
require_once 'fonctions/utilisateur.php';

$link = getConnection($dbHost, $dbUser, $dbPwd, $dbName);

if(!isset($_GET["select"])){
	$_GET["select"] = 0;
}

// si aucun utilisateur n'est connecté
if(!isset($_SESSION["logged"])){
	$_SESSION["logged"] = "";
}

// affiche les onglet en fonction de l'état de l'utilisateur
// si il est connecter ou non 
// si il est utilisateur ou admin
$ongletAjout='';
$ongletMesPhotos='';
$ongletConnexion='';
$ongletStatistique='';

if(isset($_SESSION["logged"]) && $_SESSION["logged"] !=""){
	$ongletAjout='<li><a href="ajout.php" id="tab-ajout">Ajouter une photo</a></li>';
	$ongletMesPhotos='<li><a href="mesphotos.php" id="tab-mesphotos">Mes photos</a></li>';
	$ongletConnexion='<li style="float:right"><a href="#id01" id="tab-deconnexion">Deconnexion</a></li>';
	
	$utilisateur = getUtilisateur($link, $_SESSION["logged"]);
	$statut = $utilisateur[2];
	if($statut == "admin"){
		$ongletStatistique='<li style="float:right" class="active" ><a href="statistiques.php" id="tab-stat">Statistiques</a></li>';
	}
}
else{
	$ongletAjout='<li><a href="ajout.php" id="tab-ajout" class="disabled" >Ajouter une photo</a></li>';
	$ongletMesPhotos='<li><a href="mesphotos.php" id="tab-mesphotos" class="disabled">Mes photos</a></li>';
	$ongletConnexion='<li style="float:right"><a href="connexion.php" id="tab-connexion">Connexion</a></li>';
}

if(isset($_POST["btnDeco"])){
	$_SESSION["logged"]='';
	header('Location: index.php');
}

//recupere les données de Categorie, Photo et Utilisateur 
$affiche ='';
$utilisateur = getAllUser($link);
$nbUt = sizeof($utilisateur);
			
$categorie = getCategorie($link);
$nbCat = sizeof($categorie);

$photo = getPhoto($link);
$nbPhoto = sizeof($photo);

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
	<!--<p class="login_user"><?php echo $hour; ?></p>-->
</header>


<body> <!-- corps -->
	
	<div class="menubar">
	<div class="menu">
			 <ul>
  				<li><a href="index.php" id="tab-accueil">Accueil</a></li>
  				<?php echo $ongletAjout; ?>
  				<?php echo $ongletMesPhotos; ?>
  				<?php echo $ongletConnexion; ?>
  				<li style="float:right"><a href="inscription.php" id="tab-inscription">Inscription</a></li>
  				<?php echo $ongletStatistique; ?>
			</ul> 
		</div>
		</div>
	
	<div class="background"> <!-- zone centrale -->
		<div class="container">
			<h2> Statistiques </h2>
			<div class="ajoutPhoto">
				<?php
					$affiche .= '<p><strong>Nombre d\'utilisateurs: </strong>'. $nbUt .'</p></br>';
					$affiche .= '<p><strong>Nombre de photos: </strong>'. $nbPhoto .'</p></br>';
					
					$affiche .='<p><strong>Nombre de photos par categorie: </strong></p>';
					$affiche .='<table><tr><th>#</th><th>Categorie</th><th>Nombre de photos</th></tr>';
					
					$image = getPhoto($link);
					$ind = sizeof($image);	
					
					for ($i = 0; $i < $nbCat; $i++) {
						$cat = $categorie[$i];
						$splitcat = explode(";", $cat);
						$catid = $splitcat[0];
						$cat = $splitcat[1];
						
						$cmp = photoParCat($link, $catid);
						$nbph = 0;
						
						for($j = 0; $j < $nbPhoto; $j++){
							$ph = $photo[$j];
							$splitph = explode(";", $ph);
							$idCat = $splitph[3];
							if($idCat == $catid){
							$nbph++;
							}
						}
						$affiche .='<tr><td>'. $i .'</td><td>'. $cat .'</td><td>'. $nbph .'</td></tr>';
					}
					
					$affiche .='</table></br>';
					
					$affiche .='<p><strong>Nombre de photos par utilisateur: </strong></p>';
					$affiche .='<table><tr><th>#</th><th>Utilisateur</th><th>Nombre de photos</th><th>Statut</th></tr>';
					
					
					for ($i = 0; $i < $nbUt; $i++) {
						$ut = $utilisateur[$i];
						$splitut = explode(";", $ut);
						$pseudo = $splitut[0];
						$statut = $splitut[2];
						
						$cmp = photoParUti($link, $pseudo);
						//$nbph = sizeof($cmp);
						$nbph = 0;
						
						for($j = 0; $j < $nbPhoto; $j++){
							$ph = $photo[$j];
							$splitph = explode(";", $ph);
							$auteur = $splitph[5];
							if($auteur == $pseudo){
							$nbph++;
							}
						}
						
						$affiche .='<tr><td>'. $i .'</td><td>'. $pseudo .'</td><td>'. $nbph .'</td><td>'. $statut .'</td></tr>';
					}
					
					$affiche .='</table></br>';
					
					echo $affiche;
				?>
				
				
			</div>
		</div>
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
