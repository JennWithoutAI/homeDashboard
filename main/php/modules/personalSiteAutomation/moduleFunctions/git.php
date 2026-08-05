<?php

$env = parse_ini_file('.env');
$githubToken = $env["GH_TOKEN"];
$owner       = $env["GH_NAME"];
$repo        = $env["GH_REPO"];
$branch      = $env["GH_BRANCH"];
$filePath    = 'rotator.php';
$localFile   = MODULE_FILEURL_personalSiteAutomation . '/rotator.php';

if (!file_exists($localFile)) {
    fopen($localFile,"w+");
}

$apiUrl = "https://api.github.com/repos/$owner/$repo/contents/$filePath";
$ch = curl_init("$apiUrl");

// todo make this into a more accessable for a other method?
// authy
$agent = "automatedCyberFun";
$headers = [
    "User-Agent: $agent",
    "Authorization: Bearer $githubToken",
    "X-GitHub-Api-Version: 2026-03-10",
];
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER     => $headers,
]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
unset($ch);

// todo check this? idk (CHECK BASED ON THE PREVIOUS GITHUB TIME(): // for a other time though so client side chekker now
$content = base64_encode(file_get_contents($localFile));
$sha = null;
$ch = curl_init("$apiUrl?ref=$branch");
if ($httpCode === 200) {
    $data = json_decode($response, true);
    $sha = $data['sha'];
}

$time = time();
$payload = [
    'message' => strval($time),
    'content' => $content,
    'branch'  => $branch,
];
if ($sha) {
    $payload['sha'] = $sha;
}

$ch = curl_init($apiUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST  => 'PUT',
    CURLOPT_POSTFIELDS     => json_encode($payload),
    CURLOPT_HTTPHEADER => [
        "Authorization: token $githubToken",
        "User-Agent: $agent",
        "Accept: application/vnd.github+json",
        "Content-Type: application/json",
    ],
]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
unset($ch);

// this is SOOO unneeeded but kinda fun to do... so im going to do it and later add it to a site that just changes color based on it.
