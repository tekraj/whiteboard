<?php

namespace App\Http\Controllers;

use App\Repositories\SessionRepository;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\File;
use SPSS\Sav\Reader;
use SPSS\Sav\Writer;
use View;
class UtilityController extends Controller
{
    public function saveCanvasImage(Request $request, $type)
    {

        $user = Auth::guard($type)->user();
        $time = time();
        $base64 = $request->imageData;
        $image_base64 = base64_decode($base64);
        if (!realpath(storage_path("uploads/{$type}s/canvas_images/{$user->id}"))) {
            mkdir(storage_path("uploads/{$type}s/canvas_images/{$user->id}/"), 777);
            @chmod(storage_path("uploads/{$type}s/canvas_images/{$user->id}/"),777);
        }
        $uploadDir = "{$type}s/canvas_images/{$user->id}/{$time}_{$user->id}_canvas_image.png";

        $file = storage_path("uploads/{$uploadDir}");
        file_put_contents($file, $image_base64);
        $uploadPath = preg_replace('/\/+/', '-', $uploadDir);
        return response()->json(['status' => true, 'file' => url("storage/{$uploadPath}")]);
    }

    public function readSavFile(Request $request,$type)
    {
        $user = Auth::guard($type)->user();
        if ($file = $request->file('sav-file')) {
            $ext = $file->getClientOriginalExtension();
            $file_name = 'read-sav-file' . time() . '.' . $ext;
            $dest = storage_path("uploads/sav_files/{$type}s/{$user->id}/");
            if(!realpath($dest)){
                mkdir($dest);
                @chmod($dest,777);
            }
            $data = [];
            if ($file->move($dest, $file_name)) {
                $savFile = Reader::fromFile($dest.$file_name);
                $savData = $savFile->data;
                foreach($savData[0] as $d){
                    $data[] = json_decode($d);
                }
                File::delete($dest.$file_name);
            }
            return response()->json(['status'=>true,'data'=>$data]);
        }else{
            return response()->json(['status'=>false]);
        }
    }

    public function saveCanvasToSav(Request $request,$type)
    {
        $user = Auth::guard($type)->user();
        $data =[];

        foreach($request->data as $key=>$row){
            $json = json_encode($row);
            $data[] = [
                    'name' => 'VAR'.$key,
                    'width' => strlen($json),
                    'columns' => 1,
                    'align' => 1,
                    'measure' => 1,
                    'type'=>'String',
                    'data' => [$json]
                ];
        }

        $writer = new Writer([
            'header' => [
                'prodName' => 'canvas-to-sav',
                'layoutCode' => 2,
                'compression' => 1,
                'weightIndex' => 0,
                'bias' => 100,
                'creationDate' => date('Y-m-d'),
                'creationTime' => date('H:i:s'),
            ],
            'variables' => $data
        ]);
        $dest = storage_path("uploads/sav_files/{$type}s/{$user->id}/");
        if(!realpath($dest)){
            mkdir($dest);
            @chmod($dest,777);
        }
        $fileName = 'write_sav_file_'.time().'.sav';
        $writer->save($dest.$fileName);
        return response()->json(['status'=>true,'file'=>"uploads/sav_files/{$type}s/{$user->id}/{$fileName}"]);
    }

   public function sendReportToTechTeam(Request $request , $type){
        $user = Auth::guard($type);

   }

   public function searchSessionLogs(Request $request,$type){
        $user = Auth::guard($type)->user();
        $sessions = [];
        $filters = $request->all();
        if($type=='student'){
            $subjectId = session('student_subject');
            $sessions = SessionRepository::getStudentSessions($user->id,$subjectId,$filters);
        }else if($type=='tutors'){
            $sessions = SessionRepository::getStudentSessions($user->id,$user->session_id,$filters);
        }
        $view = View::make('utility.session-table',compact('sessions','type'))->render();
        return response()->json(['status'=>true,'view'=>$view]);
   }

   public function downloadFile(Request $request){
        $fileName = $request->file;
       $headers = [
           'Content-Type' => 'application/sav',
       ];

       return response()->download(storage_path($fileName), 'canvas-file.sav', $headers)->deleteFileAfterSend(true);
   }
}
