
# cake-debug-query

### Debug Query in CakePHP

Dependency for this debugQuery($formatSQL = true) method jdorn/sql-formatter can be found https://github.com/jdorn/sql-formatter

Place the debugQuery($formatSQL = true) method as follows inside file /vendor/cakephp/cakephp/src/Database/Query.php

```
<!-- language: php -->
public function debugQuery($formatSQL = true): string  
{  
	  $query = $this->sql();  
	  $placeholderArray = $this->getValueBinder()->bindings();  
	  if (null != $placeholderArray) {  
		  foreach ($placeholderArray as $placeholder => $paramArray) {  
			  switch ($paramArray['type']) {  
				  case 'json' :  
					  $QueryParam[substr($placeholder, 1)] = '\'' . json_encode($paramArray['value'], JSON_NUMERIC_CHECK) . '\'';  
					  break; case 'boolean':  
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
```
`

###### SQL with Formatted view Within Controller

```
<!-- language: php -->
$this->loadModel('Model);  
print $this->Model  
		 ->find()
		 ->select(["select_column"])
		 ->where(["user_id" => 1])
		 ->debugQuery()
```  
[output]
```
SELECT  
    Panels.panel AS Panels__panel,  
    Panels.element AS Panels__element  
FROM  
    panels Panels  
```

###### SQL without Formatting view Within Controller
```
<!-- language: php -->
$this->loadModel('Model);  
$this->Model
	 ->find()
	 ->select(["select_column"])
	 ->where(["user_id" => 1])
	 ->debugQuery(false)
```  

[output]
```
SELECT Panels.panel AS Panels__panel, Panels.element AS Panels__element FROM panels Panels  
```
[Note]  
While using dd() in web, Logging(i.e. \Cake\Log\Log::debug()) and CLI for debugging purpose use formatSQL flag false as follows,  
otherwise debugQuery returns HTML which you can't see the SQL in readable format.

dd($this->Model->find()->where()->debugQuery(false));
