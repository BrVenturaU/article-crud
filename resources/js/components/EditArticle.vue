<template>
    <div class="row">
        <div class="col-12 col-sm-6 col-md-8 mx-auto">
            <h2>Editar articulo</h2>
            <form @submit.prevent="editArticle()" class="needs-validation" novalidate enctype="multipart/form-data">
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" id="name" name="name" class="form-control" required placeholder="Nombre..." v-model="article.name">
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
                <div class="form-group custom-file mb-3">
                    <label for="image" class="custom-file-label">{{article.name}}</label>
                    <input type="file" id="image" name="image" class="custom-file-input" lang="es" @change="onChangeImage($event)">
                    <span v-for="(error, index) of errors.image" :key="index" class="text-danger">{{error}}</span>
                </div>
                <button type="submit" class="btn btn-primary">Guardar</button>
                <img id="artImage" class="rounded mx-auto d-block" style="width:50%;height:50%;" :src="article.article_image">
                
            </form>
        </div>
    </div>    
</template>
<script>
export default {
    data() {
        return {
           article: {name:'', stock: 0, price:0.00, description:'', image:''},
           errors: {name:[], stock: [], price:[], description:[], image:[]},
           hasImageChange:false
        }
    },
    created: async function() {
        let vm = this;
        let id = vm.$route.params.id;
        let response = await vm.axios.get(`/articles/${id}`);
        vm.article = response.data;
    },
    methods: {
        onChangeImage(e){
            this.hasImageChange = true;
            this.article.image = e.target.files[0];
            let artImage = document.getElementById('artImage');
            let url = URL.createObjectURL(e.target.files[0]);
            artImage.src = url;
        },
        editArticle: async function(){
            let vm = this;
            let id = vm.$route.params.id;
            let data = new FormData();
            data.append("_method", 'PATCH');
            data.append('name', vm.article.name);
            data.append('description', vm.article.description);
            data.append('price', vm.article.price);
            data.append('stock', vm.article.stock);
            if(vm.hasImageChange)
                data.append('image', vm.article.image);
            try {
                let response = await vm.axios.post(`/articles/${id}`, data, {
                    headers: {
                    "Content-Type": "multipart/form-data"
                    }
                });
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