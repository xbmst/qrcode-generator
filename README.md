QR code generator
=====

Generate a QR code for a token and send it as email with embedded image.

Usage 
-----

- Edit `parameters.yml` according to your settings:
    
```yaml
parameters:
    mailer_transport: gmail #smtp, mail, sendmail, or gmail
    mailer_host: 127.0.0.1
    mailer_user: your_gmail@gmail.com
    mailer_password: your_gmail_password
```

- Change `AppBundle/Controller/QrGeneratorController.php`
```php
    QrGeneratorController::sendMail()
    $message
        ->setSubject('email_subject')
        ->setFrom([
            'your_gmail@gmail.com' => 'Your Name'
        ])
        ->setTo('recipient_email@example.com')
```