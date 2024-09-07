
document.addEventListener("DOMContentLoaded", function () {
    var currentURL = window.location.href;

    var dashboardURL = "dashboard.php";
    var requestURL = "request.php";
    var disbursementURL = "chequedisbursement.php";
    var doneURL = "done.php";

    var pageTitleElement = document.getElementById("page-title");

    if (currentURL.includes(dashboardURL)) {
        document.getElementById("dashboard-link").classList.add("active");
        pageTitleElement.textContent = "Dashboard";
    } else if (currentURL.includes(requestURL)) {
        document.getElementById("request-link").classList.add("active");
        pageTitleElement.textContent = "Request";
    }else if (currentURL.includes(disbursementURL)) {
        document.getElementById("disbursement-link").classList.add("active");
        pageTitleElement.textContent = "Cheque Disbursement";
    }else if (currentURL.includes(doneURL)) {
        document.getElementById("done-link").classList.add("active");
        pageTitleElement.textContent = "Done";
    }
});
