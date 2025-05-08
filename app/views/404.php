<?php
$title = '404 - Pagina no encontrada';
ob_start();
?>

<div class="error-section">
    <h2>404 - Pagina no encontrada</h2>
    <p>La pagina que estas buscando no existe.</p>
    <div class="actions">
        <a href="/dportenis/">Volver a la pagina principal</a>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/layouts/main.php';
?> 