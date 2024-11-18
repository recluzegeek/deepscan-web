    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>DeepScan Analysis Results</title>
        @if (request()->is('mail/preview'))
            <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
                rel="stylesheet">
        @endif
        <style>
            body {
                font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                line-height: 1.6;
                color: #1f2937;
                margin: 0;
                padding: 0;
                background-color: #f9fafb;
            }

            .container {
                max-width: 600px;
                margin: 2rem auto;
                padding: 0 1rem;
            }

            .card {
                background: #ffffff;
                border-radius: 0.75rem;
                box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
                overflow: hidden;
            }

            .logo-container {
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 1rem;
            }

            .logo-text {
                font-size: 1.25rem;
                font-weight: 600;
                color: #4f46e5;
                margin-left: 0.75rem;
            }

            .content {
                padding: 1.5rem;
            }

            .result-box {
                background: #f9fafb;
                border: 1px solid #e5e7eb;
                border-radius: 0.5rem;
                padding: 1rem;
                margin: 1rem 0;
            }

            .result-label {
                color: #6b7280;
                font-size: 0.875rem;
                font-weight: 500;
                margin-bottom: 0.25rem;
            }

            .result-value {
                color: #111827;
                font-size: 1rem;
                font-weight: 600;
            }

            .primary-button {
                display: inline-block;
                background-color: #4f46e5;
                color: #ffffff;
                padding: 12px 24px;
                border-radius: 8px;
                font-weight: 600;
                font-size: 14px;
                text-decoration: none;
                text-align: center;
                transition: background-color 0.2s;
                margin: 24px 0;
            }

            .primary-button:hover {
                background-color: #4338ca;
            }
            .prediction-badge {
                display: inline-block;
                padding: 6px 12px;
                border-radius: 9999px;
                font-weight: 600;
                font-size: 14px;
                margin: 8px 0;
            }
            .prediction-real {
                background-color: #dcfce7;
                color: #166534;
            }

            .prediction-fake {
                background-color: #fee2e2;
                color: #991b1b;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="card">


                <div class="content">
                    <p>Dear {{ $name }},</p>
                    <p>We’re excited to inform you that the analysis of your video has been completed! Our system has
                        reviewed your content to determine if it contains any potential deepfake elements.</p>
                    <p>Below are the key details and results of the analysis:</p>
                    <div class="result-box">
                        <table style="width: 100%">
                            <tr>
                                <td class="result-label">Filename:</td>
                                <td class="result-value">{{ $filename }}</td>
                            </tr>
                            <tr>
                                <td class="result-label">Uploaded:</td>
                                <td class="result-value">{{ $uploaded_on }}</td>
                            </tr>
                            <tr>
                                <td class="result-label">Classification:</td>
                                <td class="result-value">
                                    <span
                                        class="prediction-badge {{ $result === 'real' ? 'prediction-real' : 'prediction-fake' }}">
                                        {{ ucfirst($result) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="result-label">Confidence:</td>
                                <td class="result-value">{{ $probability }}%</td>
                            </tr>
                        </table>
                    </div>
                    <p>While the system has classified this video as "{{ ucfirst($result) }}" with a {{ $probability }}%
                        confidence level, please keep in mind that no detection tool is perfect. There is always a
                        possibility of errors, and we encourage you to use the results as part of a broader evaluation.
                    </p>
                    <p>To explore the detailed report of your video analysis, please click the button below:</p>
                    <div style="text-align: center;">
                        <a href="{{ route('reports.show', ['id' => $video_id]) }}" class="primary-button">
                            View Full Report
                        </a>
                    </div>
                    <p>Regards,<br />Deepscan Team</p>
                </div>
            </div>
        </div>
    </body>

    </html>
