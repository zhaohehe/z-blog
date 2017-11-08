<template>
    <div>
        <header class="header">
            <a class="blog-title" v-on:click="toHome">{{ title }}</a>
            <div style="clear: both"></div>
            <input class="search-bar" placeholder="Search.."
                   ref="searchBar" v-model="keyword"
                   @click="selectSearchText"
                   @keyup.esc="resetSearch">
        </header>

        <section class="list-view">
            <div v-if="!lists">loading..</div>
            <ol v-if="lists" class="list">
                <li v-for="post in lists" class="list-item">
                    <a class="item-title" :href="'post/' + post.id">
                        {{ post.title }}
                    </a>
                    <br>
                    <time class="item-date">{{post.date}}</time>
                    <edit v-if="auth" class="edit">
                        <a v-on:click="edit(post.id)">编辑</a>
                        <a v-on:click="dele(post.id)">删除</a>
                    </edit>
                </li>
            </ol>
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
  export default {
    props: ['auth'],
    data () {
      return {
        title: window.Laravel.blogTitle,
        keyword: '',
        lists: null
      }
    },
    created () {
        this.list()
    },
    computed: {
      postLink: function(id) {
        return id;
      }
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

      list () {
        axios.get(apis.getPostApi).then(response => {
            console.log(response.data.data)
            this.lists = response.data.data
        })
      },

      edit (id) {
        location.href = apis.getPost+ '/' + id + '/edit'
      },

      dele (id) {
        axios.delete(apis.getPostApi + '/' + id).then(response => {
            console.log(response.data.data)
            this.list()
        })
      }
    }
  }
</script>