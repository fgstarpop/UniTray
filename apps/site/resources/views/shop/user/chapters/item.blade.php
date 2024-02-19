<div class="chapter">
    <div class="form-group mt-4">
        <label class="col-md-12">STT</label>
        <div class="col-md-12">
            <input type="number" class="form-control order" name="chapters[{INDEX}][order]" style="font-size: 14px;" value="{INDEX}"
                id="chapterOrder">
        </div>
    </div>
    <div class="form-group mt-4">
        <label class="col-md-12">Tên chương</label>
        <div class="col-md-12">
            <input type="text" class="form-control name" name="chapters[{INDEX}][name]" style="font-size: 14px;"
                id="chapterName">
        </div>
    </div>
    <div class="form-group mt-4">
        <label class="col-md-12">Nội dung chương</label>
        <div class="col-md-12" id="divAuto">
            <div class="backdrop">
                <div class="highlights"></div>
            </div>
            <textarea class="form-control wysiwyg content" rows="10" cols="12"
                    placeholder="Bạn đang thêm chương cho truyện [[ {{ $story->name }} ]]. Một lần tối đa đăng được 12k từ"
                    name="chapters[{INDEX}][content]"></textarea>
        </div>
    </div>
    @if(currentUser()->is_vip && $story->type == 1)
    <div class="form-group mt-4">
        <div class="form-check form-switch">
            <label class="col-md-12" for="text_china">Text Trung</label>
            <input class="form-check-input" name="chapters[{INDEX}][text_china]" type="checkbox" checked value="1">
        </div>
    </div>
    @endif
    <div class="form-group mt-4">
        <div class="form-check form-switch">
            <label class="col-md-12" for="timer_checkbox">Hẹn giờ</label>
            <input class="form-check-input" name="chapters[{INDEX}][timer_checkbox]" type="checkbox"
                id="timer_checkbox" value="1">
        </div>
        <div class="col-md-12" id="divAuto">
            <div class="backdrop">
                <div class="highlights"></div>
            </div>
            <input type="datetime-local" disabled class="form-control timer" name="chapters[{INDEX}][timer]" min="{{ date('Y-m-d\Th:i') }}" style="font-size: 14px;"
                id="chapterTimer">
        </div>
    </div>
    @if(currentUser()->is_vip && $story->type == 1)
        <div class="form-group mt-4">
            <label class="col-md-12">Đường dẫn tới chương (trang khác)</label>
            <div class="col-md-12">
                <input type="text" class="form-control" name="chapters[{INDEX}][link_other]" style="font-size: 14px;"
                    id="link_other">
            </div>
        </div>
        <div class="form-group mt-4">
            <div class="form-check form-switch">
                <input class="form-check-input" name="chapters[{INDEX}][is_vip]" type="checkbox"
                    id="is_vip" value="1">
                <label class="form-check-label" for="is_vip">Chương vip</label>
            </div>
        </div>
        <div class="form-group mt-4 d-none price">
            <label class="col-md-12">Giá</label>
            <div class="col-md-12">
                <input type="number" class="form-control"  name="chapters[{INDEX}][price]" style="font-size: 14px;"
                    id="price">
            </div>
        </div>
    @endif
    <hr class="my-4">
</div>
