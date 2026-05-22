<script>
    // Sidebar toggle for mobile
    $('#sidebarToggle').click(function() {
        $('#sidebar').toggleClass('show');
    });
    
    // Update current time
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', { 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit' 
        });
        $('#current-time').text(timeString);
    }
    updateTime();
    setInterval(updateTime, 1000);
    
    // Auto-refresh pending intents count
    function refreshPendingIntents() {
        $.get('/api/purchase-intents?status=pending&count_only=1', function(data) {
            if (data.count > 0) {
                $('#pending-intents-badge').text(data.count).show();
            } else {
                $('#pending-intents-badge').hide();
            }
        }).fail(function() {
            $('#pending-intents-badge').hide();
        });
    }
    
    setInterval(refreshPendingIntents, 30000);
    refreshPendingIntents();
    
    // Active link highlight
    $(document).ready(function() {
        const currentUrl = window.location.pathname;
        $('.sidebar .nav-link').each(function() {
            const href = $(this).attr('href');
            if (href && currentUrl === href) {
                $(this).addClass('active');
            } else if (href && currentUrl.startsWith(href) && href !== '/') {
                $(this).addClass('active');
            }
        });
        
        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
        
        // Add fade-in animation to main content
        $('.main-content').addClass('fade-in');
    });
    
    // Database status check
    function checkDatabaseStatus() {
        $.get('/api/db-status', function(data) {
            $('#db-status').text(data.status).removeClass('text-danger text-success')
                .addClass(data.status === 'Connected' ? 'text-success' : 'text-danger');
        }).fail(function() {
            $('#db-status').text('Disconnected').addClass('text-danger');
        });
    }
    
    setInterval(checkDatabaseStatus, 60000);
    
    // Confirm delete
    window.confirmDelete = function(message, url) {
        if (confirm(message || 'Are you sure you want to delete this item?')) {
            window.location.href = url;
        }
        return false;
    };
    
    // Show loading overlay
    window.showLoading = function() {
        if ($('#loading-overlay').length === 0) {
            $('body').append('<div id="loading-overlay" class="loading-overlay"><div class="spinner-border text-primary" role="status"></div></div>');
        } else {
            $('#loading-overlay').show();
        }
    };
    
    // Hide loading overlay
    window.hideLoading = function() {
        $('#loading-overlay').hide();
    };
    
    // Form submit with loading
    $('form').on('submit', function() {
        showLoading();
    });
</script>