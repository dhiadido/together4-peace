-- Script SQL pour ajouter la colonne date_inscription à la table user2
-- Exécutez ce script dans votre base de données MySQL (phpMyAdmin ou ligne de commande)

-- Vérifier d'abord si la colonne existe, puis l'ajouter si nécessaire
-- Note: Si la colonne existe déjà, vous obtiendrez une erreur que vous pouvez ignorer

ALTER TABLE `user2` 
ADD COLUMN `date_inscription` DATETIME NULL DEFAULT CURRENT_TIMESTAMP AFTER `role`;

-- Mettre à jour les enregistrements existants avec la date actuelle si la colonne était NULL
UPDATE `user2` SET `date_inscription` = NOW() WHERE `date_inscription` IS NULL;

