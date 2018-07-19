<?php

require __DIR__ . '/vendor/autoload.php';

use Nischayn22\MediaWikiApi;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include( 'settings.php' );

$wikiApi = new MediaWikiApi($settings['wikiApi']);
echo "Logging in to wiki\n";
$wikiApi->login($settings['wikiUser'], $settings['wikiPassword']);

$factory = new RandomLib\Factory;
$generator = $factory->getMediumStrengthGenerator();

ini_set('auto_detect_line_endings',TRUE);
if (($handle = fopen(__DIR__ . "/" . $settings['csv_file'], "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 2000, ",")) !== FALSE) {

		$name = trim( $data[0] );

		if ( empty( $name ) ) {
			break;
		}

		$realname = trim( $data[1] );
		$email = trim( $data[2] );
		$password = $generator->generateString( 8 );

		if ( !$wikiApi->createAccount( $name, $password, $email, $realname ) ) {
			echo "Error: ". $wikiApi->getLastError() ." \nCould not create account for $name\n";
			continue;
		} else {
			echo "Created account for $name\n";
		}

		if ( $settings['mail_user'] ) {
			$mail = new PHPMailer(true);
			try {
				$mail->isSMTP();
				$mail->Host = $settings['smtp_host'];
				$mail->SMTPAuth = true;
				$mail->Username = $settings['smtp_username'];
				$mail->Password = $settings['smtp_password'];
				$mail->SMTPSecure = 'tls';
				$mail->Port = 587;

				//Recipients
				$mail->setFrom( $settings['from'] );
				$mail->addAddress( $email );
				foreach( $settings['cc'] as $cc ) {
					$mail->addCC( $cc );
				}

				//Content
				$mail->isHTML(true);
				$mail->Subject = $settings['subject'];

				$body = $settings['body'];
				$body = str_replace( "{username}", $name, $body );
				$body = str_replace( "{password}", $password, $body );

				$mail->Body = $body;

				$mail->send();
			} catch (Exception $e) {
				echo "Could not send mail to $name\n";
			}
		}
	}
}
