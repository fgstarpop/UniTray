<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <title>XOR Tool</title>
    <!-- Thêm Bootstrap CSS từ CDN -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h1>XOR Tool</h1>
        <!-- Nút để mở modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">Mở tools Xor</button>
        <!-- Modal -->
        <div class="modal fade" id="myModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">XOR Tool</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <!-- Form -->
                        @if (isset($result))
                        <div class="alert alert-info mt-3">
                            Kết quả XOR: <span id="resultText">{{ $result }}</span>
                            <button class="btn btn-sm btn-info" id="copyButton">Copy</button>
                        </div>
                    @endif

                        <form method="post" action="{{ route('test.xor') }}">
                            @csrf
                            <div class="form-group">
                                <label for="input_text">Nhập số:</label>
                                <input type="text" class="form-control" id="input_text" name="input_text"
                                    value="@if(isset($result)){{$input_text}}@endif">
                            </div>
                            <button type="submit" class="btn btn-primary" name="calculate">Tính XOR</button>
                        </form>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">

                        <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thêm Bootstrap JavaScript và jQuery từ CDN (đặt trước </body>) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Sử dụng jQuery để Sao chép Kết quả XOR -->
    <script>
        $(document).ready(function() {
            $("#myModal").modal("show");
            $("#copyButton").click(function() {
                // Lấy nội dung của phần tử có ID là "resultText"
                var resultText = $("#resultText").text();

                // Tạo một phần tử textarea tạm thời để sao chép văn bản
                var tempTextarea = $("<textarea>");
                $("body").append(tempTextarea);
                tempTextarea.val(resultText).select();

                try {
                    // Thử sao chép văn bản vào clipboard
                    document.execCommand("copy");
                    alert("Đã sao chép kết quả XOR thành công!");
                } catch (e) {
                    console.error("Sao chép không thành công: " + e);
                } finally {
                    // Xóa phần tử textarea tạm thời
                    tempTextarea.remove();
                }
            });
        });
    </script>
</body>

</html>
