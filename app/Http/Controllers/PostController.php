<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Employer;
use App\Models\Student;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\jobapply;
use App\Models\joboffer;
use Image;
use Session;
use DB;

class PostController extends Controller
{

    function createjob(Request $req)
    {

            //Image::make($image)->resize(300,300)->save(public_path('/uploads/student/'.$filename));*/
            $var = new post;
            if($req->session()->has('result')){
                $result=session('result.0.id');
                //$result = $req->session()->all();
                //dd($result);
                $var->id=$result;
            $var->job_title=$req->job_title;
            $var->job_venue=$req->job_venue;
            $var->job_address=$req->job_address;
            $var->position_available=$req->position_available;
            $var->job_salary=$req->job_salary;
            $var->job_category=$req->job_category;
            $var->job_description=$req->job_description;
            $var->job_benefit=$req->job_benefit;
            $var->job_highlight=$req->job_highlight;
            $var->job_requirement=$req->job_requirement;
            $var->save();
            return redirect('searchpost')->with('successMsg','Job Post Successfully !');
            }
    }

    function viewpostlist(Request $req)
    {
        if($req->session()->has('result')){
            $result=session('result.0.id');
        $deta = DB::table('posts')->select('*')->where('posts.id', '=', $result)->get();
        return view('\Employer\searchpost',['deta'=>$deta]);
        }
    }

    public function postlist(request $request)
{
   
    $deta = $request->input('deta'); 
    $deta = post::select('job_title','job_salary','job_venue','job_benefit')->where('job_title','LIKE', '%' . $deta . '%')->get();
    if (count ( $deta ) > 0)
    return view('\Employer\searchpost', ['deta' => $deta])->with('successMsg','Results Found !');
    else
    return view ('\Employer\searchpost', ['deta' => $deta])->with('FailedMsg','No Details found. Try to search again !' );		
    
}

function displaypost(Request $req,$post_id)

{
    if($req->session()->has('result')){
        $result=session('result.0.id');
        $deta = DB::table('employers')->join('posts', 'posts.id', '=', 'employers.id')
        ->select('posts.job_salary', 'posts.job_title', 'posts.job_venue', 'posts.job_description', 'posts.job_requirement', 'posts.job_category', 'posts.job_benefit','posts.job_applynumber','posts.hired','posts.position_available', 'employers.company_description', 'employers.company_size', 'employers.reg_no', 'posts.post_id')
        ->where('posts.id', '=', $result)->where('posts.post_id', '=', $post_id)->get();
    return view('\Employer\displaypost', ['deta' => $deta]);
    }
}

public function deletepost($post_id)
{
    
    $result = post::select('*')->where('post_id', '=', $post_id)->delete();
    return redirect('searchpost')->with('successMsg','Profile Successful deleted !');
}

function updatepost($post_id)

{
    $result = post::select('*')->where('post_id', '=', $post_id)->get();
    return view('\Employer\updatepost', ['result' => $result]);
}

function postupdate(Request $req, $post_id)
{
            if($req->session()->has('result')){
                $result=session('result.0.id');
                $var = post::where('post_id', '=', $req->post_id)->where('id','=',$result)->first();
                $var->job_title=$req->input('job_title');
                $var->job_venue=$req->input('job_venue');
                $var->job_address=$req->input('job_address');
                $var->job_salary=$req->input('job_salary');
                $var->job_category=$req->input('job_category');
                $var->job_description=$req->input('job_description');
                $var->job_benefit=$req->input('job_benefit');
                $var->job_highlight=$req->input('job_highlight');
                $var->job_requirement=$req->input('job_requirement');
                $var->update();
                return redirect('searchpost')->with('successMsg','Job Post Successfully !');
                }

    }

function viewallpostlist()
    {

        $deta = DB::table('employers')->join('posts', 'posts.id', '=', 'employers.id')
        ->select('posts.job_salary', 'posts.job_title', 'posts.job_venue', 'posts.job_benefit', 'posts.job_applynumber', 'employers.company_name', 'employers.company_logo', 'posts.post_id')->get();
    return view('\Employer\searchallpost', ['deta' => $deta]);
    }

    public function allpostlist(request $request)
{
   
    $deta = $request->input('deta'); 
    $deta = DB::table('employers')->join('posts', 'posts.id', '=', 'employers.id')
    ->select('posts.job_salary', 'posts.job_title', 'posts.job_venue', 'posts.job_benefit', 'posts.job_applynumber', 'employers.company_name', 'employers.company_logo', 'posts.post_id')
    ->where('posts.job_title','LIKE', '%' . $deta . '%')->orwhere('posts.job_salary','LIKE', '%' . $deta . '%')->orwhere('posts.job_venue','LIKE', '%' . $deta . '%')->orwhere('employers.company_name','LIKE', '%' . $deta . '%')->get();
    if (count ( $deta ) > 0)
    return view('\Employer\searchallpost', ['deta' => $deta])->with('successMsg','Results Found !');
    else
    return view ('\Employer\searchallpost', ['deta' => $deta])->with('FailedMsg','No Details found. Try to search again !' );		
    
}

function displayallpost(Request $req,$post_id)

{
        $deta = DB::table('employers')->join('posts', 'posts.id', '=', 'employers.id')
        ->select('posts.job_salary', 'posts.job_title', 'posts.job_venue', 'posts.job_description', 'posts.job_requirement', 'posts.job_category', 'posts.job_benefit', 'posts.job_applynumber', 'employers.company_description', 'employers.company_size', 'employers.reg_no', 'employers.company_name', 'employers.id', 'employers.company_logo','posts.post_id')
        ->where('posts.post_id', '=', $post_id)->get();
    return view('\Employer\displayallpost', ['deta' => $deta]);
}

function viewjoblist()
    {

        $deta = DB::table('employers')->join('posts', 'posts.id', '=', 'employers.id')
        ->select('posts.job_salary', 'posts.job_title', 'posts.job_venue', 'posts.job_benefit', 'posts.job_applynumber', 'employers.company_name', 'employers.company_logo', 'posts.post_id')->get();
    return view('\Student\searchjob', ['deta' => $deta]);
    }

    public function joblist(request $request)
{
   
    $deta = $request->input('deta'); 
    $deta = DB::table('employers')->join('posts', 'posts.id', '=', 'employers.id')
    ->select('posts.job_salary', 'posts.job_title', 'posts.job_venue', 'posts.job_benefit', 'posts.job_applynumber', 'employers.company_name', 'employers.company_logo', 'posts.post_id')
    ->where('posts.job_title','LIKE', '%' . $deta . '%')->orwhere('posts.job_salary','LIKE', '%' . $deta . '%')->orwhere('posts.job_venue','LIKE', '%' . $deta . '%')->orwhere('employers.company_name','LIKE', '%' . $deta . '%')->get();
    if (count ( $deta ) > 0)
    return view('\Student\searchjob', ['deta' => $deta])->with('successMsg','Results Found !');
    else
    return view ('\Student\searchjob', ['deta' => $deta])->with('FailedMsg','No Details found. Try to search again !' );		
    
}

function displayjob(Request $req,$post_id)

{
    $apply = post::where('post_id', '=', $post_id)->first();
    if($apply->position_available === $apply->hired){
        $deta = DB::table('employers')->join('posts', 'posts.id', '=', 'employers.id')
        ->select('posts.job_salary', 'posts.job_title', 'posts.job_venue', 'posts.job_description', 'posts.job_requirement', 'posts.job_category', 'posts.job_benefit', 'posts.job_applynumber', 'posts.position_available', 'posts.hired', 'employers.company_description', 'employers.company_size', 'employers.reg_no', 'employers.company_name', 'employers.id', 'employers.company_logo', 'posts.post_id')
        ->where('posts.post_id', '=', $post_id)->get();
        return view('\Student\displayjob', ['deta' => $deta])->with('disable',true);
    }else{
        $deta = DB::table('employers')->join('posts', 'posts.id', '=', 'employers.id')
        ->select('posts.job_salary', 'posts.job_title', 'posts.job_venue', 'posts.job_description', 'posts.job_requirement', 'posts.job_category', 'posts.job_benefit', 'posts.job_applynumber', 'posts.position_available', 'posts.hired', 'employers.company_description', 'employers.company_size', 'employers.reg_no', 'employers.company_name', 'employers.id', 'employers.company_logo', 'posts.post_id')
        ->where('posts.post_id', '=', $post_id)->get();
        return view('\Student\displayjob', ['deta' => $deta]);
    }
}

function apply(Request $req,$post_id)
{
        $deta =  DB::table('posts')->select('post_id')->where('post_id','=',$post_id)->increment('job_applynumber');
    $var = new jobapply;
    if($req->session()->has('result')){
        $result=session('result.0.id');
        $var->id=$result;
    $var->post_id=$post_id;
    $var->save();
    return redirect('searchjob')->with('successMsg','Your apply had been sent to the company!');
    }
}

function displaystudentapply(Request $req,$post_id)

{
    if($req->session()->has('result')){
        $result=session('result.0.id');
        $deta = DB::table('students')->join('jobapplies', 'jobapplies.id', '=', 'students.id')
        ->select('students.std_matric', 'students.std_name', 'students.std_email', 'students.std_phonenum', 'students.id','jobapplies.post_id')
        ->where('jobapplies.post_id', '=', $post_id)->get();
    return view('\Employer\displaystudentapply', ['deta' => $deta]);
    }
}

function display($post_id,$id)

{
    $deta = student::where('id', '=', $id)->first();
    if($deta->id === $id){
        $result = DB::table('students')->join('jobapplies', 'jobapplies.id', '=', 'students.id')
        ->select('students.std_name', 'students.std_matric', 'students.std_address', 'students.std_phonenum','students.std_email','students.std_faculty','students.std_description','students.std_pic','students.resume','students.id','students.standard','jobapplies.post_id')
        ->where('students.id','=',$id)
        ->where('jobapplies.post_id', '=', $post_id)->get();
        return view('\Employer\display', ['result' => $result])->with('disable',true);
    }else {
        $hired = jobapply::where('post_id', '=', $post_id)->first();
    if($hired->position_available === $hired->hired){
        $result = DB::table('students')->join('jobapplies', 'jobapplies.id', '=', 'students.id')
        ->select('students.std_name', 'students.std_matric', 'students.std_address', 'students.std_phonenum','students.std_email','students.std_faculty','students.std_description','students.std_pic','students.resume','students.id','students.standard','jobapplies.post_id')
        ->where('students.id','=',$id)
        ->where('jobapplies.post_id', '=', $post_id)->get();
        return view('\Employer\display', ['result' => $result])->with('disable',true);
    }else{
        $result = DB::table('students')->join('jobapplies', 'jobapplies.id', '=', 'students.id')
        ->select('students.std_name', 'students.std_matric', 'students.std_address', 'students.std_phonenum','students.std_email','students.std_faculty','students.std_description','students.std_pic','students.resume','students.id','students.standard','jobapplies.post_id')
        ->where('students.id','=',$id)
        ->where('jobapplies.post_id', '=', $post_id)->get();
        return view('\Employer\display', ['result' => $result]);
    }
    }
}

public function searchapply(request $request,$post_id)
{
   
    if($request->session()->has('result')){
        $result=session('result.0.id');
        $deta = $request->input('deta'); 
        $deta = DB::table('students')->join('jobapplies', 'jobapplies.id', '=', 'students.id')
        ->select('students.std_matric', 'students.std_name', 'students.std_email', 'students.std_phonenum','students.id','jobapplies.post_id')
        ->where('students.std_matric','LIKE', '%' . $deta . '%')
        ->orwhere('students.std_name','LIKE', '%' . $deta . '%')
        ->orwhere('students.std_email','LIKE', '%' . $deta . '%')
        ->orwhere('students.std_phonenum','LIKE', '%' . $deta . '%')
        ->where('jobapplies.post_id', '=', $post_id)->get();
        if (count ( $deta ) > 0){
        return view('\Employer\displaystudentapply', ['deta' => $deta])->with('successMsg','Results Found !');
        }
        else {
        if($request->session()->has('result')){
            $result=session('result.0.id');
            $deta = DB::table('students')->join('jobapplies', 'jobapplies.id', '=', 'students.id')
            ->select('students.std_matric', 'students.std_name', 'students.std_email', 'students.std_phonenum', 'students.id','jobapplies.post_id')
            ->where('jobapplies.post_id', '=', $post_id)->get();
        return view ('\Employer\displaystudentapply', ['deta' => $deta])->with('FailedMsg','No Details found. Try to search again !' );	
        }
    }
}

}

function hired($post_id,$id)
{
    $deta =  DB::table('posts')->join('jobapplies', 'jobapplies.post_id', '=', 'posts.post_id')
    ->select('jobapplies.id', 'posts.hired','posts.post_id')
    ->where('jobapplies.id','=', $id)
    ->where('jobapplies.post_id','=', $post_id)
    ->increment('hired');

    /*$result = DB::table('students')->join('jobapplies', 'jobapplies.id', '=', 'students.id')
    ->select('students.std_name', 'students.std_matric', 'students.std_address', 'students.std_phonenum','students.std_email','students.std_faculty','students.std_description','students.std_pic','students.resume','students.id','students.standard','jobapplies.post_id')
    ->where('students.id','=',$id)
    ->where('jobapplies.post_id', '=', $post_id)->get();
    return view('\Employer\display', ['result' => $result])->with('successMsg','Your offer had been sent to the student!');*/

        $deta = DB::table('students')->join('jobapplies', 'jobapplies.id', '=', 'students.id')
        ->select('students.std_matric', 'students.std_name', 'students.std_email', 'students.std_phonenum', 'students.id','jobapplies.post_id')
        ->where('jobapplies.post_id', '=', $post_id)->get();
    return view ('\Employer\displaystudentapply', ['deta' => $deta])->with('successMsg','Your offer had been sent to the student!');

}
}
