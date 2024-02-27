@props(['rating' => false, 'name' => 'Anonim', 'avatar', 'text', 'time'])

<div class="comment-wrap clearfix">
    <div class="comment-meta">
        <div class="comment-author vcard">
            <span class="comment-avatar clearfix">
                @isset($avatar)
                @else
                    <img alt='Image' src='https://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536?s=60' height='60' width='60' />
                @endif
            </span>
        </div>
    </div>
    <div class="comment-content clearfix">
        <div class="comment-author">{{ $name }}<span><a href="#" title="Permalink to this comment">{{ $time }}</a></span></div>
        <p>{!! nl2br(strip_tags($text)) !!}</p>
        <x-rating :rating="$rating" />
    </div>
    <div class="clear"></div>
</div>