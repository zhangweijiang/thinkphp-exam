define(function (require, exports) {
    $(document).ready(function () {
        var c = [];
        $(document).mousemove(function (e) {
            var e = e ? e : window.event, a = document.documentElement.scrollLeft || document.body.scrollLeft,
                y = document.documentElement.scrollTop || document.body.scrollTop, x = e.pageX || e.clientX + a,
                h = e.pageY || e.clientY + y;
            c.length > 2 && c.pop(), c.push({x: x, y: h})
        }), exports.getSlope = function (a) {
            var y = c.length;
            if (!(y > 2)) return 0;
            var h = {x1: c[0].x, y1: c[0].y, x2: c[y - 1].x, y2: c[y - 1].y};
            if (c = [], h.x1 == h.x2 || h.y1 == h.y2) return 0;
            if (h.x2 < h.x1) return 0;
            if (h.x1 < a.left || h.x1 > a.lx) return 0;
            var v = Math.abs((h.y2 - h.y1) / (h.x2 - h.x1));
            return v ? v > 0 && v > Math.tan(Math.PI / 3) || 0 > v && v < -Math.tan(Math.PI / 3) ? 0 : 1 : void 0
        }
    })
});