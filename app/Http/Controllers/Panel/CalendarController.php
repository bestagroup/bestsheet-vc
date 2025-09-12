<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Calendar;
use Morilog\Jalali\Jalalian;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(){
        $thispage       = [
            'title'   => 'مدیریت رویداد تقویم',
            'list'    => 'لیست رویداد تقویم',
            'add'     => 'افزودن رویداد تقویم',
            'create'  => 'ایجاد رویداد تقویم',
            'enter'   => 'ورود رویداد تقویم',
            'edit'    => 'ویرایش رویداد تقویم',
            'delete'  => 'حذف رویداد تقویم',
        ];

        $users = User::select('id', 'name' , 'gender')->get();

        return view('panel.calendar')->with(compact('thispage' , 'users'));
    }
    public function getEvents()
    {
        $calendars = Calendar::all();

        $events = $calendars->map(function ($calendar) {
            $start = Jalalian::fromFormat('Y-m-d H:i:s', $calendar->start)->toCarbon(); // شمسی -> Carbon (میلادی)
            $end   = Jalalian::fromFormat('Y-m-d H:i:s', $calendar->end)->toCarbon();
            return [
                'id'    => $calendar->id,
                'title' => $calendar->title,
                'start' => $start->format('Y-m-d\TH:i:s'),
                'end'   => $end->format('Y-m-d\TH:i:s'),
                'allDay' => (bool) $calendar->all_day,
                'url'   => $calendar->url,
                'extendedProps' => [
                    'calendar'    => $calendar->label,
                    'location'    => $calendar->location,
                    'description' => $calendar->description,
                    'guests'      => $calendar->guests,
                ],
            ];
        });


        return response()->json($events);
    }


    public function store(Request $request)
    {
        $calendar = Calendar::create([
            'title'       => $request->eventTitle,
            'label'       => $request->eventLabel,
            'start'       => $request->eventStartDate,
            'end'         => $request->eventEndDate,
            'all_day'     => $request->allDay ? true : false,
            'url'         => $request->eventURL,
            'location'    => $request->eventLocation,
            'description' => $request->eventDescription,
            'guests'      => $request->eventGuests ?? [],
        ]);

        // داده مناسب FullCalendar
        $event = [
            'id'    => $calendar->id,
            'title' => $calendar->title,
            'start' => $calendar->start,
            'end'   => $calendar->end,
            'allDay' => (bool) $calendar->all_day,
            'url'   => $calendar->url,
            'extendedProps' => [
                'calendar'    => $calendar->label,
                'location'    => $calendar->location,
                'description' => $calendar->description,
                'guests'      => $calendar->guests,
            ],
        ];

        return response()->json(['success' => true, 'subject' => 'عملیات موفق', 'flag'    => 'success', 'message' => 'رویداد با موفقیت ثبت شد', 'event'   => $event]);
    }


    public function update(Request $request, Calendar $calendar)
    {
        $calendar->update($request->all());
        return response()->json($calendar);
    }

    // حذف
    public function destroy(Calendar $calendar)
    {
        $calendar->delete();
        return response()->json(['status' => 'deleted']);
    }
}
