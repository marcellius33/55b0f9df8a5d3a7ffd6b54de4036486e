<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Services\EmailService;
use Exception;
use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as v;

class EmailController
{
    private $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }

    public function sendEmail(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();

        // Validate Data
        $validator = v::keySet(
            v::key('to', v::email()),
            v::key('subject', v::stringType()->notEmpty()),
            v::key('body', v::stringType()->notEmpty()),
        );

        try {
            $validator->assert($data);
            $this->emailService->sendEmail($data['to'], $data['subject'], $data['body']);
        } catch (ValidationException $exception) {
            $response->getBody()->write(json_encode([
                'error' => 'Validation failed',
                'messages' => $exception->getMessages(),
            ]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        } catch (Exception $exception) {
            $response->getBody()->write(json_encode([
                'error' => 'An unexpected error occurred',
            ]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }

        $response->getBody()->write(json_encode(['status' => 'Email Sent!']));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
