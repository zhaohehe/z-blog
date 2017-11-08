<html>
<head>
    {{--<link rel="stylesheet" type="text/css" href="https://simplemde.com/stylesheets/normalize.css" media="screen">--}}
    {{--<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:400,700">--}}
    {{--<link rel="stylesheet" type="text/css" href="https://simplemde.com/stylesheets/stylesheet.css" media="screen">--}}
    <link rel="stylesheet" type="text/css" href="{{url('css/mobi.min.css')}}" media="screen">
    <link rel="stylesheet" type="text/css" href="{{url('css/simplemde-theme-base.css')}}">


    <script src="{{url('js/simplemde.min.js')}}"></script>



</head>
<body>
<div>
    <textarea id="MyID"> </textarea>
</div>

<script type="application/javascript">


    var simplemde = new SimpleMDE({
        element: document.getElementById("MyID"),
        toolbar: [
            "bold", "italic", "heading", "|", "preview", "fullscreen", "image", "side-by-side",
            {
                name: "save",
                action: function save(editor) {
                    var content = editor.value();

                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.open("POST", "{{url('api/post')}}", true);
                    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
                    xmlhttp.setRequestHeader("X-CSRF-TOKEN","{{ csrf_token() }}");
                    xmlhttp.send("content=" + content);
                    xmlhttp.onreadystatechange = function()
                    {
                        if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
                        {
                            console.log(xmlhttp.responseText);
                        }
                    }

                },
                className: "fa fa-save",
                title: "save"
            },
            {
                name: "home",
                action: function save(editor) {
                    location.href = "{{url('/')}}"
                },
                className: "fa fa-home",
                title: "home"
            }
        ],
    });
    //    simplemde.toggleSideBySide();


</script>

</body>
</html>