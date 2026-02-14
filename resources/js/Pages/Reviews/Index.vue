<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'

const props = defineProps({
	reviews: Object,
	rating: [Number, String],
	reviews_count: Number,
	needs_setup: Boolean,
	sort: String,
})

const formatDate = (date) => {
	if (!date) return ''
	return new Date(date).toLocaleString('ru-RU', {
		day: '2-digit',
		month: '2-digit',
		year: 'numeric',
		hour: '2-digit',
		minute: '2-digit',
	})
}

const sync = () => {
	window.location.href = '/reviews/sync'
}

const changeSort = (newSort) => {
	router.get('/reviews', { sort: newSort }, { replace: true })
}
</script>

<template>
	<Head title="Отзывы" />

	<AuthenticatedLayout>
		<template #header>
			<h2 class="text-xl font-semibold">Отзывы компании</h2>
		</template>

		<div class="p-6 space-y-4">

			<div v-if="needs_setup"
			     class="bg-yellow-100 border-l-4 border-yellow-500 p-4 rounded text-yellow-700">
				<p class="font-bold">Внимание</p>
				<p>Сначала подключите площадку в разделе Settings.</p>
			</div>

			<div class="bg-white p-4 rounded shadow">
				<p><strong>Рейтинг:</strong> {{ rating ?? '—' }}</p>
				<p><strong>Количество отзывов:</strong> {{ reviews_count ?? 0 }}</p>

				<button
						type="button"
						@click="sync"
						:disabled="needs_setup"
						class="mt-3 px-4 py-2 rounded transition font-medium"
						:class="needs_setup
            ? 'bg-gray-400 cursor-not-allowed text-gray-200'
            : 'bg-blue-600 hover:bg-blue-700 text-white'"
				>
					Обновить отзывы
				</button>
			</div>

			<div v-if="reviews?.data?.length > 0" class="flex items-center space-x-2">
				<label class="font-medium text-gray-700">Сортировка:</label>
				<select
						:value="sort"
						@change="changeSort($event.target.value)"
						class="border-gray-300 rounded-md shadow-sm"
				>
					<option value="desc">Сначала новые</option>
					<option value="asc">Сначала старые</option>
				</select>
			</div>

			<div v-if="reviews?.data?.length > 0" class="space-y-3">
				<div
						v-for="review in reviews.data"
						:key="review.id"
						class="bg-white p-4 rounded shadow border border-gray-100"
				>
					<div class="flex justify-between">
						<p><strong>{{ review.author_name }}</strong></p>
						<span class="text-yellow-500 font-bold">{{ review.rating }}/5</span>
					</div>
					<p class="text-sm text-gray-500">
						{{ formatDate(review.published_at) }}
					</p>
					<p class="mt-2 text-gray-700">{{ review.text }}</p>
				</div>
			</div>

			<div v-else-if="!needs_setup"
			     class="text-center py-10 text-gray-500">
				Отзывов пока нет. Нажмите «Обновить отзывы».
			</div>

		</div>
	</AuthenticatedLayout>
</template>