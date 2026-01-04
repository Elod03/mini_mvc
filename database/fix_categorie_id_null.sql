-- Migration pour permettre NULL sur categorie_id dans la table produit
-- Exécutez ce script dans votre base de données MySQL/MariaDB si categorie_id ne peut pas être NULL

-- Modifier la colonne categorie_id pour permettre NULL
ALTER TABLE produit 
MODIFY COLUMN categorie_id INT NULL;

-- Note: Cette commande modifie la colonne pour permettre les valeurs NULL
-- Si la colonne accepte déjà NULL, cette commande ne changera rien









