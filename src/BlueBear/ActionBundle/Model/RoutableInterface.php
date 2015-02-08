<?php

namespace ComTSo\ForumBundle\Entity;

/**
 * Allow entity to set their route parameters themselves
 * @author vincent
 */
interface Routable
{
    public function getRoutingParameters();
}
