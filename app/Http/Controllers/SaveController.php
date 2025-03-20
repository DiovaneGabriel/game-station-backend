<?php

namespace App\Http\Controllers;

use App\Models\Save;
use DateTime;
use DBarbieri\Aws\S3;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SaveController extends Controller
{

    private S3 $s3;

    public function __construct(S3 $s3)
    {
        $this->s3 = $s3;
    }

    public function save(Request $request)
    {

        try {
            $request->validate([
                'state' => 'required|file',
            ]);
        } catch (ValidationException $e) {
            return $this->returnValidationError($e);
        }

        $file = $request->file('state');

        if ($file->isValid()) {

            $fileName = Str::uuid() . '.state';
            $size = $file->getSize() / 1000;
            $fileContent = file_get_contents($file->getRealPath());
            $hash = preg_replace('/[^a-zA-Z0-9]/', '_', pathinfo($fileName, PATHINFO_FILENAME));
            $ext = "." . pathinfo($fileName, PATHINFO_EXTENSION);
            $mimeType = $file->getClientMimeType();

            Log::info($fileName);

            try {
                $url = $this->s3->send($fileContent, $hash . $ext, true);
            } catch (Exception $e) {
                return $this->return500($e->getMessage());
            }

            $date = new DateTime();
            $formattedDate = $date->format('Y-m-d H:i:s.u');

            DB::table('cms.files')->insert([
                'name' => $fileName,
                'hash' => $hash,
                'ext' => $ext,
                'mime' => $mimeType,
                'size' => $size,
                'url' => $url,
                'provider' => 'aws-s3',
                'folder_path' => '/',
                'created_at' => $formattedDate,
                'updated_at' => $formattedDate,
                'published_at' => $formattedDate
            ]);

            $fileId = DB::getPdo()->lastInsertId();
            $gameId = 1;
            $userId = 1;

            DB::table('cms.saves')->insert([
                'created_at' => $formattedDate,
                'updated_at' => $formattedDate,
                'published_at' => $formattedDate
            ]);

            $saveId = DB::getPdo()->lastInsertId();

            DB::table('cms.saves_game_lnk')->insert([
                'save_id' => $saveId,
                'game_id' => $gameId
            ]);

            DB::table('cms.saves_users_permissions_user_lnk')->insert([
                'save_id' => $saveId,
                'user_id' => $userId
            ]);

            DB::table('cms.files_related_mph')->insert([
                'file_id' => $fileId,
                'related_id' => $saveId,
                'related_type' => "api::save.save",
                'field' => "file"
            ]);

            return $this->returnSuccess($saveId);
        }

        return $this->return400();
    }

    public function load(Request $request)
    {
        $save = Save::where('game_id', 1)
            ->where('user_id', 1)
            ->orderBy('created_at', 'desc')
            ->with(['file'])
            ->first();

        if (!$save) {
            return $this->return404();
        }

        $file = $this->s3->get($save->file->hash . $save->file->ext, null, true);
        $mimeType = $file['Content-Type'];
        $file = $file['Body']->getContents();

        return response($file, 200)->header('Content-Type', $mimeType);
    }
}
