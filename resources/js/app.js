import ApexCharts from 'apexcharts'
import {Notyf} from 'notyf';

require("./bootstrap");
require("datatables.net-bs5/js/dataTables.bootstrap5.min");
require("datatables.net-responsive-bs5/js/responsive.bootstrap5.min");

require("moment");
require("select2");

window.ApexCharts = ApexCharts;
const Swal = window.Swal = require("sweetalert2");

$(function () {
    $('[data-bs-toggle="tooltip"]').tooltip()
});

document.addEventListener("DOMContentLoaded", function () {
    window.notyf = new Notyf({
        duration: 5000,
        position: {x: "right", y: "top"}
    });
});

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
];
