<?php

namespace App\Http\Controllers;

use App;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendmailContact;

class FrontendController extends Controller
{

  public function getPage($slug) 
  {
    $pages = DB::table('pages')->where('slug', $slug)->first();
      return response()->json([
          'pages' => $pages,
      ], 200);
  }

  public function getContent($slug) 
  {
    $pages = DB::table('pages')->where('slug', $slug)->first();
    $page_id = $pages->page_id;
    $widgets = DB::table('widget')
        ->join('widget_content as wm', 'widget.widget_id', '=', 'wm.widget_fk_Id')
        ->where('widget.page_fk_id', $page_id)
        ->select('widget.*', 'wm.title', 'wm.content','wm.image', 'wm.link', 'wm.img_alt', 'wm.color_title', 'bg_color')
        ->orderBy('order_widget', 'asc')
        ->get();
      return response()->json([
          'widgets' => $widgets,
      ], 200);
  }

  public function getContact() 
  {
    $contacts = DB::table('contact')->get();
      return response()->json([
          'contacts' => $contacts,
      ], 200);
  }


}