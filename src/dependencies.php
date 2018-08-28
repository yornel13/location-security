<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// Database
$container['db'] = function ($c) {
    $connectionString = $c->get('settings')['connectionString'];

    $pdo = new PDO($connectionString['dns'], $connectionString['user'], $connectionString['pass']);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

    return new FluentPDO($pdo);
};

// Models
$container['model'] = function ($c) {
    return (object) [
        'auth' => new App\Model\AuthModel($c->db),
        'vehicle' => new App\Model\VehicleModel($c->db),
        'guard' => new App\Model\GuardModel($c->db),
        'admin' => new App\Model\AdminModel($c->db),
        'visitor' => new App\Model\VisitorModel($c->db),
        'clerk' => new App\Model\ClerkModel($c->db),
        'visitorVehicle' => new App\Model\VisitorVehicleModel($c->db),
        'visit' => new App\Model\VisitModel($c->db),
        'incidence' => new App\Model\IncidenceModel($c->db),
        'specialReport' => new App\Model\SpecialReportModel($c->db),
        'watch' => new App\Model\WatchModel($c->db),
        'reply' => new App\Model\SpecialReportReplyModel($c->db),
        'alert' => new App\Model\AlertModel($c->db),
        'company' => new App\Model\CompanyModel($c->db),
        'utility' => new App\Model\UtilityModel($c->db),
        'tablet' => new App\Model\TabletModel($c->db),
        'messenger' => new App\Model\MessengerModel($c->db),
        'banner' => new App\Model\BannerModel($c->db),
        'bounds' => new App\Model\BoundsModel($c->db),
        'stand' => new App\Model\StandModel($c->db)
    ];
};
