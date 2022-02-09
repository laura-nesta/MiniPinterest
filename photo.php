<!-- fonctions php --> 
<?php

session_start();
require_once 'fonctions/bd.php';
require_once 'fonctions/utilisateur.php';
require_once 'fonctions/categories.php';

$link = getConnection($dbHost, $dbUser, $dbPwd, $dbName);

// si aucun utilisateur n'est connecté
if(!isset($_SESSION["logged"])){
	$_SESSION["logged"] = "";
	$statut = "";
}

//recupere le nom de l'image afficher
$nomP = $_GET['img_id'];
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
	$statut = "";
	$ongletAjout='<li><a href="ajout.php" id="tab-ajout" class="disabled" >Ajouter une photo</a></li>';
	$ongletMesPhotos='<li><a href="mesphotos.php" id="tab-mesphotos" class="disabled">Mes photos</a></li>';
	$ongletConnexion='<li style="float:right"><a href="connexion.php" id="tab-connexion">Connexion</a></li>';
}

//recupere toutes les données de la photo
$photo = getPhotobyNom($link, $nomP); 
$id = $photo[0];
$nomP = $photo[1];
$desc = $photo[2];
$cat = $photo[3];
$ext = $photo[4];
$aut = $photo[5];

//lance la suppression de photo
if(isset($_POST["btnSupp"])){

	deletePhoto($link, $id);
	header('Location: mesphotos.php');
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
					<h2> Détails de la photo </h2>
					<div class="detail">
						<div class="affImage">
							<?php
								$affiche = '<img class="image" src="images/DSC_' . $id .'.' . $ext . '">';
								echo $affiche;
							?>
						</div>
						<div class="info">
						<?php						
							$affiche = '<p><strong>Description :</strong>'. $desc .'</p>';
							$affiche .= '<p><strong>Nom du fichier :</strong>'. $nomP .'.' . $ext . '</p>';
							$affiche .= '<p><strong>Categorie :</strong><a class ="liencolo" href="index.php?select='. $categorie[0] .'">' . $categorie[1] . '</a></p>';
							$affiche .= '<p><strong>auteur :</strong>' . $aut . '</a></p>';
							echo $affiche;							
						?>
						</div>
					</div>
				</div>
				<div class="btn-detail">
				<?php
				$bouton ='';				
					if($statut == "admin" || $_SESSION["logged"] == $aut)
					{
						$bouton .='<a href="modifier.php?img_id='. $nomP .'"><input style="position:relative" class="btnModif" name="Modifier" type="submit" value="Modifier"></a>';
						$bouton .='<a><input class="btnSupp" name="Supprimer" type="submit" onclick="window.location.href = \'#id03\';" value="Supprimer"></a>';
					}
					echo $bouton;
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
			<!-- boite modal modification -->

<div id="id02" class="modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="header-modal"> 
        <a href="#" class="closebtn">×</a>
        <h2>Modifier</h2>
      </div>
      <div class="body-modal-mod">
        <p>Quelles modifications souhaitez-vous apporter ?</p>  
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
			</form>
      </div>
      <div class="modal-footer">
      <?php
      	$aff ='<form name="decForm" method="POST">';      	
      	echo $aff;
      ?>
        <p><input name="btnModd" type="submit" value="Modifier" class="btn-modal"  ></p>
      </form>
      </div>
    </div>
  </div>
</div> 

	<!-- boite modal suppression -->

<div id="id03" class="modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="header-modal"> 
        <a href="#" class="closebtn">×</a>
        <h2>Suppression</h2>
      </div>
      <div class="body-modal">
        <p>Souhaitez-vous vraiment supprimer cette photo?</p>  
      </div>
      <div class="modal-footer">
      <form name="supForm" method="POST">
        <p><input name="btnSupp" type="submit" value="Supprimer" class="btn-modal"></p>
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
