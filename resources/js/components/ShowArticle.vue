<template>
    <div class="row">
        <div class="col-12 col-sm-6 mx-auto">
            <div class="card mb-3">
                <img :src="article.article_image" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">
                        {{article.name}}
                        <span class="p-2 badge badge-pill badge-dark">$ {{article.price}}</span>
                    </h5>
                    <p class="card-text">{{article.description}}</p>
                    <div class="d-flex justify-content-between">
                        <p class="card-text">Cantidad: <span class="p-2 badge badge-pill badge-primary">{{article.stock}}</span></p>

                        <div class="ml-auto custom-control custom-switch">
                            <input class="custom-control-input" type="checkbox" name="active" id="active" @change="changeStatus()" :value="article.active" :checked="article.active">
                            <label class="custom-control-label" for="active">{{article.active ? 'Activo' : 'Inactivo'}}</label>
                        </div>
                    </div>                            
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            article: {name:'', stock: 0, price:0.00, description:''},
        }
    },
    created: async function(){
        let id = this.$route.params.id;
        let response = await this.axios.get(`/articles/${id}`);
        this.article = response.data;
    },
    methods: {
        changeStatus: async function(){        
            try {
                let response = await this.axios.patch(`/articles/${this.article.id}/status`);
                this.article.active = response.data.value;
                setTimeout(()=>{
                    alert(response.data.message);
                }, 300);
            } catch (error) {
                let errors = error.response.data.errors;
                console.log(errors);
            }
        }
    },
}
</script>
<style scoped>

</style>