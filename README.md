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
- MinIO
- FFmpeg
- MariaDB >10.7 for UUID support

## Vagrant

To run DeepScan using Vagrant, install [Vagrant](https://developer.hashicorp.com/vagrant/install) and [Oracle VirtualBox](https://www.virtualbox.org/wiki/Downloads), then navigate to the vagrant directory and run `vagrant up --provision`. Once the VMs are up, you can connect via VSCode using Remote SSH. Use the `vagrant ssh-config` command to configure the SSH keys needed for the connection. A good writeup on how to setup vagrant and vscode for remote development can be found [here](https://iximiuz.com/en/posts/how-to-setup-development-environment/).

### Required Vagrant Plugins

Please install the following Vagrant plugins before running `vagrant up`.

```bash
vagrant plugin install vagrant-vbguest
vagrant plugin install vagrant-hostmanager
```

> [!CAUTION]
> If you get error of conflicting dependencies chains during plugins installation, try this [temporary workaround.](https://github.com/dotless-de/vagrant-vbguest/issues/410)

> [!NOTE]
> The Laravel application will be available at `http://192.168.56.10`, while the MinIO browser console can be accessed at `http://192.168.56.5:9001`.

> [!TIP]
> All related configuration details can be found in the `vagrant/Vagrantfile` and their corresponding provisioning bash scripts.

## Local Installation

> [!TIP]
> Feeling overwhelmed with the instructions?
> No worries — you always have the Vagrant setup as a fallback. But if you're ready to go the manual route, here's how to install everything locally:

1. Clone the repository:

   ```bash
   git clone https://github.com/recluzegeek/deepscan.git
   cd deepscan
   ```

2. Install PHP dependencies:

    ```bash
    composer install
    ```

3. Install Javascript dependencies:

    ```bash
    npm install
    ```

4. Create environment file by copying `.env.example` file:

    ```bash
    cp .env.example .env
    ```

5. Install a [Redis](https://redis.io/downloads/) client compatible with your operating system and ensure it’s running. Configure the Redis connection settings in the `.env` file.

6. Set up your preferred mail service (e.g., SMTP, Mailgun, Sendmail, Mailtrap) and update the corresponding environment variables in the `.env` file.

> [!IMPORTANT]
> Make sure to update the mailer settings in your `.env` file (MAIL_HOST, MAIL_PORT, etc.) with valid credentials.
> If left as-is or misconfigured, Laravel will default to the log mailer, and all emails will be written to `storage/logs/laravel.log` instead of being sent.

7. Install [MinIO](https://min.io/open-source/download) and configure it by creating the required buckets. Generate access and secret keys, then update the corresponding values in your Laravel `.env` file.

8. Generate Application key:

    ```bash
    php artisan key:generate
    ```

9. Configure your database in `.env` file:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

10. Run Database migrations:

    ```bash
    php artisan migrate
    ```

11. Build Frontend Assets:

    ```bash
    npm run build
    ```

## Running the Application

1. Start the development server and access on `localhost:8000`:

    ```bash
    php artisan serve
    ```

2. Start the Frontend development server:

    ```bash
    npm run dev
    ```

3. Start the Laravel Horizon and can be accessed via `http://your-app-domain/horizon` or `localhost:8000/horizon`.

    ```bash
    php artisan horizon
    ```
