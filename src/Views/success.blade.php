<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Successful</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-color: #f8f9fa;
        }

        @keyframes heartbeat {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }

        .bird {
            width: 100px;
            height: 100px;
            background-image: url('https://freelogopng.com/images/all_img/1656234782bkash-app-logo.png'); /* Replace with your bird image */
            background-size: cover;
            animation: heartbeat 1.5s ease-in-out infinite;
        }

        .success-message {
            font-size: 24px;
            margin-top: 20px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="bird"></div>
    <div class="success-message">
        Payment Successful.
    </div>
    <div class="success-message">
        @if(session('payment_confirm_message'))

       TxrID :  {{ session('payment_confirm_message') }}
@endif
    </div>
</body>
</html>
