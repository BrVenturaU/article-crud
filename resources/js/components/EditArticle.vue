<template>
    <div class="row">
        <div class="col-12 col-sm-6 col-md-8 mx-auto">
            <h2>Editar articulo</h2>
            <form @submit.prevent="editArticle()" class="needs-validation" novalidate>
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" id="name" class="form-control" required placeholder="Nombre..." v-model="article.name">
                    <span v-for="(error, index) of errors.name" :key="index" class="text-danger">{{error}}</span>
                    
                </div>
                <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="number" id="stock" name="stock" class="form-control" min="0" required placeholder="0" v-model="article.stock">
                    <span v-for="(error, index)  of errors.stock" :key="index" class="text-danger">{{error}}</span>
                </div>
                <div class="form-group">
                    <label for="price">Precio</label>
                    <input type="number" id="price" name="price" class="form-control" min="0" step="0.01" required placeholder="$0.00" v-model="article.price">
                    <span v-for="(error, index) of errors.price" :key="index" class="text-danger">{{error}}</span>
                </div>
                <div class="form-group">
                    <label for="description">Descripción</label>
                    <textarea name="description" id="description" class="form-control" cols="30" rows="5" required placeholder="Descripción..." v-model="article.description">

                    </textarea>
                    <span v-for="(error, index) of errors.description" :key="index" class="text-danger">{{error}}</span>
                </div>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>
        </div>
    </div>    
</template>
<script>
export default {
    data() {
        return {
           article: {name:'', stock: 0, price:0.00, description:''},
           errors: {name:[], stock: [], price:[], description:[]} 
        }
    },
    created: async function() {
        let vm = this;
        let id = vm.$route.params.id;
        let response = await vm.axios.get(`/articles/${id}`);
        vm.article = response.data;
    },
    methods: {
        editArticle: async function(){
            let vm = this;
            let id = vm.$route.params.id;
            try {
                let response = await vm.axios.patch(`/articles/${id}`, {
                    name: vm.article.name,
                    description: vm.article.description,
                    stock: parseInt(vm.article.stock),
                    price: parseFloat(vm.article.price)
                });
                alert(response.data);
                vm.$router.push({name: 'home'});
                
            } catch (error) {
                let errors = error.response.data.errors;
                for (const key in errors) {
                    if (Object.hasOwnProperty.call(errors, key)) {
                        const element = errors[key];
                        switch (key) {
                            case 'name':
                                vm.errors.name=element;
                                break;
                            case 'description':
                                vm.errors.description=element;
                                break;
                            case 'price':
                                vm.errors.price=element;
                                break;
                            case 'stock':
                                vm.errors.stock=element;
                                break;
                            default:
                                break;
                        }
                    }
                }
            }
        }
    },
}
</script>
<style scoped>

</style>