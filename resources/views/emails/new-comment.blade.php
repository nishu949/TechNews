<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Comment Notification</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f7fafc;
            margin: 0;
            padding: 0;
            line-height: 1.6;
            color: #2d3748;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .email-wrapper {
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }
        .header {
            background: linear-gradient(135deg, #2d3748, #1a202c);
            padding: 30px 40px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .header p {
            color: #a0aec0;
            margin: 5px 0 0;
            font-size: 14px;
        }
        .content {
            padding: 40px;
        }
        .comment-box {
            background-color: #f7fafc;
            border-left: 4px solid #4299e1;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .comment-box p {
            margin: 0;
            color: #4a5568;
            white-space: pre-wrap;
            line-height: 1.8;
        }
        .btn {
            display: inline-block;
            background-color: #2d3748;
            color: #ffffff;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
        }
        .btn:hover {
            background-color: #1a202c;
        }
        .meta-info {
            color: #718096;
            font-size: 14px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }
        .footer {
            background-color: #f7fafc;
            padding: 20px 40px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        .footer p {
            margin: 0;
            color: #a0aec0;
            font-size: 13px;
        }
        .footer .heart {
            color: #fc8181;
        }
        @media (max-width: 480px) {
            .header { padding: 20px; }
            .content { padding: 20px; }
            .footer { padding: 15px 20px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="email-wrapper">
            <!-- Header -->
            <div class="header">
                <h1>📝 New Comment</h1>
                <p>Someone commented on your article</p>
            </div>
            
            <!-- Content -->
            <div class="content">
                <p style="font-size: 16px;">
                    <strong style="font-size: 18px; color: #2d3748;">
                        {{ $comment->user->name }}
                    </strong> 
                    left a comment on your article:
                </p>
                
                <h2 style="font-size: 20px; color: #2d3748; margin: 20px 0 10px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px;">
                    "{{ $comment->article->title }}"
                </h2>
                
                <div class="comment-box">
                    <p>{{ $comment->comment }}</p>
                </div>
                
                <div style="text-align: center; margin: 30px 0;">
                    <a href="{{ route('articles.show', $comment->article->slug) }}" class="btn">
                        View Article & Reply
                    </a>
                </div>
                
                <div class="meta-info">
                    <span>
                        <strong>Posted:</strong> 
                        {{ $comment->created_at->format('F d, Y \a\t H:i') }}
                    </span>
                    <span>
                        <strong>By:</strong> 
                        {{ $comment->user->email }}
                    </span>
                </div>
                
                <p style="color: #718096; font-size: 14px; margin-top: 20px;">
                    💡 You can manage all comments from your dashboard.
                </p>
            </div>
            
            <!-- Footer -->
            <div class="footer">
                <p>
                    &copy; {{ date('Y') }} TechNews. All rights reserved.
                </p>
                <p style="margin-top: 5px;">
                    Built with <span class="heart">❤</span> for developers
                </p>
            </div>
        </div>
    </div>
</body>
</html>