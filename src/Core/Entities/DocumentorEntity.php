<?php

namespace Bfg\Entity\Core\Entities;

use Bfg\Entity\Core\Entities\Helpers\DocumentorHelper;
use Bfg\Entity\Core\Entity;

/**
 * Class DocumentorEntity
 * @package Bfg\Entity\Core\Entities
 */
class DocumentorEntity extends Entity
{
    use DocumentorHelper;

    /**
     * Doc name
     *
     * @var null|string
     */
    protected $doc_name = null;

    /**
     * Doc description
     *
     * @var null|string
     */
    protected $doc_description = null;

    /**
     * Doc entity collection
     *
     * @var array
     */
    protected $docs = [];

    /**
     * Add doc name
     *
     * @param $name
     * @return $this
     */
    public function name($name)
    {
        $this->doc_name = $name;

        return $this;
    }

    /**
     * Add doc description
     *
     * @param $description
     * @return $this
     */
    public function description($description)
    {
        $this->doc_description = $description;

        return $this;
    }

    /**
     * The "api" tag is used to declare Structural Elements as being suitable for
     * consumption by third parties.
     *
     * @link http://docs.phpdoc.org/references/phpdoc/tags/api.html
     * @return $this
     */
    public function tagApi()
    {
        $this->docs["api"][] = "";

        return $this;
    }

    /**
     * Use the "abstract" tag to declare a class as abstract, as well as for declaring what methods must be redefined in a child class.
     *
     * @link https://manual.phpdoc.org/HTMLSmartyConverter/HandS/phpDocumentor/tutorial_tags.abstract.pkg.html
     * @return $this
     */
    public function tagAbstract()
    {
        $this->docs["abstract"][] = "";

        return $this;
    }

    /**
     * If "access" is private, the element will not be documented unless specified by command-line switch --parseprivate.
     *
     * @param $modifiers
     * @return $this
     */
    public function tagAccess($modifiers)
    {
        $this->docs["access"][] = is_array($modifiers) ? implode("|", $modifiers) : $modifiers;

        return $this;
    }

    /**
     * The "author" tag is used to document the author of Structural Elements.
     *
     * @link http://docs.phpdoc.org/references/phpdoc/tags/author.html
     * @param $name
     * @param null $email
     * @return $this
     */
    public function tagAuthor($name, $email = null)
    {
        $this->docs["author"][] = $name . ($email ? " <" . $email . ">" : "");

        return $this;
    }

    /**
     * The "copyright" tag is used to document the copyright information for Structural elements.
     *
     * @link http://docs.phpdoc.org/references/phpdoc/tags/copyright.html
     * @param string|int $year
     * @param string $copyright
     * @return $this
     */
    public function tagCopyright($year, $copyright = null)
    {
        $this->docs["copyright"][] = $year . ($copyright ? " " . $copyright : "");

        return $this;
    }

    /**
     * The "deprecated" tag is used to indicate which Structural elements are deprecated and
     * are to be removed in a future version.
     *
     * @link http://docs.phpdoc.org/references/phpdoc/tags/deprecated.html
     * @param $comment
     * @return $this
     */
    public function tagDeprecated($comment)
    {
        $this->docs["deprecated"][] = $comment;

        return $this;
    }

    /**
     * The "example" tag shows the code of a specified example file, or optionally, just a portion of it.
     *
     * @link http://docs.phpdoc.org/references/phpdoc/tags/example.html
     * @param $comment
     * @return $this
     */
    public function tagExample($comment)
    {
        $this->docs["example"][] = $comment;

        return $this;
    }

    /**
     * The "filesource" tag is used to tell phpDocumentor to include the source of the current
     * file in the parsing results.
     *
     * @link http://docs.phpdoc.org/references/phpdoc/tags/filesource.html
     * @return $this
     */
    public function tagFilesource()
    {
        $this->docs["filesource"][] = "";

        return $this;
    }

    /**
     * The "internal" tag is used to denote that associated Structural Elements are elements internal to
     * this application or library. It may also be used inside a long description to insert a piece of
     * text that is only applicable for the developers of this software.
     *
     * @link http://docs.phpdoc.org/references/phpdoc/tags/internal.html
     * @param $description
     * @return $this
     */
    public function tagInternal($description)
    {
        $this->docs["internal"][] = $description;

        return $this;
    }

    /**
     * The "license" tag is used to indicate which license is applicable for the associated Structural Elements.
     *
     * @link http://docs.phpdoc.org/references/phpdoc/tags/license.html
     * @param string $url
     * @param string $name
     * @return $this
     */
    public function tagLicense($url, $name = null)
    {
        $this->docs["license"][] = $url.($name ? " " . $name: "");

        return $this;
    }

    /**
     * The "method" allows a class to know which ‘magic’ methods are callable.
     *
     * @link http://docs.phpdoc.org/references/phpdoc/tags/method.html
     * @param $type
     * @param $method
     * @param null $description
     * @return $this
     */
    public function tagMethod($type, $method, $description = null)
    {
        $this->docs["method"][] = $type . " " . $method . ($description ? " " . $description : "");

        return $this;
    }

    /**
     * The "package" tag is used to categorize Structural Elements into logical subdivisions.
     *
     * @link http://docs.phpdoc.org/references/phpdoc/tags/package.html
     * @param $namespace
     * @return $this
     */
    public function tagPackage($namespace)
    {
        $this->docs["package"][] = $namespace;

        return $this;
    }

    /**
     * The "param" tag is used to document a single argument of a function or method.
     *
     * @link http://docs.phpdoc.org/references/phpdoc/tags/param.html
     * @param $type
     * @param $param
     * @param null $description
     * @return $this
     */
    public function tagParam($type, $param = "", $description = null)
    {
        if($type == "Closure" || preg_match('/^[^\\\\]([A-Za-z\\\\]+)\\\\([A-Za-z]+)$/', $type)) $type = "\\" . $type;

        $this->docs["param"][] = $type . (!empty($param) ? " $" . $param : "") . ($description ? " " . $description : "");

        return $this;
    }

    /**
     * The "property" tag allows a class to know which ‘magic’ properties are present.
     *
     * @link http://docs.phpdoc.org/references/phpdoc/tags/property.html
     * @param $type
     * @param $param
     * @param null $description
     * @return $this
     */
    public function tagProperty($type, $param = "", $description = null)
    {
        $this->docs["property"][] = $type . (!empty($param) ? " $" . $param : "") . ($description ? " " . $description : "");

        return $this;
    }

    /**
     * The "property-read" tag allows a class to know which ‘magic’ properties are present that are read-only.
     *
     * @link http://docs.phpdoc.org/references/phpdoc/tags/property-read.html
     * @param $type
     * @param $param
     * @param null $description
     * @return $this
     */
    public function tagPropertyRead($type, $param = "", $description = null)
    {
        $this->docs["property-read"][] = $type . (!empty($param) ? " $" . $param : "") . ($description ? " " . $description : "");

        return $this;
    }

    /**
     * The "property-write" tag allows a class to know which ‘magic’ properties are present that are write-only.
     *
     * @link http://docs.phpdoc.org/references/phpdoc/tags/property-write.html
     * @param $type
     * @param $param
     * @param null $description
     * @return $this
     */
    public function tagPropertyWrite($type, $param = "", $description = null)
    {
        $this->docs["property-write"][] = $type . (!empty($param) ? " $" . $param : "") . ($description ? " " . $description : "");

        return $this;
    }

    /**
     * The "return" tag is used to document the return value of functions or methods.
     *
     * @link http://docs.phpdoc.org/references/phpdoc/tags/return.html
     * @param $type
     * @param null $description
     * @return $this
     */
    public function tagReturn($type, $description = null)
    {
        $this->docs["return"][] = $type . ($description ? " " . $description: "");

        return $this;
    }

    /**
     * The "see" tag indicates a reference from the associated Structural Elements to a website or other Structural Elements.
     *
     * @link http://docs.phpdoc.org/references/phpdoc/tags/see.html
     * @param $subject
     * @param null $description
     * @return $this
     */
    public function tagSee($subject, $description = null)
    {
        $this->docs["see"][] = $subject . ($description ? " " . $description: "");

        return $this;
    }

    /**
     * The "since" tag indicates at which version did the associated Structural Elements became available.
     *
     * @link http://docs.phpdoc.org/references/phpdoc/tags/since.html
     * @param $version
     * @param null $description
     * @return $this
     */
    public function tagSince($version, $description = null)
    {
        $this->docs["since"][] = $version . ($description ? " " . $description: "");

        return $this;
    }

    /**
     * The "source" tag shows the source code of Structural Elements.
     *
     * @link http://docs.phpdoc.org/references/phpdoc/tags/source.html
     * @param $start_line
     * @param $numbers_of_lines
     * @param null $description
     * @return $this
     */
    public function tagSource($start_line, $numbers_of_lines, $description = null)
    {
        $this->docs["source"][] = $start_line . " " . $numbers_of_lines . ($description ? " " . $description: "");

        return $this;
    }

    /**
     * The "throws" tag is used to indicate whether Structural Elements could throw a specific type of exception.
     *
     * @link http://docs.phpdoc.org/references/phpdoc/tags/throws.html
     * @param $type
     * @param null $description
     * @return $this
     */
    public function tagThrows($type, $description = null)
    {
        $this->docs["throws"][] = $type . ($description ? " " . $description: "");

        return $this;
    }

    /**
     * The "todo" tag is used to indicate whether any development activities should still be executed on associated Structural Elements.
     *
     * @param $description
     * @return $this
     */
    public function tagTodo($description)
    {
        $this->docs["todo"][] = $description;

        return $this;
    }

    /**
     * The "uses" tag indicates a reference to (and from) a single associated Structural Elements.
     *
     * @link http://docs.phpdoc.org/references/phpdoc/tags/uses.html
     * @param $fqsen
     * @param null $description
     * @return $this
     */
    public function tagUses($fqsen, $description = null)
    {
        $this->docs["uses"][] = $fqsen . ($description ? " " . $description: "");

        return $this;
    }

    /**
     * The "uses" tag indicates a reference to (and from) a single associated Structural Elements.
     *
     * @link http://docs.phpdoc.org/references/phpdoc/tags/uses.html
     * @param $fqsen
     * @param null $description
     * @return $this
     */
    public function tagUsesBy($fqsen, $description = null)
    {
        $this->docs["uses-by"][] = $fqsen . ($description ? " " . $description: "");

        return $this;
    }

    /**
     * You may use the "var" tag to document the “Type” of properties, sometimes called class variables.
     *
     * @link http://docs.phpdoc.org/references/phpdoc/tags/var.html
     * @param $type
     * @param null|string $param
     * @param null|string $description
     * @return $this
     */
    public function tagVar($type, $param = null, $description = null)
    {
        $this->docs["var"][] = $type . ($param ? " $" . $param : "") . ($description ? " " . $description : "");

        return $this;
    }

    /**
     * The "version" tag indicates the current version of Structural Elements.
     *
     * @link http://docs.phpdoc.org/references/phpdoc/tags/version.html
     * @param $vector
     * @param null $description
     * @return $this
     */
    public function tagVersion($vector, $description = null)
    {
        $this->docs["version"][] = $vector . ($description ? " " . $description: "");

        return $this;
    }

    /**
     * Add custom tag
     *
     * @param $tag_name
     * @param null $tag_data
     * @return $this
     */
    public function tagCustom($tag_name, $tag_data = null)
    {
        $this->docs[$tag_name][] = $tag_data ? $tag_data : "";

        return $this;
    }

    /**
     * @return $this
     */
    public function throwException()
    {
        $this->tagThrows("\Exception");

        return $this;
    }

    /**
     * Magic call
     *
     * @param $name
     * @param $arguments
     * @return $this
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        if (preg_match('/^tag([A-Z][A-Za-z\_]+)$/', $name, $m)) {

            return $this->tagCustom(strtolower($m[1]), implode(" ", $arguments));
        }

        return $this;
    }

    /**
     * Build entity
     *
     * @return string
     */
    protected function build(): string
    {
        $spaces = $this->space();
        $begin = $spaces." * ";
        $data = $spaces."/**" . $this->eol();

        if ($this->doc_name) {

            $data .= $begin . $this->doc_name . $this->eol();
        }

        if ($this->doc_description) {

            $data .= $begin . $this->doc_description . $this->eol();
        }

        foreach ($this->docs as $tag => $docers) {

            foreach ($docers as $docer) {

                $data .= $begin . "@" . $tag . " " . $docer . $this->eol();
            }
        }
        $data .= $spaces." */";

        return $data;
    }
}
