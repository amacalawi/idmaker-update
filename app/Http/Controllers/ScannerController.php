<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AuditLog;
use App\Batch;
use App\Dtr;
use App\DtrLog;
// use App\DtrLogSubject;
use App\DtrTimeSetting;
use App\Device;
use App\Calendar;
use App\CalendarSection;
use App\CalendarSetting;
use App\OtpRequest;
// use App\Subject;
use App\Staff;
use App\IDMaker;
use App\Student;
use App\Models\User;
Use DB; 

class ScannerController extends Controller
{
    
    public function __construct()
    {
        date_default_timezone_set('Asia/Manila');
    }
    
    private function config() 
    {
        $config = (object) array(
            // 'url' => '192.168.1.200',
            'url' => '127.0.0.1',
            'restriction' => 0
        );

        return $config;
    }

    public function index(Request $request)
    {       
        $timestamp = date('Y-m-d H:i:s');
        $batch =  Batch::where('status', 'Current')->first()->id;
        $exist = IDMaker::where([
            'identification_no' => $request->get('id'),
            'batch_id' => $batch,
            'is_active' => 1
        ])->get();

        if ($exist->count() > 0) {
            $exist = $exist->first();
            $idmaker = IDMaker::find($exist->id);
            if ($request->get('type') == 'printed') {
                $idmaker->print_delivery_status = 'printed';
                $idmaker->printed_time = $timestamp;
            } else {
                $idmaker->print_delivery_status = 'delivered';
                $idmaker->delivered_time = $timestamp;
            }
            $idmaker->updated_at = $timestamp;
            $idmaker->updated_by = 1;
            $idmaker->is_active = 1;
            if ($idmaker->update()) {
                $this->audit_logs('groups_users', $exist->id, 'has modified a status on idmaker via idmaker update.', IDMaker::find($exist->id), $timestamp, 1);
            }
            $data = array(
                'data' => $request->get('id'),
                'message' => 'the user was successfully updated.',
                'type'    => 'success',
            );

            echo json_encode( $data ); exit();
        } else {
            $data = array(
                'data' => $request->get('id'),
                'message' => 'the user was not successfully updated.',
                'type'    => 'failed',
            );

            echo json_encode( $data ); exit();
        }
    }

    public function audit_logs($entity, $entity_id, $description, $data, $timestamp, $user)
    {
        $auditLogs = AuditLog::create([
            'entity' => $entity,
            'entity_id' => $entity_id,
            'description' => $description,
            'data' => json_encode($data),
            'created_at' => $timestamp,
            'created_by' => $user
        ]);

        return true;
    }
}