@extends('layouts.app')

@section('content')

    <div class="container">

        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">
                    الاطباء
                </div>
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Title</th>
                    </tr>
                    </thead>
                    <tbody>

                    @if(isset($doctors) && $doctors->count()  > 0 )
                        @foreach($doctors as $doctor)
                            <tr>
                                <th scope="row">{{$doctor->id}}</th>
                                <td>{{$doctor->name}}</td>
                                <td>{{$doctor->title}}</td>
                            </tr>
                        @endforeach
                    @endif

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
