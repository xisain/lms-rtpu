<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\course;
use App\Http\Resources\courseResource;

class CourseAPI extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $course = course::where('public',true)->latest()->get();
        return new courseResource(true, 'List Course', $course);
    }
    public function show(){

    }


}
