<?php

namespace Akika\LaravelStanbic\Enums;

enum ChargeBearerType: string
{
    case Cred = 'CRED';
    case Debt = 'DEBT';
    case Shar = 'SHAR';
}
