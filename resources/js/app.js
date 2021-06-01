require("./bootstrap");
require("datatables.net-bs4");
require("datatables.net-responsive-bs4");

require("moment");
require("chart.js");
require("select2");

const Swal = window.Swal = require("sweetalert2");

$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})
window.showLoadPopup = function () {
    Swal.fire({
        onBeforeOpen: () => {
            Swal.showLoading();
        },
        showConfirmButton: false
    });
};