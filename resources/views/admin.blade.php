@extends('layouts.app')


@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-xl-4 col-lg-4 col-md-6 col-xs-12 user_panel">
                <div class="user float-left">
                    <i class="fa fa-user"></i>
                </div>
                <div class="num_panel float-left">
                    <h4 class="title m-4">
                        USERS
                    </h4>
                    <h1 class="number ml-4">
                        @if(isset($users))
                            {{$users}}
                        @else
                            0
                        @endif
                    </h1>
                </div>
            </div>
            <div class="col-xl-5 col-lg-5 col-md-6 col-xs-12 questions_panel">
                <div class="questions float-left">
                </div>
                <div class="num_panel float-left">
                    <h4 class="title ml-4 mt-4">
                        QUESTIONS
                    </h4>
                    <h4 class="number ml-4">
                        @if(isset($questions))
                            {{$questions}}
                        @else
                            0
                        @endif
                    </h4>
                    <h6 class="title mt-2 ml-4">
                        ANSWERS<span class="answer_num ml-1">
	                            @if(isset($answers))
                                {{$answers}}
                            @else
                                0
                            @endif
	                        </span>
                        <span class="float-right text-white-50 mt-3 mr-2">
	                            @if(isset($answers) && $questions > 0 )
                                {{100*($answers/$questions)}}%
                            @else
                                0%
                            @endif
	                        </span>
                    </h6>
                    <div class="progress ml-1 mt-2 mr-1">
                        @if(isset($answers) && $questions > 0 )
                            <div class="progress-bar bg-white" style="width:{{100*($answers/$questions)}}%"></div>
                        @else
                            <div class="progress-bar bg-primary" style="width:0%"></div>
                            @endif
                            </P>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop


@section('scripts')
    <script>
        $('.user_panel').on('click', function () {
            location.replace('/admin/view_users');
        })
        $('.questions_panel').on('click', function () {
            location.replace('/admin/view_faqs');
        })
    </script>
@stop

