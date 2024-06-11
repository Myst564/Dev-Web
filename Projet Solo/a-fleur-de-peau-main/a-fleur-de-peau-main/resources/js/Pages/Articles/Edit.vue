<script>
import AppLayout from '@/Layouts/AppLayout.vue';
export default {
    components: {
        AppLayout
    },
    props: {
        article: Object,
    },
    articleEdit(article) {
        this.$inertia.visit(route('articles.edit', this.article.id));
    },
}
</script>

<template>
    <AppLayout title="Editer un article">
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                Editer un article
            </h1>
        </template>

        <div>
            <form action="{{ route('articles.update', $article) }}" method="post">
                @method('PUT')
                @csrf
                <div>
                    <label for="title">Titre</label>
                    <input type="text" name="title" id="title" value="article.name">
                </div>
                <div>
                    <label for="content">Description de l'article</label>
                    <textarea name="content" id="content">{{ article.content }}</textarea>
                </div>
                <div>
                    <label for="status">Statut de l'article</label>
                </div>
                <div>
                    <label for="image">Image</label>
                    <textarea name="image" id="image">{{ $article->image }}</textarea>
                </div>
                <button type="submit">Update</button>
            </form>
        </div>
    </AppLayout>
</template>