<!-- fonctions php --> 
<?php

session_start();
require_once 'fonctions/bd.php';
require_once 'fonctions/utilisateur.php';
require_once 'fonctions/categories.php';

$link = getConnection($dbHost, $dbUser, $dbPwd, $dbName);

if(!isset($_GET["select"])){
	$_GET["select"] = 0;
}

$stateMsg ="";
$control = "";

// affiche les onglet en fonction de l'état de l'utilisateur
// si il est connecter ou non 
// si il est utilisateur ou admin
$ongletAjout='';
$ongletConnexion='';
$ongletStatistique='';

if(isset($_SESSION["logged"]) && $_SESSION["logged"] !=""){
	$ongletAjout='<li><a href="ajout.php" id="tab-ajout">Ajouter une photo</a></li>';
	$ongletMesPhotos='<li><a href="mesphotos.php" id="tab-mesphotos" >Mes photos</a></li>';
	$ongletConnexion='<li style="float:right"><a href="#id01" id="tab-deconnexion">Deconnexion</a></li>';
	
	$utilisateur = getUtilisateur($link, $_SESSION["logged"]);
	$pseudo = $utilisateur[0];
	$mdp = $utilisateur[1];
	$statut = $utilisateur[2];
	if($statut == "admin"){
		$ongletStatistique='<li style="float:right"><a href="statistiques.php" id="tab-stat">Statistiques</a></li>';
	}
	else{
			$ongletStatistique='<li style="float:right"><a href="mesdonnees.php" id="tab-stat" class="active">Mes données</a></li>';
		}
}

//supprime l'utilisateur 
if(isset($_POST["btnSupp"])){

	deleteUser($link, $pseudo);
	$_SESSION["logged"]='';
	header('Location: index.php');
}

//modifie le pseudo de l'utilisateur
if(isset($_POST["btnModPs"])){
	$npseudo = $_POST["nom"];
	if($npseudo != ""){
		checkAvailability($npseudo, $link);
		if($available){
			modifPseudo($link, $npesudo, $mdp);
			$_SESSION["logged"] = $npeudo;
			header('Location: mesdonnees.php');
		}
	}
}

//modifier le mot de passe de l'utilisateur 
if(isset($_POST["btnModMdp"])){
	if($_POST["mdpo"] == $mdp){
		if($_POST["mdp"]  == $_POST["mdpc"] ){
			modifMdp($link, $pesudo, $_POST["mdp"]);
			header('Location: mesdonnees.php');
		}
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
	
	<div class="background"> <!-- zone centrale -->
		<div class="container">

			<h2> Mes Données personnelles</h2>
			<div class="info_categorie">
					<div class="categorie">
						<?php
						$affiche = '';
						
						$affiche .= '<p><strong>Mon pseudo: </strong>'. $_SESSION["logged"] .'</p></br>';
						
						$affiche .='<a><input style="margin: 5px"class="btnModifPseudo" name="ModifierPseudo" type="submit" onclick="window.location.href = \'#id04\';" value="Modifier mon pseudo"></a></br>';
						$affiche .='<a><input style="margin: 5px"class="btnModifMdp" name="ModifierMdp" type="submit" onclick="window.location.href = \'#id05\';" value="Modifier mon mot de passe"></a></br>';
						$affiche .='<a><input style="margin: 5px"class="btnSupCmt" name="suppCmt" type="submit" onclick="window.location.href = \'#id03\';" value="Supprimer mon compte"></a>';
						
						echo $affiche;
			?>
					</div>	
			</div>
			</div>
		</div>
			<?php echo $control; ?>
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
	</div>
	<!-- boite modal modification pseudo-->

<div id="id04" class="modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="header-modal"> 
        <a href="#" class="closebtn">×</a>
        <h2>Modifier mon pseudo</h2>
      </div>
      <div class="body-modal-mod">
        <p>Quelle pseudo souhaitez-vous choisir ?</p>  
        <form name="modification" method="POST">
        		<?php
        			$form = '';
        			
        			$form .= '<input method="POST" id="nom" name="pseudo" class="input" type="text" value ='. $_SESSION["logged"] .'>';
        			
        			echo $form; 
        		?>
			</form>
      </div>
      <div class="modal-footer">
        <p><input name="btnModPs" type="submit" value="Modifier" class="btn-modal"  ></p>
        </form>
      </div>
    </div>
  </div>
  </div>
  	<!-- boite modal modification mot de passe-->

<div id="id05" class="modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="header-modal"> 
        <a href="#" class="closebtn">×</a>
        <h2>Modifier mon mot de passe</h2>
      </div>
      <div class="body-modal-mod">
        <p></p>  
        <form name="modifMdp" method="POST">
        		<?php
        			$form = '';
        	
        			$form .= '<p>Saisissez votre ancient mot passe</p>';
        			$form .= '<input id="mdpo" name="mdpo" class="input" type="password" placeholder="*********">';
        			$form .= '<p>Saisissez votre nouveau mot passe</p>';
        			$form .= '<input id="mdp" name="mdp" class="input" type="password" placeholder="*********">';
        			$form .= '<p>Confirmer votre nouveau mot passe</p>';
        			$form .= '<input id="mdpc" name="mdpc" class="input" type="password" placeholder="*********">';
        			
        			echo $form; 
        		?>
			</form>
      </div>
      <div class="modal-footer">
        <p><input name="btnModMdp" type="submit" value="Modifier" class="btn-modal"  ></p>
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
        <p>Souhaitez-vous vraiment supprimer votre compte?</p>  
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
