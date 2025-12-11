-- Script SQL pour ajouter la colonne google_id à la table user2
-- Exécutez ce script dans votre base de données MySQL (phpMyAdmin ou ligne de commande)

USE baseuser;

-- Vérifier d'abord si les colonnes existent, puis les ajouter si nécessaire
-- Note: Si les colonnes existent déjà, vous obtiendrez une erreur que vous pouvez ignorer

-- Ajouter la colonne google_id
ALTER TABLE `user2` 
ADD COLUMN `google_id` VARCHAR(255) NULL DEFAULT NULL AFTER `email`;

-- Ajouter la colonne photo (si elle n'existe pas déjà)
ALTER TABLE `user2` 
ADD COLUMN `photo` VARCHAR(500) NULL DEFAULT NULL AFTER `google_id`;

-- Ajouter un index unique sur google_id pour éviter les doublons
-- Note: Si l'index existe déjà, vous obtiendrez une erreur que vous pouvez ignorer
CREATE UNIQUE INDEX `idx_google_id` ON `user2` (`google_id`);

