UPDATE etat_civil 
SET prenom = 'prenomano', 
	nom= 'nomano', 
	telephone= '0123456789', photo=NULL, 
	date_naissance='1978-01-01 00:00:00.0', 
	probleme_medicaux='',
	personne_a_prevenir='anonymous',
	tel_pap='0123456789',
	fedegn='';

UPDATE proprietaire 
SET nom= 'nomano', adresse='adresse ano', mail='ano@ano.com', tel='118218';

UPDATE user 
SET email=concat(id, 'ano@ano.fr');

UPDATE photo 
SET data = null;

