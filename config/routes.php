<?php

declare(strict_types=1);

use League\Plates\Engine;
use Psr\Http\Message\ResponseInterface;
use Slim\App;
use Slim\Psr7\NonBufferedBody;

return function (App $app) {
    $app->get('/', function (ResponseInterface $response) use ($app): ResponseInterface {
        /** @var Engine $template */
        $template = $app->getContainer()->get(Engine::class);
        $contents = $template->render('home');
        $response->getBody()->write($contents);

        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
    });

    $app->get('/stocks', function (ResponseInterface $response) use ($app): ResponseInterface {
        $value = number_format(rand(30, 200), 2);
        $stocks = [
            ['symbol' => 'AAPL', 'value' => $value],
            ['symbol' => 'GOOGL', 'value' => $value],
            ['symbol' => 'MSFT', 'value' => $value],
            ['symbol' => 'AMZN', 'value' => $value],
            ['symbol' => 'FB', 'value' => $value],
            ['symbol' => 'TSLA', 'value' => $value],
        ];

        $response->getBody()->write(json_encode($stocks));

        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    });

    $app->get('/updates', function (ResponseInterface $response) use ($app): ResponseInterface {
        /** @var Engine $template */
        $template = $app->getContainer()->get(Engine::class);
        $contents = $template->render('updates');
        $response->getBody()->write($contents);

        return $response
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
    });

    $app->get('/updates-handle', function (ResponseInterface $response): ResponseInterface {
        $response = $response
            ->withBody(new NonBufferedBody())
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Content-Type', 'text/event-stream')
            ->withHeader('Cache-Control', 'no-cache')
            ->withHeader('X-Accel-Buffering', 'no');

        $body = $response->getBody();
        $symbols = ['AAPL', 'GOOGL', 'MSFT', 'AMZN', 'FB', 'TSLA'];

        while (true) {
            $data = json_encode([
                'symbol' => $symbols[array_rand($symbols)],
                'value' => number_format(rand(100, 200), 2),
            ]);

            $id = uniqid(more_entropy: true);
            $event = "id: {$id}\nevent: stock:update\ndata: $data\n\n";
            $body->write($event . ' ');

            if (ob_get_length() !== false) {
                ob_end_flush();
                flush();
            }

            if (connection_aborted()) {
                break;
            }

            usleep(100000);
        }

        return $response;
    });
};
