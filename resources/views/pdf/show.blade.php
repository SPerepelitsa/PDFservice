@extends('layouts.app')

@section('title')
    {{ "PDF" }}
@endsection

@section('content')

    <div class="container">
        <div class="row create-wrap">
            <div class="col-md-8 col-md-offset-2">
                <div class="text-center">
                    <br><br>
                    <h2>Заголовок</h2>
                    <p>Текст</p>
                    <hr>
                </div>
                <div class="text-center">
                    @foreach($metainfo as $param => $value)
                    <p><b>{{ $param }}: </b> {{ $value }} </p>
                    @endforeach
                    <hr>
                </div>
                <div class="text-center">
                    <a href="{{ URL::previous() }}" class="btn btn-danger btn-lg" role="button">Назад</a>
                </div>
            </div>
        </div>
        <!--/.row-->
    </div>
    <!--/.container-->

@endsection
