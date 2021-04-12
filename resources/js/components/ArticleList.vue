<template>
    <div class="row">
        <div class="col-12 col-sm-6 col-md-8 mx-auto">
            <h2>Lista de articulos</h2>
            <div class="custom-control custom-switch">
                <input class="custom-control-input" type="checkbox" name="order" id="order" v-model="order" @change="orderArticles()">
                <label class="custom-control-label" for="order">{{order == 1 ? 'Ascendente' : 'Descendente'}}</label>
            </div>
            <table class="table table-hover table-striped" v-if="articles.length > 0">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Estado</th>
                        <th>Imagen</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="article of articles" :key="article.id">
                        <td>{{article.id}}</td>
                        <td>{{article.name}}</td>
                        <td>${{article.price}}</td>
                        <td>{{article.stock}}</td>
                        <td>{{article.active    ? 'Activo' : 'Inactivo'}}</td>
                        <td>
                            <img class="table_image" :src="article.article_image" @click="openImage($event)">
                        </td>
                        <td>
                            <div class="btn-group">
                                <router-link :to="{name:'edit', params:{id: article.id}}" class="btn btn-success">
                                    <feather class="d-block" type="edit" size="20"></feather>
                                </router-link>
                                
                                <router-link :to="{name:'show', params:{id: article.id}}" class="btn btn-warning">
                                    <feather class="text-white d-block" type="eye" size="20"></feather>
                                </router-link>

                                <button class="btn btn-danger" :disabled="isDeleting" @click="deleteArticle(article.id)">
                                     <span v-if="isDeleting" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                     <feather class="d-block" v-if="!isDeleting" type="trash-2" size="20"></feather>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Estado</th>
                        <th colspan="2">Opciones</th>
                    </tr>
                </tfoot>
            </table>
            <nav v-if="articles.length > 0">
                <ul class="pagination">
                    <li class="page-item" v-if="pagination.current_page > 1">
                        <a class="page-link" href="#" aria-label="Previous" @click.prevent="changePage(pagination.current_page - 1)">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>

                    <li class="page-item" v-for="(page, index) of pagesNumber" v-bind:class="[page == isActive ? 'active' : '']" :key="index">
                        <a class="page-link" href="#" @click.prevent="changePage(page)">{{page}}</a>
                    </li>

                    <li class="page-item"  v-if="pagination.current_page < pagination.last_page">
                        <a class="page-link" href="#" aria-label="Next" @click.prevent="changePage(pagination.current_page + 1)">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="spinner-border d-block mx-auto" style="width: 3rem; height: 3rem;" role="status" v-else>
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
    
</template>
<script>
import ShowArticle from './ShowArticle.vue'
export default {
    components:{
        ShowArticle
    },
    data() {
        return {
            articles: [],
            isDeleting:false,
            order: true,
            pagination:{total:0, current_page:0, per_page:0, last_page:0, from:0, to:0},
            offset: 3
        }
    },
    created: async function() {
        await this.getArticles();
    },
    computed:{
        isActive(){
            return this.pagination.current_page;
        },
        pagesNumber(){
            if(!this.pagination.to)
                return [];

            var from = this.pagination.current_page - this.offset;
            if(from < 1)
                from = 1;
            var to = from + (this.offset*2);
            if(to >= this.pagination.last_page)
                to = this.pagination.last_page;

            var pages = [];
            while(from <= to){
                pages.push(from);
                from++;
            }
            return pages;
        }
    },
    methods: {
        openImage(e){
            window.open(e.target.src);
        },
        orderArticles: async function(){
            console.log(this.order)
            await this.getArticles(this.pagination.current_page);
        },
        getArticles: async function(page=1){
            let vm = this;
            let response = (await vm.axios.get(`/articles?page=${page}&order=${vm.order}`)).data;
            vm.pagination = {total:response.total, current_page:response.current_page, per_page:response.per_page, last_page:response.last_page, from:response.from, to:response.to};
            vm.articles = response.data;
        },
        changePage(page){
            this.pagination.current_page = page;
            this.getArticles(page);
        },
        deleteArticle: async function(id){
            let vm = this;
            vm.isDeleting = true;
            let response = await vm.axios.delete(`/articles/${id}`);
            let i = vm.articles.map(data => data.id).indexOf(id);
            vm.articles.splice(i, 1);
            alert(response.data);
            vm.isDeleting = false;
        }
    },
}
</script>
<style scoped>
    .table_image{
        cursor: pointer;
        width: 50px;
        height: 50px;
    }
</style>