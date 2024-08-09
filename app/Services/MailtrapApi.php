<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Log;

class MailtrapApi
{
    private $client;
    private $account_id;
    private $inbox_id;
    private $api_token;
    private $lastError;
    protected $baseUri;

    public function __construct()
    {
        $this->api_token = config('services.mailtrap.api_token');
        $this->account_id = config('services.mailtrap.account_id');
        $this->inbox_id = config('services.mailtrap.inbox_id');
        $this->baseUri = 'https://mailtrap.io/api/accounts/';
        $this->client = new Client([
            'base_uri' => 'https://mailtrap.io/api/',
            'headers' => [
                'Authorization' => "Bearer {$this->api_token}",
                'Accept' => 'application/json',
            ],
        ]);
    }

    public function getLatestEmail()
    {
        try {
            $response = $this->client->request('GET', "accounts/{$this->account_id}/inboxes/{$this->inbox_id}/messages", [
                'query' => [
                    'limit' => 1,
                    'order' => 'desc',
                    'sort_field' => 'created_at'
                ],
            ]);

            $responseBody = (string) $response->getBody();
            Log::info('Mailtrap API response', ['response' => $responseBody]);

            $messages = json_decode($responseBody, true);

            if (empty($messages)) {
                $this->lastError = "No messages found in the inbox.";
                return null;
            }

            $latestMessage = $messages[0];
            Log::info('Latest message details', ['latestMessage' => $latestMessage]);

            // Fetch the HTML body of the email
            $htmlBodyResponse = $this->client->request('GET', $latestMessage['html_source_path']);
            $htmlBody = (string) $htmlBodyResponse->getBody();

            // Return the latest message with HTML body
            return (object) [
                'id' => $latestMessage['id'],
                'inbox_id' => $latestMessage['inbox_id'],
                'subject' => $latestMessage['subject'],
                'sent_at' => $latestMessage['sent_at'],
                'from_email' => $latestMessage['from_email'],
                'from_name' => $latestMessage['from_name'],
                'to_email' => $latestMessage['to_email'],
                'to_name' => $latestMessage['to_name'],
                'email_size' => $latestMessage['email_size'],
                'is_read' => $latestMessage['is_read'],
                'created_at' => $latestMessage['created_at'],
                'updated_at' => $latestMessage['updated_at'],
                'template_id' => $latestMessage['template_id'],
                'template_variables' => $latestMessage['template_variables'],
                'html_body_size' => $latestMessage['html_body_size'],
                'text_body_size' => $latestMessage['text_body_size'],
                'human_size' => $latestMessage['human_size'],
                'html_body' => $htmlBody,
                'blacklists_report_info' => $latestMessage['blacklists_report_info'],
                'smtp_information' => $latestMessage['smtp_information'],
            ];
        } catch (GuzzleException $e) {
            $this->lastError = "API request failed: " . $e->getMessage();
            Log::error($this->lastError);
            return null;
        }
    }

    public function getLastError()
    {
        return $this->lastError;
    }
}
