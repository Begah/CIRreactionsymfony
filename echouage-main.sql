-- Seul fichier a executer en root, il creer une database, user, et met la data

DROP DATABASE IF EXISTS FRprojdb;
DROP USER IF EXISTS 'symfonyuser'@'localhost';

CREATE DATABASE FRprojdb;


CREATE USER 'symfonyuser'@'localhost' IDENTIFIED BY 'symfony44';

GRANT ALL PRIVILEGES ON FRprojdb.* TO 'symfonyuser'@'localhost';

USE FRprojdb;

source echouage-structure.sql
source echouage-data.sql
