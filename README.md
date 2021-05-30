# cake-debug-query

### Debug Query in CakePHP
This is a development support code and not the complete package.
The method in the Query.php under src folder can be copied to /vendor/cakephp/cakephp/src/Database/Query.php Class.

### Note
If There is a new update from CakePHP Database Query then the changes you made will be lost.

### Dependency
Make sure the dependency for this debugQuery($formatSQL = true) method jdorn/sql-formatter is available in vendor.
You can install this dependency using this link https://github.com/jdorn/sql-formatter.

### How to use in code?
Place the debugQuery($formatSQL = true) method as follows inside file 

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
