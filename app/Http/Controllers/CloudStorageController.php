<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CloudStorageController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $sortableColumns = ['full_name', 'email', 'mobile', 'created_at'];
        $sort = $request->query('sort', 'created_at');
        $direction = $request->query('direction', 'desc');

        if (!in_array($sort, $sortableColumns, true)) {
            $sort = 'created_at';
        }

        if (!in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'desc';
        }

        $students = Student::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('full_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('mobile', 'like', "%{$search}%");
                });
            })
            ->orderBy($sort, $direction)
            ->simplePaginate(3)
            ->withQueryString();

        return view('list-student', compact('students', 'search', 'sort', 'direction'));
    }

    public function create()
    {
        return view('add-student');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'mobile' => 'nullable|string|max:50',
            'photo' => 'required|image|max:5120',
            'sign' => 'required|image|max:5120',
        ]);

        $photoUpload = $this->uploadToCloudinary($request->file('photo'), 'students/photos');
        $signUpload = $this->uploadToCloudinary($request->file('sign'), 'students/signs');

        if (!empty($photoUpload['error']) || !empty($signUpload['error'])) {
            $errorMessage = $photoUpload['error'] ?? $signUpload['error'];

            return back()
                ->withInput()
                ->withErrors(['cloudinary' => $errorMessage]);
        }

        Student::create([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
            'photo' => $photoUpload['url'],
            'sign' => $signUpload['url'],
        ]);

        return redirect()->route('students.index')->with('success', 'Student added successfully.');
    }

    protected function uploadToCloudinary($file, string $folder)
    {
        if (!$file) {
            return ['url' => null, 'error' => 'No file uploaded.'];
        }

        $cloudName = env('CLOUDINARY_CLOUD_NAME');
        $apiKey = env('CLOUDINARY_API_KEY');
        $apiSecret = env('CLOUDINARY_API_SECRET');

        if (!$cloudName || !$apiKey || !$apiSecret) {
            return ['url' => null, 'error' => 'Cloudinary environment variables are not configured.'];
        }

        $publicId = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '-' . time();
        $timestamp = time();
        $paramsToSign = [
            'folder' => $folder,
            'overwrite' => 'true',
            'public_id' => $publicId,
            'timestamp' => $timestamp,
        ];

        ksort($paramsToSign);
        $signatureString = collect($paramsToSign)
            ->map(fn ($value, $key) => "{$key}={$value}")
            ->implode('&');

        $signature = sha1($signatureString . $apiSecret);

        $fileResource = fopen($file->getRealPath(), 'rb');
        if ($fileResource === false) {
            return ['url' => null, 'error' => 'Unable to read uploaded file for Cloudinary upload.'];
        }

        $response = Http::timeout(30)
            ->asMultipart()
            ->attach('file', $fileResource, $file->getClientOriginalName())
            ->post("https://api.cloudinary.com/v1_1/{$cloudName}/image/upload", array_merge($paramsToSign, [
                'api_key' => $apiKey,
                'signature' => $signature,
            ]));

        if ($fileResource) {
            fclose($fileResource);
        }

        if ($response->successful()) {
            return ['url' => $response->json('secure_url'), 'error' => null];
        }

        $errorBody = $response->body();
        \Log::error('Cloudinary upload failed', [
            'response' => $errorBody,
            'status' => $response->status(),
        ]);

        return ['url' => null, 'error' => "Cloudinary upload failed ({$response->status()}): {$errorBody}"];
    }
}
