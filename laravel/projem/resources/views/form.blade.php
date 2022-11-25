<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Kitap dünyam</title>
    </head>
    <body>


<form action="{{route('sonuc')}}" method="POST">
@csrf
<textarea name="metin" ></textarea><br>
<input type="submit" name="ilet" value="gonder">
</form>

    </body>
</html>