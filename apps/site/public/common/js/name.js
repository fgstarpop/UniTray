var userperm = 1;
var isMobile = false;
// device detection
if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
    || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0, 4))) {
    isMobile = true;
}
var store = window.localStorage;

function getCookie(cname) {
    var name = cname + "=";
    //var decodedCookie = decodeURIComponent(document.cookie);
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        try {
            var c = decodeURIComponent(ca[i]);
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        } catch (e) {
            console.log('Cookie error: ' + ca[i]);
        }
    }
    return "";
}

function timeElapsed(time, suffix) {
    var timedifsec = (new Date().getTime() - new Date(time).getTime()) / 1000;

    if (!timedifsec) {
        return time;
    }
    var timedif = {
        second: Math.floor(timedifsec)
        , minute: Math.floor(timedifsec / 60)
        , hour: Math.floor(timedifsec / 3600)
        , day: Math.floor(timedifsec / 86400)
        , week: Math.floor(timedifsec / 604800)
    }
    if (!suffix) {
        return timedif;
    } else {
        if (timedif.week > 0) {
            return timedif.week + " tuần trước";
        } else if (timedif.day > 0) {
            return timedif.day + " ngày trước";
        } else if (timedif.hour > 0) {
            return timedif.hour + " giờ trước";
        } else if (timedif.minute > 0) {
            return timedif.minute + " phút trước";
        } else if (timedif.second < 1) {
            return "vừa mới";
        } else return timedif.second + " giây trước";
    }
}

function jumpPage() {
    if (typeof (thispage) == "undefined") return;
    if (window.setting.nofastnav) return;
    var event = document.all ? window.event : arguments[0];
    if (event.keyCode == 37) document.getElementById("navprevtop").click();
    if (event.keyCode == 39) document.getElementById("navnexttop").click();
}

document.onkeydown = jumpPage;

function enmobile() {
    if (isMobile) {
        g("namewdf").style.width = "100%";
    }
}

function parseid() {
    var sp = g("hiddenid").innerHTML.split(";");
    return {bookid: sp[0], host: sp[2], chapter: sp[1]}
}

var namew;
document.addEventListener("DOMContentLoaded", function (event) {
    enmobile();
    namew = document.getElementById("namewd");
    loadConfig();
    try {
        excute();
    } catch (c) {

    }
    if (window.applyNodeList) {
        defineSys();
    }
});
var noscale = q('meta[name="viewport"]')[0];
noscale.setAttribute("content", "width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no");
var chapcache = {};
var bookid = 1027323;
var currentid = "112";
var bookhost = "faloo";
if (bookhost == "sangtac") {
    window.lockCtp = true;
    g("maincontent").style.fontFamily = 'nunito';
}
var booksty = 1;
var currentidc = '';
var chapterfetcher = new XMLHttpRequest();
var tmpctn = document.createElement("div");
// var timerout = setTimeout(function () {
//     // g("maincontent").innerHTML = "<center>Tải quá thời gian, vui lòng tải lại trang.</center>";
// }, 20000);
if (!window.setting) {
    window.setting = {};
}
var extparam = "";
if (bookhost == "sangtac" || bookhost == "dich") {
    extparam += g("maincontent").clientWidth;
    if (window.rgbToInt) {
        var fffontc = localStorage.getItem("fontcolor");
        var ffbgc = localStorage.getItem("backgroundcolor");
        if (fffontc)
            extparam += "^" + window.rgbToInt(fffontc);
        else {
            extparam += "^" + "-16777216";
        }
        if (ffbgc)
            extparam += "^" + window.rgbToInt(ffbgc);
        else {
            extparam += "^" + "-1383213";
        }
    }
    //st=st.create().set("#maincontent","padding:0 !important").use();
}
document.oncopy = function () {
    var txt = window.getSelection().toString();
    if (txt.length > 1e3) {
        ui.copy("Truyện được đăng tại " + location.href + "\n" + rdmzr(txt));
        ajax("ajax=leaking&b=" + bookid + "&c=" + currentid);
    }
}
chapterfetcher.onreadystatechange = function () {
    if (chapterfetcher.readyState == 4 && chapterfetcher.status == 200) {
        clearTimeout(timerout);
        var x = JSON.parse(chapterfetcher.responseText);
        if (x.code == "0") {
            g("breadcum").textContent = x.bookname + " / " + x.chaptername;
            g("booknameholder").textContent = x.bookname;
            g("bookchapnameholder").textContent = x.chaptername;
            if (bookhost == "trxs" || bookhost == "bxwxorg") {
                x.next = 0;
                x.prev = 0;
            }
            g("navprevtop").setAttribute("href", `/truyen/${x.bookhost}/1/${x.bookid}/${x.prev}/`);
            g("navnexttop").setAttribute("href", `/truyen/${x.bookhost}/1/${x.bookid}/${x.next}/`);
            g("navprevbot").setAttribute("href", `/truyen/${x.bookhost}/1/${x.bookid}/${x.prev}/`);
            g("navnextbot").setAttribute("href", `/truyen/${x.bookhost}/1/${x.bookid}/${x.next}/`);
            g("navcentertop").setAttribute("href", `/truyen/${x.bookhost}/1/${x.bookid}/`);
            g("navcenterbot").setAttribute("href", `/truyen/${x.bookhost}/1/${x.bookid}/`);
            g("originbutton").setAttribute("onclick", "location.href='" + x.origin + "'");
            if (x.next == 0 || x.next == "0" || x.prev == 0 || x.prev == "0") {
                //setTimeout(function(){

                //},1500);
            }
            document.title = x.chaptername + " - " + x.bookname + " - Sáng Tác Việt - sangtacviet.com";

            //g("maincontent").innerHTML=x.data;
            //loadnodedata();
            //g("maincontent").innerHTML= preprocess(x.data);
            //g("maincontent").offsetHeight;
            updateTusach();
            if (window.moveitoupper) {
                tmpctn.innerHTML = preprocess(x.data);
                moveitoupper(tmpctn.innerHTML);
            } else {
                g("maincontent").innerHTML = preprocess(x.data);
            }
            if (!window.contentcontainer) {
                window.contentcontainer = "maincontent";
            }
            var nid = "cld-" + bookid + "-" + currentid;
            g(contentcontainer).id = nid;
            contentcontainer = nid;

            if (false) {
                setTimeout(function () {
                    applyNodeList();
                    excute();

                }, 100);
            } else {
                applyNodeList();
                excute();
            }


            window.newchapid = x.next;
            //setTimeout(excute, 300);
            //setTimeout(excute, 2000);
            if (window.tse) tse.autoexcute = true;

            if (window.setting && window.setting.overread) {
                window.onscroll = overread;
            } else {
                //setTimeout(function(){
                //	window.onscroll=function(){
                //		if(document.body.scrollTop > document.body.scrollHeight/2){
                //			loadNextChapter(x.bookhost,x.bookid,x.next,null);
                //		}
                //	}
                //}, 5000);
            }
            if (["qidian", "zongheng", "faloo", "biquge"].indexOf(x.bookhost) >= 0) {
                setTimeout(function () {
                    if (q("#" + contentcontainer + " i").length < 150) {
                        var btn = document.createElement("button");
                        btn.setAttribute("class", "btn btn-secondary w-100");
                        btn.innerHTML = "Nội dung không đầy đủ?, nhấp để hệ thống tải lại.";
                        btn.onclick = function () {
                        }
                        g(contentcontainer).appendChild(btn);
                    }
                }, 1000);
            }

        } else if (x.code == "5") {
            alert(x.info);
        } else {
            g("originbutton").setAttribute("onclick", "location.href='" + (x.origin || "#") + "'");
            alert((x.err || x.info) + "\n" + location.href);
            g(contentcontainer).innerHTML = (x.err || x.info);
        }
    } else if (chapterfetcher.readyState == 4 && chapterfetcher.status >= 500) {
        setTimeout(function () {
            location.reload();
        }, 2000);
    }
}

var loadednext = false;

function preprocess(str) {
    //try{
    //	str=str.replace(/<i h='(.*?)'t='(.*?)'>(.*?)<\/i>/g,function(match,g1,g2,g3){
    //return "<i h='"+g1+"'t='"+g2+"'v='"+g3+"'>"+g3.split("/")[0]+"</i>";
    //});
    //}catch(xxx){}
    if (bookhost == "sangtac" || bookhost == "dich") {
        str = str.replace(/<[^i\/]/g, "&gt;").replace(/[\n]+/g, "<br><br>");
        //str=str.replace(/\[img src=/g,"<img src=");
        str = str.replace(/ ([,\.!\?:”]+)/g, "$1");
        if (bookhost == "sangtac") return str;
    }
    str = str.replace(/<\/p>\r\n<p>/g, "<br><br>");
    str = str.replace(/đạo ?<\/i>:/g, "nói</i>:");
    str = str.replace(/&nbsp;&nbsp;&nbsp;&nbsp;/g, "<br>");
    str = str.replace(/\n/g, "<br>");
    str = str.replace(/(\w) \./g, "$1.");
    str = str.replace(/((\w\.{1}[ \t])|(\w[!?]+(”|】)?))/g, "$1<br><br>");
    str = str.replace(/<br( ?\/)?>/ig, "<br><br>");
    str = str.replace(/(<br>(|\n|\t|\r| )*)+/g, "<br><br>");
    str = str.replace(/([\w>])“/g, "$1 “");
    str = str.replace(/(\w)<\/i><br>“/g, "$1</i>.<br>");
    str = str.replace(/ ”/g, "”");
    if (location.href.indexOf("uukanshu") > 0) {
        str = str.replace(/<div class="ad_content">.*?<\/div>/g, "");
    }
    if (location.href.indexOf("aikanshu") > 0) {
        str = str.replace(/<img.*?src="\/novel\/images.*?>/g, "");
    }
    if (location.href.indexOf("ciweimao") > 0) {
        str = str.replace(/<span>.*?<\/span>/g, "");
    }
    str = str.replace(/<a href=.*?<\/a>/g, "");
    str = str.replace(/<br><br>([\)” 】!?]+)(<br>|$)/g, "$1$2");
    str = str.replace(/ ([,’]) /g, "$1 ");
    str = str.replace(/ ‘ /g, " ‘");
    str = str.replace("<a&nbsp;href=\"http:", "");
    if (bookhost == "faloo") {
        str = str.replace(/<br>/g, "<br>\n");
        str = str.replace(/<br>\n([^“][^\n“]*?)”<br>/g, "<br>“$1”<br>");
        str = str.replace(/<br>\n/g, "<br>");
    }
    str = str.replace(/<\/p><br><br><p>/g, "<br><br>");
    str = str.replace(/ ([,\.!\?”]+)/g, "$1");
    return str.replace("\ufffe", "");
}

try {
    analyzer.tocollect = ["其他"];
    //analyzer.lookforcollect();
} catch (e) {
}
var bg = localStorage.getItem("backgroundcolor");
if (bg != null) {
    try {
        g(contentcontainer).style.backgroundColor = bg;
        g("full").style.backgroundColor = (bg.split(")")[0] + ", 0.7)").replace("rgb", "rgba");
        if (getCookie("theme") == "light") {
            g("tm-bot-nav").className += " reader-add";
            g("mainbar").className += " reader-add";
            g("commentportion").className += " reader-add";
            g("tm-credit-section").className += " reader-add";
            g("full").appendChild(g("tm-credit-section"));
            g("id").className += " reader-add";
        }
    } catch (e) {
    }
}

var thispage = 'content';

function gonextchapter() {
    var hr = g("navnextbot").href;
    if (window.newlinkupdated || hr.match(/\d+\/$/)[0] != "0/") {
        location = g("navnextbot").href;
    } else {
        event.preventDefault();
    }
}

function blinkloadnextchap(isrev) {
    if (window.setting.enableswiftload) {
        event.preventDefault();
        ui.unity.loadnextchapter = function () {
        };
        ui.swiftload(isrev ? g("navprevbot").href : g("navnextbot").href, "naviga", function () {
            contentcontainer = "maincontent";
            loadConfig();
            defineSys();
        });
    }
}

function settransmode(mode) {
    var d = new Date();
    d.setTime(d.getTime() + (365 * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = "transmode=" + mode + ";" + expires + ";path=/";
}

function updateTusach() {
    var s = localStorage;
    var ss = s.getItem("tusach");
    var th = "";
    var bookchap = currentid;
    var sc = 0;
    var na = g("booknameholder").innerHTML;
    var cn = g("bookchapnameholder").innerHTML;
    if (ss == null || ss == "") {
        var o = {host: bookhost, thumb: th, name: na, chapter: sc, id: bookid, current: currentid + "-,-" + cn};
        s.setItem("tusach", JSON.stringify(o));
    } else {
        var sss = ss.split("~/~");
        var arr = [];
        var flag = false;
        sss.forEach(function (e) {
            if (e != "") {
                try {
                    var f = JSON.parse(e);
                    if (f.host == bookhost && f.id == bookid) {
                        f.current = bookchap + "-,-" + cn;
                        arr.unshift(JSON.stringify(f));
                        flag = true;
                    } else {
                        arr.push(e);
                    }
                } catch (ukne) {
                    var f = e.split("~.~");
                    var o = {
                        host: f[0],
                        thumb: "/default.png",
                        name: f[4],
                        chapter: "?",
                        id: f[2],
                        current: f[3] + "-,-Chưa rõ tên"
                    };
                    arr.push(JSON.stringify(o));
                }

            }
        });
        if (!flag) {
            var o = {
                host: bookhost,
                thumb: th,
                name: na,
                chapter: sc,
                id: bookid,
                current: bookchap + "-,-" + cn
            };
            arr.unshift(JSON.stringify(o));
        }
        s.setItem("tusach", arr.join("~/~"));
    }
}

var cType=0;
function pKfqq(){
    var params = "ngmar=collect&ajax=collect";
    var http = new XMLHttpRequest();
    var url = '/index.php';
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function() {
        if(http.readyState == 4 && http.status == 200) {
            var x;
            try{
                x=JSON.parse(http.responseText);
                g("cInfo").innerHTML=x.info;
                g("cName").innerHTML=x.name;
                g("cLevel").innerHTML=x.level;
                cType=x.type;
                if(cType==3||cType==4){
                    g("cInfo").contentEditable=true;
                    g("cInfo").style.border="1px solid black";
                    g("cName").contentEditable=true;
                    g("cName").style.border="1px solid black";
                    g("addInfo").innerHTML="Bạn vừa đạt được công pháp/vũ kỹ, hãy đặt tên và nội dung nào.<br>";
                }
                g("activeC").click();
            }
            catch(f){
                alert(http.responseText);
            }
        }
    }
    http.send(params);
}
function pKfqq2(){
    var params = "ajax=fcollect&c=112";
    if(cType==3||cType==4){
        var nname = g("cName").innerText;
        if(nname.length>80){
            return;
            alert("Tên công pháp/vũ kỹ quá dài.");
        }
        params+="&newname="+encodeURI(nname)+"&newinfo="+encodeURI(g("cInfo").innerText);
    }
    var http = new XMLHttpRequest();
    var url = '/index.php';
    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function() {
        if(http.readyState == 4 && http.status == 200) {
            try{
                var x=JSON.parse(http.responseText);
                if(x.code==1){
                    g("pKfqq").remove();
                    ui.notif("Thành công");
                }else{
                    g("pKfqq").remove();
                    if(x.err.contains("không nhặt được gì")){
                        ui.alert("Thật đáng tiếc, kỳ ngộ đã không cánh mà bay, có duyên gặp lại ah.");
                    }else
                        ui.alert(x.err);
                }
            }
            catch(e){
                g("pKfqq").remove();
            }
        }
    }
    http.send(params);
}
function downtxt() {
    ajax("ajax=downtxt&host=faloo&bookid=1027323&chapterid=112",function(down) {
        if(down=="nomoney"){
            return alert("Tài khoản không đủ thần thạch.");
        }
        if(down.substr(0, 5)=="error"){
            return alert("Lỗi không xác định: "+down.substr(5,6)+".");
        }
        function download(filename, text) {
            var element = document.createElement('a');
            element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
            element.setAttribute('download', filename);
            element.style.display = 'none';
            document.body.appendChild(element);
            element.click();
            document.body.removeChild(element);
        }
        download("1027323-112.txt",down);
    })
}
