@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <table class="table">
                <thead>
                <tr class="btn-info">
                    <th>NO</th>
                    <th>FIRST NAME</th>
                    <th>LAST NAME</th>
                    <th>BODY</th>
                    <th>EMAIL</th>
                    <th>ACTION</th>
                </tr>
                </thead>
                <tbody>
                @if(isset($users))
                    @foreach($users as $user)
                        <tr id="row_{{$i}}">
                            <td>{{$i++}}</td>
                            <td>{{$user->profile['fname']}}</td>
                            <td>{{$user->profile['lname']}}</td>
                            <td>{{$user->profile['body']}}</td>
                            <td>{{$user->email}}</td>
                            <td>
                                <a href="javascript:edit('{{$user->id}}','{{$user->profile['id']}}', '{{$i-1}}');" class="btn btn-primary">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="javascript:delete_user('{{$user->id}}');" class="btn btn-danger">
                                    <i class="fa fa-trash-o"></i>
                                </a>
                            </td>
                        </tr>

                    @endforeach
                @endif
                </tbody>
            </table>
            @if(!isset($users))
                <p class="w-100 text-center mt-5">There is no data to be displayed</p>
            @endif
        </div>
    </div>
    <!-- edit profile modal -->
    <div class="modal fade" id="edit_user_modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Edit Profile</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body pl-5 pr-5">
                    <input type="hidden" id="user_id">
                    <input type="hidden" id="profile_id">
                    <div class="row form-group">
                        <label for="email">Email:</label>
                        <input type="text" class="form-control" id="email" disabled>
                    </div>
                    <div class="row form-group">
                        <label for="fname">First Name:</label>
                        <input type="text" class="form-control" id="fname" required>
                    </div>
                    <div class="row form-group">
                        <label for="lname">Last Name:</label>
                        <input type="text" class="form-control" id="lname" required>
                    </div>
                    <div class="row form-group">
                        <label for="body">Body:</label>
                        <textarea rows="3" class="form-control" id="body"></textarea>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-success" id="save_profile">Save</button>
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">cancel</button>
                </div>

            </div>
        </div>
    </div>

    <!-- delete modal -->
    <div class="modal fade" id="delete_modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header bg-danger text-white">
                    <h4 class="modal-title">Confirm</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <input type="hidden" id="uid">
                    Are you sure delete this user?
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="delete_user">Delete</button>
                    <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                </div>

            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        function edit(user_id, profile_id, row) {
            if(profile_id ==='') profile_id = 0;
            let row_id = '#row_'+row;
            let data = $(row_id).children();
            $('#edit_user_modal').modal('show');
            $('#user_id').val(user_id);
            $('#profile_id').val(profile_id);
            $('#fname').val(data.eq('1').html());
            $('#lname').val(data.eq('2').html());
            $('#body').val(data.eq('3').html());
            $('#email').val(data.eq('4').html());
        }
        function delete_user(user_id) {
            $('#delete_modal').modal('show');
            $('#uid').val(user_id);
        }
        $('#save_profile').on('click', function () {
            let user_id = $('#user_id').val();
            let profile_id = $('#profile_id').val();
            let email = $('#email').val();
            let fname = $('#fname').val();
            let lname = $('#lname').val();
            let body = $('#body').val();
            $('#edit_user_modal').modal('hide');
            $.ajax({
                url:'/admin/update_profile',
                method: 'POST',
                data: {
                    user_id:user_id,
                    profile_id:profile_id,
                    email:email,
                    fname:fname,
                    lname:lname,
                    body:body,
                    _token:'{{csrf_token()}}'
                },
                success: function (response) {
                    if(response.status === 'success') {
                        alert('save success');
                        location.reload();
                    }
                    else {
                        alert('save failed');
                    }
                },
                error:function () {
                    alert('save failed');
                }
            })
        });
        $('#delete_user').on('click', function () {
            let uid = $('#uid').val();
            $('#delete_modal').modal('hide');
            $.get(
                '/admin/'+ uid + '/delete_user',
                function (data,status) {
                    if(status === "success") alert('delete success');
                    else alert('delete error');
                    location.reload();
                }
            )
        })
    </script>
@stop