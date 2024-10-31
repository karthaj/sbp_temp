<template>
	
<section id="b-four_columns">
    <div v-if="!loading && posts.length" class="b-blog b-blog_grid text-center b-blog_grid_three my-5">
      <div class="container">
          <div class="row clearfix js-masonry">
              <div v-for="post in posts" :key="post.id" class="col-xl-4 col-lg-4 col-mb-4 col-sm-6 col-xs-12">
                  <div class="b-blog_grid_single">
                    <div class="b-blog_single_header">
                        <div class="b-blog_img_wrap">
                            <a :href="post.url">
                                <img v-if="post.featured_image" :src="post.featured_image" class="img-fluid d-block" :alt="post.title">
                                <img v-else :src="`https://via.placeholder.com/332x203?text=${post.title}`" class="img-fluid d-block" :alt="post.title">
                            </a>
                        </div>
                        <div class="b-post_time">
                            <span class="b-post_day">{{ getDate(post.date) }}</span>
                            <span class="b-post_month">{{ getMonth(post.date) }}</span>
                        </div>
                    </div>  
                    <div class="b-blog_single_info">
                        <h3 class="b-entry_title">
                            <a :href="post.url" rel="bookmark">{{ post.title }}</a>
                        </h3>
                        <div class="b-author_info">
                            <span class="b-author_name">
                                By {{ post.author }}
                            </span>
                        </div>
                        <div class="b-blog_single_content">
                            <p  class="text-truncate" v-html="post.content"></p>
                        </div>
                        <div class="b-read_more mt-3 text-center">
                            <a :href="post.url">Read More</a>
                        </div>
                    </div>
                  </div>
              </div>
          </div> 
      </div>
      <pagination v-if="meta" :pagination="meta.pagination" for="blogs"></pagination>
    </div>

    <p v-else-if="!loading && posts.length === 0" class="text-center my-5">No blog posts found.</p>
    
    <div v-else-if="loading" class="pt-50 pb-50 text-center vld-parent">
        <loading :active.sync="loading" 
        :is-full-page="false"
        loader="dots"
        ></loading>
    </div>

</section>

</template>

<script>
	
import bus from '../assets/bus'
import Loading from 'vue-loading-overlay';
import Pagination from '../partials/pagination'
var moment = require('moment');

export default {
    components: {
        Loading,
        Pagination
    },
    data () {
        return {
            meta: null,
            posts: [],
            loading: true
        }
    },
    methods: {
        getBlogPosts (page) {
            axios.get('/api/blogs', {
                params: {
                    page,
                }                   
            }).then((response) => { 
                this.posts = response.data.data;
                this.meta = response.data.meta;
                this.loading = false;
            })
        },
        switchPage(page) {
            this.$router.replace({
                name: 'blogs.index',
                query: {
                    page
                }
            })
        },
        getDate (date) {
            return moment(date).format("DD");
        },
        getMonth (date) {
            return moment(date).format("MMM");
        }
    },
	mounted () {

        this.getBlogPosts(1);
		
        bus.$on('blogs.switched-page', this.switchPage);

	}
}

</script>