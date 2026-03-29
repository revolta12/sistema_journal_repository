// Auto-hide alerts after 5 seconds
$(document).ready(function() {
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
    
    // Search form validation
    $('form').on('submit', function(e) {
        let emptyFields = $(this).find('input[required], textarea[required]').filter(function() {
            return $(this).val() === '';
        });
        
        if (emptyFields.length > 0) {
            e.preventDefault();
            emptyFields.first().focus();
            showNotification('Silakan isi semua field yang diperlukan', 'warning');
        }
    });
    
    // File upload preview
    $('#file').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        if (fileName) {
            $(this).after('<small class="text-success">File selected: ' + fileName + '</small>');
            $(this).next('small').prev().remove();
        }
    });
    
    // Search with debounce
    let searchTimeout;
    $('.search-input').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            // Perform search
        }, 500);
    });
});

function showNotification(message, type = 'info') {
    let alertClass = 'alert-info';
    if (type === 'success') alertClass = 'alert-success';
    if (type === 'error') alertClass = 'alert-danger';
    if (type === 'warning') alertClass = 'alert-warning';
    
    let alert = `<div class="alert ${alertClass} alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index: 9999;">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                 </div>`;
    
    $('body').append(alert);
    setTimeout(function() {
        $('.alert').fadeOut('slow', function() {
            $(this).remove();
        });
    }, 3000);
}

// Confirm delete
function confirmDelete(url) {
    if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
        window.location.href = url;
    }
    return false;
}

// Copy to clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        showNotification('Berhasil disalin ke clipboard!', 'success');
    }, function() {
        showNotification('Gagal menyalin', 'error');
    });
}