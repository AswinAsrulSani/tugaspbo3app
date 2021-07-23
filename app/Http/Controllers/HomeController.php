<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\sysuser;

class HomeController extends Controller
{
    public function index(request $request)
    {
         $categories = sysmenu::where('sysmenu_id','=','1')
        ->with('childrenCategories')
        ->get();
        //dd($categories);
        //return view('layout.app');
        return view('layout.app',['data_menu'=>$categories]);
    }
    public function login(request $request)
    {
        $session = $request->session()->exists('userid');
        if(!$session){
            return view('auth.login');
        }else{
            return redirect('/');
        }
    }
    public function masuk(Request $request)
    {    
        $user_name  = $request->input('txtuser');
        $pwd        = sha1($request->input('txtpass'));
        $sys_user = new sysuser();
        $data = $sys_user::where([
            ['uname', '=', $user_name],['upass', '=', $pwd]
            ])->get();
        $user = NULL;
        foreach ($data as $key => $value) {
            $user = $value->uname;
            $nama = $value->namalengkap;
            $email = $value->email;
        }
        if($user){
            session([
                'userid'=> $user,
                'nama'=> $nama,
                'email'=> $email
            ]);
            $session = $request->session()->get('userid');
            if($session){
                return redirect('/');
            }
        }else{
            return redirect('/');
        }
    }

}
