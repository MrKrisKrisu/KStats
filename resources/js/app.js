require("./bootstrap");
require("datatables.net-bs5/js/dataTables.bootstrap5.min");
require("datatables.net-responsive-bs5/js/responsive.bootstrap5.min");

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