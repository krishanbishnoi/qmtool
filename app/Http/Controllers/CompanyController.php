<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
    	$data = Company::all();
    	//return "aa";
    	return view('company.list',compact('data'));
    }
}
