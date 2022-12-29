<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Image;

class PostController extends Controller
{
    function createjob(Request $req)
    {

            //Image::make($image)->resize(300,300)->save(public_path('/uploads/student/'.$filename));*/
            $var = new post;
            $var->job_title=$req->job_title;
            $var->job_venue=$req->job_venue;
            $var->job_address=$req->job_address;
            $var->job_salary=$req->job_salary;
            $var->job_category=$req->job_category;
            $var->job_description=$req->job_description;
            $var->job_benefit=$req->job_benefit;
            $var->job_highlight=$req->job_highlight;
            $var->job_requirement=$req->job_requirement;
            $var->save();
            return redirect('createjobpost')->with('successMsg','Job Post Successfully !');
            }
}
