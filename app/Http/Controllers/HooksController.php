<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HooksController extends Controller
{
    public function handleGithubWebhookForDeploy(Request $request)
    {

        $requestHash = $request->header('x-hub-signature-256') ?? '';

        $payload = $request->getContent();

        return response()->json(['success' => 'success'], 200);
    }
}
