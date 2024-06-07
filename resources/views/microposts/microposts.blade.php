<div class="mt-4">
    @if (!empty($microposts))
        <ul class="list-none">
            @foreach ($microposts as $micropost)
                <li class="flex items-start gap-x-2 mb-4">
                    {{-- 投稿の所有者のメールアドレスをもとにGravatarを取得して表示 --}}
                    <div class="avatar">
                        <div class="w-12 rounded">
                            @if ($micropost->user->avatar_path === "default.png")
                                <img src="{{ Gravatar::get($user->email, ['size' => 500]) }}" alt="">
                            @else
                                <img src="{{ asset('storage/' . $micropost->user->avatar_path) }}" alt="Avatar">
                            @endif
                        </div>
                    </div>
                    <div>
                        <div>
                            {{-- 投稿の所有者のユーザー詳細ページへのリンク --}}
                            <a class="link link-hover text-info" href="{{ route('users.show', $micropost->user->id) }}">{{ $micropost->user->name }}</a>
                            <span class="text-muted text-gray-500">posted at {{ $micropost->created_at }}</span>
                        </div>
                        <div>
                            {{-- 投稿内容 --}}
                            <p class="mb-0">{!! nl2br(e($micropost->content)) !!}</p>
                        </div>
                        <div>
                            @if (Auth::id() == $micropost->user_id)
                                {{-- 投稿削除ボタンのフォーム --}}
                                <form method="POST" action="{{ route('microposts.destroy', $micropost->id) }}" class = "inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-light btn-sm normal-case" 
                                        onclick="return confirm('Delete id = {{ $micropost->id }} ?')">🗑</button>
                                </form>
                                @if (!Auth::user()->is_favoriting($micropost->id))
                                {{-- お気に入り追加ボタンのフォーム --}}
                                <form id="favorite-form-{{ $micropost->id }}" method="POST" action="{{ route('favorites.favorite', $micropost->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="btn btn-light btn-sm normal-case">💓{{ $micropost->favorite_count }}</button>
                                </form>    
                                @else
                                 {{-- お気に入り追加ボタンのフォーム --}}
                                <form id="favorite-form-{{ $micropost->id }}" method="POST" action="{{ route('favorites.favorite', $micropost->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="btn btn-error btn-sm normal-case">💓{{ $micropost->favorite_count }}</button>
                                </form>
                                @endif
                            @else
                                @if (!Auth::user()->is_favoriting($micropost->id))
                                {{-- お気に入り追加ボタンのフォーム --}}
                                <form id="favorite-form-{{ $micropost->id }}" method="POST" action="{{ route('favorites.favorite', $micropost->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="btn btn-light btn-sm normal-case">💓{{ $micropost->favorite_count }}</button>
                                </form>    
                                @else
                                 {{-- お気に入り追加ボタンのフォーム --}}
                                <form id="favorite-form-{{ $micropost->id }}" method="POST" action="{{ route('favorites.favorite', $micropost->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="btn btn-error btn-sm normal-case">💓{{ $micropost->favorite_count }}</button>
                                </form>
                                @endif
                            @endif
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
        {{-- ページネーションのリンク --}}
        {{ $microposts->links() }}
    @endif
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    @foreach ($microposts as $micropost)
    $('#favorite-form-{{ $micropost->id }}').on('submit', function(event) {
        event.preventDefault();

        var url = $(this).attr('action');
        var data = $(this).serialize();
        var button = $(this).find('button');

        $.post(url, data).done(function(response) {
            // ボタンのテキストを更新
            button.text('💓' + response.favoriteCount);

            // ボタンのクラスを更新
            if (response.status == 'favorited') {
                button.removeClass('btn-light');
                button.addClass('btn-error');
            } else {
                button.removeClass('btn-error');
                button.addClass('btn-light');
            }
        }).fail(function(error) {
            console.log(error);
        });
    });
    @endforeach
});
</script>