{
    "title": "{{$moduleName}}",
    "description": "",

    /* DB config*/
    "tableName": "{{strtolower($moduleName)}}",
    "incrementField": "id",
    "useLaravelTimestamps": true,
    "fillable": ["name"],
    "indexPlugin": [
        {
            "pluginName": "dataTable",
            "setStatus" : true,
            "addBtn"    : { "text": "add new {{$moduleName}}" },
            "cols"      : [
                { "name" : "Name","value" : "name"},
            ]
        }
    ],

    /* Field Configs used for: DB, Request, Tranformer, ReactJS */
    "fields": {
        "id": {
            "type": "number",
            "title": "id",
            "primaryKey" : true,
            "required" : true
        },
        "name": {
            "pluginName": "textField",
            "type": "string",
            "title": "Name",
            "unique": true,
            "required" : true
        }
    }
}