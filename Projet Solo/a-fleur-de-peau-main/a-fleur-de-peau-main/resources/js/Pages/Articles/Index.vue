<script>
import AppLayout from '@/Layouts/AppLayout.vue';
export default {
    components: {
        AppLayout
    },
    props: {
        articles: Object,
        images: Object,
    },
    articleCreate() {
        this.$inertia.visit(route('articles.create'));
    },
    articleVisit(article) {
        this.$inertia.visit(route('articles.show', this.article.id));
    },
}
</script>

<template>
    <AppLayout title="Index">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestion des articles
            </h1>
        </template>

        <div>
            <button class="bg-[#00353F] text-slate-100 rounded-md" @click="articleCreate">Créer un nouvel article</button>

            <h2>Articles existants :</h2>
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Contenu</th>
                        <th>Catégorie</th>
                        <th>Statut</th>
                        <th>Images</th>
                    </tr>
                </thead>

                <tr v-for="article in articles" class="p-6">
                    <td>{{ article.title }}</td>
                    <td>{{ article.content }}</td>
                    <td>{{ article.category }}</td>
                    <td>{{ article.status }}</td>
                    <td>
                        <img v-for="image in images" class="max-w-sm" :src="image.path" :alt="image.description">
                    </td>
                    <td><button @click="articleVisit(article)">Modifier l'article</button></td>
                    <td>Archiver</td>
                </tr>
                
            </table>

            <button class="bg-[#00353F] text-slate-100 rounded-md"><a href="{{ route('articles.create') }}">Créer un nouvel article</a></button>
            <p class="underline"><a href="{{ route('archived.index') }}">Publications archivées</a></p>
        </div>
    </AppLayout>
</template>