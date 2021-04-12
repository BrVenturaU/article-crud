<template>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
            <div class="collapse navbar-collapse">
                <div class="navbar-nav">
                    <router-link to="/" class="nav-item nav-link">Lista de articulos</router-link>
                    <router-link to="/create" class="nav-item nav-link">Crear articulo</router-link>
                </div>
            </div>
        </nav>
        <router-view></router-view>
    </div>
</template>
<script>
export default {
    
}
</script>
<style scoped>

</style>