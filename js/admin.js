// Toggle sidebar on mobile
const toggleSidebar = () => {
    const sidebar = document.querySelector('.sidebar');
    if (window.innerWidth <= 768) {
        sidebar.classList.toggle('active');
    }
};

// Create mobile menu toggle button
const createMobileToggle = () => {
    const header = document.querySelector('.content-header');
    if (header && window.innerWidth <= 768) {
        const toggleBtn = document.createElement('button');
        toggleBtn.className = 'mobile-toggle';
        toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
        toggleBtn.style.cssText = `
            background: var(--primary);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 1rem;
        `;
        toggleBtn.addEventListener('click', toggleSidebar);
        header.insertBefore(toggleBtn, header.firstChild);
    }
};

// Auto-hide alerts after 3 seconds
const alerts = document.querySelectorAll('.alert');
alerts.forEach(alert => {
    setTimeout(() => {
        alert.style.opacity = '0';
        setTimeout(() => {
            alert.style.display = 'none';
        }, 300);
    }, 3000);
});

// Confirm delete function (fallback)
window.deleteProject = function(id) {
    if (confirm('Apakah Anda yakin ingin menghapus project ini?')) {
        window.location.href = `delete-project.php?id=${id}`;
    }
};

// Form validation for add/edit project
const projectForm = document.querySelector('.project-form');
if (projectForm) {
    projectForm.addEventListener('submit', (e) => {
        const requiredFields = projectForm.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.style.borderColor = '#d63031';
                isValid = false;
                
                // Add or update error message
                let errorMsg = field.parentNode.querySelector('.error-message');
                if (!errorMsg) {
                    errorMsg = document.createElement('small');
                    errorMsg.className = 'error-message';
                    errorMsg.style.color = '#d63031';
                    errorMsg.style.display = 'block';
                    errorMsg.style.marginTop = '5px';
                    errorMsg.textContent = 'Field ini wajib diisi';
                    field.parentNode.appendChild(errorMsg);
                }
            } else {
                field.style.borderColor = '#00b894';
                const errorMsg = field.parentNode.querySelector('.error-message');
                if (errorMsg) {
                    errorMsg.remove();
                }
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang wajib diisi!');
        }
    });
}

// Preview image URL
const imageUrlInput = document.getElementById('image_url');
if (imageUrlInput) {
    imageUrlInput.addEventListener('input', (e) => {
        const url = e.target.value;
        const previewContainer = document.querySelector('.image-preview');
        
        if (previewContainer) {
            const img = previewContainer.querySelector('img');
            if (img) {
                if (url) {
                    img.src = url;
                    previewContainer.style.display = 'block';
                } else {
                    previewContainer.style.display = 'none';
                }
            }
        }
    });
}

// Auto-resize textarea
const textareas = document.querySelectorAll('textarea');
textareas.forEach(textarea => {
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
});

// Search functionality in projects table
const searchInput = document.getElementById('search-projects');
if (searchInput) {
    searchInput.addEventListener('input', (e) => {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.projects-table tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
}

// Export table to CSV
const exportBtn = document.getElementById('export-csv');
if (exportBtn) {
    exportBtn.addEventListener('click', () => {
        const table = document.querySelector('.projects-table');
        const rows = table.querySelectorAll('tr');
        const csv = [];

        rows.forEach(row => {
            const cells = row.querySelectorAll('th, td');
            const rowData = [];
            cells.forEach(cell => {
                // Remove HTML tags and trim
                let text = cell.textContent.trim();
                // Escape commas
                if (text.includes(',')) {
                    text = `"${text}"`;
                }
                rowData.push(text);
            });
            csv.push(rowData.join(','));
        });

        const csvContent = csv.join('\n');
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'projects.csv');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(url);
    });
}

// Notification system
const showNotification = (message, type = 'success') => {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
        <span>${message}</span>
    `;
    
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        background: ${type === 'success' ? '#00b894' : '#d63031'};
        color: white;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        z-index: 9999;
        transform: translateX(400px);
        transition: transform 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    setTimeout(() => {
        notification.style.transform = 'translateX(400px)';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
};

// Handle session messages
const sessionMessage = document.querySelector('.session-message');
if (sessionMessage) {
    const message = sessionMessage.dataset.message;
    const type = sessionMessage.dataset.type;
    showNotification(message, type);
}

// Initialize on load
document.addEventListener('DOMContentLoaded', () => {
    createMobileToggle();
    
    // Add active class to current nav item
    const currentLocation = window.location.pathname;
    const navLinks = document.querySelectorAll('.sidebar-nav a');
    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentLocation.split('/').pop()) {
            link.classList.add('active');
        }
    });
});

// Resize handler
window.addEventListener('resize', () => {
    if (window.innerWidth > 768) {
        const sidebar = document.querySelector('.sidebar');
        if (sidebar) {
            sidebar.classList.remove('active');
        }
        const mobileToggle = document.querySelector('.mobile-toggle');
        if (mobileToggle) {
            mobileToggle.remove();
            createMobileToggle();
        }
    }
});

// Keyboard shortcuts
document.addEventListener('keydown', (e) => {
    // Ctrl + S to save form
    if (e.ctrlKey && e.key === 's') {
        e.preventDefault();
        const submitBtn = document.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.click();
        }
    }
    
    // Esc to close mobile sidebar
    if (e.key === 'Escape') {
        const sidebar = document.querySelector('.sidebar');
        if (sidebar && sidebar.classList.contains('active')) {
            sidebar.classList.remove('active');
        }
    }
});