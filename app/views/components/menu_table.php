<?php
require_once __DIR__ . '/../../models/Menu.php';
$menuModel = new Menu();
$allMenus = $menuModel->getAllMenus(); 
require_once __DIR__ . '/delete_modal.php';
?>
<!-- Table view -->
<div id="menus-table-view" class="table-container">
    <div class="table-header">
        <div class="table-title">Registros</div>
        <div class="table-actions">
            <button class="btn btn-add" id="btn-new-menu" onclick="showCreateForm()">+ Nuevo</button>
        </div>
    </div>
    
    <div class="table-body">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Padre</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="menu-table-body">
                <?php if (count($allMenus) > 0): ?>
                    <?php foreach($allMenus as $menu): 
                        $hasChildren = $menuModel->hasChildren($menu['id_menu']);
                    ?>
                            <tr class="menu-row" style="display: none;">
                                <td><?= $menu['id_menu'] ?></td>
                                <td><?= $menu['name'] ?></td>
                                <td><?= $menuModel->getMenuNameById($menu['id_parent']) ?: '-' ?></td>
                                <td><?= substr($menu['description'], 0, 50) . (strlen($menu['description']) > 50 ? '...' : '') ?></td>
                                <td class="actions-cell">
                                    <div class="actions">
                                        <a href="javascript:void(0)" onclick="showEditForm(<?= $menu['id_menu'] ?>)" style="text-decoration: none; height: 28px;" class="btn-edit">Editar</a>
                                        <button type="button" class="btn-delete" style="height: 28px;" onclick="<?= $hasChildren ? 'showDeleteRestriction()' : 'openDeleteModal(' . $menu['id_menu'] . ', \'/dportenis/app/controllers/menu_actions.php?action=delete\')' ?>">Eliminar</button>
                                    </div>
                                </td>
                            </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="no-data">No data available. Add some records!</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <div class="paginator">
        <div class="paginator-info" id="paginator-info">Showing 0-0 of 0 entries</div>
        <div class="paginator-buttons">
            <button class="paginator-button" id="prev-button" onclick="changePage('prev')">Previous</button>
            <div id="page-numbers">
            </div>
            <button class="paginator-button" id="next-button" onclick="changePage('next')">Next</button>
        </div>
    </div>
</div>

<!-- Notification for delete restriction -->
<div id="delete-restriction-notification" class="notification" style="display: none;">
    No se puede eliminar porque tiene elementos hijos
</div>

<style>
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: #dc3545;
    color: white;
    padding: 15px 25px;
    border-radius: 4px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    z-index: 1000;
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

#page-numbers {
    display: inline-flex;
    gap: 5px;
}

.page-number {
    padding: 6px 12px;
    border: 1px solid #ddd;
    background-color: #fff;
    color: #333;
    cursor: pointer;
    border-radius: 3px;
    font-size: 14px;
}

.page-number.active {
    background-color: #007bff;
    color: #fff;
    border-color: #007bff;
}
</style> 