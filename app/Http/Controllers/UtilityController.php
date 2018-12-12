<?php

namespace App\Http\Controllers;

use App\Models\CanvasImage;
use App\Models\SessionNote;
use App\Models\SharedDocument;
use App\Models\Student;
use App\Models\TechSupportMessage;
use App\Models\Tutor;
use App\Repositories\SessionRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use function Psy\sh;
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
        if (!realpath(storage_path("app/public/uploads/{$type}s/canvas_images/{$user->id}"))) {
            mkdir(storage_path("app/public/uploads/{$type}s/canvas_images/{$user->id}/"), 777);
            @chmod(storage_path("app/public/uploads/{$type}s/canvas_images/{$user->id}/"), 777);
        }
        $uploadDir = "{$type}s/canvas_images/{$user->id}/{$time}_{$user->id}_canvas_image.png";

        $file = storage_path("app/public/uploads/{$uploadDir}");
        file_put_contents($file, $image_base64);
        $uploadPath = preg_replace('/\/+/', '-', $uploadDir);
        return response()->json(['status' => true, 'file' => url("storage/{$uploadPath}")]);
    }

    public function readSavFile(Request $request, $type)
    {
        $user = Auth::guard($type)->user();
        if ($file = $request->file('sav-file')) {
            $ext = $file->getClientOriginalExtension();
            $file_name = 'read-sav-file' . time() . '.' . $ext;
            $dest = storage_path("app/public/uploads/sav_files/{$type}s/{$user->id}/");
            if (!realpath($dest)) {
                mkdir($dest);
                @chmod($dest, 777);
            }
            $data = [];
            if ($file->move($dest, $file_name)) {
                $savFile = Reader::fromFile($dest . $file_name);
                $savData = $savFile->data;
                foreach ($savData[0] as $d) {
                    $data[] = json_decode($d);
                }
                File::delete($dest . $file_name);
            }
            return response()->json(['status' => true, 'data' => $data]);
        } else {
            return response()->json(['status' => false]);
        }
    }

    public function saveCanvasToSav(Request $request, $type)
    {
        $user = Auth::guard($type)->user();
        $data = [];

        foreach ($request->data as $key => $row) {
            $json = json_encode($row);
            $data[] = [
                'name' => 'VAR' . $key,
                'width' => strlen($json),
                'columns' => 1,
                'align' => 1,
                'measure' => 1,
                'type' => 'String',
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
        $dest = storage_path("app/public/uploads/sav_files/{$type}s/{$user->id}/");
        if (!realpath($dest)) {
            mkdir($dest);
            @chmod($dest, 777);
        }
        $fileName = 'write_sav_file_' . time() . '.sav';
        $writer->save($dest . $fileName);
        return response()->json(['status' => true, 'file' => "uploads/sav_files/{$type}s/{$user->id}/{$fileName}"]);
    }

    public function sendReportToTechTeam(Request $request, $type)
    {

        $user = Auth::guard($type);
        $message = new TechSupportMessage;
        $message->message = $request->question;
        $message->user_type = $type;
        $message->user_id = $user->id;
        if ($message->save()) {
            return response()->json(['status' => true]);
        }
        return response()->json(['status' => false]);
    }

    public function searchSessionLogs(Request $request, $type)
    {
        $user = Auth::guard($type)->user();
        $sessions = [];
        $dateRange = $request->daterange;
        $subjectId = $request->subject_id;
        if ($type == 'student') {
            $sessions = SessionRepository::getStudentSessions($user->id, $subjectId, $dateRange);
        } else if ($type == 'tutor') {
            $sessions = SessionRepository::getTutorSessions($user->id, $subjectId, $dateRange);
        }
        $view = View::make('utility.session-table', compact('sessions', 'type'))->render();
        return response()->json(['status' => true, 'view' => $view]);
    }

    public function downloadFile(Request $request)
    {
        $fileName = $request->file;
        $headers = [
            'Content-Type' => 'application/sav',
        ];

        return response()->download(storage_path("app/public//{$fileName}"), 'canvas-file.sav', $headers)->deleteFileAfterSend(true);
    }

    public function readNotifications()
    {
        TechSupportMessage::where('status', 1)->update(['status' => 0]);
        return response()->json(['status' => true]);
    }

    public function shareDrawing(Request $request, $type)
    {
        $user = Auth::guard($type)->user();
        $pageTitle = 'Share Drawing';
        $uuid = $request->user;
        if($type=='tutor'){
            $sharedUser = Student::where('uuid',$uuid)->first();
        }else{
            $sharedUser = Tutor::where('uuid',$uuid)->first();
        }
        if(!$sharedUser){
            return abort(404);
        }
        $sharedUserType = $request->userType;
        $documentShared = DB::select("SELECT image,created_at, CASE WHEN shared_user_type='tutor' THEN (SELECT name FROM tutors where id = shared_user_id LIMIT 1) ELSE (SELECT name FROM students where id = shared_user_id LIMIT 1) END as shared_to
          from shared_documents WHERE user_type='{$type}' AND user_id = {$user->id} ORDER BY id desc");

        $receivedDocs = DB::select("SELECT image,created_at, CASE WHEN user_type='tutor' THEN (SELECT name FROM tutors where id = shared_user_id LIMIT 1) ELSE (SELECT name FROM students where id = shared_user_id LIMIT 1) END as shared_by
          from shared_documents WHERE shared_user_type='{$type}' AND shared_user_id = {$user->id} ORDER BY id desc");

        return view('share-board', compact('user', 'pageTitle', 'type', 'documentShared', 'sharedUser', 'sharedUserType','receivedDocs'));
    }

    public function shareFiles(Request $request, $type)
    {
        $sharedUserId = $request->user;
        $sharedType = $request->userType;
        if($sharedType=='tutor'){
            $sharedUser = Tutor::find($sharedUserId);
        }else{
            $sharedUser = Student::find($sharedUserId);
        }
        $user = Auth::guard($type)->user();
        $docs = [];
        foreach ($request->file('files') as $file) {
            $image = time() . '_' . $user->id . '_shared.' . $file->getClientOriginalExtension();
            $file->move(storage_path('app/public/uploads/shared_docs'), $image);
            $docs[] = ['user_id' => $user->id, 'user_type' => $type, 'image' => $image, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s'), 'shared_user_type' => $sharedType, 'shared_user_id' => $sharedUserId];
        }
        SharedDocument::insert($docs);
        return response()->json(compact('sharedUser','docs'));
    }

    public function readCloudFile(Request $request){

        $files =  array_diff(scandir(storage_path("app/public/cloud-files")), array('.', '..'));
        $fileData = [];
        foreach($files as $file){

            if(!is_dir(storage_path("app/public/cloud-files/{$file}"))){
                $fileData[] = [
                    "text" => $file,
                    "a_attr" => ["class"=>"js-read-cloud-file","file"=>asset("storage/cloud-files/{$file}")],
                    "icon" => "images/file-icon.png"
                ];
            }else {

                $fileArray = ['text' => $file];
                $fileArray['children'] = [];
                $subFile = array_diff(scandir(storage_path("app/public/cloud-files/{$file}")), array('.', '..'));

                foreach ($subFile as $f) {

                    if (!is_dir(storage_path("app/public/cloud-files/{$file}/$f"))) {

                        $fileArray['children'][] = [
                            "text" => $f,
                            "a_attr" => ["class" => "js-read-cloud-file", "file" => asset("storage/cloud-files/{$file}/$f")],
                            "icon" => "images/file-icon.png"
                        ];

                    } else {
                        $subArray = ['text' => $f];
                        $subSubFile = array_diff(scandir(storage_path("app/public/cloud-files/{$file}/{$f}")), array('.', '..'));
                        foreach ($subSubFile as $sf) {
                            if (!is_dir(storage_path("app/public/cloud-files/{$file}/{$f}/{$sf}"))) {
                                $subArray['children'][] = [
                                    "text" => $sf,
                                    "a_attr" => ["class" => "js-read-cloud-file", "file" => asset("storage/cloud-files/{$file}/{$f}/{$sf}")],
                                    "icon" => "images/file-icon.png"
                                ];
                            }
                        }
                        $fileArray['children'][] = $subArray;
                    }
                }
                $fileData[] = $fileArray;
            }

        }
        return response()->json($fileData);
    }

    public function saveSessionNote(Request $request){
        $note = new SessionNote;
        $note->note = $request->note;
        $note->user_id = $request->user_id;
        $note->user_type = $request->user_type;
        $note->subject_id= $request->subject_id;
        if($note->save()){
            $note->date = Carbon::parse($note->created_at)->format('d M Y H:i');
            return response()->json(['status'=>true,'note'=>$note]);

        }

        return response()->json(['status'=>false]);
    }

    public function getUserMessages(Request $request){
        $fromUser = $request->fromUser;
        $toUser = $request->toUser;
        $userType = $request->userType;
        $query = "SELECT m.message,m.created_at,m.user_name FROM messages as m 
          INNER JOIN students as s ON s.id = (CASE WHEN m.user_type='student' THEN m.to_id ELSE m.from_id END )
          INNER JOIN tutors as t ON t.id = (CASE WHEN m.user_type='tutor' THEN m.from_id ELSE m.to_id END) 
          WHERE m.created_at BETWEEN DATE_SUB(now(),INTERVAL 1 HOUR) AND now() AND   (CASE WHEN '{$userType}'='tutor' THEN t.uuid= '{$fromUser}' ELSE t.uuid='{$toUser}' END )
          AND  (CASE WHEN '{$userType}'='student' THEN s.uuid= '{$fromUser}' ELSE s.uuid='{$toUser}' END) ORDER BY m.id desc LIMIT 100";
        $messages = DB::select($query);
        return response()->json(['status'=> true, 'messages'=> $messages]);
    }

    public function saveUsersDrawing(Request $request,$type){
        try{
            $user = Auth::guard($type)->user();
            $time = time();
            $base64 = $request->imageData;
            $base64 =  str_replace('data:image/png;base64,', '', $base64);
            $image_base64 = base64_decode($base64);
            if (!realpath(storage_path("app/public/session-images/{$type}s"))) {
                mkdir(storage_path("app/public/session-images/{$type}s/"), 777);
            }
            $fileName = "{$time}_{$user->id}_canvas_image.png";

            $file = storage_path("app/public/session-images/{$type}s/{$fileName}");

                file_put_contents($file, $image_base64);
            $image = new CanvasImage();
            $image->image = $fileName;
            $image->user_type = $type;
            $image->user_id = $user->id;
            $image->save();
            return response()->json(['status' => true]);
        }catch (\Exception $e){
            return response()->json(['status' => false]);
        }

    }
}
