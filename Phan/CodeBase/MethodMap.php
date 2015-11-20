<?php declare(strict_types=1);
namespace Phan\CodeBase;

use \Phan\Language\Element\Method;
use \Phan\Language\FQSEN;

/**
 * Information pertaining to PHP code files that we've read
 */
trait MethodMap {

    /**
     * Implementing classes must support a mechanism for
     * getting a File by its path
     */
    abstract function getFileByPath(string $file_path) : File;

    /**
     * @var Method[]
     * A map from fqsen string to the method it
     * represents
     */
    private $method_map = [];

    /**
     * @return Method[]
     * A map from FQSEN strings to the method it
     * represents for all known methods.
     */
    public function getMethodMap() : array {
        return $this->method_map;
    }

    /**
     * @param Method[] $method_map
     * A map from FQSEN strings to the method it
     * represents for all known methods.
     */
    private function setMethodMap(array $method_map) {
        $this->method_map = $method_map;
    }

    /**
     * @param Method $method
     * A method to add to the code base
     */
    private function addMethodCommon(Method $method) {
        $this->method_map[(string)$method->getFQSEN()] = $method;

        if (!$method->getContext()->isInternal()) {
            $this->getFileByPath($method->getContext()->getFile())
                ->addMethodFQSEN($method->getFQSEN());
        }
    }

    /**
     * @param Method $method
     * A method to add to the code base
     */
    public function addMethod(Method $method) {
        $this->addMethodCommon($method);
    }

    /**
     * @param Method $method
     * A method to add to the code base
     */
    public function addFunction(Method $method) {
        $this->addMethodCommon($method);
    }


    /**
     * @param Method $method
     * A method to add to the code base
     */
    public function addClosure(Method $method) {
        $this->addMethodCommon($method);
    }

    /**
     * @return bool
     * True if a method exists with the given FQSEN
     */
    public function hasMethodWithFQSEN(FQSEN $fqsen) : bool {
        return !empty($this->method_map[(string)$fqsen]);
    }

    /**
     * @return Method
     * Get the method with the given FQSEN
     */
    public function getMethodByFQSEN(FQSEN $fqsen) : Method {
        return $this->method_map[(string)$fqsen];
    }

}