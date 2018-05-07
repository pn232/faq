<?php
namespace App\Http\Controllers;
use App\Answer;
use App\Question;
use Illuminate\Http\Request;
class FaqController extends Controller
{
    public function index() {
        $questions = Question::get();
        $answers = new Answer;
        $i = 1;
        return view('view_faqs', compact('questions', 'answers','i'));
    }
}