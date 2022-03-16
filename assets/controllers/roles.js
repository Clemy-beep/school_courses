import $ from "jquery";
export default function setRoles() {
    let roles = document.getElementsByClassName('roles');
    Array.from(roles).forEach(roleElement => {
        let role = roleElement.innerText;
        switch (role) {
            case "Admin":
                roleElement.setAttribute('id', 'admin-badge');
                break;
            case "Mentor":
                roleElement.setAttribute('id', 'mentor-badge');
                break;
            default:
                roleElement.setAttribute('id', 'student-badge');
                break;
        }
        console.log(roleElement);
    });
}