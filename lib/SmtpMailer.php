<?php
declare(strict_types=1);

/**
 * Minimal SMTP mailer (SSL/TLS) with AUTH LOGIN.
 * Keeps the project dependency-free.
 * Works for basic transactional emails (admin + customer confirmations).
 */
final class SmtpMailer
{
    private string $host;
    private int $port;
    private string $secure; // 'ssl' or 'tls' or ''
    private string $user;
    private string $pass;

    public function __construct(string $host, int $port, string $secure, string $user, string $pass)
    {
        $this->host = $host;
        $this->port = $port;
        $this->secure = $secure;
        $this->user = $user;
        $this->pass = $pass;
    }

    public function send(array $mail): bool
    {
        // Required: to, subject, html, text, from_email, from_name
        $to = $mail['to'] ?? '';
        $subject = $mail['subject'] ?? '';
        $html = $mail['html'] ?? '';
        $text = $mail['text'] ?? '';
        $fromEmail = $mail['from_email'] ?? '';
        $fromName = $mail['from_name'] ?? '';
        $replyTo = $mail['reply_to'] ?? '';

        if (!$to || !$subject || !$fromEmail) {
            return false;
        }

        $boundary = 'bnd_' . bin2hex(random_bytes(12));
        $headers = [];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'From: ' . $this->formatAddress($fromEmail, $fromName);
        if ($replyTo) $headers[] = 'Reply-To: ' . $replyTo;
        $headers[] = 'To: ' . $to;
        $headers[] = 'Subject: ' . $this->encodeHeader($subject);
        $headers[] = 'Content-Type: multipart/alternative; boundary="' . $boundary . '"';

        $body  = "--{$boundary}
";
        $body .= "Content-Type: text/plain; charset=utf-8
";
        $body .= "Content-Transfer-Encoding: 8bit

";
        $body .= $text . "

";
        $body .= "--{$boundary}
";
        $body .= "Content-Type: text/html; charset=utf-8
";
        $body .= "Content-Transfer-Encoding: 8bit

";
        $body .= $html . "

";
        $body .= "--{$boundary}--
";

        return $this->smtpSend($fromEmail, $to, implode("
", $headers), $body);
    }

    private function smtpSend(string $from, string $to, string $headers, string $body): bool
    {
        $transport = ($this->secure === 'ssl') ? 'ssl://' : '';
        $socket = @fsockopen($transport . $this->host, $this->port, $errno, $errstr, 15);
        if (!$socket) return false;

        $this->expect($socket, 220);

        $this->cmd($socket, 'EHLO ongoingteam.com', [250]);
        if ($this->secure === 'tls') {
            $this->cmd($socket, 'STARTTLS', [220]);
            if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                fclose($socket); return false;
            }
            $this->cmd($socket, 'EHLO ongoingteam.com', [250]);
        }

        $this->cmd($socket, 'AUTH LOGIN', [334]);
        $this->cmd($socket, base64_encode($this->user), [334]);
        $this->cmd($socket, base64_encode($this->pass), [235]);

        $this->cmd($socket, 'MAIL FROM:<' . $from . '>', [250]);
        $this->cmd($socket, 'RCPT TO:<' . $to . '>', [250, 251]);
        $this->cmd($socket, 'DATA', [354]);

        $data = $headers . "

" . $body . "
.";
        fwrite($socket, $data . "
");
        $this->expect($socket, 250);

        $this->cmd($socket, 'QUIT', [221]);
        fclose($socket);
        return true;
    }

    private function cmd($socket, string $cmd, array $okCodes): void
    {
        fwrite($socket, $cmd . "
");
        $line = $this->readLine($socket);
        $code = (int)substr($line, 0, 3);
        if (!in_array($code, $okCodes, true)) {
            throw new RuntimeException("SMTP error after {$cmd}: {$line}");
        }
        // Some responses are multi-line: keep reading until last line (no hyphen).
        while (isset($line[3]) && $line[3] === '-') {
            $line = $this->readLine($socket);
        }
    }

    private function expect($socket, int $code): void
    {
        $line = $this->readLine($socket);
        $got = (int)substr($line, 0, 3);
        if ($got !== $code) throw new RuntimeException("SMTP expected {$code}, got {$line}");
        while (isset($line[3]) && $line[3] === '-') {
            $line = $this->readLine($socket);
        }
    }

    private function readLine($socket): string
    {
        $line = fgets($socket, 1024);
        if ($line === false) throw new RuntimeException('SMTP read failed');
        return rtrim($line, "
");
    }

    private function formatAddress(string $email, string $name): string
    {
        $name = trim($name);
        if ($name === '') return $email;
        return $this->encodeHeader($name) . " <{$email}>";
    }

    private function encodeHeader(string $value): string
    {
        // RFC 2047 encoded-word for UTF-8
        if (preg_match('/[\x80-\xFF]/', $value)) {
            return '=?UTF-8?B?' . base64_encode($value) . '?=';
        }
        return $value;
    }
}
