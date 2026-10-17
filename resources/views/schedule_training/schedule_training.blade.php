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
        <h1>Training Schedule</h1>
        <p>Hello,</p>

        <p>A training session has been scheduled for you:</p>
        <ul>
            <li><strong>Training Time:</strong> {{ $meeting_time }}</li>
            <li><strong>Training Link:</strong> <a href="{{ $submitted_link }}">{{ $submitted_link }}</a></li>
        </ul>
        
        @if (!empty($comments))
            <p><strong>Comments/Instructions:</strong></p>
            <p>{{ $comments }}</p>
        @endif
        
        <p>Best regards,<br>Qdegrees</p>
    </div>
</body>
</html>