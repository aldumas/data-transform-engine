<?php

namespace Engine\Core\Util;

trait SetPropertiesFromAssocTrait
{
    private function set_properties_from_assoc($assoc) : void
    {
        foreach (array_keys(get_class_vars(self::class)) as $var_name) {
            if (isset($assoc[$var_name])) {
                $this->$var_name = $assoc[$var_name];
            }
        }
    }
}
