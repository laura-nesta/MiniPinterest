<?php 

/* la fonction vérifie que le pseudo en paramètre n'est pas déjà dans la base de donnée  */
function checkAvailability($pseudo, $link)
{
	$query = "SELECT pseudo FROM Utilisateur WHERE pseudo = '". $pseudo ."';";
	$result = executeQuery($link, $query);
	return mysqli_num_rows($result) == 0;
}

/* la fonction enregistre un nouvel utilisateur dans la base de donnée */
/* la fonction prend les valeurs en paramètre et créer un nouvel utilisateur */
/* par défaut, un nouvel inscrit à le statut d'utilisateur */
function register($pseudo, $hashPwd, $link)
{
	$query = "INSERT INTO Utilisateur VALUES ('". $pseudo ."', '". $hashPwd ."', 'utilisateur');";
	executeUpdate($link, $query);
}

/*Cette fonction prend en entrée un pseudo et mot de passe et renvoie vrai si l'utilisateur existe (au moins un tuple dans le résultat), faux sinon*/
function getUser($pseudo, $hashPwd, $link)
{
	$query = "SELECT pseudo FROM Utilisateur WHERE pseudo = '". $pseudo ."' AND mdp = '". $hashPwd ."';";
	$result = executeQuery($link, $query);
	return (mysqli_num_rows($result) == 1);
}

/* récupère les données d'un utilisateur dont le pseudo est passé en paramètre */
/* renvoie un tableau avec le tuple utilisateur */
function getUtilisateur($link, $pseudo){
	
	$detail = array();

	$query = "SELECT * FROM Utilisateur WHERE pseudo = '". $pseudo ."';";
	$result = executeQuery($link, $query);
	
	$col = mysqli_fetch_assoc($result);
	$detail[0] = $col["pseudo"];
	$detail[1] = $col["mdp"];
	$detail[2] = $col["statut"];
	
	return $detail;
}


/* récupère tous les utilisateurs de la base de donnée */
/* renvoie un tableau contenant tous les tuples Utilisateurs (renvoie la table Utilisateur) */
function getAllUser($link)
{
	$utilisateur = array();
	
	$query = "SELECT * FROM Utilisateur;";
	$result = executeQuery($link, $query);
	
	$index = 0;
	while ($row = mysqli_fetch_assoc($result)) {
		$utilisateur[$index] = $row["pseudo"].";".$row["mdp"].";".$row["statut"];
		$index++;
    }
	
	return $utilisateur;
}

/* modifie le pseudo d'un utilisateur dont le mdp est passé en paramètre */
/* la fonction prend tout les champs en paramètre est modifie le pseudo */
function modifPseudo($link, $pseudo, $mdp)
{
	$query = "UPDATE Utilisateur SET pseudo = '". $pseudo ." WHERE mdp ='". $mdp ."';";
	executeUpdate($link, $query);
}

/* modifie le mot de passe d'un utilisateur dont le pseudo est passé en paramètre */
/* la fonction prend tout les champs en paramètre est modifie le mot de passe */
function modifMdp($link, $pseudo, $mdp)
{
	$query = "UPDATE Utilisateur SET mdp = '". $mdp ." WHERE pseudo ='". $pseudo ."';";
	executeUpdate($link, $query);
}

/* Supprime un utilisateur dont le pseudo est passer un paramètre */
function deleteUser($link, $pseudo)
{
	$query = "DELETE FROM Utilisateur WHERE pseudo = '". $pseudo ."';";
	executeUpdate($link, $query);
}

?>
