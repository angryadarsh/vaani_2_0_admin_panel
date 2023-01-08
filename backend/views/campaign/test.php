
<form id="myForm" action="test" method="post"> 
    Name: <input type="text" name="name" /> 
    Comment: <textarea name="comment"></textarea> 
    <input type="submit" value="Submit Comment" /> 
</form>

<?php
$this->registerJsFile('
    script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js" integrity="sha384-qlmct0AOBiA2VPZkMY3+2WqkHtIQ9lSdAsAn5RUJD/3vA5MKDgSGcdmIv4ycVxyn" crossorigin="anonymous"></script
');
$this->registerJs("
    $(function() { 
        // bind 'myForm' and provide a simple callback function 
        $('#myForm').ajaxForm(function() { 
            alert('Thank you for your comment!'); 
        }); 
    }); 
");