<script>
import AppLayout from '@/Layouts/AppLayout.vue';
export default {
    components: {
        AppLayout
    },
    props: {
        article: Object,
        images: Object,
    },
    articleEdit(article) {
        this.$inertia.visit(route('articles.edit', this.article.id));
    },
}
</script>

<template>
    <AppLayout title="Vue article">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestion d'un article
            </h1>
        </template>

        <div>
            <section>
                <h2>{{ article.title }}</h2>
                <p>{{ article.content }}</p>
                <p>{{ article.category }}</p>
                <p>{{ article.status }}</p>
                <img v-for="image in images" :src="image.path" :alt="image.description">
            </section>
            <button @click="articleEdit(article)">Modifier l'article</button>
        </div>
    </AppLayout>
</template>