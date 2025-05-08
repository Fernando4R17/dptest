<?php

require_once __DIR__ . '/../database/Database.php';

class Menu {
    private $db;
    private $table = 'menus';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Get all menus
     */
    public function getAllMenus() {
        $sql = "SELECT * FROM menus WHERE status = 1 ORDER BY id_menu";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get menu by ID
     */
    public function getMenuById($id) {
        $stmt = $this->db->prepare("SELECT id_menu, id_parent, status, name, description FROM {$this->table} WHERE id_menu = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Create a new menu
     */
    public function createMenu($data) {
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (id_parent, status, name, description) VALUES (:id_parent, :status, :name, :description)");
        
        $stmt->bindParam(':id_parent', $data['id_parent'], $data['id_parent'] ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $stmt->bindParam(':status', $data['status'], PDO::PARAM_INT);
        $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
        $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }

    /**
     * Update an existing menu
     */
    public function updateMenu($id, $data) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET id_parent = :id_parent, status = :status, name = :name, description = :description WHERE id_menu = :id");
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':id_parent', $data['id_parent'], $data['id_parent'] ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $stmt->bindParam(':status', $data['status'], PDO::PARAM_INT);
        $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR);
        $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
        
        return $stmt->execute();
    }

    /**
     * Delete a menu
     */
    public function deleteMenu($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id_menu = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Get all parent menus for dropdown selection
     */
    public function getAllParentMenus() {
        $stmt = $this->db->prepare("SELECT id_menu, name FROM {$this->table} WHERE status = 1 AND id_parent IS NULL");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Get menu name by ID
     */
    public function getMenuNameById($id) {
        if (!$id) return null;
        
        $stmt = $this->db->prepare("SELECT name FROM {$this->table} WHERE id_menu = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        
        return $result ? $result['name'] : null;
    }

    /**
     * Get menu items in hierarchical structure
     */
    public function getMenuHierarchy() {
        $allMenus = $this->getAllMenus();
        $menuMap = [];
        $rootMenus = [];
        
        foreach ($allMenus as $menu) {
            $menuMap[$menu['id_menu']] = $menu;
            $menuMap[$menu['id_menu']]['children'] = [];
        }
        
        foreach ($allMenus as $menu) {
            $id = $menu['id_menu'];
            $parentId = $menu['id_parent'];
            
            if ($parentId === null) {
                $rootMenus[] = &$menuMap[$id];
            } else {
                if (isset($menuMap[$parentId])) {
                    $menuMap[$parentId]['children'][] = &$menuMap[$id];
                }
            }
        }
        
        return $rootMenus;
    }

    public function updateStatus($id_menu, $status) {
        $sql = "UPDATE menus SET status = ? WHERE id_menu = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$status, $id_menu]);
    }

    /**
     * Check if a menu has children
     */
    public function hasChildren($id_menu) {
        $sql = "SELECT COUNT(*) as count FROM menus WHERE id_parent = :id_menu AND status = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_menu', $id_menu, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }
} 