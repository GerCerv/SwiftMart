<div class="upper-navbar" id="upperNavbar">
    <div class="d-flex justify-content-between align-items-center">
        <!-- Upper Left Links -->
        <div class="d-flex align-items-center ms-3">
            <!-- <div class="d-none d-lg-flex align-items-center me-1">
                <div class="form-check form-switch">
                    <input style="margin-left:5px;" class="form-check-input" type="checkbox" id="darkModeSwitch">
                    <span class="me-1" style="margin-left:1px;">🌙</span>
                </div>
            </div> -->
            <!-- Language Dropdown -->
            <div class="dropdown me-3">
                <button style="text-decoration: none;" class="btn btn-link text-success dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-globe"></i> <span class="d-none d-lg-inline">EN </span>
                </button>
                <ul class="dropdown-menu" style="z-index: 1050;" aria-labelledby="languageDropdown">
                    <li><a class="dropdown-item" style="text-decoration: none;" href="#">EN - English</a></li>
                    <li><a class="dropdown-item" style="text-decoration: none;" href="#">PH - Tagalog</a></li>
                    <li><a class="dropdown-item" style="text-decoration: none;" href="#">JP - Japanese</a></li>
                </ul>
            </div>
            <a href="#" class="text-success me-3"><i class="fa fa-phone" style="margin-left:10px;"></i> <span class="d-none d-lg-inline">: 09XX-XXXX-XXX</span></a>
        </div>

        <!-- Upper Right Links for Login & Sign Up -->
        @auth
        <div class="d-flex align-items-center me-3 position-relative">
            <a href="#" class="text-success me-3 notification-toggle" data-popup="notification-popup">
                <i class="fa fa-envelope" aria-hidden="true"></i> 
                <span class="d-none d-lg-inline"> Notification</span>
                <span id="notification-count" class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle-x" style="font-size: 0.6rem; display: none;">0</span>
            </a>
            <!-- Notification Popup -->
            <div id="notification-popup" class="popup" style="display: none; position: absolute; top: 40px; right: 0; width: 300px; background: white; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.2); z-index: 1050;">
                <div class="popup-header p-3 border-bottom">
                    <h5 class="m-0">Notifications <span id="notification-count-header" class="badge bg-success ms-2">0</span></h5>
                </div>
                <div id="notification-list" class="popup-body p-3" style="max-height: 300px; overflow-y: auto;">
                    <p class="text-center text-muted">No notifications available</p>
                </div>
                <div class="popup-footer p-3 border-top text-center">
                    <a href="#" class="text-success text-decoration-none">View All Notifications</a>
                </div>
            </div>
        </div>
        @else
        <div class="d-flex align-items-center me-3">
            <a href="#" class="text-success me-3" data-bs-toggle="modal" data-bs-target="#loginModal">
                <i class="fa fa-sign-in"></i> <span class="d-none d-lg-inline"> Login</span>
            </a>
            <a href="#" class="text-success me-3" data-bs-toggle="modal" data-bs-target="#signupModal">
                <i class="fa fa-user-plus"></i> <span class="d-none d-lg-inline"> Sign Up</span>
            </a>
        </div>
        @endauth
    </div>
</div>

<!-- JavaScript for Notification Popup -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const notificationToggle = document.querySelector('.notification-toggle');
    const notificationPopup = document.getElementById('notification-popup');
    const notificationList = document.getElementById('notification-list');
    const notificationCount = document.getElementById('notification-count');
    const notificationCountHeader = document.getElementById('notification-count-header');

    // Function to fetch notifications
    function fetchNotifications() {
        fetch('{{ route("notifications.index") }}', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Update notification count
            const count = data.count;
            notificationCount.textContent = count;
            notificationCountHeader.textContent = count;
            notificationCount.style.display = count > 0 ? 'inline-block' : 'none';

            // Update notification list
            if (count === 0) {
                notificationList.innerHTML = '<p class="text-center text-muted">No notifications available</p>';
            } else {
                notificationList.innerHTML = '';
                data.notifications.forEach(notification => {
                    const notificationItem = document.createElement('div');
                    notificationItem.className = 'd-flex align-items-center mb-2 p-2 border-bottom';
                    notificationItem.innerHTML = `
                        <div class="flex-grow-1">
                            <p class="mb-1"><strong>${notification.product_name}</strong></p>
                            <p class="mb-0 text-muted">Status: ${notification.status}</p>
                            <small class="text-muted">${notification.updated_at}</small>
                        </div>
                    `;
                    notificationList.appendChild(notificationItem);
                });
            }
        })
        .catch(error => {
            console.error('Error fetching notifications:', error);
            notificationList.innerHTML = '<p class="text-center text-danger">Error loading notifications</p>';
        });
    }

    // Toggle popup visibility
    if (notificationToggle && notificationPopup) {
        notificationToggle.addEventListener('click', function(e) {
            e.preventDefault();
            const isVisible = notificationPopup.style.display === 'block';
            document.querySelectorAll('.popup').forEach(popup => {
                popup.style.display = 'none';
            });
            notificationPopup.style.display = isVisible ? 'none' : 'block';
            if (!isVisible) {
                fetchNotifications();
            }
        });

        // Close popup when clicking outside
        document.addEventListener('click', function(e) {
            if (!notificationPopup.contains(e.target) && !notificationToggle.contains(e.target)) {
                notificationPopup.style.display = 'none';
            }
        });
    }

    // Initial fetch on page load
    @auth
        fetchNotifications();
    @endauth
});
</script>

<style>
.popup {
    animation: slideDown 0.3s ease-out;
}
.popup-header {
    background: #f8f9fa;
    border-radius: 8px 8px 0 0;
}
.popup-body {
    background: #ffffff;
}
.popup-footer {
    background: #f8f9fa;
    border-radius: 0 0 8px 8px;
}
@keyframes slideDown {
    from { transform: translateY(-10px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
</style>