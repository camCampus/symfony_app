<?php

namespace App\Service;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class AdminLog implements RequestMatcherInterface
{
    private $requestStack;

    public function __construct(
        private Security $security,
        RequestStack     $requestStack,
    )
    {
        $this->requestStack = $requestStack;
    }

    public function getFireWall(): string
    {
        $request = $this->requestStack->getCurrentRequest();
        $firewallName = $this->security->getFirewallConfig($request)?->getName();
        return $firewallName;
    }

    public function matches(Request $request): bool
    {
        $match = false;
        if ($request->getPathInfo() === "/duck") {
            return $match = true;
        }
        return $match;
    }
}