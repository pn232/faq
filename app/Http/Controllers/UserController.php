<?php
namespace App\Http\Controllers;
use App\Profile;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
    public function index() {
        $users = User::where('role', 'user')->select('id', 'email')->get();
        $admins = User::where('role', 'admin')->select('id', 'email', 'uid', 'department')->get();
        $i = 1;
        return view('view_users',compact('users','i'));
    }
    public function index_admin() {
        $admins = User::where('role', 'admin')->select('id', 'email', 'uid', 'department')->get();
        $i = 1;
        return view('view_admins',compact('admins','i'));
    }
    public function update(Request $request) {
        $uid = $department = '';
        $user_id = $request->get('user_id');
        $profile_id = $request->get('profile_id');
        $email = $request->get('email');
        $fname = $request->get('fname');
        $lname = $request->get('lname');
        $body = $request->get('body');
        $user_info = User::find($user_id);
        if($user_info['role'] == 'admin') {
            $uid = $request->get('uid');
            $department = $request->get('department');
            User::where('id',$user_id)->update(compact('uid','department'));
        }
        $result1 = User::where('id', $user_id)->update(compact('email'));
        if($profile_id == 0) {
            $profile = new Profile();
            $profile->user_id = $user_id;
            $profile->fname = $fname;
            $profile->lname = $lname;
            $profile->body = $body;
            $result2 = $profile->save();
        } else {
            $result2 = Profile::where('id', $profile_id)->update(compact('fname', 'lname', 'body'));
        }
        if($result1 && $result2) {
            return ['data'=>'ok', 'status'=>'success'];
        }
        return ['data'=>'error', 'status'=>'failed'];
    }
    public function delete($user_id) {
        $result1 = User::where('id',$user_id)->delete();
        $result2 = Profile::where('user_id', $user_id)->delete();
        if($result1 && $result2) {
            return ['data'=>'ok', 'status'=>'success'];
        }
        return ['data'=>'error', 'status'=>'failed'];
    }
}