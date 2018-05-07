<?php
namespace App\Http\Controllers;
use App\Answer;
use App\Question;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $questions = $user->questions()->paginate(6);
        return view('home')->with('questions', $questions);
    }
    public function admin_dashboard() {
        if((Auth::user()->role) != 'admin') {
            return redirect()->route('home');
        }
        $users = User::where('role', 'user')->get()->count();
        $questions = Question::get()->count();
        $answers = Answer::get()->count();
        return view('admin',compact('users', 'questions', 'answers'));
    }
    public function superadmin_dashboard() {
        if((Auth::user()->role) != 'superadmin') {
            return redirect()->route('home');
        }
        $users = User::where('role','user')->get()->count();
        $admins = User::where('role','admin')->get()->count();
        $questions = Question::get()->count();
        $answers = Answer::get()->count();
        return view('superadmin',compact('users', 'admins','questions', 'answers'));
    }
}