<div class="card border border-base-300">
    <div class="card-body bg-base-200 text-4xl">
        <h2 class="card-title">{{ $user->name }}</h2>
    </div>
    <figure>
        @if ($user->avatar_path === "default.png")
            <img src="{{ asset('avator/' . $user->avatar_path) }}" alt="！画像が設定されていません！">
        @else
            <img src="{{ asset('storage/' . $user->avatar_path) }}" alt="！もし画像がうまく表示されなければ画像を再設定してください！">
        @endif
    </figure>
</div>
{{-- フォロー／アンフォローボタン --}}
@include('user_follow.follow_button')