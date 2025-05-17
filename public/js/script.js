// Modal functionality
function setupModal() {
    const modal = document.getElementById('confirm-modal');
    const modalTitle = document.getElementById('modal-title');
    const modalMessage = document.getElementById('modal-message');
    const modalCancel = document.getElementById('modal-cancel');
    const modalConfirm = document.getElementById('modal-confirm');
    const closeModal = document.querySelector('.close-modal');
    
    let currentModalAction = null;
    
    // Open modal
    function showModal() {
        modal.style.display = 'block';
    }
    
    // Close modal
    function closeModalFunc() {
        modal.style.display = 'none';
    }
    
    // Show confirm modal
    window.showConfirmModal = function(title, message, confirmAction) {
        modalTitle.textContent = title;
        modalMessage.textContent = message;
        currentModalAction = confirmAction;
        showModal();
    };
    
    // Modal close events
    modalCancel.addEventListener('click', closeModalFunc);
    closeModal.addEventListener('click', closeModalFunc);
    modalConfirm.addEventListener('click', function() {
        if (currentModalAction) {
            currentModalAction();
        }
        closeModalFunc();
    });
    
    // Close when clicking outside modal
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeModalFunc();
        }
    });
}

// Search functionality
function setupSearch() {
    const searchInputs = document.querySelectorAll('.search-box input');
    
    searchInputs.forEach(input => {
        input.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const table = this.closest('.content-section').querySelector('table');
            const rows = table.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    });
}

// Chart functionality
function setupCharts() {
    const teacherChart = document.getElementById('teacher-chart');
    if (!teacherChart) return;

    try {
        const ctx = teacherChart.getContext('2d');
        const facultyData = JSON.parse(teacherChart.dataset.facultyData || '{}');
        
        if (Object.keys(facultyData).length === 0) {
            console.warn('No data available for chart');
            return;
        }

        const colors = {
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)'
        };

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: Object.keys(facultyData),
                datasets: [{
                    label: 'Số giáo viên theo khoa',
                    data: Object.values(facultyData),
                    backgroundColor: colors.backgroundColor,
                    borderColor: colors.borderColor,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });
    } catch (error) {
        console.error('Error initializing chart:', error);
    }
}

// Form validation
function setupFormValidation() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('error');
                } else {
                    field.classList.remove('error');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Vui lòng điền đầy đủ thông tin bắt buộc');
            }
        });
    });
}

// Initialize all functionality
document.addEventListener('DOMContentLoaded', function() {
    setupModal();
    setupSearch();
    setupCharts();
    setupFormValidation();
}); 