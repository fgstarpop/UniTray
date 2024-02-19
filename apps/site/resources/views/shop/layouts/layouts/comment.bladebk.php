<div class="row" id="comment"
     style="background-color:white;overflow:hidden;padding:14px;font-size:24px; min-height: 224px; color:black">
    <div>Bình luận
    </div>
    <hr>
    @if(!empty(currentUser()->id))
    <form class="form-inline m-0">
        <div class=" input-group">
            <textarea class="form-control text" style="font-size:14px" placeholder="Viết bình luận ..."></textarea>
            <div class="input-group-append">
                <button type="button" class="btn btn-gt text-white" onclick="comment({{ currentUser()->id}},0,$('.text').val())" style="height:100%;font-size:16px;">Gửi</button>
            </div>
        </div>
    </form>
    @endif
    <div style="font-size:16px;line-height:1.2;width:100%;margin-top: 16px;" id="list-comment">
        @if($comment->count() > 0)
            @foreach($comment as $list)
            <div class="d-flex comment list{{$list->id}} val{{$list->id}}">
            <img src="{{ $list->users->avatar ? pare_url_file($list->users->avatar,'user') : asset('uploads/user/no-user.png') }}" class="comment-avatar">
                <div class="sec val">
                    <div class="sec-top bg-light p-3">{!! $list->body !!}</div>
                    <div class="sec-bot">
                        <a style="color:#535353" href="{{ route('user.index',$list->users->id) }}">{{ $list->users->name }}</a> -
                        <span class="time">{{ $list->created_at->diffForHumans() }} </span> -
                        @if(!empty(currentUser()->id))
                            <span class="response" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target=".login{{ $list->id }}"><b onclick="response('{{ $list->users->name }}')">Trả lời</b></span>
                            @php
                                $storyCheck = isset($story) ? $story->mod_id : null;
                                $userCheck = isset($users) ? $users->id : null;
                            @endphp
                            @if(currentUser()->id == $list->user_id || $author == currentUser()->id || currentUser()->mod_id == $storyCheck || currentUser()->id == $userCheck)
                                <span class="response text-danger" style="cursor: pointer;" ><b onclick="deleteComment('{{ $list->id }}',0, '{{ $storyCheck }}', '{{ $userCheck }}')"> - Xóa</b></span>
                            @endif
                            <div class="modal fade login{{ $list->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="font-size: 12px;">
                                <div class="modal-dialog modal-md modal-dialog-centered" onclick="event.stopPropagation()">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h6 class="modal-title">Trả lời bình luận</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body"><textarea class="repcmtta cmt{{$list->id}}" style="min-height:160px;width:100%;font-size:16px;" placeholder="Nhập nội dung..."></textarea></div>
                                        <div class="modal-footer">
                                            <button onclick="comment({{ currentUser()->id}},{{ $list->id }},$('.cmt{{$list->id}}').val())" data-bs-dismiss="modal" aria-label="Close" class="btn btn-primary">Gửi bình luận
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    @if(optional($list->children)->count() > 0)
                        @foreach($list->children as $val)
                            <div class="d-flex val{{$val->id}} childVal{{$list->id}}">
                                <img
                                src="{{ $val->users->avatar ? pare_url_file($val->users->avatar,'user') : asset('uploads/user/no-user.png') }}"
                                     class="comment-avatar">
                                <div class="sec" style="flex:1;margin-left:6px;">
                                    <div class="sec-top bg-light p-3">{!! $val->body !!}</div>
                                    <div class="sec-bot">
                                        <div class="ilb t-14 pv-0" style="padding:0 4px" cmtid="983532">
                                            <a style="color:#535353" href="{{ route('user.index',$val->users->id) }}">{{ $val->users->name }}</a> -
                                            <span class="timeelap t-12 t-gray">{{ $val->created_at->diffForHumans() }} </span> -
                                            <span class="response" style="cursor: pointer;" data-bs-toggle="modal"  data-bs-target=".login{{ $list->id }}">
                                                <b onclick="response('{{ $list->users->name }}')">Trả lời</b>
                                            </span>
                                            @if(currentUser()  && currentUser()->id == $val->user_id || currentUser() || currentUser()->mod_id == $storyCheck || currentUser()->id == $userCheck)
                                                <span class="response text-danger" style="cursor: pointer;" ><b onclick="deleteComment('{{ $val->id }}','{{$list->id}}', '{{ $storyCheck }}', '{{ $userCheck }}')"> - Xóa</b></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        @endforeach
        @endif
            <!-- <div class="text-center">
                <button style="width:100%" class="btn btn-gt">Xem thêm</button>
            </div> -->
    </div>
    <br>
</div>

<script>
    function deleteComment(id,parent, storyCheck, userCheck){
        Swal.fire({
            title: 'Bạn muốn xóa bình luận này ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Tất nhiên rồi!',
            cancelButtonText: 'Chưa phải lúc này!'
        }).then((result) => {
            if (result.isConfirmed) {
                var url = "{{ route('comment.delete') }}";
                $.ajax({
                    url: url,
                    type: "DELETE",
                    data: {
                        id: id,
                        storyCheck: storyCheck,
                        userCheck: userCheck,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (res) {
                        toastr.success(res.message, 'Thành Công');
                        $('.val' + id).remove();
                        $('.childVal' + id).remove();
                    }
                })
            }
        });
    }
    function response(name) {
        $('.repcmtta').val('@' + name + ' ');
    }

    function comment(user, parent, text) {
        if(text==''){
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: 'Bạn chưa nhập bình luận',
            })
            return;
        }
        var url = "{{ route('comment.create') }}";
        var type = "{{ $type }}";
        var id = "{{ $id }}";
        $.ajax({
            url: url,
            type: "POST",
            data: {text: text, type: type, id: id, parent: parent, user: user},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                var comment = res.comment;
                if (res.status == 300) {
                    $('.error').text(res.message);
                    $("button").attr("disabled", false);
                } else {
                    if (parent == 0) {
                        $('.text').val('');
                        $('#list-comment').prepend(`
                        <div class="d-flex comment list${comment.id} val${comment.id}">
                            <img src="uploads/user/${res.user.avatar}" class="comment-avatar">
                            <div class="sec val p-2">
                                <div class="sec-top bg-light p-3">${comment.body}</div>
                                <div class="sec-bot">
                                    <a style="color:#535353" href="/user/${res.user.id}">${res.user.name}</a> -
                                    <span class="time">${comment.time} </span> -
                                    <span class="response" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target=".login${comment.id}"><b onclick="response('${res.user.name}')">Trả lời</b></span>
                                    <span class="response text-danger" style="cursor: pointer;" ><b onclick="deleteComment('${comment.id}',0)"> - Xóa</b></span>
                                    <div class="modal fade login${comment.id}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="font-size: 12px;">
                                        <div class="modal-dialog modal-md modal-dialog-centered" onclick="event.stopPropagation()">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h6 class="modal-title">Trả lời bình luận</h6>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                <textarea class="repcmtta cmt${comment.id}"
                                                      style="min-height:160px;width:100%;font-size:16px;"
                                                      placeholder="Nhập nội dung."></textarea></div>
                                                <div class="modal-footer">
                                                    <button onclick="comment(${res.user.id},${comment.id},$('.cmt${comment.id}').val())" data-bs-dismiss="modal" aria-label="Close" class="btn btn-primary">Gửi bình luận
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        `);
                    } else {
                         $('.list' + parent + ' .val').append(`
                        <div class="d-flex val${comment.id} childVal${parent}" style="">
                        <img src="uploads/user/${res.user.avatar}" class="comment-avatar">
                        <div class="sec" style="flex:1;margin-left:6px;">
                            <div class="sec-top bg-light p-3">${comment.body}</div>
                                <div class="sec-bot">
                                    <div class="ilb t-14 pv-0" style="padding:0 4px" cmtid="983532">
                                        <a style="color:#535353" href="/user/${res.user.id}">${res.user.name}</a> -
                                        <span class="timeelap t-12 t-gray">${comment.time} </span> -
                                        <span class="response" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target=".login${comment.id}"><b class="res" data-response="${res.user.name}">Trả lời</b></span>
                                        <span class="response text-danger" style="cursor: pointer;" ><b onclick="deleteComment('${comment.id}',${parent})"> - Xóa</b></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade login${comment.id}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="font-size: 12px;">
                                <div class="modal-dialog modal-md modal-dialog-centered" onclick="event.stopPropagation()">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h6 class="modal-title">Trả lời bình luận</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body"><textarea class="repcmtta cmt${comment.id}" style="min-height:160px;width:100%;font-size:16px;" placeholder="Nhập nội dung..."></textarea></div>
                                        <div class="modal-footer">
                                            <button onclick="comment(${res.user.id},${parent},$('.cmt${comment.id}').val())" data-bs-dismiss="modal" aria-label="Close" class="btn btn-primary">Gửi bình luận
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `);
                    }
                }
            }
        })
    }
</script>
