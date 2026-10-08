import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

// Modal Mode Pop Up Profile Admin Laundry
window.openProfileModal = function () {
    document.getElementById("profileModal").classList.remove("hidden");
    document.getElementById("profileModal").classList.add("flex");
};

window.closeProfileModal = function () {
    document.getElementById("profileModal").classList.add("hidden");
    document.getElementById("profileModal").classList.remove("flex");
};

window.previewProfileImage = function (event) {
    const image = document.getElementById("previewImage");
    image.src = URL.createObjectURL(event.target.files[0]);
    image.classList.remove("hidden");
};

// Fitur Hide UnitUsaha Admin Laundry Yang Terlalu Panjang
window.toggleUnit = function (id) {
    const el = document.getElementById(id);
    el.classList.toggle("hidden");
};
