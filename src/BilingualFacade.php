<?php

namespace Subashrijal5\Bilingual;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Subashrijal5\Bilingual\Skeleton\SkeletonClass
 */
class BilingualFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'bilingual';
    }
}
