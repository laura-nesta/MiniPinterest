<!-- fonctions php --> 
<?php

session_start();
require_once 'fonctions/bd.php';
require_once 'fonctions/utilisateur.php';
require_once 'fonctions/categories.php';

$link = getConnection($dbHost, $dbUser, $dbPwd, $dbName);

$stateMsg ="";
$control = "";

// affiche les onglet en fonction de l'état de l'utilisateur
// si il est connecter ou non 
// si il est utilisateur ou admin
$ongletAjout='';
$ongletConnexion='';
$ongletStatistique='';

if(isset($_SESSION["logged"]) && $_SESSION["logged"] !=""){
	$ongletAjout='<li><a href="ajout.php" id="tab-ajout" class="active">Ajouter une photo</a></li>';
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

// Verifie la validité du formulaire et lance la fonction d'ajout
//Affiche un message d'erreur en cas non-valisité du formulaire
if(isset($_POST["envoyer"])){
	if(isset($_FILES['file']['name']) && $_FILES['file']['name']!=""){ //si file ok
		if(isset($_POST["description"]) && $_POST["description"] !=""){
			if(isset($_POST["select"]) && $_POST["select"] != 0){
				$taille = $_FILES['file']['size'];
				if($taille <= 100000){
					
					$file = $_FILES['file']['name'];
					$splitfile = explode(".", $file);
			
					$desc= $_POST["description"];
					$cat = $_POST["select"];
					$nom = $splitfile[0];
					$ext = $splitfile[1];
					$pseudo = $_SESSION["logged"];

					ajoutPhoto($nom, $desc, $cat, $ext, $pseudo, $link);
					$photo = getPhotobyNom($link, $nom);
					$id = $photo[0];
					
					move_uploaded_file($_FILES['file']['tmp_name'], './images/' . basename("DSC_". $id . '.' . $ext));
					
					//export($link);
					
					header('Location: photo.php?img_id='. $nom .'');
				}		
				else{
					$stateMsg = "la taille du fichier est trop gros (100ko maximum)";
				}
			} else {
				$stateMsg = "Veuiller selectionner une categorie";
			}
			
		} 
		else{
			$stateMsg = "Veuiller saisir une description";
		}
	} else{
		$stateMsg = "Veuiller choisir un fichier";
	}	
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
  				<li><a href="index.php" id="tab-accueil">Accueil</a></li>
  				<?php echo $ongletAjout; ?>
  				<?php echo $ongletMesPhotos; ?>
  				<?php echo $ongletConnexion; ?>
  				<li style="float:right"><a href="inscription.php" id="tab-inscription">Inscription</a></li>
  				<?php echo $ongletStatistique; ?>
			</ul> 
	</div>
	
	<div class="background"> <!-- zone centrale -->
		<div class="container">

			<h2> Ajouter une photo </h2>
			<div class="ajoutPhoto">
				<p><strong><?php echo $stateMsg; ?></strong></p>
			
				<label>choisissez votre fichier</label> <!-- formaulaire d'ajout de fichier -->
				<form method="POST" enctype="multipart/form-data">
					<input type="file" name="file" accept=".jpg, .jpeg, .png, .gif">
				<p><label>Décriver votre photo</label></br>
				<textarea type="text" name="description" rows="2"></textarea></p>
				<label>Choisissez une categorie:<label></br>
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
				<p class="btnAjout"><input name="envoyer" type="submit" value="Envoyer"></p>
				</form>
			</div>
			<?php echo $control; ?>
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
