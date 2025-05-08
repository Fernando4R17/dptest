<?php
require_once __DIR__ . '/../../models/Menu.php';
$menuModel = new Menu();
$parentMenus = $menuModel->getAllParentMenus();
$isEditing = false;
$menuData = null;
$hasChildren = false;
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
    $menuData = $menuModel->getMenuById($_GET['edit']);
    $isEditing = !empty($menuData);
    if ($isEditing) {
        $hasChildren = $menuModel->hasChildren($menuData['id_menu']);
    }
}
?>
<!-- Create Form view -->
<div id="menus-create-form" class="form-container" style="display: none;">
    <div class="form-header">
        <div class="form-title">Nuevo Menú</div>
    </div>
    
    <div class="form-body">
        <form id="menu-create-form" action="/dportenis/app/controllers/menu_actions.php?action=create" method="POST">
            <div class="form-group">
                <label for="menu-name">Nombre</label>
                <input type="text" id="menu-name" name="name" class="form-control" 
                       placeholder="Ingrese el nombre del menú" required>
            </div>
            
            <div class="form-group">
                <label for="menu-parent">Menú padre</label>
                <select id="menu-parent" name="id_parent" class="form-control">
                    <option value="">Sin menú padre</option>
                    <?php foreach($parentMenus as $menu): ?>
                        <option value="<?= $menu['id_menu'] ?>">
                            <?= htmlspecialchars($menu['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group description-group">
                <label for="menu-description">Descripción</label>
                <textarea id="menu-description" name="description" class="form-control description-textarea" 
                          placeholder="Ingrese una descripción del menú" required></textarea>
            </div>

            <div class="form-footer">
                <button type="button" class="btn btn-cancel" onclick="hideCreateForm()">X Cancelar</button>
                <button type="submit" class="btn btn-save">+ Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Form view -->
<div id="menus-edit-form" class="form-container" style="display: none;">
    <div class="form-header">
        <div class="form-title">Editar Menú</div>
    </div>
    
    <div class="form-body">
        <form id="menu-edit-form" action="/dportenis/app/controllers/menu_actions.php?action=update" method="POST">
            <input type="hidden" name="id_menu" id="edit-id-menu">
            
            <div class="form-group">
                <label for="edit-menu-name">Nombre</label>
                <input type="text" id="edit-menu-name" name="name" class="form-control" 
                       placeholder="Ingrese el nombre del menú" required>
            </div>
            
            <div class="form-group">
                <label for="edit-menu-parent">Menú padre</label>
                <select id="edit-menu-parent" name="id_parent" class="form-control">
                    <option value="">Sin menú padre</option>
                    <?php foreach($parentMenus as $menu): ?>
                        <?php if (!$isEditing || $menu['id_menu'] != $menuData['id_menu']): ?>
                            <option value="<?= $menu['id_menu'] ?>">
                                <?= htmlspecialchars($menu['name']) ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
                <small id="edit-parent-warning" class="form-text text-muted" style="display: none;">
                    No se puede cambiar el menú padre porque tiene elementos hijos.
                </small>
            </div>
            
            <div class="form-group description-group">
                <label for="edit-menu-description">Descripción</label>
                <textarea id="edit-menu-description" name="description" class="form-control description-textarea" 
                          placeholder="Ingrese una descripción del menú" required></textarea>
            </div>

            <div class="form-footer">
                <button type="button" class="btn btn-cancel" onclick="hideEditForm()">X Cancelar</button>
                <button type="submit" class="btn btn-save">Actualizar</button>
            </div>
        </form>
    </div>
</div> 