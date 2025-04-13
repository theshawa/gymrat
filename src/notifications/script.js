const notification_listeners = {
  listners: [],
  add_listener: function (listner) {
    this.listners.push(listner);
  },
};

const fetch_notifications = async () => {
  const res = await fetch("/notifications/api.php", {
    method: "GET",
  });
  const { success, data } = await res.json();

  if (!success) {
    alert("Error fetching notifications: " + data);
    return;
  }

  const unread_count = data.filter(
    (notification) => !notification.is_read
  ).length;
  notification_listeners.listners.forEach((listner) => {
    listner(data, unread_count);
  });
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

  notification_listeners.listners.forEach((listner) => {
    listner([], 0);
  });
};
let interval;

function init_notifications(cb) {
  window.addEventListener("DOMContentLoaded", async () => {
    interval = setInterval(async () => {
      await fetch_notifications();
    }, 5000);
    await fetch_notifications();
  });

  window.addEventListener("beforeunload", () => {
    clearInterval(interval);
  });
}
