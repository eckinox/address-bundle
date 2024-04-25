<?php

namespace Eckinox\AddressBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

final class EckinoxAddressBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
