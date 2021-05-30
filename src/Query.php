<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         3.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License

namespace Cake\Database;

use Cake\Database\Exception\DatabaseException;
use Cake\Database\Expression\CommonTableExpression;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Database\Expression\OrderByExpression;
use Cake\Database\Expression\OrderClauseExpression;
use Cake\Database\Expression\QueryExpression;
use Cake\Database\Expression\ValuesExpression;
use Cake\Database\Expression\WindowExpression;
use Cake\Database\Statement\CallbackStatement;
use Closure;
use InvalidArgumentException;
use IteratorAggregate;
use RuntimeException;

 * This class represents a Relational database SQL Query. A query can be of
 * different types like select, update, insert and delete. Exposes the methods
 * for dynamically constructing each query part, execute it and transform it
 * to a specific SQL dialect.
 */
class Query //implements ExpressionInterface, IteratorAggregate
{
    /**
     * Cakephp Query debugger
     * @param bool $formatSQL true(Only Web Interface) / false (Plain Text - useful for dd(), CLI, \Cake\Log\Log::debug())
     * @return string
     * @return string (Query)
     * @author Karthikeyan C <karthikn.mca@gmail.com>
     *
     */
    public function debugQuery($formatSQL = true): string
    {
        $query = $this->sql();
        $placeholderArray = $this->getValueBinder()->bindings();
        if (null != $placeholderArray) {
            foreach ($placeholderArray as $placeholder => $paramArray) {
                switch ($paramArray['type']) {
                    case 'json' :
                        $QueryParam[substr($placeholder, 1)] = '\'' . json_encode($paramArray['value'], JSON_NUMERIC_CHECK) . '\'';
                        break;
                    case 'boolean':
                    case 'integer':
                        $QueryParam[substr($placeholder, 1)] = $paramArray['value'];
                        break;
                    case 'string':
                    case 'text' :
                    default :
                        $QueryParam[substr($placeholder, 1)] = "'{$paramArray['value']}'";
                        break;
                }
            }
        }
        $outputQuery = $query;
        if (!empty($QueryParam)) {
            $outputQuery = \Cake\Utility\Text::insert($query, $QueryParam);
        }
        return $formatSQL ? \SqlFormatter::format($outputQuery) : $outputQuery;
    }
}
