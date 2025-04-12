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

## Project Architecture

This project is part of a larger ecosystem that includes three main components. The primary component is the DeepScan Platform (this repository), which provides the web interface, handles video processing and queue management, and visualizes results. The second component is the [DeepScan API](https://github.com/recluzegeek/deepscan-api), a REST service that receives extracted frames and returns deepfake probability scores, Grad-CAM overlays, and model explanations. The third is the [DeepScan Model](https://github.com/recluzegeek/deepscan-model), which contains code for training deep learning models, dataset preprocessing, evaluation, and interpretability tools.

## Tech Stack

- **Backend Framework**: Laravel 11 with InertiaJS
- **Frontend Framework**: Vue.js
- **CSS Framework**: Tailwind CSS
- **Database**: MariaDB
- **Queue System**: Redis with Laravel Horizon
- **File Storage**: MinIO
- **Video Processing**: FFmpeg integration via pbmedia/laravel-ffmpeg

## Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- Redis server
- FFmpeg
- MariaDB >10.7 for UUID support

## Vagrant

To run DeepScan using Vagrant, install [Vagrant](https://developer.hashicorp.com/vagrant/install) and [Oracle VirtualBox](https://www.virtualbox.org/wiki/Downloads), then navigate to the vagrant directory and run `vagrant up --provision`. Once the VMs are up, you can connect via VSCode using Remote SSH. Use the `vagrant ssh-config` command to configure the SSH keys needed for the connection. A good writeup on how to setup vagrant and vscode for remote development can be found [here](https://iximiuz.com/en/posts/how-to-setup-development-environment/).

## Local Installation

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
