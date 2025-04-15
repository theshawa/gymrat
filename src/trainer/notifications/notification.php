<?php
$pageConfig = [
    "title" => "Notifications",
    "styles" => ["./notifications.css"],
    "navbar_active" => 2,
    "titlebar" => [
        "title" => "Notifications",
    ],
    "need_auth" => true
];

require_once "../includes/header.php";
require_once "../includes/titlebar.php";

?>

<main>
</main>

<script>
    const container = document.querySelector("main")
    notification_listeners.add_listener((items) => {
        document.querySelector("header h3").innerText = `Notifications (${items.length})`

        container.innerHTML = "";
        items.forEach((item) => {
            const notification = document.createElement("a")
            notification.classList.add("notification")

            // Handle read status
            if (item.is_read) {
                notification.classList.add("read")
            } else {
                notification.classList.add("new")
            }

            // Handle complaint notifications specially
            const isComplaintNotification = item.title.includes("Complaint") ||
                item.message.includes("complaint");

            if (isComplaintNotification) {
                notification.classList.add("complaint-notification")
            }

            // Set proper link
            notification.href = `/trainer/notifications/notification.php?id=${item.id}`
            if (item.type === "announcement") {
                notification.href += `&type=announcement`
            }

            // Create content
            notification.innerHTML = `
                <h4>${item.title}</h4>
                <p class="paragraph truncate">${item.message}</p>
                <div class="line">
                    <span>${new Date(item.created_at).toLocaleString()}</span>
                    <span>Read More</span>
                </div>`

            container.appendChild(notification)
        })

        // Handle no notifications case
        if (items.length == 0) {
            const no_notifications = document.createElement("div")
            no_notifications.classList.add("no-notifications")
            no_notifications.innerHTML = `
                <p class="paragraph">You have no notifications</p>`
            container.appendChild(no_notifications)
        }

        // Add clear notifications button
        if (items.length > 0) {
            const notifications = items.filter(item => item.type === "notification")
            if (notifications.length) {
                const clear_notifications = document.createElement("button")

                const hasUnread = notifications.some((item) => !item.is_read)

                clear_notifications.className = `btn ${hasUnread ? "outlined" : "secondary"}`
                clear_notifications.innerText = "Clear Notifications"
                clear_notifications.onclick = () => {

                    if (hasUnread) {
                        const confirm = window.confirm("Are you sure you want to clear all notifications? There are unread notifications.")
                        if (!confirm) return
                        delete_notifications()
                    } else {
                        delete_notifications()
                    }
                }
                container.appendChild(clear_notifications)
            }
        }
    })
</script>

<style>
    /* Additional styles for complaint notifications */
    .notification.complaint-notification {
        border-left: 3px solid var(--color-violet-500);
    }

    .notification.complaint-notification.new {
        background-color: rgba(114, 0, 255, 0.1);
    }
</style>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>