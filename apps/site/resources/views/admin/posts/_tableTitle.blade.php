<div class="d-flex align-items-center">
    <div>
        <a href="{{ $post->url() }}" data-toggle="tooltip" data-html="true"  title="{{ $post->title }}" class="text-default font-weight-semibold" target="_blank">{{ \Str::limit($post->title, 20) }}</a>
    </div>
</div>
