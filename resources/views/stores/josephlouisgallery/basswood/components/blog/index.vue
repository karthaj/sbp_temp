<template>
	
<div class="blog-collections pt-50">
    <div class="col-12 text-center pb-60">
        <div class="section-title">
            <h2>OUR BLOG</h2>
        </div>
    </div>
    <div v-if="!loading" class="d-flex row col-12 justify-content-center">
        <div v-for="post in posts" :key="post.id"  class="col-md-6 pb-50">
            <div class="single-blog">
                <div class="blog-info">
                    <div class="blog-date-time">
                        <span class="blog-month">{{ getMonth(post.date) }}</span>
                        <span class="blog-date">{{ getDate(post.date) }}</span>
                    </div>
                    <div class="single-blog-content">
                        <div class="blog-title">
                           <h4><a :href="post.url">{{ post.title }}</a></h4>
                        </div>
                        <div class="blog-meta">
                            <ul>
                                <li><i class="fa fa-calendar"></i>{{ getFormattedDate(post.date) }}</li>
                                <li>-</li>
                                <li><i class="fa fa-user"></i>{{ post.author }}</li>
                            </ul>
                        </div>
                        <p class="blog-description text-truncate" v-html="post.content"></p>
                        <a class="read-btn" :href="post.url"> Read More </a>
                    </div>
                </div>
            </div>
        </div>          
    </div>
    <p v-else-if="!loading && posts.length === 0" class="text-center my-5">No blogs found.</p>
    <div v-else-if="loading" class="pt-50 pb-50 vld-parent">
        <loading :active.sync="loading" 
        :is-full-page="false"
        loader="dots"
        ></loading>
    </div>
    <pagination v-if="meta && meta.pagination" :pagination="meta.pagination" for="blogs" :showTotal="false"></pagination>
</div>

</template>

<script>
	
import bus from '../../assets/js/bus'
import Loading from 'vue-loading-overlay';
import Pagination from '../../partials/pagination/pagination'

export default {
    components: {
        Loading,
        Pagination
    },
    data () {
        return {
            months: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
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

            var date = new Date(date);

            return date.getDate();
        },
        getMonth (date) {

            var date = new Date(date);
   
            return this.months[date.getMonth()];
        },
        getFormattedDate (date) {

            var date = new Date(date);
   
            return this.months[date.getMonth()] + ' ' + date.getDate() + ', ' + date.getFullYear();
        }
    },
	mounted () {

        this.getBlogPosts(1);
		
        bus.$on('blogs.switched-page', this.switchPage);

	}
}

</script>