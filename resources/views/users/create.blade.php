@extends('layouts.app')

@section('content')

    <div class="prose ml-4">
        <h2 class="text-lg">プロフィール編集</h2>
        <h3>新しいパスワードを設定をしない場合は空白のまま編集してください。</h3>
    </div>


    <div class="flex justify-center">
        <form method="POST" action="{{ route('users.update_image', \Auth::User()->id) }}" class="w-1/2" enctype="multipart/form-data">
            @csrf
            @method('PUT')

                <div class="form-control my-4 ">
                    <h2 class = "text-lg">プロフィール画像変更</h2>
                </div>
                <div class="form-control my-4">
                    <label for="avator_image" class="label">
                        <span class="label-text">画像をファイルから選択</span>
                    </label>
                    <input type="file" name="avator_image" required>
                </div>
                <button type="submit" class="btn btn-primary btn-outline mb-4">編集完了</button>
                <div class="card border border-base-300">
                    <div class="card-body bg-base-200 text-4xl">
                        <h2 class="card-title">現在のプロフィール画像</h2>
                    </div>
                    <figure>
                        @if (Auth::user()->avatar_path === "default.png")
                            <img src="{{ Gravatar::get(Auth::user()->email, ['size' => 500]) }}" alt="！画像が設定されていません！" class = "w-40 h-40">
                        @else
                            <img src="{{ asset('storage/' . Auth::user()->avatar_path) }}" alt="！もし画像がうまく表示されなければ画像を再設定してください！" class = "w-40 h-40">
                        @endif
                    </figure>
                </div>
        </form>
    </div>    
    
    

    <div class="flex justify-center">
        <form method="POST" action="{{ route('users.update', \Auth::User()->id) }}" class="w-1/2">
            @csrf
            @method('PUT')

                <div class="form-control my-4 ">
                    <h2 class = "text-lg">プロフィール情報編集</h2>
                </div>

                <div class="form-control my-4">
                    <label for="name" class="label">
                        <span class="label-text">名前（必須）</span>
                    </label>
                    <input type="text" name="name" class="input input-bordered w-full" value = {{ \Auth::User()->name }} required>
                </div>

                <div class="form-control my-4">
                    <label for="email" class="label">
                        <span class="label-text">メールアドレス（必須）</span>
                    </label>
                    <input type="email" name="email" class="input input-bordered w-full" value = {{ \Auth::User()->email }} required>
                </div>
            <button type="submit" class="btn btn-primary btn-outline">編集完了</button>
        </form>
    </div>



    <div class="flex justify-center">
        <form method="POST" action="{{route('users.edit_profile', \Auth::User()->id) }}" class="w-1/2">
            @csrf
            @method('PUT')
            
                <div class="form-control my-4 font-bold">
                    <h2 class = "text-lg">パスワードの変更</h2>
                </div>
            
                <div class="form-control my-4">
                    <label for="current_password" class="label">
                        <span class="label-text">現在のパスワードを入力（必須）</span>
                    </label>
                    <input type="password" name="current_password" class="input input-bordered w-full" required>
                </div>

                <div class="form-control my-4">
                    <label for="new_password" class="label">
                        <span class="label-text">新しいパスワード（任意）</span>
                    </label>
                    <input type="password" name="new_password" class="input input-bordered w-full">
                </div>

                <div class="form-control my-4">
                    <label for="new_password_confirmation" class="label">
                        <span class="label-text">新しいパスワードをもう一度入力してください</span>
                    </label>
                    <input type="password" name="new_password_confirmation" class="input input-bordered w-full">
                </div>

            <button type="submit" class="btn btn-primary btn-outline">編集完了</button>
        </form>
    </div>




@endsection