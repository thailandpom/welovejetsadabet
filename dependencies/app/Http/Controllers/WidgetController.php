<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

//Importing laravel-permission models

//Enables us to output flash messaging

class WidgetController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth']); //isAdmin middleware lets only users with a //specific permission permission to access these resources
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function customize($id)
    {
        $pages = DB::table('pages')->where('page_id', $id)->first();
        $widgets = DB::table('widget')->where('widget.page_fk_id', $id)->orderBy('order_widget', 'asc')->get();
        return view('widget.index')->with('name', 'Pages')
            ->with('pages', $pages)
            ->with('widgets', $widgets)
            ->with('page_id', $id);
    }

    public function create($id, $widgetType)
    {
        return view('widget.create')
            ->with('name', 'Pages')
            ->with('page_id', $id)
            ->with('widgetType', $widgetType);
    }

    public function store(Request $request)
    {

        $page_id = $request->page_fk_id;
        $widget_type = $request->widget_type;
        $col = $request->amount_column;

        $Orderwidgets = DB::table('widget')->where('page_fk_id', $page_id)->orderBy('order_widget', 'desc')->get();
        if (count($Orderwidgets)) {
            $orderList = $Orderwidgets[0]->order_widget + 1;
        } else {
            $orderList = 1;
        }

        $widget = DB::table('widget')->insertGetId(
            [
                'page_fk_id' => $page_id,
                'widget_name' => $request->widget_name,
                'widget_type' => $widget_type,
                'amount_column' => $col,
                'bg_color' => $request->bg_color,
                'status' => 1,
                'order_widget' => $orderList,
                "created_at" => \Carbon\Carbon::now(),
                "updated_at" => \Carbon\Carbon::now(),
            ]);
        if ($widget_type == 1) {
            for ($i = 1; $i <= $col; $i++) {
                DB::table('widget_content')->insert(
                    [
                        'widget_fk_Id' => $widget,
                        'content' => $request['content' . $i],
                    ]);
            }
        } else if ($widget_type == 2) {

            for ($i = 1; $i <= $col; $i++) {
                if ($request->hasFile('file' . $i)) {
                    $imageFile[$i] = $request->file('file' . $i);
                    $type[$i] = $imageFile[$i]->getClientOriginalExtension();
                    if ($type[$i] != "") {
                        $imageName[$i] = uniqid() . "." . preg_replace('/\s+/', '', $imageFile[$i]->getClientOriginalExtension());
                        $imageFile[$i]->move(base_path('/../media/widget'), preg_replace('/\s+/', '', $imageName[$i]));

                        DB::table('widget_content')->insert(
                            [
                                'widget_fk_Id' => $widget,
                                'image' => $imageName[$i],
                                'link' => $request['link' . $i],
                                'img_alt' => $request['img_alt' . $i],
                            ]);
                    }
                }
            }
        } else if ($widget_type == 3) {
            for ($i = 1; $i <= $col; $i++) {
                DB::table('widget_content')->insert(
                    [
                        'widget_fk_Id' => $widget,
                        'youtube_link' => $request['youtube_link' . $i],
                    ]);
            }
        } else if ($widget_type == 4) {
            if ($request->hasFile('file')) {
                $imageFile = $request->file('file');
                $type = $imageFile->getClientOriginalExtension();
                if ($type != "") {
                    $imageName = uniqid() . "." . preg_replace('/\s+/', '', $imageFile->getClientOriginalExtension());
                    $imageFile->move(base_path('/../media/widget'), preg_replace('/\s+/', '', $imageName));
                    DB::table('widget_content')->insert(
                        [
                            'widget_fk_Id' => $widget,
                            'content' => $request->content,
                            'image' => $imageName,
                            'link' => $request->link,
                            'img_alt' => $request->img_alt,
                        ]);
                }
            }
        } else if ($widget_type == 5) {

            DB::table('widget_content')->insert(
                [
                    'widget_fk_Id' => $widget,
                    'content' => $request->content,
                    'youtube_link' => $request->youtube_link,
                ]);
        } else if ($widget_type == 6) {
            if ($request->hasFile('file')) {
                $imageFile = $request->file('file');
                $type = $imageFile->getClientOriginalExtension();
                if ($type != "") {
                    $imageName = uniqid() . "." . preg_replace('/\s+/', '', $imageFile->getClientOriginalExtension());
                    $imageFile->move(base_path('/../media/widget'), preg_replace('/\s+/', '', $imageName));
                    DB::table('widget_content')->insert(
                        [
                            'widget_fk_Id' => $widget,
                            'image' => $imageName,
                            'link' => $request->link,
                            'img_alt' => $request->img_alt,
                            'youtube_link' => $request->youtube_link,
                        ]);
                }
            }
        }

        return redirect()->route('pages_customize', $page_id)->with('flash_message', 'เพิ่มวิดเจ็ทเรียบร้อยแล้ว!!!');
    }

    public function edit($id)
    {
        $widgets = DB::table('widget')->where('widget.widget_id', $id)
            ->orderBy('order_widget', 'asc')
            ->first();
        $widget_contents = DB::table('widget_content')
            ->where('widget_content.widget_fk_Id', $id)
            ->get();

        $page_id = $widgets->page_fk_id;
        $widget_type = $widgets->widget_type;

        return view('widget.edit')
            ->with('name', 'Pages')
            ->with('page_id', $page_id)
            ->with('widgetType', $widget_type)
            ->with('widgets', $widgets)
            ->with('widget_contents', $widget_contents);
    }

    public function update(Request $request)
    {
        $widget_id = $request->widget_id;
        $page_id = $request->page_fk_id;
        $widget_type = $request->widget_type;
        $col = $request->amount_column;

        DB::table('widget')->where('widget_id', "=", $widget_id)->update(array(
            'widget_name' => $request->widget_name,
            'amount_column' => $col,
            'bg_color' => $request->bg_color,
            "updated_at" => \Carbon\Carbon::now(),
        ));

        if ($widget_type == 1) {
            for ($i = 1; $i <= $col; $i++) {
                DB::table('widget_content')->where('widget_fk_Id', "=", $widget_id)->update(array(
                    'content' => $request['content' . $i],
                ));
            }
        } else if ($widget_type == 2) {
            for ($i = 1; $i <= $col; $i++) {
                if ($request->hasFile('file' . $i)) {
                    $imageFile[$i] = $request->file('file' . $i);
                    $type[$i] = $imageFile[$i]->getClientOriginalExtension();
                    if ($type[$i] != "") {
                        $imageName[$i] = uniqid() . "." . preg_replace('/\s+/', '', $imageFile[$i]->getClientOriginalExtension());
                        $imageFile[$i]->move(base_path('/../media/widget'), preg_replace('/\s+/', '', $imageName[$i]));

                        DB::table('widget_content')->where('widget_fk_Id', "=", $widget_id)->update(array(
                            'image' => $imageName[$i],
                            'link' => $request['link' . $i],
                            'img_alt' => $request['img_alt' . $i],
                        ));
                    }
                } else {
                    DB::table('widget_content')->where('widget_fk_Id', "=", $widget_id)->update(array(
                        'link' => $request['link' . $i],
                        'img_alt' => $request['img_alt' . $i],
                    ));
                }
            }
        } else if ($widget_type == 3) {
            for ($i = 1; $i <= $col; $i++) {
                DB::table('widget_content')->where('widget_fk_Id', "=", $widget_id)->update(array(
                    'youtube_link' => $request['youtube_link' . $i],
                ));
            }
        } else if ($widget_type == 4) {
            if ($request->hasFile('file')) {
                $imageFile = $request->file('file');
                $type = $imageFile->getClientOriginalExtension();
                if ($type != "") {
                    $imageName = uniqid() . "." . preg_replace('/\s+/', '', $imageFile->getClientOriginalExtension());
                    $imageFile->move(base_path('/../media/widget'), preg_replace('/\s+/', '', $imageName));
                    DB::table('widget_content')->where('widget_fk_Id', "=", $widget_id)->update(array(
                        'content' => $request->content,
                        'image' => $imageName,
                        'link' => $request->link,
                        'img_alt' => $request->img_alt,
                    ));
                }
            } else {
                DB::table('widget_content')->where('widget_fk_Id', "=", $widget_id)->update(array(
                    'content' => $request->content,
                    'link' => $request->link,
                    'img_alt' => $request->img_alt,
                ));
            }
        } else if ($widget_type == 5) {
            DB::table('widget_content')->where('widget_fk_Id', "=", $widget_id)->update(array(
                'content' => $request->content,
                'youtube_link' => $request->youtube_link,
            ));
        } else if ($widget_type == 6) {
            if ($request->hasFile('file')) {
                $imageFile = $request->file('file');
                $type = $imageFile->getClientOriginalExtension();
                if ($type != "") {
                    $imageName = uniqid() . "." . preg_replace('/\s+/', '', $imageFile->getClientOriginalExtension());
                    $imageFile->move(base_path('/../media/widget'), preg_replace('/\s+/', '', $imageName));
                    DB::table('widget_content')->where('widget_fk_Id', "=", $widget_id)->update(array(
                        'image' => $imageName,
                        'link' => $request->link,
                        'img_alt' => $request->img_alt,
                        'youtube_link' => $request->youtube_link,
                    ));
                }
            } else {
                DB::table('widget_content')->where('widget_fk_Id', "=", $widget_id)->update(array(
                    'link' => $request->link,
                    'img_alt' => $request->img_alt,
                    'youtube_link' => $request->youtube_link,
                ));
            }

        }

        return redirect()->route('pages_customize', $page_id)->with('flash_message', 'แก้ไขวิดเจ็ทเรียบร้อยแล้ว!!!');
    }

    public function destroy($id)
    {

        $widgets = DB::table('widget')->where('widget_id', $id)->first();
        $page_id = $widgets->page_fk_id;
        DB::table('widget')->where('widget_id', $id)->delete();
        DB::table('widget_content')->where('widget_fk_Id', $id)->delete();
        return redirect()->route('pages_customize', $page_id)->with('flash_message', 'ลบวิดเจ็ทเรียบร้อยแล้ว!!!');
    }

    public function update_order(Request $request)
    {
        $HomeIds = array_filter(explode(",", $request->home_id));
        $HomeOrders = array_filter(explode(",", $request->home_order));
        foreach ($HomeIds as $HomeId => $value) {
            DB::table('widget')->where('widget_id', '=', $value)->update(['order_widget' => $HomeOrders[$HomeId]]);
        }

        return response()->json([
            'order' => $request->home_order,
        ], 200);

    }
}
