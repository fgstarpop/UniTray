<div sechead>Thêm/sửa name trong kho</div>
<div class="roww">
	<input type="text" id="ip-chinese" placeholder="Tiếng Trung">
	<input type="text" id="ip-viet" placeholder="Name">
</div>
<div class="roww">
	<button class="w-100" onclick="addNameToStore()">Thêm/sửa</button>
</div>
<div sechead>Thêm/sửa hàng loạt [Max 100 dòng]</div>
<div class="roww">
	<textarea id="ip-bunch" placeholder="Txt"></textarea>
</div>
<div class="roww">
	<button class="w-100" onclick="addStoreBunch()">Thêm/sửa</button>
</div>
<script type="text/javascript">
	function addNameToStore() {
		var chi = g("ip-chinese").value;
		var vie = g("ip-viet").value;
		var txt = chi+"="+vie;
		modact("act=addstorebunch&txt="+encodeURIComponent(txt),function (down) {
			alert(down);
		});
	}
	function addStoreBunch() {
		var txt = g("ip-bunch").value;
		modact("act=addstorebunch&txt="+encodeURIComponent(txt),function (down) {
			alert(down);
		});
	}
</script>