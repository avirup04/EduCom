<?php
require_once '../func.class.php';
//Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require_once $BASEDIR . '/vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {

    $body_msg = <<<EOF
    <!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body {
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #4285f4;
        }

        p {
            color: #666;
        }

        .qrsec {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .qrsec p {
            font-weight: 900;
            font-style: italic;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Welcome to EduCom World!</h1>
        <p>Dear [Username],</p>
        <p>Thank you for joining EduCom World, your go-to platform for coding education!</p>
        <p>Embark on a journey of learning and discovery as you explore our coding tutorials, projects, and interactive
            lessons.</p>
        <div class="qrsec">
            <img src="https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=educom://4376844847@edu-141-com-obx&choe=UTF-8"
                alt="QR Code">
            <p>While giving attendance, please scan this QR code to present to the administrator.<br><br>In case of any
                failure
                while giving attendance by QR code, you can give your attendance by using your <strong>User ID</strong>.
            </p>
        </div>
        <p>If you have any questions or need assistance, our dedicated support team is here to help you succeed in your
            coding endeavors.</p>
        <p>Happy coding!</p>
        <p>Best regards,</p>
        <p><b>The EduCom World Team</b></p>
    </div>
</body>

</html>

EOF;

    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'educom0075@gmail.com';                     //SMTP username
    $mail->Password   = 'pqcfudnrpmogsqdc';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('educom0075@gmail.com', 'EduCom');
    $mail->addAddress('shovanm50@gmail.com', 'Shovan Mondal');     //Add a recipient
    $mail->addAddress('avirupmukherjee019@gmail.com', 'Avirup');     //Add a recipient
    $mail->addReplyTo('educom0075+help@gmail.com', 'Help');

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    = $body_msg;
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    echo '<br><br><pre>';
    print_r($e);
}
