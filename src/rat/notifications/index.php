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

            if (item.is_read) {
                notification.classList.add("read")
            }
            notification.href = `/rat/notifications/notification.php?id=${item.id}`
            if (item.type === "announcement") {
                notification.href += `/rat/notifications/notification.php?id=${item.id}&type=announcement`
            }
            notification.innerHTML = `
                <h4>${item.title}</h4>
                <p class="paragraph truncate">${item.message}</p>
                <div class="line">
                    <span>${new Date(item.created_at).toLocaleString()}</span>
                    <span>Read More</span>
                </div>`
            container.appendChild(notification)
        })
        if (items.length == 0) {
            const no_notifications = document.createElement("div")
            no_notifications.classList.add("no-notifications")
            no_notifications.innerHTML = `
                <p class="paragraph">You have no notifications</p>`
            container.appendChild(no_notifications)
        }
        if (items.length > 0) {
            const clear_notifications = document.createElement("button")
            const hasUnread = items.some((item) => !item.is_read)

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
    })
</script>

<?php require_once "../includes/navbar.php" ?>
<?php require_once "../includes/footer.php" ?>