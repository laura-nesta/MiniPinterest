<?php

$dbHost = "localhost";
$dbUser = "p1812594"; //num etudiant
$dbPwd = "Atrium14Safari"; //mdp
$dbName = "p1812594"; //nom base de donnée (num etu)

/*Connecte l'utilisateur à sa base de données*/
function getConnection($dbHost, $dbUser, $dbPwd, $dbName)
{
	$link = mysqli_connect($dbHost, $dbUser, $dbPwd, $dbName);
	if (!$link) {
		echo "Echec lors de la connexion a la base de donnees : (" . mysqli_connect_errno() . ") " . mysqli_connect_error();
	}
	return $link;
}


/* prend en entrée une connexion ainsi qu'une requete SQL SELECT et renvoie le résultat de la requête*/
function executeQuery($link, $query)
{
	$result = mysqli_query($link, $query);
	if(!$result){
		echo "La requete ".$query." n'a pas pu etre executee a cause d'une erreur de syntaxe";
	}
	return $result;
}

/*prend en entrée une cpnnexion ainsi qu'une requete SQL INSERT/UPDATE/DELETE et ne renvoie si la mise à jour à fonctionner*/
function executeUpdate($link, $query)
{
	$result = mysqli_query($link, $query);
	if(!$result){
		echo "La requete de mise a jour n'a pas pu etre executee a cause d'une erreur de syntaxe";
	}
}

 
/*Cette fonction ferme la connexion active $link passée en entrée*/
function closeConnexion($link)
{
	mysqli_close($link);
}

?>
