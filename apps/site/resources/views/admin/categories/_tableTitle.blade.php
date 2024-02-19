<div class="d-flex align-items-center">
    <div>
        <a href="javascript:;"   title="{{ $category->name }}" class="text-primary font-weight-semibold">{{ \Str::limit($category->name, 20) }}</a>
        <!-- <a href="{{ $category->url() }}" data-toggle="tooltip" data-html="true"  title="{{ $category->name }}" class="text-primary font-weight-semibold" target="_blank">{{ \Str::limit($category->name, 20) }}</a> -->
    </div>
</div>
