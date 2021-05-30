<?php
/**
 * Cakephp Query debugger
 * @return string
 * @author Karthikeyan C <karthikn.mca@gmail.com>
 *
 * @param bool $formatSQL true(Only Web Interface) / false (Plain Text - useful for dd(), CLI, \Cake\Log\Log::debug())
 * @return string (Query)
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
