<?php 

/* recupère la table Categorie (id et nom) dans la base de donnée */
function getCategorie($link)
{
	$categorie = array();
	
	$query = "SELECT * FROM Categorie ORDER BY catId"; 
	$result = executeQuery($link, $query);
	
	$index = 0;
	while ($row = mysqli_fetch_assoc($result)) {
		$categorie[$index] = $row["catId"].";".$row["nomCat"];
		$index++;
    }
    
	return $categorie;
}

/* recupère le nom d'une catégorie en fonction de son identifiant */
function getCatid($link, $id)
{
	$query = "SELECT nomCat FROM Categorie WHERE catID = ". $id .";"; 
	$result = executeQuery($link, $query);
	
	return $result;
}

/* récupère la table Photo et la retourne dans un tableau ordonnée par id */
function getPhoto($link)
{
	$photos = array();
	
	$query = "SELECT * FROM Photo ORDER BY photoId";
	$result = executeQuery($link, $query);
	
	$index = 0;
	while ($row = mysqli_fetch_assoc($result)) {
		$photo[$index] = $row["photoId"].";".$row["nomFich"].";".$row["description"].";".$row["catId"].";".$row["extension"] .";".$row["auteur"];
		$index++;
    }
    return $photo;
}


/* récupère toutes les champs d'une photo depuis son nom passé en paramètre */
/* renvoie un tableau contenant toutes les colonnes du tuple photo retourné par la requête */
function getPhotobyNom($link, $nomPhoto)
{
	$detail = array();

	$query = "SELECT * FROM Photo WHERE nomFich = '". $nomPhoto ."';";
	$result = executeQuery($link, $query);
	
	$col = mysqli_fetch_assoc($result);
	$detail[0] = $col["photoId"];
	$detail[1] = $col["nomFich"];
	$detail[2] = $col["description"];
	$detail[3] = $col["catId"];
	$detail[4] = $col["extension"];
	$detail[5] = $col["auteur"];
	
	return $detail;
}



/* Récupère les champs d'une categorie (id et nom) en fonction du nom d'une photo passer en paramètre */
/* la fonction prend en paramètre le nom d'une photo; la photo contient l'id de la categorie */
/* la fonction retourne un tableau contenant les colones du tuple Categorie retournée par la raquête */
function getCat($link, $nomPhoto)
{
	$categorie = array();

	$query = "SELECT catId, nomCat FROM Categorie NATURAL JOIN Photo 
WHERE Photo.nomFich = '". $nomPhoto ."';";
	
	$result = executeQuery($link, $query);
	
	$col = mysqli_fetch_assoc($result);
	$categorie[0] = $col["catId"];
	$categorie[1] = $col["nomCat"];
	
	return $categorie;
}

/* ajoute une  photo à la base de donnée avec les données en paramètres*/
/* la fonction récupère tout les champs d'une photo passés en paramètre et créer une nouvelle ligne dans la base de donnée */
/* l'id n'est pas demander car il s'incrémente automatiquement */
function ajoutPhoto($photo, $description, $categorie, $extension, $utilisateur ,$link)
{
	$query = "INSERT INTO Photo (nomFich, description, catId, extension, auteur) VALUES ('". $photo ."', '". $description ."', '". $categorie ."', '". $extension ."' , '". $utilisateur ."');";
	executeUpdate($link, $query);
}


/* récupère les photos d'une categorie dont l'identifiant est passer en paramètre */
/* renvoie un tableau avec les tuples contenant les photos dont l'catId est identique à celui passer en paramètre */
function photoParCat($link, $idCat){

	$detail = array();

	$query = "SELECT * FROM Photo WHERE catId = ". $idCat .";";
	$result = executeQuery($link, $query);
	
	$index = 0;
	while ($row = mysqli_fetch_assoc($result)) {
		$photo[$index] = $row["photoId"].";".$row["nomFich"].";".$row["description"].";".$row["catId"].";".$row["extension"] .";".$row["auteur"];
		$index++;
    }	
	
	return $detail;
	
}

/* Récupère les photos dont l'auteur est identique au pseudo passé en paramètre */
/* renvoie un tableau avec les tuples de photo dont l'auteur est passé en paramètre */
function photoParUti($link, $pseudo){

	$detail = array();

	$query = "SELECT * FROM Photo WHERE auteur = '". $pseudo ."';";
	$result = executeQuery($link, $query);
	
	$index = 0;
	while ($row = mysqli_fetch_assoc($result)) {
		$photo[$index] = $row["photoId"].";".$row["nomFich"].";".$row["description"].";".$row["catId"].";".$row["extension"] .";".$row["auteur"];
		$index++;
    }	
	
	return $detail;
	
}

/* Supprime une photo dont le nom est passer un paramètre */
function deletePhoto($link, $id){
	
	$query = "DELETE FROM Photo WHERE photoId = '". $id ."';";
	executeUpdate($link, $query);
}

/* modifie une photo dont l'id est passé en paramètre */
/* la fonction prend tout les champs en paramètre est modifie ceux existants par ceux passé en paramètre */
function modifPhoto($link, $id, $nom, $description, $categorie, $extension, $auteur){
	$query = "UPDATE Photo SET nomFich = '". $nom ."', description ='". $description ."', catId ='". $categorie ."' WHERE photoId ='". $id ."';";
	executeUpdate($link, $query);
}

?>
