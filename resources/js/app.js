require("./bootstrap");
require("datatables.net-bs5/js/dataTables.bootstrap5.min");
require("datatables.net-responsive-bs5/js/responsive.bootstrap5.min");

require("moment");
require("chart.js");
require("select2");

const Swal = window.Swal = require("sweetalert2");

$(function () {
    $('[data-bs-toggle="tooltip"]').tooltip()
})
window.showLoadPopup = function () {
    Swal.fire({
        onBeforeOpen: () => {
            Swal.showLoading();
        },
        showConfirmButton: false
    });
};

window.colorGradients = [
    "#184C8F",
    "#245595",
    "#2F5E9A",
    "#3B67A0",
    "#4670A5",
    "#5279AB",
    "#5D82B1",
    "#698BB6",
    "#7494BC",
    "#809DC1",
    "#8CA6C7",
    "#97AECD",
    "#A3B7D2",
    "#AEC0D8",
    "#BAC9DD",
    "#C5D2E3",
    "#D1DBE9",
    "#DCE4EE",
    "#E8EDF4",
    "#F3F6F9",
    "#FFFFFF",
];
