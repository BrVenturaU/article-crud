import ArticleList from '../components/ArticleList.vue'
import CreateArticle from '../components/CreateArticle.vue'
import EditArticle from '../components/EditArticle.vue'
import ShowArticle from '../components/ShowArticle.vue'

export const routes = [
    {
        name:'home',
        path:'/',
        component: ArticleList
    },
    {
        name:'create',
        path:'/create',
        component: CreateArticle
    },
    {
        name: 'edit',
        path: '/edit/:id',
        component: EditArticle
    },
    {
        name:'show',
        path: '/show/:id',
        component: ShowArticle
    }
]