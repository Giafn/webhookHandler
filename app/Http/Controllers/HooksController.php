<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessDeplymentWebhook;
use App\Models\LogDeploy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HooksController extends Controller
{
    public function handleGithubWebhookForDeploy(Request $request, $repoName)
    {
        try {
            $requestHash = $request->header('x-hub-signature-256') ?? '';
            $payload = $request->getContent();

            $localHash = 'sha256='.hash_hmac('sha256', $payload, env('APP_DEPLOY_SECRET'));

            if(!hash_equals($localHash, $requestHash)){
                $this->log($repoName, 'invalid-signature');
                return response()->json(['error' => 'invalid-signature'], 200);
            }
            
            $json = json_decode($payload);
            if(!isset($json->ref)){
                $this->log($repoName, 'invalid-push_payload');
                return response()->json(['error' => 'invalid-push_payload'], 200);
            }

            if( $json->ref != "refs/heads/main"){
                return response()->json(['success' => 'not-main'], 200);
            }
            
            $folderApp = $this->getBasePath(env('APP_FOLDER'));
            $repoLocation = $folderApp . '/' . $repoName;
            $scriptPath = $repoLocation . '/deploy.sh';

            if (!file_exists($scriptPath)) {
                $this->log($repoName, 'deploy.sh not found');
                return response()->json(['error' => 'deploy.sh not found'], 200);
            }

            $output = shell_exec("bash $scriptPath 2>&1");

            // commit message
            $commitMessage = $json->commits[0]->message ?? 'No commit message';

            $this->log(
                $repoName, 
                'Deployed: ' . $commitMessage . ' | ' . $output
            );
            

        } catch (\Exception $e) {
            $this->log($repoName, $e->getMessage());
            echo $e->getMessage();
            return response()->json(['error' => $e->getMessage()], 200);
        }

        return response()->json(['success' => 'success'], 200);
    }

    private function getBasePath($relatifPathApp = '')
    {
        $webhookAppPath = base_path();

        $webhookAppPath = substr($webhookAppPath, 0, strrpos($webhookAppPath, '/'));
        return $webhookAppPath . ($relatifPathApp == '' ? '' : '/' . $relatifPathApp);
    }

    private function log($repoName, $message)
    {
        $log = new LogDeploy();
        $log->repo_name = $repoName;
        $log->message = $message;
        $log->save();
    }
}


