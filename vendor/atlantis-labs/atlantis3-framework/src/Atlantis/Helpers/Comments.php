<?php

namespace Atlantis\Helpers;

class Comments
{

    private $commentsClass;

    public function __construct($commentsClassNamespace)
    {

        if (class_exists($commentsClassNamespace))
        {

            $this->editorClass = new $commentsClassNamespace();

            $this->editor = $this->editorClass->build();

            $this->checkImplementedInterface($commentsClassNamespace, Interfaces\CommentsBuilderInterface::class);

        }
    }

    public function create()
    {

        if ($this->editorClass != NULL)
        {
            return $this->build;
        }
    }


    private function checkImplementedInterface($class, $interfaceClass)
    {

        $correct = FALSE;

        foreach (class_implements($class) as $implements)
        {

            if ($implements == $interfaceClass)
            {
                $correct = TRUE;
            }
        }

        if (!$correct)
        {
            abort(404, 'Interface ' . $interfaceClass . ' not found in class ' . $class);
        }
    }

}
