<?php

declare(strict_types=1);

namespace App\Commons\Listener;

use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NoResultException;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Throwable;

#[AsEventListener(event: ExceptionEvent::class)]
class ExceptionListener
{
    public function __construct(private readonly bool $debug)
    {
    }

    #[NoReturn] public function __invoke(ExceptionEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }


        $throwable = $event->getThrowable();

        if ($throwable->getPrevious() && $throwable->getPrevious() instanceof ValidationFailedException) {
            /** @var ValidationFailedException $exception */
            $exception = $throwable->getPrevious();
            $response = new  JsonResponse($this->extractViolations($exception->getViolations()), Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $response = new JsonResponse($this->getData($throwable), $this->getStatusCode($throwable));
        }

        $event->allowCustomResponseCode();
        $event->setResponse($response);

    }

    private function extractViolations(ConstraintViolationListInterface $violationList): array
    {
        $violations = [];
        foreach ($violationList as $violation) {
            $violations[] = [$violation->getPropertyPath() => $violation->getMessage()];
        }
        return [
            'title' => Response::$statusTexts[Response::HTTP_UNPROCESSABLE_ENTITY],
            'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'violations' => $violations
        ];

    }

    private function getData(Throwable $throwable): array
    {
        $statusCode = $this->getStatusCode($throwable);

        $data = [
            'title' => Response::$statusTexts[$statusCode] ?? Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
            'status' => $statusCode,
            'details' => $throwable->getMessage()
        ];

        if ($this->debug) {
            $data['file'] = $throwable->getFile();
            $data['line'] = $throwable->getLine();
            $data['trace'] = $throwable->getTrace();
        }

        return $data;
    }


    private function getStatusCode(Throwable $throwable): int
    {
        return match (get_class($throwable)) {
            MethodNotAllowedHttpException::class,
            UnprocessableEntityHttpException::class,
            NotFoundHttpException::class,
            NoResultException::class,
            EntityNotFoundException::class => Response::HTTP_NOT_FOUND,
            BadRequestHttpException::class, BadRequestException::class => Response::HTTP_BAD_REQUEST,
            default => $throwable->getCode() > 99 && $throwable->getCode() < 512
                ? $throwable->getCode()
                : Response::HTTP_INTERNAL_SERVER_ERROR
        };
    }


}