! function (t) {
    "use strict";
    jQuery(document)
        .ready(function (t) {
            t(".cart-link")
                .click(function () {
                    let e = t(this)
                        .attr("data-href");
                    let a = t(".cart-amount")
                        .val();
                    a > 1 ? t.get(e + ",,," + a, function (e) {
                        e.message ? (toastr.success(e.message), t(".cart-amount")
                            .val(1), t("#cartIconWrapper")
                                .load(location.href + " #cartIconWrapper")) : (toastr.error(e.error), t(".cart-amount")
                                    .val(1), t("#cartIconWrapper")
                                        .load(location.href + " #cartIconWrapper"))
                    }) : t.get(e, function (e) {
                        e.message ? (toastr.success(e.message), t("#cartIconWrapper")
                            .load(location.href + " #cartIconWrapper")) : (toastr.error(e.error), t("#cartIconWrapper")
                                .load(location.href + " #cartIconWrapper"))
                    })
                }),
                t(".cart-link2")
                    .click(function () {
                        var url = t(this).attr("data-href");
                        let q = t('#quantity').val();
                        let e = url + '?qty=' + q;
                        console.log(e);
                        let a = t(".cart-amount").val();
                        a > 1 ? t.get(e + ",,," + a, function (e) {
                            e.message ? (toastr.success(e.message), t(".cart-amount")
                                .val(1), t("#cartIconWrapper")
                                    .load(location.href + " #cartIconWrapper")) : (toastr.error(e.error), t(".cart-amount")
                                        .val(1), t("#cartIconWrapper")
                                            .load(location.href + " #cartIconWrapper"))
                        }) : t.get(e, function (e) {
                            e.message ? (toastr.success(e.message), t("#cartIconWrapper")
                                .load(location.href + " #cartIconWrapper")) : (toastr.error(e.error), t("#cartIconWrapper")
                                    .load(location.href + " #cartIconWrapper"))
                        })
                    }),
                t(document)
                    .on("click", "#cartUpdate", function () {
                        let e = []
                            , a = []
                            , r = []
                            , o = t(this)
                                .attr("data-href");
                        t(".cart_qty")
                            .each(function () {
                                e.push(t(this)
                                    .val())
                            }), t(".product-price")
                                .each(function () {
                                    a.push(parseFloat(t(this)
                                        .text()))
                                }), t(".product_id")
                                    .each(function () {
                                        r.push(t(this)
                                            .val())
                                    });
                        let c = new FormData
                            , n = 0;
                        for (n = 0; n < e.length; n++) c.append("qty[]", e[n]), c.append("cartprice[]", a[n]), c.append("product_id[]", r[n]);
                        t.ajaxSetup({
                            headers: {
                                "X-CSRF-TOKEN": t('meta[name="csrf-token"]')
                                    .attr("content")
                            }
                        }), t.ajax({
                            type: "POST"
                            , url: o
                            , data: c
                            , processData: !1
                            , contentType: !1
                            , success: function (a) {
                                if (console.log(a), a.message) {
                                    let r = [];
                                    t(".product-price span")
                                        .each(function () {
                                            r.push(parseFloat(t(this)
                                                .text()))
                                        }), t(".product-total-price span")
                                            .each(function (a, o) {
                                                t(this)
                                                    .text(r[a] * e[a])
                                            }), t(".cart-total-view")
                                                .text(a.total), toastr.success(a.message), a.count && (t(".cart-item-view")
                                                    .text(a.count), t(".cart-total-view")
                                                        .text(("left" == position ? symbol + " " : "") + a.total + ("right" == position ? " " + symbol : ""))), t("#cartIconWrapper")
                                                            .load(location.href + " #cartIconWrapper")
                                } else toastr.error(a.error)
                            }
                        })
                    }), t(document)
                        .on("click", ".item-remove", function () {
                            let e = t(this)
                                .attr("rel")
                                , a = t(this)
                                    .attr("data-href");
                            t.get(a, function (a) {
                                a.message ? (toastr.success(a.message), 0 == a.count && (t(".cart-total-product")
                                    .remove(), t(".cart-total-price")
                                        .remove(), t(".cart-middle")
                                            .remove(), t(".table-outer")
                                                .html('\n                        <div class="bg-light py-5 text-center">\n                            <h3 class="text-uppercase">Cart is empty!</h3>\n                        </div>')), t(".cart-item-view")
                                                    .text(a.count), t(".cart-total-view")
                                                        .text(("left" == position ? symbol + " " : "") + a.total + ("right" == position ? " " + symbol : "")), t(".remove" + e)
                                                            .remove(), t("#cartIconWrapper")
                                                                .load(location.href + " #cartIconWrapper")) : toastr.error(a.error)
                            })
                        })
        }), t(document)
            .on("click", ".addclick", function () {
                let e = t(".cart-amount")
                    .val();
                t("#order_click_with_qty")
                    .val(e)
            }), t(document)
                .on("click", ".subclick", function () {
                    let e = t(".cart-amount")
                        .val();
                    t("#order_click_with_qty")
                        .val(e)
                })


}(jQuery);
