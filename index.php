<!-- fonctions php --> 
<?php

session_start();
require_once 'fonctions/bd.php';
require_once 'fonctions/utilisateur.php';
require_once 'fonctions/categories.php';

$link = getConnection($dbHost, $dbUser, $dbPwd, $dbName);

//si aucune categorie n'est choisie
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

// action pour déconnecter l'utilisateur
if(isset($_POST["btnDeco"])){
	$_SESSION["logged"]='';
	header('Location: index.php');
}
?>

<!doctype html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>TP projet de BDW1</title>
	<link rel="stylesheet" type="text/css" href="css/styles.css">
</head>

<!-- en-tête -->
<header> 
	<h1 class="logo">Mini Pinterest</h1> <!--titre -->
	<p class="login_user"><?php echo $_SESSION["logged"]; ?></p>
	<!--<p class="login_user"><?php echo $hour; ?></p>-->
</header>

<!-- corps -->
<body>
	<div class="menubar"> <!-- berre d'onglets -->
		<div class="menu">
			<ul>
  				<li><a href="index.php" class="active" id="tab-accueil">Accueil</a></li>
  				<?php echo $ongletAjout; ?>
  				<?php echo $ongletMesPhotos; ?>
  				<?php echo $ongletConnexion; ?>
  				<li style="float:right"><a href="inscription.php" id="tab-inscription">Inscription</a></li>
  				<?php echo $ongletStatistique; ?>
			</ul> 
		</div>
	</div>
	<!-- zone centrale -->
	<div class="background"> 
		<div class="container">
			<div class="info_categorie">
				<p> Nombres de photos affichées : <span id="nbPicturesDisplayed">
				<?php
					$cmp = 0;
					$image = getPhoto($link);
					$ind = sizeof($image);	
					for ($i = 0; $i < $ind; $i++) {
						$img = $image[$i];
						$splitimg = explode(";", $img);
						$idc = $splitimg[3];
						if ($_GET["select"] == 0 || (isset($_GET["select"]) and $_GET["select"] == '')){
							$cmp = $ind;
						}
						else if($_GET["select"] == $idc){
							$cmp++;
						}
					}
					echo $cmp;
				?>
				</span>
					<div class="categorie">
						<p>Quelle catégorie de photo souhaitez-vous afficher ?</p>
						<form name="selectCat" method="GET" action="index.php">
							<select name="select" onchange="updated(this)">
							<option value="0">toutes les photos</option>
							<?php
								$sel = '';
								$option = getCategorie($link);
								$ind = sizeof($option);
								for ($i = 0; $i < $ind; $i++) {
									$opt = $option[$i];
									$splitopt = explode(";", $opt);
									$id = $splitopt[0];
									$nom = $splitopt[1];
    								$sel .= '<option value="' . $id . '">'. $nom .'</option>';
    								$aff = '<p>'. $nom .'</p>';
								}
								echo $sel;		
								echo $aff;
							?>
							</select>
							<input type="submit" value="Valider">
						</form>
					</div>
			</div>
			<div class="SectionImages" display="flex"> <!-- images à afficher -->
			<?php
				$affiche = '';
				$image = getPhoto($link);
				$ind = sizeof($image);	
				for ($i = 0; $i < $ind; $i++) {
					$img = $image[$i];
					$splitimg = explode(";", $img);
					$idp = $splitimg[0];
					$nom = $splitimg[1];
					$des = $splitimg[2];
					$idc = $splitimg[3];
					$ext = $splitimg[4];
					if ($_GET["select"] == 0){
						$affiche .= '<img class="images" src="images/DSC_' . $idp . '.' . $ext . '" onclick=location.href="photo.php?img_id='. $nom .'">';
						$_SESSION["nomImg"]=$nom;
					}
					else if($_GET["select"] == $idc){
						$affiche .= '<img class="images" src="images/DSC_' . $idp . '.' . $ext . '" onclick=location.href="photo.php?img_id='. $nom .'">';
						$_SESSION["nomImg"]=$nom;
					}
				}
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
<!-- pied de page -->
<footer> 
</footer>
</html>
