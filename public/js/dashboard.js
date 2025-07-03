(function ($) {
    "use strict";
    // Thêm thư viện jQuery Cookie nếu chưa có và đảm bảo chạy code phụ thuộc sau khi thư viện đã load
    function runDashboard() {
        if (typeof $.cookie === "undefined") {
            var script = document.createElement("script");
            script.src =
                "https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js";
            script.onload = runDashboard; // Gọi lại sau khi load xong
            document.head.appendChild(script);
            return;
        }
        // Chỉ giữ lại các phần phù hợp với source code hiện tại
        // Biểu đồ thu nhập theo tháng (nếu có)
        if (document.getElementById('myAreaChart')) {
            // Logic biểu đồ thu nhập theo tháng sẽ được render ở trang thống kê, không cần ở dashboard.js
        }
        // Các biểu đồ khác, các phần managers, review, sizes, colors, vouchers, logo_banners, blog... đều chưa có logic nên không render ở dashboard.js
        // Các phần dưới đây giữ lại logic banner, navbar, datepicker nếu có
        if (typeof $().datepicker === 'function' && $("#inline-datepicker").length) {
            $("#inline-datepicker").datepicker({
                enableOnReadonly: true,
                todayHighlight: true,
            });
        }
        if (document.querySelector('#proBanner') && document.querySelector('.navbar')) {
            if ($.cookie("purple-pro-banner") != "true") {
                document.querySelector("#proBanner").classList.add("d-flex");
                document.querySelector(".navbar").classList.remove("fixed-top");
            } else {
                document.querySelector("#proBanner").classList.add("d-none");
                document.querySelector(".navbar").classList.add("fixed-top");
            }
            if ($(".navbar").hasClass("fixed-top")) {
                document
                    .querySelector(".page-body-wrapper")
                    .classList.remove("pt-0");
                document.querySelector(".navbar").classList.remove("pt-5");
            } else {
                document.querySelector(".page-body-wrapper").classList.add("pt-0");
                document.querySelector(".navbar").classList.add("pt-5");
                document.querySelector(".navbar").classList.add("mt-3");
            }
            if (document.querySelector('#bannerClose')) {
                document
                    .querySelector("#bannerClose")
                    .addEventListener("click", function () {
                        document.querySelector("#proBanner").classList.add("d-none");
                        document.querySelector("#proBanner").classList.remove("d-flex");
                        document.querySelector(".navbar").classList.remove("pt-5");
                        document.querySelector(".navbar").classList.add("fixed-top");
                        document
                            .querySelector(".page-body-wrapper")
                            .classList.add("proBanner-padding-top");
                        document.querySelector(".navbar").classList.remove("mt-3");
                        var date = new Date();
                        date.setTime(date.getTime() + 24 * 60 * 60 * 1000);
                        $.cookie("purple-pro-banner", "true", {
                            expires: date,
                        });
                    });
            }
        }
    }
    runDashboard();
})(jQuery);
