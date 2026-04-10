<?php

return [
    /*
     | Titan AI Bridge
     | This module can emit signals and request drafts via TitanZero.
     | Safe by default: bridge is inert if TitanZero isn't installed/enabled.
     |
     | Note: 'service' is a string (not ::class) to avoid fatal errors
     | when TitanZero is not installed. The bridge resolves it lazily.
     */
    'titan' => [
        'enabled' => true,
        'service' => 'Modules\\TitanZero\\Services\\ZeroGateway',
    ],
];
