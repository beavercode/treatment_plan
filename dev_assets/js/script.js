
/*
done. 1. error messages for forms (change Forms.php library) isInvalid -> isValid, rid of html in php code
2. add / hide stages
To start:
a. max number of stages 5.
b. add stage on click
c. remove stage on click

@info
 http://stackoverflow.com/questions/8575081/jquery-create-and-remove-div-by-click
 http://community.sitepoint.com/t/how-to-dynamically-create-div-using-jquery/11513/3
 http://www.codeproject.com/Questions/532826/addplusnewplusdivpluswhileplusclickplusaplusbutton
 http://stackoverflow.com/questions/16321748/jquery-add-new-div-onclick

3. file uploads using dropzone.js

optional
n. ajax for forms
Idea:
1) on click send ajax request on specific route
2) server has counter, containing such request
3) if counter < max response with data(template with proper field names, e.g inputStage2, inputPeriod2, inputPrice2)
ps. what type of response? string, json, xml etc...
 */

(function () {
    $('.selectpicker').selectpicker({
        size: 7
    });
})();
