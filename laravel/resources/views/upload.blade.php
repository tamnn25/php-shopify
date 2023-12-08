<!-- resources/views/upload.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ... -->
</head>
<body>
    <form action="{{ route('upload.mp3') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file" name="mp3_file" accept="audio/mp3" required>
        <button type="submit">Upload MP3</button>
    </form>

    <h2>Uploaded MP3 Files:</h2>
    <ul>
        @foreach ($mp3Files as $file)
            <li>
                <a href="{{ asset('storage/' . $file->path_mp3) }}" target="_blank">Download {{ basename($file->path_mp3) }}</a>
                |

                {{$file->path_mp3}}
                <a href="{{ route('play.mp3', ['id' => $file->id]) }}" target="_blank">Play</a>
            </li>
        @endforeach
    </ul>

    @if (session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @elseif (session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif
</body>
</html>
