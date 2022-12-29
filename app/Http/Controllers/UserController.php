<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Employer;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Image;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //Manage Sign Up

    function studentsignup(Request $req)
    {
        $std_email = $req->input('std_email'); 
        $std_password = $req->input('std_password'); 
        $std_confirmpassword = $req->input('std_confirmpassword'); 
        $std_email = Student::select('std_email')->where('std_email','LIKE', '%' . $std_email . '%')->get();
        
        if (count ( $std_email ) > 0){
            if(($std_password) !== ($std_confirmpassword)){
                return view('\Sign up\signup', ['std_email' => $std_email],['std_password' => $std_password],['std_confirmpassword' => $std_confirmpassword])->with('successMsg','Email already exist!')->with('failedMsg','Password and Confirm password unmatch!');
            }
        return view('\Sign up\signup', ['std_email' => $std_email])->with('successMsg','Email already exist!');
        }else  if(($std_password) !== ($std_confirmpassword)){
            return view('\Sign up\signup', ['std_password' => $std_password],['std_confirmpassword' => $std_confirmpassword])->with('successMsg','Password and Confirm password unmatch!');
        } else{
        $var = new student;
        $var->std_name=$req->sname;
        $var->std_matric=$req->sid;
        $var->std_address=$req->saddress;
        $var->std_phonenum=$req->sphonenum;
        $var->std_email=$req->std_email;
        $var->std_password=Hash::make($req->std_password);
        $var->std_confirmpassword=Hash::make($req->std_confirmpassword);
        $var->std_faculty=$req->sfaculty;
        $var->std_description=$req->sdescription;
        $var->save();
        
        return redirect('login')->with('successMsg','Sign Up successfully !');	
        }
    }

    function employersignup(Request $req)
    {
        $var = new employer;
        $var->company_name=$req->ename;
        $var->reg_no=$req->e_reg_no;
        $var->company_address=$req->eaddress;
        $var->company_officenum=$req->eofficenum;
        $var->company_faxnumber=$req->efaxnum;
        $var->company_email=$req->eemail;
        $var->company_password=$req->epassword;
        $var->company_confirmpass=$req->econfirmpassword;
        $var->company_size=$req->size;
        $var->company_description=$req->edescription;
        $var->save();
        return redirect('/')->with('successMsg','Please for authentication!');
    }
    
    //Manage Login

    function login(Request $req)
    {
        $role_type = $req->input('role'); 
        if ($role_type === 'student') {
            $email = $req->input('email'); 
            $password = $req->input('password'); 
            $deta = Student::select('std_email','std_password')->where('std_email','LIKE', '%' . $email . '%')->where('std_password','=', $password)->get();
            if (count ( $deta ) >0){
            return redirect('feedback');
            }else{
                return view('\Login\login')->with('failedMsg','Email and password unmatched !');
            }
        }else if ($role_type === 'Company') {
            $email = $req->input('email'); 
            $password = $req->input('password'); 
            $deta = employer::select('company_email','company_password')->where('company_email','LIKE', '%' . $email . '%')->where('company_password','=', $password)->get();
            if (count ( $deta ) >0){
            return redirect('empfeedback');
            }else{
                return view('\Login\login')->with('failedMsg','Email and password unmatched !');
            }
        }else {
            return view('\Sign Up\signup')->with('failedMsg','Login Unsuccessful');
        }
    }

    //Manage Student
    //Create New Student Profile
    function createstudent(Request $req)
    {
        if (request()->has('image') ){
            $imageuploaded = request()->file('image');
            $imagename = time() . '.' . $imageuploaded->getClientOriginalExtension();
            $imagepath = public_path('/');
            $imageuploaded->move($imagepath,$imagename);
        }

        if (request()->has('resume') ){
            $resumeuploaded = request()->file('resume');
            $resumename = time() . '.' . $resumeuploaded->getClientOriginalExtension();
            $resumepath = public_path('/');
            $resumeuploaded->move($resumepath,$resumename);
        }

            //Image::make($image)->resize(300,300)->save(public_path('/uploads/student/'.$filename));*/
            $var = new student;
            $var->std_name=$req->name;
            $var->std_matric=$req->matric;
            $var->std_address=$req->address;
            $var->std_phonenum=$req->phonenum;
            $var->std_email=$req->email;
            $var->std_password=$req->password;
            $var->std_faculty=$req->faculty;
            $var->std_description=$req->description;
            $var->resume='/' . $resumename;
            $var->std_pic = '/' . $imagename;
            $var->save();
            return redirect('searchstdprofile')->with('successMsg','Profile Successful created !');
            }


//Manage Employer
    //Create New Employer Profile
    function createemployer(Request $req)
    {
        if (request()->has('image')){
            $imageuploaded = request()->file('image');
            $imagename = time() . '.' . $imageuploaded->getClientOriginalExtension();
            $imagepath = public_path('/');
            $imageuploaded->move($imagepath,$imagename);
            //Image::make($image)->resize(300,300)->save(public_path('/uploads/student/'.$filename));*/
            $var = new employer;
            $var->company_name=$req->name;
            $var->reg_no=$req->reg;
            $var->company_address=$req->address;
            $var->company_officenum=$req->officenum;
            $var->company_faxnumber=$req->faxnum;
            $var->company_email=$req->email;
            $var->company_password=$req->password;
            $var->company_confirmpass=$req->confirmpassword;
            $var->company_size=$req->company;
            $var->company_description=$req->description;
            $var->company_logo = '/' . $imagename;
            $var->save();
            return redirect('searchempprofile')->with('successMsg','Profile Successful created !');
    
        }

}

function viewstudentlist()
{
    $deta = Student::all();
    return view('\Admin\searchstdprofile',['deta'=>$deta]);
}

public function studentlist(request $request)
{
   
    $deta = $request->input('deta'); 
    $deta = Student::select('std_matric','std_name','std_email','std_phonenum')->where('std_matric','LIKE', '%' . $deta . '%')->orwhere('std_name','LIKE', '%' . $deta . '%')->orwhere('std_email','LIKE', '%' . $deta . '%')->orwhere('std_phonenum','LIKE', '%' . $deta . '%')->get();
    if (count ( $deta ) > 0)
    return view('\Admin\searchstdprofile', ['deta' => $deta])->with('successMsg','Results Found !');
    else
    return view ('\Admin\searchstdprofile', ['deta' => $deta])->with('FailedMsg','No Details found. Try to search again !' );		
    
}

public function deletestudentprofile($id)
{
    $result = Student::select('*')->where('id', '=', $id)->delete();
    return redirect('searchstdprofile')->with('successMsg','Profile Successful deleted !');
}


//employer
function viewemployerlist()
{
    $deta = employer::all();
    return view('\Admin\searchempprofile',['deta'=>$deta]);
}

public function employerlist(request $request)
{
   
    $deta = $request->input('deta'); 
    $deta = employer::select('reg_no','company_name','company_email','company_officenum')->where('reg_no','LIKE', '%' . $deta . '%')->orwhere('company_name','LIKE', '%' . $deta . '%')->orwhere('company_email','LIKE', '%' . $deta . '%')->orwhere('company_officenum','LIKE', '%' . $deta . '%')->get();
    if (count ( $deta ) > 0)
    return view('\Admin\searchempprofile', ['deta' => $deta])->with('successMsg','Results Found !');
    else
    return view ('\Admin\searchempprofile', ['deta' => $deta])->with('FailedMsg','No Details found. Try to search again !' );		
    
}

public function deleteemployerprofile($reg_no)
{
    $result = employer::select('*')->where('reg_no', '=', $reg_no)->delete();
    return redirect('searchempprofile')->with('successMsg','Profile Successful deleted !');
}

function displaystdprofile($id)

{
    $result = student::select('*')->where('id', '=', $id)->get();
    return view('\Admin\displaystdprofile', ['result' => $result]);
}

function displayempprofile($reg_no)

{
    $result = employer::select('*')->where('reg_no', '=', $reg_no)->get();
    return view('\Admin\displayempprofile', ['result' => $result]);
}

public function updateempprofile($reg_no)
{
    $result = employer::select('*')->where('reg_no', '=', $reg_no)->get();
    return view('\Admin\updateempprofile', ['result' => $result]);
}

public function update(Request $request)
{
        $shows = employer::find($request->reg_no);
        $shows->company_name=$request->input('name');
        $shows->company_address=$request->input('address');
        $shows->company_officenum=$request->input('officenum');
        $shows->company_faxnumber=$request->input('faxnum');
        $shows->company_email=$request->input('email');
        $shows->company_size=$request->input('company');
        $shows->company_description=$request->input('description');
        $shows->company_logo = $request->input('image');
        $shows->update();
        return redirect('searchempprofile')->with('successMsg','Profile Successful created !');
}

function showstdprofile($id)

{
    $result = student::select('*')->where('id', '=', $id)->get();
    return view('\Student\showstdprofile', ['result' => $result]);
}

function viewcompanylist()
{
    $deta = employer::all();
    return view('\Student\searchcompany',['deta'=>$deta]);
}

public function companylist(request $request)
{
   
    $deta = $request->input('deta'); 
    $deta = employer::select('reg_no','company_name','company_email','company_officenum')->where('reg_no','LIKE', '%' . $deta . '%')->orwhere('company_name','LIKE', '%' . $deta . '%')->orwhere('company_email','LIKE', '%' . $deta . '%')->orwhere('company_officenum','LIKE', '%' . $deta . '%')->get();
    if (count ( $deta ) > 0)
    return view('\Student\searchcompany', ['deta' => $deta])->with('successMsg','Results Found !');
    else
    return view ('\Student\searchcompany', ['deta' => $deta])->with('FailedMsg','No Details found. Try to search again !' );		
    
}

function displaycompanyprofile($reg_no)

{
    $result = employer::select('*')->where('reg_no', '=', $reg_no)->get();
    return view('\Student\displaycompanyprofile', ['result' => $result]);
}

function destroy(Request $request)
{
    Auth::guard('web')->logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect('/');
}

function viewstdlist()
{
    $deta = Student::all();
    return view('\Employer\searchstudent',['deta'=>$deta]);
}

public function stdlist(request $request)
{
   
    $deta = $request->input('deta'); 
    $deta = Student::select('std_matric','std_name','std_email','std_phonenum')->where('std_matric','LIKE', '%' . $deta . '%')->orwhere('std_name','LIKE', '%' . $deta . '%')->orwhere('std_email','LIKE', '%' . $deta . '%')->orwhere('std_phonenum','LIKE', '%' . $deta . '%')->get();
    if (count ( $deta ) > 0)
    return view('\Employer\searchstudent', ['deta' => $deta])->with('successMsg','Results Found !');
    else
    return view ('\Employer\searchstudent', ['deta' => $deta])->with('FailedMsg','No Details found. Try to search again !' );		
    
}

function displaystudentprofile($id)

{
    $result = student::select('*')->where('id', '=', $id)->get();
    return view('\Employer\displaystudentprofile', ['result' => $result]);
}

}

