-- Optimisations MySQL pour éviter les erreurs "MySQL server has gone away"
-- Exécutez ce script dans phpMyAdmin ou via la ligne de commande MySQL

-- Augmenter le timeout de connexion (en secondes)
SET GLOBAL wait_timeout = 28800; -- 8 heures
SET GLOBAL interactive_timeout = 28800; -- 8 heures

-- Augmenter la taille maximale des paquets
SET GLOBAL max_allowed_packet = 67108864; -- 64MB

-- Augmenter le nombre maximum de connexions
SET GLOBAL max_connections = 200;

-- Activer les connexions persistantes (recommandé)
SET GLOBAL innodb_buffer_pool_size = 134217728; -- 128MB pour InnoDB

-- Afficher les paramètres actuels
SHOW VARIABLES WHERE Variable_name IN (
    'wait_timeout',
    'interactive_timeout',
    'max_allowed_packet',
    'max_connections',
    'innodb_buffer_pool_size'
);
