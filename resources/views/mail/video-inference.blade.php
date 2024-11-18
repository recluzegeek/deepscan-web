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

            .header {
                background: linear-gradient(to bottom right, rgb(79 70 229 / 0.05), rgb(99 102 241 / 0.05));
                border-bottom: 1px solid #e5e7eb;
                padding: 1.5rem;
                text-align: center;
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

            .title {
                color: #111827;
                font-size: 1.5rem;
                font-weight: 700;
                margin: 0;
                line-height: 1.25;
            }

            .subtitle {
                color: #6b7280;
                font-size: 1rem;
                margin-top: 0.5rem;
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

            .prediction-box {
                background: #4f46e5;
                color: white;
                border-radius: 0.5rem;
                padding: 1.5rem;
                text-align: center;
                margin: 1.5rem 0;
            }

            .prediction-label {
                font-size: 0.875rem;
                font-weight: 500;
                opacity: 0.9;
                text-transform: uppercase;
                letter-spacing: 0.025em;
            }

            .prediction-value {
                font-size: 1.875rem;
                font-weight: 700;
                margin: 0.5rem 0;
            }

            .confidence {
                font-size: 1rem;
                opacity: 0.9;
            }

            .button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                background-color: #4f46e5;
                color: #ffffff;
                font-size: 0.875rem;
                font-weight: 500;
                padding: 0.625rem 1.25rem;
                border-radius: 0.5rem;
                text-decoration: none;
                border: 1px solid transparent;
                box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
                transition: all 0.2s;
            }

            .button:hover {
                background: #4338ca;
            }

            .footer {
                background: #f9fafb;
                border-top: 1px solid #e5e7eb;
                padding: 1.5rem;
                text-align: center;
                font-size: 0.875rem;
                color: #6b7280;
            }

            .button-container {
                text-align: center;
                margin-top: 2rem;
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
                        <table>
                            <tr>
                                <td class="result-label">Filename:</td>
                                <td class="result-value">{{ $filename }}</td>
                            </tr>
                            <tr>
                                <td class="result-label">Uploaded On:</td>
                                <td class="result-value">{{ $uploaded_on }}</td>
                            </tr>
                            <tr>
                                <td class="result-label">Predicted Class:</td>
                                <td class="result-value">{{ $result }}</td>
                            </tr>
                            <tr>
                                <td class="result-label">Confidence Level:</td>
                                <td class="result-value">{{ $probability }}%</td>
                            </tr>
                        </table>
                    </div>
                    <p>While the system has classified this video as "{{ $result }}" with a {{ $probability }}%
                        confidence level, please keep in mind that no detection tool is perfect. There is always a
                        possibility of errors, and we encourage you to use the results as part of a broader evaluation.
                    </p>
                    <p>To explore the detailed report of your video analysis, please click the button below:</p>
                    <a href='{{ route('reports.show', ['id' => $video_id]) }}' class="hover-bg-blue-600"
                        style="display: inline-block; background-color: #3b82f6; margin-bottom: 24px ; padding-left: 24px; padding-right: 24px; padding-top: 16px; padding-bottom: 16px; text-align: center; font-size: 16px; font-weight: 600; text-transform: uppercase; color: #ffffff; text-decoration: none;">
                        <span style="mso-text-raise: 16px">View Full Report</span>
                    </a>
                    <p>Regards,<br />Deepscan Team</p>
                </div>
            </div>
        </div>
    </body>

    </html>
