<html>
<head>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/editor/0.1.0/editor.css">
    <script src="//cdn.jsdelivr.net/editor/0.1.0/editor.js"></script>
    <script src="//cdn.jsdelivr.net/editor/0.1.0/marked.js"></script>

    <script src="http://cdn.bootcss.com/vue/2.1.10/vue.js"></script>
    <script src="http://cdn.bootcss.com/vue-resource/1.1.0/vue-resource.min.js"></script>

</head>
<body>
<div id="app">
    <input class="title" id="title" type="text" placeholder="请输入文章标题" v-model="title" />
    <textarea id="editor"></textarea>
    <button v-on:click="save">保存</button>
</div>
</body>
<script type="application/javascript">
    Vue.http.interceptors.push((request, next)  => {
        request.headers.set('X-CSRF-TOKEN', '{{ csrf_token() }}')
        next();
    });

    var app = new Vue({
        el: '#app',
        editor: null,
        data: {
            title: '{{ $post['title'] }}',
        },
        mounted: function () {
            this.editor = new Editor({
                element: document.getElementById('editor'),

            })
            this.editor.render();

            //set content
            this.$http.get('{{url('api/post/'.$post['id'])}}').then(function (response)  {

                console.log(response.body.data.content)
                this.editor.codemirror.getDoc().setValue(response.body.data.content);
            }, function (response) {
                alert('保存失败')
            })
        },
        methods: {
            save: function()
            {

                var content = this.editor.codemirror.getValue()

                this.$http.put('{{url('api/post/'.$post['id'])}}', {'title':this.title,'content':content}).then(function (response)  {

                    location.href = '/post/' + '{{ $post['id'] }}'
                }, function (response) {
                    alert('保存失败')
                })
            }
        }
    })
</script>
<style>
    body{
        margin: 0px;
    }
    input.title {
        font: 18px "Helvetica Neue", "Xin Gothic", "Hiragino Sans GB", "WenQuanYi Micro Hei", "Microsoft YaHei", sans-serif;
        background: transparent;
        padding: 4px;
        height: 40px;
        width: 100%;
        border: none;
        outline: none;
        opacity: 0.6;
    }
</style>
<script type="application/javascript">

</script>
</html>

