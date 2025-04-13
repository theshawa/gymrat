const notifications = {
  items: [],
  unread_count: 0,
};

let when_notifications_update = () => {};

const set_notifications = (data) => {
  notifications.items = data;
  notifications.unread_count = data.filter(
    (notification) => !notification.is_read
  ).length;
  when_notifications_update();
};

const fetch_notifications = async () => {
  const res = await fetch("/notifications/api.php", {
    method: "GET",
  });
  const { success, data } = await res.json();
  if (!success) {
    alert("Error fetching notifications: " + data.message);
    return;
  }
  set_notifications(data);
};

const delete_notifications = async () => {
  const res = await fetch("/notifications/api.php", {
    method: "DELETE",
  });
  const { success, data } = await res.json();
  if (!success) {
    alert("Error deleting notifications: " + data.message);
    return;
  }
  set_notifications([]);
};

let interval;
window.addEventListener("DOMContentLoaded", () => {
  interval = setInterval(() => {
    fetch_notifications();
  }, 5000);
  fetch_notifications();
});

window.addEventListener("beforeunload", () => {
  clearInterval(interval);
});
