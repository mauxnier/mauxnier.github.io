<?php
// header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Origin: https://mauxnier.github.io');

$emailSnackbar = [
    'success' => false,
    'message' => "Erreur inconnue. Veuillez réessayer"
];

if (isset($_POST['name']) && $_POST['name'] !== '') {
    if (isset($_POST['message']) && $_POST['message'] !== '') {

        // check if the email is valid
        if (isset($_POST['email']) && $_POST['email'] !== '') {
            if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

                // data send via contact form
                $userName = $_POST['name'];
                $userEmail = $_POST['email'];
                $message = $_POST['message'];

                // construct of the email
                $to = "monnier.killian.pro@gmail.com";
                $subject = "mauxnier.github.io - Nouveau message";
                $body = "";

                $body .= "From:" . $userName . "\r\n";
                $body .= "Email:" . $userEmail . "\r\n";
                $body .= "Message:" . $message . "\r\n";

                // @see https://stackoverflow.com/a/18854764
                $type = 'plain'; // or HTML
                $charset = 'utf-8';

                $mail     = 'no-reply@' . $_SERVER['SERVER_NAME'];
                $uniqid   = md5(uniqid(time()));
                $headers  = 'From: ' . $mail . "\n";
                $headers .= 'Reply-to: ' . $mail . "\n";
                $headers .= 'Return-Path: ' . $mail . "\n";
                $headers .= 'Message-ID: <' . $uniqid . '@' . $_SERVER['SERVER_NAME'] . ">\n";
                $headers .= 'MIME-Version: 1.0' . "\n";
                $headers .= 'Date: ' . gmdate('D, d M Y H:i:s', time()) . "\n";
                $headers .= 'X-Priority: 3' . "\n";
                $headers .= 'X-MSMail-Priority: Normal' . "\n";
                $headers .= 'Content-Type: multipart/mixed;boundary="----------' . $uniqid . '"' . "\n";
                $headers .= '------------' . $uniqid . "\n";
                $headers .= 'Content-type: text/' . $type . ';charset=' . $charset . '' . "\n";
                $headers .= 'Content-transfer-encoding: 7bit';

                // submit email
                $isAcceptedForDelivery = mail($to, $subject, $body, $headers);

                if ($isAcceptedForDelivery) {
                    $emailSnackbar = [
                        'success' => true,
                        'message' => "Votre message a bien été accepté. Je vous recontacterai au plus vite."
                    ];
                } else {
                    $emailSnackbar = [
                        'success' => false,
                        'message' => "Il y a eu une erreur. Veuillez me contacter directement par contact@killianmonnier.com"
                    ];
                }
            } else {
                $emailSnackbar = [
                    'success' => false,
                    'message' => "Veuillez renseigner une adresse de courriel valide."
                ];
            }
        } else {
            $emailSnackbar = [
                'success' => false,
                'message' => "Veuillez renseigner votre adresse de courriel."
            ];
        }
    } else {
        $emailSnackbar = [
            'success' => false,
            'message' => "Veuillez écrire votre message."
        ];
    }
} else {
    $emailSnackbar = [
        'success' => false,
        'message' => "Veuillez renseigner votre nom."
    ];
}

// send info to fetch
echo json_encode($emailSnackbar);
