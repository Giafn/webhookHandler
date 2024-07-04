<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessDeplymentWebhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HooksController extends Controller
{
    public function handleGithubWebhookForDeploy(Request $request, $repoName)
    {
        echo "webhook received";
        Log::info('webhook received');
        $requestHash = $request->header('x-hub-signature-256') ?? '';
        $payload = $request->getContent();

        $localHash = 'sha256='.hash_hmac('sha256', $payload, env('APP_DEPLOY_SECRET'));

        if(!hash_equals($localHash, $requestHash)){
            return response()->json(['error' => 'invalid-signature'], 200);
        }
        
        $json = json_decode($payload);
        if(!isset($json->ref)){
            return response()->json(['error' => 'invalid-push_payload'], 200);
        }

        if( $json->ref != "refs/heads/main"){
            return response()->json(['success' => 'not-main'], 200);
        }
        $folderApp = $this->getBasePath(env('APP_FOLDER'));
        $repoLocation = $folderApp . '/' . $repoName;
        $scriptPath = $repoLocation . '/deploy.sh';
        
        $output = shell_exec("bash $scriptPath 2>&1");
        
        log::info("lokasi repo: $repoLocation");
        log::info("lokasi script: $scriptPath");
        Log::info("result webhook: $output");

        return response()->json(['success' => 'success'], 200);
    }

    private function getBasePath($relatifPathApp = '')
    {
        $webhookAppPath = base_path();

        $webhookAppPath = substr($webhookAppPath, 0, strrpos($webhookAppPath, '/'));
        return $webhookAppPath . ($relatifPathApp == '' ? '' : '/' . $relatifPathApp);
    }
}


