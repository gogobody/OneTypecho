"use strict";
document.addEventListener("DOMContentLoaded", function () {
    let t = document.querySelectorAll(".j-setting-tab li"), e = document.querySelector(".j-setting-notice"),
        n = document.querySelector("#j-version"), s = document.querySelector(".j-setting-contain > form"),
        i = document.querySelectorAll(".j-setting-content");
    t.forEach(function (n) {
        n.addEventListener("click", function () {
            sessionStorage.setItem("j-setting-current", n.getAttribute("data-current")), t.forEach(function (t) {
                return t.classList.remove("active")
            }), n.classList.add("active"), "j-setting-notice" === n.getAttribute("data-current") ? (e.style.display = "block", s.style.display = "none") : (s.style.display = "block", e.style.display = "none"), i.forEach(function (t) {
                t.style.display = "none", t.classList.contains(n.getAttribute("data-current")) && (t.style.display = "block")
            })
        })
    }), sessionStorage.getItem("j-setting-current") ? ("j-setting-notice" === sessionStorage.getItem("j-setting-current") ? (e.style.display = "block", s.style.display = "none") : (s.style.display = "block", e.style.display = "none"), t.forEach(function (t) {
        t.getAttribute("data-current") === sessionStorage.getItem("j-setting-current") && (t.classList.add("active"), i.forEach(function (t) {
            t.classList.contains(sessionStorage.getItem("j-setting-current")) && (t.style.display = "block")
        }))
    })) : (t[0].classList.add("active"), e.style.display = "block", s.style.display = "none");
    const a = new XMLHttpRequest;
    a.onreadystatechange = (() => {
        if (4 === a.readyState) if (a.status >= 200 && a.status < 300 || 304 === a.status) {
            let t = JSON.parse(a.responseText);
            if (t.success) if (t.title !== n.innerHTML) {
                // let s = '<h2 class="update">检测到版本更新！</h2><p>当前版本号：' + n.innerHTML + "</p><p>最新版本号：" + t.title + "</p>" + t.content;
                // e.innerHTML = s
            } else {
                let s = '<h2 class="no-update">当前已是最新版本！</h2><p>当前版本号：' + n.innerHTML + "</p><p>最新版本号：" + t.title + "</p>" + t.content;
                e.innerHTML = s
            } else e.innerHTML = "请求失败！"
        } else e.innerHTML = "请求失败！"
    }), a.open("get", "https://ae.js.cn/qqshoucang.php?key=1e16fda842c8435ffc87f1bdae983dc1", !0), a.send(null)
});