<?php
    // partially found this code online, and made myself.
    class Twitch {
        private string $baseUrl    = "https://id.twitch.tv/";
        private string $clientId;
        private string $clientSecret;
        private string $redirectUri;

        public function __construct() {
            $env = (new Tools())->loadEnv();
            $this->clientId     = $env['TWITCH_CLIENT_ID'];
            $this->clientSecret = $env['TWITCH_CLIENT_SECRET'];
            $this->redirectUri  = $env['TWITCH_REDIRECT_URI'];
        }

        public function redirectToTwitch(): void {
            $params = http_build_query([
                'response_type' => 'code',
                'client_id'     => $this->clientId,
                'redirect_uri'  => $this->redirectUri,
                'scope'         => 'channel:manage:polls channel:read:polls',
                'state'         => bin2hex(random_bytes(16)),
            ]);

            header("Location: {$this->baseUrl}oauth2/authorize?$params");
            exit;
        }

        public function handleCallback(): void {
            if (!isset($_GET['code'])) {
                die('No code returned from Twitch.');
            }

            $tokens = $this->exchangeCodeForToken($_GET['code']);

            if (isset($tokens['access_token'])) {
                // Store tokens securely (session, DB, etc.)
                $_SESSION['access_token']  = $tokens['access_token'];
                $_SESSION['refresh_token'] = $tokens['refresh_token'];
                echo "Authenticated successfully.";
            } else {
                die('Token exchange failed: ' . json_encode($tokens));
            }
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
            curl_close($ch);

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

