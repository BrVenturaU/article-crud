<template>
    <div class="row">
        <div class="col-12 col-sm-6 col-md-8 mx-auto">
            <h2>Crear articulo</h2>
            <form @submit.prevent="addArticle()" class="needs-validation" novalidate enctype="multipart/form-data">
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
                    <label for="image">Imagen</label>
                    <input type="file" id="image" name="image" class="form-control" @change="onChangeImage($event)">
                    <span v-for="(error, index) of errors.image" :key="index" class="text-danger">{{error}}</span>
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
           article: {name:'', stock: 0, price:0.00, description:'', image:''},
           errors: {name:[], stock: [], price:[], description:[], image:[]} 
        }
    },
    methods: {
        onChangeImage(e){
            this.article.image = e.target.files[0];
        },
        addArticle: async function(){
            let vm = this;
            let data = new FormData();
            for (const key in vm.article) {
                const element = vm.article[key];
                if(key == 'price')
                    element = parseFloat(element);
                if(key == 'stock')
                    element = parseInt(element);
                data.append(key, element);
            }
            try {
                let response = await vm.axios.post('/articles', data);
                alert(response.data);
                vm.$router.push({name: 'home'});
                
            } catch (error) {
                let errors = error.response.data.errors;
                console.log(errors)
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
                            case 'image':
                                vm.errors.image=element;
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