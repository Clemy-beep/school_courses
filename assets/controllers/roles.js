import $ from "jquery";
export default function setRoles() {
    let role = $("#roles").text();
    if (role === "Admin") $('#roles').addClass("admin-badge");
    else if (role === "Mentor") $('#roles').addClass("mentor-badge");
    else $('#roles').addClass('user-badge');
}