# DeepScan Video Analysis Platform

A Laravel-based web application for analyzing videos using deep learning to detect potential deepfake video content.

## Features

- Video upload and processing
- Deepfake detection analysis with visual explanations
- Email notifications with results
- User authentication and authorization
- Report generation and management
- Interactive report interface
  - Side-by-side frame comparison
  - Grad-CAM visualization of suspicious regions
  - On-demand video generation
  - Detailed analysis metrics
- Real-time processing status updates

## Project Architecture

This repository is part of a larger DeepScan ecosystem consisting of three main components:

1. **DeepScan Platform** (this repo)
   - Main web application for video upload and analysis
   - User interface and result visualization
   - Video processing and frame extraction
   - Queue management and notifications
   - Interactive report interface featuring:
     - Side-by-side comparison of original and analyzed frames
     - On-demand video generation of analysis results
     - Detailed frame-by-frame analysis visualization
     - Export and sharing capabilities

2. **DeepScan API** ([deepscan-api](https://github.com/recluzegeek/deepscan-api))
   - REST API for deepfake detection inference
   - Receives extracted frames from the main platform
     - Frame-by-frame deepfake probability scores
     - Grad-CAM visualizations highlighting suspicious regions
     - Explanation data for model decisions

   - Handles model deployment and scaling

3. **DeepScan Model** ([deepscan-model](https://github.com/recluzegeek/deepscan-model))
   - Deep learning model training code
   - Dataset preparation and preprocessing
   - Model evaluation and validation
   - Model interpretability and visualization tools

## Tech Stack

- **Backend Framework**: Laravel 11
- **Frontend Framework**: Vue.js 3 with Inertia.js
- **CSS Framework**: Tailwind CSS
- **Database**: MySQL/PostgreSQL
- **Queue System**: Redis with Laravel Horizon
- **File Storage**: SFTP support via Flysystem
- **Video Processing**: FFmpeg integration via pbmedia/laravel-ffmpeg

## Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- Redis server
- FFmpeg
- MySQL/PostgreSQL

## Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/your-repo/deepscan.git
   cd deepscan
   ```

2. Install PHP Dependencies:

    ```bash
    composer install
    ```

3. Install Javascript Dependencies:

    ```bash
    npm install
    ```

4. Create Enviornment File:

    ```bash
    cp .env.example .env
    ```

5. Generate Application Key:

    ```bash
    php artisan key:generate
    ```

6. Configure your database in `.env` file:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

7. Run Migrations:

    ```bash
    php artisan migrate
    ```

8. Build Frontend Assets:

    ```bash
    npm run build
    ```

## Running the Application

1. Start the Development Server:

    ```bash
    php artisan serve
    ```

2. Start the Frontend Development Server:

    ```bash
    npm run dev
    ```

3. Start the Queue Worker:

    ```bash
    php artisan queue:work deepscan_model
    php artisan horizon
    ```

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License.

## Acknowledgments

- Laravel Framework
- Vue.js
- Tailwind CSS
- FFmpeg
