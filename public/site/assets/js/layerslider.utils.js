var _initLayerSlider = function (t, e, i) {
    var r = jQuery;
    r(document).ready(function () {
        void 0 === r.fn.layerSlider
            ? window._layerSlider &&
              window._layerSlider.showNotice &&
              window._layerSlider.showNotice(t, "jquery")
            : (i &&
                  r.each(i, function (e, i) {
                      r(t).on(e, i);
                  }),
              r(t).layerSlider(e));
    });
};
if ("object" == typeof LS_Meta && LS_Meta.fixGSAP) {
    var LS_oldGS = window.GreenSockGlobals,
        LS_oldGSQueue = window._gsQueue,
        LS_oldGSDefine = window._gsDefine;
    (window._gsDefine = null), delete window._gsDefine;
    var LS_GSAP = (window.GreenSockGlobals = {});
}
var _gsScope =
    "undefined" != typeof module &&
    module.exports &&
    "undefined" != typeof global
        ? global
        : this || window;
(_gsScope._gsQueue || (_gsScope._gsQueue = [])).push(function () {
    "use strict";
    function t(t) {
        var e = t < 1 ? Math.pow(10, (t + "").length - 2) : 1;
        return function (i) {
            return ((Math.round(i / t) * t * e) | 0) / e;
        };
    }
    function e(t, e) {
        for (; t; ) t.f || t.blob || (t.m = e || Math.round), (t = t._next);
    }
    var i, r, n, s, a, o, l, h, u;
    function _(t, e, i, r) {
        i === r && (i = r - (r - e) / 1e6),
            t === e && (e = t + (i - t) / 1e6),
            (this.a = t),
            (this.b = e),
            (this.c = i),
            (this.d = r),
            (this.da = r - t),
            (this.ca = i - t),
            (this.ba = e - t);
    }
    function f(t, e, i, r) {
        var n = { a: t },
            s = {},
            a = {},
            o = { c: r },
            l = (t + e) / 2,
            h = (e + i) / 2,
            u = (i + r) / 2,
            _ = (l + h) / 2,
            f = (h + u) / 2,
            c = (f - _) / 8;
        return (
            (n.b = l + (t - l) / 4),
            (s.b = _ + c),
            (n.c = s.a = (n.b + s.b) / 2),
            (s.c = a.a = (_ + f) / 2),
            (a.b = f - c),
            (o.b = u + (r - u) / 4),
            (a.c = o.a = (a.b + o.b) / 2),
            [n, s, a, o]
        );
    }
    function c(t, e, i, r, o) {
        var l,
            h,
            u,
            _,
            c,
            p,
            d,
            m,
            g,
            y,
            v,
            x,
            T,
            w = t.length - 1,
            b = 0,
            P = t[0].a;
        for (l = 0; l < w; l++)
            (h = (c = t[b]).a),
                (u = c.d),
                (_ = t[b + 1].d),
                (m = o
                    ? ((v = n[l]),
                      (T =
                          (((x = s[l]) + v) * e * 0.25) /
                          (r ? 0.5 : a[l] || 0.5)),
                      u -
                          ((p =
                              u -
                              (u - h) * (r ? 0.5 * e : 0 !== v ? T / v : 0)) +
                              ((((d =
                                  u +
                                  (_ - u) *
                                      (r ? 0.5 * e : 0 !== x ? T / x : 0)) -
                                  p) *
                                  ((3 * v) / (v + x) + 0.5)) /
                                  4 || 0)))
                    : u -
                      ((p = u - (u - h) * e * 0.5) +
                          (d = u + (_ - u) * e * 0.5)) /
                          2),
                (p += m),
                (d += m),
                (c.c = g = p),
                (c.b = 0 !== l ? P : (P = c.a + 0.6 * (c.c - c.a))),
                (c.da = u - h),
                (c.ca = g - h),
                (c.ba = P - h),
                i
                    ? ((y = f(h, P, g, u)),
                      t.splice(b, 1, y[0], y[1], y[2], y[3]),
                      (b += 4))
                    : b++,
                (P = d);
        ((c = t[b]).b = P),
            (c.c = P + 0.4 * (c.d - P)),
            (c.da = c.d - c.a),
            (c.ca = c.c - c.a),
            (c.ba = P - c.a),
            i &&
                ((y = f(c.a, P, c.c, c.d)),
                t.splice(b, 1, y[0], y[1], y[2], y[3]));
    }
    function p(t, e, i, r) {
        var a,
            o,
            l,
            h,
            u,
            f,
            c = [];
        if (r)
            for (o = (t = [r].concat(t)).length; -1 < --o; )
                "string" == typeof (f = t[o][e]) &&
                    "=" === f.charAt(1) &&
                    (t[o][e] = r[e] + Number(f.charAt(0) + f.substr(2)));
        if ((a = t.length - 2) < 0)
            return (c[0] = new _(t[0][e], 0, 0, t[0][e])), c;
        for (o = 0; o < a; o++)
            (l = t[o][e]),
                (h = t[o + 1][e]),
                (c[o] = new _(l, 0, 0, h)),
                i &&
                    ((u = t[o + 2][e]),
                    (n[o] = (n[o] || 0) + (h - l) * (h - l)),
                    (s[o] = (s[o] || 0) + (u - h) * (u - h)));
        return (c[o] = new _(t[o][e], 0, 0, t[o + 1][e])), c;
    }
    function d(t, e, i, r, l, h) {
        var u,
            _,
            f,
            d,
            m,
            g,
            y,
            v,
            x = {},
            T = [],
            w = h || t[0];
        for (_ in ((l =
            "string" == typeof l
                ? "," + l + ","
                : ",x,y,z,left,top,right,bottom,marginTop,marginLeft,marginRight,marginBottom,paddingLeft,paddingTop,paddingRight,paddingBottom,backgroundPosition,backgroundPosition_y,"),
        null == e && (e = 1),
        t[0]))
            T.push(_);
        if (1 < t.length) {
            for (v = t[t.length - 1], y = !0, u = T.length; -1 < --u; )
                if (((_ = T[u]), 0.05 < Math.abs(w[_] - v[_]))) {
                    y = !1;
                    break;
                }
            y &&
                ((t = t.concat()),
                h && t.unshift(h),
                t.push(t[1]),
                (h = t[t.length - 3]));
        }
        for (n.length = s.length = a.length = 0, u = T.length; -1 < --u; )
            (_ = T[u]),
                (o[_] = -1 !== l.indexOf("," + _ + ",")),
                (x[_] = p(t, _, o[_], h));
        for (u = n.length; -1 < --u; )
            (n[u] = Math.sqrt(n[u])), (s[u] = Math.sqrt(s[u]));
        if (!r) {
            for (u = T.length; -1 < --u; )
                if (o[_])
                    for (g = (f = x[T[u]]).length - 1, d = 0; d < g; d++)
                        (m = f[d + 1].da / s[d] + f[d].da / n[d] || 0),
                            (a[d] = (a[d] || 0) + m * m);
            for (u = a.length; -1 < --u; ) a[u] = Math.sqrt(a[u]);
        }
        for (u = T.length, d = i ? 4 : 1; -1 < --u; )
            c((f = x[(_ = T[u])]), e, i, r, o[_]),
                y && (f.splice(0, d), f.splice(f.length - d, d));
        return x;
    }
    function m(t, e, i) {
        for (
            var r, n, s, a, o, l, h, u, _, f, c, p = 1 / i, d = t.length;
            -1 < --d;

        )
            for (
                s = (f = t[d]).a,
                    a = f.d - s,
                    o = f.c - s,
                    l = f.b - s,
                    r = n = 0,
                    u = 1;
                u <= i;
                u++
            )
                (r =
                    n -
                    (n =
                        ((h = p * u) * h * a +
                            3 * (_ = 1 - h) * (h * o + _ * l)) *
                        h)),
                    (e[(c = d * i + u - 1)] = (e[c] || 0) + r * r);
    }
    _gsScope._gsDefine(
        "TweenMax",
        ["core.Animation", "core.SimpleTimeline", "TweenLite"],
        function (t, e, i) {
            function r(t) {
                var e,
                    i = [],
                    r = t.length;
                for (e = 0; e !== r; i.push(t[e++]));
                return i;
            }
            function n(t, e, i) {
                var r,
                    n,
                    s = t.cycle;
                for (r in s)
                    (n = s[r]),
                        (t[r] =
                            "function" == typeof n
                                ? n(i, e[i], e)
                                : n[i % n.length]);
                delete t.cycle;
            }
            function s(t) {
                if ("function" == typeof t) return t;
                var e = "object" == typeof t ? t : { each: t },
                    i = e.ease,
                    r = e.from || 0,
                    n = e.base || 0,
                    s = {},
                    a = isNaN(r),
                    o = e.axis,
                    l = { center: 0.5, end: 1 }[r] || 0;
                return function (t, h, u) {
                    var _,
                        f,
                        c,
                        p,
                        d,
                        m,
                        g,
                        y,
                        v,
                        x = (u || e).length,
                        T = s[x];
                    if (!T) {
                        if (
                            !(v =
                                "auto" === e.grid ? 0 : (e.grid || [1 / 0])[0])
                        ) {
                            for (
                                g = -1 / 0;
                                g < (g = u[v++].getBoundingClientRect().left) &&
                                v < x;

                            );
                            v--;
                        }
                        for (
                            T = s[x] = [],
                                _ = a ? Math.min(v, x) * l - 0.5 : r % v,
                                f = a ? (x * l) / v - 0.5 : (r / v) | 0,
                                y = 1 / (g = 0),
                                m = 0;
                            m < x;
                            m++
                        )
                            (c = (m % v) - _),
                                (p = f - ((m / v) | 0)),
                                (T[m] = d =
                                    o
                                        ? Math.abs("y" === o ? p : c)
                                        : Math.sqrt(c * c + p * p)),
                                g < d && (g = d),
                                d < y && (y = d);
                        (T.max = g - y),
                            (T.min = y),
                            (T.v = x =
                                e.amount ||
                                e.each *
                                    (x < v
                                        ? x - 1
                                        : o
                                        ? "y" === o
                                            ? x / v
                                            : v
                                        : Math.max(v, x / v)) ||
                                0),
                            (T.b = x < 0 ? n - x : n);
                    }
                    return (
                        (x = (T[t] - T.min) / T.max),
                        T.b + (i ? i.getRatio(x) : x) * T.v
                    );
                };
            }
            var a = function (t, e, r) {
                    i.call(this, t, e, r),
                        (this._cycle = 0),
                        (this._yoyo =
                            !0 === this.vars.yoyo || !!this.vars.yoyoEase),
                        (this._repeat = this.vars.repeat || 0),
                        (this._repeatDelay = this.vars.repeatDelay || 0),
                        this._repeat && this._uncache(!0),
                        (this.render = a.prototype.render);
                },
                o = 1e-8,
                l = i._internals,
                h = l.isSelector,
                u = l.isArray,
                _ = (a.prototype = i.to({}, 0.1, {})),
                f = [];
            (a.version = "2.1.3"),
                (_.constructor = a),
                (_.kill()._gc = !1),
                (a.killTweensOf = a.killDelayedCallsTo = i.killTweensOf),
                (a.getTweensOf = i.getTweensOf),
                (a.lagSmoothing = i.lagSmoothing),
                (a.ticker = i.ticker),
                (a.render = i.render),
                (a.distribute = s),
                (_.invalidate = function () {
                    return (
                        (this._yoyo =
                            !0 === this.vars.yoyo || !!this.vars.yoyoEase),
                        (this._repeat = this.vars.repeat || 0),
                        (this._repeatDelay = this.vars.repeatDelay || 0),
                        (this._yoyoEase = null),
                        this._uncache(!0),
                        i.prototype.invalidate.call(this)
                    );
                }),
                (_.updateTo = function (t, e) {
                    var r,
                        n = this,
                        s = n.ratio,
                        a = n.vars.immediateRender || t.immediateRender;
                    for (r in (e &&
                        n._startTime < n._timeline._time &&
                        ((n._startTime = n._timeline._time),
                        n._uncache(!1),
                        n._gc
                            ? n._enabled(!0, !1)
                            : n._timeline.insert(n, n._startTime - n._delay)),
                    t))
                        n.vars[r] = t[r];
                    if (n._initted || a)
                        if (e) (n._initted = !1), a && n.render(0, !0, !0);
                        else if (
                            (n._gc && n._enabled(!0, !1),
                            n._notifyPluginsOfEnabled &&
                                n._firstPT &&
                                i._onPluginEvent("_onDisable", n),
                            0.998 < n._time / n._duration)
                        ) {
                            var o = n._totalTime;
                            n.render(0, !0, !1),
                                (n._initted = !1),
                                n.render(o, !0, !1);
                        } else if (
                            ((n._initted = !1), n._init(), 0 < n._time || a)
                        )
                            for (var l, h = 1 / (1 - s), u = n._firstPT; u; )
                                (l = u.s + u.c),
                                    (u.c *= h),
                                    (u.s = l - u.c),
                                    (u = u._next);
                    return n;
                }),
                (_.render = function (t, e, r) {
                    this._initted ||
                        (0 === this._duration &&
                            this.vars.repeat &&
                            this.invalidate());
                    var n,
                        s,
                        a,
                        h,
                        u,
                        _,
                        f,
                        c,
                        p,
                        d = this,
                        m = d._dirty ? d.totalDuration() : d._totalDuration,
                        g = d._time,
                        y = d._totalTime,
                        v = d._cycle,
                        x = d._duration,
                        T = d._rawPrevTime;
                    if (
                        (m - o <= t && 0 <= t
                            ? ((d._totalTime = m),
                              (d._cycle = d._repeat),
                              d._yoyo && 0 != (1 & d._cycle)
                                  ? ((d._time = 0),
                                    (d.ratio = d._ease._calcEnd
                                        ? d._ease.getRatio(0)
                                        : 0))
                                  : ((d._time = x),
                                    (d.ratio = d._ease._calcEnd
                                        ? d._ease.getRatio(1)
                                        : 1)),
                              d._reversed ||
                                  ((n = !0),
                                  (s = "onComplete"),
                                  (r = r || d._timeline.autoRemoveChildren)),
                              0 !== x ||
                                  (!d._initted && d.vars.lazy && !r) ||
                                  (d._startTime === d._timeline._duration &&
                                      (t = 0),
                                  (T < 0 ||
                                      (t <= 0 && -o <= t) ||
                                      (T === o && "isPause" !== d.data)) &&
                                      T !== t &&
                                      ((r = !0),
                                      o < T && (s = "onReverseComplete")),
                                  (d._rawPrevTime = c =
                                      !e || t || T === t ? t : o)))
                            : t < o
                            ? ((d._totalTime = d._time = d._cycle = 0),
                              (d.ratio = d._ease._calcEnd
                                  ? d._ease.getRatio(0)
                                  : 0),
                              (0 !== y || (0 === x && 0 < T)) &&
                                  ((s = "onReverseComplete"),
                                  (n = d._reversed)),
                              -o < t
                                  ? (t = 0)
                                  : t < 0 &&
                                    ((d._active = !1),
                                    0 !== x ||
                                        (!d._initted && d.vars.lazy && !r) ||
                                        (0 <= T && (r = !0),
                                        (d._rawPrevTime = c =
                                            !e || t || T === t ? t : o))),
                              d._initted || (r = !0))
                            : ((d._totalTime = d._time = t),
                              0 !== d._repeat &&
                                  ((h = x + d._repeatDelay),
                                  (d._cycle = (d._totalTime / h) >> 0),
                                  0 !== d._cycle &&
                                      d._cycle === d._totalTime / h &&
                                      y <= t &&
                                      d._cycle--,
                                  (d._time = d._totalTime - d._cycle * h),
                                  d._yoyo &&
                                      0 != (1 & d._cycle) &&
                                      ((d._time = x - d._time),
                                      (p = d._yoyoEase || d.vars.yoyoEase) &&
                                          (d._yoyoEase ||
                                              (!0 !== p || d._initted
                                                  ? (d._yoyoEase = p =
                                                        !0 === p
                                                            ? d._ease
                                                            : p instanceof Ease
                                                            ? p
                                                            : Ease.map[p])
                                                  : ((p = d.vars.ease),
                                                    (d._yoyoEase = p =
                                                        p
                                                            ? p instanceof Ease
                                                                ? p
                                                                : "function" ==
                                                                  typeof p
                                                                ? new Ease(
                                                                      p,
                                                                      d.vars.easeParams
                                                                  )
                                                                : Ease.map[p] ||
                                                                  i.defaultEase
                                                            : i.defaultEase))),
                                          (d.ratio = p
                                              ? 1 -
                                                p.getRatio((x - d._time) / x)
                                              : 0))),
                                  d._time > x
                                      ? (d._time = x)
                                      : d._time < 0 && (d._time = 0)),
                              d._easeType && !p
                                  ? ((u = d._time / x),
                                    (1 === (_ = d._easeType) ||
                                        (3 === _ && 0.5 <= u)) &&
                                        (u = 1 - u),
                                    3 === _ && (u *= 2),
                                    1 === (f = d._easePower)
                                        ? (u *= u)
                                        : 2 === f
                                        ? (u *= u * u)
                                        : 3 === f
                                        ? (u *= u * u * u)
                                        : 4 === f && (u *= u * u * u * u),
                                    (d.ratio =
                                        1 === _
                                            ? 1 - u
                                            : 2 === _
                                            ? u
                                            : d._time / x < 0.5
                                            ? u / 2
                                            : 1 - u / 2))
                                  : p ||
                                    (d.ratio = d._ease.getRatio(d._time / x))),
                        g !== d._time || r || v !== d._cycle)
                    ) {
                        if (!d._initted) {
                            if ((d._init(), !d._initted || d._gc)) return;
                            if (
                                !r &&
                                d._firstPT &&
                                ((!1 !== d.vars.lazy && d._duration) ||
                                    (d.vars.lazy && !d._duration))
                            )
                                return (
                                    (d._time = g),
                                    (d._totalTime = y),
                                    (d._rawPrevTime = T),
                                    (d._cycle = v),
                                    l.lazyTweens.push(d),
                                    void (d._lazy = [t, e])
                                );
                            !d._time || n || p
                                ? n &&
                                  this._ease._calcEnd &&
                                  !p &&
                                  (d.ratio = d._ease.getRatio(
                                      0 === d._time ? 0 : 1
                                  ))
                                : (d.ratio = d._ease.getRatio(d._time / x));
                        }
                        for (
                            !1 !== d._lazy && (d._lazy = !1),
                                d._active ||
                                    (!d._paused &&
                                        d._time !== g &&
                                        0 <= t &&
                                        (d._active = !0)),
                                0 === y &&
                                    (2 === d._initted && 0 < t && d._init(),
                                    d._startAt &&
                                        (0 <= t
                                            ? d._startAt.render(t, !0, r)
                                            : (s = s || "_dummyGS")),
                                    !d.vars.onStart ||
                                        (0 === d._totalTime && 0 !== x) ||
                                        e ||
                                        d._callback("onStart")),
                                a = d._firstPT;
                            a;

                        )
                            a.f
                                ? a.t[a.p](a.c * d.ratio + a.s)
                                : (a.t[a.p] = a.c * d.ratio + a.s),
                                (a = a._next);
                        d._onUpdate &&
                            (t < 0 &&
                                d._startAt &&
                                d._startTime &&
                                d._startAt.render(t, !0, r),
                            e ||
                                (d._totalTime === y && !s) ||
                                d._callback("onUpdate")),
                            d._cycle !== v &&
                                (e ||
                                    d._gc ||
                                    (d.vars.onRepeat &&
                                        d._callback("onRepeat"))),
                            !s ||
                                (d._gc && !r) ||
                                (t < 0 &&
                                    d._startAt &&
                                    !d._onUpdate &&
                                    d._startTime &&
                                    d._startAt.render(t, !0, r),
                                n &&
                                    (d._timeline.autoRemoveChildren &&
                                        d._enabled(!1, !1),
                                    (d._active = !1)),
                                !e && d.vars[s] && d._callback(s),
                                0 === x &&
                                    d._rawPrevTime === o &&
                                    c !== o &&
                                    (d._rawPrevTime = 0));
                    } else
                        y !== d._totalTime &&
                            d._onUpdate &&
                            (e || d._callback("onUpdate"));
                }),
                (a.to = function (t, e, i) {
                    return new a(t, e, i);
                }),
                (a.from = function (t, e, i) {
                    return (
                        (i.runBackwards = !0),
                        (i.immediateRender = 0 != i.immediateRender),
                        new a(t, e, i)
                    );
                }),
                (a.fromTo = function (t, e, i, r) {
                    return (
                        (r.startAt = i),
                        (r.immediateRender =
                            0 != r.immediateRender && 0 != i.immediateRender),
                        new a(t, e, r)
                    );
                }),
                (a.staggerTo = a.allTo =
                    function (t, e, o, l, _, c, p) {
                        var d,
                            m,
                            g,
                            y,
                            v = [],
                            x = s(o.stagger || l),
                            T = o.cycle,
                            w = (o.startAt || f).cycle;
                        for (
                            u(t) ||
                                ("string" == typeof t &&
                                    (t = i.selector(t) || t),
                                h(t) && (t = r(t))),
                                d = (t = t || []).length - 1,
                                g = 0;
                            g <= d;
                            g++
                        ) {
                            for (y in ((m = {}), o)) m[y] = o[y];
                            if (
                                (T &&
                                    (n(m, t, g),
                                    null != m.duration &&
                                        ((e = m.duration), delete m.duration)),
                                w)
                            ) {
                                for (y in ((w = m.startAt = {}), o.startAt))
                                    w[y] = o.startAt[y];
                                n(m.startAt, t, g);
                            }
                            (m.delay = x(g, t[g], t) + (m.delay || 0)),
                                g === d &&
                                    _ &&
                                    (m.onComplete = function () {
                                        o.onComplete &&
                                            o.onComplete.apply(
                                                o.onCompleteScope || this,
                                                arguments
                                            ),
                                            _.apply(
                                                p || o.callbackScope || this,
                                                c || f
                                            );
                                    }),
                                (v[g] = new a(t[g], e, m));
                        }
                        return v;
                    }),
                (a.staggerFrom = a.allFrom =
                    function (t, e, i, r, n, s, o) {
                        return (
                            (i.runBackwards = !0),
                            (i.immediateRender = 0 != i.immediateRender),
                            a.staggerTo(t, e, i, r, n, s, o)
                        );
                    }),
                (a.staggerFromTo = a.allFromTo =
                    function (t, e, i, r, n, s, o, l) {
                        return (
                            (r.startAt = i),
                            (r.immediateRender =
                                0 != r.immediateRender &&
                                0 != i.immediateRender),
                            a.staggerTo(t, e, r, n, s, o, l)
                        );
                    }),
                (a.delayedCall = function (t, e, i, r, n) {
                    return new a(e, 0, {
                        delay: t,
                        onComplete: e,
                        onCompleteParams: i,
                        callbackScope: r,
                        onReverseComplete: e,
                        onReverseCompleteParams: i,
                        immediateRender: !1,
                        useFrames: n,
                        overwrite: 0,
                    });
                }),
                (a.set = function (t, e) {
                    return new a(t, 0, e);
                }),
                (a.isTweening = function (t) {
                    return 0 < i.getTweensOf(t, !0).length;
                });
            var c = function (t, e) {
                    for (var r = [], n = 0, s = t._first; s; )
                        s instanceof i
                            ? (r[n++] = s)
                            : (e && (r[n++] = s),
                              (n = (r = r.concat(c(s, e))).length)),
                            (s = s._next);
                    return r;
                },
                p = (a.getAllTweens = function (e) {
                    return c(t._rootTimeline, e).concat(
                        c(t._rootFramesTimeline, e)
                    );
                });
            function d(t, i, r, n) {
                (i = !1 !== i), (r = !1 !== r);
                for (
                    var s,
                        a,
                        o = p((n = !1 !== n)),
                        l = i && r && n,
                        h = o.length;
                    -1 < --h;

                )
                    (a = o[h]),
                        (l ||
                            a instanceof e ||
                            ((s = a.target === a.vars.onComplete) && r) ||
                            (i && !s)) &&
                            a.paused(t);
            }
            return (
                (a.killAll = function (t, i, r, n) {
                    null == i && (i = !0), null == r && (r = !0);
                    var s,
                        a,
                        o,
                        l = p(0 != n),
                        h = l.length,
                        u = i && r && n;
                    for (o = 0; o < h; o++)
                        (a = l[o]),
                            (u ||
                                a instanceof e ||
                                ((s = a.target === a.vars.onComplete) && r) ||
                                (i && !s)) &&
                                (t
                                    ? a.totalTime(
                                          a._reversed ? 0 : a.totalDuration()
                                      )
                                    : a._enabled(!1, !1));
                }),
                (a.killChildTweensOf = function (t, e) {
                    if (null != t) {
                        var n,
                            s,
                            o,
                            _,
                            f,
                            c = l.tweenLookup;
                        if (
                            ("string" == typeof t && (t = i.selector(t) || t),
                            h(t) && (t = r(t)),
                            u(t))
                        )
                            for (_ = t.length; -1 < --_; )
                                a.killChildTweensOf(t[_], e);
                        else {
                            for (o in ((n = []), c))
                                for (s = c[o].target.parentNode; s; )
                                    s === t && (n = n.concat(c[o].tweens)),
                                        (s = s.parentNode);
                            for (f = n.length, _ = 0; _ < f; _++)
                                e && n[_].totalTime(n[_].totalDuration()),
                                    n[_]._enabled(!1, !1);
                        }
                    }
                }),
                (a.pauseAll = function (t, e, i) {
                    d(!0, t, e, i);
                }),
                (a.resumeAll = function (t, e, i) {
                    d(!1, t, e, i);
                }),
                (a.globalTimeScale = function (e) {
                    var r = t._rootTimeline,
                        n = i.ticker.time;
                    return arguments.length
                        ? ((e = e || o),
                          (r._startTime =
                              n - ((n - r._startTime) * r._timeScale) / e),
                          (r = t._rootFramesTimeline),
                          (n = i.ticker.frame),
                          (r._startTime =
                              n - ((n - r._startTime) * r._timeScale) / e),
                          (r._timeScale = t._rootTimeline._timeScale = e),
                          e)
                        : r._timeScale;
                }),
                (_.progress = function (t, e) {
                    return arguments.length
                        ? this.totalTime(
                              this.duration() *
                                  (this._yoyo && 0 != (1 & this._cycle)
                                      ? 1 - t
                                      : t) +
                                  this._cycle *
                                      (this._duration + this._repeatDelay),
                              e
                          )
                        : this.duration()
                        ? this._time / this._duration
                        : this.ratio;
                }),
                (_.totalProgress = function (t, e) {
                    return arguments.length
                        ? this.totalTime(this.totalDuration() * t, e)
                        : this._totalTime / this.totalDuration();
                }),
                (_.time = function (t, e) {
                    if (!arguments.length) return this._time;
                    this._dirty && this.totalDuration();
                    var i = this._duration,
                        r = this._cycle,
                        n = r * (i + this._repeatDelay);
                    return (
                        i < t && (t = i),
                        this.totalTime(
                            this._yoyo && 1 & r
                                ? i - t + n
                                : this._repeat
                                ? t + n
                                : t,
                            e
                        )
                    );
                }),
                (_.duration = function (e) {
                    return arguments.length
                        ? t.prototype.duration.call(this, e)
                        : this._duration;
                }),
                (_.totalDuration = function (t) {
                    return arguments.length
                        ? -1 === this._repeat
                            ? this
                            : this.duration(
                                  (t - this._repeat * this._repeatDelay) /
                                      (this._repeat + 1)
                              )
                        : (this._dirty &&
                              ((this._totalDuration =
                                  -1 === this._repeat
                                      ? 999999999999
                                      : this._duration * (this._repeat + 1) +
                                        this._repeatDelay * this._repeat),
                              (this._dirty = !1)),
                          this._totalDuration);
                }),
                (_.repeat = function (t) {
                    return arguments.length
                        ? ((this._repeat = t), this._uncache(!0))
                        : this._repeat;
                }),
                (_.repeatDelay = function (t) {
                    return arguments.length
                        ? ((this._repeatDelay = t), this._uncache(!0))
                        : this._repeatDelay;
                }),
                (_.yoyo = function (t) {
                    return arguments.length
                        ? ((this._yoyo = t), this)
                        : this._yoyo;
                }),
                a
            );
        },
        !0
    ),
        _gsScope._gsDefine(
            "TimelineLite",
            ["core.Animation", "core.SimpleTimeline", "TweenLite"],
            function (t, e, i) {
                function r(t) {
                    e.call(this, t);
                    var i,
                        r,
                        n = this,
                        s = n.vars;
                    for (r in ((n._labels = {}),
                    (n.autoRemoveChildren = !!s.autoRemoveChildren),
                    (n.smoothChildTiming = !!s.smoothChildTiming),
                    (n._sortChildren = !0),
                    (n._onUpdate = s.onUpdate),
                    s))
                        (i = s[r]),
                            f(i) &&
                                -1 !== i.join("").indexOf("{self}") &&
                                (s[r] = n._swapSelfInParams(i));
                    f(s.tweens) && n.add(s.tweens, 0, s.align, s.stagger);
                }
                function n(t) {
                    var e,
                        i = {};
                    for (e in t) i[e] = t[e];
                    return i;
                }
                function s(t, e, i) {
                    var r,
                        n,
                        s = t.cycle;
                    for (r in s)
                        (n = s[r]),
                            (t[r] =
                                "function" == typeof n
                                    ? n(i, e[i], e)
                                    : n[i % n.length]);
                    delete t.cycle;
                }
                function a(t, e, i, r) {
                    var n = "immediateRender";
                    return n in e || (e[n] = !((i && !1 === i[n]) || r)), e;
                }
                function o(t) {
                    if ("function" == typeof t) return t;
                    var e = "object" == typeof t ? t : { each: t },
                        i = e.ease,
                        r = e.from || 0,
                        n = e.base || 0,
                        s = {},
                        a = isNaN(r),
                        o = e.axis,
                        l = { center: 0.5, end: 1 }[r] || 0;
                    return function (t, h, u) {
                        var _,
                            f,
                            c,
                            p,
                            d,
                            m,
                            g,
                            y,
                            v,
                            x = (u || e).length,
                            T = s[x];
                        if (!T) {
                            if (
                                !(v =
                                    "auto" === e.grid
                                        ? 0
                                        : (e.grid || [1 / 0])[0])
                            ) {
                                for (
                                    g = -1 / 0;
                                    g <
                                        (g =
                                            u[v++].getBoundingClientRect()
                                                .left) && v < x;

                                );
                                v--;
                            }
                            for (
                                T = s[x] = [],
                                    _ = a ? Math.min(v, x) * l - 0.5 : r % v,
                                    f = a ? (x * l) / v - 0.5 : (r / v) | 0,
                                    y = 1 / (g = 0),
                                    m = 0;
                                m < x;
                                m++
                            )
                                (c = (m % v) - _),
                                    (p = f - ((m / v) | 0)),
                                    (T[m] = d =
                                        o
                                            ? Math.abs("y" === o ? p : c)
                                            : Math.sqrt(c * c + p * p)),
                                    g < d && (g = d),
                                    d < y && (y = d);
                            (T.max = g - y),
                                (T.min = y),
                                (T.v = x =
                                    e.amount ||
                                    e.each *
                                        (x < v
                                            ? x - 1
                                            : o
                                            ? "y" === o
                                                ? x / v
                                                : v
                                            : Math.max(v, x / v)) ||
                                    0),
                                (T.b = x < 0 ? n - x : n);
                        }
                        return (
                            (x = (T[t] - T.min) / T.max),
                            T.b + (i ? i.getRatio(x) : x) * T.v
                        );
                    };
                }
                var l = 1e-8,
                    h = i._internals,
                    u = (r._internals = {}),
                    _ = h.isSelector,
                    f = h.isArray,
                    c = h.lazyTweens,
                    p = h.lazyRender,
                    d = _gsScope._gsDefine.globals,
                    m = (u.pauseCallback = function () {}),
                    g = (r.prototype = new e());
                return (
                    (r.version = "2.1.3"),
                    (r.distribute = o),
                    (g.constructor = r),
                    (g.kill()._gc = g._forcingPlayhead = g._hasPause = !1),
                    (g.to = function (t, e, r, n) {
                        var s = (r.repeat && d.TweenMax) || i;
                        return e
                            ? this.add(new s(t, e, r), n)
                            : this.set(t, r, n);
                    }),
                    (g.from = function (t, e, r, n) {
                        return this.add(
                            ((r.repeat && d.TweenMax) || i).from(t, e, a(0, r)),
                            n
                        );
                    }),
                    (g.fromTo = function (t, e, r, n, s) {
                        var o = (n.repeat && d.TweenMax) || i;
                        return (
                            (n = a(0, n, r)),
                            e
                                ? this.add(o.fromTo(t, e, r, n), s)
                                : this.set(t, n, s)
                        );
                    }),
                    (g.staggerTo = function (t, e, a, l, h, u, f, c) {
                        var p,
                            d,
                            m = new r({
                                onComplete: u,
                                onCompleteParams: f,
                                callbackScope: c,
                                smoothChildTiming: this.smoothChildTiming,
                            }),
                            g = o(a.stagger || l),
                            y = a.startAt,
                            v = a.cycle;
                        for (
                            "string" == typeof t && (t = i.selector(t) || t),
                                _((t = t || [])) &&
                                    (t = (function (t) {
                                        var e,
                                            i = [],
                                            r = t.length;
                                        for (e = 0; e !== r; i.push(t[e++]));
                                        return i;
                                    })(t)),
                                d = 0;
                            d < t.length;
                            d++
                        )
                            (p = n(a)),
                                y &&
                                    ((p.startAt = n(y)),
                                    y.cycle && s(p.startAt, t, d)),
                                v &&
                                    (s(p, t, d),
                                    null != p.duration &&
                                        ((e = p.duration), delete p.duration)),
                                m.to(t[d], e, p, g(d, t[d], t));
                        return this.add(m, h);
                    }),
                    (g.staggerFrom = function (t, e, i, r, n, s, o, l) {
                        return (
                            (i.runBackwards = !0),
                            this.staggerTo(t, e, a(0, i), r, n, s, o, l)
                        );
                    }),
                    (g.staggerFromTo = function (t, e, i, r, n, s, o, l, h) {
                        return (
                            (r.startAt = i),
                            this.staggerTo(t, e, a(0, r, i), n, s, o, l, h)
                        );
                    }),
                    (g.call = function (t, e, r, n) {
                        return this.add(i.delayedCall(0, t, e, r), n);
                    }),
                    (g.set = function (t, e, r) {
                        return this.add(new i(t, 0, a(0, e, null, !0)), r);
                    }),
                    (r.exportRoot = function (t, e) {
                        null == (t = t || {}).smoothChildTiming &&
                            (t.smoothChildTiming = !0);
                        var n,
                            s,
                            a,
                            o,
                            l = new r(t),
                            h = l._timeline;
                        for (
                            null == e && (e = !0),
                                h._remove(l, !0),
                                l._startTime = 0,
                                l._rawPrevTime =
                                    l._time =
                                    l._totalTime =
                                        h._time,
                                a = h._first;
                            a;

                        )
                            (o = a._next),
                                (e &&
                                    a instanceof i &&
                                    a.target === a.vars.onComplete) ||
                                    ((s = a._startTime - a._delay) < 0 &&
                                        (n = 1),
                                    l.add(a, s)),
                                (a = o);
                        return h.add(l, 0), n && l.totalDuration(), l;
                    }),
                    (g.add = function (n, s, a, o) {
                        var l,
                            h,
                            u,
                            _,
                            c,
                            p,
                            d = this;
                        if (
                            ("number" != typeof s &&
                                (s = d._parseTimeOrLabel(s, 0, !0, n)),
                            !(n instanceof t))
                        ) {
                            if (n instanceof Array || (n && n.push && f(n))) {
                                for (
                                    a = a || "normal",
                                        o = o || 0,
                                        l = s,
                                        h = n.length,
                                        u = 0;
                                    u < h;
                                    u++
                                )
                                    f((_ = n[u])) && (_ = new r({ tweens: _ })),
                                        d.add(_, l),
                                        "string" != typeof _ &&
                                            "function" != typeof _ &&
                                            ("sequence" === a
                                                ? (l =
                                                      _._startTime +
                                                      _.totalDuration() /
                                                          _._timeScale)
                                                : "start" === a &&
                                                  (_._startTime -= _.delay())),
                                        (l += o);
                                return d._uncache(!0);
                            }
                            if ("string" == typeof n) return d.addLabel(n, s);
                            if ("function" != typeof n)
                                throw (
                                    "Cannot add " +
                                    n +
                                    " into the timeline; it is not a tween, timeline, function, or string."
                                );
                            n = i.delayedCall(0, n);
                        }
                        if (
                            (e.prototype.add.call(d, n, s),
                            (n._time || (!n._duration && n._initted)) &&
                                ((l =
                                    (d.rawTime() - n._startTime) *
                                    n._timeScale),
                                (!n._duration ||
                                    1e-5 <
                                        Math.abs(
                                            Math.max(
                                                0,
                                                Math.min(n.totalDuration(), l)
                                            )
                                        ) -
                                            n._totalTime) &&
                                    n.render(l, !1, !1)),
                            (d._gc || d._time === d._duration) &&
                                !d._paused &&
                                d._duration < d.duration())
                        )
                            for (
                                p = (c = d).rawTime() > n._startTime;
                                c._timeline;

                            )
                                p && c._timeline.smoothChildTiming
                                    ? c.totalTime(c._totalTime, !0)
                                    : c._gc && c._enabled(!0, !1),
                                    (c = c._timeline);
                        return d;
                    }),
                    (g.remove = function (e) {
                        if (e instanceof t) {
                            this._remove(e, !1);
                            var i = (e._timeline = e.vars.useFrames
                                ? t._rootFramesTimeline
                                : t._rootTimeline);
                            return (
                                (e._startTime =
                                    (e._paused ? e._pauseTime : i._time) -
                                    (e._reversed
                                        ? e.totalDuration() - e._totalTime
                                        : e._totalTime) /
                                        e._timeScale),
                                this
                            );
                        }
                        if (e instanceof Array || (e && e.push && f(e))) {
                            for (var r = e.length; -1 < --r; )
                                this.remove(e[r]);
                            return this;
                        }
                        return "string" == typeof e
                            ? this.removeLabel(e)
                            : this.kill(null, e);
                    }),
                    (g._remove = function (t, i) {
                        return (
                            e.prototype._remove.call(this, t, i),
                            this._last
                                ? this._time > this.duration() &&
                                  ((this._time = this._duration),
                                  (this._totalTime = this._totalDuration))
                                : (this._time =
                                      this._totalTime =
                                      this._duration =
                                      this._totalDuration =
                                          0),
                            this
                        );
                    }),
                    (g.append = function (t, e) {
                        return this.add(
                            t,
                            this._parseTimeOrLabel(null, e, !0, t)
                        );
                    }),
                    (g.insert = g.insertMultiple =
                        function (t, e, i, r) {
                            return this.add(t, e || 0, i, r);
                        }),
                    (g.appendMultiple = function (t, e, i, r) {
                        return this.add(
                            t,
                            this._parseTimeOrLabel(null, e, !0, t),
                            i,
                            r
                        );
                    }),
                    (g.addLabel = function (t, e) {
                        return (
                            (this._labels[t] = this._parseTimeOrLabel(e)), this
                        );
                    }),
                    (g.addPause = function (t, e, r, n) {
                        var s = i.delayedCall(0, m, r, n || this);
                        return (
                            (s.vars.onComplete = s.vars.onReverseComplete = e),
                            (s.data = "isPause"),
                            (this._hasPause = !0),
                            this.add(s, t)
                        );
                    }),
                    (g.removeLabel = function (t) {
                        return delete this._labels[t], this;
                    }),
                    (g.getLabelTime = function (t) {
                        return null != this._labels[t] ? this._labels[t] : -1;
                    }),
                    (g._parseTimeOrLabel = function (e, i, r, n) {
                        var s, a;
                        if (n instanceof t && n.timeline === this)
                            this.remove(n);
                        else if (n && (n instanceof Array || (n.push && f(n))))
                            for (a = n.length; -1 < --a; )
                                n[a] instanceof t &&
                                    n[a].timeline === this &&
                                    this.remove(n[a]);
                        if (
                            ((s =
                                "number" != typeof e || i
                                    ? 99999999999 < this.duration()
                                        ? this.recent().endTime(!1)
                                        : this._duration
                                    : 0),
                            "string" == typeof i)
                        )
                            return this._parseTimeOrLabel(
                                i,
                                r &&
                                    "number" == typeof e &&
                                    null == this._labels[i]
                                    ? e - s
                                    : 0,
                                r
                            );
                        if (
                            ((i = i || 0),
                            "string" != typeof e ||
                                (!isNaN(e) && null == this._labels[e]))
                        )
                            null == e && (e = s);
                        else {
                            if (-1 === (a = e.indexOf("=")))
                                return null == this._labels[e]
                                    ? r
                                        ? (this._labels[e] = s + i)
                                        : i
                                    : this._labels[e] + i;
                            (i =
                                parseInt(e.charAt(a - 1) + "1", 10) *
                                Number(e.substr(a + 1))),
                                (e =
                                    1 < a
                                        ? this._parseTimeOrLabel(
                                              e.substr(0, a - 1),
                                              0,
                                              r
                                          )
                                        : s);
                        }
                        return Number(e) + i;
                    }),
                    (g.seek = function (t, e) {
                        return this.totalTime(
                            "number" == typeof t
                                ? t
                                : this._parseTimeOrLabel(t),
                            !1 !== e
                        );
                    }),
                    (g.stop = function () {
                        return this.paused(!0);
                    }),
                    (g.gotoAndPlay = function (t, e) {
                        return this.play(t, e);
                    }),
                    (g.gotoAndStop = function (t, e) {
                        return this.pause(t, e);
                    }),
                    (g.render = function (t, e, i) {
                        this._gc && this._enabled(!0, !1);
                        var r,
                            n,
                            s,
                            a,
                            o,
                            h,
                            u,
                            _,
                            f = this,
                            d = f._time,
                            m = f._dirty ? f.totalDuration() : f._totalDuration,
                            g = f._startTime,
                            y = f._timeScale,
                            v = f._paused;
                        if (
                            (d !== f._time && (t += f._time - d),
                            f._hasPause && !f._forcingPlayhead && !e)
                        ) {
                            if (d < t)
                                for (
                                    r = f._first;
                                    r && r._startTime <= t && !h;

                                )
                                    r._duration ||
                                        "isPause" !== r.data ||
                                        r.ratio ||
                                        (0 === r._startTime &&
                                            0 === f._rawPrevTime) ||
                                        (h = r),
                                        (r = r._next);
                            else
                                for (
                                    r = f._last;
                                    r && r._startTime >= t && !h;

                                )
                                    r._duration ||
                                        ("isPause" === r.data &&
                                            0 < r._rawPrevTime &&
                                            (h = r)),
                                        (r = r._prev);
                            h &&
                                ((f._time = f._totalTime = t = h._startTime),
                                (_ =
                                    f._startTime +
                                    (f._reversed ? f._duration - t : t) /
                                        f._timeScale));
                        }
                        if (m - l <= t && 0 <= t)
                            (f._totalTime = f._time = m),
                                f._reversed ||
                                    f._hasPausedChild() ||
                                    ((n = !0),
                                    (a = "onComplete"),
                                    (o = !!f._timeline.autoRemoveChildren),
                                    0 === f._duration &&
                                        ((t <= 0 && -l <= t) ||
                                            f._rawPrevTime < 0 ||
                                            f._rawPrevTime === l) &&
                                        f._rawPrevTime !== t &&
                                        f._first &&
                                        ((o = !0),
                                        f._rawPrevTime > l &&
                                            (a = "onReverseComplete"))),
                                (f._rawPrevTime =
                                    f._duration ||
                                    !e ||
                                    t ||
                                    f._rawPrevTime === t
                                        ? t
                                        : l),
                                (t = m + 1e-4);
                        else if (t < l)
                            if (
                                ((f._totalTime = f._time = 0),
                                -l < t && (t = 0),
                                (0 !== d ||
                                    (0 === f._duration &&
                                        f._rawPrevTime !== l &&
                                        (0 < f._rawPrevTime ||
                                            (t < 0 && 0 <= f._rawPrevTime)))) &&
                                    ((a = "onReverseComplete"),
                                    (n = f._reversed)),
                                t < 0)
                            )
                                (f._active = !1),
                                    f._timeline.autoRemoveChildren &&
                                    f._reversed
                                        ? ((o = n = !0),
                                          (a = "onReverseComplete"))
                                        : 0 <= f._rawPrevTime &&
                                          f._first &&
                                          (o = !0),
                                    (f._rawPrevTime = t);
                            else {
                                if (
                                    ((f._rawPrevTime =
                                        f._duration ||
                                        !e ||
                                        t ||
                                        f._rawPrevTime === t
                                            ? t
                                            : l),
                                    0 === t && n)
                                )
                                    for (
                                        r = f._first;
                                        r && 0 === r._startTime;

                                    )
                                        r._duration || (n = !1), (r = r._next);
                                (t = 0), f._initted || (o = !0);
                            }
                        else f._totalTime = f._time = f._rawPrevTime = t;
                        if ((f._time !== d && f._first) || i || o || h) {
                            if (
                                (f._initted || (f._initted = !0),
                                f._active ||
                                    (!f._paused &&
                                        f._time !== d &&
                                        0 < t &&
                                        (f._active = !0)),
                                0 === d &&
                                    f.vars.onStart &&
                                    ((0 === f._time && f._duration) ||
                                        e ||
                                        f._callback("onStart")),
                                d <= (u = f._time))
                            )
                                for (
                                    r = f._first;
                                    r &&
                                    ((s = r._next),
                                    u === f._time && (!f._paused || v));

                                )
                                    (r._active ||
                                        (r._startTime <= u &&
                                            !r._paused &&
                                            !r._gc)) &&
                                        (h === r &&
                                            (f.pause(), (f._pauseTime = _)),
                                        r._reversed
                                            ? r.render(
                                                  (r._dirty
                                                      ? r.totalDuration()
                                                      : r._totalDuration) -
                                                      (t - r._startTime) *
                                                          r._timeScale,
                                                  e,
                                                  i
                                              )
                                            : r.render(
                                                  (t - r._startTime) *
                                                      r._timeScale,
                                                  e,
                                                  i
                                              )),
                                        (r = s);
                            else
                                for (
                                    r = f._last;
                                    r &&
                                    ((s = r._prev),
                                    u === f._time && (!f._paused || v));

                                ) {
                                    if (
                                        r._active ||
                                        (r._startTime <= d &&
                                            !r._paused &&
                                            !r._gc)
                                    ) {
                                        if (h === r) {
                                            for (
                                                h = r._prev;
                                                h && h.endTime() > f._time;

                                            )
                                                h.render(
                                                    h._reversed
                                                        ? h.totalDuration() -
                                                              (t -
                                                                  h._startTime) *
                                                                  h._timeScale
                                                        : (t - h._startTime) *
                                                              h._timeScale,
                                                    e,
                                                    i
                                                ),
                                                    (h = h._prev);
                                            (h = null),
                                                f.pause(),
                                                (f._pauseTime = _);
                                        }
                                        r._reversed
                                            ? r.render(
                                                  (r._dirty
                                                      ? r.totalDuration()
                                                      : r._totalDuration) -
                                                      (t - r._startTime) *
                                                          r._timeScale,
                                                  e,
                                                  i
                                              )
                                            : r.render(
                                                  (t - r._startTime) *
                                                      r._timeScale,
                                                  e,
                                                  i
                                              );
                                    }
                                    r = s;
                                }
                            f._onUpdate &&
                                (e ||
                                    (c.length && p(), f._callback("onUpdate"))),
                                a &&
                                    (f._gc ||
                                        (g !== f._startTime &&
                                            y === f._timeScale) ||
                                        !(
                                            0 === f._time ||
                                            m >= f.totalDuration()
                                        ) ||
                                        (n &&
                                            (c.length && p(),
                                            f._timeline.autoRemoveChildren &&
                                                f._enabled(!1, !1),
                                            (f._active = !1)),
                                        !e && f.vars[a] && f._callback(a)));
                        }
                    }),
                    (g._hasPausedChild = function () {
                        for (var t = this._first; t; ) {
                            if (
                                t._paused ||
                                (t instanceof r && t._hasPausedChild())
                            )
                                return !0;
                            t = t._next;
                        }
                        return !1;
                    }),
                    (g.getChildren = function (t, e, r, n) {
                        n = n || -9999999999;
                        for (var s = [], a = this._first, o = 0; a; )
                            a._startTime < n ||
                                (a instanceof i
                                    ? !1 !== e && (s[o++] = a)
                                    : (!1 !== r && (s[o++] = a),
                                      !1 !== t &&
                                          (o = (s = s.concat(
                                              a.getChildren(!0, e, r)
                                          )).length))),
                                (a = a._next);
                        return s;
                    }),
                    (g.getTweensOf = function (t, e) {
                        var r,
                            n,
                            s = this._gc,
                            a = [],
                            o = 0;
                        for (
                            s && this._enabled(!0, !0),
                                n = (r = i.getTweensOf(t)).length;
                            -1 < --n;

                        )
                            (r[n].timeline === this ||
                                (e && this._contains(r[n]))) &&
                                (a[o++] = r[n]);
                        return s && this._enabled(!1, !0), a;
                    }),
                    (g.recent = function () {
                        return this._recent;
                    }),
                    (g._contains = function (t) {
                        for (var e = t.timeline; e; ) {
                            if (e === this) return !0;
                            e = e.timeline;
                        }
                        return !1;
                    }),
                    (g.shiftChildren = function (t, e, i) {
                        i = i || 0;
                        for (var r, n = this._first, s = this._labels; n; )
                            n._startTime >= i && (n._startTime += t),
                                (n = n._next);
                        if (e) for (r in s) s[r] >= i && (s[r] += t);
                        return this._uncache(!0);
                    }),
                    (g._kill = function (t, e) {
                        if (!t && !e) return this._enabled(!1, !1);
                        for (
                            var i = e
                                    ? this.getTweensOf(e)
                                    : this.getChildren(!0, !0, !1),
                                r = i.length,
                                n = !1;
                            -1 < --r;

                        )
                            i[r]._kill(t, e) && (n = !0);
                        return n;
                    }),
                    (g.clear = function (t) {
                        var e = this.getChildren(!1, !0, !0),
                            i = e.length;
                        for (this._time = this._totalTime = 0; -1 < --i; )
                            e[i]._enabled(!1, !1);
                        return (
                            !1 !== t && (this._labels = {}), this._uncache(!0)
                        );
                    }),
                    (g.invalidate = function () {
                        for (var e = this._first; e; )
                            e.invalidate(), (e = e._next);
                        return t.prototype.invalidate.call(this);
                    }),
                    (g._enabled = function (t, i) {
                        if (t === this._gc)
                            for (var r = this._first; r; )
                                r._enabled(t, !0), (r = r._next);
                        return e.prototype._enabled.call(this, t, i);
                    }),
                    (g.totalTime = function (e, i, r) {
                        this._forcingPlayhead = !0;
                        var n = t.prototype.totalTime.apply(this, arguments);
                        return (this._forcingPlayhead = !1), n;
                    }),
                    (g.duration = function (t) {
                        return arguments.length
                            ? (0 !== this.duration() &&
                                  0 !== t &&
                                  this.timeScale(this._duration / t),
                              this)
                            : (this._dirty && this.totalDuration(),
                              this._duration);
                    }),
                    (g.totalDuration = function (t) {
                        if (arguments.length)
                            return t && this.totalDuration()
                                ? this.timeScale(this._totalDuration / t)
                                : this;
                        if (this._dirty) {
                            for (
                                var e,
                                    i,
                                    r = 0,
                                    n = this,
                                    s = n._last,
                                    a = 999999999999;
                                s;

                            )
                                (e = s._prev),
                                    s._dirty && s.totalDuration(),
                                    s._startTime > a &&
                                    n._sortChildren &&
                                    !s._paused &&
                                    !n._calculatingDuration
                                        ? ((n._calculatingDuration = 1),
                                          n.add(s, s._startTime - s._delay),
                                          (n._calculatingDuration = 0))
                                        : (a = s._startTime),
                                    s._startTime < 0 &&
                                        !s._paused &&
                                        ((r -= s._startTime),
                                        n._timeline.smoothChildTiming &&
                                            ((n._startTime +=
                                                s._startTime / n._timeScale),
                                            (n._time -= s._startTime),
                                            (n._totalTime -= s._startTime),
                                            (n._rawPrevTime -= s._startTime)),
                                        n.shiftChildren(
                                            -s._startTime,
                                            !1,
                                            -9999999999
                                        ),
                                        (a = 0)),
                                    r <
                                        (i =
                                            s._startTime +
                                            s._totalDuration / s._timeScale) &&
                                        (r = i),
                                    (s = e);
                            (n._duration = n._totalDuration = r),
                                (n._dirty = !1);
                        }
                        return this._totalDuration;
                    }),
                    (g.paused = function (e) {
                        if (!1 === e && this._paused)
                            for (var i = this._first; i; )
                                i._startTime === this._time &&
                                    "isPause" === i.data &&
                                    (i._rawPrevTime = 0),
                                    (i = i._next);
                        return t.prototype.paused.apply(this, arguments);
                    }),
                    (g.usesFrames = function () {
                        for (var e = this._timeline; e._timeline; )
                            e = e._timeline;
                        return e === t._rootFramesTimeline;
                    }),
                    (g.rawTime = function (t) {
                        return t &&
                            (this._paused ||
                                (this._repeat &&
                                    0 < this.time() &&
                                    this.totalProgress() < 1))
                            ? this._totalTime %
                                  (this._duration + this._repeatDelay)
                            : this._paused
                            ? this._totalTime
                            : (this._timeline.rawTime(t) - this._startTime) *
                              this._timeScale;
                    }),
                    r
                );
            },
            !0
        ),
        _gsScope._gsDefine(
            "TimelineMax",
            ["TimelineLite", "TweenLite", "easing.Ease"],
            function (t, e, i) {
                function r(e) {
                    t.call(this, e),
                        (this._repeat = this.vars.repeat || 0),
                        (this._repeatDelay = this.vars.repeatDelay || 0),
                        (this._cycle = 0),
                        (this._yoyo = !!this.vars.yoyo),
                        (this._dirty = !0);
                }
                var n = 1e-8,
                    s = e._internals,
                    a = s.lazyTweens,
                    o = s.lazyRender,
                    l = _gsScope._gsDefine.globals,
                    h = new i(null, null, 1, 0),
                    u = (r.prototype = new t());
                return (
                    (u.constructor = r),
                    (u.kill()._gc = !1),
                    (r.version = "2.1.3"),
                    (u.invalidate = function () {
                        return (
                            (this._yoyo = !!this.vars.yoyo),
                            (this._repeat = this.vars.repeat || 0),
                            (this._repeatDelay = this.vars.repeatDelay || 0),
                            this._uncache(!0),
                            t.prototype.invalidate.call(this)
                        );
                    }),
                    (u.addCallback = function (t, i, r, n) {
                        return this.add(e.delayedCall(0, t, r, n), i);
                    }),
                    (u.removeCallback = function (t, e) {
                        if (t)
                            if (null == e) this._kill(null, t);
                            else
                                for (
                                    var i = this.getTweensOf(t, !1),
                                        r = i.length,
                                        n = this._parseTimeOrLabel(e);
                                    -1 < --r;

                                )
                                    i[r]._startTime === n &&
                                        i[r]._enabled(!1, !1);
                        return this;
                    }),
                    (u.removePause = function (e) {
                        return this.removeCallback(
                            t._internals.pauseCallback,
                            e
                        );
                    }),
                    (u.tweenTo = function (t, i) {
                        i = i || {};
                        var r,
                            n,
                            s,
                            a = {
                                ease: h,
                                useFrames: this.usesFrames(),
                                immediateRender: !1,
                                lazy: !1,
                            },
                            o = (i.repeat && l.TweenMax) || e;
                        for (n in i) a[n] = i[n];
                        return (
                            (a.time = this._parseTimeOrLabel(t)),
                            (r =
                                Math.abs(Number(a.time) - this._time) /
                                    this._timeScale || 0.001),
                            (s = new o(this, r, a)),
                            (a.onStart = function () {
                                s.target.paused(!0),
                                    s.vars.time === s.target.time() ||
                                        r !== s.duration() ||
                                        s.isFromTo ||
                                        s
                                            .duration(
                                                Math.abs(
                                                    s.vars.time -
                                                        s.target.time()
                                                ) / s.target._timeScale
                                            )
                                            .render(s.time(), !0, !0),
                                    i.onStart &&
                                        i.onStart.apply(
                                            i.onStartScope ||
                                                i.callbackScope ||
                                                s,
                                            i.onStartParams || []
                                        );
                            }),
                            s
                        );
                    }),
                    (u.tweenFromTo = function (t, e, i) {
                        (i = i || {}),
                            (t = this._parseTimeOrLabel(t)),
                            (i.startAt = {
                                onComplete: this.seek,
                                onCompleteParams: [t],
                                callbackScope: this,
                            }),
                            (i.immediateRender = !1 !== i.immediateRender);
                        var r = this.tweenTo(e, i);
                        return (
                            (r.isFromTo = 1),
                            r.duration(
                                Math.abs(r.vars.time - t) / this._timeScale ||
                                    0.001
                            )
                        );
                    }),
                    (u.render = function (t, e, i) {
                        this._gc && this._enabled(!0, !1);
                        var r,
                            s,
                            l,
                            h,
                            u,
                            _,
                            f,
                            c,
                            p,
                            d = this,
                            m = d._time,
                            g = d._dirty ? d.totalDuration() : d._totalDuration,
                            y = d._duration,
                            v = d._totalTime,
                            x = d._startTime,
                            T = d._timeScale,
                            w = d._rawPrevTime,
                            b = d._paused,
                            P = d._cycle;
                        if (
                            (m !== d._time && (t += d._time - m),
                            g - n <= t && 0 <= t)
                        )
                            d._locked ||
                                ((d._totalTime = g), (d._cycle = d._repeat)),
                                d._reversed ||
                                    d._hasPausedChild() ||
                                    ((s = !0),
                                    (h = "onComplete"),
                                    (u = !!d._timeline.autoRemoveChildren),
                                    0 === d._duration &&
                                        ((t <= 0 && -n <= t) ||
                                            w < 0 ||
                                            w === n) &&
                                        w !== t &&
                                        d._first &&
                                        ((u = !0),
                                        n < w && (h = "onReverseComplete"))),
                                (d._rawPrevTime =
                                    d._duration ||
                                    !e ||
                                    t ||
                                    d._rawPrevTime === t
                                        ? t
                                        : n),
                                d._yoyo && 1 & d._cycle
                                    ? (d._time = t = 0)
                                    : (t = (d._time = y) + 1e-4);
                        else if (t < n)
                            if (
                                (d._locked || (d._totalTime = d._cycle = 0),
                                (d._time = 0),
                                -n < t && (t = 0),
                                (0 !== m ||
                                    (0 === y &&
                                        w !== n &&
                                        (0 < w || (t < 0 && 0 <= w)) &&
                                        !d._locked)) &&
                                    ((h = "onReverseComplete"),
                                    (s = d._reversed)),
                                t < 0)
                            )
                                (d._active = !1),
                                    d._timeline.autoRemoveChildren &&
                                    d._reversed
                                        ? ((u = s = !0),
                                          (h = "onReverseComplete"))
                                        : 0 <= w && d._first && (u = !0),
                                    (d._rawPrevTime = t);
                            else {
                                if (
                                    ((d._rawPrevTime =
                                        y || !e || t || d._rawPrevTime === t
                                            ? t
                                            : n),
                                    0 === t && s)
                                )
                                    for (
                                        r = d._first;
                                        r && 0 === r._startTime;

                                    )
                                        r._duration || (s = !1), (r = r._next);
                                (t = 0), d._initted || (u = !0);
                            }
                        else
                            0 === y && w < 0 && (u = !0),
                                (d._time = d._rawPrevTime = t),
                                d._locked ||
                                    ((d._totalTime = t),
                                    0 !== d._repeat &&
                                        ((_ = y + d._repeatDelay),
                                        (d._cycle = (d._totalTime / _) >> 0),
                                        d._cycle &&
                                            d._cycle === d._totalTime / _ &&
                                            v <= t &&
                                            d._cycle--,
                                        (d._time = d._totalTime - d._cycle * _),
                                        d._yoyo &&
                                            1 & d._cycle &&
                                            (d._time = y - d._time),
                                        d._time > y
                                            ? (t = (d._time = y) + 1e-4)
                                            : d._time < 0
                                            ? (d._time = t = 0)
                                            : (t = d._time)));
                        if (d._hasPause && !d._forcingPlayhead && !e) {
                            if (
                                m < (t = d._time) ||
                                (d._repeat && P !== d._cycle)
                            )
                                for (
                                    r = d._first;
                                    r && r._startTime <= t && !f;

                                )
                                    r._duration ||
                                        "isPause" !== r.data ||
                                        r.ratio ||
                                        (0 === r._startTime &&
                                            0 === d._rawPrevTime) ||
                                        (f = r),
                                        (r = r._next);
                            else
                                for (
                                    r = d._last;
                                    r && r._startTime >= t && !f;

                                )
                                    r._duration ||
                                        ("isPause" === r.data &&
                                            0 < r._rawPrevTime &&
                                            (f = r)),
                                        (r = r._prev);
                            f &&
                                ((p =
                                    d._startTime +
                                    (d._reversed
                                        ? d._duration - f._startTime
                                        : f._startTime) /
                                        d._timeScale),
                                f._startTime < y &&
                                    ((d._time =
                                        d._rawPrevTime =
                                        t =
                                            f._startTime),
                                    (d._totalTime =
                                        t +
                                        d._cycle *
                                            (d._totalDuration +
                                                d._repeatDelay))));
                        }
                        if (d._cycle !== P && !d._locked) {
                            var S = d._yoyo && 0 != (1 & P),
                                O = S === (d._yoyo && 0 != (1 & d._cycle)),
                                k = d._totalTime,
                                C = d._cycle,
                                R = d._rawPrevTime,
                                A = d._time;
                            if (
                                ((d._totalTime = P * y),
                                d._cycle < P ? (S = !S) : (d._totalTime += y),
                                (d._time = m),
                                (d._rawPrevTime = 0 === y ? w - 1e-4 : w),
                                (d._cycle = P),
                                (d._locked = !0),
                                (m = S ? 0 : y),
                                d.render(m, e, 0 === y),
                                e ||
                                    d._gc ||
                                    (d.vars.onRepeat &&
                                        ((d._cycle = C),
                                        (d._locked = !1),
                                        d._callback("onRepeat"))),
                                m !== d._time)
                            )
                                return;
                            if (
                                (O &&
                                    ((d._cycle = P),
                                    (d._locked = !0),
                                    (m = S ? y + 1e-4 : -1e-4),
                                    d.render(m, !0, !1)),
                                (d._locked = !1),
                                d._paused && !b)
                            )
                                return;
                            (d._time = A),
                                (d._totalTime = k),
                                (d._cycle = C),
                                (d._rawPrevTime = R);
                        }
                        if ((d._time !== m && d._first) || i || u || f) {
                            if (
                                (d._initted || (d._initted = !0),
                                d._active ||
                                    (!d._paused &&
                                        d._totalTime !== v &&
                                        0 < t &&
                                        (d._active = !0)),
                                0 === v &&
                                    d.vars.onStart &&
                                    ((0 === d._totalTime && d._totalDuration) ||
                                        e ||
                                        d._callback("onStart")),
                                m <= (c = d._time))
                            )
                                for (
                                    r = d._first;
                                    r &&
                                    ((l = r._next),
                                    c === d._time && (!d._paused || b));

                                )
                                    (r._active ||
                                        (r._startTime <= d._time &&
                                            !r._paused &&
                                            !r._gc)) &&
                                        (f === r &&
                                            (d.pause(), (d._pauseTime = p)),
                                        r._reversed
                                            ? r.render(
                                                  (r._dirty
                                                      ? r.totalDuration()
                                                      : r._totalDuration) -
                                                      (t - r._startTime) *
                                                          r._timeScale,
                                                  e,
                                                  i
                                              )
                                            : r.render(
                                                  (t - r._startTime) *
                                                      r._timeScale,
                                                  e,
                                                  i
                                              )),
                                        (r = l);
                            else
                                for (
                                    r = d._last;
                                    r &&
                                    ((l = r._prev),
                                    c === d._time && (!d._paused || b));

                                ) {
                                    if (
                                        r._active ||
                                        (r._startTime <= m &&
                                            !r._paused &&
                                            !r._gc)
                                    ) {
                                        if (f === r) {
                                            for (
                                                f = r._prev;
                                                f && f.endTime() > d._time;

                                            )
                                                f.render(
                                                    f._reversed
                                                        ? f.totalDuration() -
                                                              (t -
                                                                  f._startTime) *
                                                                  f._timeScale
                                                        : (t - f._startTime) *
                                                              f._timeScale,
                                                    e,
                                                    i
                                                ),
                                                    (f = f._prev);
                                            (f = null),
                                                d.pause(),
                                                (d._pauseTime = p);
                                        }
                                        r._reversed
                                            ? r.render(
                                                  (r._dirty
                                                      ? r.totalDuration()
                                                      : r._totalDuration) -
                                                      (t - r._startTime) *
                                                          r._timeScale,
                                                  e,
                                                  i
                                              )
                                            : r.render(
                                                  (t - r._startTime) *
                                                      r._timeScale,
                                                  e,
                                                  i
                                              );
                                    }
                                    r = l;
                                }
                            d._onUpdate &&
                                (e ||
                                    (a.length && o(), d._callback("onUpdate"))),
                                h &&
                                    (d._locked ||
                                        d._gc ||
                                        (x !== d._startTime &&
                                            T === d._timeScale) ||
                                        !(
                                            0 === d._time ||
                                            g >= d.totalDuration()
                                        ) ||
                                        (s &&
                                            (a.length && o(),
                                            d._timeline.autoRemoveChildren &&
                                                d._enabled(!1, !1),
                                            (d._active = !1)),
                                        !e && d.vars[h] && d._callback(h)));
                        } else
                            v !== d._totalTime &&
                                d._onUpdate &&
                                (e || d._callback("onUpdate"));
                    }),
                    (u.getActive = function (t, e, i) {
                        var r,
                            n,
                            s = [],
                            a = this.getChildren(
                                t || null == t,
                                e || null == t,
                                !!i
                            ),
                            o = 0,
                            l = a.length;
                        for (r = 0; r < l; r++)
                            (n = a[r]).isActive() && (s[o++] = n);
                        return s;
                    }),
                    (u.getLabelAfter = function (t) {
                        t || (0 !== t && (t = this._time));
                        var e,
                            i = this.getLabelsArray(),
                            r = i.length;
                        for (e = 0; e < r; e++)
                            if (i[e].time > t) return i[e].name;
                        return null;
                    }),
                    (u.getLabelBefore = function (t) {
                        null == t && (t = this._time);
                        for (
                            var e = this.getLabelsArray(), i = e.length;
                            -1 < --i;

                        )
                            if (e[i].time < t) return e[i].name;
                        return null;
                    }),
                    (u.getLabelsArray = function () {
                        var t,
                            e = [],
                            i = 0;
                        for (t in this._labels)
                            e[i++] = { time: this._labels[t], name: t };
                        return (
                            e.sort(function (t, e) {
                                return t.time - e.time;
                            }),
                            e
                        );
                    }),
                    (u.invalidate = function () {
                        return (
                            (this._locked = !1),
                            t.prototype.invalidate.call(this)
                        );
                    }),
                    (u.progress = function (t, e) {
                        return arguments.length
                            ? this.totalTime(
                                  this.duration() *
                                      (this._yoyo && 0 != (1 & this._cycle)
                                          ? 1 - t
                                          : t) +
                                      this._cycle *
                                          (this._duration + this._repeatDelay),
                                  e
                              )
                            : this._time / this.duration() || 0;
                    }),
                    (u.totalProgress = function (t, e) {
                        return arguments.length
                            ? this.totalTime(this.totalDuration() * t, e)
                            : this._totalTime / this.totalDuration() || 0;
                    }),
                    (u.totalDuration = function (e) {
                        return arguments.length
                            ? -1 !== this._repeat && e
                                ? this.timeScale(this.totalDuration() / e)
                                : this
                            : (this._dirty &&
                                  (t.prototype.totalDuration.call(this),
                                  (this._totalDuration =
                                      -1 === this._repeat
                                          ? 999999999999
                                          : this._duration *
                                                (this._repeat + 1) +
                                            this._repeatDelay * this._repeat)),
                              this._totalDuration);
                    }),
                    (u.time = function (t, e) {
                        if (!arguments.length) return this._time;
                        this._dirty && this.totalDuration();
                        var i = this._duration,
                            r = this._cycle,
                            n = r * (i + this._repeatDelay);
                        return (
                            i < t && (t = i),
                            this.totalTime(
                                this._yoyo && 1 & r
                                    ? i - t + n
                                    : this._repeat
                                    ? t + n
                                    : t,
                                e
                            )
                        );
                    }),
                    (u.repeat = function (t) {
                        return arguments.length
                            ? ((this._repeat = t), this._uncache(!0))
                            : this._repeat;
                    }),
                    (u.repeatDelay = function (t) {
                        return arguments.length
                            ? ((this._repeatDelay = t), this._uncache(!0))
                            : this._repeatDelay;
                    }),
                    (u.yoyo = function (t) {
                        return arguments.length
                            ? ((this._yoyo = t), this)
                            : this._yoyo;
                    }),
                    (u.currentLabel = function (t) {
                        return arguments.length
                            ? this.seek(t, !0)
                            : this.getLabelBefore(this._time + n);
                    }),
                    r
                );
            },
            !0
        ),
        (r = 180 / Math.PI),
        (n = []),
        (s = []),
        (a = []),
        (o = {}),
        (l = _gsScope._gsDefine.globals),
        (h = _gsScope._gsDefine.plugin({
            propName: "bezier",
            priority: -1,
            version: "1.3.9",
            API: 2,
            global: !0,
            init: function (t, e, i) {
                (this._target = t),
                    e instanceof Array && (e = { values: e }),
                    (this._func = {}),
                    (this._mod = {}),
                    (this._props = []),
                    (this._timeRes =
                        null == e.timeResolution
                            ? 6
                            : parseInt(e.timeResolution, 10));
                var r,
                    n,
                    s,
                    a,
                    o,
                    l = e.values || [],
                    h = {},
                    u = l[0],
                    f = e.autoRotate || i.vars.orientToBezier;
                for (r in ((this._autoRotate = f
                    ? f instanceof Array
                        ? f
                        : [
                              [
                                  "x",
                                  "y",
                                  "rotation",
                                  !0 === f ? 0 : Number(f) || 0,
                              ],
                          ]
                    : null),
                u))
                    this._props.push(r);
                for (s = this._props.length; -1 < --s; )
                    (r = this._props[s]),
                        this._overwriteProps.push(r),
                        (n = this._func[r] = "function" == typeof t[r]),
                        (h[r] = n
                            ? t[
                                  r.indexOf("set") ||
                                  "function" != typeof t["get" + r.substr(3)]
                                      ? r
                                      : "get" + r.substr(3)
                              ]()
                            : parseFloat(t[r])),
                        o || (h[r] !== l[0][r] && (o = h));
                if (
                    ((this._beziers =
                        "cubic" !== e.type &&
                        "quadratic" !== e.type &&
                        "soft" !== e.type
                            ? d(
                                  l,
                                  isNaN(e.curviness) ? 1 : e.curviness,
                                  !1,
                                  "thruBasic" === e.type,
                                  e.correlate,
                                  o
                              )
                            : (function (t, e, i) {
                                  var r,
                                      n,
                                      s,
                                      a,
                                      o,
                                      l,
                                      h,
                                      u,
                                      f,
                                      c,
                                      p,
                                      d = {},
                                      m = "cubic" === (e = e || "soft") ? 3 : 2,
                                      g = "soft" === e,
                                      y = [];
                                  if (
                                      (g && i && (t = [i].concat(t)),
                                      null == t || t.length < 1 + m)
                                  )
                                      throw "invalid Bezier data";
                                  for (f in t[0]) y.push(f);
                                  for (l = y.length; -1 < --l; ) {
                                      for (
                                          d[(f = y[l])] = o = [],
                                              c = 0,
                                              u = t.length,
                                              h = 0;
                                          h < u;
                                          h++
                                      )
                                          (r =
                                              null == i
                                                  ? t[h][f]
                                                  : "string" ==
                                                        typeof (p = t[h][f]) &&
                                                    "=" === p.charAt(1)
                                                  ? i[f] +
                                                    Number(
                                                        p.charAt(0) +
                                                            p.substr(2)
                                                    )
                                                  : Number(p)),
                                              g &&
                                                  1 < h &&
                                                  h < u - 1 &&
                                                  (o[c++] = (r + o[c - 2]) / 2),
                                              (o[c++] = r);
                                      for (
                                          u = c - m + 1, h = c = 0;
                                          h < u;
                                          h += m
                                      )
                                          (r = o[h]),
                                              (n = o[h + 1]),
                                              (s = o[h + 2]),
                                              (a = 2 == m ? 0 : o[h + 3]),
                                              (o[c++] = p =
                                                  3 == m
                                                      ? new _(r, n, s, a)
                                                      : new _(
                                                            r,
                                                            (2 * n + r) / 3,
                                                            (2 * n + s) / 3,
                                                            s
                                                        ));
                                      o.length = c;
                                  }
                                  return d;
                              })(l, e.type, h)),
                    (this._segCount = this._beziers[r].length),
                    this._timeRes)
                ) {
                    var c = (function (t, e) {
                        var i,
                            r,
                            n,
                            s,
                            a = [],
                            o = [],
                            l = 0,
                            h = 0,
                            u = (e = e >> 0 || 6) - 1,
                            _ = [],
                            f = [];
                        for (i in t) m(t[i], a, e);
                        for (n = a.length, r = 0; r < n; r++)
                            (l += Math.sqrt(a[r])),
                                (f[(s = r % e)] = l),
                                s === u &&
                                    ((h += l),
                                    (_[(s = (r / e) >> 0)] = f),
                                    (o[s] = h),
                                    (l = 0),
                                    (f = []));
                        return { length: h, lengths: o, segments: _ };
                    })(this._beziers, this._timeRes);
                    (this._length = c.length),
                        (this._lengths = c.lengths),
                        (this._segments = c.segments),
                        (this._l1 = this._li = this._s1 = this._si = 0),
                        (this._l2 = this._lengths[0]),
                        (this._curSeg = this._segments[0]),
                        (this._s2 = this._curSeg[0]),
                        (this._prec = 1 / this._curSeg.length);
                }
                if ((f = this._autoRotate))
                    for (
                        this._initialRotations = [],
                            f[0] instanceof Array ||
                                (this._autoRotate = f = [f]),
                            s = f.length;
                        -1 < --s;

                    ) {
                        for (a = 0; a < 3; a++)
                            (r = f[s][a]),
                                (this._func[r] =
                                    "function" == typeof t[r] &&
                                    t[
                                        r.indexOf("set") ||
                                        "function" !=
                                            typeof t["get" + r.substr(3)]
                                            ? r
                                            : "get" + r.substr(3)
                                    ]);
                        (r = f[s][2]),
                            (this._initialRotations[s] =
                                (this._func[r]
                                    ? this._func[r].call(this._target)
                                    : this._target[r]) || 0),
                            this._overwriteProps.push(r);
                    }
                return (this._startRatio = i.vars.runBackwards ? 1 : 0), !0;
            },
            set: function (t) {
                var e,
                    i,
                    n,
                    s,
                    a,
                    o,
                    l,
                    h,
                    u,
                    _,
                    f,
                    c = this._segCount,
                    p = this._func,
                    d = this._target,
                    m = t !== this._startRatio;
                if (this._timeRes) {
                    if (
                        ((u = this._lengths),
                        (_ = this._curSeg),
                        (f = t * this._length),
                        (n = this._li),
                        f > this._l2 && n < c - 1)
                    ) {
                        for (h = c - 1; n < h && (this._l2 = u[++n]) <= f; );
                        (this._l1 = u[n - 1]),
                            (this._li = n),
                            (this._curSeg = _ = this._segments[n]),
                            (this._s2 = _[(this._s1 = this._si = 0)]);
                    } else if (f < this._l1 && 0 < n) {
                        for (; 0 < n && (this._l1 = u[--n]) >= f; );
                        0 === n && f < this._l1 ? (this._l1 = 0) : n++,
                            (this._l2 = u[n]),
                            (this._li = n),
                            (this._curSeg = _ = this._segments[n]),
                            (this._s1 = _[(this._si = _.length - 1) - 1] || 0),
                            (this._s2 = _[this._si]);
                    }
                    if (
                        ((e = n),
                        (f -= this._l1),
                        (n = this._si),
                        f > this._s2 && n < _.length - 1)
                    ) {
                        for (
                            h = _.length - 1;
                            n < h && (this._s2 = _[++n]) <= f;

                        );
                        (this._s1 = _[n - 1]), (this._si = n);
                    } else if (f < this._s1 && 0 < n) {
                        for (; 0 < n && (this._s1 = _[--n]) >= f; );
                        0 === n && f < this._s1 ? (this._s1 = 0) : n++,
                            (this._s2 = _[n]),
                            (this._si = n);
                    }
                    o =
                        1 === t
                            ? 1
                            : (n + (f - this._s1) / (this._s2 - this._s1)) *
                                  this._prec || 0;
                } else
                    o =
                        (t -
                            (e = t < 0 ? 0 : 1 <= t ? c - 1 : (c * t) >> 0) *
                                (1 / c)) *
                        c;
                for (i = 1 - o, n = this._props.length; -1 < --n; )
                    (s = this._props[n]),
                        (l =
                            (o * o * (a = this._beziers[s][e]).da +
                                3 * i * (o * a.ca + i * a.ba)) *
                                o +
                            a.a),
                        this._mod[s] && (l = this._mod[s](l, d)),
                        p[s] ? d[s](l) : (d[s] = l);
                if (this._autoRotate) {
                    var g,
                        y,
                        v,
                        x,
                        T,
                        w,
                        b,
                        P = this._autoRotate;
                    for (n = P.length; -1 < --n; )
                        (s = P[n][2]),
                            (w = P[n][3] || 0),
                            (b = !0 === P[n][4] ? 1 : r),
                            (a = this._beziers[P[n][0]]),
                            (g = this._beziers[P[n][1]]),
                            a &&
                                g &&
                                ((a = a[e]),
                                (g = g[e]),
                                (y = a.a + (a.b - a.a) * o),
                                (y += ((x = a.b + (a.c - a.b) * o) - y) * o),
                                (x += (a.c + (a.d - a.c) * o - x) * o),
                                (v = g.a + (g.b - g.a) * o),
                                (v += ((T = g.b + (g.c - g.b) * o) - v) * o),
                                (T += (g.c + (g.d - g.c) * o - T) * o),
                                (l = m
                                    ? Math.atan2(T - v, x - y) * b + w
                                    : this._initialRotations[n]),
                                this._mod[s] && (l = this._mod[s](l, d)),
                                p[s] ? d[s](l) : (d[s] = l));
                }
            },
        })),
        (u = h.prototype),
        (h.bezierThrough = d),
        (h.cubicToQuadratic = f),
        (h._autoCSS = !0),
        (h.quadraticToCubic = function (t, e, i) {
            return new _(t, (2 * e + t) / 3, (2 * e + i) / 3, i);
        }),
        (h._cssRegister = function () {
            var t = l.CSSPlugin;
            if (t) {
                var e = t._internals,
                    i = e._parseToProxy,
                    r = e._setPluginRatio,
                    n = e.CSSPropTween;
                e._registerComplexSpecialProp("bezier", {
                    parser: function (t, e, s, a, o, l) {
                        e instanceof Array && (e = { values: e }),
                            (l = new h());
                        var u,
                            _,
                            f,
                            c = e.values,
                            p = c.length - 1,
                            d = [],
                            m = {};
                        if (p < 0) return o;
                        for (u = 0; u <= p; u++)
                            (f = i(t, c[u], a, o, l, p !== u)), (d[u] = f.end);
                        for (_ in e) m[_] = e[_];
                        return (
                            (m.values = d),
                            ((o = new n(t, "bezier", 0, 0, f.pt, 2)).data = f),
                            (o.plugin = l),
                            (o.setRatio = r),
                            0 === m.autoRotate && (m.autoRotate = !0),
                            !m.autoRotate ||
                                m.autoRotate instanceof Array ||
                                ((u =
                                    !0 === m.autoRotate
                                        ? 0
                                        : Number(m.autoRotate)),
                                (m.autoRotate =
                                    null != f.end.left
                                        ? [["left", "top", "rotation", u, !1]]
                                        : null != f.end.x && [
                                              ["x", "y", "rotation", u, !1],
                                          ])),
                            m.autoRotate &&
                                (a._transform || a._enableTransforms(!1),
                                (f.autoRotate = a._target._gsTransform),
                                (f.proxy.rotation = f.autoRotate.rotation || 0),
                                a._overwriteProps.push("rotation")),
                            l._onInitTween(f.proxy, m, a._tween),
                            o
                        );
                    },
                });
            }
        }),
        (u._mod = function (t) {
            for (var e, i = this._overwriteProps, r = i.length; -1 < --r; )
                (e = t[i[r]]) &&
                    "function" == typeof e &&
                    (this._mod[i[r]] = e);
        }),
        (u._kill = function (t) {
            var e,
                i,
                r = this._props;
            for (e in this._beziers)
                if (e in t)
                    for (
                        delete this._beziers[e],
                            delete this._func[e],
                            i = r.length;
                        -1 < --i;

                    )
                        r[i] === e && r.splice(i, 1);
            if ((r = this._autoRotate))
                for (i = r.length; -1 < --i; ) t[r[i][2]] && r.splice(i, 1);
            return this._super._kill.call(this, t);
        }),
        _gsScope._gsDefine(
            "plugins.CSSPlugin",
            ["plugins.TweenPlugin", "TweenLite"],
            function (t, e) {
                var i,
                    r,
                    n,
                    s,
                    a = function () {
                        t.call(this, "css"),
                            (this._overwriteProps.length = 0),
                            (this.setRatio = a.prototype.setRatio);
                    },
                    o = _gsScope._gsDefine.globals,
                    l = {},
                    h = (a.prototype = new t("css"));
                function u(t, e) {
                    return e.toUpperCase();
                }
                function _(t, e) {
                    var i = it.createElementNS
                        ? it.createElementNS(
                              e || "http://www.w3.org/1999/xhtml",
                              t
                          )
                        : it.createElement(t);
                    return i.style ? i : it.createElement(t);
                }
                function f(t) {
                    return X.test(
                        "string" == typeof t
                            ? t
                            : (t.currentStyle
                                  ? t.currentStyle.filter
                                  : t.style.filter) || ""
                    )
                        ? parseFloat(RegExp.$1) / 100
                        : 1;
                }
                function c(t) {
                    _gsScope.console && console.log(t);
                }
                function p(t, e) {
                    var i,
                        r,
                        n = (e = e || rt).style;
                    if (void 0 !== n[t]) return t;
                    for (
                        t = t.charAt(0).toUpperCase() + t.substr(1),
                            i = ["O", "Moz", "ms", "Ms", "Webkit"],
                            r = 5;
                        -1 < --r && void 0 === n[i[r] + t];

                    );
                    return 0 <= r
                        ? ((lt =
                              "-" +
                              (ht = 3 === r ? "ms" : i[r]).toLowerCase() +
                              "-"),
                          ht + t)
                        : null;
                }
                function d(t) {
                    return ut.getComputedStyle(t);
                }
                function m(t, e) {
                    var i,
                        r,
                        n,
                        s = {};
                    if ((e = e || d(t)))
                        if ((i = e.length))
                            for (; -1 < --i; )
                                (-1 !== (n = e[i]).indexOf("-transform") &&
                                    Xt !== n) ||
                                    (s[n.replace(V, u)] =
                                        e.getPropertyValue(n));
                        else
                            for (i in e)
                                (-1 !== i.indexOf("Transform") && jt !== i) ||
                                    (s[i] = e[i]);
                    else if ((e = t.currentStyle || t.style))
                        for (i in e)
                            "string" == typeof i &&
                                void 0 === s[i] &&
                                (s[i.replace(V, u)] = e[i]);
                    return (
                        ot || (s.opacity = f(t)),
                        (r = $t(t, e, !1)),
                        (s.rotation = r.rotation),
                        (s.skewX = r.skewX),
                        (s.scaleX = r.scaleX),
                        (s.scaleY = r.scaleY),
                        (s.x = r.x),
                        (s.y = r.y),
                        Yt &&
                            ((s.z = r.z),
                            (s.rotationX = r.rotationX),
                            (s.rotationY = r.rotationY),
                            (s.scaleZ = r.scaleZ)),
                        s.filters && delete s.filters,
                        s
                    );
                }
                function g(t, e, i, r, n) {
                    var s,
                        a,
                        o,
                        l = {},
                        h = t.style;
                    for (a in i)
                        "cssText" !== a &&
                            "length" !== a &&
                            isNaN(a) &&
                            (e[a] !== (s = i[a]) || (n && n[a])) &&
                            -1 === a.indexOf("Origin") &&
                            ("number" == typeof s || "string" == typeof s) &&
                            ((l[a] =
                                "auto" !== s || ("left" !== a && "top" !== a)
                                    ? ("" !== s &&
                                          "auto" !== s &&
                                          "none" !== s) ||
                                      "string" != typeof e[a] ||
                                      "" === e[a].replace(N, "")
                                        ? s
                                        : 0
                                    : ct(t, a)),
                            void 0 !== h[a] && (o = new bt(h, a, h[a], o)));
                    if (r) for (a in r) "className" !== a && (l[a] = r[a]);
                    return { difs: l, firstMPT: o };
                }
                function y(t, e, i) {
                    if ("svg" === (t.nodeName + "").toLowerCase())
                        return (i || d(t))[e] || 0;
                    if (t.getCTM && Qt(t)) return t.getBBox()[e] || 0;
                    var r = parseFloat(
                            "width" === e ? t.offsetWidth : t.offsetHeight
                        ),
                        n = pt[e],
                        s = n.length;
                    for (i = i || d(t); -1 < --s; )
                        (r -= parseFloat(_t(t, "padding" + n[s], i, !0)) || 0),
                            (r -=
                                parseFloat(
                                    _t(t, "border" + n[s] + "Width", i, !0)
                                ) || 0);
                    return r;
                }
                function v(t, e) {
                    return (
                        "function" == typeof t && (t = t(M, A)),
                        "string" == typeof t && "=" === t.charAt(1)
                            ? parseInt(t.charAt(0) + "1", 10) *
                              parseFloat(t.substr(2))
                            : parseFloat(t) - parseFloat(e) || 0
                    );
                }
                function x(t, e) {
                    "function" == typeof t && (t = t(M, A));
                    var i = "string" == typeof t && "=" === t.charAt(1);
                    return (
                        "string" == typeof t &&
                            "v" === t.charAt(t.length - 2) &&
                            (t =
                                (i ? t.substr(0, 2) : 0) +
                                window[
                                    "inner" +
                                        ("vh" === t.substr(-2)
                                            ? "Height"
                                            : "Width")
                                ] *
                                    (parseFloat(i ? t.substr(2) : t) / 100)),
                        null == t
                            ? e
                            : i
                            ? parseInt(t.charAt(0) + "1", 10) *
                                  parseFloat(t.substr(2)) +
                              e
                            : parseFloat(t) || 0
                    );
                }
                function T(t, e, i, r) {
                    var n, s, a, o, l;
                    return (
                        "function" == typeof t && (t = t(M, A)),
                        (o =
                            null == t
                                ? e
                                : "number" == typeof t
                                ? t
                                : ((n = 360),
                                  (s = t.split("_")),
                                  (a =
                                      ((l = "=" === t.charAt(1))
                                          ? parseInt(t.charAt(0) + "1", 10) *
                                            parseFloat(s[0].substr(2))
                                          : parseFloat(s[0])) *
                                          (-1 === t.indexOf("rad") ? 1 : J) -
                                      (l ? 0 : e)),
                                  s.length &&
                                      (r && (r[i] = e + a),
                                      -1 !== t.indexOf("short") &&
                                          (a %= n) != a % 180 &&
                                          (a = a < 0 ? a + n : a - n),
                                      -1 !== t.indexOf("_cw") && a < 0
                                          ? (a =
                                                ((a + 3599999999640) % n) -
                                                ((a / n) | 0) * n)
                                          : -1 !== t.indexOf("ccw") &&
                                            0 < a &&
                                            (a =
                                                ((a - 3599999999640) % n) -
                                                ((a / n) | 0) * n)),
                                  e + a)) < 1e-6 &&
                            -1e-6 < o &&
                            (o = 0),
                        o
                    );
                }
                function w(t, e, i) {
                    return (
                        (255 *
                            (6 * (t = t < 0 ? t + 1 : 1 < t ? t - 1 : t) < 1
                                ? e + (i - e) * t * 6
                                : t < 0.5
                                ? i
                                : 3 * t < 2
                                ? e + (i - e) * (2 / 3 - t) * 6
                                : e) +
                            0.5) |
                        0
                    );
                }
                function b(t, e) {
                    var i,
                        r,
                        n,
                        s = t.match(vt) || [],
                        a = 0,
                        o = "";
                    if (!s.length) return t;
                    for (i = 0; i < s.length; i++)
                        (r = s[i]),
                            (a +=
                                (n = t.substr(a, t.indexOf(r, a) - a)).length +
                                r.length),
                            3 === (r = yt(r, e)).length && r.push(1),
                            (o +=
                                n +
                                (e
                                    ? "hsla(" +
                                      r[0] +
                                      "," +
                                      r[1] +
                                      "%," +
                                      r[2] +
                                      "%," +
                                      r[3]
                                    : "rgba(" + r.join(",")) +
                                ")");
                    return o + t.substr(a);
                }
                ((h.constructor = a).version = "2.1.3"),
                    (a.API = 2),
                    (a.defaultTransformPerspective = 0),
                    (a.defaultSkewType = "compensated"),
                    (a.defaultSmoothOrigin = !0),
                    (h = "px"),
                    (a.suffixMap = {
                        top: h,
                        right: h,
                        bottom: h,
                        left: h,
                        width: h,
                        height: h,
                        fontSize: h,
                        padding: h,
                        margin: h,
                        perspective: h,
                        lineHeight: "",
                    });
                var P,
                    S,
                    O,
                    k,
                    C,
                    R,
                    A,
                    M,
                    D,
                    F,
                    L = /(?:\-|\.|\b)(\d|\.|e\-)+/g,
                    z = /(?:\d|\-\d|\.\d|\-\.\d|\+=\d|\-=\d|\+=.\d|\-=\.\d)+/g,
                    E = /(?:\+=|\-=|\-|\b)[\d\-\.]+[a-zA-Z0-9]*(?:%|\b)/gi,
                    I = /(?:\+=|\-=|\-|\b)[\d\-\.]+[a-zA-Z0-9]*(?:%|\b),?/gi,
                    N = /(?![+-]?\d*\.?\d+|[+-]|e[+-]\d+)[^0-9]/g,
                    j = /(?:\d|\-|\+|=|#|\.)*/g,
                    X = /opacity *= *([^)]*)/i,
                    B = /opacity:([^;]*)/i,
                    Y = /alpha\(opacity *=.+?\)/i,
                    G = /^(rgb|hsl)/,
                    U = /([A-Z])/g,
                    V = /-([a-z])/gi,
                    q = /(^(?:url\(\"|url\())|(?:(\"\))$|\)$)/gi,
                    W = /(?:Left|Right|Width)/i,
                    Q = /(M11|M12|M21|M22)=[\d\-\.e]+/gi,
                    H = /progid\:DXImageTransform\.Microsoft\.Matrix\(.+?\)/i,
                    Z = /,(?=[^\)]*(?:\(|$))/gi,
                    $ = /[\s,\(]/i,
                    K = Math.PI / 180,
                    J = 180 / Math.PI,
                    tt = {},
                    et = { style: {} },
                    it = _gsScope.document || {
                        createElement: function () {
                            return et;
                        },
                    },
                    rt = _("div"),
                    nt = _("img"),
                    st = (a._internals = { _specialProps: l }),
                    at = (_gsScope.navigator || {}).userAgent || "",
                    ot =
                        ((D = at.indexOf("Android")),
                        (F = _("a")),
                        (O =
                            -1 !== at.indexOf("Safari") &&
                            -1 === at.indexOf("Chrome") &&
                            (-1 === D || 3 < parseFloat(at.substr(D + 8, 2)))),
                        (C =
                            O &&
                            parseFloat(
                                at.substr(at.indexOf("Version/") + 8, 2)
                            ) < 6),
                        (k = -1 !== at.indexOf("Firefox")),
                        (/MSIE ([0-9]{1,}[\.0-9]{0,})/.exec(at) ||
                            /Trident\/.*rv:([0-9]{1,}[\.0-9]{0,})/.exec(at)) &&
                            (R = parseFloat(RegExp.$1)),
                        !!F &&
                            ((F.style.cssText = "top:1px;opacity:.55;"),
                            /^0.55/.test(F.style.opacity))),
                    lt = "",
                    ht = "",
                    ut =
                        "undefined" != typeof window
                            ? window
                            : it.defaultView || {
                                  getComputedStyle: function () {},
                              },
                    _t = (a.getStyle = function (t, e, i, r, n) {
                        var s;
                        return ot || "opacity" !== e
                            ? (!r && t.style[e]
                                  ? (s = t.style[e])
                                  : (i = i || d(t))
                                  ? (s =
                                        i[e] ||
                                        i.getPropertyValue(e) ||
                                        i.getPropertyValue(
                                            e.replace(U, "-$1").toLowerCase()
                                        ))
                                  : t.currentStyle && (s = t.currentStyle[e]),
                              null == n ||
                              (s &&
                                  "none" !== s &&
                                  "auto" !== s &&
                                  "auto auto" !== s)
                                  ? s
                                  : n)
                            : f(t);
                    }),
                    ft = (st.convertToPixels = function (t, i, r, n, s) {
                        if ("px" === n || (!n && "lineHeight" !== i)) return r;
                        if ("auto" === n || !r) return 0;
                        var o,
                            l,
                            h,
                            u = W.test(i),
                            _ = t,
                            f = rt.style,
                            c = r < 0,
                            p = 1 === r;
                        if (
                            (c && (r = -r),
                            p && (r *= 100),
                            "lineHeight" !== i || n)
                        )
                            if ("%" === n && -1 !== i.indexOf("border"))
                                o =
                                    (r / 100) *
                                    (u ? t.clientWidth : t.clientHeight);
                            else {
                                if (
                                    ((f.cssText =
                                        "border:0 solid red;position:" +
                                        _t(t, "position") +
                                        ";line-height:0;"),
                                    "%" !== n &&
                                        _.appendChild &&
                                        "v" !== n.charAt(0) &&
                                        "rem" !== n)
                                )
                                    f[
                                        u ? "borderLeftWidth" : "borderTopWidth"
                                    ] = r + n;
                                else {
                                    if (
                                        ((_ = t.parentNode || it.body),
                                        -1 !==
                                            _t(_, "display").indexOf("flex") &&
                                            (f.position = "absolute"),
                                        (l = _._gsCache),
                                        (h = e.ticker.frame),
                                        l && u && l.time === h)
                                    )
                                        return (l.width * r) / 100;
                                    f[u ? "width" : "height"] = r + n;
                                }
                                _.appendChild(rt),
                                    (o = parseFloat(
                                        rt[u ? "offsetWidth" : "offsetHeight"]
                                    )),
                                    _.removeChild(rt),
                                    u &&
                                        "%" === n &&
                                        !1 !== a.cacheWidths &&
                                        (((l = _._gsCache =
                                            _._gsCache || {}).time = h),
                                        (l.width = (o / r) * 100)),
                                    0 !== o || s || (o = ft(t, i, r, n, !0));
                            }
                        else
                            (l = d(t).lineHeight),
                                (t.style.lineHeight = r),
                                (o = parseFloat(d(t).lineHeight)),
                                (t.style.lineHeight = l);
                        return p && (o /= 100), c ? -o : o;
                    }),
                    ct = (st.calculateOffset = function (t, e, i) {
                        if ("absolute" !== _t(t, "position", i)) return 0;
                        var r = "left" === e ? "Left" : "Top",
                            n = _t(t, "margin" + r, i);
                        return (
                            t["offset" + r] -
                            (ft(t, e, parseFloat(n), n.replace(j, "")) || 0)
                        );
                    }),
                    pt = {
                        width: ["Left", "Right"],
                        height: ["Top", "Bottom"],
                    },
                    dt = [
                        "marginLeft",
                        "marginRight",
                        "marginTop",
                        "marginBottom",
                    ],
                    mt = function (t, e) {
                        if (
                            "contain" === t ||
                            "auto" === t ||
                            "auto auto" === t
                        )
                            return t + " ";
                        (null != t && "" !== t) || (t = "0 0");
                        var i,
                            r = t.split(" "),
                            n =
                                -1 !== t.indexOf("left")
                                    ? "0%"
                                    : -1 !== t.indexOf("right")
                                    ? "100%"
                                    : r[0],
                            s =
                                -1 !== t.indexOf("top")
                                    ? "0%"
                                    : -1 !== t.indexOf("bottom")
                                    ? "100%"
                                    : r[1];
                        if (3 < r.length && !e) {
                            for (
                                r = t.split(", ").join(",").split(","),
                                    t = [],
                                    i = 0;
                                i < r.length;
                                i++
                            )
                                t.push(mt(r[i]));
                            return t.join(",");
                        }
                        return (
                            null == s
                                ? (s = "center" === n ? "50%" : "0")
                                : "center" === s && (s = "50%"),
                            ("center" === n ||
                                (isNaN(parseFloat(n)) &&
                                    -1 === (n + "").indexOf("="))) &&
                                (n = "50%"),
                            (t =
                                n + " " + s + (2 < r.length ? " " + r[2] : "")),
                            e &&
                                ((e.oxp = -1 !== n.indexOf("%")),
                                (e.oyp = -1 !== s.indexOf("%")),
                                (e.oxr = "=" === n.charAt(1)),
                                (e.oyr = "=" === s.charAt(1)),
                                (e.ox = parseFloat(n.replace(N, ""))),
                                (e.oy = parseFloat(s.replace(N, ""))),
                                (e.v = t)),
                            e || t
                        );
                    },
                    gt = {
                        aqua: [0, 255, 255],
                        lime: [0, 255, 0],
                        silver: [192, 192, 192],
                        black: [0, 0, 0],
                        maroon: [128, 0, 0],
                        teal: [0, 128, 128],
                        blue: [0, 0, 255],
                        navy: [0, 0, 128],
                        white: [255, 255, 255],
                        fuchsia: [255, 0, 255],
                        olive: [128, 128, 0],
                        yellow: [255, 255, 0],
                        orange: [255, 165, 0],
                        gray: [128, 128, 128],
                        purple: [128, 0, 128],
                        green: [0, 128, 0],
                        red: [255, 0, 0],
                        pink: [255, 192, 203],
                        cyan: [0, 255, 255],
                        transparent: [255, 255, 255, 0],
                    },
                    yt = (a.parseColor = function (t, e) {
                        var i, r, n, s, a, o, l, h, u, _, f;
                        if (t)
                            if ("number" == typeof t)
                                i = [t >> 16, (t >> 8) & 255, 255 & t];
                            else {
                                if (
                                    ("," === t.charAt(t.length - 1) &&
                                        (t = t.substr(0, t.length - 1)),
                                    gt[t])
                                )
                                    i = gt[t];
                                else if ("#" === t.charAt(0))
                                    4 === t.length &&
                                        (t =
                                            "#" +
                                            (r = t.charAt(1)) +
                                            r +
                                            (n = t.charAt(2)) +
                                            n +
                                            (s = t.charAt(3)) +
                                            s),
                                        (i = [
                                            (t = parseInt(t.substr(1), 16)) >>
                                                16,
                                            (t >> 8) & 255,
                                            255 & t,
                                        ]);
                                else if ("hsl" === t.substr(0, 3))
                                    if (((i = f = t.match(L)), e)) {
                                        if (-1 !== t.indexOf("="))
                                            return t.match(z);
                                    } else
                                        (a = (Number(i[0]) % 360) / 360),
                                            (o = Number(i[1]) / 100),
                                            (r =
                                                2 * (l = Number(i[2]) / 100) -
                                                (n =
                                                    l <= 0.5
                                                        ? l * (o + 1)
                                                        : l + o - l * o)),
                                            3 < i.length &&
                                                (i[3] = Number(i[3])),
                                            (i[0] = w(a + 1 / 3, r, n)),
                                            (i[1] = w(a, r, n)),
                                            (i[2] = w(a - 1 / 3, r, n));
                                else i = t.match(L) || gt.transparent;
                                (i[0] = Number(i[0])),
                                    (i[1] = Number(i[1])),
                                    (i[2] = Number(i[2])),
                                    3 < i.length && (i[3] = Number(i[3]));
                            }
                        else i = gt.black;
                        return (
                            e &&
                                !f &&
                                ((r = i[0] / 255),
                                (n = i[1] / 255),
                                (s = i[2] / 255),
                                (l =
                                    ((h = Math.max(r, n, s)) +
                                        (u = Math.min(r, n, s))) /
                                    2),
                                h === u
                                    ? (a = o = 0)
                                    : ((_ = h - u),
                                      (o =
                                          0.5 < l
                                              ? _ / (2 - h - u)
                                              : _ / (h + u)),
                                      (a =
                                          h === r
                                              ? (n - s) / _ + (n < s ? 6 : 0)
                                              : h === n
                                              ? (s - r) / _ + 2
                                              : (r - n) / _ + 4),
                                      (a *= 60)),
                                (i[0] = (a + 0.5) | 0),
                                (i[1] = (100 * o + 0.5) | 0),
                                (i[2] = (100 * l + 0.5) | 0)),
                            i
                        );
                    }),
                    vt =
                        "(?:\\b(?:(?:rgb|rgba|hsl|hsla)\\(.+?\\))|\\B#(?:[0-9a-f]{3}){1,2}\\b";
                for (h in gt) vt += "|" + h + "\\b";
                function xt(t, e, i, r) {
                    if (null == t)
                        return function (t) {
                            return t;
                        };
                    var n,
                        s = e ? (t.match(vt) || [""])[0] : "",
                        a = t.split(s).join("").match(E) || [],
                        o = t.substr(0, t.indexOf(a[0])),
                        l = ")" === t.charAt(t.length - 1) ? ")" : "",
                        h = -1 !== t.indexOf(" ") ? " " : ",",
                        u = a.length,
                        _ = 0 < u ? a[0].replace(L, "") : "";
                    return u
                        ? (n = e
                              ? function (t) {
                                    var e, f, c, p;
                                    if ("number" == typeof t) t += _;
                                    else if (r && Z.test(t)) {
                                        for (
                                            p = t.replace(Z, "|").split("|"),
                                                c = 0;
                                            c < p.length;
                                            c++
                                        )
                                            p[c] = n(p[c]);
                                        return p.join(",");
                                    }
                                    if (
                                        ((e = (t.match(vt) || [s])[0]),
                                        (c = (f =
                                            t.split(e).join("").match(E) || [])
                                            .length),
                                        u > c--)
                                    )
                                        for (; ++c < u; )
                                            f[c] = i
                                                ? f[((c - 1) / 2) | 0]
                                                : a[c];
                                    return (
                                        o +
                                        f.join(h) +
                                        h +
                                        e +
                                        l +
                                        (-1 !== t.indexOf("inset")
                                            ? " inset"
                                            : "")
                                    );
                                }
                              : function (t) {
                                    var e, s, f;
                                    if ("number" == typeof t) t += _;
                                    else if (r && Z.test(t)) {
                                        for (
                                            s = t.replace(Z, "|").split("|"),
                                                f = 0;
                                            f < s.length;
                                            f++
                                        )
                                            s[f] = n(s[f]);
                                        return s.join(",");
                                    }
                                    if (
                                        ((f = (e =
                                            t.match("," == h ? E : I) || [])
                                            .length),
                                        u > f--)
                                    )
                                        for (; ++f < u; )
                                            e[f] = i
                                                ? e[((f - 1) / 2) | 0]
                                                : a[f];
                                    return (
                                        ((o &&
                                            "none" !== t &&
                                            t.substr(0, t.indexOf(e[0]))) ||
                                            o) +
                                        e.join(h) +
                                        l
                                    );
                                })
                        : function (t) {
                              return t;
                          };
                }
                function Tt(t) {
                    return (
                        (t = t.split(",")),
                        function (e, i, r, n, s, a, o) {
                            var l,
                                h = (i + "").split(" ");
                            for (o = {}, l = 0; l < 4; l++)
                                o[t[l]] = h[l] = h[l] || h[((l - 1) / 2) >> 0];
                            return n.parse(e, o, s, a);
                        }
                    );
                }
                function wt(t, e, i, r, n, s) {
                    var a = new Pt(t, e, i, r - i, n, -1, s);
                    return (a.b = i), (a.e = a.xs0 = r), a;
                }
                (vt = new RegExp(vt + ")", "gi")),
                    (a.colorStringFilter = function (t) {
                        var e,
                            i = t[0] + " " + t[1];
                        vt.test(i) &&
                            ((e =
                                -1 !== i.indexOf("hsl(") ||
                                -1 !== i.indexOf("hsla(")),
                            (t[0] = b(t[0], e)),
                            (t[1] = b(t[1], e))),
                            (vt.lastIndex = 0);
                    }),
                    e.defaultStringFilter ||
                        (e.defaultStringFilter = a.colorStringFilter);
                var bt =
                        ((st._setPluginRatio = function (t) {
                            this.plugin.setRatio(t);
                            for (
                                var e,
                                    i,
                                    r,
                                    n,
                                    s,
                                    a = this.data,
                                    o = a.proxy,
                                    l = a.firstMPT;
                                l;

                            )
                                (e = o[l.v]),
                                    l.r
                                        ? (e = l.r(e))
                                        : e < 1e-6 && -1e-6 < e && (e = 0),
                                    (l.t[l.p] = e),
                                    (l = l._next);
                            if (
                                (a.autoRotate &&
                                    (a.autoRotate.rotation = a.mod
                                        ? a.mod.call(
                                              this._tween,
                                              o.rotation,
                                              this.t,
                                              this._tween
                                          )
                                        : o.rotation),
                                1 === t || 0 === t)
                            )
                                for (
                                    l = a.firstMPT, s = 1 === t ? "e" : "b";
                                    l;

                                ) {
                                    if ((i = l.t).type) {
                                        if (1 === i.type) {
                                            for (
                                                n = i.xs0 + i.s + i.xs1, r = 1;
                                                r < i.l;
                                                r++
                                            )
                                                n +=
                                                    i["xn" + r] +
                                                    i["xs" + (r + 1)];
                                            i[s] = n;
                                        }
                                    } else i[s] = i.s + i.xs0;
                                    l = l._next;
                                }
                        }),
                        function (t, e, i, r, n) {
                            (this.t = t),
                                (this.p = e),
                                (this.v = i),
                                (this.r = n),
                                r && ((r._prev = this)._next = r);
                        }),
                    Pt =
                        ((st._parseToProxy = function (t, e, i, r, n, s) {
                            var a,
                                o,
                                l,
                                h,
                                u,
                                _ = r,
                                f = {},
                                c = {},
                                p = i._transform,
                                d = tt;
                            for (
                                i._transform = null,
                                    tt = e,
                                    r = u = i.parse(t, e, r, n),
                                    tt = d,
                                    s &&
                                        ((i._transform = p),
                                        _ &&
                                            ((_._prev = null),
                                            _._prev && (_._prev._next = null)));
                                r && r !== _;

                            ) {
                                if (
                                    r.type <= 1 &&
                                    ((c[(o = r.p)] = r.s + r.c),
                                    (f[o] = r.s),
                                    s ||
                                        ((h = new bt(r, "s", o, h, r.r)),
                                        (r.c = 0)),
                                    1 === r.type)
                                )
                                    for (a = r.l; 0 < --a; )
                                        (l = "xn" + a),
                                            (c[(o = r.p + "_" + l)] =
                                                r.data[l]),
                                            (f[o] = r[l]),
                                            s ||
                                                (h = new bt(
                                                    r,
                                                    l,
                                                    o,
                                                    h,
                                                    r.rxp[l]
                                                ));
                                r = r._next;
                            }
                            return { proxy: f, end: c, firstMPT: h, pt: u };
                        }),
                        (st.CSSPropTween = function (
                            t,
                            e,
                            r,
                            n,
                            a,
                            o,
                            l,
                            h,
                            u,
                            _,
                            f
                        ) {
                            (this.t = t),
                                (this.p = e),
                                (this.s = r),
                                (this.c = n),
                                (this.n = l || e),
                                t instanceof Pt || s.push(this.n),
                                (this.r = h
                                    ? "function" == typeof h
                                        ? h
                                        : Math.round
                                    : h),
                                (this.type = o || 0),
                                u && ((this.pr = u), (i = !0)),
                                (this.b = void 0 === _ ? r : _),
                                (this.e = void 0 === f ? r + n : f),
                                a && ((this._next = a)._prev = this);
                        })),
                    St = (a.parseComplex = function (
                        t,
                        e,
                        i,
                        r,
                        n,
                        s,
                        o,
                        l,
                        h,
                        u
                    ) {
                        (i = i || s || ""),
                            "function" == typeof r && (r = r(M, A)),
                            (o = new Pt(
                                t,
                                e,
                                0,
                                0,
                                o,
                                u ? 2 : 1,
                                null,
                                !1,
                                l,
                                i,
                                r
                            )),
                            (r += ""),
                            n &&
                                vt.test(r + i) &&
                                ((r = [i, r]),
                                a.colorStringFilter(r),
                                (i = r[0]),
                                (r = r[1]));
                        var _,
                            f,
                            c,
                            p,
                            d,
                            m,
                            g,
                            y,
                            x,
                            T,
                            w,
                            b,
                            S,
                            O = i.split(", ").join(",").split(" "),
                            k = r.split(", ").join(",").split(" "),
                            C = O.length,
                            R = !1 !== P;
                        for (
                            (-1 === r.indexOf(",") && -1 === i.indexOf(",")) ||
                                ((k =
                                    -1 !== (r + i).indexOf("rgb") ||
                                    -1 !== (r + i).indexOf("hsl")
                                        ? ((O = O.join(" ")
                                              .replace(Z, ", ")
                                              .split(" ")),
                                          k
                                              .join(" ")
                                              .replace(Z, ", ")
                                              .split(" "))
                                        : ((O = O.join(" ")
                                              .split(",")
                                              .join(", ")
                                              .split(" ")),
                                          k
                                              .join(" ")
                                              .split(",")
                                              .join(", ")
                                              .split(" "))),
                                (C = O.length)),
                                C !== k.length &&
                                    (C = (O = (s || "").split(" ")).length),
                                o.plugin = h,
                                o.setRatio = u,
                                _ = vt.lastIndex = 0;
                            _ < C;
                            _++
                        )
                            if (
                                ((p = O[_]),
                                (d = k[_] + ""),
                                (y = parseFloat(p)) || 0 === y)
                            )
                                o.appendXtra(
                                    "",
                                    y,
                                    v(d, y),
                                    d.replace(z, ""),
                                    R && -1 !== d.indexOf("px") && Math.round,
                                    !0
                                );
                            else if (n && vt.test(p))
                                (b =
                                    ")" +
                                    ((b = d.indexOf(")") + 1)
                                        ? d.substr(b)
                                        : "")),
                                    (S = -1 !== d.indexOf("hsl") && ot),
                                    (T = d),
                                    (p = yt(p, S)),
                                    (d = yt(d, S)),
                                    (x = 6 < p.length + d.length) &&
                                    !ot &&
                                    0 === d[3]
                                        ? ((o["xs" + o.l] += o.l
                                              ? " transparent"
                                              : "transparent"),
                                          (o.e = o.e
                                              .split(k[_])
                                              .join("transparent")))
                                        : (ot || (x = !1),
                                          S
                                              ? o
                                                    .appendXtra(
                                                        T.substr(
                                                            0,
                                                            T.indexOf("hsl")
                                                        ) +
                                                            (x
                                                                ? "hsla("
                                                                : "hsl("),
                                                        p[0],
                                                        v(d[0], p[0]),
                                                        ",",
                                                        !1,
                                                        !0
                                                    )
                                                    .appendXtra(
                                                        "",
                                                        p[1],
                                                        v(d[1], p[1]),
                                                        "%,",
                                                        !1
                                                    )
                                                    .appendXtra(
                                                        "",
                                                        p[2],
                                                        v(d[2], p[2]),
                                                        x ? "%," : "%" + b,
                                                        !1
                                                    )
                                              : o
                                                    .appendXtra(
                                                        T.substr(
                                                            0,
                                                            T.indexOf("rgb")
                                                        ) +
                                                            (x
                                                                ? "rgba("
                                                                : "rgb("),
                                                        p[0],
                                                        d[0] - p[0],
                                                        ",",
                                                        Math.round,
                                                        !0
                                                    )
                                                    .appendXtra(
                                                        "",
                                                        p[1],
                                                        d[1] - p[1],
                                                        ",",
                                                        Math.round
                                                    )
                                                    .appendXtra(
                                                        "",
                                                        p[2],
                                                        d[2] - p[2],
                                                        x ? "," : b,
                                                        Math.round
                                                    ),
                                          x &&
                                              ((p = p.length < 4 ? 1 : p[3]),
                                              o.appendXtra(
                                                  "",
                                                  p,
                                                  (d.length < 4 ? 1 : d[3]) - p,
                                                  b,
                                                  !1
                                              ))),
                                    (vt.lastIndex = 0);
                            else if ((m = p.match(L))) {
                                if (!(g = d.match(z)) || g.length !== m.length)
                                    return o;
                                for (f = c = 0; f < m.length; f++)
                                    (w = m[f]),
                                        (T = p.indexOf(w, c)),
                                        o.appendXtra(
                                            p.substr(c, T - c),
                                            Number(w),
                                            v(g[f], w),
                                            "",
                                            R &&
                                                "px" ===
                                                    p.substr(T + w.length, 2) &&
                                                Math.round,
                                            0 === f
                                        ),
                                        (c = T + w.length);
                                o["xs" + o.l] += p.substr(c);
                            } else
                                o["xs" + o.l] +=
                                    o.l || o["xs" + o.l] ? " " + d : d;
                        if (-1 !== r.indexOf("=") && o.data) {
                            for (b = o.xs0 + o.data.s, _ = 1; _ < o.l; _++)
                                b += o["xs" + _] + o.data["xn" + _];
                            o.e = b + o["xs" + _];
                        }
                        return (
                            o.l || ((o.type = -1), (o.xs0 = o.e)), o.xfirst || o
                        );
                    }),
                    Ot = 9;
                for ((h = Pt.prototype).l = h.pr = 0; 0 < --Ot; )
                    (h["xn" + Ot] = 0), (h["xs" + Ot] = "");
                function kt(t, e) {
                    (e = e || {}),
                        (this.p = (e.prefix && p(t)) || t),
                        (l[t] = l[this.p] = this),
                        (this.format =
                            e.formatter ||
                            xt(
                                e.defaultValue,
                                e.color,
                                e.collapsible,
                                e.multi
                            )),
                        e.parser && (this.parse = e.parser),
                        (this.clrs = e.color),
                        (this.multi = e.multi),
                        (this.keyword = e.keyword),
                        (this.dflt = e.defaultValue),
                        (this.allowFunc = e.allowFunc),
                        (this.pr = e.priority || 0);
                }
                (h.xs0 = ""),
                    (h._next =
                        h._prev =
                        h.xfirst =
                        h.data =
                        h.plugin =
                        h.setRatio =
                        h.rxp =
                            null),
                    (h.appendXtra = function (t, e, i, r, n, s) {
                        var a = this,
                            o = a.l;
                        return (
                            (a["xs" + o] +=
                                s && (o || a["xs" + o]) ? " " + t : t || ""),
                            i || 0 === o || a.plugin
                                ? (a.l++,
                                  (a.type = a.setRatio ? 2 : 1),
                                  (a["xs" + a.l] = r || ""),
                                  0 < o
                                      ? ((a.data["xn" + o] = e + i),
                                        (a.rxp["xn" + o] = n),
                                        (a["xn" + o] = e),
                                        a.plugin ||
                                            ((a.xfirst = new Pt(
                                                a,
                                                "xn" + o,
                                                e,
                                                i,
                                                a.xfirst || a,
                                                0,
                                                a.n,
                                                n,
                                                a.pr
                                            )),
                                            (a.xfirst.xs0 = 0)))
                                      : ((a.data = { s: e + i }),
                                        (a.rxp = {}),
                                        (a.s = e),
                                        (a.c = i),
                                        (a.r = n)))
                                : (a["xs" + o] += e + (r || "")),
                            a
                        );
                    });
                var Ct = (st._registerComplexSpecialProp = function (t, e, i) {
                        "object" != typeof e && (e = { parser: i });
                        var r,
                            n = t.split(","),
                            s = e.defaultValue;
                        for (i = i || [s], r = 0; r < n.length; r++)
                            (e.prefix = 0 === r && e.prefix),
                                (e.defaultValue = i[r] || s),
                                new kt(n[r], e);
                    }),
                    Rt = (st._registerPluginProp = function (t) {
                        if (!l[t]) {
                            var e =
                                t.charAt(0).toUpperCase() +
                                t.substr(1) +
                                "Plugin";
                            Ct(t, {
                                parser: function (t, i, r, n, s, a, h) {
                                    var u = o.com.greensock.plugins[e];
                                    return u
                                        ? (u._cssRegister(),
                                          l[r].parse(t, i, r, n, s, a, h))
                                        : (c(
                                              "Error: " +
                                                  e +
                                                  " js file not loaded."
                                          ),
                                          s);
                                },
                            });
                        }
                    });
                function At(t, e, i) {
                    var r,
                        n = it.createElementNS("http://www.w3.org/2000/svg", t),
                        s = /([a-z])([A-Z])/g;
                    for (r in i)
                        n.setAttributeNS(
                            null,
                            r.replace(s, "$1-$2").toLowerCase(),
                            i[r]
                        );
                    return e.appendChild(n), n;
                }
                function Mt(t, e, i, r, n, s) {
                    var o,
                        l,
                        h,
                        u,
                        _,
                        f,
                        c,
                        p,
                        d,
                        m,
                        g,
                        y,
                        v,
                        x,
                        T = t._gsTransform,
                        w = Zt(t, !0);
                    T && ((v = T.xOrigin), (x = T.yOrigin)),
                        (!r || (o = r.split(" ")).length < 2) &&
                            (0 === (c = t.getBBox()).x &&
                                0 === c.y &&
                                c.width + c.height === 0 &&
                                (c = {
                                    x:
                                        parseFloat(
                                            t.hasAttribute("x")
                                                ? t.getAttribute("x")
                                                : t.hasAttribute("cx")
                                                ? t.getAttribute("cx")
                                                : 0
                                        ) || 0,
                                    y:
                                        parseFloat(
                                            t.hasAttribute("y")
                                                ? t.getAttribute("y")
                                                : t.hasAttribute("cy")
                                                ? t.getAttribute("cy")
                                                : 0
                                        ) || 0,
                                    width: 0,
                                    height: 0,
                                }),
                            (o = [
                                (-1 !== (e = mt(e).split(" "))[0].indexOf("%")
                                    ? (parseFloat(e[0]) / 100) * c.width
                                    : parseFloat(e[0])) + c.x,
                                (-1 !== e[1].indexOf("%")
                                    ? (parseFloat(e[1]) / 100) * c.height
                                    : parseFloat(e[1])) + c.y,
                            ])),
                        (i.xOrigin = u = parseFloat(o[0])),
                        (i.yOrigin = _ = parseFloat(o[1])),
                        r &&
                            w !== Ht &&
                            ((f = w[0]),
                            (c = w[1]),
                            (p = w[2]),
                            (d = w[3]),
                            (m = w[4]),
                            (g = w[5]),
                            (y = f * d - c * p) &&
                                ((l =
                                    u * (d / y) +
                                    _ * (-p / y) +
                                    (p * g - d * m) / y),
                                (h =
                                    u * (-c / y) +
                                    _ * (f / y) -
                                    (f * g - c * m) / y),
                                (u = i.xOrigin = o[0] = l),
                                (_ = i.yOrigin = o[1] = h))),
                        T &&
                            (s &&
                                ((i.xOffset = T.xOffset),
                                (i.yOffset = T.yOffset),
                                (T = i)),
                            n || (!1 !== n && !1 !== a.defaultSmoothOrigin)
                                ? ((l = u - v),
                                  (h = _ - x),
                                  (T.xOffset += l * w[0] + h * w[2] - l),
                                  (T.yOffset += l * w[1] + h * w[3] - h))
                                : (T.xOffset = T.yOffset = 0)),
                        s || t.setAttribute("data-svg-origin", o.join(" "));
                }
                function Dt(t) {
                    var e,
                        i,
                        r = this.data,
                        n = -r.rotation * K,
                        s = n + r.skewX * K,
                        a = 1e5,
                        o = ((Math.cos(n) * r.scaleX * a) | 0) / a,
                        l = ((Math.sin(n) * r.scaleX * a) | 0) / a,
                        h = ((Math.sin(s) * -r.scaleY * a) | 0) / a,
                        u = ((Math.cos(s) * r.scaleY * a) | 0) / a,
                        _ = this.t.style,
                        f = this.t.currentStyle;
                    if (f) {
                        (i = l),
                            (l = -h),
                            (h = -i),
                            (e = f.filter),
                            (_.filter = "");
                        var c,
                            p,
                            d = this.t.offsetWidth,
                            m = this.t.offsetHeight,
                            g = "absolute" !== f.position,
                            y =
                                "progid:DXImageTransform.Microsoft.Matrix(M11=" +
                                o +
                                ", M12=" +
                                l +
                                ", M21=" +
                                h +
                                ", M22=" +
                                u,
                            v = r.x + (d * r.xPercent) / 100,
                            x = r.y + (m * r.yPercent) / 100;
                        if (
                            (null != r.ox &&
                                ((v +=
                                    (c =
                                        (r.oxp ? d * r.ox * 0.01 : r.ox) -
                                        d / 2) -
                                    (c * o +
                                        (p =
                                            (r.oyp ? m * r.oy * 0.01 : r.oy) -
                                            m / 2) *
                                            l)),
                                (x += p - (c * h + p * u))),
                            (y += g
                                ? ", Dx=" +
                                  ((c = d / 2) -
                                      (c * o + (p = m / 2) * l) +
                                      v) +
                                  ", Dy=" +
                                  (p - (c * h + p * u) + x) +
                                  ")"
                                : ", sizingMethod='auto expand')"),
                            -1 !==
                            e.indexOf("DXImageTransform.Microsoft.Matrix(")
                                ? (_.filter = e.replace(H, y))
                                : (_.filter = y + " " + e),
                            (0 !== t && 1 !== t) ||
                                1 != o ||
                                0 !== l ||
                                0 !== h ||
                                1 != u ||
                                (g && -1 === y.indexOf("Dx=0, Dy=0")) ||
                                (X.test(e) && 100 !== parseFloat(RegExp.$1)) ||
                                (-1 === e.indexOf(e.indexOf("Alpha")) &&
                                    _.removeAttribute("filter")),
                            !g)
                        ) {
                            var T,
                                w,
                                b,
                                P = R < 8 ? 1 : -1;
                            for (
                                c = r.ieOffsetX || 0,
                                    p = r.ieOffsetY || 0,
                                    r.ieOffsetX = Math.round(
                                        (d -
                                            ((o < 0 ? -o : o) * d +
                                                (l < 0 ? -l : l) * m)) /
                                            2 +
                                            v
                                    ),
                                    r.ieOffsetY = Math.round(
                                        (m -
                                            ((u < 0 ? -u : u) * m +
                                                (h < 0 ? -h : h) * d)) /
                                            2 +
                                            x
                                    ),
                                    Ot = 0;
                                Ot < 4;
                                Ot++
                            )
                                (b =
                                    (i =
                                        -1 !==
                                        (T = f[(w = dt[Ot])]).indexOf("px")
                                            ? parseFloat(T)
                                            : ft(
                                                  this.t,
                                                  w,
                                                  parseFloat(T),
                                                  T.replace(j, "")
                                              ) || 0) !== r[w]
                                        ? Ot < 2
                                            ? -r.ieOffsetX
                                            : -r.ieOffsetY
                                        : Ot < 2
                                        ? c - r.ieOffsetX
                                        : p - r.ieOffsetY),
                                    (_[w] =
                                        (r[w] = Math.round(
                                            i -
                                                b *
                                                    (0 === Ot || 2 === Ot
                                                        ? 1
                                                        : P)
                                        )) + "px");
                        }
                    }
                }
                ((h = kt.prototype).parseComplex = function (t, e, i, r, n, s) {
                    var a,
                        o,
                        l,
                        h,
                        u,
                        _,
                        f = this.keyword;
                    if (
                        (this.multi &&
                            (Z.test(i) || Z.test(e)
                                ? ((o = e.replace(Z, "|").split("|")),
                                  (l = i.replace(Z, "|").split("|")))
                                : f && ((o = [e]), (l = [i]))),
                        l)
                    ) {
                        for (
                            h = l.length > o.length ? l.length : o.length,
                                a = 0;
                            a < h;
                            a++
                        )
                            (e = o[a] = o[a] || this.dflt),
                                (i = l[a] = l[a] || this.dflt),
                                f &&
                                    (u = e.indexOf(f)) !== (_ = i.indexOf(f)) &&
                                    (-1 === _
                                        ? (o[a] = o[a].split(f).join(""))
                                        : -1 === u && (o[a] += " " + f));
                        (e = o.join(", ")), (i = l.join(", "));
                    }
                    return St(
                        t,
                        this.p,
                        e,
                        i,
                        this.clrs,
                        this.dflt,
                        r,
                        this.pr,
                        n,
                        s
                    );
                }),
                    (h.parse = function (t, e, i, r, s, a, o) {
                        return this.parseComplex(
                            t.style,
                            this.format(_t(t, this.p, n, !1, this.dflt)),
                            this.format(e),
                            s,
                            a
                        );
                    }),
                    (a.registerSpecialProp = function (t, e, i) {
                        Ct(t, {
                            parser: function (t, r, n, s, a, o, l) {
                                var h = new Pt(t, n, 0, 0, a, 2, n, !1, i);
                                return (
                                    (h.plugin = o),
                                    (h.setRatio = e(t, r, s._tween, n)),
                                    h
                                );
                            },
                            priority: i,
                        });
                    }),
                    (a.useSVGTransformAttr = !0);
                var Ft,
                    Lt,
                    zt,
                    Et,
                    It,
                    Nt =
                        "scaleX,scaleY,scaleZ,x,y,z,skewX,skewY,rotation,rotationX,rotationY,perspective,xPercent,yPercent".split(
                            ","
                        ),
                    jt = p("transform"),
                    Xt = lt + "transform",
                    Bt = p("transformOrigin"),
                    Yt = null !== p("perspective"),
                    Gt = (st.Transform = function () {
                        (this.perspective =
                            parseFloat(a.defaultTransformPerspective) || 0),
                            (this.force3D =
                                !(!1 === a.defaultForce3D || !Yt) &&
                                (a.defaultForce3D || "auto"));
                    }),
                    Ut = _gsScope.SVGElement,
                    Vt = it.documentElement || {},
                    qt =
                        ((It = R || (/Android/i.test(at) && !_gsScope.chrome)),
                        it.createElementNS &&
                            Vt.appendChild &&
                            !It &&
                            ((Lt = At("svg", Vt)),
                            (Et = (zt = At("rect", Lt, {
                                width: 100,
                                height: 50,
                                x: 100,
                            })).getBoundingClientRect().width),
                            (zt.style[Bt] = "50% 50%"),
                            (zt.style[jt] = "scaleX(0.5)"),
                            (It =
                                Et === zt.getBoundingClientRect().width &&
                                !(k && Yt)),
                            Vt.removeChild(Lt)),
                        It),
                    Wt = function (t) {
                        var e,
                            i = _(
                                "svg",
                                (this.ownerSVGElement &&
                                    this.ownerSVGElement.getAttribute(
                                        "xmlns"
                                    )) ||
                                    "http://www.w3.org/2000/svg"
                            ),
                            r = this.parentNode,
                            n = this.nextSibling,
                            s = this.style.cssText;
                        if (
                            (Vt.appendChild(i),
                            i.appendChild(this),
                            (this.style.display = "block"),
                            t)
                        )
                            try {
                                (e = this.getBBox()),
                                    (this._originalGetBBox = this.getBBox),
                                    (this.getBBox = Wt);
                            } catch (t) {}
                        else
                            this._originalGetBBox &&
                                (e = this._originalGetBBox());
                        return (
                            n ? r.insertBefore(this, n) : r.appendChild(this),
                            Vt.removeChild(i),
                            (this.style.cssText = s),
                            e
                        );
                    },
                    Qt = function (t) {
                        return !(
                            !Ut ||
                            !t.getCTM ||
                            (t.parentNode && !t.ownerSVGElement) ||
                            !(function (t) {
                                try {
                                    return t.getBBox();
                                } catch (e) {
                                    return Wt.call(t, !0);
                                }
                            })(t)
                        );
                    },
                    Ht = [1, 0, 0, 1, 0, 0],
                    Zt = function (t, e) {
                        var i,
                            r,
                            n,
                            s,
                            a,
                            o,
                            l,
                            h = t._gsTransform || new Gt(),
                            u = t.style;
                        if (
                            (jt
                                ? (r = _t(t, Xt, null, !0))
                                : t.currentStyle &&
                                  (r =
                                      (r = t.currentStyle.filter.match(Q)) &&
                                      4 === r.length
                                          ? [
                                                r[0].substr(4),
                                                Number(r[2].substr(4)),
                                                Number(r[1].substr(4)),
                                                r[3].substr(4),
                                                h.x || 0,
                                                h.y || 0,
                                            ].join(",")
                                          : ""),
                            (i =
                                !r ||
                                "none" === r ||
                                "matrix(1, 0, 0, 1, 0, 0)" === r),
                            jt &&
                                i &&
                                !t.offsetParent &&
                                t !== Vt &&
                                ((s = u.display),
                                (u.display = "block"),
                                ((l = t.parentNode) && t.offsetParent) ||
                                    ((a = 1),
                                    (o = t.nextSibling),
                                    Vt.appendChild(t)),
                                (i =
                                    !(r = _t(t, Xt, null, !0)) ||
                                    "none" === r ||
                                    "matrix(1, 0, 0, 1, 0, 0)" === r),
                                s ? (u.display = s) : ee(u, "display"),
                                a &&
                                    (o
                                        ? l.insertBefore(t, o)
                                        : l
                                        ? l.appendChild(t)
                                        : Vt.removeChild(t))),
                            (h.svg || (t.getCTM && Qt(t))) &&
                                (i &&
                                    -1 !== (u[jt] + "").indexOf("matrix") &&
                                    ((r = u[jt]), (i = 0)),
                                (n = t.getAttribute("transform")),
                                i &&
                                    n &&
                                    ((r =
                                        "matrix(" +
                                        (n =
                                            t.transform.baseVal.consolidate()
                                                .matrix).a +
                                        "," +
                                        n.b +
                                        "," +
                                        n.c +
                                        "," +
                                        n.d +
                                        "," +
                                        n.e +
                                        "," +
                                        n.f +
                                        ")"),
                                    (i = 0))),
                            i)
                        )
                            return Ht;
                        for (
                            n = (r || "").match(L) || [], Ot = n.length;
                            -1 < --Ot;

                        )
                            (s = Number(n[Ot])),
                                (n[Ot] = (a = s - (s |= 0))
                                    ? ((1e5 * a + (a < 0 ? -0.5 : 0.5)) | 0) /
                                          1e5 +
                                      s
                                    : s);
                        return e && 6 < n.length
                            ? [n[0], n[1], n[4], n[5], n[12], n[13]]
                            : n;
                    },
                    $t = (st.getTransform = function (t, i, r, n) {
                        if (t._gsTransform && r && !n) return t._gsTransform;
                        var s,
                            o,
                            l,
                            h,
                            u,
                            _,
                            f = (r && t._gsTransform) || new Gt(),
                            c = f.scaleX < 0,
                            p =
                                (Yt &&
                                    (parseFloat(
                                        _t(t, Bt, i, !1, "0 0 0").split(" ")[2]
                                    ) ||
                                        f.zOrigin)) ||
                                0,
                            d = parseFloat(a.defaultTransformPerspective) || 0;
                        if (
                            ((f.svg = !(!t.getCTM || !Qt(t))),
                            f.svg &&
                                (Mt(
                                    t,
                                    _t(t, Bt, i, !1, "50% 50%") + "",
                                    f,
                                    t.getAttribute("data-svg-origin")
                                ),
                                (Ft = a.useSVGTransformAttr || qt)),
                            (s = Zt(t)) !== Ht)
                        ) {
                            if (16 === s.length) {
                                var m,
                                    g,
                                    y,
                                    v,
                                    x,
                                    T = s[0],
                                    w = s[1],
                                    b = s[2],
                                    P = s[3],
                                    S = s[4],
                                    O = s[5],
                                    k = s[6],
                                    C = s[7],
                                    R = s[8],
                                    A = s[9],
                                    M = s[10],
                                    D = s[12],
                                    F = s[13],
                                    L = s[14],
                                    z = s[11],
                                    E = Math.atan2(k, M);
                                f.zOrigin &&
                                    ((D = R * (L = -f.zOrigin) - s[12]),
                                    (F = A * L - s[13]),
                                    (L = M * L + f.zOrigin - s[14])),
                                    (f.rotationX = E * J),
                                    E &&
                                        ((m =
                                            S * (v = Math.cos(-E)) +
                                            R * (x = Math.sin(-E))),
                                        (g = O * v + A * x),
                                        (y = k * v + M * x),
                                        (R = S * -x + R * v),
                                        (A = O * -x + A * v),
                                        (M = k * -x + M * v),
                                        (z = C * -x + z * v),
                                        (S = m),
                                        (O = g),
                                        (k = y)),
                                    (E = Math.atan2(-b, M)),
                                    (f.rotationY = E * J),
                                    E &&
                                        ((g =
                                            w * (v = Math.cos(-E)) -
                                            A * (x = Math.sin(-E))),
                                        (y = b * v - M * x),
                                        (A = w * x + A * v),
                                        (M = b * x + M * v),
                                        (z = P * x + z * v),
                                        (T = m = T * v - R * x),
                                        (w = g),
                                        (b = y)),
                                    (E = Math.atan2(w, T)),
                                    (f.rotation = E * J),
                                    E &&
                                        ((m =
                                            T * (v = Math.cos(E)) +
                                            w * (x = Math.sin(E))),
                                        (g = S * v + O * x),
                                        (y = R * v + A * x),
                                        (w = w * v - T * x),
                                        (O = O * v - S * x),
                                        (A = A * v - R * x),
                                        (T = m),
                                        (S = g),
                                        (R = y)),
                                    f.rotationX &&
                                        359.9 <
                                            Math.abs(f.rotationX) +
                                                Math.abs(f.rotation) &&
                                        ((f.rotationX = f.rotation = 0),
                                        (f.rotationY = 180 - f.rotationY)),
                                    (E = Math.atan2(S, O)),
                                    (f.scaleX =
                                        ((1e5 *
                                            Math.sqrt(T * T + w * w + b * b) +
                                            0.5) |
                                            0) /
                                        1e5),
                                    (f.scaleY =
                                        ((1e5 * Math.sqrt(O * O + k * k) +
                                            0.5) |
                                            0) /
                                        1e5),
                                    (f.scaleZ =
                                        ((1e5 *
                                            Math.sqrt(R * R + A * A + M * M) +
                                            0.5) |
                                            0) /
                                        1e5),
                                    (T /= f.scaleX),
                                    (S /= f.scaleY),
                                    (w /= f.scaleX),
                                    (O /= f.scaleY),
                                    2e-5 < Math.abs(E)
                                        ? ((f.skewX = E * J),
                                          (S = 0),
                                          "simple" !== f.skewType &&
                                              (f.scaleY *= 1 / Math.cos(E)))
                                        : (f.skewX = 0),
                                    (f.perspective = z
                                        ? 1 / (z < 0 ? -z : z)
                                        : 0),
                                    (f.x = D),
                                    (f.y = F),
                                    (f.z = L),
                                    f.svg &&
                                        ((f.x -=
                                            f.xOrigin -
                                            (f.xOrigin * T - f.yOrigin * S)),
                                        (f.y -=
                                            f.yOrigin -
                                            (f.yOrigin * w - f.xOrigin * O)));
                            } else if (
                                !Yt ||
                                n ||
                                !s.length ||
                                f.x !== s[4] ||
                                f.y !== s[5] ||
                                (!f.rotationX && !f.rotationY)
                            ) {
                                var I = 6 <= s.length,
                                    N = I ? s[0] : 1,
                                    j = s[1] || 0,
                                    X = s[2] || 0,
                                    B = I ? s[3] : 1;
                                (f.x = s[4] || 0),
                                    (f.y = s[5] || 0),
                                    (l = Math.sqrt(N * N + j * j)),
                                    (h = Math.sqrt(B * B + X * X)),
                                    (u =
                                        N || j
                                            ? Math.atan2(j, N) * J
                                            : f.rotation || 0),
                                    (_ =
                                        X || B
                                            ? Math.atan2(X, B) * J + u
                                            : f.skewX || 0),
                                    (f.scaleX = l),
                                    (f.scaleY = h),
                                    (f.rotation = u),
                                    (f.skewX = _),
                                    Yt &&
                                        ((f.rotationX = f.rotationY = f.z = 0),
                                        (f.perspective = d),
                                        (f.scaleZ = 1)),
                                    f.svg &&
                                        ((f.x -=
                                            f.xOrigin -
                                            (f.xOrigin * N + f.yOrigin * X)),
                                        (f.y -=
                                            f.yOrigin -
                                            (f.xOrigin * j + f.yOrigin * B)));
                            }
                            for (o in (90 < Math.abs(f.skewX) &&
                                Math.abs(f.skewX) < 270 &&
                                (c
                                    ? ((f.scaleX *= -1),
                                      (f.skewX += f.rotation <= 0 ? 180 : -180),
                                      (f.rotation +=
                                          f.rotation <= 0 ? 180 : -180))
                                    : ((f.scaleY *= -1),
                                      (f.skewX += f.skewX <= 0 ? 180 : -180))),
                            (f.zOrigin = p),
                            f))
                                f[o] < 2e-5 && -2e-5 < f[o] && (f[o] = 0);
                        }
                        return (
                            r &&
                                (t._gsTransform = f).svg &&
                                (Ft && t.style[jt]
                                    ? e.delayedCall(0.001, function () {
                                          ee(t.style, jt);
                                      })
                                    : !Ft &&
                                      t.getAttribute("transform") &&
                                      e.delayedCall(0.001, function () {
                                          t.removeAttribute("transform");
                                      })),
                            f
                        );
                    }),
                    Kt =
                        (st.set3DTransformRatio =
                        st.setTransformRatio =
                            function (t) {
                                var e,
                                    i,
                                    r,
                                    n,
                                    s,
                                    a,
                                    o,
                                    l,
                                    h,
                                    u,
                                    _,
                                    f,
                                    c,
                                    p,
                                    d,
                                    m,
                                    g,
                                    y,
                                    v,
                                    x,
                                    T,
                                    w,
                                    b,
                                    P = this.data,
                                    S = this.t.style,
                                    O = P.rotation,
                                    C = P.rotationX,
                                    R = P.rotationY,
                                    A = P.scaleX,
                                    M = P.scaleY,
                                    D = P.scaleZ,
                                    F = P.x,
                                    L = P.y,
                                    z = P.z,
                                    E = P.svg,
                                    I = P.perspective,
                                    N = P.force3D,
                                    j = P.skewY,
                                    X = P.skewX;
                                if (
                                    (j && ((X += j), (O += j)),
                                    !(
                                        (((1 !== t && 0 !== t) ||
                                            "auto" !== N ||
                                            (this.tween._totalTime !==
                                                this.tween._totalDuration &&
                                                this.tween._totalTime)) &&
                                            N) ||
                                        z ||
                                        I ||
                                        R ||
                                        C ||
                                        1 !== D
                                    ) ||
                                        (Ft && E) ||
                                        !Yt)
                                )
                                    O || X || E
                                        ? ((O *= K),
                                          (w = X * K),
                                          (b = 1e5),
                                          (i = Math.cos(O) * A),
                                          (s = Math.sin(O) * A),
                                          (r = Math.sin(O - w) * -M),
                                          (a = Math.cos(O - w) * M),
                                          w &&
                                              "simple" === P.skewType &&
                                              ((e = Math.tan(w - j * K)),
                                              (r *= e = Math.sqrt(1 + e * e)),
                                              (a *= e),
                                              j &&
                                                  ((e = Math.tan(j * K)),
                                                  (i *= e =
                                                      Math.sqrt(1 + e * e)),
                                                  (s *= e))),
                                          E &&
                                              ((F +=
                                                  P.xOrigin -
                                                  (P.xOrigin * i +
                                                      P.yOrigin * r) +
                                                  P.xOffset),
                                              (L +=
                                                  P.yOrigin -
                                                  (P.xOrigin * s +
                                                      P.yOrigin * a) +
                                                  P.yOffset),
                                              Ft &&
                                                  (P.xPercent || P.yPercent) &&
                                                  ((d = this.t.getBBox()),
                                                  (F +=
                                                      0.01 *
                                                      P.xPercent *
                                                      d.width),
                                                  (L +=
                                                      0.01 *
                                                      P.yPercent *
                                                      d.height)),
                                              F < (d = 1e-6) &&
                                                  -d < F &&
                                                  (F = 0),
                                              L < d && -d < L && (L = 0)),
                                          (v =
                                              ((i * b) | 0) / b +
                                              "," +
                                              ((s * b) | 0) / b +
                                              "," +
                                              ((r * b) | 0) / b +
                                              "," +
                                              ((a * b) | 0) / b +
                                              "," +
                                              F +
                                              "," +
                                              L +
                                              ")"),
                                          E && Ft
                                              ? this.t.setAttribute(
                                                    "transform",
                                                    "matrix(" + v
                                                )
                                              : (S[jt] =
                                                    (P.xPercent || P.yPercent
                                                        ? "translate(" +
                                                          P.xPercent +
                                                          "%," +
                                                          P.yPercent +
                                                          "%) matrix("
                                                        : "matrix(") + v))
                                        : (S[jt] =
                                              (P.xPercent || P.yPercent
                                                  ? "translate(" +
                                                    P.xPercent +
                                                    "%," +
                                                    P.yPercent +
                                                    "%) matrix("
                                                  : "matrix(") +
                                              A +
                                              ",0,0," +
                                              M +
                                              "," +
                                              F +
                                              "," +
                                              L +
                                              ")");
                                else {
                                    if (
                                        (k &&
                                            (A < (d = 1e-4) &&
                                                -d < A &&
                                                (A = D = 2e-5),
                                            M < d && -d < M && (M = D = 2e-5),
                                            !I ||
                                                P.z ||
                                                P.rotationX ||
                                                P.rotationY ||
                                                (I = 0)),
                                        O || X)
                                    )
                                        (O *= K),
                                            (m = i = Math.cos(O)),
                                            (g = s = Math.sin(O)),
                                            X &&
                                                ((O -= X * K),
                                                (m = Math.cos(O)),
                                                (g = Math.sin(O)),
                                                "simple" === P.skewType &&
                                                    ((e = Math.tan(
                                                        (X - j) * K
                                                    )),
                                                    (m *= e =
                                                        Math.sqrt(1 + e * e)),
                                                    (g *= e),
                                                    P.skewY &&
                                                        ((e = Math.tan(j * K)),
                                                        (i *= e =
                                                            Math.sqrt(
                                                                1 + e * e
                                                            )),
                                                        (s *= e)))),
                                            (r = -g),
                                            (a = m);
                                    else {
                                        if (!(R || C || 1 !== D || I || E))
                                            return void (S[jt] =
                                                (P.xPercent || P.yPercent
                                                    ? "translate(" +
                                                      P.xPercent +
                                                      "%," +
                                                      P.yPercent +
                                                      "%) translate3d("
                                                    : "translate3d(") +
                                                F +
                                                "px," +
                                                L +
                                                "px," +
                                                z +
                                                "px)" +
                                                (1 !== A || 1 !== M
                                                    ? " scale(" +
                                                      A +
                                                      "," +
                                                      M +
                                                      ")"
                                                    : ""));
                                        (i = a = 1), (r = s = 0);
                                    }
                                    (u = 1),
                                        (n = o = l = h = _ = f = 0),
                                        (c = I ? -1 / I : 0),
                                        (p = P.zOrigin),
                                        (d = 1e-6),
                                        (x = ","),
                                        (T = "0"),
                                        (O = R * K) &&
                                            ((m = Math.cos(O)),
                                            (_ = c * (l = -(g = Math.sin(O)))),
                                            (n = i * g),
                                            (o = s * g),
                                            (c *= u = m),
                                            (i *= m),
                                            (s *= m)),
                                        (O = C * K) &&
                                            ((e =
                                                r * (m = Math.cos(O)) +
                                                n * (g = Math.sin(O))),
                                            (y = a * m + o * g),
                                            (h = u * g),
                                            (f = c * g),
                                            (n = r * -g + n * m),
                                            (o = a * -g + o * m),
                                            (u *= m),
                                            (c *= m),
                                            (r = e),
                                            (a = y)),
                                        1 !== D &&
                                            ((n *= D),
                                            (o *= D),
                                            (u *= D),
                                            (c *= D)),
                                        1 !== M &&
                                            ((r *= M),
                                            (a *= M),
                                            (h *= M),
                                            (f *= M)),
                                        1 !== A &&
                                            ((i *= A),
                                            (s *= A),
                                            (l *= A),
                                            (_ *= A)),
                                        (p || E) &&
                                            (p &&
                                                ((F += n * -p),
                                                (L += o * -p),
                                                (z += u * -p + p)),
                                            E &&
                                                ((F +=
                                                    P.xOrigin -
                                                    (P.xOrigin * i +
                                                        P.yOrigin * r) +
                                                    P.xOffset),
                                                (L +=
                                                    P.yOrigin -
                                                    (P.xOrigin * s +
                                                        P.yOrigin * a) +
                                                    P.yOffset)),
                                            F < d && -d < F && (F = T),
                                            L < d && -d < L && (L = T),
                                            z < d && -d < z && (z = 0)),
                                        (v =
                                            P.xPercent || P.yPercent
                                                ? "translate(" +
                                                  P.xPercent +
                                                  "%," +
                                                  P.yPercent +
                                                  "%) matrix3d("
                                                : "matrix3d("),
                                        (v +=
                                            (i < d && -d < i ? T : i) +
                                            x +
                                            (s < d && -d < s ? T : s) +
                                            x +
                                            (l < d && -d < l ? T : l)),
                                        (v +=
                                            x +
                                            (_ < d && -d < _ ? T : _) +
                                            x +
                                            (r < d && -d < r ? T : r) +
                                            x +
                                            (a < d && -d < a ? T : a)),
                                        C || R || 1 !== D
                                            ? ((v +=
                                                  x +
                                                  (h < d && -d < h ? T : h) +
                                                  x +
                                                  (f < d && -d < f ? T : f) +
                                                  x +
                                                  (n < d && -d < n ? T : n)),
                                              (v +=
                                                  x +
                                                  (o < d && -d < o ? T : o) +
                                                  x +
                                                  (u < d && -d < u ? T : u) +
                                                  x +
                                                  (c < d && -d < c ? T : c) +
                                                  x))
                                            : (v += ",0,0,0,0,1,0,"),
                                        (v +=
                                            F +
                                            x +
                                            L +
                                            x +
                                            z +
                                            x +
                                            (I ? 1 + -z / I : 1) +
                                            ")"),
                                        (S[jt] = v);
                                }
                            });
                function Jt(t) {
                    var e,
                        i = this.t,
                        r = i.filter || _t(this.data, "filter") || "",
                        n = (this.s + this.c * t) | 0;
                    100 == n &&
                        (e =
                            -1 === r.indexOf("atrix(") &&
                            -1 === r.indexOf("radient(") &&
                            -1 === r.indexOf("oader(")
                                ? (i.removeAttribute("filter"),
                                  !_t(this.data, "filter"))
                                : ((i.filter = r.replace(Y, "")), !0)),
                        e ||
                            (this.xn1 &&
                                (i.filter = r =
                                    r || "alpha(opacity=" + n + ")"),
                            -1 === r.indexOf("pacity")
                                ? (0 == n && this.xn1) ||
                                  (i.filter = r + " alpha(opacity=" + n + ")")
                                : (i.filter = r.replace(X, "opacity=" + n)));
                }
                function te(t) {
                    if (((this.t._gsClassPT = this), 1 === t || 0 === t)) {
                        this.t.setAttribute("class", 0 === t ? this.b : this.e);
                        for (var e = this.data, i = this.t.style; e; )
                            e.v ? (i[e.p] = e.v) : ee(i, e.p), (e = e._next);
                        1 === t &&
                            this.t._gsClassPT === this &&
                            (this.t._gsClassPT = null);
                    } else
                        this.t.getAttribute("class") !== this.e &&
                            this.t.setAttribute("class", this.e);
                }
                ((h = Gt.prototype).x =
                    h.y =
                    h.z =
                    h.skewX =
                    h.skewY =
                    h.rotation =
                    h.rotationX =
                    h.rotationY =
                    h.zOrigin =
                    h.xPercent =
                    h.yPercent =
                    h.xOffset =
                    h.yOffset =
                        0),
                    (h.scaleX = h.scaleY = h.scaleZ = 1),
                    Ct(
                        "transform,scale,scaleX,scaleY,scaleZ,x,y,z,rotation,rotationX,rotationY,rotationZ,skewX,skewY,shortRotation,shortRotationX,shortRotationY,shortRotationZ,transformOrigin,svgOrigin,transformPerspective,directionalRotation,parseTransform,force3D,skewType,xPercent,yPercent,smoothOrigin",
                        {
                            parser: function (t, e, i, r, s, o, l) {
                                if (r._lastParsedTransform === l) return s;
                                var h =
                                    (r._lastParsedTransform = l).scale &&
                                    "function" == typeof l.scale
                                        ? l.scale
                                        : 0;
                                h && (l.scale = h(M, t));
                                var u,
                                    _,
                                    f,
                                    c,
                                    p,
                                    d,
                                    m,
                                    g,
                                    y,
                                    v = t._gsTransform,
                                    w = t.style,
                                    b = Nt.length,
                                    P = l,
                                    S = {},
                                    O = "transformOrigin",
                                    k = $t(t, n, !0, P.parseTransform),
                                    C =
                                        P.transform &&
                                        ("function" == typeof P.transform
                                            ? P.transform(M, A)
                                            : P.transform);
                                if (
                                    ((k.skewType =
                                        P.skewType ||
                                        k.skewType ||
                                        a.defaultSkewType),
                                    (r._transform = k),
                                    "rotationZ" in P &&
                                        (P.rotation = P.rotationZ),
                                    C && "string" == typeof C && jt)
                                )
                                    ((_ = rt.style)[jt] = C),
                                        (_.display = "block"),
                                        (_.position = "absolute"),
                                        -1 !== C.indexOf("%") &&
                                            ((_.width = _t(t, "width")),
                                            (_.height = _t(t, "height"))),
                                        it.body.appendChild(rt),
                                        (u = $t(rt, null, !1)),
                                        "simple" === k.skewType &&
                                            (u.scaleY *= Math.cos(u.skewX * K)),
                                        k.svg &&
                                            ((d = k.xOrigin),
                                            (m = k.yOrigin),
                                            (u.x -= k.xOffset),
                                            (u.y -= k.yOffset),
                                            (P.transformOrigin ||
                                                P.svgOrigin) &&
                                                ((C = {}),
                                                Mt(
                                                    t,
                                                    mt(P.transformOrigin),
                                                    C,
                                                    P.svgOrigin,
                                                    P.smoothOrigin,
                                                    !0
                                                ),
                                                (d = C.xOrigin),
                                                (m = C.yOrigin),
                                                (u.x -= C.xOffset - k.xOffset),
                                                (u.y -= C.yOffset - k.yOffset)),
                                            (d || m) &&
                                                ((g = Zt(rt, !0)),
                                                (u.x -=
                                                    d - (d * g[0] + m * g[2])),
                                                (u.y -=
                                                    m -
                                                    (d * g[1] + m * g[3])))),
                                        it.body.removeChild(rt),
                                        u.perspective ||
                                            (u.perspective = k.perspective),
                                        null != P.xPercent &&
                                            (u.xPercent = x(
                                                P.xPercent,
                                                k.xPercent
                                            )),
                                        null != P.yPercent &&
                                            (u.yPercent = x(
                                                P.yPercent,
                                                k.yPercent
                                            ));
                                else if ("object" == typeof P) {
                                    if (
                                        ((u = {
                                            scaleX: x(
                                                null != P.scaleX
                                                    ? P.scaleX
                                                    : P.scale,
                                                k.scaleX
                                            ),
                                            scaleY: x(
                                                null != P.scaleY
                                                    ? P.scaleY
                                                    : P.scale,
                                                k.scaleY
                                            ),
                                            scaleZ: x(P.scaleZ, k.scaleZ),
                                            x: x(P.x, k.x),
                                            y: x(P.y, k.y),
                                            z: x(P.z, k.z),
                                            xPercent: x(P.xPercent, k.xPercent),
                                            yPercent: x(P.yPercent, k.yPercent),
                                            perspective: x(
                                                P.transformPerspective,
                                                k.perspective
                                            ),
                                        }),
                                        null != (p = P.directionalRotation))
                                    )
                                        if ("object" == typeof p)
                                            for (_ in p) P[_] = p[_];
                                        else P.rotation = p;
                                    "string" == typeof P.x &&
                                        -1 !== P.x.indexOf("%") &&
                                        ((u.x = 0),
                                        (u.xPercent = x(P.x, k.xPercent))),
                                        "string" == typeof P.y &&
                                            -1 !== P.y.indexOf("%") &&
                                            ((u.y = 0),
                                            (u.yPercent = x(P.y, k.yPercent))),
                                        (u.rotation = T(
                                            "rotation" in P
                                                ? P.rotation
                                                : "shortRotation" in P
                                                ? P.shortRotation + "_short"
                                                : k.rotation,
                                            k.rotation,
                                            "rotation",
                                            S
                                        )),
                                        Yt &&
                                            ((u.rotationX = T(
                                                "rotationX" in P
                                                    ? P.rotationX
                                                    : "shortRotationX" in P
                                                    ? P.shortRotationX +
                                                      "_short"
                                                    : k.rotationX || 0,
                                                k.rotationX,
                                                "rotationX",
                                                S
                                            )),
                                            (u.rotationY = T(
                                                "rotationY" in P
                                                    ? P.rotationY
                                                    : "shortRotationY" in P
                                                    ? P.shortRotationY +
                                                      "_short"
                                                    : k.rotationY || 0,
                                                k.rotationY,
                                                "rotationY",
                                                S
                                            ))),
                                        (u.skewX = T(P.skewX, k.skewX)),
                                        (u.skewY = T(P.skewY, k.skewY));
                                }
                                for (
                                    Yt &&
                                        null != P.force3D &&
                                        ((k.force3D = P.force3D), (c = !0)),
                                        (f =
                                            k.force3D ||
                                            k.z ||
                                            k.rotationX ||
                                            k.rotationY ||
                                            u.z ||
                                            u.rotationX ||
                                            u.rotationY ||
                                            u.perspective) ||
                                            null == P.scale ||
                                            (u.scaleZ = 1);
                                    -1 < --b;

                                )
                                    (1e-6 < (C = u[(y = Nt[b])] - k[y]) ||
                                        C < -1e-6 ||
                                        null != P[y] ||
                                        null != tt[y]) &&
                                        ((c = !0),
                                        (s = new Pt(k, y, k[y], C, s)),
                                        y in S && (s.e = S[y]),
                                        (s.xs0 = 0),
                                        (s.plugin = o),
                                        r._overwriteProps.push(s.n));
                                return (
                                    (C =
                                        "function" == typeof P.transformOrigin
                                            ? P.transformOrigin(M, A)
                                            : P.transformOrigin),
                                    k.svg &&
                                        (C || P.svgOrigin) &&
                                        ((d = k.xOffset),
                                        (m = k.yOffset),
                                        Mt(
                                            t,
                                            mt(C),
                                            u,
                                            P.svgOrigin,
                                            P.smoothOrigin
                                        ),
                                        (s = wt(
                                            k,
                                            "xOrigin",
                                            (v ? k : u).xOrigin,
                                            u.xOrigin,
                                            s,
                                            O
                                        )),
                                        (s = wt(
                                            k,
                                            "yOrigin",
                                            (v ? k : u).yOrigin,
                                            u.yOrigin,
                                            s,
                                            O
                                        )),
                                        (d === k.xOffset && m === k.yOffset) ||
                                            ((s = wt(
                                                k,
                                                "xOffset",
                                                v ? d : k.xOffset,
                                                k.xOffset,
                                                s,
                                                O
                                            )),
                                            (s = wt(
                                                k,
                                                "yOffset",
                                                v ? m : k.yOffset,
                                                k.yOffset,
                                                s,
                                                O
                                            ))),
                                        (C = "0px 0px")),
                                    (C || (Yt && f && k.zOrigin)) &&
                                        (jt
                                            ? ((c = !0),
                                              (y = Bt),
                                              C ||
                                                  (C =
                                                      (C = (
                                                          _t(
                                                              t,
                                                              y,
                                                              n,
                                                              !1,
                                                              "50% 50%"
                                                          ) + ""
                                                      ).split(" "))[0] +
                                                      " " +
                                                      C[1] +
                                                      " " +
                                                      k.zOrigin +
                                                      "px"),
                                              (C += ""),
                                              ((s = new Pt(
                                                  w,
                                                  y,
                                                  0,
                                                  0,
                                                  s,
                                                  -1,
                                                  O
                                              )).b = w[y]),
                                              (s.plugin = o),
                                              Yt
                                                  ? ((_ = k.zOrigin),
                                                    (C = C.split(" ")),
                                                    (k.zOrigin =
                                                        (2 < C.length
                                                            ? parseFloat(C[2])
                                                            : _) || 0),
                                                    (s.xs0 = s.e =
                                                        C[0] +
                                                        " " +
                                                        (C[1] || "50%") +
                                                        " 0px"),
                                                    ((s = new Pt(
                                                        k,
                                                        "zOrigin",
                                                        0,
                                                        0,
                                                        s,
                                                        -1,
                                                        s.n
                                                    )).b = _),
                                                    (s.xs0 = s.e = k.zOrigin))
                                                  : (s.xs0 = s.e = C))
                                            : mt(C + "", k)),
                                    c &&
                                        (r._transformType =
                                            (k.svg && Ft) ||
                                            (!f && 3 !== this._transformType)
                                                ? 2
                                                : 3),
                                    h && (l.scale = h),
                                    s
                                );
                            },
                            allowFunc: !0,
                            prefix: !0,
                        }
                    ),
                    Ct("boxShadow", {
                        defaultValue: "0px 0px 0px 0px #999",
                        prefix: !0,
                        color: !0,
                        multi: !0,
                        keyword: "inset",
                    }),
                    Ct("clipPath", {
                        defaultValue: "inset(0%)",
                        prefix: !0,
                        multi: !0,
                        formatter: xt("inset(0% 0% 0% 0%)", !1, !0),
                    }),
                    Ct("borderRadius", {
                        defaultValue: "0px",
                        parser: function (t, e, i, s, a, o) {
                            e = this.format(e);
                            var l,
                                h,
                                u,
                                _,
                                f,
                                c,
                                d,
                                m,
                                g,
                                y,
                                v,
                                x,
                                T,
                                w,
                                b,
                                P,
                                S = [
                                    "borderTopLeftRadius",
                                    "borderTopRightRadius",
                                    "borderBottomRightRadius",
                                    "borderBottomLeftRadius",
                                ],
                                O = t.style;
                            for (
                                g = parseFloat(t.offsetWidth),
                                    y = parseFloat(t.offsetHeight),
                                    l = e.split(" "),
                                    h = 0;
                                h < S.length;
                                h++
                            )
                                this.p.indexOf("border") && (S[h] = p(S[h])),
                                    -1 !==
                                        (f = _ =
                                            _t(t, S[h], n, !1, "0px")).indexOf(
                                            " "
                                        ) &&
                                        ((f = (_ = f.split(" "))[0]),
                                        (_ = _[1])),
                                    (c = u = l[h]),
                                    (d = parseFloat(f)),
                                    (x = f.substr((d + "").length)),
                                    "" ===
                                        (v = (T = "=" === c.charAt(1))
                                            ? ((m = parseInt(
                                                  c.charAt(0) + "1",
                                                  10
                                              )),
                                              (c = c.substr(2)),
                                              (m *= parseFloat(c)),
                                              c.substr(
                                                  (m + "").length -
                                                      (m < 0 ? 1 : 0)
                                              ) || "")
                                            : ((m = parseFloat(c)),
                                              c.substr((m + "").length))) &&
                                        (v = r[i] || x),
                                    v !== x &&
                                        ((w = ft(t, "borderLeft", d, x)),
                                        (b = ft(t, "borderTop", d, x)),
                                        (_ =
                                            "%" === v
                                                ? ((f = (w / g) * 100 + "%"),
                                                  (b / y) * 100 + "%")
                                                : "em" === v
                                                ? ((f =
                                                      w /
                                                          (P = ft(
                                                              t,
                                                              "borderLeft",
                                                              1,
                                                              "em"
                                                          )) +
                                                      "em"),
                                                  b / P + "em")
                                                : ((f = w + "px"), b + "px")),
                                        T &&
                                            ((c = parseFloat(f) + m + v),
                                            (u = parseFloat(_) + m + v))),
                                    (a = St(
                                        O,
                                        S[h],
                                        f + " " + _,
                                        c + " " + u,
                                        !1,
                                        "0px",
                                        a
                                    ));
                            return a;
                        },
                        prefix: !0,
                        formatter: xt("0px 0px 0px 0px", !1, !0),
                    }),
                    Ct(
                        "borderBottomLeftRadius,borderBottomRightRadius,borderTopLeftRadius,borderTopRightRadius",
                        {
                            defaultValue: "0px",
                            parser: function (t, e, i, r, s, a) {
                                return St(
                                    t.style,
                                    i,
                                    this.format(_t(t, i, n, !1, "0px 0px")),
                                    this.format(e),
                                    !1,
                                    "0px",
                                    s
                                );
                            },
                            prefix: !0,
                            formatter: xt("0px 0px", !1, !0),
                        }
                    ),
                    Ct("backgroundPosition", {
                        defaultValue: "0 0",
                        parser: function (t, e, i, r, s, a) {
                            var o,
                                l,
                                h,
                                u,
                                _,
                                f,
                                c = "background-position",
                                p = n || d(t),
                                m = this.format(
                                    (p
                                        ? R
                                            ? p.getPropertyValue(c + "-x") +
                                              " " +
                                              p.getPropertyValue(c + "-y")
                                            : p.getPropertyValue(c)
                                        : t.currentStyle.backgroundPositionX +
                                          " " +
                                          t.currentStyle.backgroundPositionY) ||
                                        "0 0"
                                ),
                                g = this.format(e);
                            if (
                                (-1 !== m.indexOf("%")) !=
                                    (-1 !== g.indexOf("%")) &&
                                g.split(",").length < 2 &&
                                (f = _t(t, "backgroundImage").replace(q, "")) &&
                                "none" !== f
                            ) {
                                for (
                                    o = m.split(" "),
                                        l = g.split(" "),
                                        nt.setAttribute("src", f),
                                        h = 2;
                                    -1 < --h;

                                )
                                    (u = -1 !== (m = o[h]).indexOf("%")) !=
                                        (-1 !== l[h].indexOf("%")) &&
                                        ((_ =
                                            0 === h
                                                ? t.offsetWidth - nt.width
                                                : t.offsetHeight - nt.height),
                                        (o[h] = u
                                            ? (parseFloat(m) / 100) * _ + "px"
                                            : (parseFloat(m) / _) * 100 + "%"));
                                m = o.join(" ");
                            }
                            return this.parseComplex(t.style, m, g, s, a);
                        },
                        formatter: mt,
                    }),
                    Ct("backgroundSize", {
                        defaultValue: "0 0",
                        formatter: function (t) {
                            return "co" === (t += "").substr(0, 2)
                                ? t
                                : mt(-1 === t.indexOf(" ") ? t + " " + t : t);
                        },
                    }),
                    Ct("perspective", { defaultValue: "0px", prefix: !0 }),
                    Ct("perspectiveOrigin", {
                        defaultValue: "50% 50%",
                        prefix: !0,
                    }),
                    Ct("transformStyle", { prefix: !0 }),
                    Ct("backfaceVisibility", { prefix: !0 }),
                    Ct("userSelect", { prefix: !0 }),
                    Ct("margin", {
                        parser: Tt(
                            "marginTop,marginRight,marginBottom,marginLeft"
                        ),
                    }),
                    Ct("padding", {
                        parser: Tt(
                            "paddingTop,paddingRight,paddingBottom,paddingLeft"
                        ),
                    }),
                    Ct("clip", {
                        defaultValue: "rect(0px,0px,0px,0px)",
                        parser: function (t, e, i, r, s, a) {
                            var o, l, h;
                            return (
                                (e =
                                    R < 9
                                        ? ((l = t.currentStyle),
                                          (h = R < 8 ? " " : ","),
                                          (o =
                                              "rect(" +
                                              l.clipTop +
                                              h +
                                              l.clipRight +
                                              h +
                                              l.clipBottom +
                                              h +
                                              l.clipLeft +
                                              ")"),
                                          this.format(e).split(",").join(h))
                                        : ((o = this.format(
                                              _t(t, this.p, n, !1, this.dflt)
                                          )),
                                          this.format(e))),
                                this.parseComplex(t.style, o, e, s, a)
                            );
                        },
                    }),
                    Ct("textShadow", {
                        defaultValue: "0px 0px 0px #999",
                        color: !0,
                        multi: !0,
                    }),
                    Ct("autoRound,strictUnits", {
                        parser: function (t, e, i, r, n) {
                            return n;
                        },
                    }),
                    Ct("border", {
                        defaultValue: "0px solid #000",
                        parser: function (t, e, i, r, s, a) {
                            var o = _t(t, "borderTopWidth", n, !1, "0px"),
                                l = this.format(e).split(" "),
                                h = l[0].replace(j, "");
                            return (
                                "px" !== h &&
                                    (o =
                                        parseFloat(o) /
                                            ft(t, "borderTopWidth", 1, h) +
                                        h),
                                this.parseComplex(
                                    t.style,
                                    this.format(
                                        o +
                                            " " +
                                            _t(
                                                t,
                                                "borderTopStyle",
                                                n,
                                                !1,
                                                "solid"
                                            ) +
                                            " " +
                                            _t(
                                                t,
                                                "borderTopColor",
                                                n,
                                                !1,
                                                "#000"
                                            )
                                    ),
                                    l.join(" "),
                                    s,
                                    a
                                )
                            );
                        },
                        color: !0,
                        formatter: function (t) {
                            var e = t.split(" ");
                            return (
                                e[0] +
                                " " +
                                (e[1] || "solid") +
                                " " +
                                (t.match(vt) || ["#000"])[0]
                            );
                        },
                    }),
                    Ct("borderWidth", {
                        parser: Tt(
                            "borderTopWidth,borderRightWidth,borderBottomWidth,borderLeftWidth"
                        ),
                    }),
                    Ct("float,cssFloat,styleFloat", {
                        parser: function (t, e, i, r, n, s) {
                            var a = t.style,
                                o = "cssFloat" in a ? "cssFloat" : "styleFloat";
                            return new Pt(a, o, 0, 0, n, -1, i, !1, 0, a[o], e);
                        },
                    }),
                    Ct("opacity,alpha,autoAlpha", {
                        defaultValue: "1",
                        parser: function (t, e, i, r, s, a) {
                            var o = parseFloat(_t(t, "opacity", n, !1, "1")),
                                l = t.style,
                                h = "autoAlpha" === i;
                            return (
                                "string" == typeof e &&
                                    "=" === e.charAt(1) &&
                                    (e =
                                        ("-" === e.charAt(0) ? -1 : 1) *
                                            parseFloat(e.substr(2)) +
                                        o),
                                h &&
                                    1 === o &&
                                    "hidden" === _t(t, "visibility", n) &&
                                    0 !== e &&
                                    (o = 0),
                                ot
                                    ? (s = new Pt(l, "opacity", o, e - o, s))
                                    : (((s = new Pt(
                                          l,
                                          "opacity",
                                          100 * o,
                                          100 * (e - o),
                                          s
                                      )).xn1 = h ? 1 : 0),
                                      (l.zoom = 1),
                                      (s.type = 2),
                                      (s.b = "alpha(opacity=" + s.s + ")"),
                                      (s.e =
                                          "alpha(opacity=" + (s.s + s.c) + ")"),
                                      (s.data = t),
                                      (s.plugin = a),
                                      (s.setRatio = Jt)),
                                h &&
                                    (((s = new Pt(
                                        l,
                                        "visibility",
                                        0,
                                        0,
                                        s,
                                        -1,
                                        null,
                                        !1,
                                        0,
                                        0 !== o ? "inherit" : "hidden",
                                        0 === e ? "hidden" : "inherit"
                                    )).xs0 = "inherit"),
                                    r._overwriteProps.push(s.n),
                                    r._overwriteProps.push(i)),
                                s
                            );
                        },
                    });
                var ee = function (t, e) {
                    e &&
                        (t.removeProperty
                            ? (("ms" !== e.substr(0, 2) &&
                                  "webkit" !== e.substr(0, 6)) ||
                                  (e = "-" + e),
                              t.removeProperty(
                                  e.replace(U, "-$1").toLowerCase()
                              ))
                            : t.removeAttribute(e));
                };
                function ie(t) {
                    if (
                        (1 === t || 0 === t) &&
                        this.data._totalTime === this.data._totalDuration &&
                        "isFromStart" !== this.data.data
                    ) {
                        var e,
                            i,
                            r,
                            n,
                            s,
                            a = this.t.style,
                            o = l.transform.parse;
                        if ("all" === this.e) n = !(a.cssText = "");
                        else
                            for (
                                r = (e = this.e.split(" ").join("").split(","))
                                    .length;
                                -1 < --r;

                            )
                                (i = e[r]),
                                    l[i] &&
                                        (l[i].parse === o
                                            ? (n = !0)
                                            : (i =
                                                  "transformOrigin" === i
                                                      ? Bt
                                                      : l[i].p)),
                                    ee(a, i);
                        n &&
                            (ee(a, jt),
                            (s = this.t._gsTransform) &&
                                (s.svg &&
                                    (this.t.removeAttribute("data-svg-origin"),
                                    this.t.removeAttribute("transform")),
                                delete this.t._gsTransform));
                    }
                }
                for (
                    Ct("className", {
                        parser: function (t, e, r, s, a, o, l) {
                            var h,
                                u,
                                _,
                                f,
                                c,
                                p = t.getAttribute("class") || "",
                                d = t.style.cssText;
                            if (
                                (((a = s._classNamePT =
                                    new Pt(t, r, 0, 0, a, 2)).setRatio = te),
                                (a.pr = -11),
                                (i = !0),
                                (a.b = p),
                                (u = m(t, n)),
                                (_ = t._gsClassPT))
                            ) {
                                for (f = {}, c = _.data; c; )
                                    (f[c.p] = 1), (c = c._next);
                                _.setRatio(1);
                            }
                            return (
                                ((t._gsClassPT = a).e =
                                    "=" !== e.charAt(1)
                                        ? e
                                        : p.replace(
                                              new RegExp(
                                                  "(?:\\s|^)" +
                                                      e.substr(2) +
                                                      "(?![\\w-])"
                                              ),
                                              ""
                                          ) +
                                          ("+" === e.charAt(0)
                                              ? " " + e.substr(2)
                                              : "")),
                                t.setAttribute("class", a.e),
                                (h = g(t, u, m(t), l, f)),
                                t.setAttribute("class", p),
                                (a.data = h.firstMPT),
                                t.style.cssText !== d && (t.style.cssText = d),
                                (a.xfirst = s.parse(t, h.difs, a, o))
                            );
                        },
                    }),
                        Ct("clearProps", {
                            parser: function (t, e, r, n, s) {
                                return (
                                    ((s = new Pt(t, r, 0, 0, s, 2)).setRatio =
                                        ie),
                                    (s.e = e),
                                    (s.pr = -10),
                                    (s.data = n._tween),
                                    (i = !0),
                                    s
                                );
                            },
                        }),
                        h = "bezier,throwProps,physicsProps,physics2D".split(
                            ","
                        ),
                        Ot = h.length;
                    Ot--;

                )
                    Rt(h[Ot]);
                function re(t) {
                    (this.t[this.p] = this.e),
                        this.data._linkCSSP(this, this._next, null, !0);
                }
                ((h = a.prototype)._firstPT =
                    h._lastParsedTransform =
                    h._transform =
                        null),
                    (h._onInitTween = function (t, e, o, h) {
                        if (!t.nodeType) return !1;
                        (this._target = A = t),
                            (this._tween = o),
                            (this._vars = e),
                            (M = h),
                            (P = e.autoRound),
                            (i = !1),
                            (r = e.suffixMap || a.suffixMap),
                            (n = d(t)),
                            (s = this._overwriteProps);
                        var u,
                            _,
                            f,
                            c,
                            p,
                            y,
                            v,
                            x,
                            T,
                            w = t.style;
                        if (
                            (S &&
                                "" === w.zIndex &&
                                (("auto" !== (u = _t(t, "zIndex", n)) &&
                                    "" !== u) ||
                                    this._addLazySet(w, "zIndex", 0)),
                            "string" == typeof e &&
                                ((c = w.cssText),
                                (u = m(t, n)),
                                (w.cssText = c + ";" + e),
                                (u = g(t, u, m(t)).difs),
                                !ot &&
                                    B.test(e) &&
                                    (u.opacity = parseFloat(RegExp.$1)),
                                (e = u),
                                (w.cssText = c)),
                            e.className
                                ? (this._firstPT = _ =
                                      l.className.parse(
                                          t,
                                          e.className,
                                          "className",
                                          this,
                                          null,
                                          null,
                                          e
                                      ))
                                : (this._firstPT = _ = this.parse(t, e, null)),
                            this._transformType)
                        ) {
                            for (
                                T = 3 === this._transformType,
                                    jt
                                        ? O &&
                                          ((S = !0),
                                          "" === w.zIndex &&
                                              (("auto" !==
                                                  (v = _t(t, "zIndex", n)) &&
                                                  "" !== v) ||
                                                  this._addLazySet(
                                                      w,
                                                      "zIndex",
                                                      0
                                                  )),
                                          C &&
                                              this._addLazySet(
                                                  w,
                                                  "WebkitBackfaceVisibility",
                                                  this._vars
                                                      .WebkitBackfaceVisibility ||
                                                      (T ? "visible" : "hidden")
                                              ))
                                        : (w.zoom = 1),
                                    f = _;
                                f && f._next;

                            )
                                f = f._next;
                            (x = new Pt(t, "transform", 0, 0, null, 2)),
                                this._linkCSSP(x, null, f),
                                (x.setRatio = jt ? Kt : Dt),
                                (x.data = this._transform || $t(t, n, !0)),
                                (x.tween = o),
                                (x.pr = -1),
                                s.pop();
                        }
                        if (i) {
                            for (; _; ) {
                                for (y = _._next, f = c; f && f.pr > _.pr; )
                                    f = f._next;
                                (_._prev = f ? f._prev : p)
                                    ? (_._prev._next = _)
                                    : (c = _),
                                    (_._next = f) ? (f._prev = _) : (p = _),
                                    (_ = y);
                            }
                            this._firstPT = c;
                        }
                        return !0;
                    }),
                    (h.parse = function (t, e, i, s) {
                        var a,
                            o,
                            h,
                            u,
                            _,
                            f,
                            p,
                            m,
                            g,
                            v,
                            x = t.style;
                        for (a in e) {
                            if (
                                ((f = e[a]),
                                (o = l[a]),
                                "function" != typeof f ||
                                    (o && o.allowFunc) ||
                                    (f = f(M, A)),
                                o)
                            )
                                i = o.parse(t, f, a, this, i, s, e);
                            else {
                                if ("--" === a.substr(0, 2)) {
                                    this._tween._propLookup[a] =
                                        this._addTween.call(
                                            this._tween,
                                            t.style,
                                            "setProperty",
                                            d(t).getPropertyValue(a) + "",
                                            f + "",
                                            a,
                                            !1,
                                            a
                                        );
                                    continue;
                                }
                                (_ = _t(t, a, n) + ""),
                                    (g = "string" == typeof f),
                                    "color" === a ||
                                    "fill" === a ||
                                    "stroke" === a ||
                                    -1 !== a.indexOf("Color") ||
                                    (g && G.test(f))
                                        ? (g ||
                                              (f =
                                                  (3 < (f = yt(f)).length
                                                      ? "rgba("
                                                      : "rgb(") +
                                                  f.join(",") +
                                                  ")"),
                                          (i = St(
                                              x,
                                              a,
                                              _,
                                              f,
                                              !0,
                                              "transparent",
                                              i,
                                              0,
                                              s
                                          )))
                                        : g && $.test(f)
                                        ? (i = St(
                                              x,
                                              a,
                                              _,
                                              f,
                                              !0,
                                              null,
                                              i,
                                              0,
                                              s
                                          ))
                                        : ((p =
                                              (h = parseFloat(_)) || 0 === h
                                                  ? _.substr((h + "").length)
                                                  : ""),
                                          ("" !== _ && "auto" !== _) ||
                                              (p =
                                                  "width" === a ||
                                                  "height" === a
                                                      ? ((h = y(t, a, n)), "px")
                                                      : "left" === a ||
                                                        "top" === a
                                                      ? ((h = ct(t, a, n)),
                                                        "px")
                                                      : ((h =
                                                            "opacity" !== a
                                                                ? 0
                                                                : 1),
                                                        "")),
                                          "" ===
                                              (m = (v =
                                                  g && "=" === f.charAt(1))
                                                  ? ((u = parseInt(
                                                        f.charAt(0) + "1",
                                                        10
                                                    )),
                                                    (f = f.substr(2)),
                                                    (u *= parseFloat(f)),
                                                    f.replace(j, ""))
                                                  : ((u = parseFloat(f)),
                                                    g
                                                        ? f.replace(j, "")
                                                        : "")) &&
                                              (m = a in r ? r[a] : p),
                                          (f =
                                              u || 0 === u
                                                  ? (v ? u + h : u) + m
                                                  : e[a]),
                                          p === m ||
                                              ("" === m &&
                                                  "lineHeight" !== a) ||
                                              (!u && 0 !== u) ||
                                              !h ||
                                              ((h = ft(t, a, h, p)),
                                              "%" === m
                                                  ? ((h /=
                                                        ft(t, a, 100, "%") /
                                                        100),
                                                    !0 !== e.strictUnits &&
                                                        (_ = h + "%"))
                                                  : "em" === m ||
                                                    "rem" === m ||
                                                    "vw" === m ||
                                                    "vh" === m
                                                  ? (h /= ft(t, a, 1, m))
                                                  : "px" !== m &&
                                                    ((u = ft(t, a, u, m)),
                                                    (m = "px")),
                                              v &&
                                                  (u || 0 === u) &&
                                                  (f = u + h + m)),
                                          v && (u += h),
                                          (!h && 0 !== h) || (!u && 0 !== u)
                                              ? void 0 !== x[a] &&
                                                (f ||
                                                    (f + "" != "NaN" &&
                                                        null != f))
                                                  ? ((i = new Pt(
                                                        x,
                                                        a,
                                                        u || h || 0,
                                                        0,
                                                        i,
                                                        -1,
                                                        a,
                                                        !1,
                                                        0,
                                                        _,
                                                        f
                                                    )).xs0 =
                                                        "none" !== f ||
                                                        ("display" !== a &&
                                                            -1 ===
                                                                a.indexOf(
                                                                    "Style"
                                                                ))
                                                            ? f
                                                            : _)
                                                  : c(
                                                        "invalid " +
                                                            a +
                                                            " tween value: " +
                                                            e[a]
                                                    )
                                              : ((i = new Pt(
                                                    x,
                                                    a,
                                                    h,
                                                    u - h,
                                                    i,
                                                    0,
                                                    a,
                                                    !1 !== P &&
                                                        ("px" === m ||
                                                            "zIndex" === a),
                                                    0,
                                                    _,
                                                    f
                                                )).xs0 = m));
                            }
                            s && i && !i.plugin && (i.plugin = s);
                        }
                        return i;
                    }),
                    (h.setRatio = function (t) {
                        var e,
                            i,
                            r,
                            n = this._firstPT;
                        if (
                            1 !== t ||
                            (this._tween._time !== this._tween._duration &&
                                0 !== this._tween._time)
                        )
                            if (
                                t ||
                                (this._tween._time !== this._tween._duration &&
                                    0 !== this._tween._time) ||
                                -1e-6 === this._tween._rawPrevTime
                            )
                                for (; n; ) {
                                    if (
                                        ((e = n.c * t + n.s),
                                        n.r
                                            ? (e = n.r(e))
                                            : e < 1e-6 && -1e-6 < e && (e = 0),
                                        n.type)
                                    )
                                        if (1 === n.type)
                                            if (2 === (r = n.l))
                                                n.t[n.p] =
                                                    n.xs0 +
                                                    e +
                                                    n.xs1 +
                                                    n.xn1 +
                                                    n.xs2;
                                            else if (3 === r)
                                                n.t[n.p] =
                                                    n.xs0 +
                                                    e +
                                                    n.xs1 +
                                                    n.xn1 +
                                                    n.xs2 +
                                                    n.xn2 +
                                                    n.xs3;
                                            else if (4 === r)
                                                n.t[n.p] =
                                                    n.xs0 +
                                                    e +
                                                    n.xs1 +
                                                    n.xn1 +
                                                    n.xs2 +
                                                    n.xn2 +
                                                    n.xs3 +
                                                    n.xn3 +
                                                    n.xs4;
                                            else if (5 === r)
                                                n.t[n.p] =
                                                    n.xs0 +
                                                    e +
                                                    n.xs1 +
                                                    n.xn1 +
                                                    n.xs2 +
                                                    n.xn2 +
                                                    n.xs3 +
                                                    n.xn3 +
                                                    n.xs4 +
                                                    n.xn4 +
                                                    n.xs5;
                                            else {
                                                for (
                                                    i = n.xs0 + e + n.xs1,
                                                        r = 1;
                                                    r < n.l;
                                                    r++
                                                )
                                                    i +=
                                                        n["xn" + r] +
                                                        n["xs" + (r + 1)];
                                                n.t[n.p] = i;
                                            }
                                        else
                                            -1 === n.type
                                                ? (n.t[n.p] = n.xs0)
                                                : n.setRatio && n.setRatio(t);
                                    else n.t[n.p] = e + n.xs0;
                                    n = n._next;
                                }
                            else
                                for (; n; )
                                    2 !== n.type
                                        ? (n.t[n.p] = n.b)
                                        : n.setRatio(t),
                                        (n = n._next);
                        else
                            for (; n; ) {
                                if (2 !== n.type)
                                    if (n.r && -1 !== n.type)
                                        if (((e = n.r(n.s + n.c)), n.type)) {
                                            if (1 === n.type) {
                                                for (
                                                    r = n.l,
                                                        i = n.xs0 + e + n.xs1,
                                                        r = 1;
                                                    r < n.l;
                                                    r++
                                                )
                                                    i +=
                                                        n["xn" + r] +
                                                        n["xs" + (r + 1)];
                                                n.t[n.p] = i;
                                            }
                                        } else n.t[n.p] = e + n.xs0;
                                    else n.t[n.p] = n.e;
                                else n.setRatio(t);
                                n = n._next;
                            }
                    }),
                    (h._enableTransforms = function (t) {
                        (this._transform =
                            this._transform || $t(this._target, n, !0)),
                            (this._transformType =
                                (this._transform.svg && Ft) ||
                                (!t && 3 !== this._transformType)
                                    ? 2
                                    : 3);
                    }),
                    (h._addLazySet = function (t, e, i) {
                        var r = (this._firstPT = new Pt(
                            t,
                            e,
                            0,
                            0,
                            this._firstPT,
                            2
                        ));
                        (r.e = i), (r.setRatio = re), (r.data = this);
                    }),
                    (h._linkCSSP = function (t, e, i, r) {
                        return (
                            t &&
                                (e && (e._prev = t),
                                t._next && (t._next._prev = t._prev),
                                t._prev
                                    ? (t._prev._next = t._next)
                                    : this._firstPT === t &&
                                      ((this._firstPT = t._next), (r = !0)),
                                i
                                    ? (i._next = t)
                                    : r ||
                                      null !== this._firstPT ||
                                      (this._firstPT = t),
                                (t._next = e),
                                (t._prev = i)),
                            t
                        );
                    }),
                    (h._mod = function (t) {
                        for (var e = this._firstPT; e; )
                            "function" == typeof t[e.p] && (e.r = t[e.p]),
                                (e = e._next);
                    }),
                    (h._kill = function (e) {
                        var i,
                            r,
                            n,
                            s = e;
                        if (e.autoAlpha || e.alpha) {
                            for (r in ((s = {}), e)) s[r] = e[r];
                            (s.opacity = 1), s.autoAlpha && (s.visibility = 1);
                        }
                        for (
                            e.className &&
                                (i = this._classNamePT) &&
                                ((n = i.xfirst) && n._prev
                                    ? this._linkCSSP(
                                          n._prev,
                                          i._next,
                                          n._prev._prev
                                      )
                                    : n === this._firstPT &&
                                      (this._firstPT = i._next),
                                i._next &&
                                    this._linkCSSP(
                                        i._next,
                                        i._next._next,
                                        n._prev
                                    ),
                                (this._classNamePT = null)),
                                i = this._firstPT;
                            i;

                        )
                            i.plugin &&
                                i.plugin !== r &&
                                i.plugin._kill &&
                                (i.plugin._kill(e), (r = i.plugin)),
                                (i = i._next);
                        return t.prototype._kill.call(this, s);
                    });
                var ne = function (t, e, i) {
                    var r, n, s, a;
                    if (t.slice) for (n = t.length; -1 < --n; ) ne(t[n], e, i);
                    else
                        for (n = (r = t.childNodes).length; -1 < --n; )
                            (a = (s = r[n]).type),
                                s.style && (e.push(m(s)), i && i.push(s)),
                                (1 !== a && 9 !== a && 11 !== a) ||
                                    !s.childNodes.length ||
                                    ne(s, e, i);
                };
                return (
                    (a.cascadeTo = function (t, i, r) {
                        var n,
                            s,
                            a,
                            o,
                            l = e.to(t, i, r),
                            h = [l],
                            u = [],
                            _ = [],
                            f = [],
                            c = e._internals.reservedProps;
                        for (
                            t = l._targets || l.target,
                                ne(t, u, f),
                                l.render(i, !0, !0),
                                ne(t, _),
                                l.render(0, !0, !0),
                                l._enabled(!0),
                                n = f.length;
                            -1 < --n;

                        )
                            if ((s = g(f[n], u[n], _[n])).firstMPT) {
                                for (a in ((s = s.difs), r))
                                    c[a] && (s[a] = r[a]);
                                for (a in ((o = {}), s)) o[a] = u[n][a];
                                h.push(e.fromTo(f[n], i, o, s));
                            }
                        return h;
                    }),
                    t.activate([a]),
                    a
                );
            },
            !0
        ),
        ((i = _gsScope._gsDefine.plugin({
            propName: "roundProps",
            version: "1.7.0",
            priority: -1,
            API: 2,
            init: function (t, e, i) {
                return (this._tween = i), !0;
            },
        }).prototype)._onInitAllProps = function () {
            var i,
                r,
                n,
                s,
                a = this._tween,
                o = a.vars.roundProps,
                l = {},
                h = a._propLookup.roundProps;
            if ("object" != typeof o || o.push)
                for (
                    "string" == typeof o && (o = o.split(",")), n = o.length;
                    -1 < --n;

                )
                    l[o[n]] = Math.round;
            else for (s in o) l[s] = t(o[s]);
            for (s in l)
                for (i = a._firstPT; i; )
                    (r = i._next),
                        i.pg
                            ? i.t._mod(l)
                            : i.n === s &&
                              (2 === i.f && i.t
                                  ? e(i.t._firstPT, l[s])
                                  : (this._add(i.t, s, i.s, i.c, l[s]),
                                    r && (r._prev = i._prev),
                                    i._prev
                                        ? (i._prev._next = r)
                                        : a._firstPT === i && (a._firstPT = r),
                                    (i._next = i._prev = null),
                                    (a._propLookup[s] = h))),
                        (i = r);
            return !1;
        }),
        (i._add = function (t, e, i, r, n) {
            this._addTween(t, e, i, i + r, e, n || Math.round),
                this._overwriteProps.push(e);
        }),
        _gsScope._gsDefine.plugin({
            propName: "attr",
            API: 2,
            version: "0.6.1",
            init: function (t, e, i, r) {
                var n, s;
                if ("function" != typeof t.setAttribute) return !1;
                for (n in e)
                    "function" == typeof (s = e[n]) && (s = s(r, t)),
                        this._addTween(
                            t,
                            "setAttribute",
                            t.getAttribute(n) + "",
                            s + "",
                            n,
                            !1,
                            n
                        ),
                        this._overwriteProps.push(n);
                return !0;
            },
        }),
        (_gsScope._gsDefine.plugin({
            propName: "directionalRotation",
            version: "0.3.1",
            API: 2,
            init: function (t, e, i, r) {
                "object" != typeof e && (e = { rotation: e }),
                    (this.finals = {});
                var n,
                    s,
                    a,
                    o,
                    l,
                    h,
                    u = !0 === e.useRadians ? 2 * Math.PI : 360;
                for (n in e)
                    "useRadians" !== n &&
                        ("function" == typeof (o = e[n]) && (o = o(r, t)),
                        (s = (h = (o + "").split("_"))[0]),
                        (a = parseFloat(
                            "function" != typeof t[n]
                                ? t[n]
                                : t[
                                      n.indexOf("set") ||
                                      "function" !=
                                          typeof t["get" + n.substr(3)]
                                          ? n
                                          : "get" + n.substr(3)
                                  ]()
                        )),
                        (l =
                            (o = this.finals[n] =
                                "string" == typeof s && "=" === s.charAt(1)
                                    ? a +
                                      parseInt(s.charAt(0) + "1", 10) *
                                          Number(s.substr(2))
                                    : Number(s) || 0) - a),
                        h.length &&
                            (-1 !== (s = h.join("_")).indexOf("short") &&
                                (l %= u) != l % (u / 2) &&
                                (l = l < 0 ? l + u : l - u),
                            -1 !== s.indexOf("_cw") && l < 0
                                ? (l =
                                      ((l + 9999999999 * u) % u) -
                                      ((l / u) | 0) * u)
                                : -1 !== s.indexOf("ccw") &&
                                  0 < l &&
                                  (l =
                                      ((l - 9999999999 * u) % u) -
                                      ((l / u) | 0) * u)),
                        (1e-6 < l || l < -1e-6) &&
                            (this._addTween(t, n, a, a + l, n),
                            this._overwriteProps.push(n)));
                return !0;
            },
            set: function (t) {
                var e;
                if (1 !== t) this._super.setRatio.call(this, t);
                else
                    for (e = this._firstPT; e; )
                        e.f
                            ? e.t[e.p](this.finals[e.p])
                            : (e.t[e.p] = this.finals[e.p]),
                            (e = e._next);
            },
        })._autoCSS = !0),
        _gsScope._gsDefine(
            "easing.Back",
            ["easing.Ease"],
            function (t) {
                function e(e, i) {
                    var r = c("easing." + e, function () {}, !0),
                        n = (r.prototype = new t());
                    return (n.constructor = r), (n.getRatio = i), r;
                }
                function i(t, e, i, r, n) {
                    var s = c(
                        "easing." + t,
                        {
                            easeOut: new e(),
                            easeIn: new i(),
                            easeInOut: new r(),
                        },
                        !0
                    );
                    return p(s, t), s;
                }
                function r(t, e, i) {
                    (this.t = t),
                        (this.v = e),
                        i &&
                            ((((this.next = i).prev = this).c = i.v - e),
                            (this.gap = i.t - t));
                }
                function n(e, i) {
                    var r = c(
                            "easing." + e,
                            function (t) {
                                (this._p1 = t || 0 === t ? t : 1.70158),
                                    (this._p2 = 1.525 * this._p1);
                            },
                            !0
                        ),
                        n = (r.prototype = new t());
                    return (
                        (n.constructor = r),
                        (n.getRatio = i),
                        (n.config = function (t) {
                            return new r(t);
                        }),
                        r
                    );
                }
                var s,
                    a,
                    o,
                    l,
                    h = _gsScope.GreenSockGlobals || _gsScope,
                    u = h.com.greensock,
                    _ = 2 * Math.PI,
                    f = Math.PI / 2,
                    c = u._class,
                    p = t.register || function () {},
                    d = i(
                        "Back",
                        n("BackOut", function (t) {
                            return (
                                --t * t * ((this._p1 + 1) * t + this._p1) + 1
                            );
                        }),
                        n("BackIn", function (t) {
                            return t * t * ((this._p1 + 1) * t - this._p1);
                        }),
                        n("BackInOut", function (t) {
                            return (t *= 2) < 1
                                ? 0.5 * t * t * ((this._p2 + 1) * t - this._p2)
                                : 0.5 *
                                      ((t -= 2) *
                                          t *
                                          ((this._p2 + 1) * t + this._p2) +
                                          2);
                        })
                    ),
                    m = c(
                        "easing.SlowMo",
                        function (t, e, i) {
                            (e = e || 0 === e ? e : 0.7),
                                null == t ? (t = 0.7) : 1 < t && (t = 1),
                                (this._p = 1 !== t ? e : 0),
                                (this._p1 = (1 - t) / 2),
                                (this._p2 = t),
                                (this._p3 = this._p1 + this._p2),
                                (this._calcEnd = !0 === i);
                        },
                        !0
                    ),
                    g = (m.prototype = new t());
                return (
                    (g.constructor = m),
                    (g.getRatio = function (t) {
                        var e = t + (0.5 - t) * this._p;
                        return t < this._p1
                            ? this._calcEnd
                                ? 1 - (t = 1 - t / this._p1) * t
                                : e - (t = 1 - t / this._p1) * t * t * t * e
                            : t > this._p3
                            ? this._calcEnd
                                ? 1 === t
                                    ? 0
                                    : 1 - (t = (t - this._p3) / this._p1) * t
                                : e +
                                  (t - e) *
                                      (t = (t - this._p3) / this._p1) *
                                      t *
                                      t *
                                      t
                            : this._calcEnd
                            ? 1
                            : e;
                    }),
                    (m.ease = new m(0.7, 0.7)),
                    (g.config = m.config =
                        function (t, e, i) {
                            return new m(t, e, i);
                        }),
                    ((g = (s = c(
                        "easing.SteppedEase",
                        function (t, e) {
                            (t = t || 1),
                                (this._p1 = 1 / t),
                                (this._p2 = t + (e ? 0 : 1)),
                                (this._p3 = e ? 1 : 0);
                        },
                        !0
                    )).prototype =
                        new t()).constructor = s),
                    (g.getRatio = function (t) {
                        return (
                            t < 0 ? (t = 0) : 1 <= t && (t = 0.999999999),
                            (((this._p2 * t) | 0) + this._p3) * this._p1
                        );
                    }),
                    (g.config = s.config =
                        function (t, e) {
                            return new s(t, e);
                        }),
                    ((g = (a = c(
                        "easing.ExpoScaleEase",
                        function (t, e, i) {
                            (this._p1 = Math.log(e / t)),
                                (this._p2 = e - t),
                                (this._p3 = t),
                                (this._ease = i);
                        },
                        !0
                    )).prototype =
                        new t()).constructor = a),
                    (g.getRatio = function (t) {
                        return (
                            this._ease && (t = this._ease.getRatio(t)),
                            (this._p3 * Math.exp(this._p1 * t) - this._p3) /
                                this._p2
                        );
                    }),
                    (g.config = a.config =
                        function (t, e, i) {
                            return new a(t, e, i);
                        }),
                    ((g = (o = c(
                        "easing.RoughEase",
                        function (e) {
                            for (
                                var i,
                                    n,
                                    s,
                                    a,
                                    o,
                                    l,
                                    h = (e = e || {}).taper || "none",
                                    u = [],
                                    _ = 0,
                                    f = 0 | (e.points || 20),
                                    c = f,
                                    p = !1 !== e.randomize,
                                    d = !0 === e.clamp,
                                    m =
                                        e.template instanceof t
                                            ? e.template
                                            : null,
                                    g =
                                        "number" == typeof e.strength
                                            ? 0.4 * e.strength
                                            : 0.4;
                                -1 < --c;

                            )
                                (i = p ? Math.random() : (1 / f) * c),
                                    (n = m ? m.getRatio(i) : i),
                                    (s =
                                        "none" === h
                                            ? g
                                            : "out" === h
                                            ? (a = 1 - i) * a * g
                                            : "in" === h
                                            ? i * i * g
                                            : (a =
                                                  i < 0.5
                                                      ? 2 * i
                                                      : 2 * (1 - i)) *
                                              a *
                                              0.5 *
                                              g),
                                    p
                                        ? (n += Math.random() * s - 0.5 * s)
                                        : c % 2
                                        ? (n += 0.5 * s)
                                        : (n -= 0.5 * s),
                                    d && (1 < n ? (n = 1) : n < 0 && (n = 0)),
                                    (u[_++] = { x: i, y: n });
                            for (
                                u.sort(function (t, e) {
                                    return t.x - e.x;
                                }),
                                    l = new r(1, 1, null),
                                    c = f;
                                -1 < --c;

                            )
                                l = new r((o = u[c]).x, o.y, l);
                            this._prev = new r(0, 0, 0 !== l.t ? l : l.next);
                        },
                        !0
                    )).prototype =
                        new t()).constructor = o),
                    (g.getRatio = function (t) {
                        var e = this._prev;
                        if (t > e.t) {
                            for (; e.next && t >= e.t; ) e = e.next;
                            e = e.prev;
                        } else for (; e.prev && t <= e.t; ) e = e.prev;
                        return (this._prev = e).v + ((t - e.t) / e.gap) * e.c;
                    }),
                    (g.config = function (t) {
                        return new o(t);
                    }),
                    (o.ease = new o()),
                    i(
                        "Bounce",
                        e("BounceOut", function (t) {
                            return t < 1 / 2.75
                                ? 7.5625 * t * t
                                : t < 2 / 2.75
                                ? 7.5625 * (t -= 1.5 / 2.75) * t + 0.75
                                : t < 2.5 / 2.75
                                ? 7.5625 * (t -= 2.25 / 2.75) * t + 0.9375
                                : 7.5625 * (t -= 2.625 / 2.75) * t + 0.984375;
                        }),
                        e("BounceIn", function (t) {
                            return (t = 1 - t) < 1 / 2.75
                                ? 1 - 7.5625 * t * t
                                : t < 2 / 2.75
                                ? 1 - (7.5625 * (t -= 1.5 / 2.75) * t + 0.75)
                                : t < 2.5 / 2.75
                                ? 1 - (7.5625 * (t -= 2.25 / 2.75) * t + 0.9375)
                                : 1 -
                                  (7.5625 * (t -= 2.625 / 2.75) * t + 0.984375);
                        }),
                        e("BounceInOut", function (t) {
                            var e = t < 0.5;
                            return (
                                (t =
                                    (t = e ? 1 - 2 * t : 2 * t - 1) < 1 / 2.75
                                        ? 7.5625 * t * t
                                        : t < 2 / 2.75
                                        ? 7.5625 * (t -= 1.5 / 2.75) * t + 0.75
                                        : t < 2.5 / 2.75
                                        ? 7.5625 * (t -= 2.25 / 2.75) * t +
                                          0.9375
                                        : 7.5625 * (t -= 2.625 / 2.75) * t +
                                          0.984375),
                                e ? 0.5 * (1 - t) : 0.5 * t + 0.5
                            );
                        })
                    ),
                    i(
                        "Circ",
                        e("CircOut", function (t) {
                            return Math.sqrt(1 - --t * t);
                        }),
                        e("CircIn", function (t) {
                            return -(Math.sqrt(1 - t * t) - 1);
                        }),
                        e("CircInOut", function (t) {
                            return (t *= 2) < 1
                                ? -0.5 * (Math.sqrt(1 - t * t) - 1)
                                : 0.5 * (Math.sqrt(1 - (t -= 2) * t) + 1);
                        })
                    ),
                    i(
                        "Elastic",
                        (l = function (e, i, r) {
                            var n = c(
                                    "easing." + e,
                                    function (t, e) {
                                        (this._p1 = 1 <= t ? t : 1),
                                            (this._p2 =
                                                (e || r) / (t < 1 ? t : 1)),
                                            (this._p3 =
                                                (this._p2 / _) *
                                                (Math.asin(1 / this._p1) || 0)),
                                            (this._p2 = _ / this._p2);
                                    },
                                    !0
                                ),
                                s = (n.prototype = new t());
                            return (
                                (s.constructor = n),
                                (s.getRatio = i),
                                (s.config = function (t, e) {
                                    return new n(t, e);
                                }),
                                n
                            );
                        })(
                            "ElasticOut",
                            function (t) {
                                return (
                                    this._p1 *
                                        Math.pow(2, -10 * t) *
                                        Math.sin((t - this._p3) * this._p2) +
                                    1
                                );
                            },
                            0.3
                        ),
                        l(
                            "ElasticIn",
                            function (t) {
                                return (
                                    -this._p1 *
                                    Math.pow(2, 10 * --t) *
                                    Math.sin((t - this._p3) * this._p2)
                                );
                            },
                            0.3
                        ),
                        l(
                            "ElasticInOut",
                            function (t) {
                                return (t *= 2) < 1
                                    ? this._p1 *
                                          Math.pow(2, 10 * --t) *
                                          Math.sin((t - this._p3) * this._p2) *
                                          -0.5
                                    : this._p1 *
                                          Math.pow(2, -10 * --t) *
                                          Math.sin((t - this._p3) * this._p2) *
                                          0.5 +
                                          1;
                            },
                            0.45
                        )
                    ),
                    i(
                        "Expo",
                        e("ExpoOut", function (t) {
                            return 1 - Math.pow(2, -10 * t);
                        }),
                        e("ExpoIn", function (t) {
                            return Math.pow(2, 10 * (t - 1)) - 0.001;
                        }),
                        e("ExpoInOut", function (t) {
                            return (t *= 2) < 1
                                ? 0.5 * Math.pow(2, 10 * (t - 1))
                                : 0.5 * (2 - Math.pow(2, -10 * (t - 1)));
                        })
                    ),
                    i(
                        "Sine",
                        e("SineOut", function (t) {
                            return Math.sin(t * f);
                        }),
                        e("SineIn", function (t) {
                            return 1 - Math.cos(t * f);
                        }),
                        e("SineInOut", function (t) {
                            return -0.5 * (Math.cos(Math.PI * t) - 1);
                        })
                    ),
                    c(
                        "easing.EaseLookup",
                        {
                            find: function (e) {
                                return t.map[e];
                            },
                        },
                        !0
                    ),
                    p(h.SlowMo, "SlowMo", "ease,"),
                    p(o, "RoughEase", "ease,"),
                    p(s, "SteppedEase", "ease,"),
                    d
                );
            },
            !0
        );
}),
    _gsScope._gsDefine && _gsScope._gsQueue.pop()(),
    (function (t, e) {
        "use strict";
        var i = {},
            r = t.document,
            n = (t.GreenSockGlobals = t.GreenSockGlobals || t),
            s = n[e];
        if (s)
            return (
                "undefined" != typeof module &&
                module.exports &&
                (module.exports = s)
            );
        function a(t) {
            var e,
                i = t.split("."),
                r = n;
            for (e = 0; e < i.length; e++) r[i[e]] = r = r[i[e]] || {};
            return r;
        }
        function o(t) {
            var e,
                i = [],
                r = t.length;
            for (e = 0; e !== r; i.push(t[e++]));
            return i;
        }
        function l() {}
        var h,
            u,
            _,
            f,
            c,
            p,
            d,
            m = a("com.greensock"),
            g = 1e-8,
            y =
                ((p = Object.prototype.toString),
                (d = p.call([])),
                function (t) {
                    return (
                        null != t &&
                        (t instanceof Array ||
                            ("object" == typeof t &&
                                !!t.push &&
                                p.call(t) === d))
                    );
                }),
            v = {},
            x = function (r, s, o, l) {
                (this.sc = v[r] ? v[r].sc : []),
                    ((v[r] = this).gsClass = null),
                    (this.func = o);
                var h = [];
                (this.check = function (u) {
                    for (var _, f, c, p, d = s.length, m = d; -1 < --d; )
                        (_ = v[s[d]] || new x(s[d], [])).gsClass
                            ? ((h[d] = _.gsClass), m--)
                            : u && _.sc.push(this);
                    if (0 === m && o) {
                        if (
                            ((c = (f = ("com.greensock." + r).split(
                                "."
                            )).pop()),
                            (p =
                                a(f.join("."))[c] =
                                this.gsClass =
                                    o.apply(o, h)),
                            l)
                        )
                            if (
                                ((n[c] = i[c] = p),
                                "undefined" != typeof module && module.exports)
                            )
                                if (r === e)
                                    for (d in ((module.exports = i[e] = p), i))
                                        p[d] = i[d];
                                else i[e] && (i[e][c] = p);
                            else
                                "function" == typeof define &&
                                    define.amd &&
                                    define(
                                        (t.GreenSockAMDPath
                                            ? t.GreenSockAMDPath + "/"
                                            : "") + r.split(".").pop(),
                                        [],
                                        function () {
                                            return p;
                                        }
                                    );
                        for (d = 0; d < this.sc.length; d++) this.sc[d].check();
                    }
                }),
                    this.check(!0);
            },
            T = (t._gsDefine = function (t, e, i, r) {
                return new x(t, e, i, r);
            }),
            w = (m._class = function (t, e, i) {
                return (
                    (e = e || function () {}),
                    T(
                        t,
                        [],
                        function () {
                            return e;
                        },
                        i
                    ),
                    e
                );
            });
        T.globals = n;
        var b = [0, 0, 1, 1],
            P = w(
                "easing.Ease",
                function (t, e, i, r) {
                    (this._func = t),
                        (this._type = i || 0),
                        (this._power = r || 0),
                        (this._params = e ? b.concat(e) : b);
                },
                !0
            ),
            S = (P.map = {}),
            O = (P.register = function (t, e, i, r) {
                for (
                    var n,
                        s,
                        a,
                        o,
                        l = e.split(","),
                        h = l.length,
                        u = (i || "easeIn,easeOut,easeInOut").split(",");
                    -1 < --h;

                )
                    for (
                        s = l[h],
                            n = r
                                ? w("easing." + s, null, !0)
                                : m.easing[s] || {},
                            a = u.length;
                        -1 < --a;

                    )
                        (o = u[a]),
                            (S[s + "." + o] =
                                S[o + s] =
                                n[o] =
                                    t.getRatio ? t : t[o] || new t());
            });
        for (
            (_ = P.prototype)._calcEnd = !1,
                _.getRatio = function (t) {
                    if (this._func)
                        return (
                            (this._params[0] = t),
                            this._func.apply(null, this._params)
                        );
                    var e = this._type,
                        i = this._power,
                        r =
                            1 === e
                                ? 1 - t
                                : 2 === e
                                ? t
                                : t < 0.5
                                ? 2 * t
                                : 2 * (1 - t);
                    return (
                        1 === i
                            ? (r *= r)
                            : 2 === i
                            ? (r *= r * r)
                            : 3 === i
                            ? (r *= r * r * r)
                            : 4 === i && (r *= r * r * r * r),
                        1 === e
                            ? 1 - r
                            : 2 === e
                            ? r
                            : t < 0.5
                            ? r / 2
                            : 1 - r / 2
                    );
                },
                u = (h = ["Linear", "Quad", "Cubic", "Quart", "Quint,Strong"])
                    .length;
            -1 < --u;

        )
            (_ = h[u] + ",Power" + u),
                O(new P(null, null, 1, u), _, "easeOut", !0),
                O(
                    new P(null, null, 2, u),
                    _,
                    "easeIn" + (0 === u ? ",easeNone" : "")
                ),
                O(new P(null, null, 3, u), _, "easeInOut");
        (S.linear = m.easing.Linear.easeIn),
            (S.swing = m.easing.Quad.easeInOut);
        var k = w("events.EventDispatcher", function (t) {
            (this._listeners = {}), (this._eventTarget = t || this);
        });
        ((_ = k.prototype).addEventListener = function (t, e, i, r, n) {
            n = n || 0;
            var s,
                a,
                o = this._listeners[t],
                l = 0;
            for (
                this !== f || c || f.wake(),
                    null == o && (this._listeners[t] = o = []),
                    a = o.length;
                -1 < --a;

            )
                (s = o[a]).c === e && s.s === i
                    ? o.splice(a, 1)
                    : 0 === l && s.pr < n && (l = a + 1);
            o.splice(l, 0, { c: e, s: i, up: r, pr: n });
        }),
            (_.removeEventListener = function (t, e) {
                var i,
                    r = this._listeners[t];
                if (r)
                    for (i = r.length; -1 < --i; )
                        if (r[i].c === e) return void r.splice(i, 1);
            }),
            (_.dispatchEvent = function (t) {
                var e,
                    i,
                    r,
                    n = this._listeners[t];
                if (n)
                    for (
                        1 < (e = n.length) && (n = n.slice(0)),
                            i = this._eventTarget;
                        -1 < --e;

                    )
                        (r = n[e]) &&
                            (r.up
                                ? r.c.call(r.s || i, { type: t, target: i })
                                : r.c.call(r.s || i));
            });
        var C = t.requestAnimationFrame,
            R = t.cancelAnimationFrame,
            A =
                Date.now ||
                function () {
                    return new Date().getTime();
                },
            M = A();
        for (u = (h = ["ms", "moz", "webkit", "o"]).length; -1 < --u && !C; )
            (C = t[h[u] + "RequestAnimationFrame"]),
                (R =
                    t[h[u] + "CancelAnimationFrame"] ||
                    t[h[u] + "CancelRequestAnimationFrame"]);
        w("Ticker", function (t, e) {
            var i,
                n,
                s,
                a,
                o,
                h = this,
                u = A(),
                _ = !(!1 === e || !C) && "auto",
                p = 500,
                d = 33,
                m = function (t) {
                    var e,
                        r,
                        l = A() - M;
                    p < l && (u += l - d),
                        (M += l),
                        (h.time = (M - u) / 1e3),
                        (e = h.time - o),
                        (!i || 0 < e || !0 === t) &&
                            (h.frame++,
                            (o += e + (a <= e ? 0.004 : a - e)),
                            (r = !0)),
                        !0 !== t && (s = n(m)),
                        r && h.dispatchEvent("tick");
                };
            k.call(h),
                (h.time = h.frame = 0),
                (h.tick = function () {
                    m(!0);
                }),
                (h.lagSmoothing = function (t, e) {
                    return arguments.length
                        ? ((p = t || 1e8), void (d = Math.min(e, p, 0)))
                        : p < 1e8;
                }),
                (h.sleep = function () {
                    null != s &&
                        ((_ && R ? R : clearTimeout)(s),
                        (n = l),
                        (s = null),
                        h === f && (c = !1));
                }),
                (h.wake = function (t) {
                    null !== s
                        ? h.sleep()
                        : t
                        ? (u += -M + (M = A()))
                        : 10 < h.frame && (M = A() - p + 5),
                        (n =
                            0 === i
                                ? l
                                : _ && C
                                ? C
                                : function (t) {
                                      return setTimeout(
                                          t,
                                          (1e3 * (o - h.time) + 1) | 0
                                      );
                                  }),
                        h === f && (c = !0),
                        m(2);
                }),
                (h.fps = function (t) {
                    return arguments.length
                        ? ((a = 1 / ((i = t) || 60)),
                          (o = this.time + a),
                          void h.wake())
                        : i;
                }),
                (h.useRAF = function (t) {
                    return arguments.length
                        ? (h.sleep(), (_ = t), void h.fps(i))
                        : _;
                }),
                h.fps(t),
                setTimeout(function () {
                    "auto" === _ &&
                        h.frame < 5 &&
                        "hidden" !== (r || {}).visibilityState &&
                        h.useRAF(!1);
                }, 1500);
        }),
            ((_ = m.Ticker.prototype =
                new m.events.EventDispatcher()).constructor = m.Ticker);
        var D = w("core.Animation", function (t, e) {
            if (
                ((this.vars = e = e || {}),
                (this._duration = this._totalDuration = t || 0),
                (this._delay = Number(e.delay) || 0),
                (this._timeScale = 1),
                (this._active = !!e.immediateRender),
                (this.data = e.data),
                (this._reversed = !!e.reversed),
                K)
            ) {
                c || f.wake();
                var i = this.vars.useFrames ? $ : K;
                i.add(this, i._time), this.vars.paused && this.paused(!0);
            }
        });
        (f = D.ticker = new m.Ticker()),
            ((_ = D.prototype)._dirty = _._gc = _._initted = _._paused = !1),
            (_._totalTime = _._time = 0),
            (_._rawPrevTime = -1),
            (_._next = _._last = _._onUpdate = _._timeline = _.timeline = null),
            (_._paused = !1);
        var F = function () {
            c &&
                2e3 < A() - M &&
                ("hidden" !== (r || {}).visibilityState || !f.lagSmoothing()) &&
                f.wake();
            var t = setTimeout(F, 2e3);
            t.unref && t.unref();
        };
        F(),
            (_.play = function (t, e) {
                return (
                    null != t && this.seek(t, e), this.reversed(!1).paused(!1)
                );
            }),
            (_.pause = function (t, e) {
                return null != t && this.seek(t, e), this.paused(!0);
            }),
            (_.resume = function (t, e) {
                return null != t && this.seek(t, e), this.paused(!1);
            }),
            (_.seek = function (t, e) {
                return this.totalTime(Number(t), !1 !== e);
            }),
            (_.restart = function (t, e) {
                return this.reversed(!1)
                    .paused(!1)
                    .totalTime(t ? -this._delay : 0, !1 !== e, !0);
            }),
            (_.reverse = function (t, e) {
                return (
                    null != t && this.seek(t || this.totalDuration(), e),
                    this.reversed(!0).paused(!1)
                );
            }),
            (_.render = function (t, e, i) {}),
            (_.invalidate = function () {
                return (
                    (this._time = this._totalTime = 0),
                    (this._initted = this._gc = !1),
                    (this._rawPrevTime = -1),
                    (!this._gc && this.timeline) || this._enabled(!0),
                    this
                );
            }),
            (_.isActive = function () {
                var t,
                    e = this._timeline,
                    i = this._startTime;
                return (
                    !e ||
                    (!this._gc &&
                        !this._paused &&
                        e.isActive() &&
                        (t = e.rawTime(!0)) >= i &&
                        t < i + this.totalDuration() / this._timeScale - g)
                );
            }),
            (_._enabled = function (t, e) {
                return (
                    c || f.wake(),
                    (this._gc = !t),
                    (this._active = this.isActive()),
                    !0 !== e &&
                        (t && !this.timeline
                            ? this._timeline.add(
                                  this,
                                  this._startTime - this._delay
                              )
                            : !t &&
                              this.timeline &&
                              this._timeline._remove(this, !0)),
                    !1
                );
            }),
            (_._kill = function (t, e) {
                return this._enabled(!1, !1);
            }),
            (_.kill = function (t, e) {
                return this._kill(t, e), this;
            }),
            (_._uncache = function (t) {
                for (var e = t ? this : this.timeline; e; )
                    (e._dirty = !0), (e = e.timeline);
                return this;
            }),
            (_._swapSelfInParams = function (t) {
                for (var e = t.length, i = t.concat(); -1 < --e; )
                    "{self}" === t[e] && (i[e] = this);
                return i;
            }),
            (_._callback = function (t) {
                var e = this.vars,
                    i = e[t],
                    r = e[t + "Params"],
                    n = e[t + "Scope"] || e.callbackScope || this;
                switch (r ? r.length : 0) {
                    case 0:
                        i.call(n);
                        break;
                    case 1:
                        i.call(n, r[0]);
                        break;
                    case 2:
                        i.call(n, r[0], r[1]);
                        break;
                    default:
                        i.apply(n, r);
                }
            }),
            (_.eventCallback = function (t, e, i, r) {
                if ("on" === (t || "").substr(0, 2)) {
                    var n = this.vars;
                    if (1 === arguments.length) return n[t];
                    null == e
                        ? delete n[t]
                        : ((n[t] = e),
                          (n[t + "Params"] =
                              y(i) && -1 !== i.join("").indexOf("{self}")
                                  ? this._swapSelfInParams(i)
                                  : i),
                          (n[t + "Scope"] = r)),
                        "onUpdate" === t && (this._onUpdate = e);
                }
                return this;
            }),
            (_.delay = function (t) {
                return arguments.length
                    ? (this._timeline.smoothChildTiming &&
                          this.startTime(this._startTime + t - this._delay),
                      (this._delay = t),
                      this)
                    : this._delay;
            }),
            (_.duration = function (t) {
                return arguments.length
                    ? ((this._duration = this._totalDuration = t),
                      this._uncache(!0),
                      this._timeline.smoothChildTiming &&
                          0 < this._time &&
                          this._time < this._duration &&
                          0 !== t &&
                          this.totalTime(
                              this._totalTime * (t / this._duration),
                              !0
                          ),
                      this)
                    : ((this._dirty = !1), this._duration);
            }),
            (_.totalDuration = function (t) {
                return (
                    (this._dirty = !1),
                    arguments.length ? this.duration(t) : this._totalDuration
                );
            }),
            (_.time = function (t, e) {
                return arguments.length
                    ? (this._dirty && this.totalDuration(),
                      this.totalTime(
                          t > this._duration ? this._duration : t,
                          e
                      ))
                    : this._time;
            }),
            (_.totalTime = function (t, e, i) {
                if ((c || f.wake(), !arguments.length)) return this._totalTime;
                if (this._timeline) {
                    if (
                        (t < 0 && !i && (t += this.totalDuration()),
                        this._timeline.smoothChildTiming)
                    ) {
                        this._dirty && this.totalDuration();
                        var r = this._totalDuration,
                            n = this._timeline;
                        if (
                            (r < t && !i && (t = r),
                            (this._startTime =
                                (this._paused ? this._pauseTime : n._time) -
                                (this._reversed ? r - t : t) / this._timeScale),
                            n._dirty || this._uncache(!1),
                            n._timeline)
                        )
                            for (; n._timeline; )
                                n._timeline._time !==
                                    (n._startTime + n._totalTime) /
                                        n._timeScale &&
                                    n.totalTime(n._totalTime, !0),
                                    (n = n._timeline);
                    }
                    this._gc && this._enabled(!0, !1),
                        (this._totalTime === t && 0 !== this._duration) ||
                            (B.length && tt(),
                            this.render(t, e, !1),
                            B.length && tt());
                }
                return this;
            }),
            (_.progress = _.totalProgress =
                function (t, e) {
                    var i = this.duration();
                    return arguments.length
                        ? this.totalTime(i * t, e)
                        : i
                        ? this._time / i
                        : this.ratio;
                }),
            (_.startTime = function (t) {
                return arguments.length
                    ? (t !== this._startTime &&
                          ((this._startTime = t),
                          this.timeline &&
                              this.timeline._sortChildren &&
                              this.timeline.add(this, t - this._delay)),
                      this)
                    : this._startTime;
            }),
            (_.endTime = function (t) {
                return (
                    this._startTime +
                    (0 != t ? this.totalDuration() : this.duration()) /
                        this._timeScale
                );
            }),
            (_.timeScale = function (t) {
                if (!arguments.length) return this._timeScale;
                var e, i;
                for (
                    t = t || g,
                        this._timeline &&
                            this._timeline.smoothChildTiming &&
                            ((i =
                                (e = this._pauseTime) || 0 === e
                                    ? e
                                    : this._timeline.totalTime()),
                            (this._startTime =
                                i -
                                ((i - this._startTime) * this._timeScale) / t)),
                        this._timeScale = t,
                        i = this.timeline;
                    i && i.timeline;

                )
                    (i._dirty = !0), i.totalDuration(), (i = i.timeline);
                return this;
            }),
            (_.reversed = function (t) {
                return arguments.length
                    ? (t != this._reversed &&
                          ((this._reversed = t),
                          this.totalTime(
                              this._timeline &&
                                  !this._timeline.smoothChildTiming
                                  ? this.totalDuration() - this._totalTime
                                  : this._totalTime,
                              !0
                          )),
                      this)
                    : this._reversed;
            }),
            (_.paused = function (t) {
                if (!arguments.length) return this._paused;
                var e,
                    i,
                    r = this._timeline;
                return (
                    t != this._paused &&
                        r &&
                        (c || t || f.wake(),
                        (i = (e = r.rawTime()) - this._pauseTime),
                        !t &&
                            r.smoothChildTiming &&
                            ((this._startTime += i), this._uncache(!1)),
                        (this._pauseTime = t ? e : null),
                        (this._paused = t),
                        (this._active = this.isActive()),
                        !t &&
                            0 != i &&
                            this._initted &&
                            this.duration() &&
                            ((e = r.smoothChildTiming
                                ? this._totalTime
                                : (e - this._startTime) / this._timeScale),
                            this.render(e, e === this._totalTime, !0))),
                    this._gc && !t && this._enabled(!0, !1),
                    this
                );
            });
        var L = w("core.SimpleTimeline", function (t) {
            D.call(this, 0, t),
                (this.autoRemoveChildren = this.smoothChildTiming = !0);
        });
        function z(e) {
            return (
                e &&
                e.length &&
                e !== t &&
                e[0] &&
                (e[0] === t || (e[0].nodeType && e[0].style && !e.nodeType))
            );
        }
        ((_ = L.prototype = new D()).constructor = L),
            (_.kill()._gc = !1),
            (_._first = _._last = _._recent = null),
            (_._sortChildren = !1),
            (_.add = _.insert =
                function (t, e, i, r) {
                    var n, s;
                    if (
                        ((t._startTime = Number(e || 0) + t._delay),
                        t._paused &&
                            this !== t._timeline &&
                            (t._pauseTime =
                                this.rawTime() -
                                (t._timeline.rawTime() - t._pauseTime)),
                        t.timeline && t.timeline._remove(t, !0),
                        (t.timeline = t._timeline = this),
                        t._gc && t._enabled(!0, !0),
                        (n = this._last),
                        this._sortChildren)
                    )
                        for (s = t._startTime; n && n._startTime > s; )
                            n = n._prev;
                    return (
                        n
                            ? ((t._next = n._next), (n._next = t))
                            : ((t._next = this._first), (this._first = t)),
                        t._next ? (t._next._prev = t) : (this._last = t),
                        (t._prev = n),
                        (this._recent = t),
                        this._timeline && this._uncache(!0),
                        this
                    );
                }),
            (_._remove = function (t, e) {
                return (
                    t.timeline === this &&
                        (e || t._enabled(!1, !0),
                        t._prev
                            ? (t._prev._next = t._next)
                            : this._first === t && (this._first = t._next),
                        t._next
                            ? (t._next._prev = t._prev)
                            : this._last === t && (this._last = t._prev),
                        (t._next = t._prev = t.timeline = null),
                        t === this._recent && (this._recent = this._last),
                        this._timeline && this._uncache(!0)),
                    this
                );
            }),
            (_.render = function (t, e, i) {
                var r,
                    n = this._first;
                for (this._totalTime = this._time = this._rawPrevTime = t; n; )
                    (r = n._next),
                        (n._active ||
                            (t >= n._startTime && !n._paused && !n._gc)) &&
                            (n._reversed
                                ? n.render(
                                      (n._dirty
                                          ? n.totalDuration()
                                          : n._totalDuration) -
                                          (t - n._startTime) * n._timeScale,
                                      e,
                                      i
                                  )
                                : n.render(
                                      (t - n._startTime) * n._timeScale,
                                      e,
                                      i
                                  )),
                        (n = r);
            }),
            (_.rawTime = function () {
                return c || f.wake(), this._totalTime;
            });
        var E = w(
            "TweenLite",
            function (e, i, r) {
                if (
                    (D.call(this, i, r),
                    (this.render = E.prototype.render),
                    null == e)
                )
                    throw "Cannot tween a null target.";
                this.target = e = "string" != typeof e ? e : E.selector(e) || e;
                var n,
                    s,
                    a,
                    l =
                        e.jquery ||
                        (e.length &&
                            e !== t &&
                            e[0] &&
                            (e[0] === t ||
                                (e[0].nodeType && e[0].style && !e.nodeType))),
                    h = this.vars.overwrite;
                if (
                    ((this._overwrite = h =
                        null == h
                            ? Z[E.defaultOverwrite]
                            : "number" == typeof h
                            ? h >> 0
                            : Z[h]),
                    (l || e instanceof Array || (e.push && y(e))) &&
                        "number" != typeof e[0])
                )
                    for (
                        this._targets = a = o(e),
                            this._propLookup = [],
                            this._siblings = [],
                            n = 0;
                        n < a.length;
                        n++
                    )
                        (s = a[n])
                            ? "string" != typeof s
                                ? s.length &&
                                  s !== t &&
                                  s[0] &&
                                  (s[0] === t ||
                                      (s[0].nodeType &&
                                          s[0].style &&
                                          !s.nodeType))
                                    ? (a.splice(n--, 1),
                                      (this._targets = a = a.concat(o(s))))
                                    : ((this._siblings[n] = it(s, this, !1)),
                                      1 === h &&
                                          1 < this._siblings[n].length &&
                                          rt(
                                              s,
                                              this,
                                              null,
                                              1,
                                              this._siblings[n]
                                          ))
                                : "string" ==
                                      typeof (s = a[n--] = E.selector(s)) &&
                                  a.splice(n + 1, 1)
                            : a.splice(n--, 1);
                else
                    (this._propLookup = {}),
                        (this._siblings = it(e, this, !1)),
                        1 === h &&
                            1 < this._siblings.length &&
                            rt(e, this, null, 1, this._siblings);
                (this.vars.immediateRender ||
                    (0 === i &&
                        0 === this._delay &&
                        !1 !== this.vars.immediateRender)) &&
                    ((this._time = -g), this.render(Math.min(0, -this._delay)));
            },
            !0
        );
        function I(t) {
            for (var e, i = this._firstPT; i; )
                (e = i.blob
                    ? 1 === t && null != this.end
                        ? this.end
                        : t
                        ? this.join("")
                        : this.start
                    : i.c * t + i.s),
                    i.m
                        ? (e = i.m.call(
                              this._tween,
                              e,
                              this._target || i.t,
                              this._tween
                          ))
                        : e < 1e-6 && -1e-6 < e && !i.blob && (e = 0),
                    i.f
                        ? i.fp
                            ? i.t[i.p](i.fp, e)
                            : i.t[i.p](e)
                        : (i.t[i.p] = e),
                    (i = i._next);
        }
        function N(t) {
            return ((1e3 * t) | 0) / 1e3 + "";
        }
        function j(t, e, i, r) {
            var n,
                s,
                a,
                o,
                l,
                h,
                u,
                _ = [],
                f = 0,
                c = "",
                p = 0;
            for (
                _.start = t,
                    _.end = e,
                    t = _[0] = t + "",
                    e = _[1] = e + "",
                    i && (i(_), (t = _[0]), (e = _[1])),
                    _.length = 0,
                    n = t.match(G) || [],
                    s = e.match(G) || [],
                    r &&
                        ((r._next = null),
                        (r.blob = 1),
                        (_._firstPT = _._applyPT = r)),
                    l = s.length,
                    o = 0;
                o < l;
                o++
            )
                (u = s[o]),
                    (c +=
                        (h = e.substr(f, e.indexOf(u, f) - f)) || !o ? h : ","),
                    (f += h.length),
                    p ? (p = (p + 1) % 5) : "rgba(" === h.substr(-5) && (p = 1),
                    u === n[o] || n.length <= o
                        ? (c += u)
                        : (c && (_.push(c), (c = "")),
                          (a = parseFloat(n[o])),
                          _.push(a),
                          (_._firstPT = {
                              _next: _._firstPT,
                              t: _,
                              p: _.length - 1,
                              s: a,
                              c:
                                  ("=" === u.charAt(1)
                                      ? parseInt(u.charAt(0) + "1", 10) *
                                        parseFloat(u.substr(2))
                                      : parseFloat(u) - a) || 0,
                              f: 0,
                              m: p && p < 4 ? Math.round : N,
                          })),
                    (f += u.length);
            return (
                (c += e.substr(f)) && _.push(c),
                (_.setRatio = I),
                U.test(e) && (_.end = null),
                _
            );
        }
        function X(t, e, i, r, n, s, a, o, l) {
            "function" == typeof r && (r = r(l || 0, t));
            var h = typeof t[e],
                u =
                    "function" != h
                        ? ""
                        : e.indexOf("set") ||
                          "function" != typeof t["get" + e.substr(3)]
                        ? e
                        : "get" + e.substr(3),
                _ = "get" !== i ? i : u ? (a ? t[u](a) : t[u]()) : t[e],
                f = "string" == typeof r && "=" === r.charAt(1),
                c = {
                    t: t,
                    p: e,
                    s: _,
                    f: "function" == h,
                    pg: 0,
                    n: n || e,
                    m: s ? ("function" == typeof s ? s : Math.round) : 0,
                    pr: 0,
                    c: f
                        ? parseInt(r.charAt(0) + "1", 10) *
                          parseFloat(r.substr(2))
                        : parseFloat(r) - _ || 0,
                };
            return (
                ("number" == typeof _ && ("number" == typeof r || f)) ||
                    (a ||
                    isNaN(_) ||
                    (!f && isNaN(r)) ||
                    "boolean" == typeof _ ||
                    "boolean" == typeof r
                        ? ((c.fp = a),
                          (c = {
                              t: j(
                                  _,
                                  f
                                      ? parseFloat(c.s) +
                                            c.c +
                                            (c.s + "").replace(/[0-9\-\.]/g, "")
                                      : r,
                                  o || E.defaultStringFilter,
                                  c
                              ),
                              p: "setRatio",
                              s: 0,
                              c: 1,
                              f: 2,
                              pg: 0,
                              n: n || e,
                              pr: 0,
                              m: 0,
                          }))
                        : ((c.s = parseFloat(_)),
                          f || (c.c = parseFloat(r) - c.s || 0))),
                c.c
                    ? ((c._next = this._firstPT) && (c._next._prev = c),
                      (this._firstPT = c))
                    : void 0
            );
        }
        ((_ = E.prototype = new D()).constructor = E),
            (_.kill()._gc = !1),
            (_.ratio = 0),
            (_._firstPT = _._targets = _._overwrittenProps = _._startAt = null),
            (_._notifyPluginsOfEnabled = _._lazy = !1),
            (E.version = "2.1.3"),
            (E.defaultEase = _._ease = new P(null, null, 1, 1)),
            (E.defaultOverwrite = "auto"),
            (E.ticker = f),
            (E.autoSleep = 120),
            (E.lagSmoothing = function (t, e) {
                f.lagSmoothing(t, e);
            }),
            (E.selector =
                t.$ ||
                t.jQuery ||
                function (e) {
                    var i = t.$ || t.jQuery;
                    return i
                        ? (E.selector = i)(e)
                        : (r = r || t.document)
                        ? r.querySelectorAll
                            ? r.querySelectorAll(e)
                            : r.getElementById(
                                  "#" === e.charAt(0) ? e.substr(1) : e
                              )
                        : e;
                });
        var B = [],
            Y = {},
            G = /(?:(-|-=|\+=)?\d*\.?\d*(?:e[\-+]?\d+)?)[0-9]/gi,
            U = /[\+-]=-?[\.\d]/,
            V = (E._internals = {
                isArray: y,
                isSelector: z,
                lazyTweens: B,
                blobDif: j,
            }),
            q = (E._plugins = {}),
            W = (V.tweenLookup = {}),
            Q = 0,
            H = (V.reservedProps = {
                ease: 1,
                delay: 1,
                overwrite: 1,
                onComplete: 1,
                onCompleteParams: 1,
                onCompleteScope: 1,
                useFrames: 1,
                runBackwards: 1,
                startAt: 1,
                onUpdate: 1,
                onUpdateParams: 1,
                onUpdateScope: 1,
                onStart: 1,
                onStartParams: 1,
                onStartScope: 1,
                onReverseComplete: 1,
                onReverseCompleteParams: 1,
                onReverseCompleteScope: 1,
                onRepeat: 1,
                onRepeatParams: 1,
                onRepeatScope: 1,
                easeParams: 1,
                yoyo: 1,
                immediateRender: 1,
                repeat: 1,
                repeatDelay: 1,
                data: 1,
                paused: 1,
                reversed: 1,
                autoCSS: 1,
                lazy: 1,
                onOverwrite: 1,
                callbackScope: 1,
                stringFilter: 1,
                id: 1,
                yoyoEase: 1,
                stagger: 1,
            }),
            Z = {
                none: 0,
                all: 1,
                auto: 2,
                concurrent: 3,
                allOnStart: 4,
                preexisting: 5,
                true: 1,
                false: 0,
            },
            $ = (D._rootFramesTimeline = new L()),
            K = (D._rootTimeline = new L()),
            J = 30,
            tt = (V.lazyRender = function () {
                var t,
                    e,
                    i = B.length;
                for (Y = {}, t = 0; t < i; t++)
                    (e = B[t]) &&
                        !1 !== e._lazy &&
                        (e.render(e._lazy[0], e._lazy[1], !0), (e._lazy = !1));
                B.length = 0;
            });
        function et(t, e, i, r) {
            var n,
                s,
                a = t.vars.onOverwrite;
            return (
                a && (n = a(t, e, i, r)),
                (a = E.onOverwrite) && (s = a(t, e, i, r)),
                !1 !== n && !1 !== s
            );
        }
        (K._startTime = f.time),
            ($._startTime = f.frame),
            (K._active = $._active = !0),
            setTimeout(tt, 1),
            (D._updateRoot = E.render =
                function () {
                    var t, e, i;
                    if (
                        (B.length && tt(),
                        K.render(
                            (f.time - K._startTime) * K._timeScale,
                            !1,
                            !1
                        ),
                        $.render(
                            (f.frame - $._startTime) * $._timeScale,
                            !1,
                            !1
                        ),
                        B.length && tt(),
                        f.frame >= J)
                    ) {
                        for (i in ((J =
                            f.frame + (parseInt(E.autoSleep, 10) || 120)),
                        W)) {
                            for (t = (e = W[i].tweens).length; -1 < --t; )
                                e[t]._gc && e.splice(t, 1);
                            0 === e.length && delete W[i];
                        }
                        if (
                            (!(i = K._first) || i._paused) &&
                            E.autoSleep &&
                            !$._first &&
                            1 === f._listeners.tick.length
                        ) {
                            for (; i && i._paused; ) i = i._next;
                            i || f.sleep();
                        }
                    }
                }),
            f.addEventListener("tick", D._updateRoot);
        var it = function (t, e, i) {
                var r,
                    n,
                    s = t._gsTweenID;
                if (
                    (W[s || (t._gsTweenID = s = "t" + Q++)] ||
                        (W[s] = { target: t, tweens: [] }),
                    e && (((r = W[s].tweens)[(n = r.length)] = e), i))
                )
                    for (; -1 < --n; ) r[n] === e && r.splice(n, 1);
                return W[s].tweens;
            },
            rt = function (t, e, i, r, n) {
                var s, a, o, l;
                if (1 === r || 4 <= r) {
                    for (l = n.length, s = 0; s < l; s++)
                        if ((o = n[s]) !== e)
                            o._gc || (o._kill(null, t, e) && (a = !0));
                        else if (5 === r) break;
                    return a;
                }
                var h,
                    u = e._startTime + g,
                    _ = [],
                    f = 0,
                    c = 0 === e._duration;
                for (s = n.length; -1 < --s; )
                    (o = n[s]) === e ||
                        o._gc ||
                        o._paused ||
                        (o._timeline !== e._timeline
                            ? ((h = h || nt(e, 0, c)),
                              0 === nt(o, h, c) && (_[f++] = o))
                            : o._startTime <= u &&
                              o._startTime + o.totalDuration() / o._timeScale >
                                  u &&
                              (((c || !o._initted) &&
                                  u - o._startTime <= 2e-8) ||
                                  (_[f++] = o)));
                for (s = f; -1 < --s; )
                    if (
                        ((l = (o = _[s])._firstPT),
                        2 === r && o._kill(i, t, e) && (a = !0),
                        2 !== r || (!o._firstPT && o._initted && l))
                    ) {
                        if (2 !== r && !et(o, e)) continue;
                        o._enabled(!1, !1) && (a = !0);
                    }
                return a;
            },
            nt = function (t, e, i) {
                for (
                    var r = t._timeline, n = r._timeScale, s = t._startTime;
                    r._timeline;

                ) {
                    if (((s += r._startTime), (n *= r._timeScale), r._paused))
                        return -100;
                    r = r._timeline;
                }
                return e < (s /= n)
                    ? s - e
                    : (i && s === e) || (!t._initted && s - e < 2e-8)
                    ? g
                    : (s += t.totalDuration() / t._timeScale / n) > e + g
                    ? 0
                    : s - e - g;
            };
        (_._init = function () {
            var t,
                e,
                i,
                r,
                n,
                s,
                a = this.vars,
                o = this._overwrittenProps,
                l = this._duration,
                h = !!a.immediateRender,
                u = a.ease,
                _ = this._startAt;
            if (a.startAt) {
                for (r in (_ && (_.render(-1, !0), _.kill()),
                (n = {}),
                a.startAt))
                    n[r] = a.startAt[r];
                if (
                    ((n.data = "isStart"),
                    (n.overwrite = !1),
                    (n.immediateRender = !0),
                    (n.lazy = h && !1 !== a.lazy),
                    (n.startAt = n.delay = null),
                    (n.onUpdate = a.onUpdate),
                    (n.onUpdateParams = a.onUpdateParams),
                    (n.onUpdateScope =
                        a.onUpdateScope || a.callbackScope || this),
                    (this._startAt = E.to(this.target || {}, 0, n)),
                    h)
                )
                    if (0 < this._time) this._startAt = null;
                    else if (0 !== l) return;
            } else if (a.runBackwards && 0 !== l)
                if (_) _.render(-1, !0), _.kill(), (this._startAt = null);
                else {
                    for (r in (0 !== this._time && (h = !1), (i = {}), a))
                        (H[r] && "autoCSS" !== r) || (i[r] = a[r]);
                    if (
                        ((i.overwrite = 0),
                        (i.data = "isFromStart"),
                        (i.lazy = h && !1 !== a.lazy),
                        (i.immediateRender = h),
                        (this._startAt = E.to(this.target, 0, i)),
                        h)
                    ) {
                        if (0 === this._time) return;
                    } else
                        this._startAt._init(),
                            this._startAt._enabled(!1),
                            this.vars.immediateRender && (this._startAt = null);
                }
            if (
                ((this._ease = u =
                    u
                        ? u instanceof P
                            ? u
                            : "function" == typeof u
                            ? new P(u, a.easeParams)
                            : S[u] || E.defaultEase
                        : E.defaultEase),
                a.easeParams instanceof Array &&
                    u.config &&
                    (this._ease = u.config.apply(u, a.easeParams)),
                (this._easeType = this._ease._type),
                (this._easePower = this._ease._power),
                (this._firstPT = null),
                this._targets)
            )
                for (s = this._targets.length, t = 0; t < s; t++)
                    this._initProps(
                        this._targets[t],
                        (this._propLookup[t] = {}),
                        this._siblings[t],
                        o ? o[t] : null,
                        t
                    ) && (e = !0);
            else
                e = this._initProps(
                    this.target,
                    this._propLookup,
                    this._siblings,
                    o,
                    0
                );
            if (
                (e && E._onPluginEvent("_onInitAllProps", this),
                o &&
                    (this._firstPT ||
                        ("function" != typeof this.target &&
                            this._enabled(!1, !1))),
                a.runBackwards)
            )
                for (i = this._firstPT; i; )
                    (i.s += i.c), (i.c = -i.c), (i = i._next);
            (this._onUpdate = a.onUpdate), (this._initted = !0);
        }),
            (_._initProps = function (e, i, r, n, s) {
                var a, o, l, h, u, _;
                if (null == e) return !1;
                for (a in (Y[e._gsTweenID] && tt(),
                this.vars.css ||
                    (e.style &&
                        e !== t &&
                        e.nodeType &&
                        q.css &&
                        !1 !== this.vars.autoCSS &&
                        (function (t, e) {
                            var i,
                                r = {};
                            for (i in t)
                                H[i] ||
                                    (i in e &&
                                        "transform" !== i &&
                                        "x" !== i &&
                                        "y" !== i &&
                                        "width" !== i &&
                                        "height" !== i &&
                                        "className" !== i &&
                                        "border" !== i) ||
                                    !(!q[i] || (q[i] && q[i]._autoCSS)) ||
                                    ((r[i] = t[i]), delete t[i]);
                            t.css = r;
                        })(this.vars, e)),
                this.vars))
                    if (((_ = this.vars[a]), H[a]))
                        _ &&
                            (_ instanceof Array || (_.push && y(_))) &&
                            -1 !== _.join("").indexOf("{self}") &&
                            (this.vars[a] = _ =
                                this._swapSelfInParams(_, this));
                    else if (
                        q[a] &&
                        (h = new q[a]())._onInitTween(e, this.vars[a], this, s)
                    ) {
                        for (
                            this._firstPT = u =
                                {
                                    _next: this._firstPT,
                                    t: h,
                                    p: "setRatio",
                                    s: 0,
                                    c: 1,
                                    f: 1,
                                    n: a,
                                    pg: 1,
                                    pr: h._priority,
                                    m: 0,
                                },
                                o = h._overwriteProps.length;
                            -1 < --o;

                        )
                            i[h._overwriteProps[o]] = this._firstPT;
                        (h._priority || h._onInitAllProps) && (l = !0),
                            (h._onDisable || h._onEnable) &&
                                (this._notifyPluginsOfEnabled = !0),
                            u._next && (u._next._prev = u);
                    } else
                        i[a] = X.call(
                            this,
                            e,
                            a,
                            "get",
                            _,
                            a,
                            0,
                            null,
                            this.vars.stringFilter,
                            s
                        );
                return n && this._kill(n, e)
                    ? this._initProps(e, i, r, n, s)
                    : 1 < this._overwrite &&
                      this._firstPT &&
                      1 < r.length &&
                      rt(e, this, i, this._overwrite, r)
                    ? (this._kill(i, e), this._initProps(e, i, r, n, s))
                    : (this._firstPT &&
                          ((!1 !== this.vars.lazy && this._duration) ||
                              (this.vars.lazy && !this._duration)) &&
                          (Y[e._gsTweenID] = !0),
                      l);
            }),
            (_.render = function (t, e, i) {
                var r,
                    n,
                    s,
                    a,
                    o = this,
                    l = o._time,
                    h = o._duration,
                    u = o._rawPrevTime;
                if (h - g <= t && 0 <= t)
                    (o._totalTime = o._time = h),
                        (o.ratio = o._ease._calcEnd ? o._ease.getRatio(1) : 1),
                        o._reversed ||
                            ((r = !0),
                            (n = "onComplete"),
                            (i = i || o._timeline.autoRemoveChildren)),
                        0 !== h ||
                            (!o._initted && o.vars.lazy && !i) ||
                            (o._startTime === o._timeline._duration && (t = 0),
                            (u < 0 ||
                                (t <= 0 && -g <= t) ||
                                (u === g && "isPause" !== o.data)) &&
                                u !== t &&
                                ((i = !0), g < u && (n = "onReverseComplete")),
                            (o._rawPrevTime = a = !e || t || u === t ? t : g));
                else if (t < g)
                    (o._totalTime = o._time = 0),
                        (o.ratio = o._ease._calcEnd ? o._ease.getRatio(0) : 0),
                        (0 !== l || (0 === h && 0 < u)) &&
                            ((n = "onReverseComplete"), (r = o._reversed)),
                        -g < t
                            ? (t = 0)
                            : t < 0 &&
                              ((o._active = !1),
                              0 !== h ||
                                  (!o._initted && o.vars.lazy && !i) ||
                                  (0 <= u &&
                                      (u !== g || "isPause" !== o.data) &&
                                      (i = !0),
                                  (o._rawPrevTime = a =
                                      !e || t || u === t ? t : g))),
                        (!o._initted ||
                            (o._startAt && o._startAt.progress())) &&
                            (i = !0);
                else if (((o._totalTime = o._time = t), o._easeType)) {
                    var _ = t / h,
                        f = o._easeType,
                        c = o._easePower;
                    (1 === f || (3 === f && 0.5 <= _)) && (_ = 1 - _),
                        3 === f && (_ *= 2),
                        1 === c
                            ? (_ *= _)
                            : 2 === c
                            ? (_ *= _ * _)
                            : 3 === c
                            ? (_ *= _ * _ * _)
                            : 4 === c && (_ *= _ * _ * _ * _),
                        (o.ratio =
                            1 === f
                                ? 1 - _
                                : 2 === f
                                ? _
                                : t / h < 0.5
                                ? _ / 2
                                : 1 - _ / 2);
                } else o.ratio = o._ease.getRatio(t / h);
                if (o._time !== l || i) {
                    if (!o._initted) {
                        if ((o._init(), !o._initted || o._gc)) return;
                        if (
                            !i &&
                            o._firstPT &&
                            ((!1 !== o.vars.lazy && o._duration) ||
                                (o.vars.lazy && !o._duration))
                        )
                            return (
                                (o._time = o._totalTime = l),
                                (o._rawPrevTime = u),
                                B.push(o),
                                void (o._lazy = [t, e])
                            );
                        o._time && !r
                            ? (o.ratio = o._ease.getRatio(o._time / h))
                            : r &&
                              o._ease._calcEnd &&
                              (o.ratio = o._ease.getRatio(
                                  0 === o._time ? 0 : 1
                              ));
                    }
                    for (
                        !1 !== o._lazy && (o._lazy = !1),
                            o._active ||
                                (!o._paused &&
                                    o._time !== l &&
                                    0 <= t &&
                                    (o._active = !0)),
                            0 === l &&
                                (o._startAt &&
                                    (0 <= t
                                        ? o._startAt.render(t, !0, i)
                                        : (n = n || "_dummyGS")),
                                !o.vars.onStart ||
                                    (0 === o._time && 0 !== h) ||
                                    e ||
                                    o._callback("onStart")),
                            s = o._firstPT;
                        s;

                    )
                        s.f
                            ? s.t[s.p](s.c * o.ratio + s.s)
                            : (s.t[s.p] = s.c * o.ratio + s.s),
                            (s = s._next);
                    o._onUpdate &&
                        (t < 0 &&
                            o._startAt &&
                            -1e-4 !== t &&
                            o._startAt.render(t, !0, i),
                        e ||
                            ((o._time !== l || r || i) &&
                                o._callback("onUpdate"))),
                        !n ||
                            (o._gc && !i) ||
                            (t < 0 &&
                                o._startAt &&
                                !o._onUpdate &&
                                -1e-4 !== t &&
                                o._startAt.render(t, !0, i),
                            r &&
                                (o._timeline.autoRemoveChildren &&
                                    o._enabled(!1, !1),
                                (o._active = !1)),
                            !e && o.vars[n] && o._callback(n),
                            0 === h &&
                                o._rawPrevTime === g &&
                                a !== g &&
                                (o._rawPrevTime = 0));
                }
            }),
            (_._kill = function (t, e, i) {
                if (
                    ("all" === t && (t = null),
                    null == t && (null == e || e === this.target))
                )
                    return (this._lazy = !1), this._enabled(!1, !1);
                e =
                    "string" != typeof e
                        ? e || this._targets || this.target
                        : E.selector(e) || e;
                var r,
                    n,
                    s,
                    a,
                    o,
                    l,
                    h,
                    u,
                    _,
                    f =
                        i &&
                        this._time &&
                        i._startTime === this._startTime &&
                        this._timeline === i._timeline,
                    c = this._firstPT;
                if ((y(e) || z(e)) && "number" != typeof e[0])
                    for (r = e.length; -1 < --r; )
                        this._kill(t, e[r], i) && (l = !0);
                else {
                    if (this._targets) {
                        for (r = this._targets.length; -1 < --r; )
                            if (e === this._targets[r]) {
                                (o = this._propLookup[r] || {}),
                                    (this._overwrittenProps =
                                        this._overwrittenProps || []),
                                    (n = this._overwrittenProps[r] =
                                        t
                                            ? this._overwrittenProps[r] || {}
                                            : "all");
                                break;
                            }
                    } else {
                        if (e !== this.target) return !1;
                        (o = this._propLookup),
                            (n = this._overwrittenProps =
                                t ? this._overwrittenProps || {} : "all");
                    }
                    if (o) {
                        if (
                            ((h = t || o),
                            (u =
                                t !== n &&
                                "all" !== n &&
                                t !== o &&
                                ("object" != typeof t || !t._tempKill)),
                            i && (E.onOverwrite || this.vars.onOverwrite))
                        ) {
                            for (s in h) o[s] && (_ = _ || []).push(s);
                            if ((_ || !t) && !et(this, i, e, _)) return !1;
                        }
                        for (s in h)
                            (a = o[s]) &&
                                (f &&
                                    (a.f ? a.t[a.p](a.s) : (a.t[a.p] = a.s),
                                    (l = !0)),
                                a.pg && a.t._kill(h) && (l = !0),
                                (a.pg && 0 !== a.t._overwriteProps.length) ||
                                    (a._prev
                                        ? (a._prev._next = a._next)
                                        : a === this._firstPT &&
                                          (this._firstPT = a._next),
                                    a._next && (a._next._prev = a._prev),
                                    (a._next = a._prev = null)),
                                delete o[s]),
                                u && (n[s] = 1);
                        !this._firstPT &&
                            this._initted &&
                            c &&
                            this._enabled(!1, !1);
                    }
                }
                return l;
            }),
            (_.invalidate = function () {
                this._notifyPluginsOfEnabled &&
                    E._onPluginEvent("_onDisable", this);
                var t = this._time;
                return (
                    (this._firstPT =
                        this._overwrittenProps =
                        this._startAt =
                        this._onUpdate =
                            null),
                    (this._notifyPluginsOfEnabled =
                        this._active =
                        this._lazy =
                            !1),
                    (this._propLookup = this._targets ? {} : []),
                    D.prototype.invalidate.call(this),
                    this.vars.immediateRender &&
                        ((this._time = -g),
                        this.render(t, !1, !1 !== this.vars.lazy)),
                    this
                );
            }),
            (_._enabled = function (t, e) {
                if ((c || f.wake(), t && this._gc)) {
                    var i,
                        r = this._targets;
                    if (r)
                        for (i = r.length; -1 < --i; )
                            this._siblings[i] = it(r[i], this, !0);
                    else this._siblings = it(this.target, this, !0);
                }
                return (
                    D.prototype._enabled.call(this, t, e),
                    !(!this._notifyPluginsOfEnabled || !this._firstPT) &&
                        E._onPluginEvent(t ? "_onEnable" : "_onDisable", this)
                );
            }),
            (E.to = function (t, e, i) {
                return new E(t, e, i);
            }),
            (E.from = function (t, e, i) {
                return (
                    (i.runBackwards = !0),
                    (i.immediateRender = 0 != i.immediateRender),
                    new E(t, e, i)
                );
            }),
            (E.fromTo = function (t, e, i, r) {
                return (
                    (r.startAt = i),
                    (r.immediateRender =
                        0 != r.immediateRender && 0 != i.immediateRender),
                    new E(t, e, r)
                );
            }),
            (E.delayedCall = function (t, e, i, r, n) {
                return new E(e, 0, {
                    delay: t,
                    onComplete: e,
                    onCompleteParams: i,
                    callbackScope: r,
                    onReverseComplete: e,
                    onReverseCompleteParams: i,
                    immediateRender: !1,
                    lazy: !1,
                    useFrames: n,
                    overwrite: 0,
                });
            }),
            (E.set = function (t, e) {
                return new E(t, 0, e);
            }),
            (E.getTweensOf = function (t, e) {
                if (null == t) return [];
                var i, r, n, s;
                if (
                    ((t = "string" != typeof t ? t : E.selector(t) || t),
                    (y(t) || z(t)) && "number" != typeof t[0])
                ) {
                    for (i = t.length, r = []; -1 < --i; )
                        r = r.concat(E.getTweensOf(t[i], e));
                    for (i = r.length; -1 < --i; )
                        for (s = r[i], n = i; -1 < --n; )
                            s === r[n] && r.splice(i, 1);
                } else if (t._gsTweenID)
                    for (i = (r = it(t).concat()).length; -1 < --i; )
                        (r[i]._gc || (e && !r[i].isActive())) && r.splice(i, 1);
                return r || [];
            }),
            (E.killTweensOf = E.killDelayedCallsTo =
                function (t, e, i) {
                    "object" == typeof e && ((i = e), (e = !1));
                    for (var r = E.getTweensOf(t, e), n = r.length; -1 < --n; )
                        r[n]._kill(i, t);
                });
        var st = w(
            "plugins.TweenPlugin",
            function (t, e) {
                (this._overwriteProps = (t || "").split(",")),
                    (this._propName = this._overwriteProps[0]),
                    (this._priority = e || 0),
                    (this._super = st.prototype);
            },
            !0
        );
        if (
            ((_ = st.prototype),
            (st.version = "1.19.0"),
            (st.API = 2),
            (_._firstPT = null),
            (_._addTween = X),
            (_.setRatio = I),
            (_._kill = function (t) {
                var e,
                    i = this._overwriteProps,
                    r = this._firstPT;
                if (null != t[this._propName]) this._overwriteProps = [];
                else
                    for (e = i.length; -1 < --e; )
                        null != t[i[e]] && i.splice(e, 1);
                for (; r; )
                    null != t[r.n] &&
                        (r._next && (r._next._prev = r._prev),
                        r._prev
                            ? ((r._prev._next = r._next), (r._prev = null))
                            : this._firstPT === r && (this._firstPT = r._next)),
                        (r = r._next);
                return !1;
            }),
            (_._mod = _._roundProps =
                function (t) {
                    for (var e, i = this._firstPT; i; )
                        (e =
                            t[this._propName] ||
                            (null != i.n &&
                                t[i.n.split(this._propName + "_").join("")])) &&
                            "function" == typeof e &&
                            (2 === i.f ? (i.t._applyPT.m = e) : (i.m = e)),
                            (i = i._next);
                }),
            (E._onPluginEvent = function (t, e) {
                var i,
                    r,
                    n,
                    s,
                    a,
                    o = e._firstPT;
                if ("_onInitAllProps" === t) {
                    for (; o; ) {
                        for (a = o._next, r = n; r && r.pr > o.pr; )
                            r = r._next;
                        (o._prev = r ? r._prev : s)
                            ? (o._prev._next = o)
                            : (n = o),
                            (o._next = r) ? (r._prev = o) : (s = o),
                            (o = a);
                    }
                    o = e._firstPT = n;
                }
                for (; o; )
                    o.pg && "function" == typeof o.t[t] && o.t[t]() && (i = !0),
                        (o = o._next);
                return i;
            }),
            (st.activate = function (t) {
                for (var e = t.length; -1 < --e; )
                    t[e].API === st.API && (q[new t[e]()._propName] = t[e]);
                return !0;
            }),
            (T.plugin = function (t) {
                if (!(t && t.propName && t.init && t.API))
                    throw "illegal plugin definition.";
                var e,
                    i = t.propName,
                    r = t.priority || 0,
                    n = t.overwriteProps,
                    s = {
                        init: "_onInitTween",
                        set: "setRatio",
                        kill: "_kill",
                        round: "_mod",
                        mod: "_mod",
                        initAll: "_onInitAllProps",
                    },
                    a = w(
                        "plugins." +
                            i.charAt(0).toUpperCase() +
                            i.substr(1) +
                            "Plugin",
                        function () {
                            st.call(this, i, r),
                                (this._overwriteProps = n || []);
                        },
                        !0 === t.global
                    ),
                    o = (a.prototype = new st(i));
                for (e in (((o.constructor = a).API = t.API), s))
                    "function" == typeof t[e] && (o[s[e]] = t[e]);
                return (a.version = t.version), st.activate([a]), a;
            }),
            (h = t._gsQueue))
        ) {
            for (u = 0; u < h.length; u++) h[u]();
            for (_ in v)
                v[_].func ||
                    t.console.log("GSAP encountered missing dependency: " + _);
        }
        c = !1;
    })(
        "undefined" != typeof module &&
            module.exports &&
            "undefined" != typeof global
            ? global
            : this || window,
        "TweenMax"
    ),
    (function (t) {
        "function" == typeof define && define.amd
            ? define(t)
            : "undefined" != typeof exports
            ? (module.exports = t())
            : t();
    })(function () {
        (void 0 !== LS_GSAP ? LS_GSAP : window).SplitType = (function (
            t,
            e,
            i
        ) {
            function r(t) {
                return null !== t && "object" == typeof t;
            }
            function n(t) {
                return r(t) && "number" == typeof t.length && 0 < t.length;
            }
            function s(t) {
                return r(t) && /^(1|3|11)$/.test(t.nodeType);
            }
            function a(t, e, i) {
                for (
                    var s = Object(t),
                        a = n(s)
                            ? s
                            : r((h = s)) &&
                              "[object Object]" ===
                                  Object.prototype.toString.call(h)
                            ? m(s)
                            : [s],
                        o = parseInt(a.length) || 0,
                        l = 0;
                    l < o;
                    l++
                )
                    e.call(i, a[l], l, s);
                var h;
            }
            function o(t, e) {
                return (
                    (t = Object(t)),
                    (e = Object(e)),
                    Object.getOwnPropertyNames(t).reduce(function (i, r) {
                        return g(i, r, y(e, r) || y(t, r));
                    }, {})
                );
            }
            function l(t, e, n) {
                var s,
                    a = {};
                return (
                    r(t) &&
                        ((s = t[_] || (t[_] = ++c)), (a = f[s] || (f[s] = {}))),
                    n === i
                        ? e === i
                            ? a
                            : a[e]
                        : e !== i
                        ? (a[e] = n)
                        : void 0
                );
            }
            function h(t, r) {
                var n = e.createElement(t);
                return (
                    r === i ||
                        a(r, function (t) {
                            var e = r[t];
                            if (null !== e)
                                switch (t) {
                                    case "textContent":
                                        n.textContent = e;
                                        break;
                                    case "innerHTML":
                                        n.innerHTML = e;
                                        break;
                                    case "children":
                                        a(e, function (t) {
                                            s(t) && n.appendChild(t);
                                        });
                                        break;
                                    default:
                                        n.setAttribute(t, e);
                                }
                        }),
                    n
                );
            }
            function u(t, i) {
                return this instanceof u
                    ? ((this.isSplit = !1),
                      (this.settings = o(T, i)),
                      (this.elements = (function (t) {
                          var i,
                              r,
                              a,
                              o,
                              l,
                              h,
                              u = [];
                          if (
                              ("string" == typeof t &&
                                  (t =
                                      "#" !== (i = t.trim())[0] ||
                                      /[^\w]/.test((r = i.slice(1)))
                                          ? e.querySelectorAll(i)
                                          : e.getElementById(r)),
                              i || s(t))
                          )
                              return s(t) ? [t] : d.call(t);
                          if (n(t))
                              for (l = 0, a = t.length; l < a; l++)
                                  if (n(t[l]))
                                      for (h = 0, o = t[l].length; h < o; h++)
                                          s(t[l][h]) && u.push(t[l][h]);
                                  else s(t[l]) && u.push(t[l]);
                          return u;
                      })(t)),
                      void (
                          this.elements.length &&
                          ((this.originals = this.elements.map(function (t) {
                              return (l(t).html = l(t).html || t.innerHTML);
                          })),
                          this.split())
                      ))
                    : new u(t, i);
            }
            if (e.addEventListener && Function.prototype.bind) {
                var _ = "splitType" + +new Date(),
                    f = {},
                    c = 0,
                    p = Array.prototype.push,
                    d = Array.prototype.slice,
                    m = Object.keys,
                    g =
                        (Object.prototype.hasOwnProperty,
                        Object.defineProperty),
                    y =
                        (Object.defineProperties,
                        Object.getOwnPropertyDescriptor),
                    v = e.createDocumentFragment.bind(e),
                    x = e.createTextNode.bind(e),
                    T = {
                        splitClass: "",
                        lineClass: "line",
                        wordClass: "word",
                        charClass: "char",
                        split: "lines, words, chars",
                        position: "relative",
                        absolute: !1,
                        tagName: "div",
                        DEBUG: !1,
                    };
                return (
                    g(u, "defaults", {
                        get: function () {
                            return T;
                        },
                        set: function (t) {
                            T = o(T, t);
                        },
                    }),
                    (u.prototype.split = function (e) {
                        this.revert(),
                            (this.lines = []),
                            (this.words = []),
                            (this.chars = []),
                            e !== i && (this.settings = o(this.settings, e)),
                            a(
                                this.elements,
                                function (e) {
                                    (function (e) {
                                        var i,
                                            r,
                                            n,
                                            s,
                                            o = this.settings,
                                            u = o.tagName,
                                            _ = "B" + +new Date() + "R",
                                            f = o.split,
                                            c = -1 !== f.indexOf("lines"),
                                            m = -1 !== f.indexOf("words"),
                                            g = -1 !== f.indexOf("chars"),
                                            y =
                                                "absolute" === o.position ||
                                                !0 === o.absolute,
                                            T = h("div"),
                                            w = [],
                                            b = [];
                                        if (
                                            ((n = c ? h("div") : v()),
                                            (T.innerHTML = e.innerHTML.replace(
                                                /<br\s*\/?>/g,
                                                " " + _ + " "
                                            )),
                                            (s = T.textContent
                                                .replace(/\s+/g, " ")
                                                .trim()
                                                .split(" ")
                                                .map(function (t) {
                                                    if (t === _)
                                                        return (
                                                            n.appendChild(
                                                                h("br")
                                                            ),
                                                            null
                                                        );
                                                    if (g) {
                                                        var e = t
                                                            .split("")
                                                            .map(function (t) {
                                                                return h(u, {
                                                                    class:
                                                                        o.charClass +
                                                                        " " +
                                                                        o.splitClass,
                                                                    style: "display: inline-block;",
                                                                    textContent:
                                                                        t,
                                                                });
                                                            });
                                                        p.apply(b, e);
                                                    }
                                                    return (
                                                        m || c
                                                            ? ((r = h(u, {
                                                                  class:
                                                                      o.wordClass +
                                                                      " " +
                                                                      o.splitClass,
                                                                  style:
                                                                      "display: inline-block; position:" +
                                                                      (m
                                                                          ? "relative"
                                                                          : "static;"),
                                                                  children: g
                                                                      ? e
                                                                      : null,
                                                                  textContent: g
                                                                      ? null
                                                                      : t,
                                                              })),
                                                              n.appendChild(r))
                                                            : a(
                                                                  e,
                                                                  function (t) {
                                                                      n.appendChild(
                                                                          t
                                                                      );
                                                                  }
                                                              ),
                                                        n.appendChild(x(" ")),
                                                        r
                                                    );
                                                }, this)
                                                .filter(function (t) {
                                                    return t;
                                                })),
                                            (e.innerHTML = ""),
                                            e.appendChild(n),
                                            p.apply(this.words, s),
                                            p.apply(this.chars, b),
                                            y || c)
                                        ) {
                                            var P,
                                                S,
                                                O,
                                                k,
                                                C,
                                                R,
                                                A,
                                                M,
                                                D,
                                                F,
                                                L,
                                                z = [];
                                            (A = l(e).nodes =
                                                e.getElementsByTagName(u)),
                                                (M = e.parentElement),
                                                (D = e.nextElementSibling),
                                                (F = t.getComputedStyle(e)),
                                                (L = F.textAlign),
                                                y &&
                                                    ((k = {
                                                        left: n.offsetLeft,
                                                        top: n.offsetTop,
                                                        width: n.offsetWidth,
                                                    }),
                                                    (R = e.offsetWidth),
                                                    (C = e.offsetHeight),
                                                    (l(e).cssWidth =
                                                        e.style.width),
                                                    (l(e).cssHeight =
                                                        e.style.height)),
                                                a(A, function (t) {
                                                    if (t !== n) {
                                                        var e,
                                                            i =
                                                                t.parentElement ===
                                                                n;
                                                        c &&
                                                            i &&
                                                            ((e = l(t).top =
                                                                t.offsetTop) !==
                                                                S &&
                                                                ((S = e),
                                                                z.push(
                                                                    (P = [])
                                                                )),
                                                            P.push(t)),
                                                            y &&
                                                                ((l(t).top =
                                                                    e ||
                                                                    t.offsetTop),
                                                                (l(t).left =
                                                                    t.offsetLeft),
                                                                (l(t).width =
                                                                    t.offsetWidth),
                                                                (l(t).height =
                                                                    O =
                                                                        O ||
                                                                        t.offsetHeight));
                                                    }
                                                }),
                                                M.removeChild(e),
                                                c &&
                                                    ((n = v()),
                                                    (w = z.map(function (t) {
                                                        return (
                                                            n.appendChild(
                                                                (i = h(u, {
                                                                    class:
                                                                        o.lineClass +
                                                                        " " +
                                                                        o.splitClass,
                                                                    style:
                                                                        "display: block; text-align:" +
                                                                        L +
                                                                        "; width: 100%;",
                                                                }))
                                                            ),
                                                            y &&
                                                                ((l(i).type =
                                                                    "line"),
                                                                (l(i).top = l(
                                                                    t[0]
                                                                ).top),
                                                                (l(i).height =
                                                                    O)),
                                                            a(t, function (t) {
                                                                m
                                                                    ? i.appendChild(
                                                                          t
                                                                      )
                                                                    : g
                                                                    ? d
                                                                          .call(
                                                                              t.children
                                                                          )
                                                                          .forEach(
                                                                              function (
                                                                                  t
                                                                              ) {
                                                                                  i.appendChild(
                                                                                      t
                                                                                  );
                                                                              }
                                                                          )
                                                                    : i.appendChild(
                                                                          x(
                                                                              t.textContent
                                                                          )
                                                                      ),
                                                                    i.appendChild(
                                                                        x(" ")
                                                                    );
                                                            }),
                                                            i
                                                        );
                                                    })),
                                                    e.replaceChild(
                                                        n,
                                                        e.firstChild
                                                    ),
                                                    p.apply(this.lines, w)),
                                                y &&
                                                    ((e.style.width =
                                                        e.style.width ||
                                                        R + "px"),
                                                    (e.style.height = C + "px"),
                                                    a(A, function (t) {
                                                        var e =
                                                                "line" ===
                                                                l(t).type,
                                                            i =
                                                                !e &&
                                                                "line" ===
                                                                    l(
                                                                        t.parentElement
                                                                    ).type;
                                                        (t.style.top = i
                                                            ? 0
                                                            : l(t).top + "px"),
                                                            (t.style.left = e
                                                                ? k.left + "px"
                                                                : (i
                                                                      ? l(t)
                                                                            .left -
                                                                        k.left
                                                                      : l(t)
                                                                            .left) +
                                                                  "px"),
                                                            (t.style.height =
                                                                l(t).height +
                                                                "px"),
                                                            (t.style.width = e
                                                                ? k.width + "px"
                                                                : l(t).width +
                                                                  "px"),
                                                            (t.style.position =
                                                                "absolute");
                                                    })),
                                                D
                                                    ? M.insertBefore(e, D)
                                                    : M.appendChild(e);
                                        }
                                    }).call(this, e),
                                        (l(e).isSplit = !0);
                                },
                                this
                            ),
                            (this.isSplit = !0),
                            a(this.elements, function (t) {
                                for (
                                    var e = l(t).nodes || [],
                                        i = 0,
                                        r = e.length;
                                    i < r;
                                    i++
                                )
                                    (s = (n = e[i]) && n[_]) &&
                                        (delete n[s], delete f[s]);
                                var n, s;
                            });
                    }),
                    (u.prototype.revert = function () {
                        this.isSplit &&
                            (this.lines = this.words = this.chars = null),
                            a(
                                this.elements,
                                function (t) {
                                    l(t).isSplit &&
                                        l(t).html &&
                                        ((t.innerHTML = l(t).html),
                                        (t.style.height = l(t).cssHeight || ""),
                                        (t.style.width = l(t).cssWidth || ""),
                                        (this.isSplit = !1));
                                },
                                this
                            );
                    }),
                    u
                );
            }
        })(window, document);
    }),
    "object" == typeof LS_Meta &&
        LS_Meta.fixGSAP &&
        ((window.GreenSockGlobals = null),
        (window._gsQueue = null),
        (window._gsDefine = null),
        delete window.GreenSockGlobals,
        delete window._gsQueue,
        delete window._gsDefine,
        (window.GreenSockGlobals = LS_oldGS),
        (window._gsQueue = LS_oldGSQueue),
        (window._gsDefine = LS_oldGSDefine)),
    (window._layerSlider = {
        globals: { youTubeIsReady: !1, vimeoIsReady: !1 },
        GSAP: void 0 !== LS_GSAP && LS_GSAP,
        pluginsLoaded: [],
        pluginsNotLoaded: [],
        pluginsBeingLoaded: [],
        plugins: {},
        slidersList: {},
        currentScript: document.currentScript,
        lsScript: jQuery(
            'script[src*="layerslider.kreaturamedia.jquery.js"]'
        )[0],
        scriptPath: "",
        pluginsPath: !1,
        showNotice: function (t, e, i, r) {
            var n,
                s,
                a,
                o = jQuery(t),
                l = "ls-issue-" + e;
            switch (e) {
                case "jquery":
                    (a = "Multiple jQuery issue"),
                        (s =
                            'It looks like that another plugin or your theme loads an extra copy of the jQuery library causing problems for LayerSlider to show your sliders. Please navigate from your WordPress admin sidebar to LayerSlider -> Options -> Advanced and enable the "Include scripts in the footer" option.');
                    break;
                case "oldjquery":
                    (a = "Old jQuery issue"),
                        (s =
                            "It looks like you are using an old version (" +
                            i +
                            ") of the jQuery library. LayerSlider requires at least version " +
                            r +
                            " or newer. Please update jQuery to 1.10.x or higher. Important: Please do not use the jQuery Updater plugin on WordPress as it can cause issues in certain cases.");
            }
            o.each(function () {
                (n = jQuery(this)).hasClass(l) ||
                    (n.addClass(l),
                    jQuery(
                        '<div class="ls-slider-notification"><i class="ls-slider-notification-logo">!</i><strong>LayerSlider: ' +
                            a +
                            "</strong><span>" +
                            s +
                            "</span></div>"
                    ).insertBefore(n));
            });
        },
        removeSlider: function (t) {
            (this.slidersList[t] = null), delete this.slidersList[t];
        },
        checkVersions: function (t, e) {
            for (
                var i = t.split("."), r = e.split("."), n = 0;
                n < i.length;
                ++n
            ) {
                if (r.length == n) return !1;
                if (parseInt(i[n]) != parseInt(r[n]))
                    return !(parseInt(i[n]) > parseInt(r[n]));
            }
            return i.length, r.length, !0;
        },
    }),
    (Number.prototype.indexOf = function (t) {
        return ("" + this).indexOf(t);
    });
