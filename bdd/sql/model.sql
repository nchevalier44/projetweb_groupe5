#------------------------------------------------------------
#        Script MySQL.
#------------------------------------------------------------
# Verification de la présence de la base de données et supression si elle existe
DROP DATABASE IF EXISTS solar_manager;
CREATE DATABASE solar_manager;
USE solar_manager;

#------------------------------------------------------------
# Table: installateur
#------------------------------------------------------------

CREATE TABLE installateur(
        id           Int  Auto_increment  NOT NULL ,
        Installateur Varchar (50) NOT NULL
	,CONSTRAINT installateur_PK PRIMARY KEY (id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: pays
#------------------------------------------------------------

CREATE TABLE pays(
        id      Int  Auto_increment  NOT NULL ,
        Country Varchar (50) NOT NULL
	,CONSTRAINT pays_PK PRIMARY KEY (id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: region
#------------------------------------------------------------

CREATE TABLE region(
        id       Int  Auto_increment  NOT NULL ,
        Reg_nom  Varchar (50) NOT NULL ,
        Reg_code Varchar (50) NOT NULL ,
        id_pays  Int NOT NULL
	,CONSTRAINT region_PK PRIMARY KEY (id)

	,CONSTRAINT region_pays_FK FOREIGN KEY (id_pays) REFERENCES pays(id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: departement
#------------------------------------------------------------

CREATE TABLE departement(
        id        Int  Auto_increment  NOT NULL ,
        Dep_nom   Varchar (50) NOT NULL ,
        Dep_code  Varchar (50) NOT NULL ,
        id_region Int NOT NULL
	,CONSTRAINT departement_PK PRIMARY KEY (id)

	,CONSTRAINT departement_region_FK FOREIGN KEY (id_region) REFERENCES region(id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: ville
#------------------------------------------------------------

CREATE TABLE ville(
        code_insee   Varchar (50) NOT NULL ,
        Nom_standard Varchar (50) NOT NULL ,
        Population   Int NOT NULL ,
        Code_postal  Varchar (50) NOT NULL ,
        id           Int NOT NULL
	,CONSTRAINT ville_PK PRIMARY KEY (code_insee)

	,CONSTRAINT ville_departement_FK FOREIGN KEY (id) REFERENCES departement(id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: localisation
#------------------------------------------------------------

CREATE TABLE localisation(
        id         Int  Auto_increment  NOT NULL ,
        Lat        Float NOT NULL ,
        Lon        Float NOT NULL ,
        code_insee Varchar (50) NOT NULL
	,CONSTRAINT localisation_PK PRIMARY KEY (id)

	,CONSTRAINT localisation_ville_FK FOREIGN KEY (code_insee) REFERENCES ville(code_insee)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: marque_panneau
#------------------------------------------------------------

CREATE TABLE marque_panneau(
        id              Int  Auto_increment  NOT NULL ,
        Panneaux_marque Varchar (50) NOT NULL
	,CONSTRAINT marque_panneau_PK PRIMARY KEY (id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: modele_panneau
#------------------------------------------------------------

CREATE TABLE modele_panneau(
        id              Int  Auto_increment  NOT NULL ,
        Panneaux_modele Varchar (50) NOT NULL
	,CONSTRAINT modele_panneau_PK PRIMARY KEY (id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: panneau
#------------------------------------------------------------

CREATE TABLE panneau(
        id                Int  Auto_increment  NOT NULL ,
        id_marque_panneau Int NOT NULL ,
        id_modele_panneau Int NOT NULL
	,CONSTRAINT panneau_PK PRIMARY KEY (id)

	,CONSTRAINT panneau_marque_panneau_FK FOREIGN KEY (id_marque_panneau) REFERENCES marque_panneau(id)
	,CONSTRAINT panneau_modele_panneau0_FK FOREIGN KEY (id_modele_panneau) REFERENCES modele_panneau(id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: marque_onduleur
#------------------------------------------------------------

CREATE TABLE marque_onduleur(
        id              Int  Auto_increment  NOT NULL ,
        Onduleur_marque Varchar (50) NOT NULL
	,CONSTRAINT marque_onduleur_PK PRIMARY KEY (id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: modele_onduleur
#------------------------------------------------------------

CREATE TABLE modele_onduleur(
        id              Int  Auto_increment  NOT NULL ,
        Onduleur_modele Varchar (50) NOT NULL
	,CONSTRAINT modele_onduleur_PK PRIMARY KEY (id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: onduleur
#------------------------------------------------------------

CREATE TABLE onduleur(
        id                 Int  Auto_increment  NOT NULL ,
        id_marque_onduleur Int NOT NULL ,
        id_modele_onduleur Int NOT NULL
	,CONSTRAINT onduleur_PK PRIMARY KEY (id)

	,CONSTRAINT onduleur_marque_onduleur_FK FOREIGN KEY (id_marque_onduleur) REFERENCES marque_onduleur(id)
	,CONSTRAINT onduleur_modele_onduleur0_FK FOREIGN KEY (id_modele_onduleur) REFERENCES modele_onduleur(id)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: installation
#------------------------------------------------------------

CREATE TABLE installation(
        id                Int  Auto_increment  NOT NULL ,
        Iddoc             Int NOT NULL ,
        Nb_panneaux       Int NOT NULL ,
        Nb_onduleurs      Int NOT NULL ,
        Puissance_crete   Int NOT NULL ,
        Pente             Int NOT NULL ,
        Orientation       Varchar (50) NOT NULL ,
        Surface           Int NOT NULL ,
        Production_pvgis  Int NOT NULL ,
        Pente_optimum     Int,
        Orientation_opti  Int,
        Mois_installation Int NOT NULL ,
        An_installation   Int NOT NULL ,
        id_installateur   Int ,
        id_onduleur       Int NOT NULL ,
        id_panneau        Int NOT NULL ,
        id_localisation   Int NOT NULL
	,CONSTRAINT installation_PK PRIMARY KEY (id)

	,CONSTRAINT installation_installateur_FK FOREIGN KEY (id_installateur) REFERENCES installateur(id)
	,CONSTRAINT installation_onduleur0_FK FOREIGN KEY (id_onduleur) REFERENCES onduleur(id)
	,CONSTRAINT installation_panneau1_FK FOREIGN KEY (id_panneau) REFERENCES panneau(id)
	,CONSTRAINT installation_localisation2_FK FOREIGN KEY (id_localisation) REFERENCES localisation(id)
)ENGINE=InnoDB;
