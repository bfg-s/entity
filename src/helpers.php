<?php

use Bfg\Entity\ClassGetter;
use Bfg\Entity\Core\Entities\ArrayEntity;
use Bfg\Entity\Core\Entities\ClassEntity;
use Bfg\Entity\Core\Entities\ClassMethodEntity;
use Bfg\Entity\Core\Entities\ClassPropertyEntity;
use Bfg\Entity\Core\Entities\DocumentorEntity;
use Bfg\Entity\Core\Entities\Helpers\DocumentorHelper;
use Bfg\Entity\Core\Entities\NamespaceEntity;
use Bfg\Entity\Core\Entities\ParamEntity;
use Bfg\Entity\Core\EntityPhp;
use Bfg\Entity\Core\Saver;

if (! function_exists('push_to_gitignore')) {
    /**
     * @param  string  $path
     * @return bool
     */
    function push_to_gitignore(string $path)
    {
        $gitignore = file_get_contents(base_path('.gitignore'));

        $add_to_ignore = '';

        if (strpos($gitignore, $path) === false) {
            $add_to_ignore .= "{$path}\n";
        }

        if ($add_to_ignore) {
            return (bool) file_put_contents(base_path('.gitignore'), trim($gitignore)."\n".$add_to_ignore);
        }

        return false;
    }
}

if (! function_exists('class_in_file')) {
    /**
     * @param  string  $file
     * @return string|null
     */
    function class_in_file(string $file)
    {
        return (new ClassGetter())->getClassFullNameFromFile($file);
    }
}

if (! function_exists('array_export')) {
    /**
     * @param $expression
     * @return string
     */
    function array_export($expression)
    {
        $export = var_export($expression, true);
        $patterns = [
            "/array \(/" => '[',
            "/^([ ]*)\)(,?)$/m" => '$1]$2',
            "/=>[ ]?\n[ ]+\[/" => '=> [',
            "/([ ]*)(\'[^\']+\') => ([\[\'])/" => '$1$2 => $3',
        ];
        $export = preg_replace(array_keys($patterns), array_values($patterns), $export);

        return $export;
    }
}

if (! function_exists('entity')) {
    /**
     * @param $data
     * @return EntityPhp
     */
    function entity($data)
    {
        return new EntityPhp($data);
    }
}

if (! function_exists('namespace_entity')) {
    /**
     * @param  string  $name
     * @return NamespaceEntity
     */
    function namespace_entity(string $name)
    {
        return new NamespaceEntity($name);
    }
}

if (! function_exists('class_entity')) {
    /**
     * @param  string  $name
     * @return ClassEntity
     */
    function class_entity(string $name)
    {
        return new ClassEntity($name);
    }
}

if (! function_exists('class_method_entity')) {
    /**
     * @param  string  $name
     * @return ClassMethodEntity
     */
    function class_method_entity(string $name, ClassEntity $parent = null)
    {
        return new ClassMethodEntity($name, $parent);
    }
}

if (! function_exists('param_entity')) {
    /**
     * @return ParamEntity
     */
    function param_entity()
    {
        return new ParamEntity();
    }
}

if (! function_exists('class_property_entity')) {
    /**
     * @param  string  $name
     * @param  string  $value
     * @return ClassPropertyEntity
     */
    function class_property_entity(string $name, $value = ClassPropertyEntity::NONE_PARAM)
    {
        return new ClassPropertyEntity($name, $value);
    }
}

if (! function_exists('doc_entity')) {
    /**
     * @return DocumentorEntity
     */
    function doc_entity(): DocumentorEntity
    {
        return new DocumentorEntity();
    }
}

if (! function_exists('get_doc_var')) {
    /**
     * @param  string  $doc
     * @param  string  $var_name
     * @return string
     */
    function get_doc_var(string $doc, string $var_name): string
    {
        return DocumentorHelper::get_variable($doc, $var_name);
    }
}

if (! function_exists('array_entity')) {
    /**
     * @param $data
     * @return ArrayEntity
     */
    function array_entity($data): ArrayEntity
    {
        return new ArrayEntity($data);
    }
}

if (! function_exists('saver')) {
    /**
     * @param $data
     * @return Saver
     * @throws Exception
     */
    function saver($data): Saver
    {
        return new Saver($data);
    }
}

if (! function_exists('var_export_array')) {
    /**
     * Convert array to PHP.
     *
     * @param  array  $data
     * @param  bool  $compress
     * @param  int  $max_chars
     * @return string
     */
    function var_export_array(array $data = [], bool $compress = false, int $max_chars = 0)
    {
        return array_entity($data)->setMinimized($compress)->setMaxChars($max_chars)->render();
    }
}

if (! function_exists('config_file_wrapper')) {
    /**
     * File config wrapper from save.
     *
     * @param  array  $data
     * @param  bool  $compress
     * @param  int  $max_chars
     * @return string
     */
    function config_file_wrapper(array $data = [], bool $compress = false, int $max_chars = 0)
    {
        return array_entity($data)->setMinimized($compress)->setMaxChars($max_chars)->wrap('php:return')->render();
    }
}

if (! function_exists('pars_description_from_doc')) {
    /**
     * Pars PHP Doc for getting the description in one line.
     *
     * @param  string|\Illuminate\Contracts\Support\Renderable  $doc
     * @param  string  $glue
     * @return string
     */
    function pars_description_from_doc($doc, string $glue = ' ')
    {
        return \Bfg\Entity\Core\Entities\DocumentorEntity::parseDescription($doc, $glue);
    }
}

if (! function_exists('pars_return_from_doc')) {
    /**
     * Pars RETURN.
     *
     * @param  string|\Illuminate\Contracts\Support\Renderable  $doc
     * @return string
     */
    function pars_return_from_doc($doc)
    {
        return \Bfg\Entity\Core\Entities\DocumentorEntity::parseReturn($doc);
    }
}

if (! function_exists('refl_param_entity')) {
    /**
     * @param  ReflectionParameter  $item
     * @param  bool  $no_types
     * @param  bool  $no_values
     * @return string
     */
    function refl_param_entity(ReflectionParameter $item, $no_types = false, $no_values = false)
    {
        return \Bfg\Entity\Core\Entities\ParamEntity::buildFromReflection($item, $no_types, $no_values)->render();
    }
}

if (! function_exists('refl_params_entity')) {
    /**
     * @param  array|\ReflectionParameter|\ReflectionFunction|\ReflectionMethod|\Closure  $params
     * @param  bool  $no_types
     * @param  bool  $no_values
     * @return string
     */
    function refl_params_entity($params, $no_types = false, $no_values = false)
    {
        return \Bfg\Entity\Core\Entities\ParamEntity::buildFromReflection($params, $no_types, $no_values)->render();
    }
}
