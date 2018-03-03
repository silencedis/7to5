<?php

namespace Spatie\Php7to5\PrettyPrinter;

use PhpParser\PrettyPrinter\Standard;
use PhpParser\Node\Stmt;

/**
 * Class StandardX
 *
 * @author Yurii Slobodeniuk <silencedis@gmail.com>
 */
class StandardX extends Standard
{
    protected function preprocessNodes(array $nodes)
    {
        /* We can use semicolon-namespaces unless there is a global namespace declaration */
        $this->canUseSemicolonNamespaces = true;
        foreach ($nodes as $node) {
            if ($node instanceof Stmt\Namespace_ && null === $node->name) {
                $this->canUseSemicolonNamespaces = false;
            }
            if ($node instanceof Stmt\Namespace_) {
                if (is_array($node->stmts)) {
                    foreach ($node->stmts as &$namespaceStmt) {
                        if (!$namespaceStmt instanceof Stmt\Class_ || !is_array($namespaceStmt->stmts)) {
                            continue;
                        }
                        $classStmts = &$namespaceStmt->stmts;
                        foreach ($classStmts as &$classStmt) {
                            if (!$classStmt instanceof Stmt\ClassMethod || !is_array($classStmt->stmts)) {
                                continue;
                            }
                            $methodStmts = &$classStmt->stmts;
                            foreach ($methodStmts as $key => $methodStmt) {
                                unset($methodStmts[$key]);
                            }
                        }
                    }
                }
            }
        }
        $test = null;
    }
}

