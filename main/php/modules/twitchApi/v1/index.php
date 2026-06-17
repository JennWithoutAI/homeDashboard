<?php

    #[AllowDynamicProperties]
    class twitch {
        private string $baseUrl = "https://id.twitch.tv/";
        private string $clientId;
        private string $clientSecret;
        private string $redirectUri;
        private string $accessToken;

        public function __construct() {
            $env = (new Tools())->loadEnv();
            $this->clientId     = $env['TWITCH_CLIENT_ID'];
            $this->clientSecret = $env['TWITCH_CLIENT_SECRET'];
            $this->redirectUri  = $env['TWITCH_REDIRECT_URI'];
            $this->checkTokenData();
        }

        public function redirectToTwitch(): void {
            $params = http_build_query([
                'response_type' => 'code',
                'client_id'     => $this->clientId,
                'redirect_uri'  => $this->redirectUri,
                'scope' => 'channel:manage:polls channel:read:polls moderator:read:chatters user:read:email',
                'state'         => bin2hex(random_bytes(16)),
            ]);
            ob_clean();
            header("Location: {$this->baseUrl}oauth2/authorize?$params");
            exit;
        }

        public function checkTokenData(): void {
            $skipRefresh = false;
            $twitchData  = $this->loadTwitchData();

            if (isset($_GET['code'])) {
                // Only exchange the code if we have no valid token yet
                if (!$twitchData || $twitchData['expires_in'] < time()) {
                    $this->handleCallback();
                }
                $skipRefresh = true;
                $twitchData  = $this->loadTwitchData();
            }

            if (!$skipRefresh) {
                $this->refreshToken($twitchData);
            }

            $twitchData = $this->loadTwitchData();

            if (!isset($twitchData['access_token'])) {
                var_dump($twitchData);
                die();
            }

            $this->accessToken = $twitchData['access_token'];
        }

        public function handleCallback(): void {
            if (!isset($_GET['code'])) {
                die('No code returned from Twitch.');
            }

            $tokens = $this->exchangeCodeForToken($_GET['code']);

            if (isset($tokens['access_token'])) {
                $tokens['expires_in'] = $tokens['expires_in'] + time();
                $this->accessToken    = $tokens['access_token'];
                $this->saveToJson($tokens);
            } else {
                die('Token exchange failed: ' . json_encode($tokens));
            }
        }

        public function getChannelData(string $channel): int {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL            => 'https://api.twitch.tv/helix/streams?user_login=' . urlencode($channel),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER     => [
                    'Client-Id: ' . $this->clientId,
                    'Authorization: Bearer ' . $this->accessToken,
                ],
            ]);

            $response = json_decode(curl_exec($ch), true);
            unset($ch);

            return $response['data'][0]['viewer_count'] ?? 0;
        }
        private function getUserId(string $channel): string {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL            => 'https://api.twitch.tv/helix/users?login=' . urlencode($channel),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER     => [
                    'Client-Id: ' . $this->clientId,
                    'Authorization: Bearer ' . $this->accessToken,
                ],
            ]);

            $response = json_decode(curl_exec($ch), true);
            unset($ch);

            return $response['data'][0]['id'] ?? '';
        }
        public function getChatters(string $channel) {
            $broadcasterId = $this->getUserId($channel);
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL            => 'https://api.twitch.tv/helix/chat/chatters?broadcaster_id=' . urlencode($broadcasterId)."&moderator_id=". urlencode($broadcasterId) ,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER     => [
                    'Client-Id: ' . $this->clientId,
                    'Authorization: Bearer ' . $this->accessToken,
                ],
            ]);

            $response = json_decode(curl_exec($ch), true);
            unset($ch);
            $trimData = $response["data"];
            return $trimData;
        }

        private function refreshToken($twitchData): void {
            if (!$twitchData) {
                $this->redirectToTwitch();
            }

            if (!isset($twitchData['access_token'])) {
                $this->redirectToTwitch();
            }

            // Refresh 5 minutes before actual expiry so there's no gap
            if (empty($twitchData['access_token']) || $twitchData['expires_in'] < (time() + 300)) {
                $refreshedToken = $this->exchangeRefreshForToken($twitchData['refresh_token']);

                if (!isset($refreshedToken['access_token'])) {
                    // Refresh token itself has expired — start auth flow again
                    $this->redirectToTwitch();
                }

                $refreshedToken['expires_in'] = $refreshedToken['expires_in'] + time();
                $this->saveToJson($refreshedToken);
                $this->accessToken = $refreshedToken['access_token'];
            }
        }

        private function loadTwitchData(): array|false {
            $file = './jsonSheets/twitch/loginData.json';
            if (!file_exists($file)) {
                return false;
            }
            return json_decode(file_get_contents($file), true);
        }

        private function saveToJson(array $tokenData): void {
            $file = './jsonSheets/twitch/loginData.json';
            if (!file_exists($file)) {
                $initFile = fopen($file, 'w+');
                fclose($initFile);
            }
            file_put_contents($file, json_encode($tokenData));
        }

        private function exchangeCodeForToken(string $code): array {
            return $this->curl("{$this->baseUrl}oauth2/token", [
                'client_id'     => $this->clientId,
                'client_secret' => $this->clientSecret,
                'code'          => $code,
                'grant_type'    => 'authorization_code',
                'redirect_uri'  => $this->redirectUri,
            ]);
        }

        private function exchangeRefreshForToken(string $refreshToken): array {
            return $this->curl("{$this->baseUrl}oauth2/token", [
                'client_id'     => $this->clientId,
                'client_secret' => $this->clientSecret,
                'refresh_token' => $refreshToken,
                'grant_type'    => 'refresh_token', // ← was wrongly 'authorization_code'
            ]);
        }
        
        private function curl(string $url, array $postFields = []): array {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL            => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => http_build_query($postFields),
                CURLOPT_HTTPHEADER     => ['Accept: application/json', 'Content-Type: application/x-www-form-urlencoded'],
            ]);

            $response = curl_exec($ch);
            unset($ch);

            return json_decode($response, true) ?? [];
        }
    }

    class Tools {
        public function loadEnv(): array {
            $env = parse_ini_file('.env');

            if ($env === false) {
                die('.env file not found or invalid.');
            }

            return $env;
        }
    }