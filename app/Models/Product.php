<?php

namespace Mini\Models;

use Mini\Core\Database;
use PDO;

class Product
{
    private $id;
    private $nom;
    private $description;
    private $prix;
    private $stock;
    private $image_url;
    private $categorie_id;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getPrix()
    {
        return $this->prix;
    }

    public function setPrix($prix)
    {
        $this->prix = $prix;
    }

    public function getStock()
    {
        return $this->stock;
    }

    public function setStock($stock)
    {
        $this->stock = $stock;
    }

    public function getImageUrl()
    {
        return $this->image_url;
    }

    public function setImageUrl($image_url)
    {
        $this->image_url = $image_url;
    }

    public function getCategorieId()
    {
        return $this->categorie_id;
    }

    public function setCategorieId($categorie_id)
    {
        $this->categorie_id = $categorie_id;
    }

    public static function getAll()
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->query("
            SELECT p.*, c.nom as categorie_nom 
            FROM produit p 
            LEFT JOIN categorie c ON p.categorie_id = c.id 
            ORDER BY p.id DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById($id)
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("
            SELECT p.*, c.nom as categorie_nom 
            FROM produit p 
            LEFT JOIN categorie c ON p.categorie_id = c.id 
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private static function hasImageUrlColumn()
    {
        static $hasColumn = null;
        if ($hasColumn === null) {
            try {
                $pdo = Database::getPDO();
                $stmt = $pdo->query("SHOW COLUMNS FROM produit LIKE 'image_url'");
                $hasColumn = $stmt->rowCount() > 0;
            } catch (\Exception $e) {
                $hasColumn = false;
            }
        }
        return $hasColumn;
    }

    private static function categoryExists($categorie_id)
    {
        if ($categorie_id === null || $categorie_id === '' || $categorie_id === 0) {
            return false;
        }

        $categorie_id = (int)$categorie_id;
        if ($categorie_id <= 0) {
            return false;
        }
        
        try {
            $pdo = Database::getPDO();

            $stmt = $pdo->query("SHOW TABLES LIKE 'categorie'");
            if ($stmt->rowCount() == 0) {
                return false;
            }

            $stmt = $pdo->prepare("SELECT id FROM categorie WHERE id = ? LIMIT 1");
            $stmt->execute([$categorie_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            return $result !== false && isset($result['id']) && (int)$result['id'] === $categorie_id;
        } catch (\Exception $e) {
            return false;
        } catch (\PDOException $e) {
            return false;
        }
    }

    private function saveWithoutCategory()
    {
        $pdo = Database::getPDO();
        
        $values = [
            $this->nom,
            $this->description,
            $this->prix,
            $this->stock
        ];
        
        $columns = ['nom', 'description', 'prix', 'stock'];
        $placeholders = ['?', '?', '?', '?'];
        
        if (self::hasImageUrlColumn()) {
            $columns[] = 'image_url';
            $placeholders[] = '?';
            $values[] = $this->image_url;
        }
        
        $sql = "INSERT INTO produit (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($values);
    }

    public function save()
    {
        $pdo = Database::getPDO();

        $values = [
            $this->nom,
            $this->description,
            $this->prix,
            $this->stock
        ];

        $columns = ['nom', 'description', 'prix', 'stock'];
        $placeholders = ['?', '?', '?', '?'];

        if (self::hasImageUrlColumn()) {
            $columns[] = 'image_url';
            $placeholders[] = '?';
            $values[] = $this->image_url;
        }

        $shouldIncludeCategory = false;
        $validCategorieId = null;
        
        if ($this->categorie_id !== null && $this->categorie_id !== '' && $this->categorie_id !== 0) {
            $categorieIdInt = (int)$this->categorie_id;
            if ($categorieIdInt > 0) {
                // Vérifier que la catégorie existe vraiment
                if (self::categoryExists($categorieIdInt)) {
                    $shouldIncludeCategory = true;
                    $validCategorieId = $categorieIdInt;
                }
            }
        }

        if ($shouldIncludeCategory && $validCategorieId !== null) {
            $columns[] = 'categorie_id';
            $placeholders[] = '?';
            $values[] = $validCategorieId;
        }

        $sql = "INSERT INTO produit (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $stmt = $pdo->prepare($sql);

        try {
            return $stmt->execute($values);
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000 || strpos($e->getMessage(), 'foreign key') !== false || strpos($e->getMessage(), '1452') !== false) {
                if ($shouldIncludeCategory) {
                    return $this->saveWithoutCategory();
                }
            }
            throw $e;
        }
    }

    public function update()
    {
        $pdo = Database::getPDO();

        $values = [
            $this->nom,
            $this->description,
            $this->prix,
            $this->stock
        ];

        $sets = ['nom = ?', 'description = ?', 'prix = ?', 'stock = ?'];

        if (self::hasImageUrlColumn()) {
            $sets[] = 'image_url = ?';
            $values[] = $this->image_url;
        }

        $validCategorieId = null;
        if ($this->categorie_id !== null && $this->categorie_id !== '' && $this->categorie_id !== 0) {
            $categorieIdInt = (int)$this->categorie_id;
            if ($categorieIdInt > 0 && self::categoryExists($categorieIdInt)) {
                $validCategorieId = $categorieIdInt;
            }
        }

        $sets[] = 'categorie_id = ?';
        $values[] = $validCategorieId;

        $values[] = $this->id;

        try {
            $sql = "UPDATE produit SET " . implode(', ', $sets) . " WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute($values);
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000 || strpos($e->getMessage(), 'foreign key') !== false || strpos($e->getMessage(), '1452') !== false) {
                $sets = array_filter($sets, function($set) { return strpos($set, 'categorie_id') === false; });
                $values = array_slice($values, 0, -1);
                $sets[] = 'categorie_id = ?';
                $values[] = null;
                $values[] = $this->id;
                $sql = "UPDATE produit SET " . implode(', ', $sets) . " WHERE id = ?";
                $stmt = $pdo->prepare($sql);
                return $stmt->execute($values);
            }
            throw $e;
        }
    }

    public function delete()
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("DELETE FROM produit WHERE id = ?");
        return $stmt->execute([$this->id]);
    }
}

