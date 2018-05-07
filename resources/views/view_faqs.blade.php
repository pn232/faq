@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <table class="table">
                <thead>
                <tr class="btn-success">
                    <th>NO</th>
                    <th>QUESTION</th>
                    <th>QUESTIONER</th>
                    <th>QUESTION DATE</th>
                    <th>ANSWER STATE</th>
                    <th>ACTION</th>
                </tr>
                </thead>
                <tbody>
                @if(isset($questions))
                    @foreach($questions as $question)
                        <tr id="row_{{$i}}">
                            <td>{{$i++}}</td>
                            <td>{{$question->body}}</td>
                            <td>{{$question->user['email']}}</td>
                            <td>{{$question->created_at}}</td>
                            <td>
                                @if(isset($question->answers) && count($question->answers)>0)
                                    <button type="button" class="btn btn-primary answered" data-id="{{$question->id}}">
                                        view answers <span class="badge badge-pill badge-light text-primary">{{count($question->answers)}}</span>
                                    </button>
                                @else
                                    <button type="button" disabled class="btn btn-outline-secondary not-answered">
                                        &nbsp;no&nbsp; answers <span class="badge badge-pill badge-warning">0</span>&nbsp;
                                    </button>
                                @endif
                            </td>
                            <td>
                                <a href="javascript:edit_question('{{$question->id}}','row_{{$i-1}}')" class="btn btn-primary">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="javascript:delete_question('{{$question->id}}');" class="btn btn-danger">
                                    <i class="fa fa-trash-o"></i>
                                </a>
                            </td>
                        </tr>

                    @endforeach
                @endif
                </tbody>
            </table>
            @if(!isset($questions) || count($questions)==0)
                <p class="w-100 text-center mt-5">There is no data to be displayed</p>
            @endif
        </div>
    </div>

    <!-- view faq modal -->
    <div class="modal fade" id="view_faq_modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title question pl-1 pr-2" id="question"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body answers">
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
    <!-- edit question modal -->
    <div class="modal fade" id="edit_question_modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title question pl-1 pr-2" id="question">Question</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body form-group">
                    <input type="hidden" id="question_edit_id">
                    <label class="custom-control-label" for="new_question_body">Question:</label>
                    <input type="text" class="form-control" id="new_question_body">
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary save_question">Save</button>
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        function edit_question(question_id, row_id) {
            let row= '#'+ row_id;
            $('#edit_question_modal').modal('show');
            $('#question_edit_id').val(question_id);
            $('#new_question_body').val($(row).children().eq(1).html());
        }
        $('.answered').on('click', function () {
            let question_id = $(this).attr('data-id');
            let question = $(this).parent().parent().children().eq('1').html();
            $.ajax({
                url:'/questions/' + question_id + '/answers',
                method:'get',
                data:{},
                success:function (response) {
                    let html = '';
                    if(response.length > 0) {
                        for(let i = 0;i< response.length; i++) {
                            html = '<div class="panel" id="answer_'+i+'">' +
                                ' <div class="w-100">' +
                                '    <span class="text-primary answerer"><i class="fa fa-exclamation"></i> </span>' +
                                '    <button type="button" class="btn btn-outline-warning rounded btn-sm float-right delete_answer" onclick="delete_answer('+response[i]['id']+','+i+')" data-id="'+response[i]['id']+'">' +
                                '        <i class="fa fa-trash"></i>' +
                                '    </button>' +
                                '    <span class="float-right text-secondary answer_date mr-2 mt-1">'+response[i]['created_at']+'</span>' +
                                ' </div>' +
                                ' <div class="panel-body answer_body p-3">' +response[i]['body']+ '</div>' +
                                ' </div><hr class="w-100">';
                        }
                    } else {
                        html = '<p class="w-100 text-center">There is no data to be displayed.</p>';
                    }
                    $('#question').html(question);
                    $('.answers').html(html);
                    $('#view_faq_modal').modal('show');
                }
            })
        });
        $('.save_question').on('click',function () {
            let question_id = $('#question_edit_id').val();
            let question_body = $('#new_question_body').val();
            if(question_body === '') return;
            $('#edit_question_modal').modal('hide');
            $.ajax({
                url:'/admin/' + question_id + '/question_update',
                method:'post',
                data:{
                    body:question_body,
                    _token:'{{csrf_token()}}'
                },
                success:function (response) {
                    if(response.status === 'success') {
                        alert('update sucess');
                        location.reload();
                    } else {
                        alert('update failed');
                    }
                }
            });
        });
        function delete_question(question_id) {
            if(confirm('Are you sure delete this question?')) {
                $.ajax({
                    url:'/admin/'+question_id+'/question_delete',
                    method: 'get',
                    data:{},
                    success:function (response) {
                        if(response.status === 'success'){
                            alert('delete success');
                            location.reload();
                        } else {
                            alert('delete failed');
                        }
                    }
                })
            }
        }
        function delete_answer(answer_id,row) {
            let row_id = '#answer_'+row;
            if(confirm('Are you sure delete this answer?')) {
                $.ajax({
                    url:'/admin/'+answer_id+'/answer_delete',
                    method: 'get',
                    data:{},
                    success:function (response) {
                        if(response.status === 'success'){
                            alert('delete success');
                            $(row_id).css('display','none');
                        } else {
                            alert('delete failed');
                        }
                    }
                })
            }
        }
    </script>
@stop