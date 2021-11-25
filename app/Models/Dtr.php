<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dtr extends Model
{	
    protected $guarded = ['id'];

    protected $table = 'dtr';

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function get_scheduled_latein_dtr($userId, $day, $batch, $type)
    {
        switch ($day) {
            case 'Mon':
                $day = 'monday';
                break;
            
            case 'Tue':
                $day = 'tuesday';
                break;

            case 'Wed':
                $day = 'wednesday';
                break;

            case 'Thu':
                $day = 'thursday';
                break;
            
            case 'Fri':
                $day = 'friday';
                break;

            case 'Sat':
                $day = 'saturday';
                break;

            case 'Sun':
                $day = 'sunday';
                break;  

            default:
                $day = $day;
                break;
        }

        if ($type == 'staff') {
            $query = \DB::table('dtr_time_days')->distinct('dtr_time_days.time_from')
            ->join('dtr_time_settings', 'dtr_time_settings.id', '=', 'dtr_time_days.dtr_time_settings_id')
            ->join('schedules', 'schedules.id', '=', 'dtr_time_settings.schedule_id')
            ->join('enrollments_staffs', 'enrollments_staffs.schedule_id', '=', 'schedules.id')
            ->join('staffs', 'enrollments_staffs.staff_id', '=', 'staffs.id')
            ->join('dtr_logs', 'staffs.user_id', '=', 'dtr_logs.user_id')
            ->where([
                'dtr_time_settings.name' => 'LATE_IN',
                'dtr_time_days.day' => $day,
                'dtr_logs.user_id' => $userId,
                'enrollments_staffs.batch_id' => $batch,
                'enrollments_staffs.is_active' => 1
            ])
            ->get();
        } else {
            $query = \DB::table('dtr_time_days')->distinct('dtr_time_days.time_from')
            ->join('dtr_time_settings', 'dtr_time_settings.id', '=', 'dtr_time_days.dtr_time_settings_id')
            ->join('schedules', 'schedules.id', '=', 'dtr_time_settings.schedule_id')
            ->join('enrollments', 'enrollments.schedule_id', '=', 'schedules.id')
            ->join('students', 'enrollments.student_no', '=', 'students.identification_no')
            ->join('dtr_logs', 'students.user_id', '=', 'dtr_logs.user_id')
            ->where([
                'dtr_time_settings.name' => 'LATE_IN',
                'dtr_time_days.day' => $day,
                'dtr_logs.user_id' => $userId,
                'enrollments.batch_id' => $batch,
                'enrollments.status' => 'admitted',
                'enrollments.is_active' => 1
            ])
            ->get();
        }

        $results = 0;

        if($query->count() > 0)
        {
            $results = $query->first()->time_from;
        }
        else
        {
            return $results;
        }    
    }
}

