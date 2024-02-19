<script src="{{ asset('common/js/jqr.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('/asset/main.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/asset/boostrap.css') }}">

<style type="text/css">
    .fa-download{
        min-width: 30;
        font-size: 30px;
    }
    .cgcolor{
        font-size: 15px;
        line-height: 1.2;
        width: 25px;
        text-align: center;
    }
    .noti{
        padding: 0 6px;
        height: 60px;
        border: 1px solid #dddddd;
        margin-top: -1px;
    }
    .noti h6{
        margin: 6px 0 0 0;
        font-size: 14px;
    }
    #searchbox{
        line-height: 2;
    }
	#maincontent br{
        display: inline;
    }

</style>

<script src="{{ asset('common/js/stv.ui.js') }}"></script>
<script type="text/javascript">
    window.onerror=function(msg, url, lineNo, columnNo, error){
    }
</script>
<script src="{{ asset('common/js/hanviet.js') }}"></script>
<script src="{{ asset('common/js/qtOnline.js') }}"></script>

<script>

    var userperm=1;
    var isMobile= false;
    if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(navigator.userAgent)
        || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(navigator.userAgent.substr(0,4))) {
        isMobile= true;
    }
    var store = window.localStorage;



    function enmobile() {
        if (isMobile) {
            g("namewdf").style.width = "100%";
        }
    }
    function parseid(){
        var sp=g("hiddenid").innerHTML.split(";");
        return {bookid:sp[0],host:sp[2],chapter:sp[1]}
    }
    var namew;
    document.addEventListener("DOMContentLoaded", function(event) {
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
</script>
<style type="text/css">
    .contentbox img{
        display: block;
        margin: 4 auto;
    }
</style>
<script  type="text/javascript">
            var noscale=q('meta[name="viewport"]')[0];
            noscale.setAttribute("content", "width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no");
             var bookid = parseid().bookid
             var bookhost = parseid().host
             var currentid = parseid().chapter
            function preprocess(str){



                str=str.replace(/<\/p>\r\n<p>/g,"<br><br>");
                str=str.replace(/đạo ?<\/i>:/g,"nói</i>:");
                str=str.replace(/&nbsp;&nbsp;&nbsp;&nbsp;/g,"<br>");
                str=str.replace(/\n/g,"<br>");
                str=str.replace(/(\w) \./g,"$1.");
                str=str.replace(/((\w\.{1}[ \t])|(\w[!?]+(”|】)?))/g,"$1<br><br>");
                str=str.replace(/<br( ?\/)?>/ig,"<br><br>");
                str=str.replace(/(<br>(|\n|\t|\r| )*)+/g,"<br><br>");
                str=str.replace(/([\w>])“/g,"$1 “");
                str=str.replace(/(\w)<\/i><br>“/g,"$1</i>.<br>");
                str=str.replace(/ ”/g,"”");

                str=str.replace(/<a href=.*?<\/a>/g,"");
                str=str.replace(/<br><br>([\)” 】!?]+)(<br>|$)/g,"$1$2");
                str=str.replace(/ ([,’]) /g,"$1 ");
                str=str.replace(/ ‘ /g," ‘");
                str=str.replace("<a&nbsp;href=\"http:", "");
                if(bookhost=="faloo"){
                    str=str.replace(/<br>/g,"<br>\n");
                    str=str.replace(/<br>\n([^“][^\n“]*?)”<br>/g,"<br>“$1”<br>");
                    str=str.replace(/<br>\n/g,"<br>");
                }

		if(location.href.indexOf("uukanshu")>0){
					str=str.replace(/<div class="ad_content">.*?<\/div>/g,"");
				}
                str=str.replace(/<\/p><br><br><p>/g,"<br><br>");
		str=str.replace(/<\/p><p>/g,"<br><br>");
		str=str.replace(/<p>/g,"<br>");
		str=str.replace(/<\/p>/g,"<br>");

                str=str.replace(/ ([,\.!\?”]+)/g,"$1");
		str=str.replace(/<\/p><p>/g,"<br><br>");

                return str.replace("\ufffe","");

            }
            try {
                analyzer.tocollect=["其他"];
            } catch(e) {}

            if(!window.setting){
                window.setting={};
            }
            $(document).ready(function(){
                $('#maincontent').html(preprocess($('#maincontent').html()));
                applyNodeList();
                excute();
                //excute();

            })

        </script>
<script type="text/javascript">
    var bg = localStorage.getItem("backgroundcolor");
    if (bg != null) {
        try {
            g(contentcontainer).style.backgroundColor = bg;
            g("full").style.backgroundColor = (bg.split(")")[0]+", 0.7)").replace("rgb","rgba");

        } catch (e) {}
    }
</script>
<div class="container">
    <script>
        var thispage='content';
    </script>
</div>


<div id="boxfather">
    <div id="nsbox" class="bg-info" contenteditable="false" style="display:none;font-size: 16px;font-family: Arial" onkeydown="event.stopPropagation();" onclick="event.stopPropagation();">
        <style type="text/css">
            button{padding: 0;}
        </style>
        <div class="rowedit" style="flex-wrap: nowrap;">
            <button type="button" style="position: absolute;left: auto;top:0;right: 100%;width: 20%;height: 40px;" class="btn btn-danger col" onclick="hideNb(this);"><i class="fas fa-times-circle"></i></button>
            <button type="button" class="btn btn-info" onclick="expandLeft(this);"><i class="fas fa-chevron-circle-left"></i>Mở rộng</button>
            <button type="button" class="btn btn-info" onclick="expandRight(this);">Mở rộng<i class="fas fa-chevron-circle-right"></i></button>
            <button type="button" class="btn btn-danger col" onclick="hideNb(this);"><i class="fas fa-times-circle"></i></button></div>
        <div class="rowedit" style="display: none;">
            <span style="display:inline-block;width:30px;color:white;font-size:12px;padding:6px;background:green;">VP</span>
            <input class="col" style="padding:0;font-size: 12px;" id="vuc" placeholder=" Vietphrase viết hoa">
            <button class="btn btn-info" onclick="applyNs('vuc')" type="button"><i class="far fa-check-circle"></i></button>
            <button type="button" class="btn btn-info" onclick="applyAndSaveNs('vuc')"><i class="far fa-save"></i></button>
        </div>
        <div class="rowedit">
            <span style="display:inline-block;width:30px;color:white;font-size:12px;padding:6px;background:green;">hv</span>
            <input class="col" style="padding:0;font-size: 12px;" id="hv" placeholder=" Hán Việt">
            <button onclick="applyNs('hv')" class="btn btn-info" type="button"><i class="far fa-check-circle"></i></button>
            <button type="button" class="btn btn-info" onclick="applyAndSaveNs('hv')"><i class="far fa-save"></i></button>
        </div>
        <div class="rowedit">
            <span style="display:inline-block;width:30px;color:white;font-size:12px;padding:6px;background:green;">HV</span>
            <input class="col" style="padding:0;font-size: 12px;" id="huc" placeholder=" Hán Việt viết hoa">
            <button onclick="applyNs('huc')" class="btn btn-info" type="button"><i class="far fa-check-circle"></i></button>
            <button type="button" class="btn btn-info" onclick="applyAndSaveNs('huc')"><i class="far fa-save"></i></button>
        </div>
        <div class="rowedit">
            <span style="display:inline-block;width:30px;color:white;font-size:12px;padding:6px;background:green;">zw</span>
            <input class="col" style="padding:0;font-size: 12px;" onkeyup="instrans(this)" id="zw" placeholder="Tiếng Trung">
            <button onclick="googletrans('zw')" class="btn btn-info" type="button"><i class="fas fa-language"></i></button>
            <button type="button" class="btn btn-info" onclick="copychinese()"><i class="far fa-copy"></i></button>
            <button onclick="googlesearch('zw')" class="btn btn-info" type="button"><i class="fas fa-search"></i></button>
            <script>

            </script>
        </div>
        <div class="rowedit">
            <span style="display:inline-block;width:30px;color:white;font-size:12px;padding:6px;background:green;">Tr</span>
            <input class="col" style="padding:0;font-size: 12px;" id="instrans" placeholder="Dịch trực tiếp">
            <button onclick="openmodvp()" style="font-size: 12px;width: 82px;" class="btn btn-info" type="button"><i class="far fa-edit"></i> Sửa</button>
        </div>
        <div class="rowedit">
            <span style="display:inline-block;width:30px;color:white;font-size:12px;padding:6px;background:green;">tc</span>
            <input class="col" style="padding:0;font-size: 12px;" id="op" placeholder=" cụm từ tùy chọn">
            <button onclick="applyNs('op')" class="btn btn-info" type="button"><i class="far fa-check-circle"></i></button>
            <button class="btn btn-info" type="button" onclick="applyAndSaveNs('op')"><i class="far fa-save"></i></button>
        </div>
        <div class="rowedit" style="flex-wrap: nowrap;">
            <button onclick="showNS()" style="padding: 4px;" class="btn btn-info" type="button"><i class="fas fa-cog"></i> Quản lý </button>
            <button class="btn btn-info"style="padding: 12px;" type="button" onclick="excute()"><i class="fas fa-play"></i></button>
            <button class="btn btn-info"style="padding: 4px;" type="button" onclick="showAddName()" data-toggle="modal" data-target="#addnamebox"><i class="fas fa-plus"></i> Thêm name</button>

        </div>

    </div>
    <div class="modal" style="font-size:12px;" id="addnamebox" onkeydown="event.stopPropagation()" onclick="$(this).fadeOut();">
        <style type="text/css">
            .modal-body button{
                padding: 4px;
            }
            .select-editable {clear: both; position:relative; background-color:white; border:solid grey 1px;  width:100%; height:27px; }
            .select-editable select { position:absolute; top:0px; left:0px; font-size:17px; border:none; width:100%; margin:0; background: #f5f5f5;}
            .select-editable input { position:absolute; top:0px; left:0px; width:90%; padding:1px; font-size:15px; border:none; }
            .select-editable select:focus, .select-editable input:focus { outline:none; }</style>
        <div class="modal-dialog modal-sm modal-dialog-centered" style="position: fixed;bottom: 10;left: 50%; transform: translate(-50%,0);width: 90%;max-width: 360px;" onclick="event.stopPropagation();">
            <div class="modal-content">
                <script type="text/javascript">
                    if(!isMobile){
                        var w=g("addnamebox").children[0];
                        w.style.top = '10';
                        w.style.bottom = 'auto';
                    }

                </script>
                <div class="modal-header">
                    <h6 class="modal-title">Thêm name/Chỉnh name</h6>
                    <button type="button" class="close" data-dismiss="modal" onclick="$('#addnamebox').hide();">&times;</button>
                </div>
                <div class="modal-body">
                    Tiếng Trung:<br>
                    <input type="text" style="width: 100%" id="addnameboxip1" onkeyup="instrans2(this,false);" placeholder=" Tiếng trung">
                    <br>
                    Hán Việt:<br>
                    <input type="text" style="width: 100%" id="addnameboxip3" placeholder=" Hán việt">
                    <div style="display: flex;"><button onclick="addSuperName('hv','z')">Dùng</button>
                        <button onclick="addSuperName('hv','f')">Hoa chữ đầu</button>
                        <button onclick="addSuperName('hv','s')">Hoa 2 chữ đầu</button>
                        <button onclick="addSuperName('hv','l')">Thường Chữ cuối</button>
                        <button onclick="addSuperName('hv','a')">Hoa Toàn Bộ</button>
                    </div>
                    Tiếng Anh:<br>
                    <input type="text" style="width: 100%" id="addnameboxip4" placeholder=" Tiếng Anh"><br>
                    <button style="float: right;" onclick="addSuperName('el')">Dùng</button><br>
                    Vietphrase:
                    <div class="select-editable">
                        <select onchange="this.nextElementSibling.value=this.value">
                        </select>
                        <input type="text" id="addnameboxip2" placeholder=" Vietphrase v.v.." name="format" value=""/>
                    </div>
                    <button style="float: right;" onclick="addSuperName('vp')">Dùng</button>
                    Name trong kho:<br>
                    <div class="select-editable">
                        <select onchange="this.nextElementSibling.value=this.value">
                        </select>
                        <input type="text" id="addnameboxip5" placeholder=" Name trong kho v.v.." name="format" value=""/>
                    </div>
                    <button onclick="deleteName()">Xóa name</button>
                    <button style="float: right;" onclick="addSuperName('kn')">Dùng</button>
                </div>
                <div id="booknamemanager" style="padding: 0 15px;" hidden class="bg-light">
                    <span class="btn" onclick="this.nextElementSibling.click()">Lưu vào truyện </span>
                    <input type="checkbox" onchange="store.setItem('issavetobook',this.checked?'true':'false')" id="issavetobook" style="float: right;transform: scale(2);-webkit-appearance: checkbox;margin: 12px 8px 0 0;">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-info" onclick="addSuperName('el')">Thêm Name Anh</button>
                    <button type="button" class="btn btn-info" onclick="addSuperName('hv','a')">Thêm Hán Việt</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" style="font-size:12px;" id="modifyvpbox" onkeydown="event.stopPropagation()" onclick="$(this).fadeOut();">
        <div class="modal-dialog modal-sm modal-dialog-centered" style="position: fixed;bottom: 10;left: 50%; transform: translate(-50%,0);width: 90%;max-width: 360px;" onclick="event.stopPropagation();">
            <div class="modal-content">
                <script type="text/javascript">
                    if(!isMobile){
                        var w=g("modifyvpbox").children[0];
                        w.style.top = '10';
                        w.style.bottom = 'auto';
                    }
                </script>
                <div class="modal-header">
                    <h6 class="modal-title">Chỉnh Vietphrase</h6>
                    <button type="button" class="close" data-dismiss="modal" onclick="$('#modifyvpbox').hide();">&times;</button>
                </div>
                <div class="modal-body">
                    Tiếng Trung:<br>
                    <input type="text" style="width: 100%" id="modifyvpboxip1" onkeyup="instrans3(this);" placeholder=" Tiếng trung">
                    <br>
                    Hán Việt:<br>
                    <input type="text" style="width: 100%" id="modifyvpboxip2" disabled placeholder=" Hán việt">
                    <button style="float: right;" onclick="movehantomean()">Chuyển xuống</button>
                    <br>
                    Vietphrase:<br>
                    <input type="text" style="width: 100%" id="modifyvpboxip3" placeholder=" Vietphrase"><br>
                    <button onclick="movemeaning()">Nghĩa &lt;</button>
                    <button style="float: right;" onclick="g('modifyvpboxip3').value='';">&nbsp;X&nbsp;</button>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-info" onclick="delvp()">Xóa vp</button>
                    <button type="button" class="btn btn-info" onclick="modvp()">Xác nhận</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="namewdf" style="visibility:hidden" onkeydown="event.stopPropagation();" class='namewd'>
    <div id="toolbar">

        <button type="button" class='toolbar' onclick="saveNS()">Lưu lại</button>
        <button type="button" class='toolbar' onclick="saveNS();excute();">Chạy</button>
        <button type="button" class='toolbar' onclick="clearNS()">Xóa hết</button>
        <button type="button" class='toolbar' onclick="getNSOnline()">Tải name</button>
        <button type="button" class='toolbar' onclick="uploadNS()">Upload</button>
        <button type="button" class='toolbar' style="float:right" onclick="hideNS()">Ẩn</button>

    </div>
    <div id="toolbar2" style="display:none;">
        <button type="button" class='toolbar' onclick="this.parentElement.style.display='none';g('toolbar').style.display='block';g('dlnametb').style.zIndex='-1';"><span class='glyphicon glyphicon-chevron-left'></span>Quay lại</button>
        <button type="button" class='toolbar' style="float:right" onclick="hideNS()">Ẩn</button>
    </div>
    <hr style="margin:0;clear:both">
    <div id="namewdc" style="position:relative">
        <textarea id="namewd"  ></textarea>
        <div id="dlnametb" style="z-index:-1;position:absolute;top:0;left:0;width:100%;height:100%;background-color:white;max-height:269px;overflow-y:auto;">
            <table id="dlnametbcontent"></table>
        </div>

        <div id="upnamewd" style="z-index:-1;position:absolute;top:0;left:0;width:100%;height:100%;background-color:white;">
            <br>
            <center><input id="uploaduser" placeholder="Nhập tên của bạn"><br>
                <br>
                <button type="button" id="sendnsbt" onclick="this.disabled=true;sendNS()">Gửi</button>&nbsp;<button type="button" onclick="this.parentElement.parentElement.style.zIndex='-1'">Hủy</button><br></center>
        </div>
    </div>

    <div style="width:100%;border-top:1px solid black">
        <input id="fastseltext" onpaste="fastCreateN(this)" onkeyup="fastCreateN(this)" style="float:left;width:40%;height:24px;font-size:16px;" type="text"><span style="float:left;">&nbsp;=&nbsp;</span><input style="float:left;width:40%;height:24px;font-size:16px;" id="fastgentext" >
        <button type="button" class="toolbar" style="float:right;margin: 0;padding: 0;" onclick="fastAddNS()">Thêm </button>
        <div class='clear'></div>
    </div>
    <div style="padding: 4px;height: 220px;overflow-y: auto;">
        <button type="button" onclick="excute()" class="icb">
            <img loading=lazy src="/asset/runname.png"><br>Chạy</button>
        <button type="button" onclick="getNSOnline()" class="icb">
            <img loading=lazy src="/asset/downname.png"><br>Tải name được chia sẻ</button>
        <button type="button" onclick="importName()" class="icb">
            <img loading=lazy src="/asset/inputname.png"><br>Nhập dữ liệu name</button>
        <button type="button" onclick="openinsertvpmodal()" class="icb">
            <img loading=lazy src="/asset/inputname.png"><br>Sử dụng file vp riêng</button>
        <button type="button" onclick="exportName()" class="icb">
            <img loading=lazy src="/asset/outputname.png"><br>Xuất dữ liệu</button>
        <button type="button" onclick="uploadNS()" class="icb">
            <img loading=lazy src="/asset/sharename.png"><br>Chia sẽ gói name</button>
        <button type="button" onclick="var wd=ui.win.createBox('Gói Name Đặc Biệt','goiname').show();" onclicks="$('#customnamebox').modal('toggle')" class="icb">
            <img loading=lazy src="/asset/downname.png"><br>Tải name đặc biệt</button>
        <button type="button" onclick="analyzer.reset();" class="icb">
            <img loading=lazy src="/asset/delanalyzermem.png"><br>Xóa bộ nhớ Analyzer</button>
        <button type="button" onclick="ui.win.createBox('Công cụ','tools').show()" class="icb">
            <img loading=lazy src=""><br>Công cụ</button>
    </div>

</div>
<div id="configBox" class="bg-light" style="position: fixed;bottom: 75px;right: 65px;border: 1px solid #cccbcb;display: none;">
    <div>
        <div id="stylediv" style="line-height:1.5;user-select: none;padding: 5px" onclick="event.stopPropagation();">
            Màu nền: <input type="color" style="float: right;height: 20px;" onchange="this.style.backgroundColor=this.value;changebg(this)">
            <br>
            <span class="cgcolor" style="background-color:#eae4d3" onclick="changebg(this)">A</span>
            <span class="cgcolor" style="background-color:#ffffff" onclick="changebg(this)">A</span>
            <span class="cgcolor" style="background-color:#000000" onclick="changebg(this)">A</span>
            <span class="cgcolor" style="background-color:#d0d0d0" onclick="changebg(this)">A</span>
            <span class="cgcolor" style="background-color:#a3e6a2" onclick="changebg(this)">A</span>
            <span class="cgcolor" style="background-color:#a7d4e8" onclick="changebg(this)">A</span>
            <span class="cgcolor" style="background-color:#d7ffff" onclick="changebg(this)">A</span><br>
            <span class="cgcolor" style="background-color:#8a8a88" onclick="changebg(this)">A</span>
            <span class="cgcolor" style="background-color:#fbdada" onclick="changebg(this)">A</span>
            <span class="cgcolor" style="background-color:#6f3333" onclick="changebg(this)">A</span>
            <span class="cgcolor" style="background-color:#5cbd94" onclick="changebg(this)">A</span>
            <span class="cgcolor" style="background-color:#c492de" onclick="changebg(this)">A</span>
            <span class="cgcolor" style="background-color:#127743" onclick="changebg(this)">A</span>
            <span class="cgcolor" style="background-color:#ececec" onclick="changebg(this)">A</span><br>
            Màu chữ:<br>
            <span class="cgcolor" style="color:#eae4d3" onclick="changebgx(this)">A</span>
            <span class="cgcolor" style="color:#ffffff" onclick="changebgx(this)">A</span>
            <span class="cgcolor" style="color:#000000" onclick="changebgx(this)">A</span>
            <span class="cgcolor" style="color:#d0d0d0" onclick="changebgx(this)">A</span>
            <span class="cgcolor" style="color:#a3e6a2" onclick="changebgx(this)">A</span>
            <span class="cgcolor" style="color:#a7d4e8" onclick="changebgx(this)">A</span>
            <span class="cgcolor" style="color:#d7ffff" onclick="changebgx(this)">A</span><br>
            <span class="cgcolor" style="color:#8a8a88" onclick="changebgx(this)">A</span>
            <span class="cgcolor" style="color:#fbdada" onclick="changebgx(this)">A</span>
            <span class="cgcolor" style="color:#6f3333" onclick="changebgx(this)">A</span>
            <span class="cgcolor" style="color:#5cbd94" onclick="changebgx(this)">A</span>
            <span class="cgcolor" style="color:#c492de" onclick="changebgx(this)">A</span>
            <span class="cgcolor" style="color:#127743" onclick="changebgx(this)">A</span>
            <span class="cgcolor" style="color:#ececec" onclick="changebgx(this)">A</span><br>
            <center>
                <span style="padding:6px;border-radius:6px;" onclick="decreaseFontsize()"><i class="fas fa-minus"></i> </span><span style="margin-right: -20px;position: relative;"><img loading=lazy src="/asset/Aa.png" style="width: 18px;
    height: 20px;"></span><input id="changefs" style="max-width:45px;padding: 3px;padding-left:20px;" onchange="changefontsize(this);" value="25"><span style="padding:6px;border-radius:6px;" onclick="increaseFontsize()"><i class="fas fa-plus"></i></span>
                <span style="padding:6px;border-radius:6px;" onclick="decreaseLineheight()"><i class="fas fa-minus"></i> </span><span style="margin-right: -15px;position: relative"><img loading=lazy src="/asset/lh.png" style="    width: 12px;
    height: 28px;"></span><input id="changefs2" style="max-width:45px;padding: 3px;padding-left: 15px;" onchange="changelineheight(this);" value="1.6"><span style="padding:6px;border-radius:6px;" onclick="increaseLineheight()"><i class="fas fa-plus"></i></span></center>
            <select id="selfont" value="arial" style="border-radius: 0;background: white;width: 100%;">
                <option value='Georgia, Times, "Times New Roman", serif'>Georgia</option>
                <option value='"Helvetica Neue", Helvetica, Arial, sans-serif'>Helvetica</option>
                <option value='"Open Sans"'>Open Sans</option>
                <option value='Verdana, Geneva, sans-serif'>Verdana</option>
                <option value='Roboto'>Roboto</option>
                <option value='Arial'>Arial</option>
                <option value='Tahoma'>Tahoma</option>
                <option value='linotype'>Palatino linotype</option>
                <option value='nunito'>Nunito</option>
            </select>
            <center style="font-size: 22px">
                <span  style="display: inline-block;border:1px solid gray;padding: 4px" onclick="decreasepadding()"><i class="fas fa-outdent"></i></span>
                <span val="left" style="display: inline-block;border:1px solid gray;padding: 4px" onclick="changealign(this)"><i class="fas fa-align-left"></i></span>
                <span val="center" style="display: inline-block;border:1px solid gray;padding: 4px" onclick="changealign(this)"><i class="fas fa-align-center"></i></span>
                <span val="justify" style="display: inline-block;border:1px solid gray;padding: 4px" onclick="changealign(this)"><i class="fas fa-align-justify"></i></span>
                <span val="right" style="display: inline-block;border:1px solid gray;padding: 4px" onclick="changealign(this)"><i class="fas fa-align-right"></i></span>
                <span style="display: inline-block;border:1px solid gray;padding: 4px" onclick="increasepadding()"><i class="fas fa-indent"></i></span>
            </center>
            <script>
                g("selfont").onchange=function(){
                    try{
                        if(!window.lockCtp)
                            g(contentcontainer).style.fontFamily = this.value;
                        localStorage.setItem("fontfamily", this.value);
                    }catch(e){}
                }
            </script>
            </center>
        </div>
    </div>
    <div class="bg-dark" style="display: flex;">
        <button type="button" onclick="speaker.readBook()" style="background: none;border:none;color: white;font-size: 16px;padding: 6px;"><i class="fas fa-play"></i> Nghe sách</button>
        <button type="button" onclick="showNS()" style="background: none;border:none;color: white;font-size: 16px;padding: 6px;"><i class="fas fa-edit"></i> Name</button>
    </div>
</div>
<button type="button" id="btnshowns" class='toolbar showname' style="border-radius: 50%;height: 50px; width: 50px;background: #eaeaea80;bottom:20px;border:none;box-shadow: 0 0 3px gray;padding: 0" onclick="showConfigBox()"><i class="fa-cogs fas" style="font-size: 26px;color: #6564638a;"></i></button>
<div id="float-btn" style="width: 50px;position: fixed;right: 14px;bottom: 85px;">
    <button type="button" id="btnunitymode" style="border-radius: 50%;height: 50px; width: 50px;background: #eaeaea80;border:none;box-shadow: 0 0 3px gray;padding: 0;opacity: 1;display: none;" onclick="ui.unity()"><i class="fas fa-eye" style="font-size: 26px;color: #6564638a;"></i></button>
</div>
