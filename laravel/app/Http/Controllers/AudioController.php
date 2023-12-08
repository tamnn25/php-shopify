<?php

namespace App\Http\Controllers;

use App\Models\Audio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use FFMpeg\FFMpeg;
use Symfony\Component\Process\Process;
use Illuminate\Support\Str;
use Symfony\Component\Process\Exception\ProcessFailedException;

class AudioController extends Controller
{
    public function uploadMP3(Request $request)
    {
        $request->validate([
            'mp3_file' => 'required|mimes:mp3|max:2048',
        ]);

        if ($request->file('mp3_file')->isValid()) {
            $user = User::find(1); // Get the currently authenticated user
            $fileName = time() . '.' . $request->file('mp3_file')->getClientOriginalExtension();
            $filePath = $request->file('mp3_file')->storeAs('uploads/' . $user->id, $fileName, 'public');
    
            // You might want to associate this file with the user in the database
            $user->audio()->create([
                'path_mp3' => $filePath,
                'time_start' => '01:00:00', // Adjust this as needed
                'time_end' => '05:00:00', // Adjust this as needed
            ]);

            return redirect()->back()->with('success', 'MP3 file uploaded successfully!');
        }

        return redirect()->back()->with('error', 'Invalid MP3 file or file upload failed.');
    }
    
    public function showUploadForm()
    {
        $mp3Files = Audio::where('user_id', 1)->get();

        return view('upload', ['mp3Files' => $mp3Files]);
    }

    public function playMP3TestMerge($id)
    {
        $audioFiles = [
            'storage/uploads/1/1701941708.mp3',
            'storage/uploads/1/1701941805.mp3',
        ];
        $outputFile = 'storage/uploads/1/' . Str::random(10) . '.mp3';

        $mergedFile = $this->mergeAudioFiles($audioFiles, $outputFile);

        $fileContents = Storage::get($mergedFile);
        \Log::info('File path before Storage::get: ' . $mergedFile);


        return true;
    }

    public function mergeAudioFiles($audioFiles, $outputFile)
    {
        $audioFiles = [
            'storage/uploads/1/1701941708.mp3',
            'storage/uploads/1/1701941805.mp3',
        ];
        
        $outputFile = 'storage/uploads/1/concatenated.mp3';

        // Ensure full paths
        $ffmpegBinary = '/usr/local/bin/ffmpeg'; // Update this with the correct path to your ffmpeg binary
        $outputFileFullPath = storage_path($outputFile); // Ensure full path for output file

        // Create a list of input files for FFmpeg
        $inputFiles = implode('|', $audioFiles);

        // Construct the FFmpeg command
        $command = "$ffmpegBinary -i 'concat:$inputFiles' -c copy \"$outputFileFullPath\"";

        // Run the FFmpeg command
        $process = Process::fromShellCommandline($command);

        try {
            $process->mustRun();
            echo $process->getOutput();
        } catch (ProcessFailedException $exception) {
            echo $exception->getMessage();
            dd($process->getErrorOutput());
            dd($exception->getMessage());
            echo $process->getErrorOutput(); // Display error output for debugging
        }
    }

    public function playMP3($id)
    {
        $audio = Audio::find($id);

        $audioFile1 = 'storage/uploads/1/1701941708.mp3';
        $audioFile2 = 'storage/uploads/1/1701941805.mp3';
        $outputFile = 'storage/uploads/1/' . Str::random(10) . '.mp3';

        $ffmpeg = FFMpeg::create();

        // Open the first input file
        $audio1 = $ffmpeg->open($audioFile1);

        // Open the second input file
        $audio2 = $ffmpeg->open($audioFile2);

        $command = "ffmpeg -i 'concat:$audioFile1|$audioFile2' -c copy $outputFile";

        $process = Process::fromShellCommandline($command);
        $process->run();

        $fileContents = Storage::get($outputFile);
        \Log::info('File path before Storage::get: ' . $outputFile);

        // $audio1 = \falahati\PHPMP3\MpegAudio::fromFile($audioFile1)->stripTags();
        // $audio2 = \falahati\PHPMP3\MpegAudio::fromFile($audioFile2)->stripTags();
        // $out =  $audio1->append($audio2)->saveFile("3.mp3");


        $duration = trim($process->getOutput());

        // Extract start and end times
        $start = 0; // Start time in seconds
        $end = $duration; // End time in seconds
        $audio = new Audio();
        $audio->path_mp3 = str_replace('storage/', '', $outputFile);
        $audio->user_id = 1;
        $audio->time_start = 0;
        $audio->time_end = $end;
        // Set other attributes as needed
        $audio->save();

        return true;
    }
}
