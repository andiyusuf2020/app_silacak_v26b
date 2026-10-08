/*!
 * 06-comming-soon - Colorlib. No jQuery, no framework.
 * Behaviours: countdown
 */
(function () {
  'use strict';

  /* Countdown. A target date in the past counts to "now + N days" instead,
     the rule countdown100 already used, so the demo never reads 00:00:00. */
  var COUNTDOWNS = [{"sel":"#normal-countdown","kind":"strftime","date":null,"tpl":"<div class=\"time-sec\"><h3 class=\"main-time\">%D</h3> <span>Days</span></div><div class=\"time-sec\"><h3 class=\"main-time\">%H</h3> <span>Hours</span></div><div class=\"time-sec\"><h3 class=\"main-time\">%M</h3> <span>Mins</span></div><div class=\"time-sec\"><h3 class=\"main-time\">%S</h3> <span>Sec</span></div>"},{"sel":"#clock","kind":"strftime","date":"2018/01/01","tpl":"<div class=\"time-sec\"><span class=\"title\">%D</span> days </div><div class=\"time-sec\"><span class=\"title\">%H</span> hours </div><div class=\"time-sec\"><span class=\"title\">%M</span> minutes </div><div class=\"time-sec\"><span class=\"title\">%S</span> seconds </div>"}];
  var pad = function (n) { return ('0' + n).slice(-2); };
  function left(end) {
    var t = Math.max(0, end - Date.now());
    return { t: t, d: Math.floor(t / 864e5), h: Math.floor(t / 36e5) % 24,
             m: Math.floor(t / 6e4) % 60, s: Math.floor(t / 1e3) % 60 };
  }
  COUNTDOWNS.forEach(function (cd) {
    document.querySelectorAll(cd.sel).forEach(function (el) {
      if (el.querySelector('.flip-clock-wrapper')) return;   // drawn by the flip clock below
      var end;
      if (cd.kind === 'spans') {
        var c = cd.cfg;
        end = new Date(c.Y, c.M - 1, c.D, c.h, c.m, c.s).getTime();
        if (!(end > Date.now())) end = Date.now() + c.D * 864e5 + c.h * 36e5;
      } else {
        var raw = cd.date || el.getAttribute('data-date') || '';
        end = new Date(raw.replace(/-/g, '/')).getTime();
        if (!(end > Date.now())) end = Date.now() + 30 * 864e5;
      }
      function paint() {
        var r = left(end);
        if (cd.kind === 'spans') {
          var set = function (k, v) { var n = el.querySelector('.' + k); if (n) n.textContent = v; };
          set('days', r.d); set('hours', pad(r.h)); set('minutes', pad(r.m)); set('seconds', pad(r.s));
        } else {
          el.innerHTML = cd.tpl
            .replace(/%-D/g, r.d).replace(/%D/g, pad(r.d))
            .replace(/%-H/g, r.h).replace(/%H/g, pad(r.h))
            .replace(/%-M/g, r.m).replace(/%M/g, pad(r.m))
            .replace(/%-S/g, r.s).replace(/%S/g, pad(r.s))
            .replace(/%d/g, r.d % 7).replace(/%w/g, Math.floor(r.d / 7));
        }
        if (r.t <= 0) clearInterval(timer);
      }
      var timer = setInterval(paint, 1000);
      paint();
    });
  });
})();
