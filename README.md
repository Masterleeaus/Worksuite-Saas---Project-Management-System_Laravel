# Readme for worksuite

## Installation and runtime support policy

- **Primary supported install/runtime database:** MySQL 8.x.
- **SQLite:** supported for lightweight/local test flows only; full production parity is not guaranteed.
- **PHP runtime:** 8.3+.

## Required PHP extensions

Install these PHP extensions before running setup:

- mbstring
- pdo
- pdo_mysql
- dom
- curl
- zip
- bcmath
- intl
- gd
- exif
- fileinfo

## Clean install commands (matches install-check workflow)

```bash
composer install --no-interaction --prefer-dist --optimize-autoloader
npm ci --prefer-offline
npm run production

cp .env.example .env
php artisan key:generate

php artisan package:discover --ansi
php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan migrate --force --no-interaction
php artisan route:list > /tmp/route-list.txt
tail -5 /tmp/route-list.txt
php artisan about
```

## Queue, scheduler, and workers

- For background jobs in non-sync mode, run a queue worker (for example `php artisan queue:work`).
- Configure cron to run Laravel scheduler every minute:

```bash
* * * * * php /path/to/project/artisan schedule:run >> /dev/null 2>&1
```

- If realtime features are enabled, run Reverb worker:

```bash
php artisan reverb:start
```

### Plugins used in the app

<ol>
    <li>
        <strong>Bootstrap 4 </strong> - <a href="https://getbootstrap.com/">https://getbootstrap.com/</a>
    </li>
    <li>
        <strong>Moment.js </strong> - <a href="https://momentjs.com/">https://momentjs.com/</a>
    </li>
    <li>
        <strong>Bootstrap Select</strong> - <a href="https://developer.snapappointments.com/bootstrap-select/">https://developer.snapappointments.com/bootstrap-select/</a>
    </li>
    <li>
        <strong>Datepicker </strong> - <a href="https://github.com/qodesmith/datepicker">https://github.com/qodesmith/datepicker</a>
    </li>
    <li>
        <strong>Fontawesome </strong> - <a href="https://fontawesome.com/">https://fontawesome.com/</a>
    </li>
    <li>
        <strong>Bootstrap Icons (used in menu) </strong> - <a href="https://icons.getbootstrap.com/">https://icons.getbootstrap.com/</a>
    </li>
    <li>
        <strong>Dropify (used for file uploads) </strong> - <a href="https://github.com/JeremyFagis/dropify">https://github.com/JeremyFagis/dropify</a>
    </li>
    <li>
        <strong>sweetalert2 (used for alerts and notifications)</strong> - <a href="https://sweetalert2.github.io/">https://sweetalert2.github.io/</a>
    </li>
    <li>
        <strong>Quilljs (used for rich text editor)</strong> - <a href="https://quilljs.com/">https://quilljs.com/</a>
    </li>
    <li>
        <strong>Frappe Charts</strong> - <a href="https://frappe.io/charts">https://frappe.io/charts</a>
    </li>
    <li>
        <strong>Bootstrap MultiDatesPicker</strong> - <a href="https://github.com/uxsolutions/bootstrap-datepicker">https://github.com/uxsolutions/bootstrap-datepicker</a>
    </li>
    <li>
        <strong>Bootstrap Colorpicker</strong> - <a href="https://github.com/itsjavi/bootstrap-colorpicker">https://github.com/itsjavi/bootstrap-colorpicker</a>
    </li>
    <li>
        <strong>jQuery UI (used for sortable items)</strong> - <a href="https://jqueryui.com/">https://jqueryui.com/</a>
    </li>
    <li>
        <strong>Highlight JS (used for highlight html content)</strong> - <a href="https://github.com/highlightjs/highlight.js">highlight.min.js</a>
    </li>
    <li>
        <strong>Chart.js</strong> - <a href="https://www.chartjs.org/">https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js</a>
    </li>
    <li>
        <strong>Image Picker</strong> - <a href="https://rvera.github.io/image-picker/">https://rvera.github.io/image-picker/</a>
    </li>
    <li>
        <strong>Cropper.js</strong> - <a href="https://github.com/fengyuanchen/cropperjs">https://github.com/fengyuanchen/cropperjs</a>
    </li>
</ol>
