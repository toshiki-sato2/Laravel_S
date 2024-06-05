@extends('layouts.app')

@section('content')

    <div class="prose ml-4">
        <h2 class="text-lg">プロフィール編集</h2>
        <h3>新しいパスワードを設定をしない場合は空白のまま編集してください。</h3>
    </div>


    <div class="flex justify-center">
        <form method="POST" action="{{ route('users.update', \Auth::User()->id) }}" class="w-1/2">
            @csrf
            @method('PUT')

                <div class="form-control my-4 ">
                    <h2 class = "text-lg">プロフィール画像変更</h2>
                </div>

                <div class="form-control my-4">
                    <label for="image" class="label">
                        <span class="label-text">画像をファイルから選択</span>
                    </label>
                    <input type="file" name="image"  value = {{ \Auth::User()->name }} required>
                </div>
            <button type="submit" class="btn btn-primary btn-outline">編集完了</button>
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
        <form method="POST" action="{{ route('users.edit_profile', \Auth::User()->id) }}" class="w-1/2">
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