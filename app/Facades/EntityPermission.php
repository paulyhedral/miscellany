<?php

namespace App\Facades;

use App\Models\Campaign;
use App\Models\MiscModel;
use App\Models\Entity;
use App\User;
use Illuminate\Support\Facades\Facade;

/**
 * Class EntityPermission
 * @package App\Facadesf
 *
 * @method static string entityIds(string $modelName)
 * @method static bool canView(Entity $entity, Campaign $campaign = null)
 * @method static bool canViewMisc(MiscModel $model, Campaign $campaign = null)
 * @method static bool hasPermission(string $modelName, string $action, User $user = null, $entity = null, Campaign $campaign = null)
 * @method static bool canRole(string $action, string $modelName, $user = null, Campaign $campaign = null)
 *
 * @see \App\Services\EntityPermission
 */
class EntityPermission extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'entitypermission';
    }
}
