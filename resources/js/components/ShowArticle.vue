<template>
    <div class="modal fade" :id="`${articleName}${article.id}`" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
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
                                    <input class="custom-control-input" type="checkbox" name="active" id="active" @change="changeStatus()" v-model="article.active">
                                    <label class="custom-control-label" for="active">{{article.active ? 'Activo' : 'Inactivo'}}</label>
                                </div>
                            </div>                            
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props:{
        articleId: Number,
        articleName: String
    },
    created: async function() {
        let vm = this;
        let response = await vm.axios.get(`/articles/${this.articleId}`);
        vm.article = response.data;
    },
    data() {
        return {
            article:{},
        }
    },
    methods: {
        changeStatus: async function(){
            
            let vm = this;
            
            console.log(vm.article);
            try {
                let response = await vm.axios.patch(`/articles/${vm.article.id}/status`, {
                    active: !vm.article.active
                });
                alert(response.data);
            } catch (error) {
                let errors = error.response.data.errors;
                console.log(errors);
            }
            this.$emit('changeStatus', this.article.active);
        }
    },
}
</script>
<style scoped>

</style>