define(function (require) {
    require("common"), require("/static/lib/stackblur/stackblur.js");
    var a = require("./mouse-move-angle.js");
    $(function () {
        var c = function () {
            var a = navigator.userAgent;
            return a.indexOf("Opera") > -1 || a.indexOf("OPR") > -1 ? !1 : a.indexOf("compatible") > -1 && a.indexOf("MSIE") > -1 ? !0 : a.indexOf("Edge") > -1 ? "Edge" : a.indexOf("Firefox") > -1 ? !1 : a.indexOf("Safari") > -1 && -1 == a.indexOf("Chrome") ? !1 : a.indexOf("Chrome") > -1 && a.indexOf("Safari") > -1 ? !1 : window.ActiveXObject || "ActiveXObject" in window ? !0 : !1
        };
        $("#js-app-load").on("mouseenter", function () {
            $(this).find(".js-load-box").show()
        }).on("mouseleave", function () {
            $(this).find(".js-load-box").hide()
        }), $(".search-input").on("focus", function () {
            $(".search-warp").addClass("focused")
        }).on("blur", function () {
            $(".search-warp").removeClass("focused")
        }), c() && $(".bk").hide();
        var h, v = 0;
        $(".menuContent .item").mouseenter(function () {
            var c = $(this), g = {
                left: c.offset().left,
                top: c.offset().top,
                lx: c.offset().left + c.outerWidth(),
                ty: c.offset().top + c.outerHeight()
            };
            if (h = a.getSlope(g), clearTimeout(v), h) {
                var b = this, j = $(".menuContent").find(".js-menu-item-on")[0];
                v = setTimeout(function () {
                    if (j != b) {
                        $(".menuContent .item").removeClass("js-menu-item-on"), c.addClass("js-menu-item-on"), $(".submenu").hide();
                        var a = c.attr("data-id");
                        $("." + a).show()
                    }
                }, 600)
            } else {
                clearTimeout(v), $(".menuContent .item").removeClass("js-menu-item-on"), c.addClass("js-menu-item-on"), $(".submenu").hide();
                var C = c.attr("data-id");
                $("." + C).show()
            }
        }), $(".menuContent , .submenu").mouseleave(function () {
            clearTimeout(v), h || ($(".menuContent .item").removeClass("js-menu-item-on"), $(".submenu").hide())
        }), $(".submenu").mouseenter(function () {
            $(".submenu").hide();
            var a = $(this).data("id"), c = $(".menuContent .item");
            $.each(c, function (i) {
                var h = $(c[i]).data("id");
                h == a && $(c[i]).addClass("js-menu-item-on")
            }), $(this).show()
        }), $(".g-banner").on("mouseleave", function () {
            $(".menuContent .item").removeClass("js-menu-item-on"), $(".submenu").hide()
        });
        var g = !1, b = 0;
        $(document).delegate(".js-upAni", "mouseenter", function () {
            var a = this;
            b = setTimeout(function () {
                if ($(a).find(".js-upBox").css({top: "77px"}), $(a).find(".title").css({
                        height: "auto",
                        "max-height": "48px",
                        overflow: "hidden"
                    }), $(a).find(".summary").css({margin: "0px"}), !g) {
                    $(a).find(".js-upBox").addClass("doing");
                    var c = $(a).find(".js-upBox");
                    $(a).find(".js-upBox").animate({top: "22px"}, 100, function () {
                        $(a).find(".js-upBox").addClass("box_body_hover"), g = !1, c.hasClass("doing") || c.css("top", "77px")
                    })
                }
                return !1
            }, 200)
        }), $(document).delegate(".js-upAni", "mouseleave", function () {
            window.clearInterval(b), $(this).find(".js-upBox").removeClass("box_body_hover"), $(this).find(".title").css("height", "24px"), $(this).find(".summary").css({margin: "2px"}), $(".doing").css("top", "77px"), $(".doing").removeClass("doing"), $(this).find(".js-upBox").css({top: "77px"});
            $(this).find(".js-upBox");
            return !1
        })
    }), $("#js-header-login").click(function () {
        require.async("login_sns", function (mod) {
            mod.init()
        })
    }), $("#js-header-register").click(function () {
        require.async("login_sns", function (mod) {
            mod.signup()
        })
    });
    var c = "24px", h = $(".js-notice-txt").width();
    $(".js-notice").size() > 0 && ($(".js-notice").on("mouseenter", function () {
        $(this).hasClass("closed") && ($(".js-notice").removeClass("closed"), $(".js-notice-txt").animate({width: h}))
    }), $(".js-notice-close").on("click", function (e) {
        e.stopPropagation(), $(".js-notice").addClass("closed"), $(".js-notice-txt").animate({width: c})
    }));
    var v = function (a) {
        for (var c = $(".js-wonderful-list .item").outerWidth(), h = a.width(), v = parseInt(h / c), g = [], i = 0; v > i; i++) g.push(0);
        var b = 0;
        $(".js-wonderful-list .item").each(function () {
            for (var a = $(this), c = 0, h = g[0], v = 0; v < g.length; v++) g[v] < h && (h = g[v], c = v);
            a.css({left: c * a.outerWidth(!0), top: h}), g[c] = h + a.outerHeight(!0)
        }), g = g.sort(function (a, c) {
            return a - c
        }), b = g[g.length - 1], a.height(b)
    }, g = "", b = "";
    $.ajax({
        url: "/index/getstarlist", type: "get", dataType: "json", success: function (a) {
            if (0 == a.result) {
                for (var x = 0; x < a.data.numberOne.length; x++) {
                    var c = "yellow", h = "", j = "javascript:void(0);";
                    if ("" != a.data.numberOne[x]) {
                        switch (h = a.data.numberOne[x].img, j = "/u/" + a.data.numberOne[x].uid, a.data.numberOne[x].type) {
                            case 0:
                                c = "purple";
                                break;
                            case 1:
                                c = "green";
                                break;
                            case 2:
                                c = "blue";
                                break;
                            case 3:
                                c = "yellow", j = "javascript:void(0);", h = "/static/img/index/tuhao.png"
                        }
                        g += '<dd>                                <div class="lead-item-photo">                                    <a target="_blank" href=' + j + '><img src="' + h + '"></a>                                    <span class="' + c + '"></span>                                </div>                                <p class="lead-item-name ellipsis">' + a.data.numberOne[x].nickname + '</p>                                <p class="lead-item-tit">' + a.data.cate[a.data.numberOne[x].type] + "</p>                            </dd>"
                    }
                }
                $(".js-lead-list").html(g);
                for (var C = 0; C < a.data.list.length; C++) {
                    var w = "", O = "", k = "";
                    switch (a.data.list[C].type) {
                        case 0:
                            w = "purple", O = "一周获得 " + a.data.list[C].count + "积分";
                            break;
                        case 1:
                            w = "green", O = "一周解题 " + a.data.list[C].count + " 个";
                            break;
                        case 2:
                            w = "blue", O = "一周发布手记 " + a.data.list[C].count + " 篇"
                    }
                    10 == C ? k = "marr0" : 11 == C && (k = "marl0"), b += '<dd class="other-item ' + w + " " + k + '">                                <a target="_blank" href="/u/' + a.data.list[C].uid + '">                                <img src="' + a.data.list[C].img + '">                                <div>                                    <p class="title">="' + a.data.cate[a.data.list[C].type] + '"=</p>                                    <p class="nickname">' + a.data.list[C].nickname + '</p>                                    <p class="desc">' + O + '</p>                                    <span class="cur">◆</span>                                </div>                                </a>                            </dd>'
                }
                $(".js-other-list").html(b)
            }
            setTimeout(function () {
                v($(".js-wonderful-list"))
            }, 100)
        }
    });
    var j = function (t) {
        var a = Math.floor(t / 3600), m = Math.floor(t % 3600 / 60), s = Math.floor(t % 3600 % 60);
        return a = 10 > a ? "0" + a : a, m = 10 > m ? "0" + m : m, s = 10 > s ? "0" + s : s, a + ":" + m + ":" + s
    }, C = function (t, a) {
        t--, $(a).html(j(t)), t > 0 && setTimeout(function () {
            C(t, a)
        }, 1e3)
    };
    $.each($(".js-sales-timer"), function (a, c) {
        var h = $(c).data("timer");
        h && C(h, c)
    }), $(".js-other-list").on("mouseenter", "dd", function () {
        $(this).find("div").fadeIn(150)
    }).on("mouseleave", "dd", function () {
        $(this).find("div").fadeOut(150)
    }), function () {
        function a(i) {
            var a = C.filter(".slide-active");
            C.stop(!0, !0), a.removeClass("slide-active").fadeOut(400), C.eq(i).addClass("slide-active").fadeIn(600), c(i)
        }

        function c(i) {
            b && b.removeClass("active").eq(i).addClass("active"), $(".bk").css("backgroundImage", 'url("' + C.eq(i).attr("data-url") + '")')
        }

        function h() {
            y--, y = 0 > y ? k - 1 : y, a(y)
        }

        function v() {
            y++, y = y > k - 1 ? 0 : y, a(y)
        }

        function g() {
            B && clearInterval(B), B = setInterval(function () {
                v()
            }, I)
        }

        var b, j = $(".g-banner"), C = j.find(".banner-slide"), w = j.find(".banner-dots"), O = "", k = C.length, y = 0,
            I = 3e3, B = null;
        1 != k && ($(".banner-slide").each(function (i) {
            if (i > 0) {
                var a = '<img src="' + $(this).attr("data-url") + '">';
                $(this).html(a)
            }
        }), O = "<span></span>", $.each(C, function (i) {
            if (i > 0) {
                var a = '<img src="' + $(this).attr("data-url") + '">';
                $(this).html(a)
            }
            w.append(O)
        }), b = w.find("span"), j.find(".banner-anchor").removeAttr("style"), j.on("click", ".prev", function () {
            h()
        }).on("click", ".next", function () {
            v()
        }).on("click", ".banner-dots span", function () {
            if (!$(this).hasClass("active")) {
                var i = $(this).index();
                y = i, a(i)
            }
        }), j.on("mouseenter", function () {
            B && clearInterval(B)
        }).on("mouseleave", function () {
            g()
        }), c(0), g())
    }(), $(window).trigger("scroll")
});