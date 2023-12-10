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

    public function playMP3MergeMultiFile($id): bool
    {
        $user = User::find(1);

        $audioFiles = [];
        foreach ($user->audio as $value) {
            $audioFiles[] = "storage/" . $value->path_mp3;
        }

        $outputFile = 'storage/uploads/1/' . Str::random(10) . '.mp3';

        $mergedFile = $this->mergeAudioFiles($audioFiles, $outputFile);

        $audio = new Audio();
        $audio->path_mp3 = str_replace('storage/', '', $outputFile);
        $audio->user_id = 1;
        $audio->time_start = 0;
        $audio->time_end = 1;
        // Set other attributes as needed
        $audio->save();

        \Log::info('File path before Storage::get: ' . $mergedFile);
        return true;
    }

    public function mergeAudioFiles($audioFiles, $outputFile)
    {
        // Create a list of input files for FFmpeg
        $inputFiles = implode('|', $audioFiles);
        $command = "ffmpeg -i 'concat:$inputFiles' -c copy \"$outputFile\"";

        // Run the FFmpeg command
        $process = Process::fromShellCommandline($command);

        try {
            $process->run();
            Log::info($process->getOutput());
        } catch (ProcessFailedException $exception) {
            Log::info($exception->getMessage());
        }
    }

    public function playMP3Test($id): bool
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

        $duration = trim($process->getOutput());

        // Extract start and end times
        $end = $duration; // End time in seconds
        $audio = new Audio();
        $audio->path_mp3 = str_replace('storage/', '', $outputFile);
        $audio->user_id = 1;
        $audio->time_start = 5;
        $audio->time_end = $end;
        // Set other attributes as needed
        $audio->save();

        return true;
    }

    public function mergeFileMP3()
    {
        $urls = [
            "https://samplelib.com/lib/preview/mp3/sample-12s.mp3",
            "https://samplelib.com/lib/preview/mp3/sample-15s.mp3",
            "https://file-examples.com/storage/febf69dcf3656dfd992b0fa/2017/11/file_example_MP3_5MG.mp3"
        ];

        $downloadedFiles = [];

        foreach ($urls as $url) {
            $fileContents = file_get_contents($url);
            $fileName = basename($url);
            $filePath = "temp/$fileName";

            Storage::disk('local')->put($filePath, $fileContents);
            $downloadedFiles[] = storage_path("app/$filePath");
        }

        $inputFiles = implode('|', $downloadedFiles);
        $outputFile = 'merged/merged_audio' . Str::random(10) . '.mp3';

        $outputFilePath = Storage::disk('public')->path($outputFile);
        $command = "ffmpeg -i 'concat:$inputFiles' -c copy \"$outputFilePath\"";
        exec($command);

        foreach ($downloadedFiles as $file) {
            Storage::disk('local')->delete($file);
        }

        $audio = new Audio();
        $audio->path_mp3 = $outputFile;
        $audio->user_id = 1;
        $audio->time_start = 0;
        $audio->time_end = 1;

        $audio->save();

        return true;
    }
}
