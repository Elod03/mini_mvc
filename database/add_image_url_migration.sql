-- Migration pour ajouter la colonne image_url à la table produit
-- Exécutez ce script dans votre base de données MySQL/MariaDB

-- Vérifier si la colonne existe déjà avant de l'ajouter
-- Si la colonne existe déjà, cette commande échouera - c'est normal

ALTER TABLE produit 
ADD COLUMN image_url VARCHAR(255) NULL 
AFTER stock;

-- Note: La colonne est NULL pour permettre aux produits existants de ne pas avoir d'image
-- Vous pouvez modifier NULL en NOT NULL si vous souhaitez rendre l'image obligatoire









