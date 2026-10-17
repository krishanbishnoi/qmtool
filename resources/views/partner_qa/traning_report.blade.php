<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Feedback Received</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        h1 {
            color: #333;
        }
        p {
            margin: 10px 0;
        }
        .feedback-details {
            background-color: #f9f9f9;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .feedback-details p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>New Training Request</h1>
        <p>Dear SME,</p>
        <p>You have received new Training Request. Here are the details:</p>
        <div class="feedback-details">
            <p><strong>Issue:</strong></p>
            <p>{{ 'feedback_data' }}</p>
        </div>
        <p>Best regards,</p>
        <p>Your Application</p>
    </div>
</body>
</html>