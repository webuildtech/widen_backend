<?php

namespace App\Enums;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
enum GameStatus: string
{
    case PUBLISHED = 'published';

    case FINISHED = 'finished';

    case CANCELED = 'canceled';

    case CANCELED_WITHOUT_REFUND = 'canceled_without_refund';

    public function isCanceled(): bool
    {
        return in_array($this, [self::CANCELED, self::CANCELED_WITHOUT_REFUND], true);
    }

    public function forUser(): self
    {
        return $this->isCanceled() ? self::CANCELED : self::PUBLISHED;
    }
}
