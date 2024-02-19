<style>
    .window {
        font-family: sans-serif;
        font-weight: 500;
        color: #3e3e3e;
        position: fixed;
        z-index: 999;
        width: 100vw;
        left: 0;
        top: 0;
        height: 100vh;
        background: #00000030;
        overflow-y: scroll;
    }

    .window .body {
        min-height: 160px;
        max-height: calc(100vh - 50px);
        overflow-y: auto;
    }

    .btn-grid-3 {
        display: flex;
        padding: 0;
    }

    .window .closer {
        float: right;
        margin: -8px -8px 0 0;
        padding: 10px 14px 10px 16px;
        font-size: 20px;
        color: #999;
        transition: color .3s, background .3s;
        cursor: default;
    }

    .window .head {
        height: 40px;
        overflow: hidden;
        background: #ededed;
        padding: 8px;
        -padding-bottom: 2px;
    }

    .window .minimize, .window .fuller, .window .gotolink {
        float: right;
        font-size: 20px;
        margin: -8px 0;
        padding: 10px 14px 10px 16px;
        color: #999;
        transition: color .3s, background .3s;
        cursor: default;
    }
</style>
<div id="namewdf"  onkeydown="event.stopPropagation();" style="overflow-y: scroll;" class="namewd">
    <div id="toolbar">

        <button type="button" class="toolbar" onclick="saveNS()">Lưu lại</button>
        <button type="button" class="toolbar" onclick="saveNS();excute();">Chạy</button>
        <button type="button" class="toolbar" onclick="clearNS()">Xóa hết</button>
        <button type="button" class="toolbar" onclick="getNSOnline()">Tải name</button>
        <button type="button" class="toolbar" onclick="uploadNS()">Upload</button>
        <button type="button" class="toolbar" style="float:right" onclick="hideNS()">Ẩn</button>

    </div>
    <div id="toolbar2" style="display:none;">
        <button type="button" class="toolbar" onclick="this.parentElement.style.display='none';g('toolbar').style.display='block';g('dlnametb').style.zIndex='-1';"><span class="glyphicon glyphicon-chevron-left"></span>Quay lại</button>
        <button type="button" class="toolbar" style="float:right" onclick="hideNS()">Ẩn</button>
    </div>
    <hr style="margin:0;clear:both">
    <div id="namewdc" style="position:relative">
        <textarea id="namewd"></textarea>
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
        <input id="fastseltext" onpaste="fastCreateN(this)" onkeyup="fastCreateN(this)" style="float:left;width:40%;height:24px;font-size:16px;" type="text"><span style="float:left;">&nbsp;=&nbsp;</span><input style="float:left;width:40%;height:24px;font-size:16px;" id="fastgentext">
        <button type="button" class="toolbar" style="float:right;margin: 0;padding: 0;" onclick="fastAddNS()">Thêm </button>
        <div class="clear"></div>
    </div>
    <div style="padding: 4px;height: 220px;">
        <button type="button" onclick="excute()" class="icb">
            <img loading="lazy" src="/asset/runname.png"><br>Chạy</button>
        <button type="button" onclick="getNSOnline()" class="icb">
            <img loading="lazy" src="/asset/downname.png"><br>Tải name được chia sẻ</button>
        <button type="button" onclick="importName()" class="icb">
            <img loading="lazy" src="/asset/inputname.png"><br>Nhập dữ liệu name</button>
        <button type="button" onclick="openinsertvpmodal()" class="icb">
            <img loading="lazy" src="/asset/inputname.png"><br>Sử dụng file vp riêng</button>
        <button type="button" onclick="exportName()" class="icb">
            <img loading="lazy" src="/asset/outputname.png"><br>Xuất dữ liệu</button>
        <button type="button" onclick="uploadNS()" class="icb">
            <img loading="lazy" src="/asset/sharename.png"><br>Chia sẽ gói name</button>
        <button type="button" onclick="var wd=ui.win.createBox('Gói Name Đặc Biệt','namepack').show();" onclicks="$('#customnamebox').modal('toggle')" class="icb">
            <img loading="lazy" src="/asset/downname.png"><br>Tải name đặc biệt</button>
        <button type="button" onclick="analyzer.reset();" class="icb">
            <img loading="lazy" src="/asset/delanalyzermem.png"><br>Xóa bộ nhớ Analyzer</button>
        <button type="button" onclick="ui.win.createBox('Công cụ','tool').show()" class="icb">
            <img loading="lazy" src=""><br>Công cụ</button>
    </div>

</div>
<div id="configBox" class="bg-light" style="z-index:10; position: fixed; bottom: 75px; right: 65px; border: 1px solid rgb(204, 203, 203); display: none;">
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
                <span style="padding:6px;border-radius:6px;" onclick="decreaseFontsize()"><i class="fas fa-minus"></i> </span><span style="margin-right: -20px;position: relative;"><img loading="lazy" src="/asset/Aa.png" style="width: 18px;
    height: 20px;"></span><input id="changefs" style="max-width:45px;padding: 3px;padding-left:20px;" onchange="changefontsize(this);" value="24"><span style="padding:6px;border-radius:6px;" onclick="increaseFontsize()"><i class="fas fa-plus"></i></span>
                <span style="padding:6px;border-radius:6px;" onclick="decreaseLineheight()"><i class="fas fa-minus"></i> </span><span style="margin-right: -15px;position: relative"><img loading="lazy" src="/asset/lh.png" style="    width: 12px;
    height: 28px;"></span><input id="changefs2" style="max-width:45px;padding: 3px;padding-left: 15px;" onchange="changelineheight(this);" value="1.2"><span style="padding:6px;border-radius:6px;" onclick="increaseLineheight()"><i class="fas fa-plus"></i></span></center>
            <select id="selfont" value="arial" style="border-radius: 0;background: white;width: 100%;">
                <option value="Georgia, Times, &quot;Times New Roman&quot;, serif">Georgia</option>
                <option value="&quot;Helvetica Neue&quot;, Helvetica, Arial, sans-serif">Helvetica</option>
                <option value="&quot;Open Sans&quot;">Open Sans</option>
                <option value="Verdana, Geneva, sans-serif">Verdana</option>
                <option value="Roboto">Roboto</option>
                <option value="Arial">Arial</option>
                <option value="Tahoma">Tahoma</option>
                <option value="linotype">Palatino linotype</option>
                <option value="nunito">Nunito</option>
            </select>
            <center style="font-size: 22px">
                <span style="display: inline-block;border:1px solid gray;padding: 4px" onclick="decreasepadding()"><i class="fas fa-outdent"></i></span>
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

        </div>
    </div>
    <div class="bg-dark" style="display: flex;">
        <button type="button" onclick="speaker.readBook()" style="background: none;border:none;color: white;font-size: 16px;padding: 6px;"><i class="fas fa-play"></i> Nghe sách</button>
        <button type="button" onclick="showNS()" style="background: none;border:none;color: white;font-size: 16px;padding: 6px;"><i class="fas fa-edit"></i> Name</button>
    </div>
</div>
<div class="up">
    <button onclick="showConfigBox()"><i class="fa fa-2x fa-cogs"></i></button>
</div>
<div id="boxfather">
    <div id="nsbox" class="bg-info" contenteditable="false"
         style="display: none; font-size: 16px; font-family: Arial; left: 449.312px; top: 674px;"
         onkeydown="event.stopPropagation();" onclick="event.stopPropagation();">
        <style type="text/css">
            button {
                padding: 0;
            }
        </style>
        <div class="row" style="flex-wrap: nowrap;">
            <button type="button" style="position: absolute;left: auto;top:0;right: 100%;width: 20%;height: 40px;"
                    class="btn btn-danger col" onclick="hideNb(this);"><i class="fas fa-times-circle"></i></button>
            <button type="button" class="btn btn-info" onclick="expandLeft(this);"><i
                    class="fas fa-chevron-circle-left"></i>Mở rộng
            </button>
            <button type="button" class="btn btn-info" onclick="expandRight(this);">Mở rộng<i
                    class="fas fa-chevron-circle-right"></i></button>
            <button type="button" class="btn btn-danger col" onclick="hideNb(this);"><i class="fas fa-times-circle"></i>
            </button>
        </div>
        <div class="row" style="display: none;">
            <span
                style="display:inline-block;width:30px;color:white;font-size:12px;padding:6px;background:green;">VP</span>
            <input class="col" style="padding:0;font-size: 12px;" id="vuc" placeholder=" Vietphrase viết hoa">
            <button class="btn btn-info" onclick="applyNs('vuc')" type="button"><i class="far fa-check-circle"></i>
            </button>
            <button type="button" class="btn btn-info" onclick="applyAndSaveNs('vuc')"><i class="far fa-save"></i>
            </button>
        </div>
        <div class="row">
            <span
                style="display:inline-block;width:30px;color:white;font-size:12px;padding:6px;background:green;">hv</span>
            <input class="col" style="padding:0;font-size: 12px;" id="hv" placeholder=" Hán Việt">
            <button onclick="applyNs('hv')" class="btn btn-info" type="button"><i class="far fa-check-circle"></i>
            </button>
            <button type="button" class="btn btn-info" onclick="applyAndSaveNs('hv')"><i class="far fa-save"></i>
            </button>
        </div>
        <div class="row">
            <span
                style="display:inline-block;width:30px;color:white;font-size:12px;padding:6px;background:green;">HV</span>
            <input class="col" style="padding:0;font-size: 12px;" id="huc" placeholder=" Hán Việt viết hoa">
            <button onclick="applyNs('huc')" class="btn btn-info" type="button"><i class="far fa-check-circle"></i>
            </button>
            <button type="button" class="btn btn-info" onclick="applyAndSaveNs('huc')"><i class="far fa-save"></i>
            </button>
        </div>
        <div class="row">
            <span
                style="display:inline-block;width:30px;color:white;font-size:12px;padding:6px;background:green;">zw</span>
            <input class="col" style="padding:0;font-size: 12px;" onkeyup="instrans(this)" id="zw"
                   placeholder="Tiếng Trung">
            <button onclick="googletrans('zw')" class="btn btn-info" type="button"><i class="fas fa-language"></i>
            </button>
            <button type="button" class="btn btn-info" onclick="copychinese()"><i class="far fa-copy"></i></button>
            <button onclick="googlesearch('zw')" class="btn btn-info" type="button"><i class="fas fa-search"></i>
            </button>
            <script>

            </script>
        </div>
        <div class="row">
            <span
                style="display:inline-block;width:30px;color:white;font-size:12px;padding:6px;background:green;">Tr</span>
            <input class="col" style="padding:0;font-size: 12px;" id="instrans" placeholder="Dịch trực tiếp">
            <button onclick="openmodvp()" style="font-size: 12px;width: 82px;" class="btn btn-info" type="button"><i
                    class="far fa-edit"></i> Sửa
            </button>
        </div>
        <div class="row">
            <span
                style="display:inline-block;width:30px;color:white;font-size:12px;padding:6px;background:green;">tc</span>
            <input class="col" style="padding:0;font-size: 12px;" id="op" placeholder=" cụm từ tùy chọn">
            <button onclick="applyNs('op')" class="btn btn-info" type="button"><i class="far fa-check-circle"></i>
            </button>
            <button class="btn btn-info" type="button" onclick="applyAndSaveNs('op')"><i class="far fa-save"></i>
            </button>
        </div>
        <div class="row" style="flex-wrap: nowrap;">
            <button onclick="showNS()" style="padding: 4px;" class="btn btn-info" type="button"><i
                    class="fas fa-cog"></i> Quản lý
            </button>
            <button class="btn btn-info" style="padding: 12px;" type="button" onclick="excute()"><i
                    class="fas fa-play"></i></button>
            <button class="btn btn-info" style="padding: 4px;" type="button" onclick="showAddName()" data-toggle="modal"
                    data-target="#addnamebox"><i class="fas fa-plus"></i> Thêm name
            </button>

        </div>

    </div>
    <div class="modal" style="font-size: 12px; display: none;" id="addnamebox" onkeydown="event.stopPropagation()"
         onclick="$(this).fadeOut();">
        <style type="text/css" style="top: 10px; bottom: auto;">
            .modal-body button {
                padding: 4px;
            }

            .select-editable {
                clear: both;
                position: relative;
                background-color: white;
                border: solid grey 1px;
                width: 100%;
                height: 27px;
            }

            .select-editable select {
                position: absolute;
                top: 0px;
                left: 0px;
                font-size: 17px;
                border: none;
                width: 100%;
                margin: 0;
                background: #f5f5f5;
            }

            .select-editable input {
                position: absolute;
                top: 0px;
                left: 0px;
                width: 90%;
                padding: 1px;
                font-size: 15px;
                border: none;
            }

            .select-editable select:focus, .select-editable input:focus {
                outline: none;
            }</style>
        <div class="`modal-dialog modal-sm modal-dialog-centered`"
             style="position: fixed;bottom: 10;left: 50%; transform: translate(-50%,0);width: 90%;max-width: 360px;"
             onclick="event.stopPropagation();">
            <div class="modal-content">
                <script type="text/javascript">
                    if (!isMobile) {
                        var w = g("addnamebox").children[0];
                        w.style.top = '10';
                        w.style.bottom = 'auto';
                    }

                </script>
                <div class="modal-header">
                    <h6 class="modal-title">Thêm name/Chỉnh name</h6>
                    <button type="button" class="close" data-dismiss="modal" onclick="$('#addnamebox').hide();">×
                    </button>
                </div>
                <div class="modal-body">
                    Tiếng Trung:<br>
                    <input type="text" style="width: 100%" id="addnameboxip1" onkeyup="instrans2(this,false);"
                           placeholder=" Tiếng trung">
                    <br>
                    Hán Việt:<br>
                    <input type="text" style="width: 100%" id="addnameboxip3" placeholder=" Hán việt">
                    <div style="display: flex;">
                        <button onclick="addSuperName('hv','z')">Dùng</button>
                        <button onclick="addSuperName('hv','f')">Hoa chữ đầu</button>
                        <button onclick="addSuperName('hv','s')">Hoa 2 chữ đầu</button>
                        <button onclick="addSuperName('hv','l')">Thường Chữ cuối</button>
                        <button onclick="addSuperName('hv','a')">Hoa Toàn Bộ</button>
                    </div>
                    Tiếng Anh:<br>
                    <input type="text" style="width: 100%" id="addnameboxip4" placeholder=" Tiếng Anh"><br>
                    <button style="float: right;" onclick="addSuperName('el')">Dùng</button>
                    <br>
                    Vietphrase:
                    <div class="select-editable">
                        <select onchange="this.nextElementSibling.value=this.value">
                            <option value="ngọc hướng về phía">ngọc hướng về phía</option>
                        </select>
                        <input type="text" id="addnameboxip2" placeholder=" Vietphrase v.v.." name="format" value="">
                    </div>
                    <button style="float: right;" onclick="addSuperName('vp')">Dùng</button>
                    Name trong kho:<br>
                    <div class="select-editable">
                        <select onchange="this.nextElementSibling.value=this.value">
                            <option value=""></option>
                        </select>
                        <input type="text" id="addnameboxip5" placeholder=" Name trong kho v.v.." name="format"
                               value="">
                    </div>
                    <button onclick="deleteName()">Xóa name</button>
                    <button style="float: right;" onclick="addSuperName('kn')">Dùng</button>
                </div>
                <div id="booknamemanager" style="padding: 0 15px;" hidden="" class="bg-light">
                    <span class="btn" onclick="this.nextElementSibling.click()">Lưu vào truyện </span>
                    <input type="checkbox" onchange="store.setItem('issavetobook',this.checked?'true':'false')"
                           id="issavetobook"
                           style="float: right;transform: scale(2);-webkit-appearance: checkbox;margin: 12px 8px 0 0;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" onclick="addSuperName('el')">Thêm Name Anh</button>
                    <button type="button" class="btn btn-info" onclick="addSuperName('hv','a')">Thêm Hán Việt</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" style="font-size: 12px; display: none;" id="modifyvpbox" onkeydown="event.stopPropagation()"
         onclick="$(this).fadeOut();">
        <div class="modal-dialog modal-sm modal-dialog-centered"
             style="position: fixed; bottom: auto; left: 50%; transform: translate(-50%, 0px); width: 90%; max-width: 360px; top: 10px;"
             onclick="event.stopPropagation();">
            <div class="modal-content">
                <script type="text/javascript">
                    if (!isMobile) {
                        var w = g("modifyvpbox").children[0];
                        w.style.top = '10';
                        w.style.bottom = 'auto';
                    }

                </script>
                <div class="modal-header">
                    <h6 class="modal-title">Chỉnh Vietphrase</h6>
                    <button type="button" class="close" data-dismiss="modal" onclick="$('#modifyvpbox').hide();">×
                    </button>
                </div>
                <div class="modal-body">
                    Tiếng Trung:<br>
                    <input type="text" style="width: 100%" id="modifyvpboxip1" onkeyup="instrans3(this);"
                           placeholder=" Tiếng trung">
                    <br>
                    Hán Việt:<br>
                    <input type="text" style="width: 100%" id="modifyvpboxip2" disabled="" placeholder=" Hán việt">
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
<script src="{{ asset('common/js/stv.ui.js') }}"></script>
<script type="text/javascript">
    window.onerror=function(msg, url, lineNo, columnNo, error){
    }
</script>
<script src="{{ asset('common/js/hanviet.js') }}"></script>
<script src="{{ asset('common/js/qtOnline.js') }}"></script>
<script src="{{ asset('common/js/name.js') }}"></script>
