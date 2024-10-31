<template>
	<div class="container margin_30">
        <div class="page_header">
            <div class="breadcrumbs">
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="/blogs">Blogs</a></li>
                </ul>
            </div>
            <div class="d-flex justify-content-center">
                <h1>Blog &amp; News</h1>
            </div>
        </div>
        <div v-if="!processing && posts.length" class="row">
            <div v-for="post in posts" :key="post.id" class="col-lg-4">
                <transition name="fade">
                <article class="blog">
                    <figure v-if="post.featured_image">
                        <a :href="post.url">
                            <img :src="post.featured_image" :alt="post.title">
                            <div class="preview"><span>Read more</span></div>
                        </a>
                    </figure>
                    <div class="post_info">
                        <small>{{ formattedDate(post.date) }}</small>
                        <h2><a :href="post.url">{{ post.title }}</a></h2>
                        <p>{{ formattedText(post.excerpt) }}</p>
                    </div>
                </article>
                </transition>
            </div>
            <div v-if="meta && meta.pagination && meta.pagination.total_pages > 1" class="pagination__wrapper no_border add_bottom_30">
                <pagination :pagination="meta.pagination" for="blogs"></pagination>
            </div>
        </div>
        <div v-else-if="!processing && !posts.length" class="row">
            <div class="col text-center">
                <p>No blogs found</p>
                <a class="btn_1" href="/" role="button">Go to homepage</a>
            </div>
        </div>
    </div>

</template>

<script>
	
import bus from '../assets/js/bus.js'
import settings from '../assets/js/settings.js'
import pagination from '../partials/common/pagination.vue'
var moment = require('moment');

export default {
    mixins:[settings],
    components: {
        pagination
    },
    data () {
        return {
            meta: null,
            posts: [],
            processing: true
        }
    },
    watch: {
            '$route.query' (query) {
                this.getBlogPosts(query.page);
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
                this.processing = false;
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
        formattedDate (date) {
            return moment(date).format("DD MMM. YYYY");
        },
        formattedText (content) {
            return  _.truncate(_.escape(content), {
                'length': 120
            });
        }
    },
	mounted () {

        this.getBlogPosts(1);
		
        bus.$on('blogs.switched-page', this.switchPage);

	}
}

</script>