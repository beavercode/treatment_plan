#Form

##Problems
Work with forms:

 * submitted with different methods(GET, POST)
 * different data sources: inputs[text-like, file], select, text area, checkbox, option
 * handling file uploads
 * save form states to some data source(file, db, etc) (!)
 * no problems with form re-submit

##Clasess
 * Form
 * FormData 
 * FormValidation
 * FormFileUpload
 * FormState

###Form

###FormData
Used to store form data. Format is shared between classes which work with form.
####Properties
 * **id** - unique form identifier, base on some random parameter to escape from "time attacks" 
 * **name** - form name
 * **fields** - form's fields(input, textarea, select, checkbox) array in format:
        
        ['fieldName1' => 'value1', ..., 'fieldNameN' => 'valueN'], where
            fieldName - name of the field in request method array (GET, POST)
            value - field value; can be an array too
    
 * **files** - array of uploaded files related to form
 
        ['fileName1' => ['name' => 'price.docx', 'path' => 'path/where/file/is/stored', 'size' => 1440276188],
            ...,
         'fileNameN' => ['name' => 'price.docx', 'path' => 'path/where/file/is/stored', 'size' => 1440276188]], where
            fileName - name of the file in $_FILES
                name - upload file name
                path - place where file is stored
                size - size of the file
        
 * **errors** - array of form validation errors
 
        ['errorName1' => 'message1', ..., 'errorNameN' => 'messageN'], where
             errorName - name of the field where an error occurs
             message - error text
             
###FormValidation
Used for form validation. When an error occurs save "field=>message" to FormData::errors.

###FormFileUpload
File upload handling.

###FormState
Save form state(filled or partial filled fields, uploaded files, etc.) to some data source(file, db, etc).
####Methods
 * **save** - abstract method to store fields using serialization
 * **load** - abstract method to recover fields using deserialization  

##TODO
1. How to extend FormData class? Where i can store it? Example:
I Had ParsedFormData with additional property-array. It is stores parsed information, e.g. docx or excel file.
Code: 

    `ParsedFormData::parsedData['FileName' => ['k1' => 'v1', ..., 'kN' => 'vN']]`

    I can save this class to: 
    
     * ***app/src/lib*** - better for (extensibility)
     * ***app/vendor/packageName*** - better for (compatibility)
     