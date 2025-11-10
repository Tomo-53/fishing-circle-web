<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>パスワード再設定のご案内</title>
    <style>
        body {
            font-family: 'Hiragino Kaku Gothic ProN', 'ヒラギノ角ゴ ProN W3', Meiryo, メイリオ, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background-color: #4F46E5;
            color: white;
            padding: 30px;
            text-align: center;
        }
        .content {
            padding: 40px 30px;
        }
        .button {
            display: inline-block;
            padding: 15px 30px;
            background-color: #4F46E5;
            color: white !important;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #3730A3;
            color: white !important;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            font-size: 14px;
            color: #666;
            border-top: 1px solid #e9ecef;
        }
        .link-copy {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            word-break: break-all;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
        </div>

        <div class="content">
            <p>こんにちは！</p>

            <p>お客様のアカウントでパスワード再設定のリクエストを受信いたしました。</p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $actionUrl }}" class="button">パスワード再設定</a>
            </div>

            <p>このパスワード再設定リンクは<strong>60分</strong>で有効期限が切れます。</p>

            <p>もしパスワード再設定をご依頼されていない場合は、何もする必要はありません。</p>

            <div class="link-copy">
                <p><strong>「パスワード再設定」ボタンをクリックできない場合は、以下のURLをコピーしてブラウザに貼り付けてください：</strong></p>
                <p>{{ $actionUrl }}</p>
            </div>
        </div>

        <div class="footer">
            <p>よろしくお願いいたします。<br>{{ config('app.name') }}</p>
        </div>
    </div>
</body>
</html>
