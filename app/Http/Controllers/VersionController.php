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
        $version_number = 1;
        if(!empty($version)){
            $version_number = $version->number;
        }

        $version_DTO = array();
        $version_DTO["version"] = $version_number;

        return $version_DTO;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $version = Version::latest()->first();
        $version_number = 1;
        if(!empty($version)){
            $version_number = $version->number;
            $version_number++;
        }

        $version = new Version;
        $version->number = $version_number;
        $version->save();

        return "success";
    }
}
