<?php

namespace App\Http\Controllers;

use App\Models\DataCampaignAudit;
use App\Models\DataPropertyScore;
use App\Models\DataRawData;
use App\Models\DataSellerProfile;
use App\Models\DataSspProfile;
use Carbon\Carbon;
use DateTime;
use DBarbieri\Aws\S3;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
                'file' => 'required|file',
            ]);
        } catch (ValidationException $e) {
            return $this->returnValidationError($e);
        }

        $file = $request->file('file');

        if ($file->isValid()) {

            Log::info($file);

            // $fileName = $file->getClientOriginalName();
            // $size = $file->getSize() / 1000;
            // $fileContent = file_get_contents($file->getRealPath());
            // $hash = preg_replace('/[^a-zA-Z0-9]/', '_', pathinfo($fileName, PATHINFO_FILENAME));
            // $ext = "." . pathinfo($fileName, PATHINFO_EXTENSION);
            // $mimeType = $file->getClientMimeType();

            // try {
            //     $url = $this->s3->send($fileContent, $hash . $ext, true);
            // } catch (Exception $e) {
            //     return $this->return500($e->getMessage());
            // }

            // $date = new DateTime();
            // $formattedDate = $date->format('Y-m-d H:i:s.u');

            // DB::table('cms.files')->insert([
            //     'name' => $fileName,
            //     'hash' => $hash,
            //     'ext' => $ext,
            //     'mime' => $mimeType,
            //     'size' => $size,
            //     'url' => $url,
            //     'provider' => 'aws-s3',
            //     'folder_path' => '/',
            //     'created_at' => $formattedDate,
            //     'updated_at' => $formattedDate,
            // ]);

            // $fileId = DB::getPdo()->lastInsertId();

            // $propertiesScores = DataPropertyScore::first();
            // if (!$propertiesScores) {
            //     $propertiesScores = new DataPropertyScore;
            //     $propertiesScores->published_at = $formattedDate;
            //     $propertiesScores->save();
            // }

            // DB::table('cms.files_related_morphs')
            //     ->where('related_id', $propertiesScores->id)
            //     ->where('related_type', "api::data-property-score.data-property-score")
            //     ->where('field', $field)
            //     ->delete();

            // DB::table('cms.files_related_morphs')->insert([
            //     'file_id' => $fileId,
            //     'related_id' => $propertiesScores->id,
            //     'related_type' => "api::data-property-score.data-property-score",
            //     'field' => $field
            // ]);

            // return $this->returnSuccess($url);
        }

        return $this->return400();
    }
}
