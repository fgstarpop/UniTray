@if(isset($user))
@foreach($user as $key=> $list)
<li data-ids="{{$list->id}} ">{{ $list->name}} <span style="font-size:10px">
(ID người dùng : {{$list->id}})</li>
@endforeach
@endif 