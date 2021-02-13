<?php

use Bfg\Entity\Core\Entities\ArrayEntity;
use Bfg\Entity\Core\Entities\ClassEntity;
use Bfg\Entity\Core\Entities\ClassMethodEntity;
use Bfg\Entity\Core\Entities\ClassPropertyEntity;
use Bfg\Entity\Core\Entities\DocumentorEntity;
use Bfg\Entity\Core\Entities\NamespaceEntity;
use Bfg\Entity\Core\Entities\ParamEntity;
use Bfg\Entity\Core\EntityPhp;

if (!function_exists('class_in_file')) {

    /**
     * @param  string  $file
     * @return string|null
     */
    function class_in_file (string $file) {

        return (new \Bfg\Entity\ClassGetter())->getClassFullNameFromFile($file);
    }
}

if (!function_exists('array_export')) {

    /**
     * @param $expression
     * @return string
     */
    function array_export($expression) {
        $export = var_export($expression, TRUE);
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

if (!function_exists('entity')) {

    /**
     * @param $data
     * @return EntityPhp
     */
    function entity ($data) {

        return (new EntityPhp($data));
    }
}

if (!function_exists('namespace_entity')) {

    /**
     * @param string $name
     * @return NamespaceEntity
     */
    function namespace_entity (string $name) {

        return (new NamespaceEntity($name));
    }
}

if (!function_exists('class_entity')) {

    /**
     * @param string $name
     * @return ClassEntity
     */
    function class_entity (string $name) {

        return (new ClassEntity($name));
    }
}

if (!function_exists('class_method_entity')) {

    /**
     * @param string $name
     * @return ClassMethodEntity
     */
    function class_method_entity (string $name, ClassEntity $parent = null) {

        return (new ClassMethodEntity($name, $parent));
    }
}

if (!function_exists('param_entity')) {

    /**
     * @return ParamEntity
     */
    function param_entity () {

        return (new ParamEntity());
    }
}

if (!function_exists('class_property_entity')) {

    /**
     * @param string $name
     * @param string $value
     * @return ClassPropertyEntity
     */
    function class_property_entity (string $name, $value = ClassPropertyEntity::NONE_PARAM) {

        return (new ClassPropertyEntity($name, $value));
    }
}

if (!function_exists('documentor_entity')) {

    /**
     * @return DocumentorEntity
     */
    function documentor_entity () {

        return (new DocumentorEntity());
    }
}

if (!function_exists('get_doc_var')) {

    /**
     * @param  string  $doc
     * @param  string  $var_name
     * @return string
     */
    function get_doc_var (string $doc, string $var_name) {

        return \Bfg\Entity\Core\Entities\Helpers\DocumentorHelper::get_variable($doc, $var_name);
    }
}

if (!function_exists('array_entity')) {

    /**
     * @param $data
     * @return ArrayEntity
     */
    function array_entity ($data) {

        return (new ArrayEntity($data));
    }
}

if (!function_exists('saver')) {

    /**
     * @param $data
     * @return \Bfg\Entity\Core\Saver
     * @throws Exception
     */
    function saver ($data) {

        return new \Bfg\Entity\Core\Saver($data);
    }
}

if (!function_exists('file_get_lines')) {

    /**
     * @param string $file
     * @param int $from
     * @param int $to
     * @return null|string
     */
    function file_get_lines (string $file, int $from = 0, int $to = 0) {

        if (is_file($file)) {

            $file_data = explode("\n", file_get_contents($file));

            foreach ($file_data as $num => $file_line) {

                $num_line = $num + 1;

                if ($num_line < $from || $num_line > $to) {

                    unset($file_data[$num]);
                }
            }

            return implode("\n", $file_data);
        }

        return null;
    }
}

if (! function_exists('eloquent_instruction')) {

    /**
     * [ ==|=|is  (VALUE)] = where('name', '=', 'value')
     * [ <=       (VALUE)] = where('name', '<=', 'value')
     * [ >=       (VALUE)] = where('name', '>=', 'value')
     * [ <        (VALUE)] = where('name', '<', 'value')
     * [ >        (VALUE)] = where('name', '>', 'value')
     * [ !=|not   (VALUE)] = where('name', '!=', 'value')
     * [ %%|like  (VALUE)] = where('name', 'like', '%value%')
     * [ %|%like  (VALUE)] = where('name', 'like', '%value')
     * [ !%|like% (VALUE)] = where('name', 'like', 'value%')
     * [ in       (VALUE)] = whereIn('name', explode(';', 'value;value...'))
     * [ not in   (VALUE)] = whereNotIn('name', explode(';', 'value;value...'))
     * [ not null (VALUE)] = whereNotNull('name')
     * [ null     (VALUE)] = whereNull('name')
     *
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\Relation $eloquent
     * @param array $instructions
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\Relation
     */
    function eloquent_instruction ($eloquent, array $instructions) {

        return \Bfg\Entity\Core\Accessor::create($eloquent)->eloquentInstruction($instructions);
    }
}

if (! function_exists("var_export_array")) {

    /**
     * Convert array to PHP
     *
     * @param array $data
     * @param bool $compress
     * @param int $max_chars
     * @return string
     */
    function var_export_array(array $data = [], bool $compress = false, int $max_chars = 0) {

        return array_entity($data)->setMinimized($compress)->setMaxChars($max_chars)->render();
    }
}

if ( ! function_exists('multi_dot_call') ) {


    /**
     * @param $obj
     * @param string $dot_path
     * @return mixed|null
     */
    function multi_dot_call ($obj, string $dot_path) {

        return \Bfg\Entity\Core\Accessor::create($obj)->dotCall($dot_path);
    }
}

if (! function_exists("config_file_wrapper")) {

    /**
     * File config wrapper from save
     *
     * @param array $data
     * @param bool $compress
     * @param int $max_chars
     * @return string
     */
    function config_file_wrapper(array $data = [], bool $compress = false, int $max_chars = 0)
    {
        return array_entity($data)->setMinimized($compress)->setMaxChars($max_chars)->wrap('php:return')->render();
    }
}

if (!function_exists("pars_description_from_doc")) {

    /**
     * Pars PHP Doc for getting the description in one line.
     *
     * @param string|\Illuminate\Contracts\Support\Renderable $doc
     * @param string $glue
     * @return string
     */
    function pars_description_from_doc($doc, string $glue = " ") {

        return \Bfg\Entity\Core\Entities\DocumentorEntity::parseDescription($doc, $glue);
    }
}

if (!function_exists("pars_return_from_doc")) {

    /**
     * Pars RETURN.
     *
     * @param string|\Illuminate\Contracts\Support\Renderable $doc
     * @return string
     */
    function pars_return_from_doc($doc) {

        return \Bfg\Entity\Core\Entities\DocumentorEntity::parseReturn($doc);
    }
}

if (!function_exists('refl_param_entity')) {

    /**
     * @param ReflectionParameter $item
     * @param bool $no_types
     * @param bool $no_values
     * @return string
     */
    function refl_param_entity (\ReflectionParameter $item, $no_types = false, $no_values = false) {

        return \Bfg\Entity\Core\Entities\ParamEntity::buildFromReflection($item, $no_types, $no_values)->render();
    }
}

if (!function_exists('refl_params_entity')) {

    /**
     * @param array|\ReflectionParameter|\ReflectionFunction|\ReflectionMethod|\Closure $params
     * @param bool $no_types
     * @param bool $no_values
     * @return string
     */
    function refl_params_entity($params, $no_types = false, $no_values = false) {

        return \Bfg\Entity\Core\Entities\ParamEntity::buildFromReflection($params, $no_types, $no_values)->render();
    }
}