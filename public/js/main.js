document.addEventListener('DOMContentLoaded', function() {
    // Initialize all functionality
    initNavigation();
    initMenuManagement();
    initDeleteModal();
    initPagination();
});

// Navigation handling
function initNavigation() {
    const navLinks = document.querySelectorAll('.nav-link');
    const contentSections = document.querySelectorAll('.content-section');

    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all links
            navLinks.forEach(l => l.classList.remove('active'));
            
            // Add active class to clicked link
            this.classList.add('active');
            
            // Hide all content sections
            contentSections.forEach(section => section.style.display = 'none');
            
            // Show the corresponding content section
            const targetId = this.getAttribute('href').substring(1);
            const targetSection = document.getElementById(targetId);
            if (targetSection) {
                targetSection.style.display = 'block';
            }
        });
    });
}

// Menu management
function initMenuManagement() {
    // Create form handling
    const createForm = document.getElementById('menu-create-form');
    if (createForm) {
        createForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (validateForm(this)) {
                this.submit();
            }
        });
    }

    // Edit form handling
    const editForm = document.getElementById('menu-edit-form');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (validateForm(this)) {
                this.submit();
            }
        });
    }
}

// Form validation
function validateForm(form) {
    const nameInput = form.querySelector('input[name="name"]');
    const descriptionInput = form.querySelector('textarea[name="description"]');
    
    if (!nameInput.value.trim()) {
        showNotification('El nombre es requerido', 'error');
        return false;
    }
    
    if (!descriptionInput.value.trim()) {
        showNotification('La descripción es requerida', 'error');
        return false;
    }
    
    return true;
}

// Show create form
function showCreateForm() {
    const createForm = document.getElementById('menus-create-form');
    const tableView = document.getElementById('menus-table-view');
    if (createForm && tableView) {
        createForm.style.display = 'block';
        tableView.style.display = 'none';
    }
}

// Hide create form
function hideCreateForm() {
    const createForm = document.getElementById('menus-create-form');
    const tableView = document.getElementById('menus-table-view');
    if (createForm && tableView) {
        createForm.style.display = 'none';
        tableView.style.display = 'block';
        createForm.reset();
    }
}

// Show edit form
function showEditForm(menuId) {
    const editForm = document.getElementById('menus-edit-form');
    const tableView = document.getElementById('menus-table-view');
    
    if (!editForm || !tableView) return;
    
    // Show edit form and hide table
    editForm.style.display = 'block';
    tableView.style.display = 'none';
    
    // Fetch menu data
    fetch(`/dportenis/app/controllers/get_menu.php?id=${menuId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                document.getElementById('edit-id-menu').value = data.menu.id_menu;
                document.getElementById('edit-menu-name').value = data.menu.name;
                document.getElementById('edit-menu-description').value = data.menu.description;
                
                // Handle parent menu selection
                const parentSelect = document.getElementById('edit-menu-parent');
                if (parentSelect) {
                    // Fetch and populate parent menus
                    fetch('/dportenis/app/controllers/get_parent_menus.php')
                        .then(response => response.json())
                        .then(parentMenus => {
                            while (parentSelect.options.length > 1) {
                                parentSelect.remove(1);
                            }
                            
                            
                            parentMenus.forEach(menu => {
                                if (menu.id_menu !== data.menu.id_menu) {
                                    const option = document.createElement('option');
                                    option.value = menu.id_menu;
                                    option.textContent = menu.name;
                                    if (menu.id_menu === data.menu.id_parent) {
                                        option.selected = true;
                                    }
                                    parentSelect.appendChild(option);
                                }
                            });
                            
                            
                            const parentWarning = document.getElementById('edit-parent-warning');
                            if (parentWarning) {
                                parentWarning.style.display = data.menu.has_children ? 'block' : 'none';
                                if (data.menu.has_children) {
                                    parentSelect.disabled = true;
                                } else {
                                    parentSelect.disabled = false;
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching parent menus:', error);
                            showNotification('Error al cargar los menús padre', 'error');
                        });
                }
            } else {
                showNotification(data.message || 'Error al cargar los datos del menú', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error al cargar los datos del menú', 'error');
        });
}

// Hide edit form
function hideEditForm() {
    const editForm = document.getElementById('menus-edit-form');
    const tableView = document.getElementById('menus-table-view');
    if (editForm && tableView) {
        editForm.style.display = 'none';
        tableView.style.display = 'block';
        editForm.reset();
    }
}

// Delete modal handling
function initDeleteModal() {
    const modal = document.getElementById('delete-modal');
    const closeBtn = modal.querySelector('.modal-close');
    const cancelBtn = modal.querySelector('.btn-cancel');
    const backdrop = modal.querySelector('.modal-backdrop');
    
    const closeModal = () => {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    };
    
    // Event listeners for closing modal
    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
    if (backdrop) backdrop.addEventListener('click', closeModal);
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.style.display === 'block') {
            closeModal();
        }
    });
}

// Open delete modal
function openDeleteModal(id, action) {
    const modal = document.getElementById('delete-modal');
    const form = document.getElementById('delete-form');
    const deleteIdInput = document.getElementById('delete-id');
    
    if (modal && form && deleteIdInput) {
        deleteIdInput.value = id;
        form.action = action;
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
}

// Show notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.display = 'block';
    }, 100);
    
    setTimeout(() => {
        notification.style.display = 'none';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

function showDeleteRestriction() {
    showNotification('No se puede eliminar porque tiene elementos hijos', 'error');
}

// Pagination handling
function initPagination() {
    const rowsPerPage = 7;
    const rows = document.querySelectorAll('.menu-row');
    const totalRows = rows.length;
    const totalPages = Math.ceil(totalRows / rowsPerPage);
    let currentPage = 1;

    function updatePagination() {
        // Update table rows visibility
        rows.forEach((row, index) => {
            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            row.style.display = (index >= start && index < end) ? '' : 'none';
        });

        // Update paginator info
        const start = (currentPage - 1) * rowsPerPage + 1;
        const end = Math.min(currentPage * rowsPerPage, totalRows);
        document.getElementById('paginator-info').textContent = 
            `Showing ${totalRows ? start : 0}-${end} of ${totalRows} entries`;

        // Update page numbers
        const pageNumbers = document.getElementById('page-numbers');
        pageNumbers.innerHTML = '';
        
        for (let i = 1; i <= totalPages; i++) {
            const pageButton = document.createElement('button');
            pageButton.className = 'page-number' + (i === currentPage ? ' active' : '');
            pageButton.textContent = i;
            pageButton.onclick = () => {
                currentPage = i;
                updatePagination();
            };
            pageNumbers.appendChild(pageButton);
        }

        document.getElementById('prev-button').disabled = currentPage === 1;
        document.getElementById('next-button').disabled = currentPage === totalPages;
    }


    updatePagination();
}

function changePage(direction) {
    const rows = document.querySelectorAll('.menu-row');
    const totalRows = rows.length;
    const totalPages = Math.ceil(totalRows / 7);
    let currentPage = parseInt(document.querySelector('.page-number.active')?.textContent || 1);

    if (direction === 'prev' && currentPage > 1) {
        currentPage--;
    } else if (direction === 'next' && currentPage < totalPages) {
        currentPage++;
    }

    // Trigger click on the new page number
    const pageButtons = document.querySelectorAll('.page-number');
    pageButtons[currentPage - 1]?.click();
} 