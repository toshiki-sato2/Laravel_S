@extends('layouts.app')

@section('content')

    <div class="prose ml-4">
        <h2 class="text-lg">プロフィール編集</h2>
    </div>

    <div class="flex justify-center">
        <form method="POST" action="{{ route('users.update', \Auth::User()->id) }}" class="w-1/2">
            @csrf

                <div class="form-control my-4">
                    <label for="name" class="label">
                        <span class="label-text">名前:</span>
                    </label>
                    <input type="text" name="name" class="input input-bordered w-full" placeholder = {{ \Auth::User()->name }}>
                </div>

                <div class="form-control my-4">
                    <label for="e-mail" class="label">
                        <span class="label-text">e-mail</span>
                    </label>
                    <input type="text" name="e-mail" class="input input-bordered w-full" placeholder = {{ \Auth::User()->email }}>
                </div>

            <button type="submit" class="btn btn-primary btn-outline">編集完了</button>
        </form>
    </div>

@endsection