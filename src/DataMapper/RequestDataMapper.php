<?php

declare(strict_types=1);

namespace Setono\SyliusFacebookPlugin\DataMapper;

use Setono\SyliusFacebookPlugin\ServerSide\ServerSideEventInterface;
use Symfony\Component\HttpFoundation\Request;
use Webmozart\Assert\Assert;

/* not final */ class RequestDataMapper implements DataMapperInterface
{
    /**
     * @psalm-assert-if-true Request $context['request']
     */
    public function supports(object $source, ServerSideEventInterface $target, array $context = []): bool
    {
        return isset($context['request'])
            && $context['request'] instanceof Request;
    }

    public function map(object $source, ServerSideEventInterface $target, array $context = []): void
    {
        Assert::true($this->supports($source, $target, $context));

        /** @var Request $request */
        $request = $context['request'];

        $target
            ->setEventSourceUrl($request->getUri())
        ;

        $userData = $target->getUserData();

        /** @psalm-suppress PossiblyNullArgument */
        $userData->setClientIpAddress($request->getClientIp());

        /** @psalm-suppress PossiblyNullArgument */
        $userData->setClientUserAgent($request->headers->get('User-Agent'));
    }
}
