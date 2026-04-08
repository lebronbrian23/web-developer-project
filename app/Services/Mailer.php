<?php

/**
 * Mailer
 *
 * Sends the confirmation/summary email after a valid submission.
 * Uses PHP's built-in mail() function — no third-party library.
 */

namespace App\Services;

class Mailer
{
    private string $fromAddress;
    private string $fromName;
    private string $toAddress;

    public function __construct()
    {
        $this->fromAddress = env('MAIL_FROM', 'noreply@voices.com');
        $this->fromName = env('MAIL_FROM_NAME', 'Voices Job Submission Form');
        $this->toAddress = env('MAIL_TO', 'jobform@voices.com');
    }

    public function sendConfirmationEmail(array $submission)
    {
        // Build the email subject and body
        $subject = "New Job Submission: " . ($submission['title'] ?? 'No Title');
        $body = "A new job has been submitted with the following details:\n\n";
        $body .= "Title: " . ($submission['title'] ?? 'N/A') . "\n";
        $body .= "Script: " . ($submission['script'] ?? 'N/A') . "\n";
        $body .= "Country: " . ($submission['country'] ?? 'N/A') . "\n";
        $body .= "State/Province: " . ($submission['state_or_province'] ?? 'N/A') . "\n";
        $body .= "Budget: " . ($submission['budget'] ?? 'N/A') . "\n";

        // Log email attempt
        log_email("SENDING EMAIL TO: {$this->toAddress}", true, [
            'subject' => $subject,
            'to' => $this->toAddress,
            'from' => "{$this->fromName} <{$this->fromAddress}>",
            'submission_id' => $submission['id'] ?? 'unknown'
        ]);

        $sent = mail(
            $this->toAddress,
            $subject,
            $body,
            "From: {$this->fromName} <{$this->fromAddress}>"
        );

        // Log result
        log_email(
            $sent ? "EMAIL SENT SUCCESSFULLY" : "EMAIL SEND FAILED",
            $sent,
            $submission
        );

        return $sent;
    }
}
