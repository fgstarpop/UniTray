<div class="row" id="comment"
     style="background-color:white;overflow:hidden;padding:14px;font-size:24px; min-height: 224px; color:black; padding-top: 0px;">
     <div>
        <div style="display:inline-block; font-size: 16px; font-weight: bold;">Bình luận
        </div>
        <div style="display:inline-block; font-size: 14px; color:gray;" > có {{ $comment->count() }} bình luận </div>
    </div>
    @if((new \Jenssegers\Agent\Agent())->isDesktop())
    <hr>
    @endif
    @if(!empty(currentUser()->id))
    <form class="form-inline m-0">
        <div class=" input-group">
            <textarea class="form-control text comment-mobile-textarea" style="font-size:14px" placeholder="Viết bình luận ..."></textarea>
            <div class="input-group-append">
                <button type="button" class="btn btn-gt btn-gt-mobile text-white" onclick="comment({{ currentUser()->id}},0 ,0,$('.text').val())" style="height:100%;font-size:16px;">Gửi<i style="display: none;" class="fa fa-sign-in send-cmt-mobile"></i></button>
            </div>
        </div>
    </form>
    @endif
    <div style="font-size:16px;line-height:1.2;width:100%;margin-top: 16px;border-top: dashed 2px #ebe7e7;padding-top:15px;" id="list-comment">
        @if($comment->count() > 0)
            @foreach($comment as $list)
                @if ($list->parent_id == 0)
                    <div class="d-flex comment list{{$list->id}} val{{$list->id}} comment-box-mobile">
                        <div class="img-cmt-block d-flex flex-column avt-box-mobile">
                            <img src="{{ $list->users->avatar ? pare_url_file($list->users->avatar,'user') : asset('uploads/user/no-user.png') }}"
                                 class="comment-avatar @if($list->users->user_vip == 1) border-vip @endif">
                            @if ($list->users->is_vip == 1)
                                <img style="width: 50px;" class="mt-1 tag-mod" src="{{ asset('frontend/images/mod.png') }}" alt="">
                            @elseif ($list->users->user_vip == 1)
                                <img style="width: 50px;" class="mt-1 tag-vip" src="{{ asset('frontend/images/user_vip.png') }}" alt="">
                            @endif
                        </div>
                        <div class="sec val">
                            <div class="sec-title" style="display: none;">
                            <a style="color:#535353; @if($list->users->user_vip == 1) padding-right: 25px @endif"
                                class="@if($list->users->user_vip == 1) text-danger font-weight-bold @endif position-relative"
                                href="{{ route('user.index',$list->users->id) }}" >
                                 {{ $list->users->name }}
                                 @if($list->users->user_vip == 1)
                                     <div class="circle bg-success d-inline-block m-0 comment-vip-icon position-absolute"
                                          style="right: 5px; top: 50%; transform: translateY(-50%)">
                                         <div class="checkmark"></div>
                                     </div>
                                 @endif
                             </a>
                            @if ($list->users->is_vip == 1)
                             <img style="width: 50px;" class="mt-1 tag-mod" src="{{ asset('frontend/images/mod.png') }}" alt="">
                             @endif
                            @if ($list->users->user_vip == 1)
                             <img style="width: 50px;" class="mt-1 tag-vip" src="{{ asset('frontend/images/user_vip.png') }}" alt="">
                            @endif
                            </div>
                            <div class="sec-top @if($list->users->user_vip == 1) bg-vip border-vip text-success font-weight-bold @else bg-light @endif p-2 indent-text-comment">"{!! $list->body !!}"</div>
                            <div class="sec-bot">
                                <a style="color:#535353; @if($list->users->user_vip == 1) padding-right: 25px @endif"
                                   class="@if($list->users->user_vip == 1) text-danger font-weight-bold @endif position-relative"
                                   href="{{ route('user.index',$list->users->id) }}" >
                                    {{ $list->users->name }}
                                    @if($list->users->user_vip == 1)
                                        <div class="circle bg-success d-inline-block m-0 comment-vip-icon position-absolute"
                                             style="right: 5px; top: 50%; transform: translateY(-50%)">
                                            <div class="checkmark"></div>
                                        </div>
                                    @endif
                                </a> -
                                <span class="time">{{ $list->created_at->diffForHumans() }} </span> -
                                @if(!empty(currentUser()->id))
                                    <span class="response" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target=".login{{ $list->id }}"><b onclick="response('{{ $list->users->name }}')">Trả lời</b></span>
                                    @php
                                        $storyCheck = isset($story) ? $story->mod_id : null;
                                        $userCheck = isset($list->user_id) ? $list->user_id : null;
                                    @endphp
                                    @if(currentUser() && currentUser()->is_vip == 1)
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
                                                    <button onclick="comment({{ currentUser()->id}}, {{ $list->user_id }}, {{ $list->id }},$('.cmt{{$list->id}}').val())" data-bs-dismiss="modal" aria-label="Close" class="btn btn-primary">Gửi bình luận
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @if(optional($list->children)->count() > 0)
                            @foreach($list->children as $val)
                                <div class="d-flex val{{$val->id}} childVal{{$list->id}} child-cmt-desktop">
                                    <div class="img-cmt-block d-flex flex-column avt-box-mobile">
                                        <img src="{{ $val->users->avatar ? pare_url_file($val->users->avatar,'user') : asset('uploads/user/no-user.png') }}"
                                             class="comment-avatar @if($val->users->user_vip == 1) border-vip @endif">
                                        @if ($val->users->is_vip == 1)
                                            <img style="width: 50px;" class="mt-1" src="{{ asset('frontend/images/mod.png') }}" alt="">
                                        @elseif ($val->users->user_vip == 1)
                                            <img style="width: 50px;" class="mt-1" src="{{ asset('frontend/images/user_vip.png') }}" alt="">
                                        @endif
                                    </div>
                                    <div class="sec" style="flex:1;margin-left:6px;">
                                        <div class="sec-top @if($val->users->user_vip == 1) bg-vip border-vip text-success font-weight-bold @else bg-light @endif p-2 indent-text-comment">{!! $val->body !!}</div>
                                        <div class="sec-bot">
                                            <a style="color:#535353; @if($val->users->user_vip == 1) padding-right: 25px @endif"
                                               class="@if($val->users->user_vip == 1) text-danger font-weight-bold @endif position-relative pr-3"
                                               href="{{ route('user.index',$val->users->id) }}">{{ $val->users->name }}
                                                @if($val->users->user_vip == 1)
                                                    <div class="circle bg-success d-inline-block m-0 comment-vip-icon position-absolute"
                                                         style="right: 5px; top: 50%; transform: translateY(-50%)">
                                                        <div class="checkmark"></div>
                                                    </div>
                                                @endif
                                            </a> -
                                            <span class="timeelap t-12 t-gray">{{ $val->created_at->diffForHumans() }} </span> -
                                            <span class="response" style="cursor: pointer;" data-bs-toggle="modal"  data-bs-target=".login{{ $list->id }}">
                                            <b onclick="response('{{ $val->users->name }}')">Trả lời</b>
                                        </span>
                                            @if(Auth::check())
                                                @if((currentUser()  && currentUser()->id == $val->user_id) || ($author == currentUser()->id) || (currentUser() && currentUser()->is_vip == 1))
                                                    <span class="response text-danger" style="cursor: pointer;" ><b onclick="deleteComment('{{ $val->id }}','{{$val->id}}', '{{ $storyCheck }}', '{{ $val->user_id }}')"> - Xóa</b></span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                            <div class="sec-bot-mobile" style="display: none;">
                                <i class="fa fa-clock"></i> <span class="time">{{ $list->created_at }} </span>
                                @if(!empty(currentUser()->id))
                                <!-- <span class="response icon-under-cmt-mobile" style="cursor: pointer;" > <i class="fa fa-thumbs-up" aria-hidden="true"></i> 10</span> -->
                                @endif
                                @if(!empty(currentUser()->id))
                                    <span class="response icon-under-cmt-mobile" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target=".mobile-cmt{{ $list->id }}"><b onclick="response('{{ $list->users->name }}')"> <i class="fa fa-comment"> {{ $list->children->count(); }}</i></b></span>
                                    @php
                                        $storyCheck = isset($story) ? $story->mod_id : null;
                                        $userCheck = isset($list->user_id) ? $list->user_id : null;
                                    @endphp
                                 <div class="modal fade mobile-cmt{{ $list->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="font-size: 12px;">
                                    <div class="modal-dialog modal-md modal-dialog-centered" onclick="event.stopPropagation()">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h6 class="modal-title" style="margin-left: 127px;">Trả lời bình luận</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body body-child-comment-mobile" style="padding-top: 23px;
                                            background: #bfbfcb29;">
                                                @if(optional($list->children)->count() > 0)
                                                @foreach($list->children as $val)
                                                    <div class="d-flex val{{$val->id}} childVal{{$list->id}} child-cmt-flex">
                                                        <div class="img-cmt-block d-flex flex-column avt-box-mobile">
                                                            <img src="{{ $val->users->avatar ? pare_url_file($val->users->avatar,'user') : asset('uploads/user/no-user.png') }}"
                                                                 class="comment-avatar @if($val->users->user_vip == 1) border-vip @endif">
                                                            @if ($val->users->is_vip == 1)
                                                                <img style="width: 50px;" class="mt-1 tag-mod" src="{{ asset('frontend/images/mod.png') }}" alt="">
                                                            @elseif ($val->users->user_vip == 1)
                                                                <img style="width: 50px;" class="mt-1 tag-vip" src="{{ asset('frontend/images/user_vip.png') }}" alt="">
                                                            @endif
                                                        </div>
                                                        <div class="sec val" style="flex:1;margin-left:6px;">
                                                            <div class="sec-title">
                                                                <a style="color:#535353; @if($val->users->user_vip == 1) padding-right: 25px @endif"
                                                                    class="@if($val->users->user_vip == 1) text-danger font-weight-bold @endif position-relative"
                                                                    href="{{ route('user.index',$val->users->id) }}" >
                                                                     {{ $val->users->name }}
                                                                     @if($val->users->user_vip == 1)
                                                                         <div class="circle bg-success d-inline-block m-0 comment-vip-icon position-absolute"
                                                                              style="right: 5px; top: 50%; transform: translateY(-50%)">
                                                                             <div class="checkmark"></div>
                                                                         </div>
                                                                     @endif
                                                                 </a>
                                                                @if ($val->users->is_vip == 1)
                                                                 <img style="width: 50px;" class="mt-1 tag-mod" src="{{ asset('frontend/images/mod.png') }}" alt="">
                                                                 @endif
                                                                @if ($val->users->user_vip == 1)
                                                                 <img style="width: 50px;" class="mt-1 tag-vip" src="{{ asset('frontend/images/user_vip.png') }}" alt="">
                                                                @endif
                                                                </div>
                                                                <div class="sec-top @if($val->users->user_vip == 1) bg-vip border-vip text-success font-weight-bold @else bg-light @endif p-2 indent-text-comment">"{!! $val->body !!}"</div>
                                                            <div class="sec-bot-mobile">
                                                                <i class="fa fa-clock"></i> <span class="time">{{ $val->created_at }} </span>
                                                                @if(!empty(currentUser()->id))
                                                                <!-- <span class="response icon-under-cmt-mobile" style="cursor: pointer;" > <i class="fa fa-thumbs-up" aria-hidden="true"></i> 10</i></span> -->
                                                                @endif
                                                                 @if(currentUser() && currentUser()->is_vip == 1)
                                                                <span class="response text-danger icon-under-cmt-mobile" style="cursor: pointer;" ><b onclick="deleteComment('{{ $val->id }}','{{$val->id}}', '{{ $storyCheck }}', '{{ $val->user_id }}')"> <i class="fa fa-trash" aria-hidden="true"></i></b></span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                            </div>
                                            <div class="modal-footer">
                                                <div class=" input-group">
                                                    <textarea class="repcmtta cmt-moblie{{$list->id}} comment-mobile-textarea" style="min-height:20px;width:100%;font-size:16px;" placeholder="Nhập nội dung..."></textarea>
                                                    <div class="input-group-append">
                                                        <button type="button" data-bs-dismiss="modal" aria-label="Close" class="btn btn-gt-mobile text-white" onclick="comment({{ currentUser()->id}},{{ $list->user_id }}, {{ $list->id }},$('.cmt-moblie{{$list->id}}').val())" style="height:100%;font-size:16px;"><i class="fa fa-sign-in send-cmt-mobile"></i></button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                 @if(currentUser() && currentUser()->is_vip == 1)
                                <span class="response text-danger icon-under-cmt-mobile" style="cursor: pointer;" ><b onclick="deleteComment('{{ $list->id }}',0, '{{ $storyCheck }}', '{{ $userCheck }}')"> <i class="fa fa-trash" aria-hidden="true"></i></b></span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
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

    function comment(user, user_parent, parent, text) {
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
            data: {text: text, type: type, id: id, user_parent: user_parent, parent: parent, user: user},
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res) {
                var comment = res.comment;
                if (res.status == 300) {
                    $('.error').text(res.message);
                    $("button").attr("disabled", false);
                } else {
                    let vipAvatarClass = res.user.user_vip == 1 ? 'border-vip' : '',
                        vipNameClass = (res.user.user_vip == 1 ? 'text-danger font-weight-bold' : '') + ' position-relative pr-3' ,
                        vipIcon = res.user.user_vip == 1 ? '<div class="circle bg-success d-inline-block m-0 comment-vip-icon position-absolute" style="right: 5px; top: 50%; transform: translateY(-50%)"><div class="checkmark"></div></div>' : '',
                        vipCmtClass = res.user.user_vip == 1 ? 'bg-vip border-vip text-success font-weight-bold' : ' bg-light',
                        modIconClass = res.user.is_vip == 1 ? '<img style="width: 50px;" class="mt-1" src="{{ asset('frontend/images/mod.png') }}" alt="">' : res.users.user_vip == 1 ? '<img style="width: 50px;" class="mt-1" src="{{ asset('frontend/images/user_vip.png') }}" alt="">' : '',
                        vipStyleName = res.user.user_vip == 1 ? 'padding-right: 25px' : ''
                    if (parent == 0) {
                        $('.text').val('');
                        $('#list-comment').prepend(`
                        <div class="d-flex comment list${comment.id} val${comment.id} comment-box-mobile">
                            <div class="img-cmt-block d-flex flex-column avt-box-mobile">
                                <img src="uploads/user/${res.user.avatar}" class="comment-avatar ${vipAvatarClass}">
                                ${modIconClass}
                            </div>
                            <div class="sec val">
                                <div class="sec-top ${vipCmtClass} p-2 indent-text-comment">${comment.body}</div>
                                <div class="sec-bot">
                                    <a style="color:#535353; ${vipStyleName}" class="${vipNameClass}" href="/user/${res.user.id}">${res.user.name} ${vipIcon}</a> -
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
                            <div class="img-cmt-block d-flex flex-column avt-box-mobile">
                                <img src="uploads/user/${res.user.avatar}" class="comment-avatar ${vipAvatarClass}">
                                ${modIconClass}
                            </div>
                        <div class="sec" style="flex:1;margin-left:6px;">
                            <div class="sec-top ${vipCmtClass} p-2 indent-text-comment">${comment.body}</div>
                                <div class="sec-bot" class="${vipNameClass}"">
                                    <div class="ilb t-14 pv-0" style="padding:0 4px" cmtid="983532">
                                        <a style="color:#535353; ${vipStyleName}" class="${vipNameClass}" href="/user/${res.user.id}">${res.user.name} ${vipIcon}</a> -
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
        location.reload();
    }
</script>
