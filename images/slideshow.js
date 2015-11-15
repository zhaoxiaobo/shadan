function SlideShow(c) {
    var a = document.getElementById("slideContainer"), f = document.getElementById("slidesImgs").getElementsByTagName("li"),  d = f.length, c = c || 3000, e = lastI = 0, j, m;
    function b() {
        m = setInterval(function () {
            e = e + 1 >= d ? e + 1 - d : e + 1;
            g()
        }, c)
    }
    function k() {
        clearInterval(m)
    }
    function g() {
        f[lastI].style.display = "none";
       
        f[e].style.display = "block";
       
        lastI = e
    }
    f[e].style.display = "block";
  
   
    b()
}
;
/* 酷站代码整理 http://www.5icool.org */