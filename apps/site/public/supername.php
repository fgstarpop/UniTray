<div sechead>Thêm/sửa name siêu cấp</div>
<div class="roww">
	<input type="text" id="ip-chinese" placeholder="Tiếng Trung">
	<input type="text" id="ip-viet" placeholder="Name">
</div>
<div class="roww">
	<button class="w-100" onclick="addSuperName()">Thêm/sửa</button>
</div>
<div sechead hidden="">Thêm/sửa hàng loạt</div>
<div class="roww" hidden="">
	<textarea id="ip-bunch" placeholder="Txt"></textarea>
</div>
<div class="roww" hidden="">
	<button class="w-100" onclick="addSuperNameBunch()">Thêm/sửa</button>
</div>
<script type="text/javascript">
	function addSuperName() {
		var chi = g("ip-chinese").value;
		var vie = g("ip-viet").value;
		modact("act=addsupername&chi="+encodeURIComponent(chi)+"&vie="+encodeURIComponent(vie),function (down) {
			alert(down);
		});
	}
</script>
<div class="roww" style="border-bottom: 1px solid gray">Name đã có
	<div class="padder"></div>
	<button right onclick="toggleNamebox()">+/-</button>
</div>
<div id="namebox"></div>