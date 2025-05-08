<?php
require_once __DIR__ . '/../../models/Menu.php';
$menuModel = new Menu();
$menuHierarchy = $menuModel->getMenuHierarchy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'CRUD App' ?></title>
    <link rel="stylesheet" href="/dportenis/public/css/style.css">
    <script src="/dportenis/public/js/main.js"></script>
</head>
<body>
    <div class="container">
        <div class="content-wrapper">
            <div class="sidebar">
                <h3>dportenis</h3>
                
                <div class="sidebar-content">
                    <div class="sidebar-section">
                        <div class="section-title">Menú</div>
                        <ul>
                            <?php if (count($menuHierarchy) > 0): ?>
                                <?php 
                                function renderMenuItems($menuItems, $level = 0) {
                                    foreach ($menuItems as $menu) {
                                        $target = strtolower(str_replace(' ', '', $menu['name']));
                                        $indentClass = $level > 0 ? 'menu-child level-' . $level : '';
                                        ?>
                                        <li class="<?= $indentClass ?>">
                                            <a href="#<?= $target ?>" 
                                               class="nav-link <?= $level === 0 && $menu['name'] === 'Home' ? 'active' : '' ?>" 
                                               data-target="<?= $target ?>">
                                                <?= $menu['name'] ?>
                                            </a>
                                        </li>
                                        <?php
                                        if (!empty($menu['children'])) {
                                            renderMenuItems($menu['children'], $level + 1);
                                        }
                                    }
                                }
                                renderMenuItems($menuHierarchy);
                                ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                
                <div class="sidebar-footer">
                    <div class="sidebar-section admin-section">
                        <div class="section-title">Administración</div>
                        <ul>
                            <li><a href="#menus" class="nav-link" data-target="menus">Menús</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <main>
                <div id="dashboard" class="content-section active">
                    <div class="welcome-message">
                        <h2>Bienvenido a la Evaluación</h2>
                        <p>Selecciona una opción del menú para comenzar.</p>
                    </div>
                </div>

                <?php if (count($menuHierarchy) > 0): ?>
                    <?php 
                    function renderContentSections($menuItems, $level = 0) {
                        foreach ($menuItems as $menu) {
                            $target = strtolower(str_replace(' ', '', $menu['name']));
                            ?>
                            <div id="<?= $target ?>" class="content-section <?= $level === 0 && $menu['name'] === 'Home' ? 'active' : '' ?>">
                                <h2><?= $menu['name'] ?></h2>
                                <p><?= $menu['description'] ?></p>
                            </div>
                            <?php
                            if (!empty($menu['children'])) {
                                renderContentSections($menu['children'], $level + 1);
                            }
                        }
                    }
                    renderContentSections($menuHierarchy);
                    ?>
                <?php endif; ?>
                
                <div id="menus" class="content-section">
                    <?php include __DIR__ . '/../components/menu_table.php'; ?>
                    <?php include __DIR__ . '/../components/menu_form.php'; ?>
                </div>
            </main>
        </div>
    </div>
</body>
</html> 