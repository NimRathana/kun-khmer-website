<?php

namespace App\Http\Controllers\Api;

use Exception;
use Google_Client;
use Inertia\Inertia;
use Google_Service_YouTube;
use Illuminate\Http\Request;
use Google_Service_Exception;
use Google_Http_MediaFileUpload;
use Google_Service_YouTube_Video;
use App\Http\Controllers\Controller;
use Google_Service_YouTube_VideoStatus;
use Illuminate\Support\Facades\Session;
use Google_Service_YouTube_VideoSnippet;

class YoutubeController extends Controller
{
    private $json = '
        {
            "web":
            {
                "client_id":"412343088797-9fdpllkvn5557084m7dqqmgnppd8klvr.apps.googleusercontent.com",
                "project_id":"adept-script-447607-v2","auth_uri":"https://accounts.google.com/o/oauth2/auth",
                "token_uri":"https://oauth2.googleapis.com/token",
                "auth_provider_x509_cert_url":"https://www.googleapis.com/oauth2/v1/certs",
                "client_secret":"",
                "redirect_uris":["http://127.0.0.1:8000/youtube"],
                "javascript_origins":["http://127.0.0.1:8000"]
            }
        }';
    public function upload(Request $request)
    {
        try{
            // Create and configure client
            $client = new Google_Client();
            $client->setAuthConfig($this->json);
            $client->setClientId(env('YOUTUBE_CLIENT_ID'));
            $client->setClientSecret(env('YOUTUBE_CLIENT_SECRET'));
            $client->setRedirectUri(env('YOUTUBE_REDIRECT_URI'));
            $client->setAccessType('offline');
            $client->addScope(Google_Service_YouTube::YOUTUBE_UPLOAD);

            // Scenario 1: Complete authorization
            if ($request->has('code') && $request->input('code') != null) {
                try {
                    $client->authenticate($request->input('code'));
                    $token = $client->getAccessToken();
                    Session::put('google_oauth_token', $token);
                } catch (Exception $e) {
                    session()->forget('google_access_token');
                    return redirect()->back()->withErrors($e->getMessage());
                    throw $e;
                }
            }

            // Scenario 2: Already authorized
            if (Session::has('google_oauth_token')) {
                $client->setAccessToken(Session::get('google_oauth_token'));
                if ($client->isAccessTokenExpired()) {
                    Session::forget('google_oauth_token');
                    return Inertia::render('Components/Youtube');
                }

                if ($request->hasFile('video')) {
                    try {
                        // Get the video file path
                        $videoPath = $request->file('video')->path();

                        // Create a new YouTube Service instance
                        $youtube = new Google_Service_YouTube($client);

                        // Set video snippet (title, description, tags, category)
                        $snippet = new Google_Service_YouTube_VideoSnippet();
                        $snippet->setTitle('Test Videowffew'); // Replace with your title
                        $snippet->setDescription('This is a test video upload.'); // Replace with your description
                        $snippet->setTags(['test', 'video']); // Replace with your tags
                        $snippet->setCategoryId('22'); // Category ID for "People & Blogs"

                        // Set video privacy status
                        $status = new Google_Service_YouTube_VideoStatus();
                        $status->setPrivacyStatus('private'); // Change to 'public' or 'unlisted' as needed

                        // Combine snippet and status into a new video instance
                        $video = new Google_Service_YouTube_Video();
                        $video->setSnippet($snippet);
                        $video->setStatus($status);

                        $chunkSizeBytes = 1 * 1024 * 1024;
                        $client->setDefer(true);

                        try {
                            $insertRequest = $youtube->videos->insert("status,snippet", $video);
                            $media = new Google_Http_MediaFileUpload(
                                $client,
                                $insertRequest,
                                'video/*',
                                null,
                                true,
                                $chunkSizeBytes
                            );
                            $media->setFileSize(filesize($videoPath));

                            $status = false;
                            $handle = fopen($videoPath, "rb");
                            while (!$status && !feof($handle)) {
                                $chunk = fread($handle, $chunkSizeBytes);
                                $status = $media->nextChunk($chunk);
                            }
                            fclose($handle);

                            return Inertia::render('Components/Youtube', ['success' => 'Video uploaded successfully.']);
                        } catch (Google_Service_Exception $e) {
                            dd($e);
                            Session::forget('google_oauth_token');
                            return redirect()->back()->withErrors($e->getMessage());
                            throw $e;
                        }

                    } catch (Google_Service_Exception $e) {
                        Session::forget('google_oauth_token');
                        return redirect()->back()->withErrors($e->getMessage());
                        throw $e;
                    }
                } else {
                    return Inertia::render('Components/Youtube', ['message' => 'Please select a video file to upload.']);
                }
            } else {
                $authUrl = $client->createAuthUrl();
                return Inertia::render('Components/Youtube', ['authUrl' => $authUrl]);
            }
        }catch(Exception $e){
            Session::forget('google_oauth_token');
            return redirect()->back()->withErrors($e->getMessage());
            throw $e;
        }
    }

    private $client;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setAuthConfig($this->json);
        $this->client->setClientId(env('YOUTUBE_CLIENT_ID'));
        $this->client->setClientSecret(env('YOUTUBE_CLIENT_SECRET'));
        $this->client->setRedirectUri(env('YOUTUBE_REDIRECT_URI'));
        $this->client->setAccessType('offline');
        $this->client->addScope(Google_Service_YouTube::YOUTUBE_READONLY);
    }

    public function getPlaylists()
    {
        $youtube = new Google_Service_YouTube($this->client);

        try {
            $playlists = $youtube->playlists->listPlaylists('id,snippet', [
                'mine' => true,
                'maxResults' => 10,
            ]);

            $data = [];
            foreach ($playlists->getItems() as $playlist) {
                $data[] = [
                    'id' => $playlist['id'],
                    'title' => $playlist['snippet']['title'],
                    'description' => $playlist['snippet']['description'],
                ];
            }

            return response()->json($data);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
