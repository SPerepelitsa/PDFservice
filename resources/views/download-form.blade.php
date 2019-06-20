@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-md-8 col-md-offset-2">
            <h3>Загрузка файла</h3>
            <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{ route('download') }}">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="file" class="col-md-4 control-label">Загрузите ваш pdf файл</label>
                    <div class="col-sm-8">
                        <input type="file" name="file" id="file">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-8">
                        <button type="submit" id="submit" class="btn btn-primary">Отправить</button>
                        <div></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
