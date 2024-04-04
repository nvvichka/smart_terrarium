self.addEventListener('push', function(event) {
    var options = {
        body: event.data.text(),
        icon: 'icon.png', // You can customize the notification icon
        badge: 'badge.png' // You can customize the badge for the notification
    };

    event.waitUntil(
        self.registration.showNotification('Push Notification', options)
    );
});