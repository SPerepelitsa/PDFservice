@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <h1>Файлы</h1>

                {{--@include('partials._validation_messages')--}}

                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif

            </div>
            <div class="col-md-2">
                <a href="{{ route('upload-form') }}" class="btn btn-lg btn-primary btn-block">Add</a>
            </div>
            <div class="col-md-12">
                <hr>
            </div>
        </div> <!-- end of a .row-->

        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Titile</th>
                        <th scope="col">Description</th>
                        <th scope="col">Key Words</th>
                        <th scope="col">Pages</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($files as $file)
                    <tr>
                        <th scope="row">{{ $file->id }}</th>
                        <td>{{ $file->title }}</td>
                        <td>{{ $file->description }}</td>
                        <td>{{ $file->key_words }}</td>
                        <td>{{ $file->metainfo }}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="text-center">
                    {{--pagination--}}
                </div>
            </div>
        </div>
        <!--/.row-->
    </div>
@endsection
