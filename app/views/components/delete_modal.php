<div id="delete-modal" class="modal" style="display: none;">
    <div class="modal-backdrop"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3>Confirmación</h3>
            <button class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <p>¿Estás Seguro?</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-cancel">Cancelar</button>
            <form id="delete-form" method="POST" style="display: inline;">
                <input type="hidden" name="id_menu" id="delete-id">
                <button type="submit" class="btn btn-accept">Aceptar</button>
            </form>
        </div>
    </div>
</div>

<style>
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1000;
    display: none;
}

.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: white;
    border-radius: 8px;
    min-width: 300px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    background-color: #002855;
    color: white;
}

.modal-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: bold;
}

.modal-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: white;
    padding: 0;
    line-height: 1;
}

.modal-body {
    padding: 30px 20px;
    text-align: center;
}

.modal-body p {
    margin: 0;
    font-size: 16px;
    color: #333;
}

.modal-footer {
    display: flex;
    justify-content: space-between;
    padding: 15px 20px;
    background-color: #f8f8f8;
    border-top: 1px solid #ddd;
}

.modal-footer .btn {
    padding: 8px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
    min-width: 100px;
}

.modal-footer .btn-cancel {
    background-color: #dc3545;
    color: white;
}

.modal-footer .btn-cancel:hover {
    background-color: #c82333;
}

.modal-footer .btn-accept {
    background-color: #28a745;
    color: white;
}

.modal-footer .btn-accept:hover {
    background-color: #218838;
}
</style> 