<?php

/*
 * This file is part of the API Platform project.
 *
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ApiPlatform\Symfony\Validator\Exception;

use ApiPlatform\JsonLd\ContextBuilderInterface;
use ApiPlatform\Metadata\Error as ErrorOperation;
use ApiPlatform\Metadata\ErrorResource;
use ApiPlatform\Metadata\Exception\HttpExceptionInterface;
use ApiPlatform\Metadata\Exception\ProblemExceptionInterface;
use ApiPlatform\Validator\Exception\ConstraintViolationListAwareExceptionInterface as ApiPlatformConstraintViolationListAwareExceptionInterface;
use ApiPlatform\Validator\Exception\ValidationException as BaseValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface as SymfonyHttpExceptionInterface;
use Symfony\Component\WebLink\Link;

/**
 * Thrown when a validation error occurs.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
#[ErrorResource(
    uriTemplate: '/validation_errors/{id}',
    status: 422,
    openapi: false,
    uriVariables: ['id'],
    provider: 'api_platform.validator.state.error_provider',
    shortName: 'ConstraintViolationList',
    operations: [
        new ErrorOperation(
            name: '_api_validation_errors_problem',
            outputFormats: ['json' => ['application/problem+json']],
            normalizationContext: ['groups' => ['json'],
                'skip_null_values' => true,
                'rfc_7807_compliant_errors' => true,
            ]
        ),
        new ErrorOperation(
            name: '_api_validation_errors_hydra',
            outputFormats: ['jsonld' => ['application/problem+json']],
            links: [new Link(rel: ContextBuilderInterface::JSONLD_NS.'error', href: 'http://www.w3.org/ns/hydra/error')],
            normalizationContext: [
                'groups' => ['jsonld'],
                'skip_null_values' => true,
                'rfc_7807_compliant_errors' => true,
            ]
        ),
        new ErrorOperation(
            name: '_api_validation_errors_jsonapi',
            outputFormats: ['jsonapi' => ['application/vnd.api+json']],
            normalizationContext: ['groups' => ['jsonapi'], 'skip_null_values' => true, 'rfc_7807_compliant_errors' => true]
        ),
    ],
    graphQlOperations: []
)]
final class ValidationException extends BaseValidationException implements ConstraintViolationListAwareExceptionInterface, ApiPlatformConstraintViolationListAwareExceptionInterface, \Stringable, ProblemExceptionInterface, HttpExceptionInterface, SymfonyHttpExceptionInterface
{
}
