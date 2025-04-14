<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

class TrustHosts extends Middleware
{
    /**
     * Get the host patterns that should be trusted.
     *
     * @return array<int, string|null>
     */
    public function hosts(): array
{
    return [
        '192.168.222.17',
        '192.168.222.17:7080',
        $this->allSubdomainsOfApplicationUrl(),
    ];
}

}
