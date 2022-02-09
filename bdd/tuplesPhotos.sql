CREATE TABLE IF NOT EXISTS Categorie (
  catId INT NOT NULL,
  nomCat VARCHAR(250) NOT NULL,
  PRIMARY KEY (catId)
);

INSERT INTO Categorie VALUES (1, 'Paysage');
INSERT INTO Categorie VALUES (2, 'Nouriture');
INSERT INTO Categorie VALUES (3, 'Animaux');
INSERT INTO Categorie VALUES (4, 'Personne');
INSERT INTO Categorie VALUES (5, 'Film & Série');
INSERT INTO Categorie VALUES (6, 'Dessin & Art');



CREATE TABLE IF NOT EXISTS Photo (
  photoId INT NOT NULL AUTO_INCREMENT,
  nomFich VARCHAR(250) NOT NULL,
  description VARCHAR(250),
  catId INT NOT NULL,
  extension VARCHAR(250) NOT NULL,
  auteur VARCHAR(250) NOT NULL,
  PRIMARY KEY (photoId),
  FOREIGN KEY (catId) REFERENCES Categorie (catId)
);

CREATE TABLE IF NOT EXISTS Utilisateur (
  pseudo VARCHAR(250) NOT NULL UNIQUE,
  mdp VARCHAR(250) NOT NULL,
  statut VARCHAR(250) NOT NULL,
  PRIMARY KEY (pseudo)
);
