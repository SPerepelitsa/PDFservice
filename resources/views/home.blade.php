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
                        <th scope="col">Tags</th>
                        <th scope="col">Size</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                        <td>@mdo</td>
                    </tr>
                    <tr>
                        <th scope="row">2</th>
                        <td>Jacob</td>
                        <td>Thornton</td>
                        <td>@fat</td>
                        <td>@mdo</td>
                    </tr>
                    <tr>
                        <th scope="row">3</th>
                        <td>Larry</td>
                        <td>the Bird</td>
                        <td>@twitter</td>
                        <td>@mdo</td>
                    </tr>
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
