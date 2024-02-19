<div class="btn-grid-3">
	<a href="javascript:supernamewindow()">Name Siêu Cấp</a>
	<a href="javascript:khonamewindow()">Kho Name</a>
	<a href="javascript:namepackwindow()">Gói Name</a>
</div>
<div class="btn-grid-3" hidden>
	<a href="javascript:deletethischapter()">Xóa txt chương này</a>
	<a href="javascript:gotox()">Rescan</a>
	<a href="javascript:" hidden>Tìm nguồn khác</a>
</div>
<div class="btn-grid-3">
	<a href="javascript:opensettingwindow()">Cài đặt</a>
	<a href="javascript:openitemwindow()">Túi đồ</a>
</div>
<script type="text/javascript">
	function supernamewindow() {
		var wd=ui.win.createBox("Name Siêu Cấp","supername");
		wd.minimizable();
		wd.show();
	}
	function khonamewindow() {
		var wd=ui.win.createBox("Kho Name","namestorage");
		wd.minimizable();
		wd.show();
	}
	function namepackwindow() {
		var wd=ui.win.createBox("Gói Name Đặc Biệt","namepack");
		wd.show();
	}
	function opensettingwindow() {
		var wd=ui.win.createFrame("Cài đặt hệ thống","/frame/?p=setting");
		//ui.win.fullscreen(wd);
		wd.show();
	}
	function openitemwindow() {
		var wd=ui.win.createFrame("Túi đồ","/frame/?p=user");
		ui.win.fullscreen(wd);
		wd.show();
	}
</script>