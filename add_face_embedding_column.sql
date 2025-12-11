-- Script SQL pour ajouter la colonne face_embedding à la table user2
-- Exécutez ce script dans votre base de données MySQL

ALTER TABLE `user2` 
ADD COLUMN `face_embedding` TEXT NULL AFTER `photo`;

-- La colonne face_embedding stockera les embeddings faciaux au format JSON
-- Elle est optionnelle (NULL) car tous les utilisateurs n'ont pas forcément enregistré leur Face ID


