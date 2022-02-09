<!-- fonctions php --> 
<?php

session_start();
require_once 'fonctions/bd.php';
require_once 'fonctions/utilisateur.php';
require_once 'fonctions/categories.php';

$link = getConnection($dbHost, $dbUser, $dbPwd, $dbName);

$nomP = $_GET['img_id'];
$image = getPhotobyNom($link, $nomP);
$categorie = getCat($link, $nomP);

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

if(isset($_POST["btnSup"])){
	deletePhoto($link, $nom);
}

$control = '';
$stateMsg = '';

// recupere les données de la photo a modifiée
$photo = getPhotobyNom($link, $nomP); 
$id = $photo[0];
$nomP = $photo[1];
$desc = $photo[2];
$cat = $photo[3];
$ext = $photo[4];
$aut = $photo[5];

//effectue les modifications en fonction des données du formaulaire
if(isset($_POST["btnModd"])){
	if(($_POST["select"]) != 0){
		$cat = $_POST["select"];
	}
	if($_POST["description"] != ""){
		$desc = $_POST["description"];
	}
	if($_POST["nom"] != ""){
		$nomP = $_POST["nom"];
	}	
	modifPhoto($link, $id, $nomP, $desc, $cat, $ext, $aut);
	header('Location: photo.php?img_id='. $nomP .'');
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
		<div class="background"> <!-- zone centrale -->
			<div class="container">
				<div class="detailPhoto">
					<h2>Modifier</h2>
      		</div>	
        		<p>Quelles modifications souhaitez-vous apporter ?</p>  
        			<?php        			
        			echo $stateMsg; 
        			?>
        		<form name="modification" method="POST">
        			<p><label>Modifier le nom de votre image</label></br>
        			<?php
        					$form = '';
				  			$form .= '<input id="nom" name="nom" class="input" type="text" value ='. $nomP .'>';
        					$form .= '<p><label>Modifier la description de votre photo</label></br>';
        					$form .='<textarea type="text" name="description" rows="2">'. $desc .'</textarea></p>';
        					echo $form; 
        			?>
					<label>Modifier categorie:<label></br>
					<select name="select" onchange="updated(this)">
					<option value=0></option>';
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
						}
						echo $sel;
					?>
				</select>  	      	
        		<p><input style="margin-bottom: 15px" name="btnModd" type="submit" value="Modifier" class="btn-modal"></p>
        		</form>
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
