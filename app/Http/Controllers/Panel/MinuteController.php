<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Minute;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MinuteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Minute::where('project_id' , $request->id)->get();
            return Datatables::of($data)
                ->addColumn('title', function ($data) {
                    return ($data->title ?? '');
                })
                ->addColumn('date', function ($data) {
                    return ($data->date ?? '');
                })
                ->addColumn('type', function ($data) {
                    return ($data->type ?? '');
                })
                ->addColumn('file_path', function ($data) {
                    $fileUrl = asset('storage/' . $data->file_path);

                    if ($data->type === 'image') {
                        return '<img src="' . $fileUrl . '" alt="تصویر" style="width: 80px; height: auto;">';
                    } elseif ($data->type === 'audio') {
                        return '<audio controls style="width: 150px;"><source src="' . $fileUrl . '" type="audio/mpeg">مرورگر شما از پخش صوت پشتیبانی نمی‌کند.</audio>';
                    } elseif ($data->type === 'videos') {
                        return '<video width="160" height="90" controls><source src="' . $fileUrl . '" type="video/mp4">مرورگر شما از پخش ویدیو پشتیبانی نمی‌کند.</video>';
                    } else {
                        return '<a href="' . $fileUrl . '" target="_blank">' . 'دانلود فایل' . '</a>';
                    }
                })
                ->rawColumns(['file_path'])
                ->make(true);
        }
        return view('panel.company');
    }

    public function store(Request $request)
    {

        try {
            $minute = new Minute();
            $minute->title       = $request->input('title');
            $minute->date        = $request->input('date');
            $minute->type        = $request->input('type');
            $minute->file_path   = $request->input('file_path');
            $minute->company_id  = $request->input('project_id');
            $minute->project_id  = $request->input('project_id');

            $result = $minute->save();

            if ($result == true) {
                $success = true;
                $flag    = 'success';
                $subject = 'عملیات موفق';
                $message = 'اطلاعات زیرمنو با موفقیت ثبت شد';
            }
            elseif($result != true) {
                $success = false;
                $flag    = 'error';
                $subject = 'عملیات نا موفق';
                $message = 'اطلاعات زیرمنو ثبت نشد، لطفا مجددا تلاش نمایید';
            }

        } catch (Exception $e) {

            $success = false;
            $flag    = 'error';
            $subject = 'خطا در ارتباط با سرور';
            //$message = strchr($e);
            $message = 'اطلاعات زیرمنو ثبت نشد،لطفا بعدا مجدد تلاش نمایید ';
        }

        return response()->json(['success'=>$success , 'subject' => $subject, 'flag' => $flag, 'message' => $message]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
