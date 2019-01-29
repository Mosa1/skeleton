{
    "title": "{{$moduleName}}",
    "description": "",

    /* DB config*/
    "tableName": "{{strtolower(str_plural($moduleName))}}",
    "incrementField": "id",
    "useLaravelTimestamps": true,

    /* Field Configs used for: DB, Request, Tranformer, ReactJS */
    "fields": {
        "id": {
            "type": "number",
            "title": "id",
            "primaryKey" : true,
            "required" : true
        },
        "name": {
            "type": "string",
            "title": "Name",
            "unique": true,
            "required" : true
        }
    }
}