<template>
    <div>
        <header class="header">
            <a class="blog-title" v-on:click="toHome">{{ blogTitle }}</a>
            <div style="clear: both"></div>
            <input class="search-bar" placeholder="Search.."
                   ref="searchBar"
                   @click="selectSearchText"
                   @keyup.esc="resetSearch">
        </header>

        <section class="post-view">
            <div v-if="!content">loading..</div>
            <h1 class="post-title">
                {{ title }}
                <time class="post-date">{{date}}</time>
            </h1>
            <article v-if="content" v-html="htmlFromMarkdown"></article>
        </section>


        <footer class="footer">
            Copyright © {{ (new Date()).getFullYear() }} |
            Powered by <a href="https://github.com/zhaohehe/z-blog" target="_blank">z-blog</a>
        </footer>
    </div>
</template>

<style lang="stylus" src="./style/index.styl"></style>

<script>
  import apis from './api/index';
  import marked from './utils/render.js'
  export default {
    props: ['id'],
    data () {
      return {
        blogTitle: window.Laravel.blogTitle,
        date: null,
        title: '',
        content: ''
      }
    },

    computed: {
      htmlFromMarkdown () {
        return marked(this.content)
      }
    },

    created () {
          axios.get(apis.getPostApi + '/' + this.id).then(response => {
            console.log(response.data.data)
            var post = response.data.data
            this.date = post.date
            this.title = post.title
            this.content = post.content
        })
    },
    methods: {
      resetSearch () {
        this.keyword = ''
        this.$refs.searchBar.blur()
      },
      selectSearchText () {
        this.$refs.searchBar.select()
      },
      toHome () {
         location.href = '/'
      },
    }
  }
</script>