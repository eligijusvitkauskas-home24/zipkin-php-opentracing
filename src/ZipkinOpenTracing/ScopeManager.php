<?php

namespace ZipkinOpenTracing;

use OpenTracing\ScopeManager as OTScopeManager;
use OpenTracing\Span as OTSpan;

final class ScopeManager implements OTScopeManager
{
    /**
     * @var Scope
     */
    private $active;

    /**
     * {@inheritdoc}
     */
    public function activate(OTSpan $span)
    {
        $this->active = new Scope($this, $span);

        return $this->active;
    }

    /**
     * {@inheritdoc}
     */
    public function getActive()
    {
        return $this->active;
    }

    public function getScope(OTSpan $span)
    {
        for ($scope = $this->active; $scope !== null; $scope = $scope->getToRestore()) {
            if ($span === $scope->getSpan()) {
                return $scope;
            }
        }

        return null;
    }

    public function setActive(Scope $scope = null)
    {
        $this->active = $scope;
    }
}
