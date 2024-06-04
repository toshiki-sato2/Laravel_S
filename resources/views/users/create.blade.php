@extends('layouts.app')

@section('content')

    <div class="prose ml-4">
        <h2 class="text-lg">プロフィール編集</h2>
    </div>

    <div class="flex justify-center">
        <form method="POST" action="{{ route('users.update', \Auth::User()->id) }}" class="w-1/2">
            @csrf
            @method('PUT')

                <div class="form-control my-4">
                    <label for="name" class="label">
                        <span class="label-text">名前:</span>
                    </label>
                    <input type="text" name="name" class="input input-bordered w-full" placeholder = {{ \Auth::User()->name }} required>
                </div>

                <div class="form-control my-4">
                    <label for="email" class="label">
                        <span class="label-text">e-mail</span>
                    </label>
                    <input type="email" name="email" class="input input-bordered w-full" placeholder = {{ \Auth::User()->email }} required>
                </div>

                <div class="form-control my-4">
                    <label for="current_password" class="label">
                        <span class="label-text">current password</span>
                    </label>
                    <input type="password" name="current_password" class="input input-bordered w-full" required>
                </div>

                <div class="form-control my-4">
                    <label for="new_password" class="label">
                        <span class="label-text">new_password</span>
                    </label>
                    <input type="password" name="new_password" class="input input-bordered w-full" required>
                </div>

                <div class="form-control my-4">
                    <label for="new_password_confirmation" class="label">
                        <span class="label-text">confirm new_password</span>
                    </label>
                    <input type="password" name="new_password_confirmation" class="input input-bordered w-full" required>
                </div>

            <button type="submit" class="btn btn-primary btn-outline">編集完了</button>
        </form>
    </div>

@endsection