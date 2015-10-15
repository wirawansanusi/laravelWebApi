<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

// Using Entity Models
use App\Version;

class VersionController extends Controller
{

    /**
     * Check user credentials.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $version = Version::latest()->first();
        $version_number = 0;
        if(!empty($version)){
            $version_number = $version->number;
        }
        return $version_number;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dateformat = date("YmdHis");

        $version = new Version;
        $version->number = $dateformat;
        $version->save();

        return "success";
    }
}
