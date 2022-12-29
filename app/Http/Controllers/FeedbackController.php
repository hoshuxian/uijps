<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use Illuminate\Support\Facades\Route;

class FeedbackController extends Controller
{
    function submitfeedback(Request $req)
    {   
        $var = new feedback;
            $var->rate=$req->rate;
            $var->comment=$req->comment;
            $var->save();
            return redirect('feedback')->with("openpopup()");
    
        }

        function submitempfeedback(Request $req)
    {
            $var = new feedback;
            $var->rate=$req->rate;
            $var->comment=$req->comment;
            $var->save();
            return redirect('empfeedback');
    
        }

        function viewfeedback()
{
    $deta = feedback::all();
    return view('\Admin\searchfeedback',['deta'=>$deta]);
}

public function feedbacklist(request $request)
{
   
    $deta = $request->input('deta'); 
    $deta = feedback::select('rate','comment')->where('rate','LIKE', '%' . $deta . '%')->orwhere('comment','LIKE', '%' . $deta . '%')->get();
    if (count ( $deta ) > 0)
    return view('\Admin\searchfeedback', ['deta' => $deta])->with('successMsg','Results Found !');
    else
    return view ('\Admin\searchfeedback', ['deta' => $deta])->with('FailedMsg','No Details found. Try to search again !' );		
    
}
}
