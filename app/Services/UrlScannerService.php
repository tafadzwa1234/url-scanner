<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UrlScannerService
{
    public function scanUrl(string $url): array
    {
        try {
            // Basic URL validation
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                return [
                    'is_malicious' => false,
                    'threat_details' => 'Invalid URL format'
                ];
            }

            // Check for common phishing indicators
            $threats = [];
            
            // Check for suspicious TLDs
            $suspiciousTlds = ['xyz', 'tk', 'ml', 'ga', 'cf', 'gq'];
            $urlParts = parse_url($url);
            $host = $urlParts['host'] ?? '';
            $tld = substr(strrchr($host, "."), 1);
            
            if (in_array(strtolower($tld), $suspiciousTlds)) {
                $threats[] = "Suspicious top-level domain: .$tld";
            }

            // Check for IP address instead of domain name
            if (filter_var($host, FILTER_VALIDATE_IP)) {
                $threats[] = "URL contains IP address instead of domain name";
            }

            // Check for excessive subdomains
            $subdomainCount = substr_count($host, '.');
            if ($subdomainCount > 3) {
                $threats[] = "Excessive number of subdomains detected";
            }

            // Check for suspicious keywords in URL
            $suspiciousKeywords = ['login', 'signin', 'verify', 'account', 'secure', 'update'];
            foreach ($suspiciousKeywords as $keyword) {
                if (stripos($url, $keyword) !== false) {
                    $threats[] = "Suspicious keyword in URL: $keyword";
                }
            }

            // Check for URL encoding
            if (urlencode(urldecode($url)) !== $url) {
                $threats[] = "URL contains encoded characters";
            }

            return [
                'is_malicious' => !empty($threats),
                'threat_details' => !empty($threats) ? implode("\n", $threats) : 'No threats detected'
            ];
        } catch (\Exception $e) {
            Log::error('URL scanning error: ' . $e->getMessage());
            return [
                'is_malicious' => false,
                'threat_details' => 'Error scanning URL: ' . $e->getMessage()
            ];
        }
    }
} 