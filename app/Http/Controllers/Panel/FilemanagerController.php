<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\MediaFile;
use App\Models\MenuPanel;
use App\Models\SubmenuPanel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class FilemanagerController extends Controller
{
    public function index(Request $request){
        $thispage       = [
            'title'   => 'مدیریت فایل ها',
            'list'    => 'لیست فایل ها',
            'add'     => 'افزودن فایل ها',
            'create'  => 'ایجاد فایل ها',
            'enter'   => 'ورود فایل ها',
            'edit'    => 'ویرایش فایل ها',
            'delete'  => 'حذف فایل ها',
        ];
        $menupanels     = Menupanel::select('id','priority','icon', 'title','label', 'slug', 'status' , 'class' , 'controller')->get();
        $submenupanels  = Submenupanel::select('id','priority', 'title','label', 'slug', 'status' , 'class' , 'controller' , 'menu_id')->get();
        $mediafiles     = MediaFile::all();

        if ($request->ajax()) {
            $data = MediaFile::all();

            return Datatables::of($data)
                ->addColumn('file_path', function ($data) {
                    $fileUrl = asset('storage/' . $data->file_path);

                    if ($data->type === 'image') {
                        return '<img src="' . $fileUrl . '" alt="تصویر" style="width: 80px; height: auto;">';
                    } elseif ($data->type === 'audio') {
                        return '<audio controls style="width: 150px;"><source src="' . $fileUrl . '" type="audio/mpeg">مرورگر شما از پخش صوت پشتیبانی نمی‌کند.</audio>';
                    } elseif ($data->type === 'videos') {
                        return '<video width="160" height="90" controls><source src="' . $fileUrl . '" type="video/mp4">مرورگر شما از پخش ویدیو پشتیبانی نمی‌کند.</video>';
                    } else {
                        return 'نامشخص';
                    }
                })
                ->addColumn('name', function ($data) {
                    return ($data->name);
                })
                ->addColumn('type', function ($data) {
                    return match ($data->type) {
                        'image'        => 'عکس',
                        'video'        => 'ویدیویی',
                        'audio'        => 'صوتی',
                        'document'     => 'سند متنی',
                        'spreadsheet'  => 'اکسل',
                        'presentation' => 'پاورپوینت',
                        'other'        => 'سایر',
                        default         => 'نامشخص',
                    };
                })
                ->addColumn('size', function ($data) {
                    $sizeInBytes = $data->size;

                    if ($sizeInBytes >= 1073741824) {
                        return number_format($sizeInBytes / 1073741824, 2) . ' GB';
                    } elseif ($sizeInBytes >= 1048576) {
                        return number_format($sizeInBytes / 1048576, 2) . ' MB';
                    } elseif ($sizeInBytes >= 1024) {
                        return number_format($sizeInBytes / 1024, 2) . ' KB';
                    } else {
                        return $sizeInBytes . ' B';
                    }
                    //return ($data->size);
                })
                ->addColumn('date', function ($data) {
                    return (jdate($data->updated_at)->format('Y/m/d'));
                })
                ->editColumn('action', function ($data) {
                    $actionBtn = '<button type="button" data-bs-toggle="modal" data-bs-target="#editModal'.$data->id.'" class="btn btn-sm btn-icon btn-outline-primary" ><i class="mdi mdi-pencil-outline"></i></button>
                    <button class="btn btn-sm btn-icon btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal'.$data->id.'" id="#deletesubmit_'.$data->id.'" data-id="#deletesubmit_'.$data->id.'"><i class="mdi mdi-delete-outline"></i></button>';
                    return $actionBtn;
                })
                ->rawColumns(['action' ,'file_path'])
                ->make(true);
        }
        return view('panel.file_manager')->with(compact(['menupanels' , 'submenupanels' , 'mediafiles','thispage']));
    }

    public function store(Request $request)
    {

        //dd($request->all());
        //dd($request->file('file'));

        $request->validate([
            'file' => 'required|file|max:102400',
        ]);

        $file           = $request->file('file');
        $subject_id     = $request->input('subject_id');
        $originalName   = $file->getClientOriginalName();
        $extension      = $file->getClientOriginalExtension();
        $size           = $file->getSize();
        $project_id     = $request->input('record_id');
        $company_id     = $request->input('record_id');
        $mime = $request->file('file')->getMimeType();

        $type = match (true) {
            Str::contains($mime, 'image')                            => 'images',
            Str::contains($mime, 'video')                            => 'videos',
            Str::contains($mime, 'audio')                            => 'audios',
            $mime === 'application/pdf'                                     => 'documents',
            Str::contains($mime, 'msword')                           => 'documents', // doc
            Str::contains($mime, 'officedocument.wordprocessingml')  => 'documents', // docx
            Str::contains($mime, 'ms-excel')                         => 'spreadsheets', // xls
            Str::contains($mime, 'officedocument.spreadsheetml')     => 'spreadsheets', // xlsx
            Str::contains($mime, 'ms-powerpoint')                    => 'presentations', // ppt
            Str::contains($mime, 'officedocument.presentationml')    => 'presentations', // pptx
            Str::contains($mime, 'zip') || $mime === 'application/zip'              => 'archives', // zip
            Str::contains($mime, 'rar') || $mime === 'application/x-rar-compressed' => 'archives', // rar
            default                                                  => 'others',
        };
        $fileName = uniqid() . '.' . $extension;
        if ($request->input('record_id')){
            $path = $file->storeAs("uploads/".$request->input('record_id').'/'.$type, $fileName, 'public');

        }else {
            $path = $file->storeAs("uploads/" . $type, $fileName, 'public');
        }

        MediaFile::create([
            'subject_id'    => $subject_id,
            'name'          => $fileName,
            'original_name' => $originalName,
            'type'          => rtrim($type, 's'),
            'file_path'     => $path,
            'size'          => $size,
            'project_id'    => $project_id,
            'company_id'    => $company_id,
            'mime'          => $mime,
            'user_id'       => Auth::user()->id,
        ]);

        return Redirect::back();
    }

    public function destroy(Request $request)
    {
        try{
            $media = MediaFile::findOrFail($request->input('id'));
            Storage::disk('public')->delete($media->file_path);
//            if (Storage::exists($media->file_path)) {
//                Storage::delete($media->file_path);
//            }
            $result = $media->delete();

            if ($result) {
                $success = true;
                $flag = 'success';
                $subject = 'عملیات موفق';
                $message = 'اطلاعات با موفقیت پاک شد';
            }elseif($result != true) {
                $success = false;
                $flag    = 'error';
                $subject = 'عملیات نا موفق';
                $message = 'اطلاعات زیرمنو ثبت نشد، لطفا مجددا تلاش نمایید';
            }
        } catch (Exception $e) {
            $success = false;
            $flag    = 'error';
            $subject = 'خطا در ارتباط با سرور';
            $message = 'اطلاعات پاک نشد،لطفا بعدا مجدد تلاش نمایید ';
        }
        return response()->json(['success'=>$success , 'subject' => $subject, 'flag' => $flag, 'message' => $message]);
    }

    public function selectfile(Request $request)
    {

        $thispage       = [
            'title'   => 'مدیریت فایل ها',
            'list'    => 'لیست فایل ها',
            'add'     => 'افزودن فایل ها',
            'create'  => 'ایجاد فایل ها',
            'enter'   => 'ورود فایل ها',
            'edit'    => 'ویرایش فایل ها',
            'delete'  => 'حذف فایل ها',
        ];

        $recordId = $request->record_id;
        $files = MediaFile::where('project_id', $recordId)->get();
        //dd($files);
        return view('panel.files', compact('files' , 'thispage'));
    }

    public function deletefile($id)
    {
        $file = MediaFile::findOrFail($id);

        // حذف فایل از storage
        if (Storage::exists($file->file_path)) {
            Storage::delete($file->file_path);
        }
        // حذف از دیتابیس
        $file->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }

    public function filestatus(Request $request)
    {
        try{
        $file           = MediaFile::whereId($request->input('id'))->first();
        $file->status   = $request->input('status');
        $result         = $file->save();
        if ($result) {
            $success = true;
            $flag = 'success';
            $subject = 'عملیات موفق';
            $message = 'اطلاعات با موفقیت پاک شد';
        }elseif($result != true) {
            $success = false;
            $flag    = 'error';
            $subject = 'عملیات نا موفق';
            $message = 'اطلاعات زیرمنو ثبت نشد، لطفا مجددا تلاش نمایید';
        }
    } catch (Exception $e) {
        $success = false;
        $flag    = 'error';
        $subject = 'خطا در ارتباط با سرور';
        $message = 'اطلاعات پاک نشد،لطفا بعدا مجدد تلاش نمایید ';
        }
    return response()->json(['success'=>$success , 'subject' => $subject, 'flag' => $flag, 'message' => $message]);
    }
}
